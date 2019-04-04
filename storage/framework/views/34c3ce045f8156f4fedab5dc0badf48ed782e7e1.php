<section class="c-center mt-5">
    <p><a href="#"><i class="fab fa-instagram font-60"></i></a></p>
    <p class="font-24">
        <span class="text-red">@</span> evolable_asia
    </p>
    <?php if($listPost->isNotEmpty()): ?>
        <figure class="mt-5">
            <?php $__currentLoopData = $listPost; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo route('post.detail', [$post->code, $post->id]); ?>" title="<?php echo e($post->title); ?>">
                    <img src="<?php echo image_url($post->thumbnail_url, '225x225'); ?>" alt="<?php echo e($post->title); ?>">
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </figure>
    <?php endif; ?>
</section>
<footer class="bot-border mt-5">
    <div class="container">
        <a href="#" id="gotop" class="image_box" style="display: block;">
            <img src="<?php echo url_static('images/top-icon.png'); ?>" alt="上へ戻る">
        </a>
        <div class="show_pc">
            <div class="row mt-n3">
                <div class="col">© 2019 Designed &amp; Developed by Evolable.Asia</div>
                <div class="col  c-right">
                    <ul class="register font-1">
                        <?php $__currentLoopData = $listCategoryParent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo route('post.category', [$category->code]); ?>"><?php echo e($category->title); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-n3 show_sp">
            <div class="col c-center">
                <ul class="register font-1">
                    <?php $__currentLoopData = $listCategoryParent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo route('post.category', [$category->code]); ?>"><?php echo e($category->title); ?></a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div class="col c-center mt-3">© 2019 Designed &amp; Developed by Evolable.Asia</div>
        </div>
    </div>
</footer>
