<link href="{{URL::to('/')}}/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<form action="{{URL::to('/').'/carbasic/carclass/update_model_order/'.$carclass->id}}" class="form-horizontal" id="priority_form" role="form" method="POST" enctype="multipart/form-data">
{{ csrf_field() }}
{{--<input type="hidden" name="class_id" value="{{$carclass->id}}" />--}}
<div class="form-group has-feedback row {{ $errors->has('model_priority') ? ' has-error ' : '' }}">
    {!! Form::label('model_priorities',
                'モデル優先度',
                array('class' => 'col-md-3 control-label')); !!}
    <div class="col-md-9">
        <div>
            <input type="hidden" id="model_orders" name="model_orders"  />

            <ul id="model_sort" name="model_sort">
                @foreach($priorities as $p)
                    <li class="ui-state-default" order="{{ $p->model_id }}">
                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{ $p->name }}
                    </li>
                @endforeach
            </ul>
        </div>
        @if ($errors->has('model_priority'))
            <span class="help-block">
                <strong>{{ $errors->first('model_priority') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-sm-9 col-sm-offset-3">
        <label>
{{--            {!! Form::open(array('url' => URL::to('/').'/carbasic/carclass/update_model_order/' . $carclass->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'update')) !!}--}}
            {!! Form::button(
                '<i class="fa fa-fw fa-save" aria-hidden="true"></i> ' . trans('profile.submitButton'),
                 array(
                    'class' 		 	=> 'btn btn-success',
                    'type' 			 	=> 'button',
                    'data-target' 		=> '#confirmForm',
                    'data-modalClass' 	=> 'modal-success',
                    'data-toggle' 		=> 'modal',
                    'data-title' 		=> 'モデル優先度を保存',
                    'data-message' 		=> 'このモデル優先度の変更を保存しますか？'
            )) !!}
{{--            {!! Form::close() !!}--}}
        </label>
    </div>
</div>
</form>
<style>
    .ui-state-default {

    }
</style>





