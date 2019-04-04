<?php $__env->startSection('css'); ?>
<!-- css link here -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="container c-mt-15 c-center">
    <div class="row">
        <div class="col-sm-5">
            <p><a href="<?php echo route('home'); ?>"><img src="<?php echo url_static('images/logo-eas.png'); ?>" alt="logo eas"></a></p>
            <?php if(config('site.general.social_login.enable')): ?>
                <?php if(config('site.general.social_login.facebook.enable')): ?>
                    <p class="mt-5"><a href="<?php echo route('auth.social.login', ['facebook']); ?>" class="btn blue c-w320"><i class="fab fa-facebook padd-icon font-30 mr-3"></i><span>Đăng ký với Facebook</span></a></p>
                <?php endif; ?>
                <?php if(config('site.general.social_login.google.enable')): ?>
                    <p><a href="<?php echo route('auth.social.login', ['google']); ?>" class="btn red c-w320"><i class="fab fa-google-plus padd-icon font-30 mr-3"></i><span>Đăng ký với Google+<span></span></span></a></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="col-sm-2">
            <img src="<?php echo url_static('images/line-or.png'); ?>" alt="logo eas">
        </div>
        <div class="col-sm-5">
            <form action="<?php echo e(route('auth.login.post')); ?>" method="post" id="frmLogin" name="frmLogin">
                <?php echo e(csrf_field()); ?>

                <div class="form-group row">
                    <div class="col">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="<?php echo e(old('email')); ?>" />
                        <span class="icon"><i class="fas fa-envelope font-16"></i></span>
                        <?php echo show_error($errors, 'email'); ?>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <input type="password" class="form-control" placeholder="Mật khẩu" name="password" id="password" />
                        <span class="icon"><i class="fas fa-key font-16"></i></span>
                        <?php echo show_error($errors, 'password'); ?>

                    </div>
                </div>
                <p><button type="submit" class="btn btn--big btn-outline-danger c-full text-upcase font-bold">Đăng nhập</button></p>
                <p>Bạn là thành viên. <a href="<?php echo route('auth.register'); ?>"><span class="text-red text-upcase font-bold">Đăng ký</span></a></p>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
<!-- js link here -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>