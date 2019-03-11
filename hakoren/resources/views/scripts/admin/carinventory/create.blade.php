<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
<script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#statusBtn span').on('click', function () {
            var sel = $(this).data('value');
            var tog = $(this).data('toggle');
            $('#' + tog).val(sel);
            // You can change these lines to change classes (Ex. btn-default to btn-danger)
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');
        });
        $('#smokeBtn span').on('click', function () {
            var sel = $(this).data('value');
            var tog = $(this).data('toggle');
            $('#' + tog).val(sel);
            // You can change these lines to change classes (Ex. btn-default to btn-danger)
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');
        });

        $('#dropoffBtn span').on('click', function () {
            var sel = $(this).data('value');
            var tog = $(this).data('toggle');
            $('#' + tog).val(sel);
            // You can change these lines to change classes (Ex. btn-default to btn-danger)
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');
            if(sel == '0') {
                $('#other_locations').hide();
                $("#dropoff_id").chosen().val('');
                $('#dropoff_ids').val('');
                //$('#dropoff_ids').trigger("chosen:updated");
                $('.chosen-select').trigger('chosen:updated');
            }else {
                $('#other_locations').show();
            }
        });
        /*search and select*/
        $(".chosen-select").chosen({
            max_selected_options: 5,
            no_results_text: "Oops, nothing found!",
        });
    });

    $("#dropoff_id").chosen().change(function(e, params){
        var values = $("#dropoff_id").chosen().val();
        $("#dropoff_ids").val(values);
    });

</script>