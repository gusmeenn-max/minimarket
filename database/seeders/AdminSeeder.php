<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Minimarket',
                'password' => Hash::make('admin123'),
            ]
        );

        // kategori => [deskripsi, [[kode, nama, satuan, stok, stok minimum], ...]]
        $data = [
            'Makanan Ringan' => ['Keripik, biskuit, wafer, cokelat, dan camilan kemasan.', [
                ['MKR-001', 'Chitato Sapi Panggang 68 g', 'pcs', 48, 12],
                ['MKR-002', 'Oreo Vanilla 133 g', 'pcs', 36, 10],
                ['MKR-003', 'Silverqueen Cashew 58 g', 'pcs', 8, 10],
                ['MKR-004', 'Tango Wafer Cokelat 176 g', 'pcs', 24, 8],
            ]],
            'Minuman' => ['Air mineral, teh, kopi, jus, dan minuman kemasan.', [
                ['MNM-001', 'Aqua Botol 600 ml', 'botol', 120, 36],
                ['MNM-002', 'Teh Botol Sosro 450 ml', 'botol', 60, 24],
                ['MNM-003', 'Pocari Sweat 500 ml', 'botol', 0, 12],
                ['MNM-004', 'Good Day Cappuccino 250 ml', 'botol', 30, 12],
            ]],
            'Sembako' => ['Beras, minyak, gula, tepung, dan kebutuhan pokok harian.', [
                ['SMB-001', 'Beras Premium Rojolele 5 kg', 'karung', 25, 8],
                ['SMB-002', 'Minyak Goreng Bimoli 2 L', 'pouch', 40, 12],
                ['SMB-003', 'Gula Pasir Gulaku 1 kg', 'pak', 45, 15],
                ['SMB-004', 'Tepung Terigu Segitiga Biru 1 kg', 'pak', 6, 10],
            ]],
            'Mie & Makanan Instan' => ['Mie instan, bubur, sereal, dan makanan siap saji.', [
                ['MIE-001', 'Indomie Goreng Original', 'bungkus', 200, 60],
                ['MIE-002', 'Mie Sedaap Soto 75 g', 'bungkus', 90, 40],
                ['MIE-003', 'Pop Mie Ayam Bawang', 'cup', 30, 20],
            ]],
            'Susu & Olahan' => ['Susu UHT, susu kental manis, keju, dan yogurt.', [
                ['SSU-001', 'Ultra Milk Full Cream 250 ml', 'kotak', 48, 24],
                ['SSU-002', 'Frisian Flag Kental Manis 370 g', 'kaleng', 22, 10],
                ['SSU-003', 'Cimory Yogurt Drink 240 ml', 'botol', 14, 12],
            ]],
            'Kebersihan & Perawatan' => ['Sabun, sampo, pasta gigi, dan perlengkapan mandi.', [
                ['KBR-001', 'Lifebuoy Sabun Mandi 75 g', 'pcs', 55, 20],
                ['KBR-002', 'Pantene Sampo 170 ml', 'botol', 18, 8],
                ['KBR-003', 'Pepsodent Pasta Gigi 190 g', 'pcs', 5, 10],
            ]],
            'Perlengkapan Rumah Tangga' => ['Deterjen, pembersih lantai, tisu, dan kebutuhan rumah.', [
                ['RMH-001', 'Rinso Anti Noda 800 g', 'pak', 26, 10],
                ['RMH-002', 'Sunlight Cairan Cuci Piring 755 ml', 'pouch', 32, 12],
                ['RMH-003', 'Paseo Tisu Gulung Isi 4', 'pak', 20, 8],
            ]],
            'Rokok & Lainnya' => ['Rokok, korek api, baterai, dan kebutuhan kecil lainnya.', [
                ['RLN-001', 'Baterai ABC Alkaline AA', 'pak', 15, 6],
                ['RLN-002', 'Korek Api Gas Tokai', 'pcs', 40, 15],
            ]],
        ];

        foreach ($data as $categoryName => [$description, $products]) {
            $category = Category::updateOrCreate(
                ['name' => $categoryName],
                ['description' => $description]
            );

            foreach ($products as [$code, $name, $unit, $stock, $minimum]) {
                Product::updateOrCreate(
                    ['code' => $code],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'unit' => $unit,
                        'stock' => $stock,
                        'minimum_stock' => $minimum,
                    ]
                );
            }
        }
    }
}
