<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'question' => 'Bisa bayar di tempat?',
                'answer' => 'Kami menyarankan pembayaran online melalui sistem kami (QRIS/Bank Transfer) untuk konfirmasi instan. Namun, hubungi admin jika Anda membutuhkan bantuan khusus untuk pembayaran di lokasi.',
                'order' => 1
            ],
            [
                'question' => 'Bisa reschedule jadwal?',
                'answer' => 'Tentu! Reschedule jadwal dapat dilakukan maksimal 24 jam sebelum waktu main yang telah dipesan melalui WhatsApp admin kami dengan menyertakan bukti booking.',
                'order' => 2
            ],
            [
                'question' => 'Apakah tersedia sewa raket?',
                'answer' => 'Ya, kami menyediakan penyewaan raket dan penjualan bola di lokasi. Member mendapatkan diskon khusus untuk penyewaan alat.',
                'order' => 3
            ],
            [
                'question' => 'Bagaimana jika hujan?',
                'answer' => 'Arena kami dilengkapi dengan sistem drainase standar internasional. Namun, jika terjadi cuaca ekstrem yang membahayakan pemain, admin akan menghubungi Anda untuk opsi reschedule.',
                'order' => 4
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
