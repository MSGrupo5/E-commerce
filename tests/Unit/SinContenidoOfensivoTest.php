<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Rules\SinContenidoOfensivo;
use Tests\TestCase;

class SinContenidoOfensivoTest extends TestCase
{
    public function test_clean_text_passes(): void
    {
        $failed = false;

        (new SinContenidoOfensivo)->validate('description', 'Monitor gamer 24 pulgadas en excelente estado', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed);
    }

    public function test_detects_offensive_token_as_an_isolated_word(): void
    {
        $message = null;

        (new SinContenidoOfensivo)->validate('description', 'este vendedor es un hdp', function ($msg) use (&$message) {
            $message = $msg;
        });

        $this->assertNotNull($message);
        $this->assertStringContainsString('vulgar', $message);
    }

    public function test_does_not_flag_a_token_that_only_appears_as_a_substring(): void
    {
        // "puta" no debería matchear dentro de otra palabra (ej. "disputa").
        $failed = false;

        (new SinContenidoOfensivo)->validate('description', 'Hubo una disputa por el precio del producto', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed);
    }

    public function test_detects_offensive_phrase_regardless_of_spacing(): void
    {
        $message = null;

        (new SinContenidoOfensivo)->validate('description', 'que producto de mierda, anda a la mierda', function ($msg) use (&$message) {
            $message = $msg;
        });

        $this->assertNotNull($message);
    }

    public function test_detects_offensive_phrase_with_collapsed_spaces(): void
    {
        $message = null;

        (new SinContenidoOfensivo)->validate('description', 'sos un hijodeputa', function ($msg) use (&$message) {
            $message = $msg;
        });

        $this->assertNotNull($message);
        $this->assertStringContainsString('vulgar', $message);
    }

    public function test_detects_racist_phrase(): void
    {
        $message = null;

        (new SinContenidoOfensivo)->validate('description', 'no le vendo a un negro de mierda', function ($msg) use (&$message) {
            $message = $msg;
        });

        $this->assertNotNull($message);
        $this->assertStringContainsString('racista', $message);
    }

    public function test_skips_azure_check_when_credentials_are_not_configured(): void
    {
        config(['services.azure_content_safety.key' => null, 'services.azure_content_safety.endpoint' => null]);

        $failed = false;

        (new SinContenidoOfensivo)->validate('description', 'Texto perfectamente normal sobre electrónica', function () use (&$failed) {
            $failed = true;
        });

        $this->assertFalse($failed);
    }
}
