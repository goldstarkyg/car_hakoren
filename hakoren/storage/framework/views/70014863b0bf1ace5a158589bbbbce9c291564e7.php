<?php if($paginator->hasPages()): ?>
    <ul class="pagination pull-right custom-pagination">
        
        <?php if($paginator->onFirstPage()): ?>
            <li class="disabled"><span>前</span></li>
        <?php else: ?>
            <li><a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">前</a></li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(is_string($element)): ?>
                <li class="disabled"><span><?php echo e($element); ?></span></li>
            <?php endif; ?>

            
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li class="active"><span><?php echo e($page); ?></span></li>
                    <?php else: ?>
                        <li><a href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li><a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">次</a></li>
        <?php else: ?>
            <li class="disabled"><span>次</span></li>
        <?php endif; ?>
    </ul>
<?php endif; ?>