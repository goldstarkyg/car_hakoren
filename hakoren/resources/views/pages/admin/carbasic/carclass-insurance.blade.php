{!! Form::model($carclass, array('method' => 'PATCH', 'action' => array('CarClassController@updateinsurance', $carclass->id),  'class' => 'form-horizontal', 'id'=>'insuranceform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}

{{ csrf_field() }}
    <input type="hidden" name="class_id" value="{{$carclass->id}}" />
    <div class="form-group m-t-sm">
        <label for="car_class_priority" class="col-sm-3 control-label">{{$insurances[0]->name}}</label>
        <input type="hidden" name="first_val" value="{{$insurances[0]->price}}_{{$insurances[0]->id}}" >
        <input type="hidden" name="first_ins_id" value="{{$insurances[0]->id}}" >
        <div class="col-sm-9">
            {!! Form::text('ins_first', $insurances[0]->price, ['class' => 'insurance_price form-control required', 'placeholder' => '', 'id' =>'ins_first']) !!}
        </div>
    </div>
    <div class="form-group m-t-sm">
        <label for="car_class_priority" class="col-sm-3 control-label">{{$insurances[1]->name}}</label>
        <input type="hidden" name="second_val" value="{{$insurances[1]->price}}_{{$insurances[1]->id}}" >
        <input type="hidden" name="second_ins_id" value="{{$insurances[1]->id}}" >
        <div class="col-sm-9">
            {!! Form::text('ins_second', $insurances[1]->price, ['class' => 'insurance_price form-control required', 'placeholder' => '', 'id' =>'ins_second']) !!}
        </div>
    </div>

<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <label>
            {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/updateinsurance/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
            {!! Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> '補償を保存',
                    'data-message' 		=> 'この補償の変更を保存しますか？'
            )) !!}
            {!! Form::close() !!}
        </label>
        {{--<label>--}}
        {{--{!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/updateoption/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}--}}
        {{--{!! Form::hidden('_method', 'DELETE') !!}--}}
        {{--{!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>--}}
        {{--<span class="hidden-xs hidden-sm">削除</span>',--}}
        {{--array('class' => 'btn btn-danger',--}}
        {{--'type' => 'button' ,--}}
        {{--'data-toggle' => 'modal',--}}
        {{--'data-target' => '#confirmDelete',--}}
        {{--'data-title' => 'Delete Option',--}}
        {{--'data-message' => 'Do you want to permanently delete this car class option?')) !!}--}}
        {{--{!! Form::close() !!}--}}
        {{--</label>--}}
    </div>
</div>

{!! Form::close() !!}





