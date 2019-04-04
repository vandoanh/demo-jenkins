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
        <link rel="canonical" href="<?php echo url()->current(); ?>"/>
        <link rel="manifest" href="<?php echo url('manifest.json'); ?>">
        <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
        <link rel="stylesheet" href="<?php echo e(url_static('css/vendor.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(url_static('css/app.css')); ?>">
        <?php echo config('site.general.ga_code'); ?>

    </head>
    <body>
    	<div class="wrap">
            <header class="container bg-point-circle-top"></header>
            <?php echo $__env->yieldContent('content'); ?>
            <footer class="container bg-point-circle-bot"></footer>
    	</div>
    	<!-- /.auth-box -->
        <script type="text/javascript" src="<?php echo e(url_static('js/app.js')); ?>"></script>
        <?php echo $__env->yieldContent('javascript'); ?>
  	</body>
</html>
