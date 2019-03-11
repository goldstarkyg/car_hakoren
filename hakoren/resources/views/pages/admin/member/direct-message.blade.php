@extends('layouts.adminapp1')

@section('template_title')
    簡易フォーム
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .datepicker{
            background: #ffffff;
        }
        .table-responsive {
            overflow-x:hidden !important;
        }
        .close_tab {
            float: right;
            font-size: 18px !important;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            filter: alpha(opacity=20);
            opacity: .2;
            background-color: #e41d1d !important;
        }
        a {
            outline: none !important;
        }
        .member {
            border:1px solid #0a568c;
            padding: 5px 5px 5px 5px;
            margin: 2px 0px 2px 0px;
            white-space: nowrap;
            border-radius: 7px;
        }
        .member:hover {
            background: #d8d9d9;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <link href="{{URL::to('/')}}/css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>Direct Message</h2>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-default shadow-box">
                <div class="panel-heading">
                    <div>
                        <form id="searchform" action="{{URL::to('/')}}/directmessage" method="post">
                            {!! csrf_field() !!}
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size:16px; color:#222;">
                                表示条件の設定
                                <a href="#" class="btn btn-info btn-xs" id="rerfresh" style="background-color:#909090;border-color:#909090">
                                    <i class="fa fa-refresh"></i>&nbsp;
                                </a>
                            </div>
                            <div class="row m-t-sm">
                                <div class="col-md-6">
                                    <div class="row" >
                                        <div class="col-md-4">
                                            Registered Date
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control daterange"  id="registerdate" name="registerdate"
                                                   value="{{ $registerdate }}" readonly onchange="clicksearch()" placeholder="日付を選択してください"
                                            >
                                        </div>
                                    </div>
                                    <div class="row m-t-sm">
                                        <div class="col-md-4">
                                            Last return Date
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control daterange" id="lastreturndate" name="lastreturndate"
                                                   value="{{$lastreturndate}}" readonly onchange="clicksearch()" placeholder="日付を選択してください"
                                            >
                                        </div>
                                    </div>
                                    <div class="row m-t-sm">
                                        <div class="col-md-4">
                                            Booking Numbers
                                        </div>
                                        <div class="col-md-8">
                                            <select name="booking_numbers" id="booking_numbers" class="form-control" onchange="clicksearch()" >
                                                <option value="">全体</option>
                                                <option value="1" @if($booking_numbers == '1') selected @endif >1</option>
                                                <option value="2" @if($booking_numbers == '2') selected @endif >2~5</option>
                                                <option value="6" @if($booking_numbers == '6') selected @endif >6~10</option>
                                                <option value="11" @if($booking_numbers == '11') selected @endif >11~20</option>
                                                <option value="21" @if($booking_numbers == '21') selected @endif >21~</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row m-t-sm">
                                        <div class="col-md-4">
                                            Spent Amount
                                        </div>
                                        <div class="col-md-8">
                                            <select name="spent_amount" id="spent_amount" class="form-control" onchange="clicksearch()" >
                                                <option value="">全体</option>
                                                <option value="40000" @if($spent_amount == '40000') selected @endif >~40000</option>
                                                <option value="40001" @if($spent_amount == '40001') selected @endif >40001~100000</option>
                                                <option value="100001" @if($spent_amount == '100001') selected @endif >100001~</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Tag
                                        </div>
                                        <div class="col-md-8">
                                            <select name="group_id" id="group_id" class="form-control" onchange="clicksearch()" >
                                                <option value="">全体</option>
                                                @foreach($groups as $gr)
                                                    <option value="{{$gr->id}}" @if($group_id == $gr->id) selected @endif >{{$gr->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row m-t-sm">
                                        <div class="col-md-4">
                                            Class
                                        </div>
                                        <div class="col-md-8">
                                            <select name="class_id" id="class_id" class="form-control" onchange="clicksearch()" >
                                                <option value="">全体</option>
                                                @foreach($class as $c)
                                                    <option value="{{$c->id}}" @if($class_id == $c->id) selected @endif >{{$c->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row m-t-sm">
                                        <div class="col-md-4">
                                            Shop
                                        </div>
                                        <div class="col-md-8">
                                            <select name="shop_id" id="shop_id" class="form-control" onchange="clicksearch()" >
                                                <option value="">全体</option>
                                                @foreach($shop as $s)
                                                    <option value="{{$s->id}}" @if($s->id == $shop_id) selected @endif >{{$s->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row m-t-sm">
                                        <div class="col-md-4">
                                            Prefecture
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="prefecture" id="prefecture" value="{{$prefecture}}" onkeypress="enterchange(event)" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--member list part start-->
                <div class="panel-body">
                    <p>条件に合う会員は以下、<span id="member_count"> </span>名になります。</p>
                    <p>ここに表示された全てのユーザーに対してDMを送信します。</p>
                </div>
                <div class="panel-body">
                    <div class="row">
                        @foreach($members as $member )
                            <div id="mem_{{$member->id}}" class="col-md-2" style="padding: 5px;" onclick="create_tab('{{$member->id}}','{{$member->last_name." ".$member->first_name}}')">
                                <div class="member">
                                    <span>{{str_pad($member->id, 6, '0', STR_PAD_LEFT)}} </span>
                                    {{--<span>{{$member->first_name.' '.$member->last_name}}</span>--}}
                                    <span>{{mb_substr($member->first_name.' '.$member->last_name,0,8)}}</span>
                                    <button type='button' class='close close_tab' aria-label='Close' onclick='remove_tab(event,{{$member->id}})'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!--member list part ent-->
                <!--email title-->
                <div class="panel-body">
                    <div class="form-group has-feedback row ">
                        <label for="subject" class="col-md-2 control-label">subject</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input id="subject" class="form-control"  name="subject" type="text" value="【ハコレン】">
                                <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback row ">
                        <label for="sender" class="col-md-2 control-label">Sender</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <input id="sender" class="form-control" name="sender" type="text" value="ハコレンタカー運営事務局">
                                <label class="input-group-addon" for="title"><i class="fa fa-fw fa-pencil }}" aria-hidden="true"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback row">
                        <label for="content" class="col-md-2 control-label">content</label>
                        <div class="col-md-10">
                            <input type="hidden" id="content" name="content" value=""/>
                            <div class="ibox-content n  o-padding">
                                <div class="summernote" style="display: none;">
                                    {user_name} 様<br><br>
                                    いつもハコレンタカーをお選びいただき、心より御礼を申し上げます。<br>
                                    何かご不明なことがございましたらハコレンタカーサポートセンターまでお知らせください。<br>
                                    お問い合わせフォーム <a href="https://www.hakoren.com/contact">https://www.hakoren.com/contact</a> <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-2 pull-right">
                            <button class="btn btn-primary btn-block margin-bottom-1 btn-save" type="button" data-toggle="modal"
                                    data-target="#confirmSubscribe" onclick="sendMessage()">
                                <i class="fa fa-fw fa-save" aria-hidden="true"></i>Send</button>
                        </div>
                    </div>
                </div>
                <!--email content-->
            </div>
        </div>
    </div>
    <!--modal-->
    <div id="completed_notifi" class="modal fade modal-warning" role="dialog" aria-hidden="true" style="padding-right: 17px;">
        <div class="modal-dialog" >
            <!-- Modal content-->
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Direct Message</h4>
                    </div>
                    <div class="modal-body">
                        <div style="padding-left: 20px;">
                            Successfully was send notification to members!
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button class="btn" type="button" data-dismiss="modal">Clost</button>
                    </div>
            </div>

        </div>
    </div>
    <!---->
    <!--display mmodal to make sure -->
    <div class="modal fade modal-warning" id="modalConfirm" role="dialog" aria-hidden="true" style="padding-right: 17px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Direct Message</h4>
                </div>
                <div class="modal-body">
                    <p class="error-text">Do you want to send mass message to members?</p>
                </div>
                <div class="modal-footer text-center">
                    <button class="btn" type="button" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" onclick="sendNotifi()">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!---->
@endsection

@section('footer_scripts')
    <style>
        .text-wrap{
            width: 100% !important;
        }
        .ibox-content {
            border:1px solid #CCCCCC;
        }
        /*button[data-event=codeview] {
            display:none !important;
        }*/
    </style>
    <script src="{{URL::to('/')}}/js/jquery.uploadPreview.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/summernote/summernote.min.js"></script>
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.core.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.tags.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.autocomplete.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.focus.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.prompt.css" type="text/css">
    <link rel="stylesheet" href="{{URL::to('/')}}/textext/css/textext.plugin.arrow.css" type="text/css">
    <script src="{{URL::to('/')}}/textext/js/textext.core.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.tags.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.autocomplete.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.suggestions.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.filter.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.focus.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.prompt.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.ajax.js" type="text/javascript" charset="utf-8"></script>
    <script src="{{URL::to('/')}}/textext/js/textext.plugin.arrow.js" type="text/javascript" charset="utf-8"></script>


    <link href="{{URL::to('/')}}/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <script src="{{URL::to('/')}}/js/jquery.dataTables2.js"></script>
    <script src="{{URL::to('/')}}/js/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.data-table').dataTable({
                "order": [[ 1, 'desc' ]],
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "pageLength" : 15,
                "serverSide": false,
                "ordering": false,
                "info": false,
                "autoWidth": true,
                "dom": 'T<"clear">lfrtip',
                "sPaginationType": "full_numbers",
            });
            $('.summernote').summernote();
            membercount();
        });
    </script>
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    <script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>
    <script src="{{URL::to('/')}}/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script>
        //create tab
        function create_tab(id, name){
            window.open('{!! url('/members/') !!}/'+id);
        }
        function clicksearch(){
            $('#searchform').submit();
        }
        function enterchange(e){
            if (e.which == 13) {
                clicksearch();
            }
        }
        $('.daterange').daterangepicker(
            {
                format: 'YYYY/MM/DD',
                "locale": {
                    "fromLabel": "から",
                    "toLabel": "に",
                    "daysOfWeek": [
                        "日",
                        "月",
                        "火",
                        "水",
                        "木",
                        "金",
                        "土"
                    ],
                    "monthNames": [
                        "1月",
                        "2月",
                        "3月",
                        "4月",
                        "5月",
                        "6月",
                        "7月",
                        "8月",
                        "9月",
                        "10月",
                        "11月",
                        "12月"
                    ],
                }
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
            });
        $('#rerfresh').click(function() {
            $('#registerdate').val("");
            $('#lastreturndate').val("");
            $('#booking_numbers').val("");
            $('#spent_amount').val("");
            $('#group_id').val("");
            $('#spent_amount').val("");
            $('#class_id').val("");
            $('#shop_id').val("");
            $('#prefecture').val("");
            $('#searchform').submit();
        });
        var member_ids = [];
        var members = <?php echo json_encode($members) ;?>;
        function membercount(){
            for ( var i = 0 ; i < members.length; i++  ) {
                member_ids.push(members[i].id);
            }
           $('#member_count').text(member_ids.length);
        }
        //remove tab
        function remove_tab(e,id){
            e.stopPropagation();
            var tab = $( "#mem_"+id ).remove();
            member_ids = jQuery.grep(member_ids, function(value) {
                return value != id;
            });
            $('#member_count').text(member_ids.length);
        }
        //send notification
        function sendMessage(){
            $('#modalConfirm').modal('show');
        }
        function sendNotifi(){
            $('#modalConfirm').modal('hide');
            var url         = '{{URL::to('/')}}/directmessage/sendmessage';
            var token = '{{ csrf_token() }}';
            var data = [];
            var data        = [];
            var subject  = $('#subject').val();
            var sender  = $('#sender').val();
            var text = $(".summernote").code();

            var content = text;
            data.push(
                {name: '_token',   value: token},
                {name: 'subject',  value: subject},
                {name: 'sender',  value: sender},
                {name: 'content',  value: content},
                {name: 'members',  value: member_ids});
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
                    if(content.code == '200' ) {
                        $('#completed_notifi').modal('show');
                    }
                }
            });
        }
    </script>
@endsection