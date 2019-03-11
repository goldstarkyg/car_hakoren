@extends('layouts.adminapp')

@section('template_title')
    ブログ投稿一覧
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>ブログ投稿一覧</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px;" >
                    <a href="{{URL::to('/')}}/adminblog/blogposts/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
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
                                    <th>ID</th>                                
                                    <th>画像</th>
                                    <th>タイトル</th>
                                    <th>公開日</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tagdata as $tag)
                                    <tr  valign="middle">
                                        <td style="vertical-align:middle;">{{str_pad($tag->id, 6, '0', STR_PAD_LEFT)}}</td>
                                         
                                        <td style="vertical-align:middle;">
                                        
                                            @if($tag->featured_image)
                                                <img src="{{URL::to('/').$tag->featured_image}}" class="img-thumbnail" style="width:100px; height: auto" >
                                            @endif                                        
                                        
                                        </td>
                                        <td style="vertical-align:middle;">{{$tag->title}}</td>
                                        <td style="vertical-align: middle;">{{$tag->publish_date}}</td>
                                        
                                        <td style="vertical-align: middle;">
                                            <label>
                                                <a class="btn btn-sm btn-success" href="{{ URL::to('adminblog/blogposts/' . $tag->id) }}" title="詳細">
                                                    <span class="hidden-xs hidden-sm">詳細</span>
                                                </a>
                                            </label>
                                            <label>
                                                <a class="btn btn-sm btn-info " href="{{ URL::to('/adminblog/blogposts/' . $tag->id . '/edit') }}" title="編集">
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