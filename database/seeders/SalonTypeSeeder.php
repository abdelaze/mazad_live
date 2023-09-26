<?php

namespace Database\Seeders;

use App\Models\SalonType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title_ar' => 'صالون رجالي',
                'title_en' => 'Men hairdressing',
                'title_de' => 'Herrenfriseur',
                'title_tu' => 'Erkek kuaförü',
            ],
            [
                'title_ar' => 'كوافير حريمي',
                'title_en' => 'Ladies hairdressing',
                'title_de' => 'Damenfriseur',
                'title_tu' => 'Bayan kuaförü',
            ],
            [
                'title_ar' => 'كوافير محجبات',
                'title_en' => 'Hairdressing for veiled ladies',
                'title_de' => 'Friseur für verschleierte Damen',
                'title_tu' => 'لإesettürlü bayanlar için kuaför',
            ]
        ];
        foreach ($data as $key => $value) {
            SalonType::create($value);
        }
    }
}
