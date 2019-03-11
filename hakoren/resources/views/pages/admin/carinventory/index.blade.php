@extends('layouts.adminapp')

@section('template_title')
    個別車両一覧
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@endsection
@inject('service', 'App\Http\Controllers\CarInventoryController')
@section('content')
    <style>
        .btn-circle_active {
            width: 10px;
            height: 10px;
            padding: 3px 0;
            border-radius: 5px;
            margin-left: 5px;
            background-color: #13e313;
            position: absolute;
            margin-top: 5px;;
        }
        .btn-circle_inactive {
            width: 10px;
            height: 10px;
            padding: 3px 0;
            border-radius: 15px;
            margin-left: 5px;
            background-color:#f44336;
            position: absolute;
            margin-top: 5px;;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2 style="display: inline-block;margin-right:30px;">個別車両一覧</h2>
                <form class="form-inline" method="post" name="search" id="search" action="{{URL::to('/')}}/carinventory/inventory">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="email">店舗&emsp;</label>
                        <select name="search_shop" id="search_shop" class="form-control" onchange="changeShop()">
                            {{--<option value="all">すべて</option>--}}
                            @foreach($shops as $shop)
                                <option @if($shop_id == $shop->id) {{ "selected" }} @endif value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endforeach
                        </select>
                        &emsp;<label for="email">車両クラス&emsp;</label>
                        <select name="search_class" id="search_class" class="form-control" onchange="searchBook()">
                            <option value="all">すべて</option>
                            @foreach($classes as $cls)
                                <option @if($class_id == $cls->id) {{ "selected" }} @endif  class="car_class" shop="{{$cls->car_shop_name}}" value="{{ $cls->id }}">{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/carinventory/inventory/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
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
                        <div class="clearfix"></div>
                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table" width="100%">
                                <thead>
                                <tr>
                                    <th>車両番号</th>
                                    {{--<th>クラス</th>--}}
                                    <th>モデル</th>
                                    <th>略称</th>
                                    {{--<th>優先度</th>--}}
                                    <th>所属店舗</th>
                                    <th>禁煙?</th>
                                    <th>最大乗車人数</th>
                                    <th> </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invens as $inven)
                                    <tr align="middle" >
                                        <td style="vertical-align:middle;">
                                            {{$inven->numberplate1}} {{$inven->numberplate2}} {{$inven->numberplate3}}<br/>{{$inven->numberplate4}}
                                            @if($inven->status == '1')
                                                <label class="btn-circle_active"></label>
                                            @else
                                                <label class="btn-circle_inactive"></label>
                                            @endif
                                        </td>
                                        {{--<td style="vertical-align: middle;">--}}
                                            {{--{{$service->getClassNameFromModelId($inven->model_id)}}--}}
                                        {{--</td>--}}
                                        <td style="vertical-align: middle;">{{$inven->model_name}}</td>
                                        <td style="vertical-align: middle;">{{$inven->shortname}}</td>
                                        {{--<td style="vertical-align: middle;">{{$inven->priority}}</td>--}}
                                        <td style="vertical-align: middle;">{{$inven->shop_name}}</td>
                                        <td style="vertical-align: middle;">@if($inven->smoke ==1) 喫煙 @else 禁煙 @endif</td>
                                        <td style="vertical-align: middle;">{{$inven->max_passenger}}</td>
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="{{ URL::to('/carinventory/inventory/' . $inven->id) }}" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="{{ URL::to('/carinventory/inventory/' . $inven->id . '/edit') }}" title="編集">
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


<script type="text/javascript">
    function changeShop() {
        $('#search_class').val('all');
        searchBook();
    }
    function searchBook() {
        $('#search').submit();
    }

    $('#search_shop').change( function () {
        var shop = $(this).val();
        $('.car_class').hide();
        $('.car_class[shop="'+ shop +'"]').show();
    });
</script>


@endsection

@section('footer_scripts')
    @include('scripts.admin.carinventory.index')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
@endsection