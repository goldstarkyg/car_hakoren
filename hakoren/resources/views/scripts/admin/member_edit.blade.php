
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
<script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>

<script>
    /* change category*/
    function SelectCategory(){
        var category = $('#category_id').val();
        if(category == '1') {
            $('#individual').css('display','block');
            $('#corporate').css('display','none');
            $('#foreigner').css('display','none');
        }
        if(category == '2') {
            $('#individual').css('display','none');
            $('#corporate').css('display','block');
            $('#foreigner').css('display','none');
        }
        if(category == '3') {
            $('#individual').css('display','none');
            $('#corporate').css('display','none');
            $('#foreigner').css('display','block');
        }
    }
    /*change birthday*/
    $('#birth').datepicker({
        language: "ja",
        format: 'yyyy/mm/dd',
        orientation: "bottom",
        todayHighlight: true,
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
    });
    $('#credit_card_expiration').datepicker({
        language: "ja",
        format: 'yyyy/mm/dd',
        orientation: "bottom",
        todayHighlight: true,
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
    });
    /*search and select*/
    $(".chosen-select").chosen();
    $("#user_group").chosen().change(function(e, params){
        var values = $("#user_group").chosen().val();
        $("#groups").val(values);
    });
    $(document).ready(function() {
        SelectCategory();
        var values = $("#user_group").chosen().val();
        $("#groups").val(values);
    });
</script>