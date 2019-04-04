<!doctype html>
<html lang="<?php echo e(config('app.locale')); ?>">
    <head>
        <title><?php echo config('site.general.site_name'); ?> - <?php echo $__env->yieldContent('title'); ?></title>
        <meta charset="utf-8">
        <meta name="robots" content="noindex,nofollow" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
        <style type="text/css">
            .app-error { color: #22292f; } .app-error .error-wrapper { min-height: 100vh; display: flex; } .error-wrapper .error-content-wrapper
            { width: 50%; align-items: center; justify-content: center; display: flex; } .error-content-wrapper .error-content { margin:
            30px; max-width: 480px; } .error-content .error-code { font-size: 9rem; font-weight: 900; } .error-content .error-line {
            width: 100%; height: 5px; margin: 30px 0; background-color: #dc3545; } .error-content .error-message { font-size: 1.875rem;
            color: #606f7b; margin-bottom: 30px; line-height: 1.5; font-weight: 300; } .error-wrapper .error-image { width: 50%; display:
            flex; min-height: 100vh; padding-bottom: 0; position: relative; } .error-image .image { position: absolute; top: 0; right:
            0; bottom: 0; left: 0; background-repeat: no-repeat; background-position: center left; } .error-image .image-403 { background-image:
            url('/static/images/403.svg'); } .error-image .image-404 { background-image: url('/static/images/404.svg'); } .error-image .image-419
            { background-image: url('/static/images/500.svg'); } .error-image .image-429 { background-image: url('/static/images/500.svg'); } .error-image
            .image-500 { background-image: url('/static/images/500.svg'); } .error-image .image-503 { background-image: url('/static/images/503.svg');
            } @media (max-width: 767px) { .app-error .error-wrapper { display: block; } .error-wrapper .error-content-wrapper { width:
            100%; } .error-wrapper .error-image { width: 100%; } .error-content .error-code { font-size: 3rem; } .error-content .error-line
            { margin: 12px 0; } .error-content .error-message { font-size: 1.5rem; } .error-image .image { background-position: top center;
            } }
        </style>
    </head>
    <body class="app-error">
        <div class="error-wrapper">
            <div class="error-content-wrapper">
                <div class="error-content">
                    <div class="error-code">
                        <?php echo $__env->yieldContent('code', __('Oh no')); ?>
                    </div>
                    <div class="error-line"></div>
                    <p class="error-message">
                        <?php echo $__env->yieldContent('message'); ?>
                    </p>
                    <div>
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Go Home</a>
                    </div>
                </div>
            </div>
            <div class="error-image">
                <?php echo $__env->yieldContent('image'); ?>
            </div>
        </div>
    </body>
</html>
