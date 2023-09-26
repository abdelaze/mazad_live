<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\SubCategoryExcelResource;

class ExportSubCategory implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SubCategoryExcelResource::collection(SubCategory::select('name','category_id')->get());
    }

    public function headings(): array
    {
        return ["English Name", "Arabic Name", "Category Id"];
    }
}
