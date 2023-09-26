<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pattern\Input\InputStrategy;
use App\Models\{Attribute, AttributeBrand, Brand};
use App\Http\Requests\Admin\Attriubtes\{UpdateAttributeRequest,StoreBrandAttributeRequest,InputPreviewRequest};


class BrandAttribute extends Controller
{
    use ResponseJson;

    private AttributeBrand $attributeBrand;

    public function __construct(AttributeBrand $attributeBrand)
    {
        $this->attributeBrand = $attributeBrand;
    }

    public function create(): \Illuminate\View\View
    {
        return view('admin.brand-attributes.create');
    }

    public function preview(InputPreviewRequest $inputPreviewRequest): \Illuminate\Http\JsonResponse
    {
        try {
            $requestData = $inputPreviewRequest->validated();
            $brands = Brand::all();
            $renderHtml = view('admin.brand-attributes.preview', compact('brands', 'requestData'))->render();
            return $this->responseJson(['data' => $renderHtml], 200);
        } catch (\Exception $exception) {
            return $this->responseJson(['errors' => ['server error']], 500);
        }
    }

    public function store(StoreBrandAttributeRequest $storeBrandAttributeRequest): \Illuminate\Http\JsonResponse
    {
        $type = Str::ucfirst($storeBrandAttributeRequest->validated()['input_type']);
        if (class_exists($className = "App\\Pattern\\Input\\" . $type)) {
            return (new InputStrategy(new $className($storeBrandAttributeRequest->validated(), 'attribute_brands')))->create();
        }
        return $this->responseJson(['errors' => ['this method not implemented yet']], 500);
    }

    public function getRadioInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.brand-attributes.radio-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getCheckboxInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.brand-attributes.checkbox-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getSelectInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.brand-attributes.select-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getAttributesBrand(Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $this->attributeBrand->where('brand_id', $request->brand_id)->get();
        $renderHtml = view('admin.brand-attributes.attributes-brand', compact('attributes'))->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function show(Attribute $attribute)
    {
    }

    public function edit(Attribute $attribute)
    {

    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute)
    {

    }


    public function destroy(Attribute $attribute)
    {

    }

    public function index()
    {

    }
}
