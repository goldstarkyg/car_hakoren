@extends('layouts.adminapp')

@section('template_title')
    固定ページ一覧
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>固定ページ一覧</h2>
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
                                    <th>画像</th>
                                    <th>タイトル</th>
                                    <th>スラグ</th>
                                    <th>アクション</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pages as $page)
                                    <tr  valign="middle">
                                         
                                        <td style="vertical-align:middle;">
                                        
                                            @if($page->featured_image)
                                                <img src="{{URL::to('/').$page->featured_image}}" class="img-thumbnail" style="width:100px; height: auto" >
                                            @endif                                        
                                        
                                        </td>
                                        <td style="vertical-align:middle;">{{$page->title}}</td>
                                        <td style="vertical-align: middle;">{{$page->slug}}</td>
                                        
                                        <td style="vertical-align: middle;">
                                            <label>
												
                                                <a class="btn btn-sm btn-success" @if($page->meta_only == 0) href="{{ URL::to('/page/' . $page->slug) }}" @else  href="{{ URL::to('' . $page->slug) }}" @endif title="詳細">
                                                    <span class="hidden-xs hidden-sm">公開ページを見る</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="{{ URL::to('/adminpage/webpages/' . $page->id . '/edit') }}" title="編集">
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