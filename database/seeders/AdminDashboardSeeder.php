<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminDashboardSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Categories ----------
        $categories = collect([
            ['name' => 'Apparel & Pakaian', 'icon' => 'checkroom'],
            ['name' => 'Aromaterapi & Lilin', 'icon' => 'spa'],
            ['name' => 'Drinkware & Mug', 'icon' => 'local_cafe'],
            ['name' => 'Kopi & Teh Herbal', 'icon' => 'coffee'],
            ['name' => 'Tas & Souvenir', 'icon' => 'shopping_bag'],
        ])->map(fn ($c) => Category::create([
            'name' => $c['name'],
            'slug' => Str::slug($c['name']),
            'icon' => $c['icon'],
        ]));

        // ---------- Products & Variants ----------
        $productDefs = [
            [
                'name' => 'The Lodge Classic Pine Hoodie',
                'subtitle' => 'Zip Hoodie Katun Hijau Lumut Lembang',
                'category' => 'Apparel & Pakaian',
                'sku' => 'LMD-APP-001',
                'price' => 349000,
                'status' => 'active',
                'variants' => [
                    ['name' => 'S', 'stock' => 12, 'shelf' => 'B-01'],
                    ['name' => 'M', 'stock' => 20, 'shelf' => 'B-02'],
                    ['name' => 'L', 'stock' => 8, 'shelf' => 'B-04'],
                    ['name' => 'XL', 'stock' => 5, 'shelf' => 'B-05'],
                ],
            ],
            [
                'name' => 'Pinus Maribaya Aromatherapy Scented Candle 200g',
                'subtitle' => 'Lilin Aromaterapi Kaca Amber',
                'category' => 'Aromaterapi & Lilin',
                'sku' => 'LMD-CND-004',
                'price' => 165000,
                'status' => 'active',
                'variants' => [
                    ['name' => 'Amber Glass Jar', 'stock' => 45, 'shelf' => 'A-02'],
                ],
            ],
            [
                'name' => 'Bamboo Thermal Tumbler',
                'subtitle' => 'Tumbler Bambu 500ml Anti Bocor',
                'category' => 'Drinkware & Mug',
                'sku' => 'LMD-DRK-010',
                'price' => 189000,
                'status' => 'active',
                'variants' => [
                    ['name' => 'Natural', 'stock' => 30, 'shelf' => 'C-03'],
                ],
            ],
            [
                'name' => 'Enamel Camping Mug',
                'subtitle' => 'Mug Enamel Tema Highland',
                'category' => 'Drinkware & Mug',
                'sku' => 'LMD-DRK-011',
                'price' => 119000,
                'status' => 'active',
                'variants' => [
                    ['name' => 'Sage', 'stock' => 3, 'shelf' => 'C-05'],
                    ['name' => 'Cream', 'stock' => 2, 'shelf' => 'C-06'],
                ],
            ],
            [
                'name' => 'Arabica Beans 250gr',
                'subtitle' => 'Kopi Arabika Gunung Lembang Roast Medium',
                'category' => 'Kopi & Teh Herbal',
                'sku' => 'LMD-COF-002',
                'price' => 95000,
                'status' => 'active',
                'variants' => [
                    ['name' => 'Medium Roast', 'stock' => 60, 'shelf' => 'D-01'],
                ],
            ],
            [
                'name' => 'Botanical Canvas Tote Bag',
                'subtitle' => 'Tas Kanvas Motif Botani Highland',
                'category' => 'Tas & Souvenir',
                'sku' => 'LMD-BAG-006',
                'price' => 149000,
                'status' => 'active',
                'variants' => [
                    ['name' => 'Olive', 'stock' => 18, 'shelf' => 'E-02'],
                    ['name' => 'Beige', 'stock' => 15, 'shelf' => 'E-03'],
                ],
            ],
            [
                'name' => 'Batik Scarf Maribaya Edition',
                'subtitle' => 'Syal Batik Motif Pinus Khas Lembang',
                'category' => 'Apparel & Pakaian',
                'sku' => 'LMD-APP-007',
                'price' => 275000,
                'status' => 'active',
                'variants' => [
                    ['name' => 'Forest Green', 'stock' => 9, 'shelf' => 'B-08'],
                ],
            ],
            [
                'name' => 'Highland Herbal Tea Sachet Box',
                'subtitle' => 'Teh Herbal 20 Sachet Racikan Lokal',
                'category' => 'Kopi & Teh Herbal',
                'sku' => 'LMD-TEA-003',
                'price' => 78000,
                'status' => 'draft',
                'variants' => [
                    ['name' => 'Original', 'stock' => 0, 'shelf' => 'D-04'],
                ],
            ],
        ];

        $products = [];
        foreach ($productDefs as $def) {
            $product = Product::create([
                'category_id' => $categories->firstWhere('name', $def['category'])->id,
                'name' => $def['name'],
                'slug' => Str::slug($def['name']) . '-' . Str::random(4),
                'subtitle' => $def['subtitle'],
                'description' => $def['subtitle'] . '. Produk resmi merchandise The Lodge Maribaya, Lembang.',
                'sku' => $def['sku'],
                'price' => $def['price'],
                'stock' => collect($def['variants'])->sum('stock'),
                'weight_grams' => rand(150, 900),
                'status' => $def['status'],
            ]);

            foreach ($def['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'sku' => $def['sku'] . '-' . Str::upper(Str::slug($v['name'])),
                    'stock' => $v['stock'],
                    'shelf_location' => $v['shelf'],
                ]);
            }

            $products[$def['sku']] = $product->fresh('variants');
        }

        // ---------- Customers ----------
        $customerDefs = [
            ['name' => 'Dimas Arya Prasetyo', 'phone' => '0812-3344-5566', 'email' => 'dimas.arya92@gmail.com', 'city' => 'Bandung', 'province' => 'Jawa Barat', 'address' => 'Jl. Cihampelas No. 45'],
            ['name' => 'Siti Nurhaliza', 'phone' => '0818-9922-1133', 'email' => 'siti.nur@gmail.com', 'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'address' => 'Jl. Fatmawati No. 12'],
            ['name' => 'Budi Santoso', 'phone' => '0821-4455-7788', 'email' => 'budisantoso@gmail.com', 'city' => 'Surabaya', 'province' => 'Jawa Timur', 'address' => 'Jl. Diponegoro No. 8'],
            ['name' => 'Amanda Clara', 'phone' => '0877-1122-3344', 'email' => 'amanda.clara@gmail.com', 'city' => 'Tangerang Selatan', 'province' => 'Banten', 'address' => 'Jl. BSD Raya No. 21'],
            ['name' => 'Hendra Wijaya', 'phone' => '0813-8899-2211', 'email' => 'hendra.w@gmail.com', 'city' => 'Semarang', 'province' => 'Jawa Tengah', 'address' => 'Jl. Pandanaran No. 5'],
            ['name' => 'Rina Kusumawati', 'phone' => '0819-2233-4455', 'email' => 'rina.kusuma@gmail.com', 'city' => 'Yogyakarta', 'province' => 'DI Yogyakarta', 'address' => 'Jl. Malioboro No. 3'],
        ];

        $customers = collect($customerDefs)->map(fn ($c) => Customer::create($c));

        // ---------- Orders ----------
        $orderDefs = [
            [
                'number' => '8921', 'customer' => 0,
                'items' => [['sku' => 'LMD-APP-001', 'variant' => 'L', 'qty' => 1], ['sku' => 'LMD-APP-001', 'variant' => 'XL', 'qty' => 1], ['sku' => 'LMD-CND-004', 'variant' => 'Amber Glass Jar', 'qty' => 1]],
                'payment_method' => 'BCA Virtual Account', 'payment_status' => 'paid', 'fulfillment_status' => 'packing',
                'courier' => 'JNE', 'courier_service' => 'YES', 'shipping' => 15000,
                'buyer_note' => 'Tolong sertakan kartu ucapan The Lodge Maribaya: Selamat Ulang Tahun Ayah tercinta. Packing bubble wrap tebal ya kak untuk lilinnya agar tidak pecah di jalan. Terima kasih!',
                'gift' => true, 'wrap' => true, 'hoursAgo' => 3,
            ],
            [
                'number' => '8920', 'customer' => 1,
                'items' => [['sku' => 'LMD-DRK-010', 'qty' => 1], ['sku' => 'LMD-COF-002', 'qty' => 1]],
                'payment_method' => 'QRIS Mandiri', 'payment_status' => 'paid', 'fulfillment_status' => 'packing',
                'courier' => 'J&T', 'courier_service' => 'Reguler', 'shipping' => 12000, 'hoursAgo' => 4,
            ],
            [
                'number' => '8919', 'customer' => 2,
                'items' => [['sku' => 'LMD-DRK-011', 'variant' => 'Sage', 'qty' => 1]],
                'payment_method' => 'Mandiri VA', 'payment_status' => 'pending', 'fulfillment_status' => 'pending_payment',
                'courier' => 'SiCepat', 'courier_service' => 'REG', 'shipping' => 10000, 'hoursAgo' => 6,
            ],
            [
                'number' => '8918', 'customer' => 3,
                'items' => [['sku' => 'LMD-BAG-006', 'variant' => 'Olive', 'qty' => 1], ['sku' => 'LMD-BAG-006', 'variant' => 'Beige', 'qty' => 1]],
                'payment_method' => 'GoPay', 'payment_status' => 'paid', 'fulfillment_status' => 'shipped',
                'courier' => 'JNE', 'courier_service' => 'Reguler', 'shipping' => 15000, 'tracking' => 'JNE9821731', 'hoursAgo' => 20,
            ],
            [
                'number' => '8917', 'customer' => 4,
                'items' => [['sku' => 'LMD-APP-007', 'qty' => 1], ['sku' => 'LMD-CND-004', 'qty' => 1]],
                'payment_method' => 'Kartu Kredit BCA', 'payment_status' => 'paid', 'fulfillment_status' => 'completed',
                'courier' => 'Paxel', 'courier_service' => 'NextDay', 'shipping' => 25000, 'hoursAgo' => 30,
            ],
            [
                'number' => '8916', 'customer' => 5,
                'items' => [['sku' => 'LMD-APP-001', 'variant' => 'M', 'qty' => 1]],
                'payment_method' => 'ShopeePay', 'payment_status' => 'expired', 'fulfillment_status' => 'cancelled',
                'courier' => null, 'courier_service' => null, 'shipping' => 0, 'hoursAgo' => 34,
            ],
        ];

        foreach ($orderDefs as $def) {
            $customer = $customers[$def['customer']];
            $items = collect($def['items']);
            $subtotal = 0;
            $weight = 0;

            $preparedItems = $items->map(function ($it) use ($products, &$subtotal, &$weight) {
                $product = $products[$it['sku']];
                $variant = isset($it['variant']) ? $product->variants->firstWhere('name', $it['variant']) : $product->variants->first();
                $price = $variant?->price_override ?? $product->price;
                $qty = $it['qty'];
                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;
                $weight += $product->weight_grams * $qty;

                return [
                    'product' => $product,
                    'variant' => $variant,
                    'price' => $price,
                    'qty' => $qty,
                    'lineTotal' => $lineTotal,
                ];
            });

            $total = $subtotal + $def['shipping'];
            $createdAt = now()->subHours($def['hoursAgo']);

            $order = Order::create([
                'order_number' => 'ORD-2024-' . $def['number'],
                'trx_id' => 'LMD-TRX-' . $def['number'] . '0-BDG',
                'customer_id' => $customer->id,
                'subtotal' => $subtotal,
                'shipping_cost' => $def['shipping'],
                'total' => $total,
                'payment_method' => $def['payment_method'],
                'payment_status' => $def['payment_status'],
                'fulfillment_status' => $def['fulfillment_status'],
                'courier' => $def['courier'],
                'courier_service' => $def['courier_service'],
                'tracking_number' => $def['tracking'] ?? null,
                'shipping_address' => $customer->address,
                'shipping_city' => $customer->city,
                'shipping_province' => $customer->province,
                'buyer_note' => $def['buyer_note'] ?? null,
                'flag_gift_card' => $def['gift'] ?? false,
                'flag_extra_wrap' => $def['wrap'] ?? false,
                'source' => 'Website Store (Desktop)',
                'customer_ip' => '182.253.118.' . rand(10, 250),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            foreach ($preparedItems as $pi) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $pi['product']->id,
                    'product_variant_id' => $pi['variant']?->id,
                    'product_name' => $pi['product']->name,
                    'variant_name' => $pi['variant']?->name,
                    'sku' => $pi['variant']?->sku ?? $pi['product']->sku,
                    'shelf_location' => $pi['variant']?->shelf_location,
                    'unit_price' => $pi['price'],
                    'qty' => $pi['qty'],
                    'subtotal' => $pi['lineTotal'],
                    'weight_grams' => $pi['product']->weight_grams * $pi['qty'],
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'actor_name' => $customer->name . ' (Pembeli)',
                'actor_role' => null,
                'actor_type' => 'customer',
                'description' => 'Pesanan baru dibuat via web checkout browser Chrome Desktop. Alamat IP: ' . $order->customer_ip . ' (' . $customer->city . ', ' . $customer->province . ').',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            if ($def['payment_status'] === 'paid') {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'actor_name' => 'Sistem Otomatis Midtrans Gateway',
                    'actor_role' => null,
                    'actor_type' => 'system',
                    'description' => 'Mengonfirmasi pembayaran lunas ' . \App\Support\Format::rupiah($total) . ' melalui webhook settlement bank. (Nomor Referensi: TRX-' . strtoupper(Str::random(6)) . ').',
                    'created_at' => $createdAt->copy()->addMinutes(2),
                    'updated_at' => $createdAt->copy()->addMinutes(2),
                ]);
            }

            if (in_array($def['fulfillment_status'], ['packing', 'ready_to_ship', 'shipped', 'completed'])) {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'actor_name' => 'Ratna Kartika',
                    'actor_role' => 'Store Operations Lead',
                    'actor_type' => 'staff',
                    'description' => 'Mengubah status pesanan menjadi "' . Order::fulfillmentStatusLabel($def['fulfillment_status']) . '". Mencetak picking-slip item untuk tim fulfillment gudang Lembang.',
                    'created_at' => $createdAt->copy()->addMinutes(25),
                    'updated_at' => $createdAt->copy()->addMinutes(25),
                ]);
            }
        }
    }
}
