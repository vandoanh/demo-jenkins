<?php $__env->startSection('content'); ?>
<section class="container">
    <div class="row">
        <div class="col-sm-12">
            <!-- article -->
            <article class="article">
            <header class="article__header mb-3">
                <h1 class="article__title"><?php echo e($postInfo->title); ?></h1>
                <span class="date"><?php echo e(format_date_localize($postInfo->published_at, 'article')); ?></span>
                <span class="tool-tip"><?php echo e($postInfo->category->title); ?></span>
            </header>
            <main class="article__content">
                <?php echo $postInfo->content; ?>

                <?php if(!empty($postInfo->source_name)): ?>
                    <div class="text-right font-italic text-decoration">
                        Nguồn: <u><a href="<?php echo e($postInfo->source_link); ?>" target="_blank"><?php echo e($postInfo->source_name); ?></a></u>
                    </div>
                <?php endif; ?>
            </main>
            </article>
            <!-- ./article -->

            <div class="fb-interaction mt-5">
                <div class="fb-like" data-href="<?php echo $postInfo->share_url; ?>" data-layout="button_count" data-action="like" data-size="large" data-show-faces="false" data-share="true"></div>
            </div>

            <!-- comment -->
            <?php if($postInfo->show_comment): ?>
                <div id="box_comment" data-params="<?php echo e(json_encode(['post_id' => $postInfo->id, 'limit' => config('constants.post.limit.comment')])); ?>"></div>
            <?php endif; ?>
            <!-- ./comment -->

            <!-- related post -->
            <div class="related-posts mt-5">
                <h2 class="heading-secondary mb-5 text-center">Các bài viết khác</h2>
                <?php if($listRelatedPost->isNotEmpty()): ?>
                    <div class="card-list d-flex justify-content-between flex-wrap">
                        <?php $__currentLoopData = $listRelatedPost; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card">
                                <a href="<?php echo route('post.detail', [$post->code, $post->id]); ?>" title="<?php echo e($post->title); ?>">
                                    <img class="card-img-top" src="<?php echo image_url($post->thumbnail_url, '348x261'); ?>" alt="<?php echo e($post->title); ?>">
                                </a>
                                <div class="card-body">
                                    <span class="badge badge--small badge--color-yellow badge--arrow badge--arrow-bottom-left"><?php echo e($post->category->title); ?></span>
                                    <a href="<?php echo route('post.detail', [$post->code, $post->id]); ?>#box_comment">
                                        <span class="badge badge--small badge--color-red badge--arrow badge--arrow-bottom-left" data-type="widget" data-widgettype="<?php echo e(config('constants.widget.type.comment')); ?>" data-widgetid="<?php echo e($post->id); ?>"></span>
                                    </a>
                                    <p class="card-text"><a href="<?php echo route('post.detail', [$post->code, $post->id]); ?>" title="<?php echo e($post->title); ?>"><?php echo e($post->title); ?></a></p>
                                    <p class="date"><?php echo e(format_date_localize($post->published_at, 'article')); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
            <!-- ./related post -->
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<!-- js link here -->
<script type="text/javascript">
    $(document).ready(function(){
        interaction.showComment();
        interaction.updateView('<?php echo e($postInfo->id); ?>');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>