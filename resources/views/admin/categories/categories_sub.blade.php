<option value="">{{ trans('translation.select_subcategory') }}</option>
@foreach ($subcategories as $subcat)
<option value="{{ $subcat->id }}"> {{ $subcat->name }} </option> 
@endforeach