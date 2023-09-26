<?php

namespace App\Pattern\Input;

use App\Models\Attribute;
use App\Models\SubAttribute;
use App\Traits\ResponseJson;
use App\Models\AttributeBrand;
use Illuminate\Support\Facades\DB;

class Radio implements InputInterface
{
    use ResponseJson;

    public function __construct(private array $validatedInput,private string $table){}

    public function store(): \Illuminate\Http\JsonResponse
    {
        try {
            $validated                             =  $this->validatedInput;
            $validated['input_label']              =  ['en' =>   $validated['input_label']  , 'ar' =>   $validated['input_label_ar'] ];
            $validated['options']                  =  json_encode( $validated['options']);
            $validated['options_label']            =  json_encode( $validated['options_label']);
            $validated['options_ar']               =  json_encode( $validated['options_ar']);
            $validated['options_label_ar']         =  json_encode( $validated['options_label_ar']);
            if($this->table == 'attributes') {
                Attribute::create($validated);
            }

            if($this->table == 'sub_attributes') {
                SubAttribute::create($validated);
               }
    
                if($this->table == 'attribute_brands') {
                    AttributeBrand::create($validated);
                }
            //DB::table($this->table)->insert($this->validatedInput);
            return $this->responseJson(['message' => 'attribute created successfully'], 201);
        } catch (\Exception $exception) {
            return $this->responseJson(['errors' => ['server error']], 500);
        }
    }
}
