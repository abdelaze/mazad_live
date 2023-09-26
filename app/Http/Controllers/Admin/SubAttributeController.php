<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pattern\Input\InputStrategy;
use App\Models\{SubAttribute,SubCategory};
use App\Http\Requests\Admin\Attriubtes\{UpdateAttributeRequest,StoreSubAttributeRequest,InputPreviewRequest};



class SubAttributeController extends Controller
{
    use ResponseJson; 

    private SubAttribute $subAttributeModel;

    public function __construct(SubAttribute $subAttributeModel)
    {
        $this->subAttributeModel = $subAttributeModel;
    }

    public function create(): \Illuminate\View\View
    {
        return view('admin.sub-attributes.create');
    }

    public function preview(InputPreviewRequest $inputPreviewRequest): \Illuminate\Http\JsonResponse
    {
        try {
            $requestData = $inputPreviewRequest->validated();
            $subcategories = SubCategory::all();
            $renderHtml = view('admin.sub-attributes.preview', compact('subcategories', 'requestData'))->render();
            return $this->responseJson(['data' => $renderHtml], 200);
        } catch (\Exception $exception) {
            return $this->responseJson(['errors' => ['server error']], 500);
        }
    }

    public function store(StoreSubAttributeRequest $storeSubAttributeRequest): \Illuminate\Http\JsonResponse
    { 
        $type = Str::ucfirst($storeSubAttributeRequest->validated()['input_type']);
        if (class_exists($className = "App\\Pattern\\Input\\" . $type)) {
            return (new InputStrategy(new $className($storeSubAttributeRequest->validated(),'sub_attributes')))->create();
        }
        return $this->responseJson(['errors' => ['this method not implemented yet']], 500);
    }

    public function getRadioInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.sub-attributes.radio-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getCheckboxInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.sub-attributes.checkbox-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getSelectInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.sub-attributes.select-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getAttributesSubCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $this->subAttributeModel->where('sub_category_id',$request->subcategory_id)->get();
        $renderHtml = view('admin.sub-attributes.attributes-subcategory',compact('attributes'))->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function show(SubAttribute $subAttribute)
    {

    }


    public function edit(SubAttribute $subAttribute)
    {

    }


    public function update(UpdateAttributeRequest $request, SubAttribute $subAttribute)
    {


    }


    public function destroy(SubAttribute $subAttribute)
    {

    }

    public function index()
    {

    }
}
