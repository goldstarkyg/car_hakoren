<script>
    var thumbs = [];
    var public_url = '{{URL::to('/')}}';
</script>
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
<script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
<script src="{{URL::to('/')}}/js/multiimageupload.js"></script>
<script>
    /*search and select*/
    $(".chosen-select").chosen({
        max_selected_options: 5,
        no_results_text: "Oops, nothing found!",
    });

    /*get car type list*/
    $('#category_id').on('change',function() {
        var id = this.value;
        var url = '{{URL::to('/')}}/carbasic/carmodela/getcartype';
        var token = $('input[name="_token"]').val();
        var data = [];
        data.push({name: 'category_id', value: id}, {name: '_token', value: token});
        data = jQuery.param(data);
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            async: false,
            dataType: "json",
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
            success: function (content) {
                $('form select[name="type_id"]').html("");
                $.each(content, function(k,v){
                    $('form select[name="type_id"]').prepend("<option value='"+v.id+"'>"+ v.name+"</option>");
                });
                $('form select[name="type_id"]').prepend("<option value='0' selected >--Select a Car Type--</option>");
                $('form select[name="type_id"]').trigger("chosen:updated");
            }
        });
    });

</script>