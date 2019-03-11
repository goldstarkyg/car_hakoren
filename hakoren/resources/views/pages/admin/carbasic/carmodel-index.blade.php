@extends('layouts.adminapp')

@section('template_title')
    車両モデル一覧
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
         .attribute {
             background: url({{ URL::to('/images/attributes.png')}}) no-repeat;
             color: #303740;
             display: block;
             font-size: 0.9em;
             height: 23px;
             line-height: 20px;
             padding: 0 5px 0 0;
             text-align: right;
             width: 38px;
         }
        .attribute-passengers {
            background-position: 0 0;
        }
        .attribute-luggages {
            background-position: 0 -23px;
        }
        .attribute-doors {
            background-position: 0 -46px;
        }
         .attribute_smoke {
             background: url({{ URL::to('/images/smoke.png')}}) no-repeat;
             color: #303740;
             display: block;
             font-size: 0.9em;
             height: 23px;
             line-height: 20px;
             padding: 0 5px 0 0;
             text-align: right;
             width: 38px;
         }
         .attribute-smoke {
             background-position: 0 0;
         }
         .attribute-nonsmoke {
             background-position: 0 -23px;
         }
    </style>
@endsection
@inject('service', 'App\Http\Controllers\CarModelController')
@section('content')
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>車両モデル一覧</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/carbasic/carmodel/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-car" aria-hidden="true"></i>
                        作成する
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table" width="100%">
                                <thead>
                                <tr>
                                    <th>モデルID</th>
                                    <th>外観</th>
                                    <th>モデル名</th>
                                    <th>車両<br/>タイプ</th>
                                    <th>メーカー</th>
                                    {{--<th>優先順位</th>--}}
                                    <th>エンジン</th>
                                    <th>喫煙/禁煙</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($models as $model)
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle;">{{str_pad($model->id, 6, '0', STR_PAD_LEFT)}}</td>
                                        <td class="hidden-xs" style="vertical-align: middle;width:100px; height: auto">
                                            @if(!$model->thumb_path)
                                                <img src="{{URL::to('/')}}/images/car_default.png" width="80px" class="img-thumbnail">
                                            @else
                                                <img src="{{URL::to('/').$model->thumb_path}}" width="80px" class="img-thumbnail">
                                            @endif
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <span class="pj-table-cell-label">
                                                {{$model->name}}
                                                {{--<span class="attribute attribute-passengers float_left">--}}
                                                    {{--{{$model->passengers}}--}}
                                                {{--</span>--}}
                                                <span class="attribute attribute-luggages float_left">
                                                    {{$model->luggages}}
                                                </span>
                                                <span class="attribute attribute-doors float_left">
                                                    {{$model->doors}}
                                                </span>
                                            </span>
                                        </td>
                                        <td style="vertical-align: middle;">@if(!empty($model->type_id)){{empty($model->type)? '':$model->type->name}} @endif</td>
                                        <td style="vertical-align: middle;">@if($model->vendor_id != 0){{$model->vendor->name}} @endif</td>
{{--                                        <td style="vertical-align: middle;">{{$model->priority}} </td>--}}
                                        <td style="vertical-align: middle;">{{ucfirst($model->transmission)}}</td>
                                        <td style="vertical-align: middle;">
                                            <span class="attribute_smoke attribute-smoke float_left">
                                                    {{$service->getnumberSmoking($model->id,1)}}
                                            </span>
                                            <span class="attribute_smoke attribute-nonsmoke float_left">
                                                    {{$service->getnumberSmoking($model->id,0)}}
                                            </span>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="{{ URL::to('carbasic/carmodel/' . $model->id) }}" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="{{ URL::to('/carbasic/carmodel/' . $model->id . '/edit') }}" title="編集">
                                                    <span class="hidden-xs hidden-sm">編集</span>
                                                </a>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    @include('scripts.admin.carbasic.carmodel-index')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
@endsection