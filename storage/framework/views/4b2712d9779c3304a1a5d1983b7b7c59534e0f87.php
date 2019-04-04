<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
    <head>
        <title><?php echo config('site.general.site_name'); ?></title>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- META FOR FACEBOOK -->
        <meta property="og:site_name" content="<?php echo e(config('app.name')); ?>" />
        <meta property="og:url" itemprop="url" content="<?php echo url()->current(); ?>" />
        <meta property="og:image" itemprop="thumbnailUrl" content="<?php echo url_static('images/icon_512.png'); ?>" />
        <meta property="og:title" itemprop="headline" content="<?php echo e(config('site.general.seo.title')); ?>" />
        <meta property="og:description" itemprop="description" content="<?php echo e(config('site.general.seo.description')); ?>" />
        <!-- END META FOR FACEBOOK -->
        <link rel="canonical" href="<?php echo url()->current(); ?>"/>
        <link rel="manifest" href="<?php echo url('manifest.json'); ?>">
        <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
        <link rel="stylesheet" href="<?php echo e(url_static('css/vendor.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url_static('css/app.css')); ?>">
    </head>
    <body>
        <div class="wrap">
            <?php echo $__env->make('frontend.layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <!-- END header -->
            <?php echo $__env->yieldContent('content'); ?>
            <?php echo $__env->make('frontend.layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <!-- END footer -->
            <?php echo $__env->make('frontend.layouts.partials.box_chat', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.2&appId=<?php echo e(env('FACEBOOK_CLIENT_ID')); ?>&autoLogAppEvents=1"></script>
        <script type="text/javascript">
            var CKEDITOR_BASEPATH = "<?php echo e(config('app.url') . '/static/js/ckeditor/'); ?>";
        </script>
        <script type="text/javascript" src="<?php echo e(url_static('js/app.js')); ?>"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            });

            $('document').ready(function() {
                notification.init({
                    subscribe_link: "<?php echo route('notification.subscribe'); ?>",
                    unsubscribe_link: "<?php echo route('notification.unsubscribe'); ?>",
                    vapid_public_key: "<?php echo config('site.notification.vapid.public_key'); ?>"
                });

                if (notification.isSupport()) {
                    notification.subscribe();
                }

                frontend.init({
                    url: {
                        root: "<?php echo e(config('app.url')); ?>",
                        css: "<?php echo e(config('app.url') . '/static/css'); ?>",
                        images: "<?php echo e(config('app.url') . '/static/images'); ?>",
                        js: "<?php echo e(config('app.url') . '/static/js'); ?>"
                    }
                });

                interaction.init({
                    url_get_widget: "<?php echo route('interaction.widget'); ?>",
                    url_get_comment: "<?php echo route('interaction.comment'); ?>",
                    url_post_comment: "<?php echo route('interaction.comment.post'); ?>",
                    url_update_view: "<?php echo route('interaction.post.view'); ?>",
                    url_update_comment_like: "<?php echo route('interaction.comment.like'); ?>",
                    max_time_allow_like: 30
                 });

                chat.init({
                    user_id: '<?php echo e($user_id); ?>'
                });

                <?php if(old('message')): ?>
                    common.showMessage('<?php echo e(old('message')[0]); ?>', 'info');
                <?php endif; ?>

                <?php if(old('error')): ?>
                    common.showMessage('<?php echo e(old('error')[0]); ?>', 'error');
                <?php endif; ?>
            });
        </script>
        <?php echo $__env->yieldContent('javascript'); ?>
    </body>
</html>
