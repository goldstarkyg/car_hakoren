<table class="table table-striped table-condensed data-table" width="100%" id="customtable">
  <thead>
    <tr>
      <th>ID</th>
      <th colspan="2">タイトル</th>
      <th>開始日</th>
      <th>終了日</th>
      <th> </th>
    </tr>
  </thead>
  <tbody>  
  @if(!empty($customs))
  
  @foreach($customs as $cu)
  <tr  valign="middle">
    <td style="vertical-align:middle;">{{str_pad($cu->id, 6, '0', STR_PAD_LEFT)}}</td>
    <td style="vertical-align: middle;" colspan="2">{{$cu->title}}
      <label style="color: {!! ($cu->count_flag=='priceup')?'#1d84c6':'#ed5565' !!}">(通常より{{$cu->percent}}% {!! ($cu->count_flag=='priceup')?'値上り':'値下げ' !!})</label></td>
    <td style="vertical-align: middle;">{{$cu->startdate}}</td>
    <td style="vertical-align: middle;">{{$cu->enddate}}</td>
    <td style="vertical-align: middle;"><label> <a class="btn btn-sm btn-success" href="{{ URL::to('carbasic/carclass/editpricecustom/' . $cu->id) }}" title="Edit"> <span class="hidden-xs hidden-sm">編集</span> </a> </label>
      <label> @include('modals.modal-delete-confirm', ['data' => $cu, 'name' => 'carclass']) </label></td>
  </tr>
  <tr>
    <td>日帰り</td>
    <td class="custom_price_td">{{$cu->d1_day1}}</td>
    <td class="empty_td"></td>
    <td class="empty_td"></td>
    <td class="empty_td"></td>
    <td class="custom_price_td">{{$cu->d1_total}}円</td>
  </tr>
  <tr>
    <td>1泊2日</td>
    <td class="custom_price_td">{{$cu->n1d2_day1}}</td>
    <td class="custom_price_td">{{$cu->n1d2_day2}}</td>
    <td class="empty_td"></td>
    <td class="empty_td"></td>
    <td class="custom_price_td">{{$cu->n1d2_total}}円</td>
  </tr>
  <tr>
    <td>2泊3日</td>
    <td class="custom_price_td">{{$cu->n2d3_day1}}</td>
    <td class="custom_price_td">{{$cu->n2d3_day2}}</td>
    <td class="custom_price_td">{{$cu->n2d3_day3}}</td>
    <td class="empty_td"></td>
    <td class="custom_price_td">{{$cu->n2d3_total}}円</td>
  </tr>
  <tr>
    <td>3泊4日</td>
    <td class="custom_price_td">{{$cu->n3d4_day1}}</td>
    <td class="custom_price_td">{{$cu->n3d4_day2}}</td>
    <td class="custom_price_td">{{$cu->n3d4_day3}}</td>
    <td class="custom_price_td">{{$cu->n3d4_day4}}</td>
    <td class="custom_price_td">{{$cu->n3d4_total}}円</td>
  </tr>
  <tr>
    <td>1日追加</td>
    <td colspan="4" class="custom_price_td">{{$cu->additional_day}}</td>
    <td class="custom_price_td">{{$cu->additional_total}}円</td>
  </tr>
  <tr>
    <td colspan="6" style="background-color: #D4D4D4"></td>
  </tr>
  @endforeach
  
  @else
  <tr colspan="6" align="center">レコードが見つかりません</tr>
  @endif
  </tbody>
</table>
<div class="clearfix">
  {!! $customs->render('pagination.carclassdata') !!}   
</div>