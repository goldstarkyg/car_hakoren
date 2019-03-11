@extends('layouts.adminapp')

@section('template_title')
    店舗管理
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>店舗一覧</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/shopbasic/shop/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-car" aria-hidden="true"></i>
                        新規登録
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
                                    <th>ID</th>
                                    <th>画像</th>
                                    <th>店舗名</th>
                                    <th>略称</th>
                                    {{--<th>Business Hour</th>--}}
                                    <th>都道府県</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shops as $shop)
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle;">{{str_pad($shop->id, 6, '0', STR_PAD_LEFT)}}</td>
                                        <td class="hidden-xs" style="vertical-align: middle;width:50px;height: auto">
                                            @if($shop->thumb_path)
                                                <img src="{{URL::to('/').$shop->thumb_path}}" class="img-thumbnail" style="width:100px; height: auto" >
                                            @else
                                                <img src="{{URL::to('/')}}/images/car_default.png" class="img-thumbnail" style="width:100px; height: auto" >
                                            @endif
                                        </td>
                                        <td style="vertical-align:middle;">{{$shop->name}}</td>
                                        <td style="vertical-align: middle;">{{$shop->abbriviation}}</td>
                                        {{--<td style="vertical-align: middle;">2 hoours</td>--}}
                                        <td style="vertical-align: middle;">{{$shop->prefecture}}</td>
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="{{ URL::to('shopbasic/shop/' . $shop->id) }}" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="{{ URL::to('/shopbasic/shop/' . $shop->id . '/edit') }}" title="編集">
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
    @include('scripts.admin.shopbasic.shop-index')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
@endsection