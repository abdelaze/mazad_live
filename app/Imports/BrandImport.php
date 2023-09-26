<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BrandImport implements ToModel , WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function startRow(): int
    {
        return 2;
    } 

    public function model(array $row)
    {
       
        $brand                      = Brand::create([
                                                'name'             => ['en' => $row[0] , 'ar' => $row[1]],
                                                'category_id'      => $row[2],
                                                'sub_category_id'  => $row[3],
                                                'image'            => $row[4],
                                            ]);

        return $brand;
    }
}
