<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Attriubtes\{UpdateAttributeRequest,StoreAttributeRequest,InputPreviewRequest};
use App\Models\{Attribute,Category};
use App\Http\Controllers\Controller;
use App\Pattern\Input\InputStrategy;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    use ResponseJson;

    private Attribute $attributeModel;

    public function __construct(Attribute $attributeModel)
    {
        /*$this->middleware('permission:attribute-list');
        $this->middleware('permission:attribute-index', ['only' => ['index']]);
        $this->middleware('permission:attribute-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:attribute-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:attribute-delete', ['only' => ['destroy']]);*/
        $this->attributeModel = $attributeModel;
    }

    public function create(): \Illuminate\View\View
    {
        return view('admin.attributes.create');
    }

    public function preview(InputPreviewRequest $inputPreviewRequest): \Illuminate\Http\JsonResponse
    {
       // dd('preview');
        try {
            $requestData = $inputPreviewRequest->validated();
            $categories  = Category::all();
            $renderHtml  = view('admin.attributes.preview', compact('categories', 'requestData'))->render();
            return $this->responseJson(['data' => $renderHtml], 200);
            //return response()->json(['succes' => true, 'data' => $renderHtml]);
        } catch (\Exception $exception) {
             dd($exception);
            return $this->responseJson(['errors' => ['server error']], 500);
        }
    }

    public function store(StoreAttributeRequest $storeAttributeRequest): \Illuminate\Http\JsonResponse
    {
        $type = Str::ucfirst($storeAttributeRequest->validated()['input_type']);
        if (class_exists($className = "App\\Pattern\\Input\\" . $type)) {
            return (new InputStrategy(new $className($storeAttributeRequest->validated(),'attributes')))->create();
        }
        return $this->responseJson(['errors' => ['this method not implemented yet']], 500);
    }

    public function getRadioInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.attributes.radio-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getCheckboxInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.attributes.checkbox-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getSelectInput(): \Illuminate\Http\JsonResponse
    {
        $renderHtml = view('admin.attributes.select-input')->render();
        return $this->responseJson(['data' => $renderHtml]);
    }

    public function getAttributesCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $this->attributeModel->where('category_id',$request->category_id)->get();
        $renderHtml = view('admin.attributes.attributes-category',compact('attributes'))->render();
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
        $attribute->delete();
        flasher(trans('translation.data_deleted_successfully'),'success');
        return redirect()->back();
    }

    public function index()
    {

    }
}
