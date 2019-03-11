<script>
    var thumbs = [];
    var public_url = '<?php echo e(URL::to('/')); ?>';
</script>
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
<script src="<?php echo e(URL::to('/')); ?>/js/plugins/fullcalendar/moment.min.js"></script>
<script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>
<script src="<?php echo e(URL::to('/')); ?>/js/multiimageupload.js"></script>

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
    });
    /*search and select*/
    $(".chosen-select").chosen({
        max_selected_options: 5,
        no_results_text: "Oops, nothing found!",
    });
    $("#car_model").chosen().change(function(e, params){
        var values = $("#car_model").chosen().val();
        $("#car_models").val(values);
    });
    $("#car_psgtag").chosen().change(function(e, params){
        var values = $("#car_psgtag").chosen().val();
        $("#car_psgtags").val(values);
    });
</script>