<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Brand;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\BrandExcelResource;

class BrandExport implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BrandExcelResource::collection(Brand::select('name','category_id' , 'sub_category_id' ,'image' ,'status')->get());
    }

    public function headings(): array
    {
        return ["English Name", "Arabic Name", "Category" , "SubCategory" ,"Image"];
    }
}
