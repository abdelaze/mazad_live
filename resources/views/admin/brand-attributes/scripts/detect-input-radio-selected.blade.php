<script>
    $(document).ready(function () {
        $('.select2').on('change', function () {
            let inputType = $(this).val();
            if(inputType === 'radio') {
                $.ajax({
                    url: "{{ route('attributes.radio.input') }}",
                    method: "GET",
                    success: function (response) {
                        $('.input-details').html(response.data);
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }else if(inputType === 'checkbox'){
                $.ajax({
                    url: "{{route('attributes.checkbox.input')}}",
                    type: "GET",
                    success: function (response) {
                        $('.input-details').html(response.data);
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }else if(inputType === 'select'){
                $.ajax({
                    url: "{{route('attributes.select.input')}}",
                    type: "GET",
                    success: function (response) {
                        $('.input-details').html(response.data);
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            } else{
                $('.input-details').empty();
            }
        });
    });
</script>
