<?php

namespace App\Imports;

use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportSubCategory implements ToModel , WithStartRow
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

        $subcat2                      = SubCategory::orderBy('id' , 'DESC')->select('position')->first();
        if($subcat2) {
            $position = $subcat2->position;
        }else {
            $position = 0;
        }
       
        $subcat                      = SubCategory::create([
                                                'name'      => ['en' => $row[0] , 'ar' => $row[1]],
                                                'category_id'     => $row[2],
                                            ]);

        $subcat['position']          = $position+1;
        $subcat->save();
        return $subcat;
    }
}
