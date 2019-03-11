<?php $__env->startSection('template_title'); ?>
<?php echo app('translator')->getFromJson('qs1.title'); ?>
<?php $__env->stopSection(); ?>
<?php $util = app('App\Http\DataUtil\ServerPath'); ?>
<?php $__env->startSection('template_linked_css'); ?>
    <link href="<?php echo e(URL::to('/')); ?>/css/page_quickstart_form.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html"/>
    <style>
	.error-class{
		text-align:left !important;
	}
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
	<script>
		<?php if(strpos($_SERVER['HTTP_HOST'], 'hakoren') !== false || strpos($_SERVER['HTTP_HOST'], 'motocle8') !== false ): ?>
        dataLayer.push({
            'event': 'checkout',
            'ecommerce': {
                'checkout': {
                    'actionField': {'step': 3}, // Please change step number dynamically according to the checkout step.
                    'products': [{
                        'name'	: '<?php echo e($booking_class_name); ?>',
                        'id'	: '<?php echo e($booking_class_id); ?>',
                        'price'	: '<?php echo e($booking_price); ?>',
                        // 'brand': 'エスティマ',
                        'quantity': 1
                    }]
                }
            }
        });
		<?php endif; ?>
	</script>

	<div class="page-container">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head hidden-xs">
            <div class="container clearfix">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo e(URL::to('/')); ?>"><i class="fa fa-home"></i></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <span> <?php echo app('translator')->getFromJson('qs1.carhire'); ?></span>
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
								<h1> <?php echo app('translator')->getFromJson('qs1.carhire'); ?> </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- begin search -->
            <div class="page-content">
                <div class="container">
                    <div class="row">

						<div class="content-main col-lg-12 col-md-12 col-sm-12 col-xs-12 pxs0 quick_one">
                            <div class="quick_user">
                            <h3> <?php echo app('translator')->getFromJson('qs1.reservation'); ?> <?php echo app('translator')->getFromJson('qs1.mr_en'); ?> <?php echo e($userInfo->last_name); ?> <?php echo e($userInfo->first_name); ?> <?php echo app('translator')->getFromJson('qs1.mr'); ?></h3>
							<p> <?php echo app('translator')->getFromJson('qs1.thank'); ?><br/><?php echo app('translator')->getFromJson('qs1.quicklaunch'); ?></p>
                            </div>
							<!-- quick start 1 -->
							<div class="box-shadow relative red-border-top">

								<form id="quickstart-form" method="post" action="<?php echo url('/savequickstart-01'); ?>" enctype="multipart/form-data" onsubmit="return validateForm();">
                                	 <?php echo csrf_field(); ?>


									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12">
											<ol class="stepBar step3">
											  <li class="step current">STEP1</li>
											  <li class="step">STEP2</li>
											  <li class="step">STEP3</li>
											</ol>
											<h3 class="text-center q_title" style="margin-top: 30px; margin-bottom: 30px;">①【<?php echo app('translator')->getFromJson('qs1.information'); ?>】</h3></div>
									</div>
									<div class="row">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.mail'); ?><span class="req-red"> <?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<input class="form-control h40" type="text" name="email" id="email" readonly="readonly" placeholder="<?php echo app('translator')->getFromJson('qs1.mail'); ?>" value="<?php echo $userInfo->email; ?>" style="margin-bottom:15px;">
                                            <?php echo $errors->first('email', '<span class="error-class erroremail">:message</span>'); ?>

										</div>
									</div>

									<div class="row m_B10">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.name'); ?><span class="req-red"> <?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<div class="row">
												<div class="col-md-6 col-sm-6 col-xs-6 qpd_r">
													<?php if($util->lang()== 'ja'): ?>
                                                    <?php echo Form::text('last_name', $userInfo->last_name, ['id'=>'last_name', 'class' => 'form-control h40','placeholder'=>'姓','maxlength'=>'255']); ?>

                                                    <?php echo $errors->first('last_name', '<span class="error-class errorlast_name">:message</span>'); ?>

													<?php endif; ?>
													<?php if($util->lang()== 'en'): ?>
														<?php echo Form::text('last_name', $userInfo->last_name, ['id'=>'last_name', 'class' => 'form-control h40','placeholder'=>'Surname','maxlength'=>'255']); ?>

														<?php echo $errors->first('last_name', '<span class="error-class errorlast_name">:message</span>'); ?>

													<?php endif; ?>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-6 qpd_l">
													<?php if($util->lang()== 'ja'): ?>
                                                    <?php echo Form::text('first_name', $userInfo->first_name, ['id' => 'first_name','class' => 'form-control h40','placeholder'=>'名','maxlength'=>'255']); ?>

                                                    <?php echo $errors->first('first_name', '<span class="error-class errorfirst_name">:message</span>'); ?>

													<?php endif; ?>
													<?php if($util->lang()== 'en'): ?>
														<?php echo Form::text('first_name', $userInfo->first_name, ['id' => 'first_name','class' => 'form-control h40','placeholder'=>'First name','maxlength'=>'255']); ?>

														<?php echo $errors->first('first_name', '<span class="error-class errorfirst_name">:message</span>'); ?>

													<?php endif; ?>

												</div>
											</div>
										</div>
									</div>
									<?php if($util->lang()== 'ja'): ?>
									<div class="row m_B10">
										<label class="col-md-3 col-sm-3 col-xs-12 text-right" style="margin-bottom:3px;"> <?php echo app('translator')->getFromJson('qs1.phone'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<div class="row">
												<div class="col-md-6 col-sm-6 col-xs-6 qpd_r">
													<?php if($util->lang()== 'ja'): ?>
                                                    <?php echo Form::text('furi_last_name',  $userInfo->profile->fur_last_name, ['id' => 'furi_last_name','class' => 'form-control h40','placeholder'=>'セイ','maxlength'=>'255']); ?>

                                                    <?php echo $errors->first('furi_last_name', '<span class="error-class errorfuri_last_name">:message</span>'); ?>

													<?php endif; ?>
													<?php if($util->lang()== 'en'): ?>
														<?php echo Form::text('furi_last_name',  $userInfo->profile->fur_last_name, ['id' => 'furi_last_name','class' => 'form-control h40','placeholder'=>'Sur name','maxlength'=>'255']); ?>

														<?php echo $errors->first('furi_last_name', '<span class="error-class errorfuri_last_name">:message</span>'); ?>

													<?php endif; ?>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-6 qpd_l">
												   <?php if($util->lang()== 'ja'): ?>
													   <?php echo Form::text('furi_first_name',  $userInfo->profile->fur_first_name, ['id' => 'furi_first_name','class' => 'form-control h40','placeholder'=>'メイ','maxlength'=>'255']); ?>

													   <?php echo $errors->first('furi_first_name', '<span class="error-class errorfuri_first_name">:message</span>'); ?>

												   <?php endif; ?>
												   <?php if($util->lang()== 'en'): ?>
													   <?php echo Form::text('furi_first_name',  $userInfo->profile->fur_first_name, ['id' => 'furi_first_name','class' => 'form-control h40','placeholder'=>'First name','maxlength'=>'255']); ?>

													   <?php echo $errors->first('furi_first_name', '<span class="error-class errorfuri_first_name">:message</span>'); ?>

												   <?php endif; ?>
												</div>
											</div>
										</div>
									</div>
									<?php endif; ?>
									<div class="row m_B10">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.phonenumber'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<?php if($util->lang() == 'ja'): ?>
                                            	<?php echo Form::text('phone',  $userInfo->profile->phone, ['id' => 'phone','class' => 'form-control','placeholder'=>'日中に連絡がとれる電話番号をご入力ください','maxlength'=>'25']); ?>

                                            	<?php echo $errors->first('phone', '<span class="error-class errorphone">:message</span>'); ?>

											<?php endif; ?>
											<?php if($util->lang() == 'en'): ?>
												<?php echo Form::text('phone',  $userInfo->profile->phone, ['id' => 'phone','class' => 'form-control','placeholder'=>'Please input Telephone Number so that we can read to you.','maxlength'=>'25']); ?>

												<?php echo $errors->first('phone', '<span class="error-class errorphone">:message</span>'); ?>

											<?php endif; ?>
										</div>
									</div>
									<div class="row m_B10">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.postalcode'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<?php if($util->lang() == 'ja'): ?>
                                            	<?php echo Form::text('zip11',  $userInfo->profile->postal_code, ['onKeyUp'=>"AjaxZip3.zip2addr(this,'','address','address');",'onBlur'=>"AjaxZip3.zip2addr(this,'','address','address');",'id' => 'zip11','class' => 'form-control form-control h40','placeholder'=>'ハイフンなしの半角数字','maxlength'=>'25']); ?> <?php echo $errors->first('zip11', '<span class="error-class errorzip11 mh20">:message</span>'); ?>

											<?php endif; ?>
											<?php if($util->lang() == 'en'): ?>
												<?php echo Form::text('zip11',  $userInfo->profile->postal_code, ['onKeyUp'=>"AjaxZip3.zip2addr(this,'','address','address');",'onBlur'=>"AjaxZip3.zip2addr(this,'','address','address');",'id' => 'zip11','class' => 'form-control form-control h40','placeholder'=>'Half size number with no hyphen (-)','maxlength'=>'25']); ?> <?php echo $errors->first('zip11', '<span class="error-class errorzip11 mh20">:message</span>'); ?>

											<?php endif; ?>
                                        </div>
                                    </div>

									<div class="row m_B10">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.address'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">
											<?php if($util->lang() == 'ja'): ?>
												<?php echo Form::text('address',  $userInfo->profile->address1, ['id' => 'address','class' => 'form-control h40','placeholder'=>'郵便番号を入力すると自動挿入されます。','maxlength'=>'255']); ?>

												<?php echo $errors->first('address', '<span class="error-class erroraddress">:message</span>'); ?>

											<?php endif; ?>
											<?php if($util->lang() == 'en'): ?>
												<?php echo Form::text('address',  $userInfo->profile->address1, ['id' => 'address','class' => 'form-control h40','placeholder'=>'After input Postcode, The address will be displayed automatically.','maxlength'=>'255']); ?>

												<?php echo $errors->first('address', '<span class="error-class erroraddress">:message</span>'); ?>

											<?php endif; ?>
										</div>
									</div>

									<div class="row m_B10">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.flightnumber'); ?></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">

                                            <input type="text" name="flight_inform" id="flight_inform" class="form-control h40" maxlength="255" placeholder="ANA 123">
                                            
												
												
												
											
                                            <?php echo $errors->first('flight', '<span class="error-class erroraddress">:message</span>'); ?>

										</div>
									</div>
									<div class="row m_B10">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.passenger'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">
                                        
										<div>
											<select id="person_number" class="m_B10 form-control w60 form-control h40" name="person_number">
												<?php $__currentLoopData = $maxPerson; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<option value="<?php echo e($pe); ?>" <?php if($bookingInfo->passengers == $pe): ?> selected <?php endif; ?> <?php if( $pe > $bookingInfo->passengers): ?> disabled <?php endif; ?> ><?php echo e($pe); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</select><?php echo app('translator')->getFromJson('qs1.people'); ?>
										</div>
                                         <?php echo $errors->first('person_number', '<span class="error-class errorperson_number">:message</span>'); ?>

										</div>


									</div>
									<div class="row m_B10">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.drivers'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">
                                        	<?php echo Form::select('driver_number', [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10], $bookingInfo->drivers, ['id' => 'driver_number','onchange'=>"javascript:uploadDriverLicences();",'class' => 'm_B10 form-control w60 form-control h40']); ?> <?php echo app('translator')->getFromJson('qs1.people'); ?>
                                            <?php echo $errors->first('driver_number', '<span class="error-class errordriver_number">:message</span>'); ?>

										</div>
									</div>
									<div class="row m_B10 quick_driv_lab">
										<div class="col-md-3 col-sm-3 col-xs-12 text-right">
											<label><?php echo app('translator')->getFromJson('qs1.license'); ?><span class="req-red"><?php echo app('translator')->getFromJson('qs1.required'); ?></span></label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-12">

                                            <div id="drivers_licenses">

                                            <?php for($i=1; $i <= (old('driver_number')?old('driver_number'):($bookingInfo->drivers?$bookingInfo->drivers:1)); $i++): ?>

                                            <?php $i=0; $j=0; ?>
                                            <?php if(!empty($driverLicences->toArray())): ?>

                                            	<?php $__currentLoopData = $driverLicences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            	<?php $i++; $j++; ?>
												<div class="row" style="padding-bottom:35px;">
                                                <div class="imgInput col-md-6 col-sm-6 col-xs-12">
													<div class="sec-text"><p><span><?php echo app('translator')->getFromJson('qs1.surface'); ?></span><?php echo app('translator')->getFromJson('qs1.upload'); ?></p></div>
													<input type="file" id="file-<?php echo e($i); ?>" accept="image/*" capture="camera" name="license_surface[]" onchange="javascript:readURL(this);" class="mw220 inputfile">
                                                    <label for="file-<?php echo e($i); ?>">
                                                        <strong><i class="fa fa-camera"></i> <?php echo app('translator')->getFromJson('qs1.photo'); ?></strong>
                                                        <span id="display_name<?php echo $i;?>"></span>
                                                    </label>

													<img src="<?php echo ($driver->representative_license_surface)?url($driver->representative_license_surface):url('/img/license_omote.png'); ?>" alt="" class="imgView img-responsive">


                                                     <?php if($errors->has('license_surface.'.($i-1))): ?>
														<span class="error-class errorperson_number" style="margin:15px 0px;"><?php echo e($errors->first('license_surface.'.($i-1))); ?></span>
                                                     <?php endif; ?>
												</div>
												<div class="imgInput col-md-6 col-sm-6 col-xs-12">
													<div class="sec-text"><p><span><?php echo app('translator')->getFromJson('qs1.backside'); ?></span><?php echo app('translator')->getFromJson('qs1.upload'); ?></p></div>

													<input type="file" id="backfile-<?php echo $i;?>" accept="image/*" capture="camera" name="license_back[]" onchange="javascript:readURL(this);" class="mw220">
                                                    <label for="backfile-<?php echo $i;?>">
                                                        <strong><i class="fa fa-camera"></i> <?php echo app('translator')->getFromJson('qs1.photo'); ?></strong>
                                                        <span id="display_back_name<?php echo $i;?>"></span>
                                                    </label>
													<img src="<?php echo ($driver->representative_license_back)?url($driver->representative_license_back):url('/img/license_ura.png'); ?>" alt="" class="imgView img-responsive">
                                                    <?php if($errors->has('license_back.'.($i-1))): ?>                                                       	                                              		 <span class="error-class errorperson_number" style="margin:15px 0px;"><?php echo e($errors->first('license_back.'.($i-1))); ?></span>

                                                     <?php endif; ?>
												</div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                            <?php $i++;$j++; ?>
                                            <div class="row" style="padding-bottom:35px;">
												<div class="imgInput col-md-6 col-sm-6 col-xs-12">
													<div class="sec-text"><p><span><?php echo app('translator')->getFromJson('qs1.surface'); ?></span><?php echo app('translator')->getFromJson('qs1.upload'); ?></p></div>
													<input type="file" id="file-<?php echo e($i); ?>" accept="image/*" capture="camera" name="license_surface[]" onchange="javascript:readURL(this);" class="mw220 inputfile">
                                                    <label for="file-<?php echo e($i); ?>">
                                                        <strong><i class="fa fa-camera"></i> <?php echo app('translator')->getFromJson('qs1.photo'); ?></strong>
                                                        <span id="display_name<?php echo $i;?>"></span>
                                                    </label>
													<img src="<?php echo e(URL::to('/img/license_omote.png')); ?>" alt="" class="imgView img-responsive driv_gallery">
                                                     <?php if($errors->has('license_surface.'.($i-1))): ?>                                                    	                                              		 <span class="error-class errorperson_number" style="margin:15px 0px;"><?php echo e($errors->first('license_surface.'.($i-1))); ?></span>
                                                     <?php endif; ?>
												</div>
												<div class="imgInput col-md-6 col-sm-6 col-xs-12">
													<div class="sec-text"><p><span><?php echo app('translator')->getFromJson('qs1.backside'); ?></span><?php echo app('translator')->getFromJson('qs1.upload'); ?></p></div>
													<input type="file" id="backfile-<?php echo $i;?>" accept="image/*" capture="camera" name="license_back[]" onchange="javascript:readURL(this);" class="mw220">
                                                    <label for="backfile-<?php echo $i;?>">
                                                        <strong><i class="fa fa-camera"></i> <?php echo app('translator')->getFromJson('qs1.photo'); ?></strong>
                                                        <span id="display_back_name<?php echo $i;?>"></span>
                                                    </label>
													<img src="<?php echo e(URL::to('/img/license_ura.png')); ?>" alt="" class="imgView img-responsive driv_gallery">
                                                     <?php if($errors->has('license_surface.'.($i-1))): ?>                                                    	                                              		 <span class="error-class errorperson_number" style="margin:15px 0px;"><?php echo e($errors->first('license_surface.'.($i-1))); ?></span>
                                                     <?php endif; ?>
												</div>
											</div>
                                            <?php endif; ?>
											<?php endfor; ?>

                                            </div>

											<p><?php echo app('translator')->getFromJson('qs1.bothphoto'); ?></p>
										</div>

										<div class="row m_B10">
											<div class="col-md-3 col-sm-3 col-xs-12 text-right">
												<label style="margin-bottom: 10px;"><?php echo app('translator')->getFromJson('qs1.comment'); ?></label>
											</div>
											<div class="col-md-9 col-sm-9 col-xs-12 text-center">
												<textarea name="comment" id="comment" placeholder="<?php echo app('translator')->getFromJson('qs1.requestetc'); ?>" rows="5" style="width: 80%;"></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="error-box col-md-12 col-sm-12 col-xs-12">
											<span class="error-class errorphone"></span>
										</div>
									</div>
									<input type="submit" name="submit" class="submitBtn form-control h40" value=" <?php echo app('translator')->getFromJson('qs1.content'); ?>">
								</form>
							</div>
							<!-- quick start 1 -->


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
                    <div class="row">
                        <div class="col-md-12 clearfix">
                            <a href="#" class="bg-carico totop-link"><?php echo app('translator')->getFromJson('qs1.toppage'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end search -->
        </div>
        <!-- END CONTENT -->

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

        var increment          = "<?php echo $i; ?>";
        //var back_increment     = "<?php echo $j; ?>";

		function uploadDriverLicences(){

			var total_drivers = $('#driver_number').val();

			var	html_text	  = "";

			var front_img     = "<?php echo URL::to('/img/license_omote.png'); ?>";
			var back_img      = "<?php echo URL::to('/img/license_ura.png'); ?>";

			$('div#drivers_licenses').html('');

			var count 		  = $("div#drivers_licenses").children().length;


			for(var i=1; i <= parseInt(total_drivers - count) ; i++){

				// console.log("row:",i);

				html_text	  += '<div class="row" style="padding-bottom:35px;"><div class="imgInput col-md-6 col-sm-6 col-xs-12"><div class="sec-text"><p><span>表面</span>をアップロードして下さい。</p></div><input type="file" accept="image/*" id="file-'+increment+'" capture="camera" name="license_surface[]" onchange="javascript:readURL(this);">'

				+'<label for="file-'+increment+'"><strong><i class="fa fa-camera"></i> 写真を選択</strong><span id="display_name'+increment+'"></span></label>'

				+'<img src="'+front_img+'" alt="" class="imgView img-responsive"></div><div class="imgInput col-md-6 col-sm-6 col-xs-12"><div class="sec-text"><p><span>裏面</span>をアップロードして下さい。</p></div> <input type="file" id="backfile-'+increment+'" accept="image/*" capture="camera" name="license_back[]" onchange="javascript:readURL(this);">'

				+'<label for="backfile-'+increment+'"><strong><i class="fa fa-camera"></i> 写真を選択</strong><span id="display_back_name'+increment+'"></span></label>'


				+'<img src="'+back_img+'" alt="" class="imgView img-responsive"></div></div>';

				increment++;
				//back_increment++;
			}


			$('#drivers_licenses').append(html_text);

		}

		function validateForm(){

			var error 			= 0;
			var filter			= /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
			var email 		  	=  $('#email').val();
			var last_name 	  	=  $('#last_name').val();
			var first_name 	  	=  $('#first_name').val();
			var furi_last_name  =  $('#furi_last_name').val();
			var furi_first_name =  $('#furi_first_name').val();
			var phone 		  	=  $('#phone').val();
			var address 	  	=  $('#address').val();
			var zip11 		  	=  $('#zip11').val();
			var person_number 	=  parseInt($('#person_number').val());
			var driver_number 	=  parseInt($('#driver_number').val());
			var max_passenger	=  '<?php echo $psgRanges->max_passenger; ?>';

			$("#email").next("span").remove();
			$("#last_name").next("span").remove();
			$("#first_name").next("span").remove();
			$("#phone").next("span").remove();
			$("#address").next("span").remove();
			$("#zip11").next("span").remove();
			$("#person_number").parent("div").next("span").remove();
			$("#driver_number").next("span").remove();

			if(!email){
				$("#email").after("<span class='error-class'>The email field is required.</span>"); error++;
			}else if(!filter.test(email)){
				$("#email").after("<span class='error-class'>This email is found invalid.</span>"); error++;
			}

			if(!last_name){
				$("#last_name").after("<span class='error-class'>The last name field is required.</span>"); error++;
			}

			if(!first_name){
				$("#first_name").after("<span class='error-class'>The first name field is required.</span>"); error++;
			}

			if(!phone){
				$("#phone").after("<span class='error-class'>The phone field is required.</span>"); error++;
			}

			if(!address){
			    <?php if($util->lang() == 'ja'): ?>
					$("#address").after("<span class='error-class'>住所は必須項目です</span>"); error++;
				<?php endif; ?>
				<?php if($util->lang() == 'en'): ?>
                	$("#address").after("<span class='error-class'>The address field is required.</span>"); error++;
				<?php endif; ?>
			}

			if(!zip11){
			    <?php if($util->lang() == 'ja'): ?>
					$("#zip11").after("<span class='error-class'>郵便番号は必須項目です</span>"); error++;
				<?php endif; ?>
				<?php if($util->lang() == 'en'): ?>
                	$("#zip11").after("<span class='error-class'>Half size number with no hyphen (-)</span>"); error++;
				<?php endif; ?>
			}

			if(!person_number){
				$("#person_number").parent('div').after("<span class='error-class'>乗車人数をご記入ください。</span>"); error++;
			}else if(parseInt(person_number) > parseInt(max_passenger)){
				$("#person_number").parent('div').after("<span class='error-class'>最大車両数は "+parseInt(max_passenger)+" 名です</span>"); error++;
			}

			if(!driver_number){
				$("#driver_number").after("<span class='error-class'>The drivers number field is required.</span>"); error++;
			}

			$('input[type="file"]').each(function(index){
				$(this).parent(".imgInput").find('span.error-class').remove();
				<?php if($util->lang() == 'ja'): ?>
					if($(this).attr('name') == 'license_surface[]' && (~($(this).parent(".imgInput").find('img').attr('src')).indexOf("license_omote"))){
						$(this).parent(".imgInput").find("img").after("<span class='error-class'>免許書の表面をアップロードしてください</span>");
						error++;
					}
					if($(this).attr('name') == 'license_back[]' && (~($(this).parent(".imgInput").find('img').attr('src')).indexOf("license_ura"))){
						$(this).parent(".imgInput").find("img").after("<span class='error-class'>免許証の裏面をアップロードしてください</span>");
						error++;
					}
				<?php endif; ?>
				<?php if($util->lang() == 'en'): ?>
					if($(this).attr('name') == 'license_surface[]' && (~($(this).parent(".imgInput").find('img').attr('src')).indexOf("license_omote"))){
						$(this).parent(".imgInput").find("img").after("<span class='error-class'>Please upload the license's surface.</span>");
						error++;
					}
					if($(this).attr('name') == 'license_back[]' && (~($(this).parent(".imgInput").find('img').attr('src')).indexOf("license_ura"))){
						$(this).parent(".imgInput").find("img").after("<span class='error-class'>Please upload the back of your license.</span>");
						error++;
					}
				<?php endif; ?>

            });

			if(error >0){ return false; }
		}
</script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>

</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<script type="text/javascript">

/*
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
        });
    });
});
*/

	function readURL(input) {
		if (input.files && input.files[0]) {
            var file = input.files[0];
            var filename = file.name;
            //alert(filename);
			var reader = new FileReader();
			reader.onload = function (e) {
				$(input).parent(".imgInput").find('img').attr("src", e.target.result);

				var display_name = input.id.split("-");
				var get_id = display_name[1];


				if(display_name[0] == 'backfile'){
					$("#display_back_name"+get_id).html(filename);

				} else
				{
					$("#display_name"+get_id).html(filename);
				}


			}
			reader.readAsDataURL(input.files[0]);
		}
	}
</script>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>