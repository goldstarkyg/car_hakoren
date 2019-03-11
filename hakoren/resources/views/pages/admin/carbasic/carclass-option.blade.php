{!! Form::model($carclass, array('method' => 'PATCH', 'action' => array('CarClassController@updateoption', $carclass->id),  'class' => 'form-horizontal', 'id'=>'optionform', 'role' => 'form', 'method' => 'POST', 'enctype' => 'multipart/form-data')) !!}

{{ csrf_field() }}
<input type="hidden" name="class_id" value="{{$carclass->id}}" />
<div class="form-group has-feedback row {{ $errors->has('car_options') ? ' has-error ' : '' }}">
    {!! Form::label('car_option',
                'オプション',
                array('class' => 'col-md-3 control-label')); !!}
    <div class="col-md-9">
        <div>
            <input type="hidden" id="car_options" name="car_options"  />
            <select class="chosen-select form-control" name="car_option" id="car_option" data-placeholder="Choose a Car Option" multiple tabindex="2">
                @foreach($options as $option)
                    <?php $select = ''; ?>
                    @foreach($carclass->carClassOption as $cl)
                        <?php
                        if($cl->option_id == $option->id)
                        {
                            $select = 'selected' ;
                            break;
                        }
                        ?>
                    @endforeach
                    <option value="{{ $option->id }}" <?php echo $select ?> >{{ $option->name }}</option>
                @endforeach
            </select>
        </div>
        @if ($errors->has('car_classes'))
            <span class="help-block">
                    <strong>{{ $errors->first('car_options') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <label>
            {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/updateoption/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}
            {!! Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success disableddd',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> 'オプションの保存',
                    'data-message' 		=> 'このオプションの変更を保存しますか？'
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
                    {{--'data-title' => 'オプションを削除',--}}
                    {{--'data-message' => 'このオプションを本当に削除しますか？この操作を取り消すことはできません。')) !!}--}}
            {{--{!! Form::close() !!}--}}
        {{--</label>--}}
    </div>
</div>

{!! Form::close() !!}





