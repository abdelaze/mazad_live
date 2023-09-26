@if($requestData['input_type'] === 'text')
<form id="store-inputs" action="{{route('sub-attributes.store')}}" method="POST">
@csrf
<input type="hidden" name="input_label" value="{{$requestData['input_label']}}" class="form-control">
<input type="hidden" name="input_label_ar" value="{{$requestData['input_label_ar']}}" class="form-control">
<input type="hidden" name="input_type" value="{{$requestData['input_type']}}" class="form-control">
<input type="hidden" name="input_name" value="{{$requestData['input_name']}}" class="form-control">
<label class="font-size-14">{{__('translation.select_subcategory')}}:</label>
<select class="form-control mb-3" name="sub_category_id" required>
    <option disabled selected>{{__('translation.select_subcategory')}}</option>
    @foreach($subcategories as $subcategory)
        <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
    @endforeach
</select>
<label class="form-label">{{$requestData['input_label']}}</label>
<input type="text"  class="form-control" disabled/>

    <div class="text-center">
        <button type="submit" class="btn btn-primary mt-2">{{__('translation.save')}}</button>
    </div>
</form>
@endif

@if($requestData['input_type'] === 'number')
    <form id="store-inputs" action="{{route('sub-attributes.store')}}" method="POST">
        @csrf
        <input type="hidden" name="input_label" value="{{$requestData['input_label']}}" class="form-control">
        <input type="hidden" name="input_label_ar" value="{{$requestData['input_label_ar']}}" class="form-control">
        <input type="hidden" name="input_type" value="{{$requestData['input_type']}}" class="form-control">
        <input type="hidden" name="input_name" value="{{$requestData['input_name']}}" class="form-control">
        <label class="font-size-14">{{__('translation.select_subcategory')}}:</label>
        <select class="form-control mb-3" name="sub_category_id" required>
            <option disabled selected>{{__('translation.select_subcategory')}}</option>
            @foreach($subcategories as $subcategory)
                <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
            @endforeach
        </select>
        <label class="form-label">{{$requestData['input_label']}}</label>
        <input type="number"  class="form-control" disabled/>

        <div class="text-center">
            <button type="submit" class="btn btn-primary mt-2">{{__('translation.save')}}</button>
        </div>
    </form>
@endif

@if($requestData['input_type'] === 'textarea')
<form id="store-inputs" action="{{route('translation.sub-attributes.store')}}" method="POST">
@csrf
<input type="hidden" name="input_label" value="{{$requestData['input_label']}}" class="form-control">
<input type="hidden" name="input_label_ar" value="{{$requestData['input_label_ar']}}" class="form-control">
<input type="hidden" name="input_type" value="{{$requestData['input_type']}}" class="form-control">
<input type="hidden" name="input_name" value="{{$requestData['input_name']}}" class="form-control">
<label class="font-size-14">{{__('translation.select_subcategory')}}:</label>
<select class="form-control mb-3" name="sub_category_id" required>
    <option disabled selected>{{__('translation.select_subcategory')}}</option>
    @foreach($subcategories as $subcategory)
        <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
    @endforeach
</select>
<label class="form-label">{{$requestData['input_label']}}</label>
<textarea class="form-control" disabled></textarea>
    <div class="text-center">
        <button type="submit" class="btn btn-primary mt-2">{{__('translation.save')}}</button>
    </div>
</form>
@endif

@if($requestData['input_type'] === 'color')
<form id="store-inputs" action="{{route('sub-attributes.store')}}" method="POST">
@csrf
<input type="hidden" name="input_label" value="{{$requestData['input_label']}}" class="form-control">
<input type="hidden" name="input_label_ar" value="{{$requestData['input_label_ar']}}" class="form-control">
<input type="hidden" name="input_type" value="{{$requestData['input_type']}}" class="form-control">
<input type="hidden" name="input_name" value="{{$requestData['input_name']}}" class="form-control">
<label class="font-size-14">{{__('translation.select_subcategory')}}:</label>
<select class="form-control mb-3" name="sub_category_id" required>
    <option disabled selected>{{__('translation.select_subcategory')}}</option>
    @foreach($subcategories as $subcategory)
        <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
    @endforeach
</select>
<label class="form-label">{{$requestData['input_label']}}</label>
<input type="color" class="form-control form-control-color mw-100" disabled/>
    <div class="text-center">
        <button type="submit" class="btn btn-primary mt-2">{{__('translation.save')}}</button>
    </div>
</form>
@endif


@if($requestData['input_type'] === 'radio')
<form id="store-inputs" action="{{route('sub-attributes.store')}}" method="POST">
    @csrf
    <input type="hidden" name="input_label" value="{{$requestData['input_label']}}" class="form-control">
    <input type="hidden" name="input_label_ar" value="{{$requestData['input_label_ar']}}" class="form-control">
    <input type="hidden" name="input_type" value="{{$requestData['input_type']}}" class="form-control">
    <input type="hidden" name="input_name" value="{{$requestData['input_name']}}" class="form-control">
    @foreach($requestData['radio-options'] as $radioOption)
        <input type="hidden" name="options_label[]" value="{{$radioOption['label_radio']}}" class="form-control">
    @endforeach
    @foreach($requestData['radio-options'] as $radioOption)
        <input type="hidden" name="options[]" value="{{$radioOption['value_radio']}}" class="form-control">
    @endforeach

    @foreach($requestData['radio-options'] as $radioOption_ar)
    <input type="hidden" name="options_label_ar[]" value="{{$radioOption_ar['label_radio_ar']}}" class="form-control">
    @endforeach
    @foreach($requestData['radio-options'] as $radioOption_ar)
        <input type="hidden" name="options_ar[]" value="{{$radioOption_ar['value_radio_ar']}}" class="form-control">
    @endforeach

    <label class="font-size-14">{{__('translation.select_subcategory')}}:</label>
    <select class="form-control mb-3" name="sub_category_id" required>
        <option disabled selected>{{__('translation.select_subcategory')}}</option>
        @foreach($subcategories as $subcategory)
            <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
        @endforeach
    </select>
    <label class="font-size-14">{{ $requestData['input_label'] }}</label>
    @foreach($requestData['radio-options'] as $key => $radioOption)
        <div class="form-check">
            <input class="form-check-input" type="radio" disabled>
            <label class="form-check-label">{{$radioOption['label_radio']}}</label>
        </div>
    @endforeach
    <div class="text-center">
        <button type="submit" class="btn btn-primary mt-2">{{__('translation.save')}}</button>
    </div>
</form>
@endif


@if($requestData['input_type'] === 'checkbox')
<form id="store-inputs" action="{{route('sub-attributes.store')}}" method="POST">
@csrf
<input type="hidden" name="input_label" value="{{$requestData['input_label']}}" class="form-control">
<input type="hidden" name="input_label_ar" value="{{$requestData['input_label_ar']}}" class="form-control">
<input type="hidden" name="input_type" value="{{$requestData['input_type']}}" class="form-control">
<input type="hidden" name="input_name" value="{{$requestData['input_name']}}" class="form-control">
@foreach($requestData['checkbox-options'] as $checkbox_option)
<input type="hidden" name="options_label[]" value="{{$checkbox_option['label_checkbox']}}" class="form-control">
@endforeach
@foreach($requestData['checkbox-options'] as $checkbox_option)
    <input type="hidden" name="options[]" value="{{$checkbox_option['value_checkbox']}}" class="form-control">
@endforeach

@foreach($requestData['checkbox-options'] as $checkbox_option_ar)
<input type="hidden" name="options_label_ar[]" value="{{$checkbox_option_ar['label_checkbox_ar']}}" class="form-control">
@endforeach
@foreach($requestData['checkbox-options'] as $checkbox_option_ar)
    <input type="hidden" name="options_ar[]" value="{{$checkbox_option_ar['value_checkbox_ar']}}" class="form-control">
@endforeach

<label class="font-size-14">{{__('translation.select_subcategory')}}:</label>
<select class="form-control mb-3" name="sub_category_id" required>
    <option disabled selected>{{__('translation.select_subcategory')}}</option>
    @foreach($subcategories as $subcategory)
        <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
    @endforeach
</select>
<label class="font-size-14">{{ $requestData['input_label'] }}</label>
@foreach($requestData['checkbox-options'] as $key => $checkbox_option)
<div class="form-check">
    <input class="form-check-input" type="checkbox" disabled>
    <label class="form-check-label">{{$checkbox_option['label_checkbox']}}</label>
</div>
@endforeach
<div class="text-center">
    <button type="submit" class="btn btn-primary mt-2">{{__('translation.save')}}</button>
</div>
</form>
@endif

@if($requestData['input_type'] === 'select')
<form id="store-inputs" action="{{route('sub-attributes.store')}}" method="POST">
        @csrf
        <input type="hidden" name="input_label" value="{{$requestData['input_label']}}" class="form-control">
        <input type="hidden" name="input_label_ar" value="{{$requestData['input_label_ar']}}" class="form-control">
        <input type="hidden" name="input_type" value="{{$requestData['input_type']}}" class="form-control">
        <input type="hidden" name="input_name" value="{{$requestData['input_name']}}" class="form-control">
        @foreach($requestData['select-options'] as $select_option)
            <input type="hidden" name="options_label[]" value="{{$select_option['label_select']}}" class="form-control">
        @endforeach
        @foreach($requestData['select-options'] as $select_option)
            <input type="hidden" name="options[]" value="{{$select_option['value_select']}}" class="form-control">
        @endforeach

        @foreach($requestData['select-options'] as $select_option_ar)
        <input type="hidden" name="options_label_ar[]" value="{{$select_option_ar['label_select_ar']}}" class="form-control">
        @endforeach
        @foreach($requestData['select-options'] as $select_option_ar)
            <input type="hidden" name="options_ar[]" value="{{$select_option_ar['value_select_ar']}}" class="form-control">
        @endforeach

        <label class="font-size-14">{{__('translation.select_subcategory')}}:</label>
        <select class="form-control mb-3" name="sub_category_id" required>
            <option disabled selected>{{__('translation.select_subcategory')}}</option>
            @foreach($subcategories as $subcategory)
                <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
            @endforeach
        </select>
        <label class="font-size-14">{{ $requestData['input_label'] }}</label>
        <select class="form-control mb-3" disabled>
            <option disabled selected>{{__('translation.select')}}</option>
            @foreach($requestData['select-options'] as $key => $select_option)
                <option value="{{$select_option['value_select']}}">{{$select_option['label_select']}}</option>
            @endforeach
        </select>
        <div class="text-center">
            <button type="submit" class="btn btn-primary mt-2">{{__('translation.save')}}</button>
        </div>
    </form>
@endif

@include('admin.sub-attributes.scripts.store-inputs')
