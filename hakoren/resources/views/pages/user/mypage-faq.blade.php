@extends('layouts.frontend')
@section('template_linked_css')
    <link href="{{URL::to('/')}}/css/page_mypage.css" rel="stylesheet" type="text/css">
    <link href="{{URL::to('/')}}/css/faq.css" rel="stylesheet" type="text/css">
    {{--<link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">--}}
    {{--<link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">--}}

    <style>
        .btn-circle_active {
            left:15px;
            background:#13e313;
            width:10px;
            height:10px;
            position:absolute;
            margin-right:10px;
            border-radius: 5px;
        }
        .btn-circle_old {

            background:#fff;
            width:10px;
            position:absolute;
            height:10px;
            margin-right:10px;
            border-radius: 5px;
        }
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
                            <span>@lang('mypage.mypage')</span>
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
                            <h1>@lang('mypage.mypage')</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">

                        <div class="content-main col-lg-9 col-md-9 col-sm-9 col-xs-12 ">
                            <!-- mypage box -->
                            <div class="mypage-box">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php
                                         $mapage_top = $util->Tr('mapage-top');
                                        ?>
                                        <img class="img-responsive center-block m_T20" src="{{URL::to('/')}}/img/{{$mapage_top}}.png" alt="">
                                        {{--<h3>ようこそ、<b>{{ $user->last_name }}{{ $user->first_name }}</b>様</h3>--}}
                                    </div>
                                </div>

                                <div class="row stepbox2 mypage-faq">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <!-------------faq-01 ------------>
                                        <div class="panel-group m_B20 m_T20">
                                            <h3>@lang('mypage.aboutreservation')</h3>
                                            <div class="panel panel-default fr-faq">
                                                <div class="panel-heading fr-faq">
                                                    <h4 class="panel-title ">
                                                        <a data-toggle="collapse" href="#collapse1" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('mypage.bookingmethod')
</span></a>
                                                    </h4>
                                                </div>
                                            </div>

                                            <div id="collapse1" class="panel-collapse collapse in" aria-expanded="false">
                                                <div class="panel-body faq01">
                                                    <p>@lang('mypage.rentaltype')<br><br>

                                                        <span class="bold">1. @lang('mypage.webreservation')</span><br>@lang('mypage.quicklaunch')<br><br>

                                                        <span class="bold">2. @lang('mypage.reservationphone')</span><br>@lang('mypage.reservationontact')<br>
                                                        <a href="{{URL::to('/')}}/shop/fukuoka-airport-rentacar-shop">@lang('mypage.fuku')</a><br>
                                                        <a href="{{URL::to('/')}}/shop/naha-airport-rentacar-shop">@lang('mypage.okina')</a></p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-------------faq-03 ------------>
                                        <div class="panel-group m_B20">
                                            <div class="panel panel-default fr-faq">
                                                <div class="panel-heading fr-faq">
                                                    <h4 class="panel-title ">
                                                        <a data-toggle="collapse" href="#collapse3" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('mypage.nonuser')
</span></a>
                                                    </h4>
                                                </div>
                                            </div>

                                            <div id="collapse3" class="panel-collapse collapse " aria-expanded="false">
                                                <div class="panel-body faq01">
                                                    <p>@lang('mypage.nondrivers')<br>@lang('mypage.driverlicense')<br>@lang('mypage.process')</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-------------faq-04 ------------>
                                        <div class="panel-group m_B20">
                                            <div class="panel panel-default fr-faq">
                                                <div class="panel-heading fr-faq">
                                                    <h4 class="panel-title ">
                                                        <a data-toggle="collapse" href="#collapse4" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('mypage.whenreservation')</span></a>
                                                    </h4>
                                                </div>
                                            </div>

                                            <div id="collapse4" class="panel-collapse collapse " aria-expanded="false">
                                                <div class="panel-body faq01">
                                                    <p>@lang('mypage.fuku')<br>@lang('mypage.onehour')<br>@lang('mypage.depending')<br><br>
                                                        @lang('mypage.okina')<br>@lang('mypage.startdate')<br>@lang('mypage.note1')
                                                </div>
                                            </div>
                                        </div>

                                        <!-------------faq-06 ------------>
                                        <div class="panel-group m_B20">
                                            <div class="panel panel-default fr-faq">
                                                <div class="panel-heading fr-faq">
                                                    <h4 class="panel-title ">
                                                        <a data-toggle="collapse" href="#collapse6" class="collapsed" aria-expanded="false"><span class="icon-faq"> @lang('mypage.cancel') </span></a>
                                                    </h4>
                                                </div>
                                            </div>

                                            <div id="collapse6" class="panel-collapse collapse " aria-expanded="false">
                                                <div class="panel-body faq01">
                                                    <p>@lang('mypage.cancelreservation')<br>
                                                        <a href="{{URL::to('/')}}/shop/fukuoka-airport-rentacar-shop">@lang('mypage.fuku')</a><br>
                                                        <a href="{{URL::to('/')}}/shop/naha-airport-rentacar-shop">@lang('mypage.okina')</a><br><br>@lang('mypage.note15')
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-------------faq-08 ------------>
                                        <div class="panel-group m_B20">
                                            <div class="panel panel-default fr-faq">
                                                <div class="panel-heading fr-faq">
                                                    <h4 class="panel-title ">
                                                        <a data-toggle="collapse" href="#collapse8" class="collapsed" aria-expanded="false"><span class="icon-faq">@lang('mypage.cancelfee')</span></a>
                                                    </h4>
                                                </div>
                                            </div>

                                            <div id="collapse8" class="panel-collapse collapse " aria-expanded="false">
                                                <div class="panel-body faq01">
                                                    <p>@lang('mypage.cancelfee1')<br><br>
                                                        @lang('mypage.thatday') @lang('mypage.charge100')<br>
                                                        @lang('mypage.oneday')	@lang('mypage.charge80')<br>
                                                        @lang('mypage.fourday') @lang('mypage.charge50')<br>
                                                        @lang('mypage.nineday')	@lang('mypage.charge30')<br>
                                                        @lang('mypage.fifthday')@lang('mypage.charge20')<br>
                                                        @lang('mypage.specdate')	@lang('mypage.none')<br>
                                                        　</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- faq -->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PAGE CONTENT INNER -->
                        <div class="dynamic-sidebar col-lg-3 col-md-3 col-sm-3 col-xs-12">

                            <div class="mp-menu">
                                <h3>@lang('mypage.pagemenu')</h3>
                                <ul>
                                    <a href="{{URL::to('/mypage/top')}}"><li>@lang('mypage.bookinglist')</li></a>
                                    <a href="{{URL::to('/mypage/log')}}"><li>@lang('mypage.reservationhistory')</li></a>
                                    <a href="{{URL::to('/mypage/profile')}}"><li>@lang('mypage.memberinformation')</li></a>
                                    <a href="{{URL::to('/mypage/faq')}}"><li>@lang('mypage.faq')</li></a>
                                    <a href="{{URL::to('/mypage/changepassword')}}"><li>@lang('mypage.changepass')</li></a>
                                    <a href="{{URL::to('/logout')}}"><li>@lang('mypage.logout')</li></a>
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
