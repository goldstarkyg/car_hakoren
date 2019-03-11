@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_policy.css" rel="stylesheet" type="text/css">
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
                        <li>
                            <span>個人情報保護方針</span>
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
								<h1>個人情報保護方針（プライバシーポリシー）</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
					<div class="box-shadow" style="padding-top:30px;padding-bottom:30px;">
						<div class="row">
							<div class="col-md-10 col-md-offset-1 rule">
								<h2>個人情報保護方針</h2>
									<p>ハコレンタカー（以下「当社」といいます。）は、高度情報通信社会における個人情報保護の重要性を認識し、「個人情報の保護に関する法律」（以下「個人情報保護法」といいます。）等の法令及びその他の規範を遵守するとともに、これらの法令及び規範に適合した個人情報保護遵守規程・規則等（コンプライアンス・プログラム）を策定し、個人情報の適正な取扱い及び保護に努めます。</p>

									<h4>1．個人情報の取得について</h4>

									<p>当社は、個人情報（個人情報保護法で定義する個人情報をいいます。以下「個人情報」といいます。）を取得する場合、利用目的をできる限り特定し、あらかじめ当社ウェブサイト等において利用目的を公表している場合を除き、速やかにその利用目的をご本人に通知又は公表します。また、あらかじめご本人の同意を得た場合、その他法令により認められる場合を除き、要配慮個人情報を取得しません。</p>

									<h4>2．個人情報の利用について</h4>

									<p>当社は、取得した個人情報を利用目的の達成に必要な範囲で利用します。この範囲を超えて個人情報を利用する必要が生じた場合には、法令に定めがある場合を除き、その利用についてご本人の同意を得るものとします。なお、当社は業務を円滑に進めるため、業務の一部を外部に委託し、業務委託先に対して必要な範囲で個人情報を提供することがありますが、この場合、当社はこれらの業務委託先との間で個人情報保護のために必要な事項を取り決め、個人情報の取扱いについて必要かつ適切な監督を行います。</p>

									<h4>3．個人データの安全管理について</h4>

									<p>当社は、データベース等により容易に検索可能な状態にした個人情報（以下「個人データ」といいます。）について、正確性を保つとともに、紛失、改ざん、漏洩、外部からの不正アクセス等の防止等、個人データを安全に管理するために適正な組織的・人的・物理的・技術的安全管理措置を講じます。</p>

									<h4>4．個人データの第三者提供について</h4>

									<p>当社は、法令により認められる場合を除き、本人の同意を得ることなく第三者（外国にある第三者を含む）に個人データを提供しません。</p>

									<h4>5．個人データの開示・訂正・利用停止・消去等について</h4>

									<p>当社は、当社が保有する個人データについて、開示・訂正・利用停止・消去等のお申し出があった場合には、お申し出いただいた方がご本人であることを確認した上で、合理的な期間及び範囲で適切に対応します。</p>

									<h4>6．個人情報保護の所内体制について</h4>

									<p>当ウェブサイトの利用は、お客様の責任において行われるものとします。 当ウェブサイト及び当ウェブサイトにリンクが設定されている、他のウェブサイトから取得された各種情報の利用によって生じたあらゆる損害に関して、当社は一切の責任を負いません。</p>

									<h4>7．お問合せについて</h4>

									<p>当社の個人情報の保護に関するお問合せ、相談、苦情等は、下記窓口で、電話・郵便・電子メールにて受付けております。</p>
									<ul>
										<li>＜お問合せ先＞</li>
										<li>ハコレンタカー　個人情報保護担当</li>
										<li>〒812-0857 福岡県福岡市博多区西月隈2丁目10番</li>
										<li>Tel： 092-260-9506</li>
										<li>Mail: info@hakoren.com</li>
									</ul>

									<h4>8．個人情報保護方針の見直し・改善について</h4>

									<p>当社は、個人情報の取扱いをより適正なものにするために、この個人情報保護方針を適宜見直し、継続的に改善します。</p>
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

@section('footer_scripts')
@endsection
