<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\CategoryExcelResource;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportCategory implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CategoryExcelResource::collection(Category::select('name','image','status')->get());
    }

    public function headings(): array
    {
        return ["English Name", "Arabic Name", "Image"];
    }
}
