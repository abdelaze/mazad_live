@forelse($attributes as $attribute)

    @if($attribute->input_type === 'text' || $attribute->input_type === 'color')
    <div class="col-md-4">
    <div class="mb-3">
        <label for="{{$attribute->input_name}}" class="form-label">{{$attribute->input_label}}</label>
        <input type="{{$attribute->input_type}}" id="{{$attribute->input_name}}"  name="options[{{$attribute->input_name}}]" class="form-control" required>
    </div>
    </div>
    @endif

    @if($attribute->input_type === 'number')
        <div class="col-md-4">
            <div class="mb-3">
                <label for="{{$attribute->input_name}}" class="form-label">{{$attribute->input_label}}</label>
                <input type="{{$attribute->input_type}}" id="{{$attribute->input_name}}" min="0"  name="options[{{$attribute->input_name}}]" class="form-control" required>
            </div>
        </div>
    @endif

    @if($attribute->input_type === 'textarea')
        <div class="col-md-4">
            <div class="mb-3">
                <label for="{{$attribute->input_name}}" class="form-label">{{$attribute->input_label}}</label>
                <textarea name="options[{{$attribute->input_name}}]" id="{{$attribute->input_name}}" class="form-control" required></textarea>
            </div>
        </div>
    @endif

    @if($attribute->input_type === 'radio')
        <div class="col-md-4">
            <div class="mb-3">
                <label class="font-size-14">{{ $attribute->input_label }}</label>
                @foreach(json_decode($attribute->options) as $key => $option)
                    <div class="form-check">
                        <input name="options[{{$attribute->input_name}}]" value="{{$option}}" class="form-check-input" type="radio" required>
                        <label class="form-check-label">{{json_decode($attribute->options_label)[$key]}}</label>
                    </div>
                @endforeach

            </div>
        </div>
    @endif

    @if($attribute->input_type === 'checkbox')
        <div class="col-md-4">
            <div class="mb-3">
                <label class="font-size-14">{{ $attribute->input_label }}</label>
                @foreach(json_decode($attribute->options) as $key => $option)
                    <div class="form-check">
                        <input id="{{$attribute->input_name}}" name="options[{{$attribute->input_name}}][]" value="{{$option}}" class="form-check-input" type="checkbox">
                        <label for="{{$attribute->input_name}}" class="form-check-label">{{json_decode($attribute->options_label)[$key]}}</label>
                    </div>
                @endforeach

            </div>
        </div>
    @endif


    @if($attribute->input_type === 'select')
        <div class="col-md-4">
            <div class="mb-3">
                <label class="font-size-14">{{ $attribute->input_label }}</label>
                <select name="options[{{$attribute->input_name}}]" class="form-control select2">
                    <option disabled selected>{{__('dashboard.select')}}</option>
                    @foreach(json_decode($attribute->options) as $key => $option)
                    <option value="{{$option}}">{{json_decode($attribute->options_label)[$key]}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

@empty
@endforelse
@include('admin.partials.scripts.init-select2')
