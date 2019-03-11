@extends('layouts.adminapp')

@section('template_title')
    予約の編集
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link href="{{URL::to('/')}}/css/plugins/chosen/chosen.css" rel="stylesheet">
    <style type="text/css" media="screen">
        .left_back{
            background-color: #dfeaff;
        }
        table.table-bordered{
            border:1px solid #a7a7a7;
            margin-top:20px;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #a7a7a7;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid #a7a7a7;
        }
        .title{
            margin-left: 0px;;
        }
    </style>
@endsection

@section('content')
    <link id="bsdp-css" href="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <?php
    //\App\Http\Controllers\EndUsersManagementController::calculateData();
    ?>
    <div>
        <div class="row">
            <div class="panel panel-default">
                <h2>予約の編集
                    <a href="{{URL::to('/')}}/booking/detail/{{$book->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        詳細を見る
                    </a>
                    <a href="{{URL::to('/')}}/booking/all" class="btn btn-info btn-xs pull-right">
                        <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                        リストを見る
                    </a>
                </h2>
            </div>
        </div>
        <!--eidt page start-->
        <div class="row">
            <form action="{{URL::to('/')}}/booking/update" method="post" class="form-horizontal">
                {!! csrf_field() !!}
                <input type="hidden" name="book_id" value="{{$book->id}}" required>
                <input type="hidden" name="admin_id" value="{{ $book->admin_id }}" required>
                <input type="hidden" name="user_id" value="{{ $book->client_id }}" required>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        <div style="border-bottom: 1px solid #000">
                            <label>
                                <h3 class="title">{{$book->last_name}} {{$book->first_name}} さんのご予約状況</h3>
                            </label>
                            <label class="pull-right">
                                @if($book->portal_flag == '0')
                                    <span>自社HP</span>
                                @elseif( $book->portal_flag == '1')
                                    <span>{{$book->portal_name}}</span>
                                    <span>{{$book->booking}}</span>
                                @endif
                                <input type="hidden" name="booking" value="">
                                <input type="hidden" name="portal_id" value="{{$book->portal_id}}">
                            </label>
                        </div>
                        <div>
                            <!--start first part-->
                            <table class="table table-bordered users-table">
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="status" >Status</label>
                                    </td>
                                    <td>
                                        <label style="padding-right: 20px;">{{$book->status_name}} </label>
                                        <label>
                                            <select class="form-control">
                                                <option value="0">Select below</option>
                                                <option value="9">キャンセル</option> <!--cancel-->
                                                <option value="8">End</option> <!--ended-->
                                                <option value="10">Ignore</option><!--ignore-->
                                                <option value="7">Delay</option><!--delay-->
                                            </select>
                                        </label>
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label">Booking Id</label>
                                    </td>
                                    <td>
                                        <label class="control-label">{{$book->booking_id}}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="left_back">
                                        <label class="control-label" for="last_name" >Last Name</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="last_name" id="last_name" class="form-control" value="{{$book->last_name}}">
                                        </label>
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label" for="first_name" >First Name</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{$book->first_name}}">
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back" >
                                        <label class="control-label" for="phone" >Phone</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="phone" id="phone" class="form-control" value="{{$book->phone}}">
                                        </label>
                                    </td>
                                    <td class="left_back" >
                                        <label class="control-label" for="email" >Email</label>
                                    </td>
                                    <td >
                                        <label>
                                            <input type="text" name="email" id="email" class="form-control" value="{{$book->email}}">
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="postal_code" >Postal Code</label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{$book->postal_code}}">
                                        </label>
                                    </td>
                                    <td class="left_back" >
                                        <label class="control-label" for="prefecture" >Prefecture</label>
                                    </td>
                                    <td>
                                        <label>
                                            <input type="text" name="prefecture" id="prefecture" class="form-control" value="{{$book->prefecture}}">
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="address" >Address1</label>
                                    </td>
                                    <td colspan="3">
                                        <input type="text" name="address1" id="address1" class="form-control" value="{{$book->address1}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="address" >Address2</label>
                                    </td>
                                    <td colspan="3">
                                        <input type="text" name="address2" id="address2" class="form-control" value="{{$book->address2}}">
                                    </td>
                                </tr>
                            </table>
                            <!--end first part-->
                        </div>
                        <div>
                            <!--start second part-->
                            <table class="table table-bordered users-table">
                                <tr>
                                    <td class="left_back" >
                                        <label class="control-label" for="class" >車両番号</label>
                                    </td>
                                    <td colspan="3" >
                                        <select id="inventory_id" name="inventory_id" class="chosen-select form-control" >
                                            <option value="0"> Select below </option>
                                            @foreach( $cars as $car )
                                                <option value="{{ $car->car_id }}_{{ $car->class_id }}_{{ $car->model_id }}_{{ $car->type_id}}" @if($book->inventory_id == $car->car_id) selected @endif>
                                                    Class:{{$car->class_name }} &nbsp;
                                                    Model:{{$car->model_name}} &nbsp;
                                                    Type:{{$car->type_name}} &nbsp;
                                                    Number: {{$car->numberplate1}} {{$car->numberplate2}} {{$car->numberplate3}} {{$car->numberplate4}} {{$car->smoke}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="left_back" >
                                        <label class="control-label">Departure Shop</label>
                                    </td>
                                    <td >
                                        <select id="pickup_id" name="pickup_id" class="chosen-select form-control" required>
                                            <option value="0" >Select below </option>
                                            @foreach( $shops as $shop )
                                                <option value="{{ $shop->id }}" @if($shop->id == $book->pickup_id) selected @endif >{{ $shop->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="left_back" >
                                        <label class="control-label">Retunring Shop</label>
                                    </td>
                                    <td >
                                        <select id="dropoff_id" name="dropoff_id" class="chosen-select form-control" >
                                            <option value="0" >Select below </option>
                                            @foreach( $shops as $shop )
                                                <option value="{{ $shop->id }}" @if($shop->id == $book->dropoff_id) selected @endif>{{ $shop->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td  class="left_back" >
                                        <label class="control-label">Departure Date</label>
                                    </td>
                                    <td >
                                        <div class="input-group date col-lg-7 pull-left"  id="depart-datepicker">
                                            <input type="text" name="depart_date" id="depart-date" class="form-control" readonly required>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                        <div class="col-lg-5" style="padding-right: 0">
                                            <select class="chosen-select form-control" name="depart_time" id="depart-time" required>
                                                @foreach($hour as $h)
                                                    <option value="{{$h}}" @if($h==$book->depart_time) selected @endif >{{$h}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td class="left_back" >
                                        <label class="control-label">Retunring Date</label>
                                    </td>
                                    <td >
                                        <div class="input-group date col-lg-7 pull-left" id="return-datepicker">
                                            <input type="text" name="return_date" id="return-date" class="form-control" readonly required>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                        <div class="col-lg-5" style="padding-right: 0">
                                            <select class="chosen-select form-control" name="return_time" id="return-time" required>
                                                @foreach($hour as $h)
                                                    <option value="{{$h}}" @if($h==$book->return_time) selected @endif>{{$h}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="insurance" >Insurance</label>
                                    </td>
                                    <td colspan="3" >
                                        insurance1  insurance2
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="insurance" >Pickup Option</label>
                                    </td>
                                    <td colspan="3" >
                                        pick up1
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" for="insurance" >Paid Option</label>
                                    </td>
                                    <td colspan="3" >
                                        <input type="text" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" >Total amount</label>
                                    </td>
                                    <td >
                                        1000
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label" for="insurance" >Paymetn Statua</label>
                                    </td>
                                    <td>
                                        1000
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" >Driver Number</label>
                                    </td>
                                    <td>
                                        123456
                                    </td>
                                    <td class="left_back">
                                        <label class="control-label">Flight Information</label>
                                    </td>
                                    <td>
                                        1000
                                    </td>
                                </tr>
                                <tr>
                                    <td class="left_back">
                                        <label class="control-label" >Staff Note</label>
                                    </td>
                                    <td colspan="3">
                                        <input type="text" name="" id="" class="form-control">
                                    </td>
                                </tr>
                            </table>
                            <!--end second part-->
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--edit page end-->
        <div class="row">
            <form action="{{URL::to('/')}}/booking/update" method="post" class="form-horizontal">
                {!! csrf_field() !!}
                <input type="hidden" name="book_id" value="{{$book->id}}" required>
                <input type="hidden" name="admin_id" value="{{ $book->admin_id }}" required>
                <input type="hidden" name="user_id" value="{{ $book->client_id }}" required>
                <div class="panel panel-default shadow-box">
                    <div class="panel-body">
                        <div>
                            @if($book->depart_task == '0')
                                <h3>配車</h3>
                            @elseif($book->depart_task == '1' && $book->return_task == '0')
                                <h3>返車</h3>
                            @elseif($book->return_task == '1')
                                <h3>Completed </h3>
                            @endif
                            <hr>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">経由</label>
                            <div class="col-lg-4" style="padding-top: 5px;font-size: 16px">
                                @if($book->portal_flag == '0')
                                    <span>自社HP</span>
                                    <input type="hidden" name="booking" value="">
                                    <input type="hidden" name="portal_id" value="{{$book->portal_id}}">
                                @elseif( $book->portal_flag == '1')
                                    <span class="new_row" >{{$book->portal_name}}</span>
                                    <span>{{$book->booking}}</span>
                                    <input type="hidden" name="booking" value="{{$book->booking}}">
                                    <input type="hidden" name="portal_id" value="{{$book->portal_id}}">
                                @endif
                            </div>
                            <label class="col-lg-2 control-label">担当者</label>
                            <div class="col-lg-4" style="padding-top: 5px;font-size: 16px">{{ $book->adminName }}</div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">ステータス</label>
                            <div class="col-lg-4 m-t-xs">
                                <select name="booking_status" class="chosen-select form-control">
                                    @foreach($booking_statuses as $book_status)
                                        <option value="{{ $book_status->status }}" @if($book_status->status==$book->status) selected @endif >
                                            {{ $book_status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-lg-2 control-label">Task Status</label>
                            <div class="col-lg-4 m-t-xs">
                                <!---->
                                @if($book->web_status == '1')
                                    <div>
                                        <label>
                                            <input type="checkbox" name="" value=""> <span style="position: relative; left:5px; top: -2px;">Web免許</span>
                                        </label>
                                        @if(!empty($book->license_surface))
                                            <label onclick="viewlicense({{json_encode($book)}})">免</label>
                                        @endif
                                    </div>

                                @elseif($book->web_status == '2')
                                    <div>
                                        <label>
                                            <input type="checkbox" name="" value=""> <span style="position: relative; left:5px; top: -2px;">Web免許</span>
                                        </label>
                                        @if(!empty($book->license_surface))
                                            <span class="license" onclick="viewlicense({{json_encode($book)}})">免</span>
                                        @endif
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="" value=""> <span style="position: relative; left:5px; top: -2px;">Web同意</span>
                                        </label>
                                    </div>
                                @elseif($book->web_status == '3')
                                    <div>
                                        <label>
                                            <input type="checkbox" name="web_status" checked > <span style="position: relative; left:5px; top: -2px;">Web免許</span>
                                        </label>
                                        @if(!empty($book->license_surface))
                                            <span class="license" onclick="viewlicense({{json_encode($book)}})">免</span>
                                        @endif
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="" checked > <span style="position: relative; left:5px; top: -2px;">Web同意</span>
                                        </label>
                                    </div>
                                    @if($book->pay_status == '1')
                                        <div>
                                            <label>
                                                <input type="checkbox" name="" value="" checked> <span style="position: relative; left:5px; top: -2px;">Web決済</span>
                                            </label>
                                        </div>
                                    @endif
                                @endif
                                <input type="hidden" name="web_" value="{{$book->web_status}}" />
                                <!---->
                            </div>
                        </div>
                        <div>
                            <hr>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">姓</label>
                            <div class="col-lg-4"><input name="client_first_name" class="form-control" value="{{ $book->fur_first_name }}" required></div>
                            <label class="col-lg-2 control-label">名</label>
                            <div class="col-lg-4"><input name="client_last_name" class="form-control" value="{{ $book->fur_last_name }}" required></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">電話</label>
                            <div class="col-lg-4"><input name="client_phone" class="form-control" value="{{ $book->phone }}" required></div>
                            <label class="col-lg-2 control-label">メール</label>
                            <div class="col-lg-4"><input name="client_email" class="form-control" value="{{ $book->email }}" required></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">緊急連絡先</label>
                            <div class="col-lg-4"><input name="emergency_phone" class="form-control" value="{{$book->emergency_phone}}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">運転手名</label>
                            <div class="col-lg-4"><input name="driver_name" class="form-control" value="{{ $book->driver_name }}"></div>
                            <label class="col-lg-2 control-label">乗車人数</label>
                            <div class="col-lg-4"><input name="passengers" class="form-control" value="{{ $book->passengers }}" required></div>
                        </div>
                        <div>
                            <hr>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">航空会社</label>
                            <div class="col-lg-4">
                                <select name="flight_line" class=" chosen-select form-control">
                                    <option value="0">Select below</option>
                                    @foreach($flight_lines as $flight_line)
                                        <option value="{{ $flight_line->id }}" @if($book->flight_line==$flight_line->id) selected @endif >
                                            {{ $flight_line->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-lg-2 control-label">便名</label>
                            <div class="col-lg-4"><input name="flight_number" class="form-control" value="{{ $book->flight_number }}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">お迎え</label>
                            <div class="col-lg-4">
                                <select id="pickup_id" name="pickup_id" class="chosen-select form-control" required>
                                    <option value="0" >Select below </option>
                                    @foreach( $shops as $shop )
                                        <option value="{{ $shop->id }}" @if($shop->id == $book->pickup_id) selected @endif >{{ $shop->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-lg-2 control-label">お送り</label>
                            <div class="col-lg-4">
                                <select id="dropoff_id" name="dropoff_id" class="chosen-select form-control" >
                                    <option value="0" >Select below </option>
                                    @foreach( $shops as $shop )
                                        <option value="{{ $shop->id }}" @if($shop->id == $book->dropoff_id) selected @endif>{{ $shop->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">車両番号</label>
                            <div class="col-lg-10">
                                <select id="inventory_id" name="inventory_id" class="chosen-select form-control" >
                                    <option value="0"> Select below </option>
                                    @foreach( $cars as $car )
                                        <option value="{{ $car->car_id }}_{{ $car->class_id }}_{{ $car->model_id }}_{{ $car->type_id}}" @if($book->inventory_id == $car->car_id) selected @endif>
                                            Class:{{$car->class_name }} &nbsp;
                                            Model:{{$car->model_name}} &nbsp;
                                            Type:{{$car->type_name}} &nbsp;
                                            Number: {{$car->numberplate1}} {{$car->numberplate2}} {{$car->numberplate3}} {{$car->numberplate4}} {{$car->smoke}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">無料オプション</label>
                            <div class="col-lg-4">
                                <select class="chosen-select form-control" name="free_options" id="free_options" multiple tabindex="2" >
                                    @foreach($book->free_options as $option)
                                        <option value="{{$option->option_id}}" @if(in_array($option->option_id, $book->free_option_ids)) selected @endif >
                                            {{$option->option_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-lg-2 control-label">有料オプション</label>
                            <div class="col-lg-4">
                                <select class="chosen-select form-control" name="paid_options" id="paid_options" multiple tabindex="2" >
                                    @foreach($book->paid_options as $option)
                                        <option value="{{$option->option_id}}" @if(in_array($option->option_id, $book->paid_option_ids)) selected @endif >
                                            {{$option->option_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">オプション合計</label>
                            <div class="col-lg-4">
                                <input name="option_price" id="option_price" class="form-control"
                                       value="{{$book->option_price}}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">出発</label>
                            <div class="col-lg-4">
                                <div class="input-group date col-lg-7 pull-left"  id="depart-datepicker">
                                    <input type="text" name="depart_date" id="depart-date" class="form-control" readonly required>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                                <div class="col-lg-5" style="padding-right: 0">
                                    <select class="chosen-select form-control" name="depart_time" id="depart-time" required>
                                        @foreach($hour as $h)
                                            <option value="{{$h}}" @if($h==$book->depart_time) selected @endif >{{$h}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label class="col-lg-2 control-label">返却</label>
                            <div class="col-lg-4">
                                <div class="input-group date col-lg-7 pull-left" id="return-datepicker">
                                    <input type="text" name="return_date" id="return-date" class="form-control" readonly required>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                                <div class="col-lg-5" style="padding-right: 0">
                                    <select class="chosen-select form-control" name="return_time" id="return-time" required>
                                        @foreach($hour as $h)
                                            <option value="{{$h}}" @if($h==$book->return_time) selected @endif>{{$h}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">利用期間</label>
                            <div class="col-lg-4">
                                <input name="total_rent_days" id="total_rent_days" class="form-control" value="{{ explode('_',$book->rent_days)[0] }}泊{{ explode('_',$book->rent_days)[1] }}日" readonly>
                                <input name="rentdays_val" id="rentdays_val" type="hidden" value="{{ $book->rent_days }}">
                            </div>
                            <label class="col-lg-2 control-label">Car Basci Price</label>
                            <div class="col-lg-4">
                                <input name="car_price" id="car_price" value="{{$book->basic_price}}"  class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">お問い合わせ</label>
                            <div class="col-lg-4"><input name="client_message" class="form-control" value="{{$book->client_message}}"></div>
                            <label class="col-lg-2 control-label">スタッフメモ</label>
                            <div class="col-lg-4"><input name="admin_memo" class="form-control" value="{{$book->admin_memo}}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">予約方法</label>
                            <div class="col-lg-4">
                                <select name="reservations" class="form-control" required>
                                    @foreach($reservations as $reservation)
                                        <option value="{{ $reservation->id }}" @if($book->reservation_id == $reservation->id) selected @endif>{{ $reservation->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-lg-2 control-label">プラン</label>
                            <div class="col-lg-4"><input name="plan" class="form-control" value="{{ $book->plan_id }}"> </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">ポイント</label>
                            <div class="col-lg-4"><input name="given_point" class="form-control" value="{{ $book->given_points }}" readonly></div>
                            <label class="col-lg-2 control-label">小計/label>
                                <div class="col-lg-4"><input id="subtotal" name="subtotal" class="form-control" value="{{ $book->subtotal }}" required></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">値引額</label>
                            <div class="col-lg-4">
                                <input id="discount" name="discount" class="form-control" value="{{ $book->discount }}"></div>
                            {{--<label class="col-lg-2 control-label">消費税</label>--}}
                            <div class="col-lg-4 hidden"><input id="tax" name="tax" class="form-control" value="0" readonly required></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">合計金額</label>
                            <div class="col-lg-4"><input id="total_pay" name="total_pay" class="form-control" value="{{ $book->payment }}" readonly required></div>
                            <label class="col-lg-2 control-label">前払い金額</label>
                            <div class="col-lg-4"><input id="prepaid" name="prepaid" class="form-control" value="{{ $book->prepaid }}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">前払い方法</label>
                            <div class="col-lg-4">
                                <select name="paymethod" class="form-control">
                                    @foreach($paymethods as $paymethod)
                                        <option value="{{ $paymethod->id }}" @if($book->pay_method == $paymethod->id) selected @endif>{{ $paymethod->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-lg-2 control-label">お支払い状況</label>
                            <div class="col-lg-4 m-t-xs">
                                <input type="hidden" name="payment_status" class="form-control" value="{{ $book->payment_status }}">
                                @if($book->payment_status == '0') Unpaid @endif
                                @if($book->payment_status == '1') Paid   @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">支払い ID</label>
                            <div class="col-lg-4"><input name="pay_id" class="form-control" value="{{$book->pay_id}}"></div>
                            <label class="col-lg-2 control-label">取引 ID</label>
                            <div class="col-lg-4"><input name="trans_id" class="form-control" value="{{$book->trans_id}}"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">payment ID</label>
                            <div class="col-lg-4"><input name="client_pay_id" class="form-control" value="{{ $book->user_pay_id }}"></div>
                            <label class="col-lg-2 control-label">transaction ID</label>
                            <div class="col-lg-4"><input name="client_trans_id" class="form-control" value="{{ $book->user_trans_id }}"></div>
                        </div>
                        <input type="hidden" name="portal_flag" value="{{$book->portal_flag}}" />
                        <div class="col-lg-12 text-center" style="margin-top:15px">
                            <button class="btn btn-primary">予約を保存</button>
                            <a href="{{URL::to('/')}}/booking/all" class="btn btn-primary">予約リストに戻る</a>
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
    </style>

    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="https://uxsolutions.github.io/bootstrap-datepicker/bootstrap-datepicker/js/locales/bootstrap-datepicker.ja.min.js" charset="UTF-8"></script>
    <script src="{{URL::to('/')}}/js/plugins/chosen/chosen.jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // init depart time and return time
            var departDate = '{{date_format(new \DateTime($book->departing),'Y/m/d')}}';
            var returnDate = '{{date_format(new \DateTime($book->returning),'Y/m/d')}}';
            var departTime = '{{date_format(new \DateTime($book->departing),'H:i')}}';
            var returnTime = '{{date_format(new \DateTime($book->returning),'H:i')}}';
            $('#depart-date').val(departDate);
            $('#return-date').val(returnDate);

            var hours = ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30'];
            $('#depart-time').html("");
            for(var k = 0; k < hours.length; k++) {
                var selected = ( departTime == hours[k])? 'selected':'';
                var option = '<option value="'+ hours[k] +'" '+selected+'>'+ hours[k] +'</option>';
                $('#depart-time').append(option);
            }

            $('#return-time').html("");
            for(var k = 0; k < hours.length; k++) {
                var selected = ( returnTime == hours[k])? 'selected':'';
                var option = '<option value="'+ hours[k] +'" '+selected+'>'+ hours[k] +'</option>';
                $('#return-time').append(option);
            }
            $('form select[name="depart-time"]').trigger("chosen:updated");
            $('form select[name="return-time"]').trigger("chosen:updated");

        });

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
            if(!isNumeric(discount)){
                $('#discount').val(0);
                return;
            }
            if(subtotal * 1 < discount * 1){
                $('#subtotal').val(0);
                $('#discount').val(0);
                return;
            }
            var tax = (subtotal - discount)*0.08;
            $('#tax').val(tax);
            $('#total_pay').val(subtotal - discount - tax);
        }

        $('#subtotal').change(function(){
            calcTaxPay();
        });

        $('#discount').change(function(){
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

        //search and select
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
            for(var i = 0; i < values.length ; i++) {
                var option_id = values[i] ;
                $.each(options, function (k, v) {
                    if(option_id == v.id) {
                        prices += v.price;
                    }
                });
            }
            $("#option_price").val(prices);
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