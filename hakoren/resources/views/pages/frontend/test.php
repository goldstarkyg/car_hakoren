@extends('layouts.frontend')
@section('template_linked_css')
<link href="{{URL::to('/')}}/css/page_quickstart_form.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
@endsection
@section('content')
<div class="page-container">
    <!-- BEGIN PAGE HEAD-->
    <div class="page-head hidden-xs">
        <div class="container clearfix">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <ul class="page-breadcrumb breadcrumb">
                    <li> <a href="{{URL::to('/')}}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> </li>
                    <li>
                        <span>レンタカークイック乗り出し</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE TITLE -->
        </div>
    </div>
    <!-- END PAGE HEAD-->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->

        <!-- BEGIN CONTENT HEADER -->
        <div class="dynamic-page-header dynamic-page-header-default">
            <div class="container clearfix">
                <div class="col-md-12 bottom-border ">
                    <div class="page-header-title">
                        <h1>クイックスタート登録フォーム</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- begin search -->
        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
                        <h3>ご予約 {{ $userInfo->last_name }} {{ $userInfo->first_name }} 様</h3>

                        @if(isset($transaction_id))
                        <p>クイックスタートサービスをご利用いただきありがとうございました。</p>
                        @endif

                        <!-- quick start 3 -->
                        <div class="box-shadow relative red-border-top">
                            <div class="formcard-block-cont">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formcard-right">
                                        <h3 class="text-center relative">お手続きが完了しました</h3>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formcard-right">
                                        <div class="panel panel-default">
                                            <div class="bg-grad-gray panel-heading"> <a id="credit-sec"></a>
                                                @if(isset($transaction_id))
                                                <h4>おめでとうございます！お菓子を一つ選択してください！</h4>
                                                @else
                                                <h4>この度、ハコレンタカーをご予約していただき、ありがとうございます。<br />当日はスタッフ一同、楽しみにお待ちしております。
                                                </h4>
                                                @endif
                                            </div>
                                        </div>

                                        {{--
                                        <div class="formcard-wrapper clearfix">
                                            <div class="col-xs-12 only-nonmember　" style="padding: 0; margin-bottom:0px;">
                                                <div><strong>お取引ID</strong><br />
                                                    {!! $transaction_id !!}<br />
                                                    <br />
                                                </div>
                                                <div><strong>お支払額</strong><br />
                                                    {!! $amount_paid !!}<br />
                                                    <br />
                                                </div>
                                                <div><strong>お取引日</strong><br />
                                                    {!! $payment_at !!}</div>
                                            </div>
                                        </div>
                                        --}}

                                        @if(isset($transaction_id))
                                        <form method="post" id="search-thankyou" action="">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="bag_choosed" id="bag_choosed" value="1" />
                                            <input type="hidden" name="booking_id" id="booking_id" value="{!! $booking_id !!}" />
                                            <div class="choose-content">
                                                <div class="choose-common">
                                                    <label >
                                                        <input type="radio" name="bag_choose" value="1" class="bag_choose hidden" bid="1">
                                                        <div class="choose-inner-block active" bid="1">
                                                            <div class="small-block">
                                                                <h2>選択中</h2>
                                                            </div>
                                                            <div class="choose-img-block">
                                                                <img src="img/bag1.jpg" alt="">
                                                            </div>
                                                            <div class="choose-text-block">
                                                                <p>ドライブのリフレッシュに... <br>いつでもスーっとしたい時はこれっ！</p>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="choose-common">
                                                    <label>
                                                        <input type="radio" name="bag_choose" value="2" class="bag_choose hidden" bid="2">
                                                        <div class="choose-inner-block" bid="2">
                                                            <div class="small-block" style="display: none">
                                                                <h2>選択中</h2>
                                                            </div>
                                                            <div class="choose-img-block">
                                                                <img src="img/bag2.jpg" alt="">
                                                            </div>
                                                            <div class="choose-text-block">
                                                                <p>大人も子ども好き...<br>ドライブ中の気分転換に常備しておきたい♪</p>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="choose-common">
                                                    <label>
                                                        <input type="radio" name="bag_choose" value="3" class="bag_choose hidden" bid="3">
                                                        <div class="choose-inner-block" bid="3">
                                                            <div class="small-block" style="display: none">
                                                                <h2>選択中</h2>
                                                            </div>
                                                            <div class="choose-img-block">
                                                                <img src="img/bag3.jpg" alt="">
                                                            </div>
                                                            <div class="choose-text-block">
                                                                <p> せっかくのドライブを楽しめるように ...ご乗車人数分（一回の服用分）プレゼント！</p>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="quick-button" id="show_message">
                                                <input type="button" name="submit" id="bagsubmit" onclick="javascript:choosebag();" class="submitBtn form-control h40" value="送信する">
                                            </div>
                                        </form>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- quick start 3 -->

                    </div>
                    <!-- END PAGE CONTENT INNER -->
                    <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
                        <div class="portlet portlet-fit light cont-box">
                            <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10"> <a href="#"><img class="center-block img-responsive" src="" alt=""></a> </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 clearfix"> <a href="#" class="bg-carico totop-link">ページトップへ</a> </div>
                </div>
            </div>
        </div>
        <!-- end search -->
    </div>
    <!-- END CONTENT -->

</div>
@endsection

@section('style')
<style>

</style>
@endsection

@section('footer_scripts')
<script>
    $small_block = $('.small-block');
    $('.bag_choose').click( function () {
        var bag_id = $(this).attr('bid');
        $('.choose-inner-block').removeClass('active');
        $('.choose-inner-block div.small-block').fadeOut();

        $('.choose-inner-block[bid="' + bag_id + '"]').addClass('active');
        $('.choose-inner-block.active div.small-block').fadeIn();
        $('#bag_choosed').val(bag_id);
        console.log(bag_id);
    });


    function choosebag(){

        var bag_choosed = $('#bag_choosed').val();
        var booking_id  = $('#booking_id').val();
        $.ajax({
            url  : '{!! URL::to("/savebag_choose") !!}',
            type : 'post',
            data : {'booking_id':booking_id, 'bag_choosed':bag_choosed, '_token':$('input[name="_token"]').val()},
            success : function(result,status,xhr) {
                $('#show_message').html('<br>ご利用いただきまして、ありがとうございます。<br><br>当日はスタッフ一同楽しみにおまちしております！<br>');
            },
            error : function(xhr,status,error){
                alert(error);
            }
        });
    }


</script>
@endsection
