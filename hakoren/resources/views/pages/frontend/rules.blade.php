@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_rules.css" rel="stylesheet" type="text/css">
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
                            <span>利用規約 </span>
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
								<h1>利用規約 ・ 会員規約</h1>
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
								<h2>利用規約</h2>
								<p>この利用規約（以下、当規約）は、ハコレンタカー（以下、当所）が提供するサービスの利用条件を定めるものです。当サービスの利用者（以下、利用者）は、当規約をよくお読み頂き当サービスをご利用ください。当サービスの利用者は、当規約に同意いただいたものとして取り扱わせて頂きます。</p>

								<h4>第１条（ハコレンタカー）</h4>

								<p>ハコレンタカー（以下、当サイト）とは、当所が運営するレンタカー業務全般を取り扱うサイトをいいます。当所は、当サイトを通じて、レンタカーやそれに付随する各サービス（以下、当サービス）を提供します。当サイトは、レンタカーを適正に使用するための情報を掲載しています。よって、それ以外の方に対する情報提供を目的としたものではありません。</p>

								<h4>第２条（情報配信）</h4>

								<p>当所は、利用者に対し有益と思われる情報を、当サイト上に配信します。当サイト上から配信される情報には登録者の登録情報に基づいて作成される情報（登録者自身の氏名、住所等）があり、第三者が閲覧できない情報が含まれている場合があります。当サイトから配信される情報を第三者に転送したことにより生じる利用者の損害に関して、当所は一切の責任を負わないものとします。</p>

								<h4>第３条（サービス内容等の変更・中断・廃止）</h4>

								<p>当所は、利用者への予告なしに当情報の内容を変更し、当サイトを閉鎖することがあります。当所は、当サービスを提供するために使用する設備およびソフトウェアの保守・点検を行う場合、または天災地変等の非常事態の発生により通信回線に異常が生じた場合などやむを得ない事情により、当サービスの全部または一部の提供を中断する場合があります。</p>

								<h4>第４条（クッキー（Cookie）およびアクセス履歴）</h4>

								<p>当サイトでは、提供する情報やサービスの向上のためにCookieを使用しています。また、当サイトでは、ホームページの保守管理やご利用状況に関する統計分析のために、アクセス履歴を記録しています。当サイトでは、当サイトへ会員登録された方に対して、その方のアクセス履歴に基づき、各種情報、広告等を提供することがあります。</p>

								<h4>第５条（利用規約の変更）</h4>

								<p>当所は、当サービスの追加変更または法令の改正等に伴い、予告なしに当規約を追加、変更、削除する場合があります。変更を行った当規約は、サイト上に表示されることにより効力を生ずるものとします。</p>

								<h4>第６条（利用環境）</h4>

								<p>当サービスは、ご利用のハードウェアやソフトウェア環境、またはアップデート等により表示に問題が生じる場合がございます。表示に問題がある場合は、ご利用のハードウェアやソフトウェア環境の提供元にお問合せください。</p>

								<h4>第７条（免責事項）</h4>

								<p>当所は、当サイトにおいて提供される情報（リンク先の情報を含む）について、慎重に作成、管理しますが、その完全性、正確性、確実性、有用性等のいかなる保証も行いません。当サービスの提供、遅滞、変更、中断、中止もしくは廃止、および当サイトを通じて登録、提供される情報等の流失もしくは消失等で発生した利用者の損害について、当所は一切の責任を負いません。</p>

								<h4>第８条（知的財産権）</h4>

								<p>当サイトから配信される情報に関する著作権等の知的財産権は、当所または第三者に帰属します。利用者は、当該情報を印刷・複製することや第三者へ提供することはできますが、当該情報を改変し、第三者へ提供される場合には、事前に当所へご連絡ください。</p>

								<h4>第９条（準拠法、管轄裁判所）</h4>
								<p>当規約の解釈にあたっては、日本国の法令を準拠法とします。当サービスに関して紛争が生じた場合には、当所の本店所在地を管轄する裁判所を専属的合意管轄とします。</p>

								<h2>会員規約</h2>
								<p>この利用規約（以下、当規約）は、ハコレンタカー（以下、当社）が提供するサービスの利用条件を定めるものです。当サービスの利用者（以下、利用者）は、当規約をよくお読み頂き当サービスをご利用ください。当サービスの利用者は、当規約に同意いただいたものとして取り扱わせて頂きます。</p>

								<h4>第１条（ハコレンタカー）</h4>
								<p>ハコレンタカー（以下、当サイト）とは、当社が運営するレンタカー業務全般を取り扱うサイトをいいます。当社は、当サイトを通じて、レンタカーやそれに付随する各サービス（以下、当サービス）を提供します。当サイトは、レンタカーを適正に使用するための情報を掲載しています。よって、それ以外の方に対する情報提供を目的としたものではありません。</p>

								<h4>第２条（情報配信）</h4>
								<p>当社は、利用者に対し有益と思われる情報を、当サイト上に配信します。当サイト上から配信される情報には登録者の登録情報に基づいて作成される情報（登録者自身の氏名、住所等）があり、第三者が閲覧できない情報が含まれている場合があります。当サイトから配信される情報を第三者に転送したことにより生じる利用者の損害に関して、当所は一切の責任を負わないものとします。</p>

								<h4>第３条（サービス内容等の変更・中断・廃止）</h4>
								<p>当社は、利用者への予告なしに当情報の内容を変更し、当サイトを閉鎖することがあります。当社は、当サービスを提供するために使用する設備およびソフトウェアの保守・点検を行う場合、または天災地変等の非常事態の発生により通信回線に異常が生じた場合などやむを得ない事情により、当サービスの全部または一部の提供を中断する場合があります。</p>

								<h4>第４条（クッキー（Cookie）およびアクセス履歴）</h4>
								<p>当サイトでは、提供する情報やサービスの向上のためにCookieを使用しています。また、当サイトでは、ホームページの保守管理やご利用状況に関する統計分析のために、アクセス履歴を記録しています。当サイトでは、当サイトへ会員登録された方に対して、その方のアクセス履歴に基づき、各種情報、広告等を提供することがあります。</p>

								<h4>第５条（利用規約の変更）</h4>
								<p>当社は、当サービスの追加変更または法令の改正等に伴い、予告なしに当規約を追加、変更、削除する場合があります。変更を行った当規約は、サイト上に表示されることにより効力を生ずるものとします。</p>

								<h4>第６条（利用環境）</h4>
								<p>当サービスは、ご利用のハードウェアやソフトウェア環境、またはアップデート等により表示に問題が生じる場合がございます。表示に問題がある場合は、ご利用のハードウェアやソフトウェア環境の提供元にお問合せください。</p>

								<h4>第７条（免責事項）</h4>
								<p>当社は、当サイトにおいて提供される情報（リンク先の情報を含む）について、慎重に作成、管理しますが、その完全性、正確性、確実性、有用性等のいかなる保証も行いません。
	当サービスの提供、遅滞、変更、中断、中止もしくは廃止、および当サイトを通じて登録、提供される情報等の流失もしくは消失等で発生した利用者の損害について、当社は一切の責任を負いません。</p>

								<h4>第８条（知的財産権）</h4>
								<p>当サイトから配信される情報に関する著作権等の知的財産権は、当社または第三者に帰属します。利用者は、当該情報を印刷・複製することや第三者へ提供することはできますが、当該情報を改変し、第三者へ提供される場合には、事前に当所へご連絡ください。</p>

								<h4>第９条（準拠法、管轄裁判所）</h4>
								<p>当規約の解釈にあたっては、日本国の法令を準拠法とします。当サービスに関して紛争が生じた場合には、当社の本店所在地を管轄する裁判所を専属的合意管轄とします。</p>
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
