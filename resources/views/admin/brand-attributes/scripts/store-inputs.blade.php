<script>
    $('#store-inputs').on('submit', function (event) {
        event.preventDefault();
        let form = $(this);
        let formData = form.serialize();
        let url = form.attr('action');
        let method = form.attr('method');
        $.ajax({url: url, type: method, data: formData,
            success: function (response) {
                $('.preview').empty();
                $("#generate-new-input").trigger("reset");
                flasher.success("success",response.message+"");
            },
            error: function (data) {
                $.each(data.responseJSON.errors, function (key, value) {
                    flasher.error("error",value+"");
                });

            }
        });
    });
</script>
