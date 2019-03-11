@if($button == 'text')

<div class="back-block"> <a href="javascript:void(0);" onclick="javascript:showbubblestep(0);"><i class="fa fa-chevron-circle-left"></i><span>戻る</span></a> </div>
<div>
  <p>ハコレンタカーの●●です！<br />旅に関する素敵なことわざ、聞いていってください。<br />
  <span class="random_text">{!! $random_text !!}</span>
  </p>
  <div class="d-flex justify-content-around"><a href="javascript:void(0);" class="suggest-btn bg-gry">Facebook Share Button</a> </div>
</div>
@endif

@if($step_number == 0)

<div id="bubble_content">
  <div class="company-block">
      <h2>レンタカー会社なんてどこも同じ・・・ ではありません！ 私にご案内させてください！</h2>
      <div class="company-btn-block">
        <ul>
          <li class="green-btn"> <a href="javascript:void(0);" onclick="javascript:showbubblestep(1);">試しに頼んでみる</a> </li>
          <li class="gray-btn"> <a href="javascript:void(0);" onclick="javascript:showbubbletext();">嫌だ、頼まない</a> </li>
        </ul>
      </div>
   </div>
</div>

@endif
 
@if($step_number == 1)


<div class="car-listing">
  <div class="back-block"> <a href="javascript:void(0);" onclick="javascript:showbubblestep(0);"><i class="fa fa-chevron-circle-left"></i><span>戻る</span></a> </div>
  <form name="select_car_class" id="select_car_class">
  <h2>ありがとうございます！どんな車をお探しですか？</h2>
  <ul>
  @foreach($carClass as $class)
    <li class="bubble_car_class">
      <div class="checkbox">
        <input type="checkbox" id="car_class_{{ $class->id }}" name="bubble_car_class" value="{!! $class->id !!}">
        <label for="check1">
        <img src="{{ URL::to('/').$class->thumb_path}}">
        <h3> 
        
        @if($class->name == 'CW2')
        みんなでワイワイ乗れるコスパ最高の車
        @elseif($class->name == 'HW')
        高級で広々、ビップカーでおもてなし
        @elseif($class->name == 'K2')
        コンパクトで少人数用の格安車
        @endif         
        
        </h3>
        </label>
      </div>
    </li>        
  @endforeach  
  </ul>
  <div class="ok-block">
    <input name="submit" id="submit_car_class" disabled="disabled" onclick="javascript:showbubblestep(2);" value="OK" type="button">
  </div>
  </form>
</div>

<script type="text/javascript">
	$(document).ready(function(e) {
		$('li.bubble_car_class').on('click', function() {

			$('#data_class_id').val(0);
			$('li.bubble_car_class').find("input[name='bubble_car_class']").attr('checked', false);	
			var selectedClass = $(this).find("input[name='bubble_car_class']").val();
			if($(this).hasClass('active')){				 
				$('#car_class_'+selectedClass).attr('checked', false);
			}else{				 			
				$('#car_class_'+selectedClass).attr('checked', true);
				$('#data_class_id').val(selectedClass);				
			}																	  			
			$('li.bubble_car_class').not(this).removeClass('active');  
			$('#submit_car_class').prop("disabled", false);
		});
	});	
</script>
@endif


@if($step_number == 2)

<div class="prepare-block">
  <div class="back-block"> <a href="javascript:void(0);" onclick="javascript:showbubblestep(1);"><i class="fa fa-chevron-circle-left"></i><span>戻る</span></a> </div>
  <form name="select_car_option" id="select_car_option">
  <h2>その車！ご用意できます！こんなオプションは必要ですか？</h2>
  <ul class="prepare-listing">
    
    @foreach($car_options->classOptions as $option)
    <li class="bubble_car_options"> <a href="#">
      <input type="checkbox" name="bubble_car_option[]" value="{!! $option->id !!}" id="car_option_{!! $option->id !!}" class="hidden">
      <span>{!! $option->name !!}&yen;{!! $option->price !!}</span> </a> </li>
    @endforeach
     
    <li class="bubble_car_options"> <a href="#">
      <input type="checkbox" name="car_ins_checked" value="1" id="car_ins_checked" class="hidden">
      <span>ワイド免責補償を付ける ~ {!! number_format($insurance_price1 + $insurance_price2) !!}円</span> </a>     
    </li>
  </ul>
  <div class="let-block">
    <input type="hidden" name="insurance_type" id="insurance_type" value="2" />
    <input type="hidden" id="ins_cost1" value="{!! $insurance_price1 !!}">
    <input type="hidden" id="ins_cost2" value="0">      
    <input type="button" id="submit_car_option" onclick="javascript:showbubblestep(3);"  value="Let's Go">
  </div>
  </form>
</div>


<script type="text/javascript">
	$(document).ready(function(e) {
		$('li.bubble_car_options').on('click', function() {
			
			if($(this).find("input[name='car_ins_checked']").length){
				if($(this).hasClass('active')){				 
					$('#car_ins_checked').attr('checked', false);
				}else{				 			
					$('#car_ins_checked').attr('checked', true);			
				}
			}else{
				var selectedOption = $(this).find("input[name='bubble_car_option[]']").val();		
				if($(this).hasClass('active')){				 
					$('#car_option_'+selectedOption).attr('checked', false);
				}else{				 			
					$('#car_option_'+selectedOption).attr('checked', true);			
				}
			}
						   
		});
	});	
</script>

@endif


@if($step_number == 3)

<div class="when-block">
<form name="select_booking" id="select_booking">
    <div class="back-block">
        <a href="javascript:void(0);" onclick="javascript:showbubblestep(2);"><i class="fa fa-chevron-circle-left"></i><span>戻る</span></a>
    </div>
    <div class="when-inner">
        <h2>ありがとうございます！ お車はいつ、どこで必要ですか！</h2>
        <h3>ご利用場所</h3>
        <ul class="airport-opt">
            
            @foreach($shops as $key=>$shop)
            <li class= "bubble_car_shop @if($loop->first) active @endif">
                <input type="radio" @if($loop->first) checked="checked" @endif name="bubble_departing_shop" id="departing_shop_{!! $shop->id !!}" value="{!! $shop->id !!}">
                <label for="{{$shop->name}}">{{$shop->name}}</label>
            </li>
            @endforeach
             
        </ul>
        <div class="deprt-block">
            <div class="deprt-pickr">
                <div class="input-group date" id="departing-datepicker">
                    <input type='text' class="form-control input-sm" name="bubble_departing_date" id="bubble_departing_date" readonly="readonly" onchange="javascript:enable_submit(this);" />
                    <span class="input-group-addon input-sm">
                    <span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
            <div class="deprt-time">
		<select name="bubble_departing_time" id="bubble_departing_time">          
            @foreach($hours as $hour)          
              <option value="{!! $hour !!}">{!! $hour !!}</option>          
            @endforeach
        </select>
            </div>
            <div class="deprt-txt">
                <span>時に出発</span>
            </div>
        </div>
        <div class="night-block">
        <select name="numbers_nights" id="numbers_nights" >
          <option value="0">0</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
          <option value="9">9</option>
          <option value="10">10</option>
        </select>
            <span>泊</span>
        </div>
        <div class="srch-block">
            <input type="button" name="submit" id="submit_car_booking" disabled="disabled" value="Search" onclick="javascript:showbubblestep(4);" />
        </div>
    </div>
</form>
</div>
<script type="text/javascript">
    $(document).ready(function(e) {		 
		$('#departing-datepicker').datepicker({
		language: "ja",
		format: 'yyyy/mm/dd',
		startDate: "{!! $booking_available_date !!}",
        endDate: '{{ date('Y-m-d', strtotime(date("Y-m-d", time()) . " + 1 year")) }}',
		orientation: "bottom",
		todayHighlight: true,
		daysOfWeekHighlighted: "0,6",
		autoclose: true
	    }).on('changeDate', function (ev) {

			var today         = new Date().toDateString();
			var curreSelected = new Date($('#bubble_departing_date').val()).toDateString(); 
            var options_hours = '';			
			if(today == curreSelected){
				@foreach($hours as $hour)          
				  options_hours +='<option value="{!! $hour !!}">{!! $hour !!}</option>';
				@endforeach				
			}else{
			  options_hours +='<option value="09:00" > 09:00 </option><option value="09:30" > 09:30 </option><option value="10:00" > 10:00 </option><option value="10:30" > 10:30 </option><option value="11:00" > 11:00 </option><option value="11:30" > 11:30 </option><option value="12:00" > 12:00 </option><option value="12:30" > 12:30 </option><option value="13:00" > 13:00 </option><option value="13:30" > 13:30 </option><option value="14:00" > 14:00 </option><option value="14:30" > 14:30 </option><option value="15:00" > 15:00 </option><option value="15:30" > 15:30 </option><option value="16:00" > 16:00 </option><option value="16:30" > 16:30 </option><option value="17:00" > 17:00 </option><option value="17:30" > 17:30 </option><option value="18:00" > 18:00 </option><option value="18:30" > 18:30 </option><option value="19:00" > 19:00 </option><option value="19:30" > 19:30 </option>';	
			}
			$('#bubble_departing_time').html(options_hours);
		});;
		
	});
	
	function enable_submit(obj){
		if($(obj).val()){
			$('#submit_car_booking').prop("disabled", false);
		}else{
			$('#submit_car_booking').prop("disabled", true);
		}
	}	 
	
    $(document).ready(function(e) {
		
		$('li.bubble_car_shop').on('click', function() {
						 
			var selectedOption = $(this).find("input[name='bubble_departing_shop']").val();		
			$('.bubble_car_shop').find('input').removeAttr('checked');
			if($(this).hasClass('active')){				 
				$('#departing_shop_'+selectedOption).attr( "checked", false );
			}else{				 			
				$('#departing_shop_'+selectedOption).attr( "checked", true );
				$('#data_depart_shop').val(selectedOption);
			}			 
						   
		});
	});		
	
</script>
@endif


@if($step_number == 4)

<div class="qut-block">
    <div class="back-block">
        <a href="javascript:void(0);" onclick="javascript:showbubblestep(3);"><i class="fa fa-chevron-circle-left"></i><span>戻る</span></a>
    </div>
    <div class="qut-inner">
        <h2>それならこのお車が最適です！</h2>
        <img src="{{ URL::to('/').$carClass->thumb_path}}">
        <div class="qut-txt">
            <h3>お見積もり条件	{!! $carPrice !!} 円</h3>
            <h4>残り在庫<span id="bubble_car_count">{!! $car_count !!}</span>台</h4>
            <ul>
                <li><span>禁煙/喫煙</span></li>
                <li>                     
                    <select name="bubble_car_smoking" id="bubble_car_smoking">
                      <option value="0">禁煙</option>
                      <option value="1">喫煙</option>
                      <option value="both" selected="">どちらでも良い</option>
                    </select>                    
                </li>
            </ul>
            <div class="book-block">
            
				<input name="submit" @if($car_count == 0) disabled="disabled" @endif id="submit_car_booking_button" onclick="javascript:showbubblestep(5);"  value="この車を予約する" type="button">            
                <input type="button" value="自分で探す" onclick="location.href='{!! url('/search-car') !!}';">
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$('#bubble_car_smoking').change(function () {
		var cid = $(this).attr('class_id');
		var data = {
			_token      : $('input[name="_token"]').val(),
			class_id    : $('#data_class_id').val(),
			smoke       : $(this).val(),
			depart_date : $('#data_depart_date').val(),
			depart_time : $('#data_depart_time').val(),
			return_date : $('#data_return_date').val(),
			return_time : $('#data_return_time').val(),
			depart_shop : $('#data_depart_shop').val(),
			return_shop : $('#data_return_shop').val(),
			category    : $('#data_car_category').val(),
			passenger   : 'all',
		};
		$.ajax({
			url  : '{!! URL::to("/class-search") !!}',
			type : 'post',
			data :  data,
			success : function(result,status,xhr) {                    
				if(status != 'success') return;
				if(isJson(result)){
					var data = JSON.parse(result);
					if(data.error != '' || data.success != 'true') return;
					var cls = data.class;
					var cid = cls.id;
					
					// use new values						
					$('#bubble_car_count').html(cls.car_count);
					if(cls.car_count === 0) {
						$('#submit_car_booking_button').removeAttr('disabled').attr('disabled','disabled');
					} else {
						$('#submit_car_booking_button').removeAttr('disabled');
					}
				}
			},
			error : function(xhr,status,error){
				console.log(error);
			}
		});
	});
	
	function isJson(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}		
</script>
@endif