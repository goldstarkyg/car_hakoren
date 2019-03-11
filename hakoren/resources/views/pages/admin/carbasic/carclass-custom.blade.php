<style>
    .empty_td{
        background-color:#ECECEC;
    }

    .datepicker {
        background: #fff;
        border: 1px solid #555;
    }
    .ui-datepicker-week-end, .ui-datepicker-week-end a.ui-state-default {color:red;}
</style>

{!! Form::model($customs, array('action' =>'CarClassController@updatecustom',  'class' => 'form-horizontal', 'id'=>'customform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}

{{ csrf_field() }}
<input type="hidden" name="class_id" value="{{$carclass->id}}" />
<input type="hidden" name="id" value="0" >
<input type="hidden" name="method" value="CREATE">
<div style="background-color: #f0f0f0;" >
<div class="row m-t-md" style="padding: 30px;">
   <div class="form-group">
       <label for="title" class="col-sm-2 control-label">タイトル</label>
       <div class="col-sm-10">
           <div>
               <input type="text" name="title" class="form-control" required >
               <input type="hidden" name="count_flag" value="priceup">
           </div>
       </div>
   </div>
    <div  class="form-group">
        <div class="col-md-4">
            <div id="startdate" class="input-group date">
                <input type="text" name="startdate"  readonly class="form-control" required placeholder="開始日">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div id="enddate"  class="input-group date">
                <input type="text" name="enddate" id="enddate" readonly class="form-control" required placeholder="終了日" >
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group" id="percent_group">
                <input type="number" name="percent" id="percent" min="0" max="100" class="form-control" required>
                <div class="input-group-addon">
                    <span>% </span>
                </div>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-triangle-top percent_icon" style="color: blue" title="アップ" onclick="changePercent('priceup')"></span>
                </div>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-triangle-bottom percent_icon" style="color: red" title="ダウン" onclick="changePercent('discount')"></span>
                </div>
            </div>
        </div>
    </div>
</div>
    <input type="hidden" class="form-control" name="d1_day1"    value="0" >
    <input type="hidden" class="form-control" name="d1_total"   value="0" >
    <input type="hidden" class="form-control" name="n1d2_day1"  value="0" >
    <input type="hidden" class="form-control" name="n1d2_day2"  value="0" >
    <input type="hidden" class="form-control" name="n1d2_total" value="0" >
    <input type="hidden" class="form-control" name="n2d3_day1"  value="0" >
    <input type="hidden" class="form-control" name="n2d3_day2"  value="0" >
    <input type="hidden" class="form-control" name="n2d3_day3"  value="0" >
    <input type="hidden" class="form-control" name="n2d3_total" value="0" >
    <input type="hidden" class="form-control" name="n3d4_day1"  value="0" >
    <input type="hidden" class="form-control" name="n3d4_day2"  value="0" >
    <input type="hidden" class="form-control" name="n3d4_day3"  value="0" >
    <input type="hidden" class="form-control" name="n3d4_day4"  value="0" >
    <input type="hidden" class="form-control" name="n3d4_total" value="0" >
    <input type="hidden" class="form-control" name="additional_day"   value="0" >
    <input type="hidden" class="form-control" name="additional_total" value="0" >

    <div class="table-responsive m-t-n-lg" style="padding-left: 10px;padding-right: 10px;" >
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="col-md-2">
                    通常料金 <br><br><br>
                    <label id="price_title" class="customprice"> 変更後の料金 </label>
                </th>
                <th class="col-md-2 text-center">
                    日帰り
                    <label class="normalprice">{{$normal->d1_total}}円</label>
                    <label class="normalmiddle">
                        <span class="glyphicon glyphicon glyphicon-arrow-down"></span>
                    </label>
                    <label class="customprice">
                        <input type="text" class="form-control" name="d1_total" readonly value="0"  >
                    </label>
                </th>
                <th class="col-md-2 text-center">
                    1泊2日
                    <label class="normalprice">{{$normal->n1d2_total}}円</label>
                    <label class="normalmiddle">
                        <span class="glyphicon glyphicon glyphicon-arrow-down"></span>
                    </label>
                    <label class="customprice">
                        <input type="text" class="form-control" name="n1d2_total" readonly value="0"  >
                    </label>
                </th>
                <th class="col-md-2 text-center">
                    2泊3日
                    <label class="normalprice">{{$normal->n2d3_total}}円</label>
                    <label class="normalmiddle">
                        <span class="glyphicon glyphicon glyphicon-arrow-down"></span>
                    </label>
                    <label class="customprice">
                        <input type="text" class="form-control" name="n2d3_total" readonly value="0"  >
                    </label>
                </th>
                <th class="col-md-2 text-center">
                    3泊4日
                    <label class="normalprice">{{$normal->n3d4_total}}</label>
                    <label class="normalmiddle">
                        <span class="glyphicon glyphicon glyphicon-arrow-down"></span>
                    </label>
                    <label class="customprice">
                        <input type="text" class="form-control" name="n3d4_total" readonly value="0"  >
                    </label>
                </th>
                <th class="col-md-2 text-center">
                    1日追加
                    <label class="normalprice">{{$normal->additional_total}}円</label>
                    <label class="normalmiddle">
                        <span class="glyphicon glyphicon glyphicon-arrow-down"></span>
                    </label>
                    <label class="customprice">
                        <input type="text" class="form-control" name="additional_total" readonly value="0"  >
                    </label>
                </th>
            </tr>
            </thead>
        </table>
    </div>
<!---->
<div class="form-group">
    <div class="pull-right m-r-md">
        <label>
            {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/updatecustom', 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
            {!! Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> '特別料金の保存',
                    'data-message' 		=> 'この特別料金の変更を保存しますか？'
            )) !!}
            {!! Form::close() !!}
        </label>
        {{--<label>--}}
            {{--{!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/updatecustom', 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}--}}
            {{--{!! Form::hidden('method', 'DELETE') !!}--}}
            {{--<input type="hidden" name="class_id" value="{{$custom->class_id}}" >--}}
            {{--{!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>--}}
                {{--<span class="hidden-xs hidden-sm">削除</span>',--}}
                {{--array('class' => 'btn btn-danger',--}}
                    {{--'type' => 'button' ,--}}
                    {{--'data-toggle' => 'modal',--}}
                    {{--'data-target' => '#confirmDelete',--}}
                    {{--'data-title' => '特別料金の削除',--}}
                    {{--'data-message' => 'この特別料金を本当に削除しますか？この操作を取り消すことはできません。')) !!}--}}
            {{--{!! Form::close() !!}--}}
        {{--</label>--}}
    </div>
</div>
</div>
{!! Form::close() !!}

<div class="table-responsive users-table" id="showCarClassCustomData">
</div>

<script>
    //event when click input text
//    $('input').keyup(function(event) {
//        var name = $(event.target).attr('name');
//        var val = $(this).val();
//        var total = 0 ;
//        switch(name) {
//            case 'd1_day1':
//                $('#customform input[name="d1_total"]').val(val);
//                break;
//            case 'n1d2_day1':
//                total = parseInt($('#customform input[name="n1d2_day1"]').val())+parseInt($('#customform input[name="n1d2_day2"]').val());
//                $('#customform input[name="n1d2_total"]').val(total);
//                break;
//            case 'n1d2_day2':
//                total = parseInt($('#customform input[name="n1d2_day1"]').val())+ parseInt($('#customform input[name="n1d2_day2"]').val());
//                $('#customform input[name="n1d2_total"]').val(total);
//                break;
//            case 'n2d3_day1':
//                total = parseInt($('#customform input[name="n2d3_day1"]').val())+parseInt($('#customform input[name="n2d3_day2"]').val())+parseInt($('#customform input[name="n2d3_day3"]').val());
//                $('#customform input[name="n2d3_total"]').val(total);
//                break;
//            case 'n2d3_day2':
//                total = parseInt($('#customform input[name="n2d3_day1"]').val())+parseInt($('#customform input[name="n2d3_day2"]').val())+parseInt($('#customform input[name="n2d3_day3"]').val());
//                $('#customform input[name="n2d3_total"]').val(total);
//                break;
//            case 'n2d3_day3':
//                total = parseInt($('#customform input[name="n2d3_day1"]').val())+parseInt($('#customform input[name="n2d3_day2"]').val())+parseInt($('#customform input[name="n2d3_day3"]').val());
//                $('#customform input[name="n2d3_total"]').val(total);
//                break;
//            case 'n3d4_day1':
//                total = parseInt($('#customform input[name="n3d4_day1"]').val())+parseInt($('#customform input[name="n3d4_day2"]').val())+parseInt($('#customform input[name="n3d4_day3"]').val())+parseInt($('#customform input[name="n3d4_day4"]').val());
//                $('#customform input[name="n3d4_total"]').val(total);
//                break;
//            case 'n3d4_day2':
//                total = parseInt($('#customform input[name="n3d4_day1"]').val())+parseInt($('#customform input[name="n3d4_day2"]').val())+parseInt($('#customform input[name="n3d4_day3"]').val())+parseInt($('#customform input[name="n3d4_day4"]').val());
//                $('#customform input[name="n3d4_total"]').val(total);
//                break;
//            case 'n3d4_day3':
//                total = parseInt($('#customform input[name="n3d4_day1"]').val())+parseInt($('#customform input[name="n3d4_day2"]').val())+parseInt($('#customform input[name="n3d4_day3"]').val())+parseInt($('#customform input[name="n3d4_day4"]').val());
//                $('#customform input[name="n3d4_total"]').val(total);
//                break;
//            case 'n3d4_day4':
//                total = parseInt($('#customform input[name="n3d4_day1"]').val())+parseInt($('#customform input[name="n3d4_day2"]').val())+parseInt($('#customform input[name="n3d4_day3"]').val())+parseInt($('#customform input[name="n3d4_day4"]').val());
//                $('#customform input[name="n3d4_total"]').val(total);
//                break;
//            case 'additional_day':
//                total = parseInt($('#customform input[name="additional_day"]').val());
//                $('#customform input[name="additional_total"]').val(total);
//                break;
//        }
//
//    });

    var percent_cond = 'priceup'; //price up and discount condition
    //setting datepicker with default value
    var dateToday = new Date();
    var enddate = new Date();
    $('#startdate').datepicker({
        language: "ja",
        format: 'yyyy/mm/dd',
        orientation: "auto",
        todayHighlight: true,
        startDate: dateToday,
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
    }).on('changeDate',function(){
        var sdate = $('input[name="startdate"]').val();
        $('#enddate').datepicker('setStartDate', sdate);
    });

    $('#enddate').datepicker({
        language: "ja",
        format: 'yyyy/mm/dd',
        orientation: "auto",
        todayHighlight: true,
        startDate: enddate,
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
    }).on('changeDate',function(){
        var sdate = $('input[name="enddate"]').val();
        $('#startdate').datepicker('setEndDate', sdate);
    });

    //when click edit button to update
    var custom = {};
    @if(session('custom')) custom = <?php echo json_encode(session('custom')) ?>; @endif
    if(custom.title){
        $('#customform input[name="id"]').val(custom.id);
        $('#customform input[name="class_id"]').val(custom.class_id);
        $('#customform input[name="title"]').val(custom.title);
        $('#customform input[name="startdate"]').val(custom.startdate);
        $('#customform input[name="enddate"]').val(custom.enddate);
        $('#customform input[name="percent"]').val(custom.percent);
        $('#customform input[name="count_flag"]').val(custom.count_flag);
        $('#customform input[name="d1_day1"]').val(custom.d1_day1);
        $('#customform input[name="d1_total"]').val(custom.d1_total);
        $('#customform input[name="n1d2_day1"]').val(custom.n1d2_day1);
        $('#customform input[name="n1d2_day2"]').val(custom.n1d2_day2);
        $('#customform input[name="n1d2_total"]').val(custom.n1d2_total);
        $('#customform input[name="n2d3_day1"]').val(custom.n2d3_day1);
        $('#customform input[name="n2d3_day2"]').val(custom.n2d3_day2);
        $('#customform input[name="n2d3_day3"]').val(custom.n2d3_day3);
        $('#customform input[name="n2d3_total"]').val(custom.n2d3_total);
        $('#customform input[name="n3d4_day1"]').val(custom.n3d4_day1);
        $('#customform input[name="n3d4_day2"]').val(custom.n3d4_day2);
        $('#customform input[name="n3d4_day3"]').val(custom.n3d4_day3);
        $('#customform input[name="n3d4_day4"]').val(custom.n3d4_day4);
        $('#customform input[name="n3d4_total"]').val(custom.n3d4_total);
        $('#customform input[name="additional_day"]').val(custom.additional_day);
        $('#customform input[name="additional_total"]').val(custom.additional_total);
        $("#customform input").each(function() {
            var name = $(this).attr('name');
            var names = ['d1_day1','d1_total','n1d2_day1','n1d2_day2','n1d2_total','n2d3_day1',
                'n2d3_day2','n2d3_day3','n2d3_total','n3d4_day1','n3d4_day2','n3d4_day3','n3d4_day4',
                'n3d4_total','additional_day','additional_total'];
            var flag = jQuery.inArray( name, names );
            if(flag != -1) {
                var num = $(this).val();
                var commaNum = numberWithCommas(num);
                $(this).val(commaNum);
            }
        });
        var cond = custom.count_flag;
        percent_cond = cond;
        var percent_val = $('#percent').val();
        var percent_attr = "";
        if(cond == 'priceup') percent_attr = '<span class="glyphicon glyphicon-triangle-top" style="color: blue" title="priceup"></span>';
        if(cond == 'discount') percent_attr = '<span class="glyphicon glyphicon-triangle-bottom" style="color: red" title="discount"></span>';
        var html = percent_val+'% '+percent_attr+" 料金";
        $('#price_title').html(html);
        if(cond == 'priceup')  $('.customprice').css('color','blue');
        if(cond == 'discount') $('.customprice').css('color','red');
    };

    //add new line after normal price
    $(".normalprice" ).before( "<br />" );
    $(".normalmiddle" ).before( "<br />" );
    $(".customprice" ).before( "<br />" );

    //handle custom price folloing percent
    function changePercent(cond) {
        $('#customform input[name="count_flag"]').val(cond);
        percent_cond = cond;
        var percent_val = $('#percent').val();
        var percent_attr = '';
        if(cond == 'priceup') percent_attr = '<span class="glyphicon glyphicon-triangle-top" style="color: blue" title="アップ"></span>';
        if(cond == 'discount') percent_attr = '<span class="glyphicon glyphicon-triangle-bottom" style="color: red" title="ダウン"></span>';
        var html = percent_val+'% '+percent_attr+" 料金";
        $('#price_title').html(html);
        var d1_day1     = cal('{{$normal->d1_day1}}', percent_val, cond);
        $('#customform input[name="d1_day1"]').val(d1_day1);
        var d1_total    = d1_day1;
        $('#customform input[name="d1_total"]').val(d1_total);
        var n1d2_day1   = cal('{{$normal->n1d2_day1}}', percent_val, cond);
        $('#customform input[name="n1d2_day1"]').val(n1d2_day1);
        var n1d2_day2   = cal('{{$normal->n1d2_day2}}', percent_val, cond);
        $('#customform input[name="n1d2_day2"]').val(n1d2_day2);
        var n1d2_total  =  parseInt('{{$normal->n1d2_day1}}') +  parseInt('{{$normal->n1d2_day2}}');
        n1d2_total      = cal(n1d2_total , percent_val, cond);
        $('#customform input[name="n1d2_total"]').val(n1d2_total);
        var n2d3_day1   = cal('{{$normal->n2d3_day1}}', percent_val, cond);
        $('#customform input[name="n2d3_day1"]').val(n2d3_day1);
        var n2d3_day2   = cal('{{$normal->n2d3_day2}}', percent_val, cond);
        $('#customform input[name="n2d3_day2"]').val(n2d3_day2);
        var n2d3_day3   = cal('{{$normal->n2d3_day3}}', percent_val, cond);
        $('#customform input[name="n2d3_day3"]').val(n2d3_day3);
        var n2d3_total  = parseInt('{{$normal->n2d3_day1}}') + parseInt('{{$normal->n2d3_day2}}') + parseInt('{{$normal->n2d3_day3}}');
        n2d3_total      = cal(n2d3_total , percent_val, cond);
        $('#customform input[name="n2d3_total"]').val(n2d3_total);
        var n3d4_day1   = cal('{{$normal->n3d4_day1}}', percent_val, cond);
        $('#customform input[name="n3d4_day1"]').val(n3d4_day1);
        var n3d4_day2   = cal('{{$normal->n3d4_day2}}', percent_val, cond);
        $('#customform input[name="n3d4_day2"]').val(n3d4_day2);
        var n3d4_day3   = cal('{{$normal->n3d4_day3}}', percent_val, cond);
        $('#customform input[name="n3d4_day3"]').val(n3d4_day3);
        var n3d4_day4   = cal('{{$normal->n3d4_day4}}', percent_val, cond);
        $('#customform input[name="n3d4_day4"]').val(n3d4_day4);
        var n3d4_total  = parseInt('{{$normal->n3d4_day1}}') + parseInt('{{$normal->n3d4_day2}}') + parseInt('{{$normal->n3d4_day3}}') + parseInt('{{$normal->n3d4_day4}}');
        n3d4_total      = cal(n3d4_total , percent_val, cond);
        $('#customform input[name="n3d4_total"]').val(n3d4_total);
        var additional_day  = cal('{{$normal->additional_day}}', percent_val, cond);
        $('#customform input[name="additional_day"]').val(additional_day);
        var additional_total= additional_day;
        $('#customform input[name="additional_total"]').val(additional_total);
        if(cond == 'priceup')  $('.customprice').css('color','blue');
        if(cond == 'discount') $('.customprice').css('color','red');
        $("#customform input").each(function() {
            var name = $(this).attr('name');
            var names = ['d1_day1','d1_total','n1d2_day1','n1d2_day2','n1d2_total','n2d3_day1',
                        'n2d3_day2','n2d3_day3','n2d3_total','n3d4_day1','n3d4_day2','n3d4_day3','n3d4_day4',
                        'n3d4_total','additional_day','additional_total'];
            var flag = jQuery.inArray( name, names );
            if(flag != -1) {
                var num = $(this).val();
                var commaNum = numberWithCommas(num);
                $(this).val(commaNum);
            }
        });
    }
    //calculate percent
    function cal(val, percent_val, cond) {
        var normal  = parseInt(val);
        var custom  = 0;
        if(val != 0 && percent_val != 0 ) {
            var percent_value = Math.floor((normal/100)*percent_val);
            if(cond == 'priceup')   custom = normal + percent_value;
            if(cond == 'discount')  custom = normal - percent_value;
            var remain = custom % 100;
            custom = custom - (custom % 100);
            if(remain >= 50 ) custom = custom + 50;
        }
        return custom;
    }

    //load event
    $('#tab_custom').click(function(){
        $('.customprice').css('color','black');
        initial();
    });

	function fetchCarClassCustomData(carClassId, page){
		
	  var token  = $("input[name=_token]").val();	  
	  $('#showCarClassCustomData').html('<div align="center" style="margin-top:20px;">データのロード</div>');	   
	  $.ajax({
			type: "POST",
			dataType: "json",
			url  : "{!! url('/fetchCarClassCustomData') !!}",
			cache: false,
			data : ('page='+page+'&car_class_id='+carClassId+'&_token='+token),
			success:function(data){	
				$('#showCarClassCustomData').html(data);
			},
			error: function(data){
				console.log('Error, try again!');
			},
			timeout:10000
	 });	
	}

    //initial setting fields
    function initial(){
        $('#startdate').hide();
        $('#enddate').hide();
        $('#percent_group').hide();
        $('.normalmiddle').hide();
        $('.customprice').hide();
    }
    //show date when generate key event in title
    $('#customform input[name="title"]').keyup(function(){
        $('#startdate').show();
        $('#enddate').show();
    });
    //show price when generate key event in enddate
    $('#enddate').datepicker().on('changeDate',function(){
        $('#percent_group').show();
    });
    //show custom price when generate click event in percent_icon
    $('.percent_icon').click(function(){
        $('.normalmiddle').show();
        $('.customprice').show();
    });
    //format number
    function numberWithCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
    //run to format
    $(document).ready(function() {
		 
		fetchCarClassCustomData('{!! $carclass->id !!}', 1);		
       
		$(".normalprice").each(function() {
            var num = $(this).text();
            var commaNum = numberWithCommas(num);
            $(this).text(commaNum);
        });
        $(".custom_price_td").each(function() {
            var num = $(this).text();
            var commaNum = numberWithCommas(num);
            $(this).text(commaNum);
        });
    });
    //percent key event
    $('#percent').keyup(function(){
        changePercent(percent_cond);
        $('.normalmiddle').show();
        $('.customprice').show();
    });
	
$(document).ajaxComplete(function() {
    $('#showCarClassCustomData .pagination li a').click(function(e) {
        e.preventDefault();
        var page = $(this).attr('href');
		    page =(parseInt(page.replace ( /[^\d.]/g, '' )));
		fetchCarClassCustomData('{!! $carclass->id !!}', page);
    });	
});
</script>