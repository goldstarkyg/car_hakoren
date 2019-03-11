<div>
        <div class="form-group">
            <label class="col-lg-2 control-label">担当者</label>
            <div class="col-lg-4" style="padding-top: 5px;font-size: 16px">{{ $admin->last_name.$admin->first_name}}</div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">航空会社</label>
            <div class="col-lg-4">
                <select name="flight_line" class="chosen-select form-control">
                    <option value="0">Select below</option>
                    @foreach($flight_lines as $flight_line)
                        <option value="{{ $flight_line->id }}">{{ $flight_line->title }}</option>
                    @endforeach
                </select>
            </div>
            <label class="col-lg-2 control-label">便名</label>
            <div class="col-lg-4"><input name="flight_number" class="form-control"></div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">お迎え</label>
            <div class="col-lg-4">
                <select id="pickup_id" name="pickup_id" class="chosen-select form-control" required>
                    <option value="0" >Select below </option>
                    @foreach( $shops as $shop )
                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>
            </div>
            <label class="col-lg-2 control-label">お送り</label>
            <div class="col-lg-4">
                <select id="dropoff_id" name="dropoff_id" class="chosen-select form-control" >
                    <option value="" >Select below </option>
                    @foreach( $shops as $shop )
                        <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">車両番号</label>
            <div class="col-lg-10">
                <select id="inventory_id" name="inventory_id" class="chosen-select form-control" >
                    <option value="" >Select below </option>
                    @foreach( $carclasses as $carclass )
                        <option value="{{ $carclass->id }}_{{ $carclass->class_id }}_{{ $carclass->model_id }}_{{ $carclass->type_id}}">
                            Class:{{$carclass->class_name }} &nbsp;
                            Model:{{$carclass->model_name}} &nbsp;
                            Type:{{$carclass->type_name}} &nbsp;
                            Number: {{$carclass->numberplate1}}{{$carclass->numberplate2}}{{$carclass->numberplate3}}{{$carclass->numberplate4}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">乗車人数</label>
            <div class="col-lg-4"><input name="passengers" class="form-control" ></div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">無料オプション</label>
            <div class="col-lg-4">
                <select class="chosen-select form-control" name="free_options" id="free_options" multiple tabindex="2" >
                    <!--options list-->
                    <option value="0">Select Option</option>
                </select>
                {{--<input name="free_options" class="form-control">--}}
            </div>
            <label class="col-lg-2 control-label">有料オプション</label>
            <div class="col-lg-4">
                <select class="chosen-select form-control" name="paid_options" id="paid_options" multiple tabindex="2" >
                    <!--options list-->
                    <option value="0">Select Option</option>
                </select>
                <input type="hidden" name="paid_options_price" id="paid_options_price" />
                {{--<input name="paid_options" class="form-control">--}}
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">オプション合計</label>
            <div class="col-lg-4">
                <input name="option_price" id="option_price" class="form-control" readonly value="0">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">出発</label>
            <div class="col-lg-4">
                <div class="input-group date col-lg-7 pull-left"  id="depart-datepicker">
                    <input type="text" name="depart_date" id="depart-date" class="form-control" placeholder="" readonly required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
                <div class="col-lg-5" style="padding-right: 0">
                    <select class="chosen-select form-control" name="depart_time" id="depart-time" required>
                        @foreach($hour as $h)
                            <option value="{{$h}}">{{$h}} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <label class="col-lg-2 control-label">返却</label>
            <div class="col-lg-4">
                <div class="input-group date col-lg-7 pull-left" id="return-datepicker">
                    <input type="text" name="return_date" id="return-date" class="form-control" placeholder="" readonly required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
                <div class="col-lg-5" style="padding-right: 0">
                    <select class="chosen-select form-control" name="return_time" id="return-time" required>
                        @foreach($hour as $h)
                            <option value="{{$h}}">{{$h}} </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">利用期間</label>
            <div class="col-lg-4">
                <input name="total_rent_days" id="total_rent_days" class="form-control" readonly>
            </div>
            <input name="rentdays_val" id="rentdays_val" type="hidden">
            <label class="col-lg-2 control-label">車両金額</label>
            <div class="col-lg-4">
                <input name="car_price" id="car_price" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">補償</label>
            <div class="col-lg-4">
                <select id="insurance" name="insurance" class="chosen-select form-control" >
                    <option value="" >Select below </option>
                    @foreach( $insurances as $in )
                        <option value="{{ $in->search_condition}}">{{ $in->name }}</option>
                    @endforeach
                </select>
            </div>
            <label class="col-lg-2 control-label">補償合計</label>
            <div class="col-lg-4">
                <input name="insurance_price" id="insurance_price" class="form-control" readonly value="0">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">お問い合わせ</label>
            <div class="col-lg-4"><input name="client_message" class="form-control"></div>
            <label class="col-lg-2 control-label">スタッフメモ</label>
            <div class="col-lg-4"><input name="admin_memo" class="form-control"></div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">予約方法</label>
            <div class="col-lg-4">
                <select name="reservations" class="form-control">
                    <option value="">Select below</option>
                    @foreach($reservations as $reservation)
                        <option value="{{ $reservation->id }}">{{ $reservation->title }}</option>
                    @endforeach
                </select>
            </div>
            <label class="col-lg-2 control-label">プラン</label>
            <div class="col-lg-4"><input type="number" name="plan_id" min="0" class="form-control"></div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">ポイント</label>
            <div class="col-lg-4"><input name="given_point" class="form-control" value="0" readonly></div>
            <label class="col-lg-2 control-label">小計</label>
            <div class="col-lg-4">
                <input id="subtotal" name="subtotal" class="form-control" readonly value="0" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">値引き額</label>
            <div class="col-lg-4">
                <input type="number" id="discount" name="discount" class="form-control" min=0 value="0" required>
            </div>
            <label class="col-lg-2 control-label">消費税</label>
            <div class="col-lg-4">
                <input type="number" id="tax" name="tax" class="form-control" value="0" min="0" readonly required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">合計金額</label>
            <div class="col-lg-4">
                <input id="total_pay" name="total_pay" class="form-control" value="0" readonly required>
            </div>
            <label class="col-lg-2 control-label">前払い金額</label>
            <div class="col-lg-4">
                <input type="number" id="prepaid" name="prepaid" class="form-control" value="0" min="0" >
            </div>
        </div>
 </div>
