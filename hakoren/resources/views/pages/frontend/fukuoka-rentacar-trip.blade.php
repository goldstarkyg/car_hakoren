@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_first.css" rel="stylesheet" type="text/css">
    {{--<link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">--}}
    {{--<link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">--}}
    <style>
    </style>
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
                            <span>{{trans('fs.current')}}</span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE TITLE -->
            </div>
        </div>
        <!-- END PAGE HEAD-->

<style>
<!--
.infocategory {}
.infocategory a {}
.infocategory .infocatebox {border:1px solid #ccc;padding:10px 15px; text-align:center; background:#f1f1f1;}
.infocategory a .info01 {color:#333;}
.infocategory .info02 > a {color:#333;}
.infocategory a .info03 {color:#f00;}
.infocategory a .info04 {color:#333;}
.infocategory .eng {font-size:18px;font-weight:600;color:inherit;}
.infocategory .jpn {font-size:14px;font-weight:400;color:inherit;}
-->
</style>

        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
						<div class="row">
							<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
								<img class="center-block img-responsive" src="img/pages/posts/fukuoka-rentacar-trip-information-top.jpg" alt="">
							</div>
<!--
							<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0">
								<h1>どんな福岡体験を期待していますか？</h1>
							</div>
-->
						</div>
						<!-- カテゴリボックス -->
<!--
						<div class="row infocategory">
							<a href="#info-category1">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pxs0">
									<div class="infocatebox info01">
										<span class="eng">ADVENTURE</span><br/>
										<span class="jpn">ワクワク</span>
									</div>
								</div>
							</a>
							<a href="#info-category2">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pxs0">
									<div class="infocatebox info02">
										<span class="eng">FUN</span><br/>
										<span class="jpn">楽しい</span>
									</div>
								</div>
							</a>
							<a href="#info-category3">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pxs0">
									<div class="infocatebox info03">
										<span class="eng">GOUMET</span><br/>
										<span class="jpn">グルメ</span>
									</div>
								</div>
							</a>
							<a href="#info-category4">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pxs0">
									<div class="infocatebox info04">
										<span class="eng">LUXURY</span><br/>
										<span class="jpn">ビップ体験</span>
									</div>
								</div>
							</a>
						</div>
-->
						<!-- カテゴリボックス終了 -->
<!--						<hr/>-->

						<!-- 情報category1 -->
<!--
						<div id="info-category1">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-5">
											<div style="min-height:150px;background:#f12;">
												abc
											</div>
											<div>
												<h3>abc</h3>
												<div>
													住所がはいります
												</div>
												<div>
													電話番号が入ります
												</div>
											</div>
										</div>
										<div class="col-md-7">
											<div>
												<h3>
													タイトル
												</h3>
												<p>
													内容をここに入れる
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
-->

<!--
						<div id="info-category2">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-5">
											<div style="min-height:150px;background:#f12;">
												abc
											</div>
											<div>
												<h3>abc</h3>
												<div>
													住所がはいります
												</div>
												<div>
													電話番号が入ります
												</div>
											</div>
										</div>
										<div class="col-md-7">
											<div>
												<h3>
													タイトル
												</h3>
												<p>
													内容をここに入れる
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
-->

<!--
						<div id="info-category3">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-5">
											<div style="min-height:150px;background:#f12;">
												abc
											</div>
											<div>
												<h3>abc</h3>
												<div>
													住所がはいります
												</div>
												<div>
													電話番号が入ります
												</div>
											</div>
										</div>
										<div class="col-md-7">
											<div>
												<h3>
													タイトル
												</h3>
												<p>
													内容をここに入れる
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
-->

<!--
						<div id="info-category4">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-5">
											<div style="min-height:150px;background:#f12;">
												abc
											</div>
											<div>
												<h3>abc</h3>
												<div>
													住所がはいります
												</div>
												<div>
													電話番号が入ります
												</div>
											</div>
										</div>
										<div class="col-md-7">
											<div>
												<h3>
													タイトル
												</h3>
												<p>
													内容をここに入れる
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
-->

                        <div class="trip-block">
                            <!-- <div class="info-link">
                                <a href="#">沖縄情報はこちら＞</a>
                            </div>
							-->
                            <div class="exp-block">
                                <h2 class="title">どんな福岡体験がしたいですか？</h2>
                                <ul class="tabs tab-nav">
                                    <li class="adventure current" data-tab="tab-1">
                                        <a href="javascript:void(0)">
                                            <h2>ADVENTURE</h2>
                                            <h3>ワクワク体験</h3>
                                        </a>
                                    </li>
                                    <li class="fun" data-tab="tab-3">
                                        <a href="javascript:void(0)">
                                            <h2>FUN</h2>
                                            <h3>ワイワイ体験</h3>
                                        </a>
                                    </li>
                                    <li class="gourmet" data-tab="tab-4">
                                        <a href="javascript:void(0)">
                                            <h2>GOURMET</h2>
                                            <h3>グルメ</h3>
                                        </a>
                                    </li>
                                    <li class="luxury" data-tab="tab-5">
                                        <a href="javascript:void(0)">
                                            <h2>LUXURY</h2>
                                            <h3>ビップ体験</h3>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content current" id="tab-1">
									<div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/1.jpg" alt="">
														<span>1.</span>
													</div>
													<div class="trip-content">
														<h2>フォレストアドベンチャー・糸島</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒819-1622 福岡県糸島市二丈一貴山３１２−３９０ 樋ノ口ハイランド内</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>080-5548-2070</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>フォレストアドベンチャー・糸島</h2>
													<p>福岡県の中でも海、山、グルメと良い場所の糸島。福岡市内からも約1時間で行ける自然共存型アウトドアパーク。春にはサクラ、秋には紅葉、また展望スポットからは糸島の海が一望できます。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
									<div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/2.jpg" alt="">
														<span>2.</span>
													</div>
													<div class="trip-content">
														<h2>マリンワールド海ノ中道</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒811-0321 福岡市東区大字西戸崎18-28</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-603-0400</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>マリンワールド海ノ中道</h2>
													<p>九州最大級の水族館といえばここ。イルカやアシカなどのパフォーマンスショーはとっても面白くて、動物たちとのふれあいイベントもありますよ。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
									<div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/3.jpg" alt="">
														<span>3.</span>
													</div>
													<div class="trip-content">
														<h2>柳川川下り「松月乗船場」</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒832-0826 福岡県柳川市三橋町高畑３２９</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>0936520334</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>柳川川下り「松月乗船場」</h2>
													<p>旧柳川城の城濠をたどっていく「川下り」は、今では四季を通じて水郷柳川を代表する観光として親しまれています。3月はおひな祭りの「さげもん」、また寒くなると船にはこたつが準備されて、寒い時期の川下りもオススメです。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
									<div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/4.jpg" alt="">
														<span>4.</span>
													</div>
													<div class="trip-content">
														<h2>南蔵院</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span> 〒811-2405 福岡県糟屋郡篠栗町大字篠栗1035 </span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span> 092-947-7195</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>南蔵院</h2>
													<p>青銅製では世界一の大きさを誇る涅槃像！金運上昇の名所としても知られています。間近で見ると、想像以上のド迫力です。一度は、訪れてほしい・・・</p>
													<ul>
														<!--<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
                                    </div>
                                </div>
                                <div class="tab-content" id="tab-3">
                                    <div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/5.jpg" alt="">
														<span>1.</span>
													</div>
													<div class="trip-content">
														<h2>博多の食と文化の博物館</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒812-0068 福岡県福岡市東区社領2-14-28</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-621-8989</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>博多の食と文化の博物館</h2>
													<p>福岡と言えば明太子！日本で初めて明太子を製造・販売した「ふくや」の工場見学や体験ができます。ここの人気といえば、自分の好きな味をつくれる明太子の体験工房！</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
                                    <div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/6.jpg" alt="">
														<span>2.</span>
													</div>
													<div class="trip-content">
														<h2>一蘭の森</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒819-1306 福岡県糸島市志摩松隈２５６−１０</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-332-8902</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>一蘭の森</h2>
													<p>福岡ひいては全国で超人気のとんこつラーメン店「一蘭」。とんこつラーメンの工場と店舗を併設した「一蘭の森」。 無料で施設内を見学することができ、子供だけでなく意外と大人も楽しめるスポット</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
                                    <div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/7.jpg" alt="">
														<span>3</span>
													</div>
													<div class="trip-content">
														<h2>「博多町家」ふるさと館</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒812-0039 福岡市博多区冷泉町6-10</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-281-7761</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>「博多町家」ふるさと館</h2>
													<p>「博多人形」や「博多張子」、「博多独楽」、「博多曲物」の福岡の伝統工芸の絵付け体験ができる！福岡市の指定文化財である「町家棟」、「みやげ処」、「展示棟」の3棟で構成されているので、博多の歴史や伝統を知りたい方はオススメ。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
                                    <div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/8.jpg" alt="">
														<span>4.</span>
													</div>
													<div class="trip-content">
														<h2>SUiTO FUKUOKA</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒810-0041 福岡県福岡市中央区大名１丁目１５−２７ 福岡大名ビル 3F</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-724-1055</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>SUiTO FUKUOKA</h2>
													<p>観光案内所でありながら、訪日外国人が日本の文化を体験したり、地元の人たちが食事を楽しめたり、国際交流を体験できるユニークな施設。美味しい会席料理や握りずし体験、利き酒体験等と言った九州ならではの体験が出来ます。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
                                </div>
                                <div class="tab-content" id="tab-4">
									<div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/9.jpg" alt="">
														<span>1.</span>
													</div>
													<div class="trip-content">
														<h2>旭軒 駅前本店</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒812-0011 福岡県福岡市博多区博多駅前２丁目１５番地２２</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-451-7896</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>旭軒 駅前本店</h2>
													<p>昭和29年に屋台から始まった有名餃子専門店。福岡の一口餃子といえばココ。皮がカリカリで揚げ餃子っぽくも感じる食感と中の具材のジュワっと出てくる素材の味。一人で3～4人前はぺろっと行けちゃいます。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
									<div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/10.jpg" alt="">
														<span>2.</span>
													</div>
													<div class="trip-content">
														<h2>Hostel STAND BY ME</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒810-0074 福岡県福岡市中央区大手門１丁目３−２２</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-791-1974</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>Hostel STAND BY ME</h2>
													<p>福岡に来たらここに行かんといかん！福岡の面白い人たちが集まる、泊まれる・立ち飲みホステル。ここでは、立ち飲みを通じて旅人や現地の人々と交流ができたり、福岡名店の料理を味わうことができます。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
									<div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/11.jpg" alt="">
														<span>3.</span>
													</div>
													<div class="trip-content">
														<h2>やま中本店</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒815-0035 福岡県福岡市南区向野2-2-12</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-553-6915</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>やま中本店</h2>
													<p>福岡といえば、「もつ鍋」しょうゆベースが主流ですが、ここは味噌味がオススメ。もつ鍋の味を決めるのは、やっぱり「もつ」。ぷりぷりで新鮮なもつはくさみが全くなく、噛んだ瞬間に口の中いっぱいに脂と旨みが広がっていきます。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
                                </div>
                                <div class="tab-content" id="tab-5">
                                    <div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/12.jpg" alt="">
														<span>1.</span>
													</div>
													<div class="trip-content">
														<h2>マリエラ</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒812-0021 福岡県福岡市博多区築港本町１３−６</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-751-7171</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>マリエラ</h2>
													<p>クルージングをしながら、船内では本格的な料理が味わえて、自然たっぷりの能古島や福岡の街並みが望めます。ライブやイベントなどエンターテインメントも楽しめます。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
                                    <div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/13.jpg" alt="">
														<span>2.</span>
													</div>
													<div class="trip-content">
														<h2>グランピング福岡 ぶどうの樹～海風と波の音～</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒811-3219 福岡県福津市西福間４丁目１０ 西福間4-10-10</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>0120-4649-56</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>グランピング福岡 ぶどうの樹～海風と波の音～</h2>
													<p>従来のキャンプ場とは違ったワンランク上のラグジュアリーな大人のキャンプ体験げできるグランピング。海が目の前に広がる光景は絶景。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
                                    <div class="exp-content common">
										<div class="row">
											<div class="col-sm-5">
												<div class="trip-lf-block">
													<div class="trip-img-block">
														<img src="img/trip/14.jpg" alt="">
														<span>3.</span>
													</div>
													<div class="trip-content">
														<h2>California B.B.Q Beach</h2>
														<ul>
															<li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span>〒819-0162 福岡市西区今宿青木1119-1</span></a></li>
															<li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span>092-805-4389</span></a></li>
														</ul>
													</div>
												</div>
											</div>
											<div class="col-sm-7">
												<div class="trip-rt-block">
													<h2>California B.B.Q Beach</h2>
													<p>本場アメリカ「WEBER社」のスモークピットのコンロを使用した本格的な「カリフォルニアスタイル」のバーベキューを楽しめるスポット。海の目の前なのでロケーションも最高です。</p>
													<!--<ul>
														<li class="short-left"><img src="img/trip/car-img.png" alt=""></li>
														<li class="short-right"><h3>河内藤園までの近道マップをプレゼント！ ご希望の方には、しっとりBG Mをおつけします！</h3></li>
													</ul>-->
												</div>
											</div>
										</div>
									</div>
								</div>
                            </div>
                            <div class="bottom-link">
                                <a href="{{URL::to('/')}}/view-post/fukuoka-rentacar-onsen-oita">人気の温泉 x レンタカープランはこちら</a>
                            </div>
                        </div>
                    <div class="add-block">
					<!--
                        <div class="row">
                            <div class="col-sm-4 add-block-common">
                                <a href="#">
                                    <img src="img/trip/add-img.jpg" alt="">
                                </a>
                            </div>
                            <div class="col-sm-4 add-block-common">
                                <a href="#">
                                    <img src="img/trip/add-img.jpg" alt="">
                                </a>
                            </div>
                            <div class="col-sm-4 add-block-common">
                                <a href="#">
                                    <img src="img/trip/add-img.jpg" alt="">
                                </a>
                            </div>
                        </div>
						-->
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
        {{--form to go confirm page--}}
        <form action="{{URL::to('/')}}/search-save" method="POST" name="booking-submit" id="booking-submit">
            {!! csrf_field() !!}
            <input type="hidden" name="email" id="data_email" >
            <input type="hidden" name="first_name" id="data_first_name" >
            <input type="hidden" name="last_name" id="data_last_name" >
            <input type="hidden" name="furi_first_name" id="data_furi_first_name" >
            <input type="hidden" name="furi_last_name" id="data_furi_last_name" >
            <input type="hidden" name="phone" id="data_phone" >
        </form>
        {{--end form--}}
    </div>

    <script>
        function showModal(text) {
            $('#modalError p.error-text').html(text);
            $('#modalError').modal();
        }

        function selectModel(model_id) {
            $('input[name="model_id"]').val(model_id);
            // change style of selected tag
        }

        function check() {
            if($('#check-it').prop('checked') === false){
                showModal('会員規約に同意する必要があります。');
                return false;
            }
            var email = $('input[name="email"]').val().trim();
            if(email === '') {
                showModal('Input email');
                return false;
            } else {
                $('#data_email').val(email);
            }
            var fname = $('input[name="first_name"]').val().trim();
            if(fname === '') {
                showModal('Input first name');
                return false;
            } else {
                $('#data_first_name').val(fname);
            }
            var lname = $('input[name="last_name"]').val().trim();
            if(lname === '') {
                showModal('Input last name');
                return false;
            } else {
                $('#data_last_name').val(lname);
            }
            var ffname = $('input[name="furi_first_name"]').val().trim();
            if(ffname === '') {
                showModal('Input furigana first name');
                return false;
            } else {
                $('#data_furi_first_name').val(ffname);
            }
            var flname = $('input[name="furi_last_name"]').val().trim();
            if(flname === '') {
                showModal('Input furigana last name');
                return false;
            } else {
                $('#data_furi_last_name').val(flname);
            }
            var phone = $('input[name="phone"]').val().trim();
            if(phone === '') {
                showModal('Input phone number');
                return false;
            } else {
                $('#data_phone').val(phone);
            }
            // var model = $('#model_id').val();
            // if(model === '') {
            //     showModal('Select car model');
            //     return false;
            // }

            $('#booking-submit').submit();
        }

        $('.model-photo').click(function () {
            // var mid = $(this).attr('model_id');
            // $('.model-photo').removeClass('active');
            // $(this).addClass('active');
            // selectModel(mid);
        });

        $('.breadcrumb-item').click(function () {
            // var mid = $(this).attr('model_id');
            // $('.breadcrumb-item').removeClass('active');
            // $(this).addClass('active');
            // selectModel(mid);
        });
    </script>

    @include('modals.modal-membership')
    @include('modals.modal-error')
@endsection

@section('style')
  <style>

  </style>
@endsection

@section('footer_scripts')
<script type="text/javascript">
$(function(){
    var setFileInput = $('.imgInput'),
    setFileImg = $('.imgView');

    setFileInput.each(function(){
        var selfFile = $(this),
        selfInput = $(this).find('input[type=file]'),
        prevElm = selfFile.find(setFileImg),
        orgPass = prevElm.attr('src');

        selfInput.change(function(){
            var file = $(this).prop('files')[0],
            fileRdr = new FileReader();

            if (!this.files.length){
                prevElm.attr('src', orgPass);
                return;
            } else {
                if (!file.type.match('image.*')){
                    prevElm.attr('src', orgPass);
                    return;
                } else {
                    fileRdr.onload = function() {
                        prevElm.attr('src', fileRdr.result);
                    }
                    fileRdr.readAsDataURL(file);
                }
            }
			alert('sda');
        });
    });
});
</script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/admin_assets/global/plugins/jquery-validation/js/localization/messages_ja.js" type="text/javascript"></script>
<script type="text/javascript">
	var validate_rule = {
            last_name: { required: true },
            first_name: { required: true },
            furi_last_name: { required: true, katakana: true },
            furi_first_name: { required: true, katakana: true },
            email: { required: true, email: true },
            phone: { required: true, number: true, minlength: 9, maxlength: 11 },
            zip11: { required: true, number: true },
            address: { required: true},
            person-number: { required: true, number: true },
            driver-number: { required: true, number: true }
        };
	$('#quickstart-form').validate({
		errorElement : 'span',
		errorPlacement: function(error, element) {
			var eP = $(".error"+element.attr("name"));
			error.appendTo(eP);
		},

		rules: validate_rule,
		messages: {
			last_name: {
				required: jQuery.validator.format("姓を入力してください"),
			},
			first_name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_first_name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_last_name: {
				required: jQuery.validator.format("姓を入力してください"),
			},
			'checkboxes1[]': {
				required: 'Please check some options',
				minlength: jQuery.validator.format("At least {0} items must be selected"),
			},
			'checkboxes2[]': {
				required: 'Please check some options',
				minlength: jQuery.validator.format("At least {0} items must be selected"),
			}
		},
		submitHandler: function(form) {
			check();
		}
	});

	//全角ひらがなのみ
	jQuery.validator.addMethod("katakana", function(value, element) {
	 return this.optional(element) || /^([ァ-ヶー]+)$/.test(value);
	 }, "全角カタカナを入力下さい"
	);
</script>
<script src="{{URL::to('/')}}/js/plugins/kana/jquery.autoKana.js" type="text/javascript"></script>
<script type="text/javascript"><!--
//jQuery.noConflict();
	$(function() {
		$.fn.autoKana('#first_name', '#furi_first_name', {
			katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
		});
		$.fn.autoKana('#last_name', '#furi_last_name', {
			katakana : true  //true：カタカナ、false：ひらがな（デフォルト）
		});
	});
	-->
</script>
<script type="text/javascript">
	var validate_rule_payment = {
            card_num: { required: true, number: true, minlength: 15, maxlength: 16 },
            card_expired_y: { required: true, number: true},
            card_expired_y: { required: true, number: true},
            secure_num: { required: true, number: true , minlength: 3, maxlength: 3}
        };
	$('#formcard-form').validate({
		errorElement : 'span',
		errorPlacement: function(error, element) {
			var eP = $(".error"+element.attr("name"));
			error.appendTo(eP);
		},

		rules: validate_rule_payment,
		messages: {
			last_name: {
				required: jQuery.validator.format("姓を入力してください"),
			},
			first_name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_first_name: {
				required: jQuery.validator.format("名前を入力してください"),
			},
			furi_last_name: {
				required: jQuery.validator.format("姓を入力してください"),
			},
			'checkboxes1[]': {
				required: 'Please check some options',
				minlength: jQuery.validator.format("At least {0} items must be selected"),
			},
			'checkboxes2[]': {
				required: 'Please check some options',
				minlength: jQuery.validator.format("At least {0} items must be selected"),
			}
		},
		submitHandler: function(form) {
			check();
		}
	});
	$('input,textarea').on('keyup blur', function() {
    var $submitBtn = $('.submitBtn');
    if ($("#formcard-form").valid()) {
        $submitBtn.prop('disabled', false);
    } else {
        $submitBtn.prop('disabled', 'disabled');
    }
});
</script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
@endsection
