<div data-repeater-list="checkbox-options">
    <div data-repeater-item>
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <input type="text" name="label_checkbox" class="form-control" placeholder="{{__('translation.label')}}" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <input type="text" name="label_checkbox_ar" class="form-control" placeholder="{{__('translation.label_ar')}}" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <input type="text" name="value_checkbox" class="form-control" placeholder="{{__('translation.value')}}" required>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <input type="text" name="value_checkbox_ar" class="form-control" placeholder="{{__('translation.value_ar')}}" required>
                </div>
            </div>

            <div class="col-md-2">
                <div class="mb-3">
                    <input class="form-control btn btn-danger" data-repeater-delete type="button" value="Delete"/>
                </div>
            </div>
        </div>
    </div>
</div>
<input class="btn btn-success" data-repeater-create type="button" value="Add"/>
<script src="{{ asset(ASSET_PATH.'assets/backend/js/jq-repeater.js') }}"></script>
