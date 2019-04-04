<div id="notice_content" class="hide">
    <div class="notify">
        <p class="notify__header">Bảng thông báo</p>
        <div class="notify__body">
            <?php if($listNotice->isNotEmpty()): ?>
                <?php $__currentLoopData = $listNotice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="notify__item notify__item--active">
                        <div class="notify__content">
                            <a href="<?php echo route('notice.detail', [$item->id]); ?>" class="notify__title"><?php echo e($item->title); ?></a>
                        </div>
                        <p class="date"><?php echo e(format_date_localize($item->published_at, 'article')); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="notify__item">
                    <div class="notify__content">Không có thông báo mới.</div>
                </div>
            <?php endif; ?>
        </div>
        <p class="notify__footer"><a href="<?php echo route('notice.index'); ?>">Xem tất cả thông báo</a></p>
    </div>
</div>
