<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Print All Booking Task - Hakoren</title>
    <style type="text/css">
        body {
            font-size: 10px;
            font-family: "Noto Sans Japanese";
            padding: 0px;
            margin: 0px;
        }
        
        * {
            box-sizing: border-box;
        }
        
        table {
            border-collapse: collapse;
        }
        .cls{
           float: right; 
        }
        .cls a{
          
        }
        .prt{
           float: left; 
        }
        .prt a{
            
        }
        .prtn-cont {
            width: 800px;
            margin: 18px auto;
        }
        .cls-prt{
            overflow: hidden;
            width: 800px;
            margin: 10px auto 0;
        }
        .txt1 {
            text-align: right;
        }
        
        .midl {
            text-align: center;
        }
        
        .midl-cmn {
            display: inline-block;
            border: 1px solid #d0cbc7;
            padding: 2px 70px;
        }
        
        .midl1 {font-size: 16px; font-weight: 600;}
        
        .midl2 {}
        
        .btm-block {
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .btm-cm {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding: 0 4px;
        }
        .btm-cm p{
            margin: 0;
        }
        .btm-left {}
        
        .btm-right {border: 1px solid #d0cbc7;}
        .btm-right p{
            padding: 4px;
        }
        
        .pas-block {
            margin: 20px 0;
        }
        
        .pas-block p {
            margin: 2px 0 0;
            font-size: 16px;
            display: inline-block;
            vertical-align: middle;
        }
        .pas-block .checkbox{
            display: inline-block;
            width: 24px;
            height: 24px;
            vertical-align: top;
            margin: 0 10px;
        }
        .pas-block .checkbox label:before{width: 22px; height: 22px;}
        .print1 {
            margin: 0 0 8px;
        }
        .pas-block h2{
            display: inline-block;
            margin: 2px 0 0 0;
            vertical-align: middle;
            line-height: normal;
        }
        .print1 img {
            
        }
        
        .txt-block {
            overflow: hidden;
            margin: 0 0 8px;
        }
        
        .txt-cm {
            display: inline-block;
            padding: 0 10px;
        }
        
        .txt-cm p {
            margin: 0;
            padding: 2px;
            font-size: 14px;
        }
        
        .txt-lft {
            float: left;
        }
        
        .txt-rht {
            float: right;
            border: 1px solid #d0cbc7;
        }
        
        .print2 {}
        
        .print2 img {
            width: 100%;
        }
        .ftr-block{
            margin-top: 8px;
            overflow: hidden;
        }
        .ftr-cm{
            display: table-cell;
            width: 400px;
            vertical-align: top;
            padding: 0 4px;
        }
        .ftr-cm p{
            margin: 0;
        }
        .ftr-lft{
            
        }
        .ftr-cnt-info{
            border: 1px solid #d0cbc7;
            padding: 4px;
        }
        .ftr-cnt{margin: 0 0 2px;}
        .ftr-cnt span{}
        .cnt-info{padding: 0 8px;}
        .cnt-info h2{ margin: 0 0 4px; font-size: 14px;}
        .cnt-info p{margin: 0 0 8px;}
        .ftr-rht{border: 1px solid #d0cbc7;}
        .ftr-upp{ border: 1px solid #d0cbc7; padding: 2px; margin: 0 0 2px;}
        .ftr-upp p{}
        .ftr-low{padding: 6px;}
        .ftr-low h2{margin: 10px 0 20px; font-size: 12px;}
        .ftr-low p{margin: 6px 0;}
        .ftr-low p span{display: block; margin: 20px 0 0;}
		.dt-block{
            margin: 31px 0 24px;
        }
        .dt-block ul{
            margin: 0;
            padding: 0;
            display: inline-block;
        }
        .dt-block span{
            float: right;
            margin: 0 10px 0 0;
        }
        .dt-block ul li{
            display: inline-block;
            list-style-type: none;
            /*border-right: 1px solid #000;*/
            padding: 0 22px;
        }
        .dt-block ul li:last-child{
            border: none;
        }
        .pas2-block{
            text-align: center;
            margin: 18px 0;
        }
        .pas2-block p{
            margin: 0;
        }
        .ftr-logo{text-align: center; margin: 20px 0 0;}
        .ftr-logo img{
            
        }
        .no-border{ border: 0;}
        .border-rht{ border-right: 1px solid #000;}
        .top-panel {page-break-after: always;}
        @media print {
        .no-print{ display: none;}
		 body {margin-top: 0mm; margin-bottom: 0mm; margin-left: 0mm; margin-right: 0mm};
        .top-panel {page-break-after: always;}
		.option-bg {background-color: #ececec; -webkit-print-color-adjust: exact; }
        }
        @page {
            margin-top: 0cm;
            margin-bottom: 0cm;
         }
		 
		 /*==Custom Table===*/
        .vhcl-block{overflow: hidden; margin-left: -20px; margin-right: -20px;}
        .vhcl-lft{float: left; width: 60%; padding: 0px 20px; text-align: center;}
        .vhcl-lft table{height: 104px;}
        .vhcl-rht{float: left; width: 40%; padding: 0 20px; text-align: center;}
        .vhcl-rht table{height: 104px;}
        .vhcl-rht table td{vertical-align: top; padding-top: 10px; width: 50%;}
		 
		/*==Checkbox===*/
        
        .option-main {overflow: hidden; margin-left: -10px; margin-right: -10px;}
        .option-common {float: left; width: 50%; padding: 0 10px; margin: 0 0 4px;}
        .option-bg {background-color: #ececec; padding: 10px; overflow: hidden;}
        .option-left {float: left; width: 40%; padding: 0 10px; text-align: center; margin: 0;}
        .option-left img{ }
        .option-left h2 { margin: 4px 0 0;}
        .option-right {float: left; width: 60%; padding: 0 10px; margin-left: -6px; margin-right: -6px;}
        .option-right .checkbox label{display: none;}
        .option-chk {}
        .checkbox{}
        .checkbox input{display: none;}
        .checkbox label{display: inline-block; cursor: pointer; position: relative; font-size: 13px; width: 100%; height: 24px;}
        .checkbox label:before { content: ""; display: inline-block; width: 42px; height: 42px; position: absolute; left: 0; border: 2px solid #464646;}
        .checkbox input[type=checkbox] + label:before {content: "\2713"; font-size: 24px; color: #000; text-align: center; line-height: 24px;}
        .option-list {float: left; width: 63%; padding: 0 6px;}
        .option-list h2 {margin: 0;}
        .option-list ul {margin: 0; padding: 0 0 0 20px;}
        .option-list ul li {}
    </style>
</head>

<body>
   <div class="cls-prt">
            <div class="cls">
                <a class="btn btn-sm btn-success no-print" href="javascript:void(0);" onclick="window.close();" title="Close Window">閉じる</a>
            </div>
            <div class="prt">
                <a class="btn btn-sm btn-success no-print" href="javascript:void(0);" onclick="window.print();" title="Print Now">印刷</a>
            </div>
        </div>
    @foreach($data as $singleRecord)
    <div class="prtn-cont print-all">
        <div class="top-panel">
            <div class="txt1 ">
                <span>
                    @if( $singleRecord->last_name.' '.$singleRecord->first_name == ' ')
                        <td>{!! $singleRecord->fur_last_name.' '.$singleRecord->fur_first_name !!} 様</td>
                    @else
                        <td>{!! $singleRecord->last_name.' '.$singleRecord->first_name !!} 様</td>
                    @endif
                     [ お客様控え ]</span>
            </div>
            <div class="midl">
                <div class="midl1 midl-cmn">車両チェックシート</div>
            </div>
            <div class="btm-block">
                <div class="btm-left btm-cm">
                    <p>
					当社は、道路運送車両法第47条の2 (日常点検整備) 及び第48条 (定期点検整備) に定める点検をし、必要な整備を実施したレンタカーを貸渡するものとします。<br/>
					2 借受人又は運転者は、レンタカーの貸渡にあたり、本紙に定める点検表に基づく車体外観及び付属品の検査を行い、レンタカーに整備不良がないこと等を確認するとともにレンタカーが貸渡条件を満たしていることを確認するものとします。
					</p>
                </div>
                <div class="btm-right btm-cm">
                    <p>借受人又は運転者ご確認署名欄</p>
				</div>
            </div>
			<div class="vhcl-block">
                <div class="vhcl-lft">
                    <table width="100%" border="1" cellspacing="5" cellpadding="5">
                        <tr>
                            <td style="width:50%;">
                                出発日
                            </td>
                            <td>
                                {!! date('Y/m/d', strtotime($singleRecord->departing)) !!}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                クラス
                            </td>
                            <td>
                                {!! $singleRecord->class_name !!} / {!! $singleRecord->shortname !!}
                            </td>
                        </tr>
						<tr>
                            <td>
                                車両番号
                            </td>
                            <td>
                                <div> <span>{{$singleRecord->car_number1 }} {{$singleRecord->car_number2 }}</span> <span>{{$singleRecord->car_number3 }}</span> <span>{{$singleRecord->car_number4 }}</span> </div>
                            </td>
                        </tr>
<!--
						<tr>
                            <td>
                                顧客の電話番号
                            </td>
                            <td>
                                {{$singleRecord->phone }}
                            </td>
                        </tr>
-->
                    </table>
                </div>
                <div class="vhcl-rht">
                    <table width="100%" border="1" cellspacing="5" cellpadding="5">
                        <tr>
                            <td>
                                車両点検整備者<br/><br/><span style="font-size:20px;">山田</span>
                            </td>
                            <td>
                                担当者
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- <table width="100%" border="1" cellspacing="5" cellpadding="5"> -->
                <!-- <tr> -->
                    <!-- <td width="20%">名前</td> -->
                    <!-- @if( $singleRecord->last_name.' '.$singleRecord->first_name == ' ') -->
                        <!-- <td>{!! $singleRecord->fur_last_name.' '.$singleRecord->fur_first_name !!} 様</td> -->
                    <!-- @else -->
                        <!-- <td>{!! $singleRecord->last_name.' '.$singleRecord->first_name !!} 様</td> -->
                    <!-- @endif -->
                    <!-- <td>&nbsp;</td> -->
                    <!-- <td width="15%" class="no-border border-rht"></td> -->
                    <!-- <td width="15%" class="no-border"></td> -->
                <!-- </tr> -->
                <!-- <tr> -->
                    <!-- <td>出発日</td> -->
                    <!-- <td>{!! date('Y/m/d', strtotime($singleRecord->departing)) !!}</td> -->
                    <!-- <td>&nbsp;</td> -->
                    <!-- <td class="no-border border-rht"></td> -->
                    <!-- <td class="no-border"></td> -->
                <!-- </tr> -->
                <!-- <tr> -->
                    <!-- <td>クラス</td> -->
                    <!-- <td>{!! $singleRecord->class_name !!}</td> -->
                    <!-- <td>&nbsp;</td> -->
                    <!-- <td class="no-border border-rht"></td> -->
                    <!-- <td class="no-border"></td> -->
                <!-- </tr> -->
                <!-- <tr> -->
                    <!-- <td>車両番号</td> -->
                    <!-- <td> -->
                        <!-- <div> <span>{{$singleRecord->car_number1 }} {{$singleRecord->car_number2 }}</span> <span>{{$singleRecord->car_number3 }}</span> <span>{{$singleRecord->car_number4 }}</span> </div> -->
                    <!-- </td> -->
                    <!-- <td>&nbsp;</td> -->
                    <!-- <td class="no-border border-rht"></td> -->
                    <!-- <td class="no-border"></td> -->
                <!-- </tr> -->
                <!-- <tr> -->
                    <!-- <td>顧客の電話番号</td> -->
                    <!-- <td>{{$singleRecord->phone }}</td> -->
                    <!-- <td>&nbsp;</td> -->
                    <!-- <td class="no-border border-rht"></td> -->
                    <!-- <td class="no-border"></td> -->
                <!-- </tr> -->
            <!-- </table> -->
            <div class="pas-block">
                <p>【日常点検整備チェック項目】</p>
                <div class="checkbox">
                    <input id="check1" type="checkbox" name="check" value="check1">
                    <label for="check1"></label>
                </div>
                <h2>車両点検整備者が以下の項目をチェックしました</h2>
            </div>
            <div class="print1">
                <div class="option-main">
                    <div class="option-common option-one">
                        <div class="option-bg">
                            <div class="option-left">
                                <img src="{{URL::to('/')}}/img/print/icon01.png">
                                <h2>油類・液類</h2>
                            </div>
                            <div class="option-right">
                                <div class="checkbox">
                                    <input id="check1" type="checkbox" name="check" value="check1">
                                    <label for="check1"></label>
                                </div>
                                <div class="option-list">
                                    <h2>チェック項目</h2>
                                    <ul>
                                        <li>エンジンオイルの量</li>
                                        <li>ブレーキフルード量</li>
                                        <li>冷却水の量・漏れ</li>
                                        <li>バッテリー液量</li>
                                        <li>クラッチオイル</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-common option-two">
                        <div class="option-bg">
                            <div class="option-left">
                                <img src="{{URL::to('/')}}/img/print/icon02.png">
                                <h2>エンジン・ベルト</h2>
                            </div>
                            <div class="option-right">
                                <div class="checkbox">
                                    <input id="check2" type="checkbox" name="check" value="check2">
                                    <label for="check2"></label>
                                </div>
                                <div class="option-list">
                                    <h2>チェック項目</h2>
                                    <ul>
                                        <li>かかり具合及び異音</li>
                                        <li>ファンベルト/張り具合や損傷</li>
                                        <li>パワステ・エアコンベルト/張り具合や損傷</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-common option-three">
                        <div class="option-bg">
                            <div class="option-left">
                                <img src="{{URL::to('/')}}/img/print/icon03.png">
                                <h2>タイヤ</h2>
                            </div>
                            <div class="option-right">
                                <div class="checkbox">
                                    <input id="check3" type="checkbox" name="check" value="check3">
                                    <label for="check3"></label>
                                </div>
                                <div class="option-list">
                                    <h2>チェック項目</h2>
                                    <ul>
                                        <li>前右、前左、後右、後左の<br/>タイヤの状態/偏摩耗/空気圧等</li>
                                        <li>スペアタイヤの状態</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-common option-four">
                        <div class="option-bg">
                            <div class="option-left">
                                <img src="{{URL::to('/')}}/img/print/icon04.png">
                                <h2>ブレーキ</h2>
                            </div>
                            <div class="option-right">
                                <div class="checkbox">
                                    <input id="check4" type="checkbox" name="check" value="check4">
                                    <label for="check4"></label>
                                </div>
                                <div class="option-list">
                                    <h2>チェック項目</h2>
                                    <ul>
                                        <li>ブレーキペダル</li>
                                        <li>エアブレーキ</li>
                                        <li>駐車ブレーキ</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-common option-five">
                        <div class="option-bg">
                            <div class="option-left">
                                <img src="{{URL::to('/')}}/img/print/icon05.png">
                                <h2>ランプ・ワイパー</h2>
                            </div>
                            <div class="option-right">
                                <div class="checkbox">
                                    <input id="check5" type="checkbox" name="check" value="check5">
                                    <label for="check5"></label>
                                </div>
                                <div class="option-list">
                                    <h2>チェック項目</h2>
                                    <ul>
                                        <li>ヘッドライト（上下）</li>
                                        <li>テールランプ</li>
                                        <li>ストップランプ</li>
                                        <li>ウィンカーランプ</li>
                                        <li>ワイパー（前後）</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="option-common option-six">
                        <div class="option-bg">
                            <div class="option-left">
                                <img src="{{URL::to('/')}}/img/print/icon06.png">
                                <h2>シートベルト</h2>
                            </div>
                            <div class="option-right">
                                <div class="checkbox">
                                    <input id="check6" type="checkbox" name="check" value="check6">
                                    <label for="check6"></label>
                                </div>
                                <div class="option-list">
                                    <h2>チェック項目</h2>
                                    <ul>
                                        <li>破損・ねじれの具合</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="txt-block">
                <div class="txt-lft txt-cm">
                    <p>【外装チェック】（受渡時のボディチェック）</p>
                </div>
                <div class="txt-rht txt-cm">
                    <p>～すり傷　〇へこみ　●塗装済</p>
                </div>
            </div>
            <div class="print2">
                <img src="{{URL::to('/')}}/img/print/print2.jpg">
            </div>
            <div class="ftr-block">
                <div class="ftr-lft ftr-cm">
                  <div class="ftr-cnt-info">
                   <div class="ftr-cnt">
                    <p>
						本日はハコレンタカーをご利用いただき誠にありがとうございます。<br/>お客様に安全、快適にご利用いただきますよう心を込めてご準備いたしました。<br/>何かお気づきの点がございましたら、ご遠慮なくフロントまでお申し出くださいませ。<br/>これからも、良いクルマと良いサービスをモットーに皆様のご期待に沿えるよう努めて参ります。
					</p>
                     <span>●貸出に関する連絡先店舗</span>
                    </div>
					<div class="cnt-info">
                        <h2>ハコレンタカー {{ $car_shop_name }}</h2>
					    <p style="margin: 0 0 5px;">住所 : {{$car_shop_prefecture}}{{$car_shop_city}}{{$car_shop_address_1}}{{$car_shop_address_2}}</p>
					    <p style="margin-bottom: 10px;">TEL: {{ $car_shop_phone }}　Email: info@hakoren.com</p>
					</div>
                    </div>
                </div>
                <div class="ftr-rht ftr-cm">
<!--
                   <div class="ftr-upp">
                    <p>※お客様よりお預かりした車両について<br/>レンタカーをご利用中、お客様の車両（バイク、自転車を含む）をお預かりいたしますが、（原則として1台）、これはサービスの一環として行っています。<br/>その為、お預かり中の車両及びその積載品において、不注意によらないで万が一事故や盗難・破損等の損害が発生しましても当社は一切の責任を負えませんので予めご了承下さいませ。
                    </p>
                    </div>
-->
                    <div class="ftr-low">
                    <h2>燃料満タン給油証明書（ガソリン・軽油）</h2>
					<p>
						上記のレンタカーを当店にて満タンにしましたことを証明いたします。
                        <span>給油所名</span>
					</p>
					<div class="dt-block">
                            <ul>
                                <li>日付</li>
                                <li>年</li>
                                <li>月</li>
                                <li>日</li>
                            </ul>
                           <span>印</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pas2-block">
                <p>※お客様へ - このチェックシートは必ず到着時点で係員にご提出ください。</p>
            </div>
            <div class="ftr-logo">
                <img src="{{URL::to('/')}}/img/print/ftr-logo.jpg">
            </div>
        </div>
    </div>
    @endforeach
</body>

</html>