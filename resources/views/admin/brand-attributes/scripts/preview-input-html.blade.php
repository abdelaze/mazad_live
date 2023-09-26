<script>
    $(document).ready(function () {
        $('#generate-new-input').on('submit', function (event) {
            event.preventDefault();
            let form = $(this);
            let formData = form.serialize();
            let url = form.attr('action');
            let method = form.attr('method');
            let spinner = form.find('.spinner-border');
            let preview = $('.preview');
            spinner.removeClass('d-none');
            $.ajax({url: url, type: method, data: formData,
                success: function (response) {
                    spinner.addClass('d-none');
                    preview.html(response.data);
                },
                error: function (data) {
                    spinner.addClass('d-none');
                    $.each(data.responseJSON.errors, function (key, value) {
                        flasher.error("error",value[0]);
                    });
                }
            });
        });
    });
</script>
