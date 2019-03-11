<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>

<script>
    $(document).ready(function() {
        var sel = $('#charge_system').val();
        var tog = $('#radioBtn span').data('toggle');
        $('#' + tog).val(sel);
        $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
        $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');

    });
</script>