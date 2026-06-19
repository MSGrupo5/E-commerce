<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SinContenidoOfensivo implements ValidationRule
{
    // Palabras que como token aislado son inequívocamente ofensivas en cualquier contexto
    private static array $tokens = [
        'homofobia' => [
            'puto', 'trolo', 'trola', 'tortillera', 'maricón', 'maricon', 'marica',
            'faggot', 'dyke', 'fag', 'camiona',
        ],
        'racismo' => [
            'nigger', 'nigga', 'kike', 'spic', 'wetback', 'chink', 'gook', 'beaner',
        ],
        'ableismo' => [
            'mogolico', 'mogólico', 'mongolito', 'mongoloide',
        ],
        'vulgaridad' => [
            'puta', 'hdp', 'ctm', 'fdp',
        ],
    ];

    // Frases con colapso de espacios → captura variantes pegadas
    // Ej: "negro de mierda" == "negro demierda" == "negrodemierda"
    private static array $frases = [
        'racismo' => [
            'negro de mierda',
            'muerte a los negros', 'muerte a los judios', 'muerte a los inmigrantes',
            'limpieza racial', 'raza superior', 'raza inferior',
            'mata negros', 'mata judios', 'judio de mierda',
        ],
        'xenofobia' => [
            // Discriminación por origen / clase social — muy común en Argentina
            'villero de mierda', 'negro villero', 'cabecita negra', 'negro de villa',
            'bolita de mierda',   // Bolivia
            'paragua de mierda',  // Paraguay
            'peruano de mierda',  // Perú
            'chilote de mierda',  // Chile
            'sudaca de mierda',
            'inmigrante de mierda',
        ],
        'homofobia' => [
            'muerte a los gays', 'muerte a los putos', 'muerte a los homosexuales',
            'muerte a los trans', 'los gays son enfermos', 'los trans son enfermos',
            'sos un puto', 'sos una trola',
        ],
        'nazismo' => [
            'heil hitler', 'sieg heil', 'ku klux klan', 'kkk power',
            'white power', 'white pride', 'white supremacy',
            'muerte a los judios', 'el holocausto fue mentira',
            'gas the jews', 'nazi power',
        ],
        'vulgaridad' => [
            // Insultos argentinos / español rioplatense
            'hijo de puta', 'hijo de una puta', 'hija de puta',
            'hijos de puta', 'hijas de puta', 'hijo de mil putas',
            'concha de tu madre', 'concha de su madre',
            'la concha de tu madre', 'la concha de su madre',
            'concha tu madre', 'reconcha de tu madre',
            'la puta que te pario', 'puta que te pario',
            'la puta que lo pario', 'andate a la mierda',
            'vete a la mierda', 'anda a la mierda',
            'la puta madre', 'puta que me pario',
            'pelotudo de mierda', 'pelotuda de mierda',
            'boludo de mierda', 'tarado de mierda',
            'imbecil de mierda', 'garca de mierda',
        ],
        'ableismo' => [
            'retrasado mental', 'retrasada mental',
            'deficiente mental', 'mogolico de mierda',
            'mongoloide de mierda',
        ],
    ];

    private static array $mensajes = [
        'racismo'    => 'El contenido incluye lenguaje racista o de odio racial, lo cual no está permitido en Marketo.',
        'xenofobia'  => 'El contenido incluye lenguaje discriminatorio hacia personas de otras nacionalidades o grupos sociales, lo cual no está permitido en Marketo.',
        'homofobia'  => 'El contenido incluye lenguaje homofóbico o discriminatorio hacia la comunidad LGBTQ+, lo cual no está permitido en Marketo.',
        'nazismo'    => 'El contenido hace referencia a ideologías de odio o extremismo (nazismo, supremacismo), lo cual no está permitido en Marketo.',
        'vulgaridad' => 'El contenido incluye lenguaje vulgar u obsceno, lo cual no está permitido en Marketo.',
        'ableismo'   => 'El contenido incluye lenguaje discriminatorio hacia personas con discapacidad, lo cual no está permitido en Marketo.',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $texto  = (string) $value;
        $lower  = mb_strtolower(trim($texto));
        $sinEsp = preg_replace('/\s+/', '', $lower);

        // — Capa 1a: tokens individuales —
        // Separa por espacios y puntuación → "hdp", "puta", "mogolico", etc.
        $palabras = preg_split('/[\s\p{P}]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY);

        foreach (self::$tokens as $categoria => $lista) {
            foreach ($lista as $term) {
                if (in_array(mb_strtolower($term), $palabras, true)) {
                    $fail(self::$mensajes[$categoria]);
                    return;
                }
            }
        }

        // — Capa 1b: frases (con y sin espacios) —
        // "hijo de puta" == "hijodeputa" == "hijo deputa"
        foreach (self::$frases as $categoria => $lista) {
            foreach ($lista as $frase) {
                $fraseNorm = preg_replace('/\s+/', '', mb_strtolower($frase));
                if (str_contains($sinEsp, $fraseNorm)) {
                    $fail(self::$mensajes[$categoria]);
                    return;
                }
            }
        }

        // — Capa 2: Azure AI Content Safety —
        $key      = config('services.azure_content_safety.key');
        $endpoint = config('services.azure_content_safety.endpoint');

        if (! blank($key) && ! blank($endpoint)) {
            $this->checkWithAzure($texto, $fail, $key, $endpoint);
        }
    }

    private function checkWithAzure(string $texto, Closure $fail, string $key, string $endpoint): void
    {
        $host = rtrim($endpoint, '/');
        if (! str_starts_with($host, 'https://')) {
            $host = "https://{$host}.cognitiveservices.azure.com";
        }

        $url = "{$host}/contentsafety/text:analyze?api-version=2024-09-01";

        try {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $key,
                'Content-Type'              => 'application/json',
            ])
            ->timeout(8)
            ->post($url, [
                'text'       => mb_substr($texto, 0, 10000),
                'categories' => ['Hate', 'Violence', 'Sexual', 'SelfHarm'],
                'outputType' => 'FourSeverityLevels',
            ]);

            if (! $response->ok()) {
                Log::warning('Azure Content Safety: respuesta no OK', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return;
            }

            $mensajesAzure = [
                'Hate'     => 'El contenido incluye lenguaje de odio o discriminatorio, lo cual no está permitido en Marketo.',
                'Violence' => 'El contenido incluye lenguaje violento, lo cual no está permitido en Marketo.',
                'Sexual'   => 'El contenido incluye material sexualmente explícito, lo cual no está permitido en Marketo.',
                'SelfHarm' => 'El contenido hace referencia a autolesiones, lo cual no está permitido en Marketo.',
            ];

            // severity: 0=seguro, 2=bajo, 4=medio, 6=alto — bloqueamos severity >= 2
            foreach ($response->json('categoriesAnalysis', []) as $item) {
                $categoria = $item['category'] ?? '';
                $severity  = $item['severity'] ?? 0;

                if ($severity >= 2 && isset($mensajesAzure[$categoria])) {
                    $fail($mensajesAzure[$categoria]);
                    return;
                }
            }

        } catch (\Exception $e) {
            Log::warning('SinContenidoOfensivo: error al llamar Azure Content Safety', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
