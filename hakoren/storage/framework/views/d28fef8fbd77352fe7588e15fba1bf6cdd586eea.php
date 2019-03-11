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
  <?php if(!empty($customs)): ?>
  
  <?php $__currentLoopData = $customs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <tr  valign="middle">
    <td style="vertical-align:middle;"><?php echo e(str_pad($cu->id, 6, '0', STR_PAD_LEFT)); ?></td>
    <td style="vertical-align: middle;" colspan="2"><?php echo e($cu->title); ?>

      <label style="color: <?php echo ($cu->count_flag=='priceup')?'#1d84c6':'#ed5565'; ?>">(通常より<?php echo e($cu->percent); ?>% <?php echo ($cu->count_flag=='priceup')?'値上り':'値下げ'; ?>)</label></td>
    <td style="vertical-align: middle;"><?php echo e($cu->startdate); ?></td>
    <td style="vertical-align: middle;"><?php echo e($cu->enddate); ?></td>
    <td style="vertical-align: middle;"><label> <a class="btn btn-sm btn-success" href="<?php echo e(URL::to('carbasic/carclass/editpricecustom/' . $cu->id)); ?>" title="Edit"> <span class="hidden-xs hidden-sm">編集</span> </a> </label>
      <label> <?php echo $__env->make('modals.modal-delete-confirm', ['data' => $cu, 'name' => 'carclass'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> </label></td>
  </tr>
  <tr>
    <td>日帰り</td>
    <td class="custom_price_td"><?php echo e($cu->d1_day1); ?></td>
    <td class="empty_td"></td>
    <td class="empty_td"></td>
    <td class="empty_td"></td>
    <td class="custom_price_td"><?php echo e($cu->d1_total); ?>円</td>
  </tr>
  <tr>
    <td>1泊2日</td>
    <td class="custom_price_td"><?php echo e($cu->n1d2_day1); ?></td>
    <td class="custom_price_td"><?php echo e($cu->n1d2_day2); ?></td>
    <td class="empty_td"></td>
    <td class="empty_td"></td>
    <td class="custom_price_td"><?php echo e($cu->n1d2_total); ?>円</td>
  </tr>
  <tr>
    <td>2泊3日</td>
    <td class="custom_price_td"><?php echo e($cu->n2d3_day1); ?></td>
    <td class="custom_price_td"><?php echo e($cu->n2d3_day2); ?></td>
    <td class="custom_price_td"><?php echo e($cu->n2d3_day3); ?></td>
    <td class="empty_td"></td>
    <td class="custom_price_td"><?php echo e($cu->n2d3_total); ?>円</td>
  </tr>
  <tr>
    <td>3泊4日</td>
    <td class="custom_price_td"><?php echo e($cu->n3d4_day1); ?></td>
    <td class="custom_price_td"><?php echo e($cu->n3d4_day2); ?></td>
    <td class="custom_price_td"><?php echo e($cu->n3d4_day3); ?></td>
    <td class="custom_price_td"><?php echo e($cu->n3d4_day4); ?></td>
    <td class="custom_price_td"><?php echo e($cu->n3d4_total); ?>円</td>
  </tr>
  <tr>
    <td>1日追加</td>
    <td colspan="4" class="custom_price_td"><?php echo e($cu->additional_day); ?></td>
    <td class="custom_price_td"><?php echo e($cu->additional_total); ?>円</td>
  </tr>
  <tr>
    <td colspan="6" style="background-color: #D4D4D4"></td>
  </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  
  <?php else: ?>
  <tr colspan="6" align="center">レコードが見つかりません</tr>
  <?php endif; ?>
  </tbody>
</table>
<div class="clearfix">
  <?php echo $customs->render('pagination.carclassdata'); ?>   
</div>