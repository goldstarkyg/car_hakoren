{!! Form::model($time_2, array('action' => 'ShopController@updatebusinesscustom',
                           'method' => 'POST', 'role' => 'form','id'=>'hour2form',
                           'class' => 'form-horizontal','enctype'=>'multipart/form-data')) !!}
{{ csrf_field() }}
<input type="hidden" name="shop_id" value="{{$shop->id}}" >
<input type="hidden" name="id" value="0" >
<input type="hidden" name="method" value="CREATE">

<div class="form-group m-t-sm">
    <label for="date" class="col-sm-3 control-label">適用日</label>
    <div class="col-sm-5">
        <div class="input-group date" data-provide="datepicker">
            <input type="text" name="date" class="form-control" >
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="is_dayoff" class="col-sm-3 control-label">休日</label>
    <div class="col-sm-5">
        <input type="checkbox" name="is_dayoff" >
    </div>
</div>
<div class="form-group" id="start_time">
    <label for="start_time" class="col-sm-3 control-label">開始時刻</label>
    <div class="col-sm-5">
        <div class="input-group bootstrap-timepicker timepicker">
            <input  name="start_time" type="text"  class="form-control inputtime">
            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
        </div>
    </div>
</div>
<div class="form-group" id="end_time">
    <label for="end_time" class="col-sm-3 control-label">終了時刻</label>
    <div class="col-sm-5">
        <div class="input-group bootstrap-timepicker timepicker">
            <input  name="end_time" type="text" class="form-control inputtime">
            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">

        <label>
            {!! Form::open(array('url' => URL::to('/').'/shopbasic/updatebusinesscustom', 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
            {!! Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> '営業時間を保存',
                    'data-message' 		=> 'この営業時間を保存しますか？'
            )) !!}
            {!! Form::close() !!}
        </label>
        {{--<label>--}}
            {{--{!! Form::open(array('url' => URL::to('/').'/shopbasic/shop/' . $shop->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}--}}
            {{--{!! Form::hidden('_method', 'DELETE') !!}--}}
            {{--{!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>--}}
                {{--<span class="hidden-xs hidden-sm">削除</span>',--}}
                {{--array('class' => 'btn btn-danger',--}}
                    {{--'type' => 'button' ,--}}
                    {{--'data-toggle' => 'modal',--}}
                    {{--'data-target' => '#confirmDelete',--}}
                    {{--'data-title' => 'Delete Shop',--}}
                    {{--'data-message' => 'Do you want to permanently delete this car shop?')) !!}--}}
            {{--{!! Form::close() !!}--}}
        {{--</label>--}}
    </div>
</div>
{!! Form::close() !!}
<!--data list-->
<div class="table-responsive users-table">
    <table class="table table-striped table-condensed data-table" width="100%" id="customtable">
        <thead>
        <tr>
            <th>ID</th>
            <th>適用日</th>
            <th>開始時刻</th>
            <th>終了時刻</th>
            <th>休日</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($time_2))
            @foreach($time_2 as $hour)
                <tr  valign="middle">
                    <td style="vertical-align:middle;">{{str_pad($hour->id, 6, '0', STR_PAD_LEFT)}}</td>
                    <td style="vertical-align: middle;">{{$hour->date}}</td>
                    <td style="vertical-align: middle;">@if($hour->is_dayoff == '0') {{$hour->start_time}} @endif </td>
                    <td style="vertical-align: middle;">@if($hour->is_dayoff == '0') {{$hour->end_time}} @endif </td>
                    <td style="vertical-align: middle;">
                        @if($hour->is_dayoff == '1') 1日休日 @else --  @endif
                    </td>
                    <td style="vertical-align: middle;">
                        <label>
                            <a class="btn btn-sm btn-success" href="{{ URL::to('shopbasic/editbusinesscustom/' . $hour->id) }}" title="Edit">
                                <span class="hidden-xs hidden-sm">編集</span>
                            </a>
                        </label>
                        <label>
                            <a class="btn btn-danger btn-sm" href="{{ URL::to('shopbasic/deletebusinesscustom/' . $hour->id) }}" title="Edit">
                                <span class="hidden-xs hidden-sm">削除</span>
                            </a>
                        </label>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<script>
    $("input[name='is_dayoff']").change(function(e) {
        if(this.checked) {
            //Do stuff
            $("input[name='is_dayoff']").val('1');
            $("#start_time").hide();
            $("#end_time").hide();
        }else {
            $("input[name='is_dayoff']").val('0');
            $("#start_time").show();
            $("#end_time").show();
        }
    });
    var hour2 = {};
    @if(session('customhour')) hour2 = <?php echo json_encode(session('customhour')) ?>; @endif
    if(hour2.start_time){
        $('#hour2form input[name="id"]').val(hour2.id);
        $('#hour2form input[name="shop_id"]').val(hour2.shop_id);
        $('#hour2form input[name="date"]').val(hour2.date);
        $('#hour2form input[name="start_time"]').val(hour2.start_time);
        $('#hour2form input[name="end_time"]').val(hour2.end_time);
        $('#hour2form input[name="is_dayoff"]').val(hour2.is_dayoff);
        if(hour2.is_dayoff == '1') {
            $('#hour2form input[name="is_dayoff"]').attr('checked', true);
            $("#start_time").hide();
            $("#end_time").hide();
        }
    };
</script>