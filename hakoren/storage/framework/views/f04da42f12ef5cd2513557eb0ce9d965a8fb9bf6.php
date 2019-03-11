<script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#statusBtn span').on('click', function () {
            if( $(this).attr('disabled') ) return;
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
        //set model
        var values = $("#dropoff_id").chosen().val();
        $("#dropoff_ids").val(values);


        //select charge system
        for(var i= 0 ;i<3; i++ ) {
            var sel, tog;
            if(i==0) {
                sel = $('#smoke').val();
                tog = $('#smokeBtn span').data('toggle');
            }
            if(i==1) {
                sel = $('#dropoff_availability').val();
                tog = $('#dropoffBtn span').data('toggle');
                if(sel == '0') {
                    $('#other_locations').hide();
                    $("#dropoff_id").chosen().val('');
                    $('#dropoff_ids').val('');
                    //$('#dropoff_ids').trigger("chosen:updated");
                    $('.chosen-select').trigger('chosen:updated');
                }else {
                    $('#other_locations').show();
                }
            }
            if(i==2) {
                sel = $('#status').val();
                tog = $('#statusBtn span').data('toggle');
            }
            $('#' + tog).val(sel);
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');

        }

    });

    /*search and select model*/
    $(".chosen-select").chosen({
        max_selected_options: 5,
        no_results_text: "Oops, nothing found!",
    });
    $("#dropoff_id").chosen().change(function(e, params){
        var values = $("#dropoff_id").chosen().val();
        $("#dropoff_ids").val(values);
    });
</script>