@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_transactions.css" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div class="page-container">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head hidden-xs">
            <div class="container clearfix">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="{{URL::to('/')}}"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li class="hidden">
                            <a href="#">{{trans('fs.parent')}}</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span>特定商取引法に基づく表記</span>
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
								<h1>特定商取引法に基づく表記</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
					<div class="box-shadow" style="padding-top:30px;padding-bottom:30px;">
						<div class="row">
							<div class="col-md-10 col-md-offset-1 transaction">
								<table class="center table table-bordered c-table" >
									<tbody>
										<tr>
											<th>販売業者</th>
											<td>ハコレンタカー</td>
										</tr>
										<tr>
											<th>代表責任者</th>
											<td>主計 勝</td>
										</tr>
										<tr>
											<th>所在地</th>
											<td>福岡県福岡市博多区西月隈2丁目10番</td>
										</tr>

										<tr>
											<th>電話番号</th>
											<td>TEL: 092-260-9506</td>
										</tr>
										<tr>
											<th>受付時間</th>
											<td>9：00～19：30</td>
										</tr>
										<tr>
											<th>メール</th>
											<td>info@hakoren.com</td>
										</tr>
										<tr>
											<th>ホームページ</th>
											<td><a href="https://www.hakoren.com">https://www.hakoren.com</a></td>
										</tr>
										<tr>
											<th>お支払方法</th>
											<td>現金またはクレジットカード</td>
										</tr>
									</tbody>
								</table>
								<h3>■ ご予約時の注意事項に関して</h3>
								<p>免許取得一年未満のお客様、21歳未満のお客様は免責補償制度にご加入いただけません。ご出発時にお断りさせていただく場合がございます。ご了承くださいませ。</p>
								<ul>
									<li>6歳未満の幼児のお客様がご同乗される際、チャイルドシートが無い場合には、レンタカーの貸渡しをお断りさせていただきます。当社のチャイルドシートをご利用いただくか、ご持参ください。</li>
									<li>ペットのご乗車は禁止させていただいております。乗車を確認した場合、車内清掃料、およびNOC（休業損害）を申し受ける場合がございます。</li>
									<li>ご予約時に指定された運転者以外の方がご来店された場合は、貸渡しをお断りする場合がございます。</li>
									<li>キャンセルの場合、ご出発日より15日前より所定のキャンセル料を申し受けます。<br>
									当日 : 基本料金の100%<br>
									1日前まで : 基本料金の80%<br>
									4日前まで : 基本料金の50%<br>
									9日前まで : 基本料金の30%<br>
									15日前まで : 基本料金の20%<br>
									上記指定日以前 : なし
									</li>
									<li>料金のお支払い方法は、・Web予約時にクレジット支払い。 ・当日、店頭でクレジット支払い又は現金支払い。をご利用いただけます。</li>
									<li>法令により、運転手付きの貸渡しや、紹介及び斡旋はいたしかねます。</li>
									<li>当社は中古車を利用しております。整備・点検等は行っておりますが、新車ではございませんのでご了承ください。</li>
									<li>ご出発手続き後、早期ご返却時のご返金は承りかねますのでご了承下さい。</li>
									<li>ご予約を頂戴した時点で、弊社貸渡約款、および貸渡規約にご同意いただいたものとさせていただきます。</li>
									<li>ご予約の確認メールがお客様のメールアドレスに届いていないもの、またはハコレンのシステムに登録されていないご予約は、有効な予約とはなりませんので、予めご了承ください。<br>予約を取ったのに、確認メールが届いていないお客様はハコレンサポートセンターまでお電話ください。</li>
								</ul>
							</div>
						</div>
					</div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link">ページトップへ</a>
                        </div>
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
@endsection
