@extends('layouts.adminapp')

@section('template_title')
    PDFダウンロード
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>PDFダウンロード</h2>
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
                                    <th>ファイル名</th>
                                    <th>アクション</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr  valign="middle">
                                         
                                        <td style="vertical-align:middle;">                          
                                        チェックシート（福岡空港店）
                                        </td>
                                        
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success"  title="ダウンロード" target="_blank" href="{{URL::to('/')}}/img/check_sheet_fukuoka.pdf">
													<span>ダウンロード</span>
                                                </a>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr  valign="middle">
                                         
                                        <td style="vertical-align:middle;">                          
                                        チェックシート（那覇空港店）
                                        </td>
                                        
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success"  title="ダウンロード" target="_blank" href="{{URL::to('/')}}/img/check_sheet_naha.pdf">
													<span>ダウンロード</span>
                                                </a>
                                            </label>
                                        </td>
                                    </tr>
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