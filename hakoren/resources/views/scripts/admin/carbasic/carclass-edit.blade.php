<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
<script src="{{URL::to('/')}}/js/multiimageupload.js"></script>
<script>
    $(document).ready(function() {
        var $model_sort = $('#model_sort');
        var $model_orders = $('#model_orders');
        function getOrder() {
            var models = $model_sort.find('li');
            var orders = [];
            for(var k = 0; k < models.length; k++) {
                orders.push($(models[k]).attr('order'));
            }
            $model_orders.val(orders.join(','));
        }
        $model_sort.sortable({
            create: function( event, ui ) { getOrder(); },
            update: function( event, ui ) { getOrder(); }
        });
        $model_sort.disableSelection();

        $('#statusBtn span').on('click', function () {
            var sel = $(this).data('value');
            var tog = $(this).data('toggle');
            $('#' + tog).val(sel);
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');
        });

        //set model
        var values = $("#car_model").chosen().val();
        $("#car_models").val(values);

        //set suggests
        values = $("#car_suggest_classes").chosen().val();
        $("#suggest_list").val(values);

        //select charge system
        var sel = $('#status').val();
        var tog = $('#statusBtn span').data('toggle');
        $('#' + tog).val(sel);
        $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
        $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');

        //get options when loading
        var values = $("#car_option").chosen().val();
        $("#car_options").val(values);

        //get equipments when loading
        var values = $("#car_equipment").chosen().val();
        $("#car_equipments").val(values);
    });

    /*search and select model*/
    $(".chosen-select").chosen({
        max_selected_options: 20,
        no_results_text: "Oops, nothing found!",
        width: '100%'
    });

    //save car models
    $("#car_model").chosen().change(function(e, params){
        var values = $("#car_model").chosen().val();
        $("#car_models").val(values);
    });

    //save car models
    $("#car_suggest_classes").chosen().change(function(e, params){
        var values = $("#car_suggest_classes").chosen().val();
        $("#suggest_list").val(values);
    });

    // save car options
    $("#car_option").chosen().change(function(e, params){
        var values = $("#car_option").chosen().val();
        $("#car_options").val(values);
    });

    //save equipmetns
    $("#car_equipment").chosen().change(function(e, params){
        var values = $("#car_equipment").chosen().val();
        $("#car_equipments").val(values);
    });
    // save passenger tags
    $("#car_psgtag").chosen().change(function(e, params){
        var values = $("#car_psgtag").chosen().val();
        $("#car_psgtags").val(values);
    });

    //chnage event insurance
    $('#ins_first').on('change paste keyup',function(){
        var value = $('#ins_first').val();
        if(value == "") value = 0;
        var val = value+"_"+$('input[name="first_ins_id"]').val();
        $('input[name="first_val"]').val(val);
    });
    $('#ins_second').on('change paste keyup',function(){
        var value = $('#ins_second').val();
        if(value == "") value = 0;
        var val = value+"_"+$('input[name="second_ins_id"]').val();
        $('input[name="second_val"]').val(val);
    });

    $('input[name="thumb_path"]').change( function (e) {
        var files = e.target.files;
        var done = function (url) {
            $('#class_thumb').attr('src', url);
        };
        var reader, file, url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });



</script>