<?php $__env->startSection('template_title'); ?>
    ダッシュボード
<?php $__env->stopSection(); ?>

<?php $__env->startSection('head'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/js/plugins/nvd3/nv.d3.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.2/d3.min.js" charset="utf-8"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/nvd3/nv.d3.js"></script>
    <script src="<?php echo e(URL::to('/')); ?>/js/plugins/nvd3/stream_layers.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.3/css/rowReorder.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">

    <style>
		.users-table { border: 0; }
		.users-table tr td:first-child { padding-left: 15px; }
		.users-table tr td:last-child { padding-right: 15px; }
		.users-table.table-responsive,
		.users-table.table-responsive table { margin-bottom: 0; }
        text {
            font: 12px sans-serif;
        }
        svg {
            display: block;
        }
        html, body, #chart1, svg {
            margin: 0px;
            padding: 0px;
            height: 100%;
            width: 100%;
        }
        .testBlock {
            display: block;
            float: left;
            height: 300px;
            width: 300px;
        }

		.quickcheck span.largerfont {
			font-size:22px;
			font-weight:600;
		}

		.btnlabel {
			background-color: #3bb3e0; display:inline-block;
			vertical-align:middle;
			padding:4px 6px 3px 6px; vertical-align:middle;
			position:absolute;left: -56px;
			top: -4px;
			font-weight:500;
			font-family: 'Open Sans', sans-serif;
			font-size: 12px;
			text-decoration: none;
			color: #fff;
			border: solid 1px #186f8f;
			background-image: linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
			background-image: -o-linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
			background-image: -moz-linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
			background-image: -webkit-linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
			background-image: -ms-linear-gradient(bottom, rgb(44,160,202) 0%, rgb(62,184,229) 100%);
			background-image: -webkit-gradient( linear, left bottom, left top, color-stop(0, rgb(44,160,202)), color-stop(1, rgb(62,184,229)) );
			-webkit-box-shadow: inset 0px 1px 0px #7fd2f1, 0px 1px 0px #fff;
			-moz-box-shadow: inset 0px 1px 0px #7fd2f1, 0px 1px 0px #fff;
			box-shadow: inset 0px 1px 0px #7fd2f1, 0px 1px 0px #fff;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			-o-border-radius: 5px;
			border-radius: 5px;
		}
		.pielabel {
			min-height: 20px;
			padding: 3px;
			margin-bottom: 10px;
			background-color: #f5f5f5;
			border: 1px solid #e3e3e3;
			border-radius: 4px;
			-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
			box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
		}
		.po_title{
			padding: 3px 6px;
			font-weight: 400;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
		}
		.dashed {stroke-dasharray: 3,3;}
		#portalbookingmount .nv-x text{
			font-size: 0.8em;
		}
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div>
        <div>
			<!--quick start section start-->
			<div class="row panel panel-default quickcheck" style="margin:5px 5px 20px 5px; background-color: #fff5e7 !important;border-top: 4px #fbb047 solid; font-size: 17px; font-weight:400;">
				<form action="<?php echo e(URL::to('/')); ?>/admintop" method="post" class="form-horizontal">
				<div class="col-md-12" style="margin-top:5px; margin-bottom:20px">
					<div class="col-md-9" style="margin-top:5px; text-align:center;">
						<span class="largerfont">クイックレビュー&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo e($quick_check['current_date']); ?>(<?php echo e($quick_check['current_day']); ?>) </span>
					</div>
					<div class="col-md-2" style="margin-top: 5px;" >
						<select name="shop_id" id="shop_id" method="post" class="form-control" style="background-color: #fbb047" onchange="this.form.submit()">
							<option value="0" <?php if($shop_id == '0'): ?> selected <?php endif; ?>>全て</option>
							<?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($shop->id); ?>" <?php if($shop_id == $shop->id): ?> selected <?php endif; ?>><?php echo e($shop->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>
				<div class="col-md-6" style="margin-top: 5px;padding-left:50px;padding-bottom:10px">
					<p>本日の見込売上：
						<span class="largerfont"><?php echo e(number_format($quick_check['expected_sales'])); ?></span>円
						&nbsp;&nbsp;(配車 <?php echo e($quick_check['depart_count']); ?>件 / 返車 <?php echo e($quick_check['return_count']); ?>件 )
          				<br/>本日の実際売上：
						<span class="largerfont">
							<?php if($shop_kind == 'both'): ?>
							<?php echo e(number_format($today_salesmount->all_sales)); ?>

							<?php endif; ?>
							<?php if($shop_kind == 'fuku'): ?>
								<?php echo e(number_format($today_salesmount->fuku_sales)); ?>

							<?php endif; ?>
							<?php if($shop_kind == 'okina'): ?>
								<?php echo e(number_format($today_salesmount->okina_sales)); ?>

							<?php endif; ?>
							<?php if($shop_kind == 'kagoshima'): ?>
								<?php echo e(number_format($today_salesmount->kagoshima_sales)); ?>

							<?php endif; ?>
						</span>円
						&nbsp;&nbsp;(配車 <?php echo e($quick_check['real_depart_count']); ?>件 / 返車 <?php echo e($quick_check['real_return_count']); ?>件 )
						<br/>
            				<?php echo e(date('n', strtotime($quick_check['cu_month']))); ?>

                    月の見込売上： <span class="largerfont"><?php echo e(number_format($quick_check['month_expected_sales'])); ?></span>円<br/>
            				<?php echo e(date('n', strtotime($quick_check['cu_month']))); ?>

                    月の実際売上： <span class="largerfont"><?php echo e(number_format($quick_check['month_real_sales'])); ?></span>円　　
					</p>
				</div>
				<div class="col-md-6" style="margin-top: 5px;padding-bottom:10px">
					<p>本日の予約数：
						<span class="largerfont"><?php echo e($quick_check['created_count']); ?></span>件&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;キャンセル数：<span class="largerfont"><?php echo e($quick_check['cancel_count']); ?></span>件 <br/>
            <?php echo e(date('n', strtotime($quick_check['cu_month']))); ?>月の予約数：
						<span class="largerfont"><?php echo e($quick_check['cu_month_depart_count']); ?></span>件&nbsp;&nbsp;
						<span class="largerfont"> <?php echo e($quick_check['cu_month_usedcar']); ?> </span>%が埋まっています。
						<br/><?php echo e(date('n', strtotime($quick_check['next_month']))); ?>月の予約数：
						<span class="largerfont"><?php echo e($quick_check['next_month_depart_count']); ?></span>件&nbsp;&nbsp;
						<span class="largerfont"> <?php echo e($quick_check['next_month_usedcar']); ?> </span>%が埋まっています。
						<br/><?php echo e(date('n', strtotime($quick_check['next_next_month']))); ?>月の予約数：
						<span class="largerfont"><?php echo e($quick_check['next_next_month_depart_count']); ?></span>件&nbsp;&nbsp;
						<span class="largerfont"> <?php echo e($quick_check['next_next_month_usedcar']); ?> </span>%が埋まっています。
					</p>
				</div>
					<?php echo csrf_field(); ?>

				</form>
			</div>
			<!--quick start secction end-->
			<!--2 section start-->
			<div class="row">
				<div class="col-md-9">
					<div class="row panel panel-default" style="background-color: #ffffff; margin-left: 5px;margin-bottom: 10px;padding-bottom: 20px;">
						<div>
							<h3><span class="btnlabel">Box 1</span> <span style="padding-left:4px;"><?php echo e(date('n')); ?>月の売上・件数</span></h3>
						</div>
						<div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
							<div id="realsalechart" class='with-3d-shadow with-transitions'>
							</div>
						</div>
						<div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
							<div id="realamountchart" class='with-3d-shadow with-transitions'>
								<svg></svg>
							</div>
						</div>
					</div>
					<div class="row panel panel-default" style="background-color: #ffffff; margin-left: 5px;padding-bottom: 20px;">
						<div>
							<h3><span class="btnlabel">Box 2</span> <span style="padding-left:4px;"><?php echo e(date('n')); ?>月の獲得予約</span></h3>
						</div>
						<div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
							<div id="temporalchart" class='with-transitions'>

							</div>
						</div>
						<div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
							<div id="temporalamountchart" class='with-3d-shadow with-transitions'>
								<svg> </svg>
							</div>
						</div>
					</div>
					<!-- Expected chart is hidden / Moto
					<div class="row panel panel-default" style="background-color: #ffffff; margin-left: 5px;">
						<div>
							<h3><span class="btnlabel"><?php echo e(date('n')); ?>月</span>&nbsp;&nbsp; Expected Price</h3>
						</div>
						<div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
							<div id="expectedchart" class='with-transitions'>
							</div>
						</div>
						<div class="col-md-6" style="padding-left: 0px; padding-right: 0px;">
							<div id="expectedamountchart" class='with-3d-shadow with-transitions'>
								<svg> </svg>
							</div>
						</div>
					</div>
					-->
				</div>
				<div class="col-md-3">
					<div class="row panel panel-default" style="background-color: #ffffff;margin-left: 5px; margin-right: 5px;">
						<div style="margin-bottom:20px">
							<h3><span class="btnlabel">Box 3</span> <span style="padding-left:4px;">部門別</span></h3>
						</div>
						<div style="background-color: #d7efff;">
							<div style="padding: 30px 0px 30px 10px;;">
								<p style="text-align: center; font-size: 16px; font-weight: bold">本日の売上</p>
								<p>全体&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($today_salesmount->all_sales)); ?>円&nbsp;/&nbsp;<?php echo e(number_format($today_salesmount->all_mount)); ?>件</p>
								<p>福岡&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($today_salesmount->fuku_sales)); ?>円&nbsp;/&nbsp;<?php echo e(number_format($today_salesmount->fuku_mount)); ?>件&nbsp;(<?php echo e($today_salesmount->fuku_percent); ?>%)</p>
								<p>沖縄&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($today_salesmount->okina_sales)); ?>円&nbsp;/&nbsp;<?php echo e(number_format($today_salesmount->okina_mount)); ?>件&nbsp;(<?php echo e($today_salesmount->okina_percent); ?>%)</p>
								<p>鹿児島&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($today_salesmount->kagoshima_sales)); ?>円&nbsp;/&nbsp;<?php echo e(number_format($today_salesmount->kagoshima_mount)); ?>件&nbsp;(<?php echo e($today_salesmount->kagoshima_percent); ?>%)</p>
							</div>
						</div>
						<div >
							<div style="padding: 30px 0px 20px 10px;;">
								<p style="text-align: center; font-size: 16px; font-weight: bold">補償のみの売上</p>
								<p>全体&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($todayinsurance->all_insurance_price)); ?>円&nbsp;(<?php echo e(number_format($todayinsurance->all_insurance_mount)); ?>件)</p>
								<p>福岡&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($todayinsurance->fuku_insurance_price)); ?>円&nbsp;(<?php echo e(number_format($todayinsurance->fuku_insurance_mount)); ?>件)</p>
								<p>沖縄&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($todayinsurance->okina_insurance_price)); ?>円&nbsp;(<?php echo e(number_format($todayinsurance->okina_insurance_mount)); ?>件)</p>
								<p>鹿児島&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($todayinsurance->kagoshima_insurance_price)); ?>円&nbsp;(<?php echo e(number_format($todayinsurance->kagoshima_insurance_mount)); ?>件)</p>
							</div>
						</div>
						<div>
							<div style="padding: 10px 0px 20px 10px;;">
								<p style="text-align: center; font-size: 16px; font-weight: bold" >オプションのみの売上</p>
								<p>全体&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($todayoption->all_option_price)); ?>円&nbsp;(<?php echo e(number_format($todayoption->all_option_mount)); ?>件)</p>
								<p>福岡&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($todayoption->fuku_option_price)); ?>円&nbsp;(<?php echo e(number_format($todayoption->fuku_option_mount)); ?>件)</p>
								<p>沖縄&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($todayoption->okina_option_price)); ?>円&nbsp;(<?php echo e(number_format($todayoption->okina_option_mount)); ?>件)</p>
								<p>鹿児島&nbsp;:&nbsp;&nbsp;<?php echo e(number_format($todayoption->kagoshima_option_price)); ?>円&nbsp;(<?php echo e(number_format($todayoption->kagoshima_option_mount)); ?>件)</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--2 section end-->
			<!-- 3 sectioin start -->
			<div class="row">
				<div class="col-md-8" style="padding-right: 0px;">
					<div class="panel panel-default">
						<div class="row" style="background-color: #ffffff; margin: 10px 0px 10px 5px;">
							<div>
								<h3><span class="btnlabel">Box 4</span> <span style="padding-left:4px;">直近の予約一覧</span></h3>
							</div>
							<div style="padding: 10px 0px 0px 10px ;">
								<!--<span><?php echo e($todaybooking['date']); ?>  </span>-->
								<span><?php echo e($todaybooking['shop_name']); ?> </span>
							</div>
							<div class="table-responsive users-table">
							<table id="bookinglist" class="table table-borderless data-table" width="100%">
								<thead>
								<tr>
									<th>No.</th>
									<th>経路</th>
									<th>配車日 -  返車日</th>
									<th>車両クラス</th>
									<th>リピート</th>
								</tr>
								</thead>
								<tbody>
								<?php $i = 0; ?>
								<?php $__currentLoopData = $todaybooking['list']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php $i++; ?>
										<!---->
								<tr valign="middle">
									<td>
										<a href="<?php echo e(URL::to('/')); ?>/booking/detail/<?php echo e($book->id); ?>" title="予約詳細を見る">
											<span><?php echo e($book->booking_id); ?></span>
										</a>
									</td>
									<td>
									   <span class="po_title" style="background: <?php echo e($book->color); ?>"><?php echo e($book->portal_name); ?></span>
									</td>
									<td class="cell">
										<span><?php echo e($book->period); ?></span>
									</td>
									<td>
										<span><?php echo e($book->class_name); ?> <?php echo e($book->smoke); ?></span>
									</td>
									<td>
										<span><?php echo e($book->loop_user); ?></span>
									</td>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4" >
					<div class="panel panel-default">
						<div>
							<h3><span class="btnlabel">Box 5</span> <span style="padding-left:4px;"><?php echo e($quick_check['current_date']); ?>の予約経路</span></h3>
						</div>
						<div style="background-color: #ffffff;margin: 10px 5px 10px 0px; ">
							<div class="row" style="padding: 10px 0px 0px 10px ;">
								<!-- <span><?php echo e($todayportalbooking['date']); ?>  </span> -->
								<span><?php echo e($todayportalbooking['shop_name']); ?> </span>
							</div>
							<div class="row">
								<div class="col-md-4">
									<span style="font-size: 16px;margin-top: 61px;position: absolute;font-weight: bold;">全体 <?php echo e($todayportalbooking['all']); ?>件</span>
								</div>
								<div class="col-md-8" class="testBlock">
									<svg id="portalbooking"></svg>
								</div>
							</div>
							<div class="row" style="margin: 0px 5px 10px 10px;">
								<?php $portal = $todayportalbooking['list'] ;  ?>
								<?php $__currentLoopData = $portal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<div class="col-md-4" style="padding-left:0px; padding-right:5px;">
									<div class="pielabel portal_pie" id="portal_<?php echo e($po->id); ?>">
										<label style="padding:0px;width:5px;margin-top:5px; background-color:<?php echo e($po->color); ?>">&nbsp;</label>
										<label><?php echo e($po->key); ?></label>
										<label> <?php echo e($po->y); ?></label>
									</div>
								</div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
				</div>
            </div>
			<!-- 3 section end-->
			<!--4 section start-->
			<div class="row">
				<div class="col-md-12">
					<div class="row panel panel-default" style="background-color: #ffffff; margin: 0px 5px 10px 5px;">
						<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
							<div>
								<h3><span class="btnlabel">Box 6</span> <span style="padding-left:4px;"><?php echo e(date('Y')); ?>年の売上推移</span></h3>
							</div>
							<div id="monthsalechart" class='with-3d-shadow with-transitions'>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--4 section end-->
			<!--5 section start-->
			<div class="row">
				<div class="col-md-12">
					<div class="row panel panel-default" style="background-color: #ffffff;margin: 0px 5px 10px 5px; ">
						<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
							<div>
								<h3><span class="btnlabel">Box 7</span> <span style="padding-left:4px;"><?php echo e(date('Y')); ?>年の配車数の推移</span></h3>
							</div>
							<div id="bookingmount" class='with-3d-shadow with-transitions'>
								<svg></svg>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--5 section end-->
			<!--6 section start-->
			<div class="row">
				<div class="col-md-12">
					<div class="row panel panel-default" style="background-color: #ffffff; margin: 0px 5px 10px 5px;">
						<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
							<div>
								<h3><span class="btnlabel">Box 8</span> <span style="padding-left:4px;"><?php echo e(date('Y')); ?>年の予約件数と経路</span></h3>
							</div>
							<div id="portalbookingmount" class='with-3d-shadow with-transitions'>
								<svg></svg>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--6 section end-->
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer_scripts'); ?>
	<script src="<?php echo e(URL::to('/')); ?>/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo e(URL::to('/')); ?>/js/dataTables.responsive.min.js"></script>
    <script>
		$(document).ready(function() {
			$('.data-table').dataTable({
				"responsive": true,
				"scrollX": false,
				"order": [[ 0, 'desc' ]],
				"paging": true,
				"lengthChange": true,
				"searching": false,
				"pageLength" : 8,
				"lengthChange":false,
//            lengthMenu: [
//                [ 10, 24, 50, 100],
//                [ '10', '24', '50', '100' ]
//            ],
//            buttons: [
//                'pageLength'
//            ],
				"serverSide": false,
				"ordering": true,
				"info": false,
				"autoWidth": true,
				"dom": 'T<"clear">lfrtip',
				"sPaginationType": "full_numbers",
				"language": {
					processing:     "処理中...",
					search:         "検索:",
					lengthMenu:     "_MENU_個の予約を表示",
					info:           "_START_ ~ _END_  を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全項目 _TOTAL_",
					infoEmpty:      "0件中0件から0件までを表示",
					infoFiltered:   "（合計で_MAX_個の項目からフィルタリングされました）",
					infoPostFix:    "",
					loadingRecords: "読み込んでいます...",
					zeroRecords:    "表示する項目がありません",
					emptyTable:     "テーブルのデータがありません",
					paginate: {
						first:      "最初&nbsp;&nbsp",
						previous:   "以前&nbsp&nbsp;",
						next:       "&nbsp;&nbsp;次に",
						last:       "&nbsp;&nbsp;最終"
					},
					aria: {
						sortAscending:  ": 列を昇順にソートする有効にします。",
						sortDescending: ": 列を降順で並べ替えるためにアクティブにする"
					}
				}
			});
		});
		/* sales chart start*/
		nv.addGraph(function() {
			chart = nv.models.lineChart()
					.options({
						duration: 300,
						useInteractiveGuideline: true
					})
					.margin({left: 70,bottom:20});

			// chart sub-models (ie. xAxis, yAxis, etc) when accessed directly, return themselves, not the parent chart, so need to chain separately
			chart.xAxis
					//.axisLabel("Time (s)")
					.tickFormat(function(d) {
						return d+'日';
					});
					//.staggerLabels(true);

			chart.yAxis
					//.axisLabel('Voltage (v)')
					.tickFormat(function(d) {
						var format = d3.format(",");
						return format(d)+'円'; // If you want add '£' symbol
					});

			d3.select('#realsalechart').append('svg')
					.datum(realsalechart())
					.attr('height', 250)
					.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
        function realsalechart() {
            return <?php echo json_encode($realsalechart); ?>;
        }
        /* sales chart end*/
        /* depart chart start */
		nv.addGraph(function() {
			var chart = nv.models.multiBarChart()
					//.transitionDuration(350)
					//.stacked(true)
					.reduceXTicks(true)   //If 'false', every single x-axis tick label will be rendered.
					.rotateLabels(0)      //Angle to rotate x-axis labels.
					.showControls(false)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
					.groupSpacing(0.1)    //Distance between each group of bars.
					.margin({left: 50,bottom:20,right:10})
					;

			chart.xAxis
					//.tickFormat(d3.format('d'));
					.tickFormat(function(d) {
						return d+'日';
					});
			chart.yAxis
					//.tickFormat(d3.format('d'));
					.tickFormat(function (d) {
						return d+'件'; // If you want add '£' symbol
					});


			d3.select('#realamountchart svg')
					.attr('height', 250)
					.datum(realamountchart())
					.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
		function realamountchart() {
			var list =  <?php echo json_encode($realamountchart); ?>;
			var streamcount = list.length;
			return stream_layers(streamcount,13).map(function(data, i) {
				var key, val , bgcolor;
				var one = list[i];
				key = one.key;
				val = one.values;
				bgcolor = one.color;
				return {
					key:   key,
					values: val,
					color: bgcolor
				};
			});
		}

        /* depart chart end */
        /*temporal chart start*/
		nv.addGraph(function() {
			chart = nv.models.lineChart()
					.options({
						duration: 300,
						useInteractiveGuideline: true
					})
					.margin({left: 70,bottom:20});

			// chart sub-models (ie. xAxis, yAxis, etc) when accessed directly, return themselves, not the parent chart, so need to chain separately
			chart.xAxis
					//.axisLabel("Time (s)")
					.tickFormat(function(d) {
						return d+'日';
					});
			//.staggerLabels(true);

			chart.yAxis
					//.axisLabel('Voltage (v)')
					.tickFormat(function(d) {
						var format = d3.format(",");
						return format(d)+'円'; // If you want add '£' symbol
					});

			d3.select('#temporalchart').append('svg')
					.datum(temporalchart())
					.attr('height', 250)
					.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
        function temporalchart() {
            return <?php echo json_encode($temporalchart); ?>;
        }
        /*tem poral chart end*/
        /* booking amount  chart start */
		nv.addGraph(function() {
			var chart = nv.models.multiBarChart()
					//.transitionDuration(350)
					//.stacked(true)
					.reduceXTicks(true)   //If 'false', every single x-axis tick label will be rendered.
					.rotateLabels(0)      //Angle to rotate x-axis labels.
					.showControls(false)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
					.groupSpacing(0.1)    //Distance between each group of bars.
					.margin({left: 50,bottom:20,right:10})
					;

			chart.xAxis
					//.tickFormat(d3.format('d'));
					.tickFormat(function(d) {
						return d+'日';
					});
			chart.yAxis
					//.tickFormat(d3.format('d'));
					.tickFormat(function (d) {
						return d+'件'; // If you want add '£' symbol
					});


			d3.select('#temporalamountchart svg')
					.attr('height', 250)
					.datum(temporalamountchart())
					.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
		function temporalamountchart() {
			var list =  <?php echo json_encode($temporalamount); ?>;
			var streamcount = list.length;
			return stream_layers(streamcount,13).map(function(data, i) {
				var key, val , bgcolor;
				var one = list[i];
				key = one.key;
				val = one.values;
				bgcolor = one.color;
				return {
					key:   key,
					values: val,
					color: bgcolor
				};
			});
		}
        /*booking amount chart end */
		/*expected chart start*/
		nv.addGraph(function() {
			chart = nv.models.lineChart()
					.options({
						duration: 300,
						useInteractiveGuideline: true
					})
					.margin({left: 70,bottom:20});

			// chart sub-models (ie. xAxis, yAxis, etc) when accessed directly, return themselves, not the parent chart, so need to chain separately
			chart.xAxis
					//.axisLabel("Time (s)")
					.tickFormat(function(d) {
						return d+'日';
					});
			//.staggerLabels(true);

			chart.yAxis
					//.axisLabel('Voltage (v)')
					.tickFormat(function(d) {
						var format = d3.format(",");
						return format(d)+'円'; // If you want add '£' symbol
					});

			d3.select('#expectedchart').append('svg')
					.datum(expectedchart())
					.attr('height', 250)
					.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
		function expectedchart() {
			return <?php echo json_encode($expectedchart); ?>;
		}
		/*expected chart end*/
		/* expected amount  chart start */
		nv.addGraph(function() {
			var chart = nv.models.historicalBarChart();
			chart.useInteractiveGuideline(true)
					.margin({left: 50,bottom:20,right:10})
					.duration(250);

			// chart sub-models (ie. xAxis, yAxis, etc) when accessed directly, return themselves, not the parent chart, so need to chain separately
			chart.xAxis
					//.axisLabel("Date (d)")
					//.tickFormat(d3.format('d'));
					.tickFormat(function(d) {
						return d+'日';
					});

			chart.yAxis
					//.axisLabel('Temporal amount')
					//.tickFormat(d3.format('d'));
					.tickFormat(function(d) {
						return d+'件';
					});
			chart.showXAxis(true);

			d3.select('#expectedamountchart svg')
					.datum(expectedamountchart())
					.attr('height', 250)
					.call(chart);

			nv.utils.windowResize(chart.update);
			chart.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
			return chart;
		});
		function expectedamountchart() {
			return <?php echo json_encode($expectedamount); ?>;
		}
		/*booking amount chart end */
        /*start pie chasrt for route*/
        var testdata = [
            {key: "One", y: 5},
            {key: "Two", y: 2},
            {key: "Three", y: 9},
            {key: "Four", y: 7},
            {key: "Five", y: 4},
            {key: "Six", y: 3},
            {key: "Seven", y: 0.5}
        ];
		var portalbooking = <?php echo json_encode($todayportalbooking['list']); ?>;


		var width = 170;
        var height = 170;
        nv.addGraph(function() {
            var chart = nv.models.pie()
                    .x(function(d) { return d.key; })
                    .y(function(d) { return d.y; })
                    .width(width)
                    .height(height)
                    .labelType('percent')
                    .valueFormat(d3.format('%'))
                    .donut(true);



            d3.select("#portalbooking")
                    .datum([portalbooking])
                    .transition().duration(1200)
                    .attr('width', width)
                    .attr('height', height)
                    .call(chart);

			chart.dispatch.on("elementMouseover", function(e) {
				var object_id = e.data.id;
				var color = e.data.color;
				$('.portal_pie').css('background-color', '#f5f5f5');
				$('#portal_'+object_id).css('background-color',color);
				//alert("You've clicked " + e.data.id);
			});
            return chart;
        });
        /*end pie chart for route*/
		/*month sales chart start*/
		nv.addGraph(function() {
			chart = nv.models.lineChart()
					.options({
						duration: 300,
						useInteractiveGuideline: true
					})
					.margin({left: 70,bottom:20});

			// chart sub-models (ie. xAxis, yAxis, etc) when accessed directly, return themselves, not the parent chart, so need to chain separately
			chart.xAxis
					//.axisLabel("Time (s)")
					.tickFormat(function(d) {
						return d+'月';
					});
			//.staggerLabels(true);

			chart.yAxis
					//.axisLabel('Voltage (v)')
					.tickFormat(function(d) {
						var format = d3.format(",");
						return format(d)+'円'; // If you want add '£' symbol
					});

			d3.select('#monthsalechart').append('svg')
					.datum(monthsalechart())
					.attr('height', 250)
					.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
		function monthsalechart() {
			return <?php echo json_encode($monthsalechart); ?>;
		}
		/*month sales chart end*/
		/*month booking number start*/
		nv.addGraph(function() {
			var chart = nv.models.multiBarChart()
					//.transitionDuration(350)
					//.stacked(true)
					.reduceXTicks(false)   //If 'false', every single x-axis tick label will be rendered.
					.rotateLabels(0)      //Angle to rotate x-axis labels.
					.showControls(false)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
					.groupSpacing(0.1)    //Distance between each group of bars.
					;

			chart.xAxis
					//.tickFormat(d3.format('d'));
					.tickFormat(function(d) {
						return d+'月';
					});
			chart.yAxis
					//.tickFormat(d3.format('d'));
					.tickFormat(function (d) {
						return d+'件'; // If you want add '£' symbol
					});

			d3.select('#bookingmount svg')
					.attr('height', 250)
					.datum(monthlyBooking())
					.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
		function monthlyBooking() {
			return stream_layers(2,13).map(function(data, i) {
				var key, val , bgcolor;
				if(i == 0) {
					 key = '<?php echo e($monthbookingamount[0]->key); ?>年';
					 val = <?php echo json_encode($monthbookingamount[0]->values); ?> ;
					 bgcolor = '<?php echo e($monthbookingamount[0]->color); ?>';
				}
				if(i == 1) {
					key = '<?php echo e($monthbookingamount[1]->key); ?>年';
					val = <?php echo json_encode($monthbookingamount[1]->values); ?> ;
					bgcolor = '<?php echo e($monthbookingamount[1]->color); ?>';
				}
				return {
					key:   key,
					values: val,
					color: bgcolor
				};
			});
		}
		/*month booking number end*/
		/*month  portal booking number start*/
		nv.addGraph(function() {
			var chart = nv.models.multiBarChart()
					//.transitionDuration(350)
					.stacked(true)
					.reduceXTicks(false)   //If 'false', every single x-axis tick label will be rendered.
					.rotateLabels(0)      //Angle to rotate x-axis labels.
					.showControls(false)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
					.groupSpacing(0.1)    //Distance between each group of bars.
					;
//			chart.wrapLabels(true);
//			chart.reduceXTicks(true);
//			chart.showLegend(true);
//			chart.tooltips(true);
			chart.xAxis
					//.tickFormat(d3.format('d'));
					.tickFormat(function(d) {
						// return d+'月';
						return d;
					});
			chart.yAxis
					//.tickFormat(d3.format('d'));
					.tickFormat(function (d) {
						return d+'件'; // If you want add '£' symbol
					});
			chart.legend.margin({top: 5, right:0, left:0, bottom: 10});
			d3.select('#portalbookingmount svg')
					.attr('height', 360)
					.datum(portalBooking())
					.call(chart);

			nv.utils.windowResize(chart.update);

			return chart;
		});
		function portalBooking() {
			var list =  <?php echo json_encode($monthportalbooking); ?>;
			var streamcount = list.length;
			return stream_layers(streamcount,13).map(function(data, i) {
				var key, val , bgcolor;
				var one = list[i];
				key = one.key;
				val = one.values;
				bgcolor = one.color;
				return {
					key:   key,
					values: val,
					color: bgcolor
				};
			});
		}
		/*month portal booking number end*/
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminapp1', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>