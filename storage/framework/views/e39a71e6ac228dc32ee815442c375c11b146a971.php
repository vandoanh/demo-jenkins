<?php $__env->startSection('content'); ?>
<?php if($listBuildTop->isNotEmpty()): ?>
    <?php
        $firstPost = $listBuildTop->first();
        $listBuildTop->forget(0);
    ?>
    <article class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="box-cell">
                    <li class="c-pr-1">
                        <a href="<?php echo route('post.detail', [$firstPost->code, $firstPost->id]); ?>" title="<?php echo e($firstPost->title); ?>">
                            <div class="image-box">
                                <img src="<?php echo image_url($firstPost->thumbnail_url, '545x410'); ?>" alt="<?php echo e($firstPost->title); ?>" class="image">
                                <label class="tool-tip top-head"><?php echo e($firstPost->category->title); ?></label>
                                <div class="overlay full">
                                    <span><?php echo e($firstPost->title); ?></span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <?php $__currentLoopData = $listBuildTop->chunk(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $posts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="box-list<?php echo $index > 0 ? ' c-pt-1' : ''; ?>">
                                <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="box-item<?php echo $i % 2 != 0 ? ' c-pr-1' : ''; ?>">
                                        <a href="<?php echo route('post.detail', [$post->code, $post->id]); ?>" title="<?php echo e($post->title); ?>">
                                            <div class="image-box">
                                                <img src="<?php echo image_url($post->thumbnail_url, '268x200'); ?>" alt="<?php echo e($post->title); ?>" class="image-small">
                                                <label class="tool-tip top-head"><?php echo e($post->category->title); ?></label>
                                                <div class="overlay"><?php echo e($post->title); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </li>
                </ul>
            </div>
        </div>
    </article>
<?php endif; ?>
<?php if($listPost->isNotEmpty()): ?>
    <article class="container c-mt-3">
        <div class="card-list d-flex justify-content-between flex-wrap">
            <?php $__currentLoopData = $listPost; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card">
                    <a href="<?php echo route('post.detail', [$post->code, $post->id]); ?>" title="<?php echo e($post->title); ?>">
                        <img class="card-img-top" src="<?php echo image_url($post->thumbnail_url, '348x261'); ?>" alt="<?php echo e($post->title); ?>">
                    </a>
                    <div class="card-body">
                        <label class="tool-tip"><?php echo e($post->category->title); ?></label>
                        <p class="card-text">
                            <a href="<?php echo route('post.detail', [$post->code, $post->id]); ?>" title="<?php echo e($post->title); ?>"><?php echo e($post->title); ?></a>
                        </p>
                        <p class="date"><?php echo e(format_date_localize($post->published_at, 'article')); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </article>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<!-- js link here -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>