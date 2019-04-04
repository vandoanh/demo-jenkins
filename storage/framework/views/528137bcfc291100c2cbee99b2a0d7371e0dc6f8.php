<div class="chat-popup" id="frmChat">
    <div class="chat-head">
        <button type="button" class="btn_close"><i class="ic-sprite ic-sprite--close"></i></button>
        <div class="box-chat">
            <div class="float-left">
                <div class="img-avatar pr-1">
                    <figure class="avatar-shape">
                        <?php if(auth()->check()): ?>
                            <img src="<?php echo image_url(auth()->user()->avatar, '40x40'); ?>" alt="<?php echo e(auth()->user()->fullname); ?>" />
                        <?php else: ?>
                            <img src="<?php echo url_static('images/avatar.jpg'); ?>" alt="Incognito" />
                        <?php endif; ?>
                    </figure>
                </div>
                <div class="title"><?php echo e(auth()->check() ? auth()->user()->fullname : 'User ' . $user_id); ?></div>
            </div>
        </div>
        <div class="bot-border"></div>
    </div>
    <div id="messagePanel" class="box-content-chat">
        <?php if($listMessage->isNotEmpty()): ?>
            <?php $__currentLoopData = $listMessage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="box-chat" id="message-<?php echo $message->id; ?>">
                    <div class="float-left">
                        <div class="img-avatar pr-1">
                            <figure class="avatar-shape">
                                <?php if($message->user): ?>
                                    <img src="<?php echo image_url($message->user->avatar, '40x40'); ?>" alt="<?php echo e($message->user->fullname); ?>" />
                                <?php else: ?>
                                    <img src="<?php echo url_static('images/avatar.jpg'); ?>" alt="<?php echo e($message->user); ?>" />
                                <?php endif; ?>
                            </figure>
                        </div>
                        <div class="content-chat">
                            <p class="box-grey"><?php echo $message->message; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="box-chat no-message">
                <div class="float-left">
                    <div class="content-chat">There is no message yet.</div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="footer-chat">
        <div class="bot-border mb-2"></div>
        <form id="frmMessage" action="<?php echo route('chat.send'); ?>" method="post" class="form-container" data-sending="0">
            <?php echo e(csrf_field()); ?>

            <!--<div class="float-right mr-2">
                <a href="#"><i class="far fa-smile grey mr-2"></i></a>
                <a href="#"><i class="fas fa-paperclip grey"></i></a>
            </div>-->
            <textarea placeholder="Type message..." name="message"></textarea>
        </form>
    </div>
</div>
