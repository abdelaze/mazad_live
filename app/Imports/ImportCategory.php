<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportCategory implements ToModel , WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function startRow(): int
    {
        return 2;
    } 

    public function model(array $row)
    {
        $cat2                      = Category::orderBy('id' , 'DESC')->select('position')->first();
        if($cat2) {
            $position = $cat2->position;
        }else {
            $position = 0;
        }
        $cat                      = Category::create([
                                                'name'      => ['en' => $row[0] , 'ar' => $row[1]],
                                                'image'     => $row[2],
                                                 // 'status'    => $row[3],
                                            ]);

        $cat['position']          =  $position+1;
        $cat->save();
        return $cat;
    }
}
