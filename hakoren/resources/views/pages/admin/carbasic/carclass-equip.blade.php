{!! Form::model($carclass, array('method' => 'PATCH', 'action' => array('CarClassController@updateequipment', $carclass->id),  'class' => 'form-horizontal', 'id'=>'insuranceform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}

{{ csrf_field() }}
<input type="hidden" name="class_id" value="{{$carclass->id}}" />
<div class="form-group has-feedback row {{ $errors->has('car_options') ? ' has-error ' : '' }}">
    {!! Form::label('car_equipment',
                '装備',
                array('class' => 'col-md-3 control-label')); !!}
    <div class="col-md-9">
        <div>
            <input type="hidden" id="car_equipments" name="car_equipments"  />
            <select class="chosen-select form-control" name="car_equipment" id="car_equipment" data-placeholder="Choose a Car Equipment" multiple tabindex="2">
                @foreach($equipments as $equip)
                    <?php $select = ''; ?>
                    @foreach($carclass->carClassEquipment as $ce)
                        <?php
                        if($ce->equipment_id == $equip->id)
                        {
                            $select = 'selected' ;
                            break;
                        }
                        ?>
                    @endforeach
                    <option value="{{ $equip->id }}" <?php echo $select ?> >{{ $equip->name }}</option>
                @endforeach
            </select>
        </div>
        @if ($errors->has('car_equipment'))
            <span class="help-block">
                    <strong>{{ $errors->first('car_eqipment') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <label>
            {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/updateequipment/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
            {!! Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> '装備を保存',
                    'data-message' 		=> 'この装備の変更を保存しますか？'
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





