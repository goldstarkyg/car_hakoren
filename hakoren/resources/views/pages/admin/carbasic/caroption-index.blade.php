@extends('layouts.adminapp')

@section('template_title')
    オプション一覧
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }
        .data-table thead tr th {
            white-space: nowrap;
        }
        .data-table tbody tr td {
            white-space: nowrap;
        }
    </style>
@endsection
@inject('service_caroption', 'App\Http\Controllers\CarOptionController')

@section('content')
    <style>
        .class_model {
            display: block;
            background-color: #eee;
            text-align: center;
            font-weight:300;
            padding-bottom: 2px;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>オプション一覧</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/carbasic/caroption/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
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
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>略名</th>
                                    <th>名前</th>
                                    <th>価格</th>
                                    <th>店舗</th>
                                    <th>料金制度</th>
                                    <th>クラス</th>
                                    <th>Google 番号</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($options as $option)
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle;">{{str_pad($option->id, 6, '0', STR_PAD_LEFT)}}</td>
                                        <td class="hidden-xs" style="vertical-align:middle;">{{$option->abbriviation}}</td>
                                        <td style="vertical-align:middle;">{{$option->name}}</td>
                                        <td style="vertical-align:middle;">{{$option->price}}</td>
                                        <td style="vertical-align:middle;">
                                            @foreach($option->carOptionShop as $shop)
                                                <label class="class_model">{{$service_caroption->getCarShopName($shop->shop_id)}}*{{$shop->option_count}} = {{$shop->price}}</label>
                                            @endforeach
                                        </td>
                                        <td style="vertical-align:middle;">{{$option->charge_system}}</td>
                                        <td style="vertical-align:middle;">
                                            @foreach($option->carOptionClass as $class)
                                                <label class="class_model">{{$service_caroption->getCarClassName($class->class_id)}} </label>
                                            @endforeach
                                        </td>
                                        <td style="vertical-align:middle;">{{$option->google_column_number}}</td>
                                        <td style="vertical-align:middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="{{ URL::to('carbasic/caroption/' . $option->id) }}" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="{{ URL::to('/carbasic/caroption/' . $option->id . '/edit') }}" title="編集">
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
    @include('scripts.admin.carbasic.caroption-index')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
@endsection