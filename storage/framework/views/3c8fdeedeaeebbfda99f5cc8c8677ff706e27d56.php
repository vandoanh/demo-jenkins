<div class="comments mt-5">
    <h2 class="heading-secondary mb-4">Ý kiến bạn đọc (<?php echo e($total); ?>)</h2>
    <?php if($listComments->isNotEmpty()): ?>
        <div class="comments__list">
            <?php $__currentLoopData = $listComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="comments__item" id="comment_<?php echo e($comment->id); ?>">
                    <div class="comments__avatar">
                        <figure class="comments__avatar__shape">
                            <img src="<?php echo e(image_url($comment->user->avatar, '40x40')); ?>" alt="Image placeholder">
                        </figure>
                    </div>
                    <div class="comments__detail">
                        <p class="comments__user"><?php echo e($comment->user->fullname); ?></p>
                        <p class="comments__content"><?php echo e($comment->content); ?></p>
                        <div class="comments__interaction">
                            <ul class="comments__actions">
                                <li class="comments__like" data-id="<?php echo e($comment->id); ?>">
                                    <i class="ic-sprite ic-sprite--heart ic-sprite--heart--empty mr-1"></i>
                                    <span class="comments__count"><?php echo e($comment->total_like); ?></span>
                                </li>
                                <?php if(auth()->guard()->check()): ?>
                                    <li class="comments__reply"><a href="#" class="reply rounded" data-reply="<?php echo e($comment->id); ?>" data-parentid="<?php echo e($comment->parent_id); ?>">Trả lời</a></li>
                                <?php endif; ?>
                            </ul>
                            <span class="comments__time"><?php echo e(format_date_chat($comment->created_at)); ?></span>
                        </div>
                    </div>
                    <?php if($comment->childs->count() > 0): ?>
                        <ul class="comments__child">
                            <?php $__currentLoopData = $comment->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <div class="comments__item" id="comment_<?php echo e($child->id); ?>">
                                        <div class="comments__avatar">
                                            <figure class="comments__avatar__shape">
                                                <img src="<?php echo e(image_url($child->user->avatar, '40x40')); ?>" alt="Image placeholder">
                                            </figure>
                                        </div>
                                        <div class="comments__detail">
                                            <p class="comments__user"><?php echo e($child->user->fullname); ?></p>
                                            <p class="comments__content"><?php echo e($child->content); ?></p>
                                            <div class="comments__interaction">
                                                <ul class="comments__actions">
                                                    <li class="comments__like" data-id="<?php echo e($child->id); ?>">
                                                        <i class="ic-sprite ic-sprite--heart ic-sprite--heart--empty mr-1"></i>
                                                        <span class="comments__count"><?php echo e($child->total_like); ?></span>
                                                    </li>
                                                    <?php if(auth()->guard()->check()): ?>
                                                        <li class="comments__reply"><a href="#" class="reply rounded" data-reply="<?php echo e($child->parent_id); ?>" data-parentid="<?php echo e($child->parent_id); ?>">Trả lời</a></li>
                                                    <?php endif; ?>
                                                </ul>
                                                <span class="comments__time"><?php echo e(format_date_chat($child->created_at)); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="mt-5"><?php echo e($pagination); ?></div>
        </div>
    <?php endif; ?>
</div>
<?php if(auth()->guard()->check()): ?>
    <div class="comments__editor text-right mt-5">
        <form id="comment_form" action="#">
            <textarea name="content" cols="30" rows="5" placeholder="Ý kiến của bạn"></textarea>
            <button type="submit" class="btn btn--red mt-3">Gửi bình luận</button>
        </form>
    </div>
    <div class="comments__editor text-right mt-2" id="comment_reply_wrapper" style="display: none;">
        <form id="comment_reply_form" action="#">
            <textarea name="content" cols="30" rows="5" placeholder="Ý kiến của bạn"></textarea>
            <button type="submit" class="btn btn--red mt-3">Gửi bình luận</button>
        </form>
    </div>
<?php endif; ?>
