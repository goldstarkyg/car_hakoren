@extends('layouts.frontend')
@section('template_title')
車種・料金
@endsection
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_carlist.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>

@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
@section('content')
    @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
    <script>
        dataLayer.push({
            'ecommerce': {
                'currencyCode': 'JPY',
                'impressions': [
                        @foreach($classes as $key=>$cls)
                    {
                        'name': '{{ $cls->abbriviation }}',
                        'id': '{{ $cls->id }}',
                        'price': '{{ $cls->price1_0 }}',
{{--                        'brand': '{{ $cls->abbriviation }}',--}}
                        'list': 'Carclass {{ $shop->region_code }}',
                        'position': '{{ $key + 1 }}'
                    },
                    @endforeach
                ]
            }
        });
    </script>
    @endif

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
                            <a href="#">親ページ</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span> @lang('classlist.classlist') </span>
                        </li>
                    </ul>
                </div>
                <!-- END PAGE TITLE -->
            </div>
        </div>
        <!-- END PAGE HEAD-->

        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT HEADER -->
            <div class="dynamic-page-header dynamic-page-header-default">
                <div class="container clearfix">
                    <div class="col-md-12 bottom-border ">
                        <div class="page-header-title">
                            <h1>@if(!empty($class->csname)) {{ $class->csname }} @endif @lang('classlist.carfee')</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BEGIN PAGE CONTENT BODY -->

            <div class="page-content">
                <div class="container">

                    <!-- ROW -->
                    <div class="row">
                        <div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php
                                $car_class_names = \DB::table('car_shop')->select('id')->where('slug',Request::segment(2))->first();
                                if(!empty($car_class_names))
                                {
                                    $shop_id = $car_class_names->id;
                                }
                                else
                                {
                                    $shop_id = "";
                                }

                            ?>
                            <!-- CarClass 1 -->

                            @if(!empty($shop_id) || $shop_id != 0)
                                    @foreach($classes as $key => $class)
                                        @if($shop_id == $class->cs_id)
                                            <div class="box-shadow relative">
                                                <div class="ribbon-block bg-grad-red">
                                                    <h3>{{$class->name}}　</h3><!--@if($class->smoke == '0')禁煙車 @endif-->
                                                </div>
                                                <!-- ROW -->
                                                <div class="row margin-bottom-40 carclass-margin">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <?php
                                                        $class_thumb = (count($class->models) > 0)? $class->models[0]->thumb_path : 'images/blank.jpg';
                                                        ?>
                                                        <img src="{{URL::to('/')}}/{{$class_thumb}}" class="img-responsive center-block">
                                                        <p class="sml-txt"> @lang('classlist.modelimage') </p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <?php
                                                        $min_psg = $class->passenger->min_passenger;
                                                        $max_psg = $class->passenger->max_passenger;
                                                        $psg_num = ($min_psg != $max_psg)? $min_psg.'～'.$max_psg : $min_psg;
                                                        if($class->name == 'CW3H' || $class->name == 'W3' )
                                                            $psg_num = $min_psg;
                                                        ?>
                                                        <h3 class="jnum"> @lang('classlist.passenger') ：
                                                            <span> {{ $psg_num }}</span> @lang('classlist.name')
                                                        </h3>

                                                        <table class="table carclass-tbl">
                                                            <tr>
                                                                <th width="30%">
                                                                    <div class="titlebox">
                                                                     @lang('classlist.modelname')
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <?php
                                                                        $m = 0;
                                                                        $name = $util->Tr('name');
                                                                    ?>
                                                                    @foreach($class->models as $model)
                                                                        {{$model->$name}} @if(count($class->models) > $m+1)、@endif
                                                                       <?php $m++; ?>
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <div class="titlebox">
                                                                        @lang('classlist.option')
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <?php $op = 0; ?>
                                                                    @foreach($class->options as $option)
                                                                        {{$option->$name}} @if(count($class->options) > $op+1)、@endif
                                                                       <?php $op++; ?>
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <div class="titlebox">
                                                                        @lang('classlist.onedayprice') <br/><span style="font-size:12px;">（@lang('classlist.1n2dtime')）</span>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    {{number_format($class->price1_0)}} @lang('classlist.yen')/1 @lang('classlist.day')(@lang('classlist.tax')<br>
                                                                    ({{number_format($class->price2_1)}}@lang('classlist.yen') @lang('classlist.1n2d'))
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <div class="clearfix bg-white calc-btn">
                                                            <a class="bg-grad-red" onclick="gotoDetail('{{$class->name}}', {{$class->id}}, {{$class->price1_0}},{{$key + 1}})"> @lang('classlist.viewdetail')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ROW -->
                                            </div>
                                        @endif
                                    @endforeach
                                   @if($shop_id == 4)
									<div class="box-shadow relative">
										<div class="ribbon-block bg-grad-red">
											<h3>MB</h3> {{--@if($class->smoke == '0') 禁煙車--}}
										</div>
										<!-- ROW -->
										<div class="row margin-bottom-40 carclass-margin">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<img src="{{URL::to('/')}}/img/mb.png" class="img-responsive center-block m_Txs60">
												<p class="sml-txt"> @lang('classlist.modelimage')</p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<h3 class="jnum"> @lang('classlist.passenger')： <span>26～29</span> @lang('classlist.name') </h3>

												<table class="table carclass-tbl">
													<tr>
														<th width="30%"><div class="titlebox"> @lang('classlist.modelname')</div></th>
														<td>
															@lang('classlist.coaster')
														</td>
													</tr>
													<tr>
														<th><div class="titlebox"> @lang('classlist.option')</div></th>
														<td>
															@lang('classlist.optionlist')
														</td>
													</tr>
													<tr>
														<th>
															<div class="titlebox">
																@lang('classlist.price')
															</div>
														</th>
														<td>
															21,900 @lang('classlist.yen')/1 @lang('classlist.day')(@lang('classlist.tax')
	(43,800 @lang('classlist.yen') @lang('classlist.1n2d')<br/><br/> @lang('classlist.rental') <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> @lang('classlist.inquire')
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													<a class=" bg-grad-red" href="{{URL::to('/')}}/contact"> @lang('classlist.inqu') </a>
												</div>
											</div>
										</div>
										<!-- ROW -->
									</div>

									<div class="box-shadow relative">
										<div class="ribbon-block bg-grad-red">
											<h3> @lang('classlist.carlexus')</h3><!--@if($class->smoke == '0') 禁煙車 @endif-->
										</div>
										<!-- ROW -->
										<div class="row margin-bottom-40 carclass-margin">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<img src="{{URL::to('/')}}/img/ssp2.jpg" class="img-responsive center-block m_Txs60">
												<p class="sml-txt"> @lang('classlist.modelimage') </p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<h3 class="jnum"> @lang('classlist.passenger')： <span>5</span> @lang('classlist.name') </h3>

												<table class="table carclass-tbl">
													<tr>
														<th width="30%"><div class="titlebox"> @lang('classlist.modelname') </div></th>
														<td>
															@lang('classlist.carlexus')
														</td>
													</tr>
													<tr>
														<th>
                                                                    <div class="titlebox"> @lang('classlist.option')</div></th>
														<td>

														</td>
													</tr>
													<tr>
														<th>
															<div class="titlebox">
																@lang('classlist.price')
															</div>
														</th>
														<td>
															@lang('classlist.lexusdesc1') <br/> @lang('classlist.rental') <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> @lang('classlist.inquire')
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													<a class=" bg-grad-red" href="{{URL::to('/')}}/contact"> @lang('classlist.inqu')</a>
												</div>
											</div>
										</div>
										<!-- ROW -->
									</div>

									<div class="box-shadow relative">
										<div class="ribbon-block bg-grad-red">
											<h3> @lang('classlist.carlexus1') </h3><!--@if($class->smoke == '0') 禁煙車 @endif-->
										</div>
										<!-- ROW -->
										<div class="row margin-bottom-40 carclass-margin">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<img src="{{URL::to('/')}}/img/ssp3.jpg" class="img-responsive center-block m_Txs60">
												<p class="sml-txt"> @lang('classlist.modelimage')</p>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<h3 class="jnum"> @lang('classlist.passenger')： <span>5</span> @lang('classlist.name')</h3>

												<table class="table carclass-tbl">
													<tr>
														<th width="30%"><div class="titlebox"> @lang('classlist.modelname')</div></th>
														<td>
															@lang('classlist.carlexus1')
														</td>
													</tr>
													<tr>
														<th>
                                                                    <div class="titlebox"> @lang('classlist.option') </div></th>
														<td>

														</td>
													</tr>
													<tr>
														<th>
															<div class="titlebox">
																@lang('classlist.price')
															</div>
														</th>
														<td>
															@lang('classlist.lexusdesc1') <br/> @lang('classlist.rental') <a href="tel:092-260-9506" style="color: inherit">092-260-9506</a> @lang('classlist.inquire')
														</td>
													</tr>
												</table>
												<div class="clearfix bg-white calc-btn">
													<a class=" bg-grad-red" href="{{URL::to('/')}}/contact"> @lang('classlist.inqu')    </a>
												</div>
											</div>
										</div>
										<!-- ROW -->
									</div>
								   @endif
                            <!-- CarClass 1-->

                        </div>
                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 hidden-lg hidden-md hidden-sm hidden-xs">
                            <div class="portlet portlet-fit light cont-box">
                                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 margin-bottom-10">
                                    <a href="#"><img class="center-block img-responsive" src="" alt=""></a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- ROW -->
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"> @lang('classlist.toppage') </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="box-shadow relative text-center">
                    @lang('classlist.notfound')
                </div>
                @endif
            </div>
            <!-- END PAGE CONTENT BODY -->

            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->

    </div>
    <!-- END CONTAINER -->
 @endsection

 @section('footer_scripts')
<script>

    function gotoDetail(class_name, class_id, class_price, position){
        @if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false)
            dataLayer.push({
                'event': 'productClick',
                'ecommerce': {
                    'click': {
                        'actionField': {'list': 'Carclass {{ $shop->region_code }}'},
                        'products': [{
                            'name': class_name,                      // Dynamic value
                            'id': class_id,					// Dynamic value
                            'price': class_price,
                            // 'brand': productObj.brand,
                            'position': position
                        }]
                    }
                }
            });
        @endif
        location.href = "{{URL::to('/')}}/carclass-detail/" + class_id;
    }
</script>

 @endsection
