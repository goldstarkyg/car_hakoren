@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_mypage.css" rel="stylesheet" type="text/css">
    {{--<link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">--}}
    {{--<link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">--}}
    <style>
    </style>
@endsection
@inject('util', 'App\Http\DataUtil\ServerPath')
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
                            <a href="{!! url('/mypage/top') !!}">@lang('mypaage.mypage')</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span>@lang('mypage.reservationhistory')</span>
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
								<h1>@lang('mypage.reservationhistory')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">
                        
						<div class="content-main col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<!-- caution 2 -->
							<div class="caution-block box-shadow p-block bg-darkred">
								<h3 class="bg-grad-gray">@lang('mypage.reservationhistory')</h3>
								<div class="clearfix bg-white">			
									<div class="col-lg-12 col-md-12 col-sm-12 co-xs-12">
										<p>@lang('mypage.historydetail')</p>
										<table class="table table-bordered">
											<tr>
												<th>@lang('mypage.dateofuse')</th>
												<th>@lang('mypage.period')</th>
												<th>@lang('mypage.carclass')</th>
												<th>@lang('mypage.price')</th>
												<th>@lang('mypage.detail')</th>
											</tr>
											@foreach( $books as $key => $book)
											<tr>
                                                @if($util->lang() == 'ja')
												<td>{{ date('Y年n月j日', strtotime($book->departing)) }}</td>
                                                @endif
                                                @if($util->lang() == 'en')
                                                    <td>{{ date('Y/n/j', strtotime($book->departing)) }}</td>
                                                @endif
												<td>{{ $book->rentdate_str }}</td>
												<td>{{ $book->class_name }}</td>
												<td>{{ $book->payment }}@lang('mypage.yen')</td>
												<td><a href="" class="btn btn-link" data-toggle="modal" data-target="#Modal_{{$key}}">@lang('mypage.detail')</a></td>
												
                                                <!-- Modal -->
												<div class="modal fade" id="Modal_{{$key}}" tabindex="-1">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">@lang('mypage.usagehistory')</h5>
																<button type="button" class="close" data-dismiss="modal">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<p class="yoyaku_ttl">@lang('mypage.detailuse')</p>
																<ul>
																	<li>@lang('mypage.departure')： {{$book->depart_datetime}}　{{$book->depart_shop}}</li>
																	<li>@lang('mypage.return')： {{$book->return_datetime}} {{$book->return_shop}}</li>
																	<li>@lang('mypage.period')： {{ ($book->rentdate_str=='0泊1日')?'日帰り':$book->rentdate_str }} {{number_format($book->subtotal)}}円</li>
																	<li>@lang('mypage.carclass')： {{$book->class_name}}({{$book->model_names}})、{{$book->smoke}} </li>
																	<li>@lang('mypage.compensation')： {{number_format($book->insurance1)}}円 + ワ{{$book->insurance2}}円</li>
																	<li>@lang('mypage.option')：
																	<?php 
																		$cc = 0; 
																		$opt_num = explode(',',$book->paid_option_numbers);
																		
																		$opt_names = $book->paid_option_names;
																		
																		$opt_prices = explode(',',$book->paid_options_price);
																		
																		//dd($books[3]->paid_option_names);
																	?>
																	
																	@foreach($opt_names as $opt)
																		@if(isset($opt_num[$cc]))
																			@if($opt_num[$cc] != 0)
																			{{ $opt }}({{$opt_prices[$cc]}}円)
																				
																				@if($cc != (count($opt_names)-1))
																					<span class="con">,</span>
																				@endif
																			@endif
																		@endif
																		<?php $cc++; ?>
																	@endforeach
																	</li>
																	
																	<li>@lang('mypage.pickup')： @if(!empty($book->free_options)) 利用 @else 利用なし @endif</li>
																	<li>@lang('mypage.total')： {{number_format($book->payment)}}円</li>
																</ul>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</div>
												<!-- Modal -->
                                                
											</tr>
											@endforeach
										</table>
									</div>

									<div class="col-xs-12">
                                        <p class="text-center">
                                            @if($books->currentPage()==1)
                                            <a href="javascript:void(0);" disabled> @lang('mypage.forward')</a>
                                            @else
                                            <a href="{{$books->previousPageUrl()}}">  @lang('mypage.forward')</a>
                                            @endif
                                            <span style="border: 1px solid #b0c3ed;background: #e7f6ff;padding: 2px 10px;border-radius: 3px !important;margin: 0 40px;">{{ $books->currentPage() }}@lang('mypage.page')</span>
                                            @if($books->hasMorePages())
                                                <a href="{{$books->nextPageUrl()}}">@lang('mypage.next')</a>
                                            @else
                                                <a href="javascript:void(0);" disabled>@lang('mypage.next')</a>
                                            @endif
                                        </p>
                                        <p class="text-center">@lang('mypage.all')&nbsp;{{$books->total}} &nbsp;@lang('mypage.matterin')&nbsp;{{$books->start}}〜{{$books->end}}@lang('mypage.showsubject')</p>
                                    </div>
								</div>

							</div>
							<!-- caution 2 -->					
															
							
                        </div>
                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 col-sm-3 col-xs-12">
								
							<div class="mp-menu">
								<h3>@lang('mypage.pagemenu')</h3>
								<ul>
									<a href="{{URL::to('/')}}/mypage/top"><li>@lang('mypage.bookinglist')</li></a>
									<a href="{{URL::to('/')}}/mypage/log"><li>@lang('mypage.reservationhistory')</li></a>
									<a href="{{URL::to('/')}}/mypage/profile"><li>@lang('mypage.memberinformation')</li></a>
                                    <a href="{{URL::to('/mypage/faq')}}"><li>@lang('mypage.faq')</li></a>
                                    <a href="{{URL::to('/mypage/changepassword')}}"><li>@lang('mypage.changepass')</li></a>
									<a href="{{URL::to('/')}}/logout"><li>@lang('mypage.logout')</li></a>
								</ul>
							</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link">@lang('mypage.toppage')</a>
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