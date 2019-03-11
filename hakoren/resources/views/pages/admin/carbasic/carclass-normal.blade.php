<style>
    .empty_td{
        background-color:#ECECEC;
    }
</style>

{!! Form::model($normal, array('action' =>'CarClassController@updatenormal',  'class' => 'form-horizontal', 'id'=>'normalform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}

{{ csrf_field() }}
<input type="hidden" name="class_id" value="{{$normal->class_id}}" />
<div class="table-responsive m-t-sm ">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="col-md-2">Date Type</th>
            <th class="col-md-2">1日</th>
            <th class="col-md-2">2日</th>
            <th class="col-md-2">3日</th>
            <th class="col-md-2">4日</th>
            <th class="col-md-2">合計</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>日帰り</td>
            <td><input type="text" class="form-control" name="d1_day1"  value="{{$normal->d1_day1}}"></td>
            <td class="empty_td"></td>
            <td class="empty_td"></td>
            <td class="empty_td"></td>
            <td><input type="text" class="form-control" readonly name="d1_total" value="{{$normal->d1_total}}"></td>
        </tr>
        <tr>
            <td>1泊2日</td>
            <td><input type="text" class="form-control" name="n1d2_day1" value="{{$normal->n1d2_day1}}"></td>
            <td><input type="text" class="form-control" name="n1d2_day2" value="{{$normal->n1d2_day2}}"   ></td>
            <td class="empty_td"></td>
            <td class="empty_td"></td>
            <td><input type="text" class="form-control" readonly name="n1d2_total" value="{{$normal->n1d2_total}}" ></td>
        </tr>
        <tr>
            <td>2泊3日</td>
            <td><input type="text" class="form-control" name="n2d3_day1" value="{{$normal->n2d3_day1}}"></td>
            <td><input type="text" class="form-control" name="n2d3_day2" value="{{$normal->n2d3_day2}}"></td>
            <td><input type="text" class="form-control" name="n2d3_day3" value="{{$normal->n2d3_day3}}"></td>
            <td class="empty_td"></td>
            <td><input type="text" class="form-control" readonly name="n2d3_total" value="{{$normal->n2d3_total}}" ></td>
        </tr>
        <tr>
            <td>3泊4日</td>
            <td><input type="text" class="form-control" name="n3d4_day1" value="{{$normal->n3d4_day1}}"></td>
            <td><input type="text" class="form-control" name="n3d4_day2" value="{{$normal->n3d4_day2}}"></td>
            <td><input type="text" class="form-control" name="n3d4_day3" value="{{$normal->n3d4_day3}}"></td>
            <td><input type="text" class="form-control" name="n3d4_day4" value="{{$normal->n3d4_day4}}"></td>
            <td><input type="text" class="form-control" name="n3d4_total" value="{{$normal->n3d4_total}}" readonly ></td>
        </tr>
        <tr>
            <td>1日追加</td>
            <td colspan="4"><input type="text" class="form-control" name="additional_day" value="{{$normal->additional_day}}"></td>
            <td><input type="text" class="form-control" readonly name="additional_total" value="{{$normal->additional_total}}" ></td>
        </tr>
        </tbody>
    </table>
</div>
<div class="form-group">
    <div class="pull-right m-r-md">
        <label>
            {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/updatenormal/'.$normal->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
            {!! Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> '通常料金の保存',
                    'data-message' 		=> 'この通常料金の変更を保存しますか？'
            )) !!}
            {!! Form::close() !!}
        </label>
        <label>
            {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/updatenormal', 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
            {!! Form::hidden('method', 'DELETE') !!}
            <input type="hidden" name="class_id" value="{{$normal->class_id}}" >
            {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                <span class="hidden-xs hidden-sm">削除</span>',
                array('class' => 'btn btn-danger',
                    'type' => 'button' ,
                    'data-toggle' => 'modal',
                    'data-target' => '#confirmDelete',
                    'data-title' => '通常料金の削除',
                    'data-message' => 'この通常料金を本当に削除しますか？この操作を取り消すことはできません。')) !!}
            {!! Form::close() !!}
        </label>
    </div>
</div>

{!! Form::close() !!}

<script>
  $('input').keyup(function(event) {
      var name = $(event.target).attr('name');
      var val = $(this).val();
      var total = 0 ;
      switch(name) {
          case 'd1_day1':
              $('#normalform input[name="d1_total"]').val(val);
              break;
          case 'n1d2_day1':
              total = parseInt($('#normalform input[name="n1d2_day1"]').val())+parseInt($('#normalform input[name="n1d2_day2"]').val());
              $('#normalform input[name="n1d2_total"]').val(total);
              break;
          case 'n1d2_day2':
              total = parseInt($('#normalform input[name="n1d2_day1"]').val())+ parseInt($('#normalform input[name="n1d2_day2"]').val());
              $('#normalform input[name="n1d2_total"]').val(total);
              break;
          case 'n2d3_day1':
              total = parseInt($('#normalform input[name="n2d3_day1"]').val())+parseInt($('#normalform input[name="n2d3_day2"]').val())+parseInt($('#normalform input[name="n2d3_day3"]').val());
              $('#normalform input[name="n2d3_total"]').val(total);
              break;
          case 'n2d3_day2':
              total = parseInt($('#normalform input[name="n2d3_day1"]').val())+parseInt($('#normalform input[name="n2d3_day2"]').val())+parseInt($('#normalform input[name="n2d3_day3"]').val());
              $('#normalform input[name="n2d3_total"]').val(total);
              break;
          case 'n2d3_day3':
              total = parseInt($('#normalform input[name="n2d3_day1"]').val())+parseInt($('#normalform input[name="n2d3_day2"]').val())+parseInt($('#normalform input[name="n2d3_day3"]').val());
              $('#normalform input[name="n2d3_total"]').val(total);
              break;
          case 'n3d4_day1':
              total = parseInt($('#normalform input[name="n3d4_day1"]').val())+parseInt($('#normalform input[name="n3d4_day2"]').val())+parseInt($('#normalform input[name="n3d4_day3"]').val())+parseInt($('#normalform input[name="n3d4_day4"]').val());
              $('#normalform input[name="n3d4_total"]').val(total);
              break;
          case 'n3d4_day2':
              total = parseInt($('#normalform input[name="n3d4_day1"]').val())+parseInt($('#normalform input[name="n3d4_day2"]').val())+parseInt($('#normalform input[name="n3d4_day3"]').val())+parseInt($('#normalform input[name="n3d4_day4"]').val());
              $('#normalform input[name="n3d4_total"]').val(total);
              break;
          case 'n3d4_day3':
              total = parseInt($('#normalform input[name="n3d4_day1"]').val())+parseInt($('#normalform input[name="n3d4_day2"]').val())+parseInt($('#normalform input[name="n3d4_day3"]').val())+parseInt($('#normalform input[name="n3d4_day4"]').val());
              $('#normalform input[name="n3d4_total"]').val(total);
              break;
          case 'n3d4_day4':
              total = parseInt($('#normalform input[name="n3d4_day1"]').val())+parseInt($('#normalform input[name="n3d4_day2"]').val())+parseInt($('#normalform input[name="n3d4_day3"]').val())+parseInt($('#normalform input[name="n3d4_day4"]').val());
              $('#normalform input[name="n3d4_total"]').val(total);
              break;
          case 'additional_day':
              total = parseInt($('#normalform input[name="additional_day"]').val());
              $('#normalform input[name="additional_total"]').val(total);
              break;
      }

  })
</script>