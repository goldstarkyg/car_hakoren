{{--
    blade of class block of search result at front search
--}}

@foreach($search_class as $class)
<?php $cid = $class->id; ?>

<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 listing_wrap">
    <div class="box-shadow relative search-result-panel ">
        <div class="row">
            <div class="col-xs-12">
                <div class="car_title m_T10 m_B20" class_id="{{ $cid }}" style="margin-left: 10px">
                    {{$class->class_name}}
                </div>
            </div>
            <div class="col-xs-12">
                <div class="col-sm-6 col-xs-12">
                    <img src="{{URL::to('/').$class->thum_path}}" class="img-responsive center-block m_Txs60">
                    <input type="hidden" class="car_photo" class_id="{{ $cid }}" value="{{ $class->thum_path }}">
                    <p class="sml-txt">
                        <label class="result_shop">
                            @if($class->shop_name =="" )
                                店舗未選択
                            @else
                                {{$class->shop_name}}
                            @endif
                        </label>
                        <label class="result_shop car_category" class_id="{{ $cid }}" style="margin-left: 20px;">
                            @if($class->category_name =="" )
                                車両カテゴリ未選択
                            @else
                                {{$class->category_name}}
                            @endif
                        </label>
                    </p>
                </div>
                <div class="col-sm-6 col-xs-12 padding-right-0">
                    <div class="panel panel-default" style="margin-bottom: 5px;">
                        <div class="panel-heading bg-grad-gray">
                            お見積もり料金
                        </div>
                        <div class="panel-body" style="padding-bottom: 0px;">
                            <div class="form-group row-bordered-result row">
                                <label class="col-md-7 col-sm-7 col-xs-6 label_manage"
                                       style="padding-left: 0;padding-top: 3px;
    margin-bottom: 10px;">
                                    <?php
                                    $dt1 = date('Y年n月j日', strtotime($class->depart_date)) . ' ';
                                    $dt1 .= date('G時', strtotime($class->depart_time));
                                    $min = intval(date('i', strtotime($class->depart_time)));
                                    if ($min > 0) $dt1 .= $min . '分';
                                    $dt2 = date('Y年n月j日', strtotime($class->return_date)) . ' ';
                                    $dt2 .= date('G時', strtotime($class->return_time));
                                    $min = intval(date('i', strtotime($class->return_time)));
                                    if ($min > 0) $dt2 .= $min . '分';
                                    ?>
                                    <div>
                                        <label>出発 : </label>
                                        <label>{{$dt1}}</label>
                                    </div>
                                    <div>
                                        <label>返却 : </label>
                                        <label>{{$dt2}}</label>
                                    </div>
                                </label>
                                <label class="col-md-5 col-sm-5 col-xs-6" style="padding-right: 0">
                                    <div class="bubble-wrap toltip_wrap" style="width: 100%">

                                        <?php
                                        $leftmany = ($class->car_count >= 10) ? 'active' : '';
                                        $leftfew = ($class->car_count <= 9 && $class->car_count >= 4) ? 'active' : '';
                                        $leftafew = ($class->car_count <= 3) ? 'active' : '';
                                        ?>
                                        <div class="bubble left many {{$leftmany}}" class_id="{{ $cid }}" style="font-size: 16px">
                                            予約できます
                                        </div>
                                        <div class="bubble left few {{$leftfew}}" class_id="{{ $cid }}" style="font-size: 16px">
                                            残り<span class="bubble_write">僅か</span>です
                                        </div>
                                        <div class="bubble left afew {{$leftafew}}" class_id="{{ $cid }}" style="font-size: 16px">
                                            残り在庫
                                            <span class="bubble_write car_count" class_id="{{ $cid }}">{{ $class->car_count }}</span>台
                                        </div>

                                    </div>
                                </label>
                            </div>
                            <div class="form-group row-bordered-result row">
                                <label class="pull-left span_nightday" class_id="{{ $cid }}">
                                    基本料金 (<?php if ($class->night_day == "0泊1日") {
                                        echo "当日返却";
                                    } else {
                                        echo $class->night_day;
                                    } ?>)
                                </label>
                                <label class="pull-right basic_price" class_id="{{ $cid }}">
                                    {{number_format($class->price)}}円
                                </label>
                                <input type="hidden" class="rent_days" class_id="{{ $cid }}"
                                       value="{{ $class->night_day }}">
                                <input type="hidden" class="price_rent"
                                       class_id="{{ $cid }}" value="{{ $class->price }}">
                            </div>
                            <?php
                            $option_ids = [];
                            $option_names = [];
                            $option_costs = [];
                            $option_numbers = [];
                            $option_prices = [];
                            if (!empty($class->options)) {
                                foreach ($class->options as $op) {
                                    $option_ids[] = $op->id;
                                    $option_names[] = $op->name;
                                    $option_costs[] = $op->price;
                                    $option_numbers[] = 1;
                                    $vp = 0;
                                    if ($op->charge_system == 'one') {
                                        $vp = $op->price;
                                    } else {
                                        $vp = $op->price * $search->rentdates;
                                    }
                                    $option_prices[] = $vp;
                                }
                            }
                            ?>
                            @if(!empty($class->options))
                                <div class="option-wrapper" class_id="{{ $cid }}">
                                    <table style="width: 100%">
                                        <tbody style="font-size: 13px">
                                        @foreach($class->options as $op)
                                            <tr class=" row-bordered-result">
                                                <td style="text-align: left">{{$op->name}}(オプション)</td>
                                                <td style="text-align: center">
                                                    <input type="hidden" name="opt_charge"
                                                           class="opt_charge"
                                                           class_id="{{ $cid }}"
                                                           oid="{{$op->id}}"
                                                           value="{{$op->charge_system}}">
                                                    @if($op->max_number == 1)

                                                        <select name="opt_num"
                                                                class="opt_num selectpicker"
                                                                class_id="{{ $cid }}"
                                                                oid="{{$op->id}}"
                                                                value="{{ $op->number }}"
                                                                min="0">
                                                            <option value="1"
                                                                    @if($op->number == '1') selected @endif>
                                                                必要
                                                            </option>
                                                            <option value="0"
                                                                    @if($op->number == '0') selected @endif>
                                                                不要
                                                            </option>
                                                        </select>
                                                    @else

                                                        <select name="opt_num"
                                                                class="opt_num selectpicker"
                                                                class_id="{{ $cid }}"
                                                                oid="{{$op->id}}"
                                                                value="{{ $op->number }}"
                                                                min="0">
                                                            @for($k = 0; $k < $op->max_number; $k++)
                                                                <option value="{{ $k }}" @if($op->number == $k) selected @endif>
                                                                    {{ $k }}個
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    @endif
                                                </td>
                                                <td style="text-align: right">
                                                    <?php
                                                    if ($op->charge_system == 'one') {
                                                        $oprice = $op->price * $op->number;
                                                    } else {
                                                        $oprice = $op->price * $op->number * $search->rentdates;
                                                    }
                                                    ?>
                                                    <span class="opt_cost" oid="{{$op->id}}" class_id="{{$cid}}">{{number_format($oprice)}}</span>円
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" class="option_ids" class_id="{{ $cid }}" value="{{ implode(',', $option_ids) }}">
                                <input type="hidden" class="option_names" class_id="{{ $cid }}" value="{{ implode(',', $option_names) }}">
                                <input type="hidden" class="option_numbers" class_id="{{ $cid }}" value="{{ implode(',', $option_numbers) }}">
                                <input type="hidden" class="option_costs" class_id="{{ $cid }}" value="{{ implode(',', $option_costs) }}">
                                <input type="hidden" class="option_prices" class_id="{{ $cid }}" value="{{ implode(',', $option_prices) }}">
                            @endif

                            @if(!empty($class->insurance))
                                <div class="form-group row-bordered-result row hidden">
                                    <div class="col-xs-6" style="padding: 0">
                                        免責保障
                                        <select name="insurance" class="insurance pull-right" class_id="{{ $cid }}">
                                            <option value="0">不要</option>
                                            <option value="{{ $class->insurance[1] }} selected">免責補償</option>
                                            <option value="{{ $class->insurance[1]+$class->insurance[2] }}">ワイド免責補償</option>
                                        </select>
                                        <input type="hidden" class="insurance_price1" value="{{ $class->insurance_price1 }}" class_id="{{ $cid }}">
                                        <input type="hidden" class="insurance_price2" value="{{ $class->insurance_price2 }}" class_id="{{ $cid }}">
                                    </div>
                                    <div class="col-xs-6" style="padding-right: 0;">
                                        <label class="pull-right">
                                            <span class="insurance-price" class_id="{{ $cid }}">0</span>円
                                        </label>
                                    </div>
                                </div>
                            @endif

                            @php
                                $max_passengers = $class->max_passengers;
                                $noset_maxpassenger = count($max_passengers) == 0;
                                $count_notempty = 0; $mps = [];
                                foreach($max_passengers as $key=>$mp){
                                    if($mp->count > 0) {
                                        $count_notempty++;
                                        $mps[] = $mp;
                                    }
                                }
                                $noset_maxpassenger = $count_notempty == 0;
                            @endphp

                            <div class="form-group row-bordered-result row">
                                <div class="col-xs-12" style="padding: 0">
                                    禁煙/喫煙
                                    <select name="car_smoking"
                                            class="car_smoking pull-right"
                                            class_id="{{ $cid }}"
                                            @if($noset_maxpassenger) disabled @endif>
                                        <option value="0" @if($class->smoke == '0') selected @endif>禁煙</option>
                                        <option value="1" @if($class->smoke == '1') selected @endif>喫煙</option>
                                        <option value="both" @if($class->smoke == 'both') selected @endif>どちらでも良い</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row-bordered-result row">
                                @if(count($max_passengers) == 1)
                                    <div class="col-xs-12" style="padding: 0">
                                        <b>{{$max_passengers[0]->max_passenger}}</b>人乗り車両
                                        <input type="hidden" name="car_passenger"
                                               class="car_passenger"
                                               value="{{$max_passengers[0]->max_passenger}}"
                                               class_id="{{ $cid }}">
                                    </div>
                                @elseif(count($max_passengers) > 1)
                                    <div class="col-xs-12" style="padding: 0">
                                        @if($count_notempty > 1)
                                            車両定員数
                                            <select name="car_passenger"
                                                    class="car_passenger pull-right"
                                                    class_id="{{ $cid }}">
                                                @foreach($mps as $pt)
                                                    <option value="{{$pt->max_passenger}}">{{$pt->max_passenger}}
                                                        人乗り
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif($count_notempty == 1)
                                            <span>
                                                *現在この車両の在庫は<b>{{$mps[0]->max_passenger}}</b>名乗りのみとなります。
                                            </span>
                                            <input type="hidden" name="car_passenger"
                                                   class="car_passenger"
                                                   value="{{$mps[0]->max_passenger}}"
                                                   class_id="{{ $cid }}">
                                        @else
                                            <span class="alert-msg">現在の車両の在庫はありません。</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="col-xs-12" style="padding: 0">車両定員数
                                        <span class="alert-msg">車両定員数が設定されていません。</span>
                                    </div>
                                @endif
                            </div>
                            @if(count($free_options)>0)
                                <div class="form-group row-bordered-result row">
                                    <div class="col-xs-12" style="padding: 0">
                                        空港送迎
                                        <select name="car_pickup" class="car_pickup pull-right" class_id="{{ $cid }}">
                                            @foreach($free_options as $fr)
                                                <option value="{{ $fr->id }}"
                                                        @if($fr->id == $search->pickup) selected @endif>{{ $fr->name }}</option>
                                            @endforeach
                                            <option value="" @if($search->pickup == '') selected @endif>
                                                不要
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-sm-5 padding-0">
                                    <div>総計（税込）</div>
                                </label>
                                <label class="col-sm-7 padding-0" style="color: #b63432">
                                    <div style="padding-top: 15px;">
                                        <label style="font-weight:bold;font-size: 55px; color:#e60707;/*margin-top: -20px; margin-bottom: -20px;*/" class="total_price" class_id="{{ $cid }}">
                                            {{number_format($class->all_price)}}
                                        </label><label style="font-weight: 300">円</label>
                                    </div>
                                    <input type="hidden" class="price_all" class_id="{{ $cid }}" value="{{ $class->all_price }}">
                                    <div>地域最安値挑戦中！</div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <label>
                            <button class="btn bg-grad-red btn_book{{$cid}}" style=" margin-top:10px;padding: 10px 50px 10px 50px"
                                    onclick="submit_booking({{ $cid }}, '{{$class->class_name}}')"
                                    @if($noset_maxpassenger)disabled @endif>
                                予約する
                            </button>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endforeach