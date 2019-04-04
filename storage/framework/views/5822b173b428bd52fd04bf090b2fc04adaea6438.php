<header class="container">
    <div class="show_pc">
        <div class="row mt-4">
            <div class="col-sm-2">
                <a href="#"><i class="fab fa-facebook-f mr-3 mt-4 font-24"></i></a>
                <a href="#"><i class="fab fa-instagram mt-4 font-24"></i></a>
            </div>
            <div class="col-sm-8 c-center">
                <a href="<?php echo route('home'); ?>"><img class="img-fluid" src="<?php echo url_static('images/logo-blog.png'); ?>" alt="logo EAS HCM blog"></a>
            </div>
            <div class="col-sm-2 c-right">
                <ul class="chat">
                    <li>
                        <a href="#" class="chat__link show-notice" data-toggle="popover" data-container="body" data-trigger="focus" data-placement="bottom" data-html="true">
                            <span class="chat__num"><?php echo e($listNotice->count()); ?></span>
                            <i class="ic-sprite ic-sprite--doc"></i>
                        </a>
                    </li>
                    <li><a href="#" class="chat__link show-chat"><i class="ic-sprite ic-sprite--chatbox"></i></a></li>
                </ul>
            </div>
        </div>
        <?php echo $__env->make('frontend.layouts.partials.box_notice', ['listNotice' => $listNotice], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
    <div class="show_sp">
        <div class="row c-center">
            <div class="col-sm-12 c-mt-3 c-center">
                <a href="<?php echo route('home'); ?>"><img class="img-fluid img-small2" src="<?php echo url_static('images/logo-blog.png'); ?>" alt="logo EAS HCM blog"></a>
            </div>
            <div class="col-sm-12 mb-3">
                <ul class="chat">
                    <li><a href="#" class="chat__link"><i class="fab fa-instagram font-24"></i></a></li>
                    <li><a href="#" class="chat__link"><i class="fab fa-facebook-f font-24"></i></a></li>
                    <li>
                        <a href="#" class="chat__link show-notice" data-toggle="popover" data-container="body" data-trigger="focus" data-placement="bottom" data-html="true">
                            <span class="chat__num chat__num--sp"><?php echo e($listNotice->count()); ?></span>
                            <i class="ic-sprite ic-sprite--doc-sm"></i>
                        </a>
                    </li>
                    <li><a href="#" class="chat__link show-chat"><i class="ic-sprite ic-sprite--chatbox-sm"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<nav class="container navbar navbar-expand-sm">
    <a class="navbar-brand" href="<?php echo route('home'); ?>">Trang chủ</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <i class="fa fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
            <?php $__currentLoopData = $listCategoryParent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo route('post.category', [$category->code]); ?>"><?php echo e($category->title); ?></a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <div class="show_pc">
        <ul class="register font-14 logged-yes">
            <?php if(auth()->guard()->guest()): ?>
                <li><a href="<?php echo route('auth.register'); ?>"><img class="mr-1" src="<?php echo url_static('images/register-icon.png'); ?>" alt="Đăng ký">Đăng ký</a></li>
                <li>/</li>
                <li><a href="<?php echo route('auth.login'); ?>">Đăng nhập</a></li>
            <?php else: ?>
                <li>
                    <a href="#" class="d-flex align-items-center" data-toggle="user__profile" data-trigger="focus" data-placement="bottom" data-html="true">
                        <figure class="avatar"><img src="<?php echo image_url(auth()->user()->avatar, '40x40'); ?>" alt="avatar" class="avatar__photo"></figure>
                        <label for="" class="user__name mr-3"><?php echo e(auth()->user()->fullname); ?></label>
                        <i class="ic-sprite ic-sprite--arrow ic-sprite--arrow--down tf-top-1"></i>
                    </a>
                </li>
            <?php endif; ?>
            <li class="b-left">&nbsp;</li>
            <li>
                <form class="position-relative" action="<?php echo route('post.search'); ?>" method="get">
                    <input class="search" type="text" name="key" placeholder="">
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
    <div id="user__profile--content" class="notify hide">
        <div class="notify">
            <div class="notify__body">
                <div class="notify__item">
                    <div class="notify__content">
                        <a href="<?php echo route('user.profile'); ?>" class="notify__title">Thông tin cá nhân</a>
                    </div>
                </div>
                <div class="notify__item">
                    <div class="notify__content">
                        <a href="<?php echo route('auth.logout'); ?>" class="notify__title">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<section class="mb-4 show_sp">
    <div class="row">
        <div class="col-sm-12 c-center">
            <ul class="register font-14">
                <?php if(auth()->guard()->guest()): ?>
                    <li><a href="<?php echo route('auth.register'); ?>"><img class="mr-1" src="<?php echo url_static('images/register-icon.png'); ?>" alt="">Đăng ký</a></li>
                    <li>/</li>
                    <li><a href="<?php echo route('auth.login'); ?>">Đăng nhập</a></li>
                <?php else: ?>
                    <li>
                        <a href="#" class="d-flex align-items-center" data-toggle="user__profile" data-trigger="focus" data-placement="bottom" data-html="true">
                            <figure class="avatar"><img src="<?php echo image_url(auth()->user()->avatar, '40x40'); ?>" alt="avatar" class="avatar__photo"></figure>
                            <label for="" class="user__name mr-3"><?php echo e(auth()->user()->fullname); ?></label>
                            <i class="ic-sprite ic-sprite--arrow ic-sprite--arrow--down tf-top-1"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="b-left">&nbsp;</li>
                <li>
                    <form class="position-relative" action="<?php echo route('post.search'); ?>" method="get">
                        <input class="search" type="text" name="key" placeholder="">
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</section>
<div class="bot-border mb-4 show_pc"></div>
