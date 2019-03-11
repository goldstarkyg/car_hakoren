<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#radioBtn span').on('click', function () {
            var sel = $(this).data('value');
            var tog = $(this).data('toggle');
            $('#' + tog).val(sel);
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');
        });
        //set model
        var values = $("#car_class").chosen().val();
        $("#car_classes").val(values);

        //select charge system
        var sel = $('#charge_system').val();
        var tog = $('#radioBtn span').data('toggle');
        $('#' + tog).val(sel);
        $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
        $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');

    });

    /*search and select model*/
    $(".chosen-select").chosen();
    $("#car_class").chosen().change(function(e, params){
        var values = $("#car_class").chosen().val();
        $("#car_classes").val(values);
    });
</script>