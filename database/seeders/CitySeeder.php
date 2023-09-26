<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            array('name_en' => "Joal-Fadiouth",'state_id' => 3082),
            array('name_en' => "Kayar",'state_id' => 3082),
            array('name_en' => "Khombole",'state_id' => 3082),
            array('name_en' => "Mbour",'state_id' => 3082),
            array('name_en' => "Meckhe",'state_id' => 3082),
            array('name_en' => "Nguekhokh",'state_id' => 3082),
            array('name_en' => "Pout",'state_id' => 3082),
            array('name_en' => "Thiadiaye",'state_id' => 3082),
            array('name_en' => "Thies",'state_id' => 3082),
            array('name_en' => "Tivaouane",'state_id' => 3082),


            array('name_en' => "Takamaka",'state_id' => 3083),


            array('name_en' => "Dahra",'state_id' => 3084),
            array('name_en' => "Kebemer",'state_id' => 3084),
            array('name_en' => "Linguere",'state_id' => 3084),
            array('name_en' => "Louga",'state_id' => 3084),

            array('name_en' => "Bakel",'state_id' => 3086),
            array('name_en' => "Kedougou",'state_id' => 3086),
            array('name_en' => "Tambacounda",'state_id' => 3086),

            array('name_en' => "Bambey",'state_id' => 3087),
            array('name_en' => "Diourbel",'state_id' => 3087),
            array('name_en' => "Mbacke",'state_id' => 3087),
            array('name_en' => "Touba",'state_id' => 3087),

            array('name_en' => "Cascade",'state_id' => 3088),

            array('name_en' => "Dagana",'state_id' => 3092),
            array('name_en' => "Gollere",'state_id' => 3092),
            array('name_en' => "Kanel",'state_id' => 3092),
            array('name_en' => "Matam",'state_id' => 3092),
            array('name_en' => "Ndioum",'state_id' => 3092),
            array('name_en' => "Ourossogui",'state_id' => 3092),
            array('name_en' => "Podor",'state_id' => 3092),
            array('name_en' => "Richard Toll",'state_id' => 3092),
            array('name_en' => "Saint-Louis",'state_id' => 3092),
            array('name_en' => "Semme",'state_id' => 3092),
            array('name_en' => "Thilogne",'state_id' => 3092),
            array('name_en' => "Waounde",'state_id' => 3092),
        ];

        DB::table('cities')->insert($cities);
    }
}
