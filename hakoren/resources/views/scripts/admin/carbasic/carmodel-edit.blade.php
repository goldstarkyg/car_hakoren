<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
<script src="{{URL::to('/')}}/js/multiimageupload.js"></script>

<script>
    /*search and select model*/
    $(".chosen-select").chosen({
        max_selected_options: 5,
        no_results_text: "Oops, nothing found!",
    });
    //get current type list from category_id
        var type_id = '{{$carmodel->type_id}}';
        var category_id = $('#category_id').val();
        var url = '{{URL::to('/')}}/carbasic/carmodela/getcartype';
        var token = $('input[name="_token"]').val();
        var data = [];
        data.push({name: 'category_id', value: category_id}, {name: '_token', value: token});
        data = jQuery.param(data);
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            async: false,
            dataType: "json",
            error: function (jqXHR, textStatus, errorThrown) {
                $("#company_inform").html(errorThrown);
            },
            success: function (content) {
                $('form select[name="type_id"]').html("");
                $.each(content, function(k,v){
                    var select= '';
                    if(v.id == type_id) select = 'selected';
                    $('form select[name="type_id"]').prepend("<option value='"+v.id+"' "+select+">"+ v.name+"</option>");
                });
                $('form select[name="type_id"]').trigger("chosen:updated");
            }
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