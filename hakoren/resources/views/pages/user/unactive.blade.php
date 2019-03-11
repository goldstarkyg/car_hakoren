@extends('layouts.unactiveapp')

@section('template_title')
    登録完了
@endsection

@section('template_fastload_css')
@endsection
@section('content')

    <div class="container" style="margin-top:20px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                @include('panels.unactive-panel')

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
@endsection