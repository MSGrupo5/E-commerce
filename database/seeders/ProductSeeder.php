<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private const IMAGES = [
        'gpu' => [
            'https://images.unsplash.com/photo-1591799044315-869e5ad0c03f?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1555680202-c86f0e12f086?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1591405351990-4726e331f141?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1562976540-1502c2145186?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1555618561-b9c8963fad88?w=400&h=300&fit=crop',
        ],
        'motherboard' => [
            'https://images.unsplash.com/photo-1518770660439-4636190af475?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1563770660941-20978e870e26?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1587202372634-32705e3bf83c?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1617788138017-80ad40651399?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1555618561-b9c8963fad88?w=400&h=300&fit=crop',
        ],
        'monitor' => [
            'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1569428034239-f9565e32e224?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1540821927689-8494128bb20a?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1586210579191-33b45e38fa2c?w=400&h=300&fit=crop',
        ],
        'keyboard' => [
            'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=400&h=300&fit=crop',
        ],
        'mouse' => [
            'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1629429408209-1f2da617f7a8?w=400&h=300&fit=crop',
        ],
        'mousepad' => [
            'https://images.unsplash.com/photo-1602253057119-44d745d9b860?w=400&h=300&fit=crop',
        ],
        'headphones' => [
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1487215078519-e21cc028cb29?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1545127398-14699f92334b?w=400&h=300&fit=crop',
        ],
        'speakers' => [
            'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=400&h=300&fit=crop',
        ],
        'microphone' => [
            'https://images.unsplash.com/photo-1590658268037-6bf12f032f55?w=400&h=300&fit=crop',
        ],
        'tech' => [
            'https://images.unsplash.com/photo-1517077304055-6e89abbf09b0?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1518770660439-4636190af475?w=400&h=300&fit=crop',
        ],
    ];

    public function run(): void
    {
        $sellers = [];
        for ($i = 1; $i <= 3; $i++) {
            $sellers[] = User::factory()->create([
                'name' => "Vendedor $i",
                'role' => 'usuario',
            ]);
        }

        $products = [
            // ── Tarjetas de video (category_id: 1) ──
            ['category_id' => 1, 'name' => 'NVIDIA GeForce RTX 4090',           'description' => 'GPU NVIDIA Ada Lovelace, 24GB GDDR6X, 16384 CUDA cores, soporte DLSS 3, ray tracing en tiempo real.',                          'price' => 2199999, 'stock' => 5,  'img' => 'gpu', 'img_idx' => 0],
            ['category_id' => 1, 'name' => 'NVIDIA GeForce RTX 4070 Ti',         'description' => 'GPU NVIDIA Ada Lovelace, 12GB GDDR6X, 7680 CUDA cores, eficiencia energética superior y DLSS 3.',                         'price' => 899999,  'stock' => 12, 'img' => 'gpu', 'img_idx' => 1],
            ['category_id' => 1, 'name' => 'AMD Radeon RX 7900 XTX',             'description' => 'GPU AMD RDNA 3, 24GB GDDR6, 96MB Infinity Cache, soporte FSR 3 y DisplayPort 2.1.',                                        'price' => 1099999, 'stock' => 8,  'img' => 'gpu', 'img_idx' => 2],
            ['category_id' => 1, 'name' => 'NVIDIA GeForce RTX 4060',            'description' => 'GPU NVIDIA Ada Lovelace, 8GB GDDR6, 3072 CUDA cores, ideal para gaming 1080p con trazado de rayos.',                        'price' => 399999,  'stock' => 20, 'img' => 'gpu', 'img_idx' => 3],
            ['category_id' => 1, 'name' => 'AMD Radeon RX 7600',                 'description' => 'GPU AMD RDNA 3, 8GB GDDR6, 32MB Infinity Cache, perfecta para builds de entrada con gran rendimiento.',                    'price' => 349999,  'stock' => 15, 'img' => 'gpu', 'img_idx' => 4],

            // ── Motherboards (category_id: 2) ──
            ['category_id' => 2, 'name' => 'ASUS ROG Strix Z790-E',              'description' => 'Motherboard Intel Z790, ATX, DDR5, PCIe 5.0, WiFi 6E, 2.5Gb LAN, iluminación RGB Aura Sync.',                             'price' => 589999,  'stock' => 7,  'img' => 'motherboard', 'img_idx' => 0],
            ['category_id' => 2, 'name' => 'MSI MAG Z790 Tomahawk',              'description' => 'Motherboard Intel Z790, ATX, DDR5, PCIe 5.0, WiFi 6E, disipación térmica mejorada, diseño militar.',                     'price' => 429999,  'stock' => 10, 'img' => 'motherboard', 'img_idx' => 1],
            ['category_id' => 2, 'name' => 'Gigabyte B650 Aorus Elite AX',       'description' => 'Motherboard AMD B650, ATX, DDR5, PCIe 5.0, WiFi 6E, circuito de alimentación de 14+2+1 fases.',                           'price' => 299999,  'stock' => 14, 'img' => 'motherboard', 'img_idx' => 2],
            ['category_id' => 2, 'name' => 'ASUS TUF Gaming B760-PLUS',          'description' => 'Motherboard Intel B760, ATX, DDR5, PCIe 5.0, certificación TUF, protección contra sobretensión y temperaturas extremas.', 'price' => 259999,  'stock' => 18, 'img' => 'motherboard', 'img_idx' => 3],
            ['category_id' => 2, 'name' => 'ASRock B550M Pro4',                  'description' => 'Motherboard AMD B550, Micro ATX, DDR4, PCIe 4.0, ideal para builds de presupuesto ajustado con gran estabilidad.',         'price' => 129999,  'stock' => 25, 'img' => 'motherboard', 'img_idx' => 4],

            // ── Monitores (category_id: 3) ──
            ['category_id' => 3, 'name' => 'Samsung Odyssey G9 49"',             'description' => 'Monitor curvo 49 pulgadas Dual QHD, 240Hz, 1ms, FreeSync Premium Pro, HDR2000, relación 32:9 inmersiva.',                 'price' => 1599999, 'stock' => 3,  'img' => 'monitor', 'img_idx' => 0],
            ['category_id' => 3, 'name' => 'LG UltraGear 27GP850-B',             'description' => 'Monitor IPS 27 pulgadas QHD, 165Hz, 1ms, FreeSync Premium, DCI-P3 98%, ideal para gaming competitivo.',                   'price' => 499999,  'stock' => 9,  'img' => 'monitor', 'img_idx' => 1],
            ['category_id' => 3, 'name' => 'Dell S2722QC 27" 4K',                'description' => 'Monitor IPS 27 pulgadas 4K UHD, 60Hz, USB-C 65W, altavoces integrados, ideal para productividad y diseño.',               'price' => 449999,  'stock' => 6,  'img' => 'monitor', 'img_idx' => 2],
            ['category_id' => 3, 'name' => 'ASUS ROG Swift PG259QNR',            'description' => 'Monitor IPS 25 pulgadas Full HD, 360Hz, 1ms, G-Sync, con disipador de calor para sesiones prolongadas.',                  'price' => 699999,  'stock' => 4,  'img' => 'monitor', 'img_idx' => 3],
            ['category_id' => 3, 'name' => 'MSI Optix G2412',                    'description' => 'Monitor IPS 24 pulgadas Full HD, 144Hz, 1ms, FreeSync Premium, diseño sin bordes, excelente relación calidad-precio.',    'price' => 249999,  'stock' => 22, 'img' => 'monitor', 'img_idx' => 4],

            // ── Periféricos (category_id: 4) ──
            ['category_id' => 4, 'name' => 'Logitech G Pro X Superlight',        'description' => 'Mouse inalámbrico ultraliviano 63g, sensor HERO 25K, 70hrs batería, diseño ambidiestro, 5 botones programables.',        'price' => 189999,  'stock' => 15, 'img' => 'mouse', 'img_idx' => 0],
            ['category_id' => 4, 'name' => 'Razer Huntsman V2 TKL',              'description' => 'Teclado mecánico tenkeyless, switches ópticos Razer, reposamuñecas, cable USB-C desmontable, retroiluminación RGB.',     'price' => 199999,  'stock' => 11, 'img' => 'keyboard', 'img_idx' => 0],
            ['category_id' => 4, 'name' => 'Keychron Q1 Pro',                    'description' => 'Teclado mecánico 75% inalámbrico, carcasa de aluminio, switches Gateron Jupiter, QMK/VIA, RGB.',                          'price' => 249999,  'stock' => 8,  'img' => 'keyboard', 'img_idx' => 1],
            ['category_id' => 4, 'name' => 'Logitech MX Master 3S',              'description' => 'Mouse inalámbrico ergonómico, sensor 8000 DPI, scroll electromagnético, botones personalizables, USB-C, 70 días batería.', 'price' => 159999,  'stock' => 20, 'img' => 'mouse', 'img_idx' => 1],
            ['category_id' => 4, 'name' => 'SteelSeries QcK Heavy XXL',          'description' => 'Alfombrilla de escritorio 900x400mm, superficie de tela microtejida, base antideslizante de goma, costuras reforzadas.',  'price' => 39999,   'stock' => 30, 'img' => 'mousepad', 'img_idx' => 0],

            // ── Audio (category_id: 5) ──
            ['category_id' => 5, 'name' => 'Sony WH-1000XM5',                    'description' => 'Auriculares inalámbricos con cancelación de ruido activa, 30hrs batería, drivers 30mm, Multipoint Bluetooth 5.2.',         'price' => 429999,  'stock' => 7,  'img' => 'headphones', 'img_idx' => 0],
            ['category_id' => 5, 'name' => 'SteelSeries Arctis Nova Pro',        'description' => 'Auriculares gaming con DAC GameDAC Gen 2, drivers magnéticos de neodimio, cancelación de ruido AI, sonido Hi-Res.',       'price' => 399999,  'stock' => 5,  'img' => 'headphones', 'img_idx' => 1],
            ['category_id' => 5, 'name' => 'HyperX Cloud Alpha Wireless',        'description' => 'Auriculares inalámbricos gaming, 300hrs batería, drivers de doble cámara, almohadillas de espuma viscoelástica.',        'price' => 249999,  'stock' => 13, 'img' => 'headphones', 'img_idx' => 2],
            ['category_id' => 5, 'name' => 'Edifier R1280DB',                    'description' => 'Parlantes de estante activos 42W RMS, Bluetooth 5.0, entrada óptica/coaxial/RCA, control remoto incluido.',                'price' => 159999,  'stock' => 10, 'img' => 'speakers', 'img_idx' => 0],
            ['category_id' => 5, 'name' => 'Shure MV7',                          'description' => 'Micrófono dinámico USB/XLR, mezcla de audio en tiempo real, patrón polar cardioide, ideal para streaming y podcast.',      'price' => 359999,  'stock' => 4,  'img' => 'microphone', 'img_idx' => 0],

            // ── Combos (category_id: 6) ──
            ['category_id' => 6, 'name' => 'Kit Streaming Esencial',             'description' => 'Micrófono Shure MV7 + Webcam Logitech StreamCam + Brazo Rode PSA1. Todo lo necesario para empezar a hacer stream.',        'price' => 749999,  'stock' => 3,  'img' => 'tech', 'img_idx' => 0],
            ['category_id' => 6, 'name' => 'Combo Gaming RGB',                   'description' => 'Mouse Logitech G502 X + Teclado Razer Huntsman Mini + Alfombrilla SteelSeries QcK XXL. RGB sincronizable.',               'price' => 349999,  'stock' => 6,  'img' => 'keyboard', 'img_idx' => 1],
            ['category_id' => 6, 'name' => 'Estación de Trabajo 4K',             'description' => 'Monitor Dell S2722QC + Soporte de brazo + Hub USB-C 10 en 1. Espacio de trabajo ordenado y productivo.',                  'price' => 649999,  'stock' => 4,  'img' => 'monitor', 'img_idx' => 2],
            ['category_id' => 6, 'name' => 'Build AMD Inicio',                   'description' => 'Motherboard ASRock B550M + GPU AMD RX 7600. Base sólida para tu primera PC gamer.',                                       'price' => 479998,  'stock' => 8,  'img' => 'gpu', 'img_idx' => 4],
            ['category_id' => 6, 'name' => 'Build Intel Pro',                    'description' => 'Motherboard ASUS TUF B760 + GPU NVIDIA RTX 4060. Rendimiento equilibrado para gaming y productividad.',                    'price' => 659998,  'stock' => 5,  'img' => 'motherboard', 'img_idx' => 3],
        ];

        foreach ($products as $i => $product) {
            $seller = $sellers[$i % count($sellers)];

            Product::create([
                'user_id' => $seller->id,
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'image' => self::IMAGES[$product['img']][$product['img_idx']],
                'is_active' => true,
            ]);
        }
    }
}
