@extends('layouts.adminapp')

@section('template_title')
    モデル内優先度
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@endsection
@inject('service', 'App\Http\Controllers\CarInventoryController')
@section('content')
    <style>
        .info_block { display: inline-block; padding: 10px 15px 0 15px; font-size: 14px; text-align: center}
        .info_label { font-weight: 700;}
        .info_value { font-size: 16px; font-weight: 700;}
        #smoke_sort, #nonsmoke_sort {
            display: inline-block;
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 60%;
            text-align: left;
        }
        #smoke_sort li, #nonsmoke_sort li {
            margin: 0 3px 3px 3px;
            padding: 0.4em;
            cursor: all-scroll;
        }
    </style>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>モデル内優先度</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;display: none" >
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
                        <div class="col-xs-12" style="padding:10px; border-bottom: 2px solid #ccc">
                            <select class="selectpicker show-tick" id="shop_list">
                            </select>

                            <select class="selectpicker show-tick" id="model_list">
                            </select>

                            <div class="info_block">
                                <span class="info_label" style="">全在庫数</span><br>
                                <span class="info_value" id="count_all">10</span>台
                            </div>
                            <div class="info_block">
                                <span class="info_label" style="">禁煙</span><br>
                                <span class="info_value" id="count_nonsmoke">10</span>台
                            </div>
                            <div class="info_block">
                                <span class="info_label" style="">喫煙</span><br>
                                <span class="info_value" id="count_smoke">10</span>台
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <h3 style="margin: 15px 0 10px 0"> 禁煙車 </h3>
                                <ul id="nonsmoke_sort" name="nonsmoke_sort">
                                </ul>
                            </div>
                            <div class="col-xs-6 text-center">
                                <h3 style="margin: 15px 0 10px 0"> 喫煙車 </h3>
                                <ul id="smoke_sort" name="smoke_sort">
                                </ul>
                            </div>
                        </div>

                        <div class="row text-center" style="margin: 15px 0; ">
                            <form action="{{URL::to('/')}}/carinventory/storepriority" method="post" id="saveform">
                                {{ csrf_field() }}
                                <input type="hidden" id="nonsmoke_orders" name="nonsmoke_orders"  />
                                <input type="hidden" id="smoke_orders" name="smoke_orders"  />
                                <input type="hidden" id="shop" name="shop_id"  />
                                <input type="hidden" id="model" name="model_id"  />

                                <button class="btn btn-primary" data-toggle="confirmation" id="btn-save">保存</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.modal-delete')

@endsection

@section('footer_scripts')
    @include('scripts.admin.carinventory.index')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    @include('scripts.admin.carinventory.priority')
@endsection