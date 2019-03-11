@extends('layouts.adminapp')

@section('template_title')
    新しい予約追加
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/navtab.css" rel="stylesheet">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }
        .tab-font{
            font-size: 14px !important;
        }
        form .active{
            color: #464545 !important;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>新しい予約の追加</h2>
                <div style="position: absolute; margin-top: -2.5em;right: 20px; display: none;" >
                    <a href="{{URL::to('/')}}/members/create" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-user" aria-hidden="true"></i>
                        作成する
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <form action="{{URL::to('/')}}/booking/create" method="post" class="form-horizontal">
                {!! csrf_field() !!}
                <input type="hidden" name="admin_id" value="{{ $admin->id }}" required>
                <input type="hidden" name="user_id" value="{{ $user->id }}" required>
                <!---->
                <div class="panel with-nav-tabs panel-default shadow-box">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li @if(session('tag') == 'class' || !session('tag')) class="active" @endif >
                                <a class="tab-font" data-toggle="tab" href="#rental" >レンタル内容</a></li>
                            <li @if(session('tag') == 'normal') class="active" @endif >
                                <a class="tab-font" data-toggle="tab" href="#customer">顧客情報</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="rental" class="tab-pane fade @if(session('tag') == 'rental' || !session('tag')) in active @endif ">
                                @include('pages.admin.booking.add-manual-rental')
                            </div>

                            <div id="customer" class="tab-pane fade @if(session('tag') == 'customer') in active @endif">
                                @include('pages.admin.booking.add-manual-customer')
                                <div class="col-lg-12 text-center" style="margin-top:15px">
                                    <button  class="btn btn-primary">予約を保存</button>
                                    <a href="{{URL::to('/')}}/booking/all" class="btn btn-primary">予約リストに戻る</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('modals.modal-delete')

@endsection
@section('footer_scripts')
    <style>
        .col-lg-12 { padding-bottom: 7px;}
        .datepicker {
            background: #fff;
            border: 1px solid #555;
        }
        .chosen-container-multi .chosen-choices{
            border: 1px solid #dedede;
            background-image:none;
        }
    </style>

    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script>
        function getTodayString(){
            var today = new Date(),
                mm = today.getMonth() + 1,
                dd = today.getDate(),
                yyyy = today.getFullYear();
            if(mm < 10) mm = '0' + mm;
            if(dd < 10) dd = '0' + dd;
            return yyyy + '/' + mm + '/' + dd;
        }

        //caluclate day from packing day to return day .
        function calculateRentDates() {
            var departDate = $('#depart-date').val(),
                returnDate = $('#return-date').val(),
                departTime = $('#depart-time').val(),
                returnTime = $('#return-time').val();
            if(departDate === '' || returnDate === '')
                return null;

            var start   = new Date(departDate+" " +departTime);
            var end     = new Date(returnDate+" "+returnTime);
            var diff    = new Date(end - start);
            var days    = diff/1000/60/60/24;
                days    = Math.round(days*10)/10;
            return days;
        }

        // calculate day from packing to return day
        function showRentDays() {
            var rentDate    = calculateRentDates();
            var night   = Math.floor(rentDate)
            var day = rentDate - night;
            if(rentDate != null && rentDate > 0 ) {
                if(day >= 0.5) day =  night + 1;
                else day =  night;
                $('#total_rent_days').val(night + '泊' + day + '日');
                $('#rentdays_val').val(night+"_"+day);
            }
            getPrice();
        }

        function isNumeric(num){
            return !isNaN(num)
        }

        function calcTaxPay(){
            var subtotal = $('#subtotal').val();
            if(!isNumeric(subtotal)){
                $('#subtotal').val(0);
                return;
            }
            var discount = $('#discount').val();
            var insurance_price= $('#insurance_price').val();
            if(!isNumeric(discount)){
                $('#discount').val(0);
                return;
            }
            if(subtotal * 1 < discount * 1){
                $('#subtotal').val(0);
                $('#discount').val(0);
                return;
            }
            var tax = Math.ceil((subtotal - discount)*0.08);
            $('#tax').val(tax);
            $('#total_pay').val(subtotal + insurance_price - discount + tax);
        }

        $('#discount').keyup(function(){
            calcTaxPay();
        });

        var today = getTodayString();
        var datepicker_setting = {
            locale : 'ja',
            format: 'yyyy/mm/dd',
            startDate: today,
            orientation: "bottom",
            todayHighlight: true,
            daysOfWeekHighlighted: "0,6",
            autoclose: true
        };
        var departDate, returnDate;
        $('#depart-datepicker').datepicker(datepicker_setting)
            .on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            departDate = minDate;
            $('#return-datepicker').datepicker('setStartDate', minDate);
            showRentDays();
        });

        $('#return-datepicker').datepicker(datepicker_setting)
            .on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            returnDate = maxDate;
            $('#depart-datepicker').datepicker('setEndDate', maxDate);
            showRentDays();
        });

        $('#depart-time').change(function(){
            showRentDays();
        });
        $('#return-time').change(function(){
            showRentDays();
        });

        /*search and select*/
        $(".chosen-select").chosen({
            max_selected_options: 5,
            no_results_text: "Oops, nothing found!",
        });

        //get options list from car model of car inventory
        var options = [];
        $('#inventory_id').on('change',function() {
            var id = this.value;
            var url = '{{URL::to('/')}}/booking/getoptionsfrommodel';
            var token = $('input[name="_token"]').val();
            var data = [];
            data.push({name: 'inventory_id', value: id}, {name: '_token', value: token});
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "json",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    options= content;
                    $('form select[name="free_options"]').html("");
                    $('form select[name="paid_options"]').html("");
                    $.each(content, function(k,v){
                        $('form select[name="free_options"]').prepend("<option value='"+v.id+"'>"+ v.name+"  "+ v.price+"  "+ v.charge_system+"  </option>");
                        $('form select[name="paid_options"]').prepend("<option value='"+v.id+"'>"+ v.name+"  "+ v.price+"  "+ v.charge_system+"  </option>");
                    });
                    $('form select[name="free_options"]').trigger("chosen:updated");
                    $('form select[name="paid_options"]').trigger("chosen:updated");
                }
            });
        });

        //calculator option price for paid option
        $("#paid_options").chosen().change(function(e, params){
            var values = $("#paid_options").chosen().val();
            var prices = 0;
            var option_price = "";
            for(var i = 0; i < values.length ; i++) {
                var option_id = values[i] ;
                $.each(options, function (k, v) {
                    if(option_id == v.id) {
                        prices += v.price;
                        option_price += v.price+",";
                    }
                });
            }
            $("#option_price").val(prices);
            $("#paid_options_price").val(option_price);
        });

        //get price
        function getPrice() {
            var start_date     = $('#depart-date').val();
            var end_date       = $('#return-date').val();
            var inventory_id   = $('form select[name="inventory_id"]').val();
            var selected_day   = $('#rentdays_val').val();
            var url = '{{URL::to('/')}}/booking/getprice';
            var token = $('input[name="_token"]').val();
            var data = [];
            data.push(  {name: 'start_date', value: start_date},
                        {name: '_token', value: token},
                        {name: 'end_date', value: end_date},
                        {name: 'inventory_id', value: inventory_id},
                        {name: 'selected_day', value: selected_day});
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "text",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    $('form input[name="car_price"]').val(content);
                    subtotal();
                    calcTaxPay();
                }
            });
        };
        //get insurance price
        $("#insurance").chosen().change(function(e, params){
            getInsurancePrice();
        });
        function getInsurancePrice() {
            var depart_date     = $('#depart-date').val();
            var depart_time     = $('#depart-time').val();
            var return_date     = $('#return-date').val();
            var return_time     = $('#return-time').val();
            var inventory_id    = $('form select[name="inventory_id"]').val();
            var insurance       = $('#insurance').val();
            var url = '{{URL::to('/')}}/booking/getinsuranceprice';
            var token = $('input[name="_token"]').val();
            var data = [];
            data.push(  {name: 'depart_date', value: depart_date},
                    {name: 'depart_time', value: depart_time},
                    {name: '_token', value: token},
                    {name: 'return_date', value: return_date},
                    {name: 'return_time', value: return_time},
                    {name: 'insurance', value: insurance},
                    {name: 'inventory_id', value: inventory_id});
            data = jQuery.param(data);
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                async: false,
                dataType: "text",
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                success: function (content) {
                    $('form input[name="insurance_price"]').val(content);
                }
            });
        };
        //calculator subtotal
        function subtotal(){
            var carprice =  $('form input[name="car_price"]').val();
            var caroption =  $("#option_price").val();
            var price = parseInt(carprice) + parseInt(caroption);
            $('#subtotal').val(price);
        }
    </script>
    @include('scripts.admin.member')
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
@endsection