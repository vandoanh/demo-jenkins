<?php $__env->startSection('code', '404'); ?>
<?php $__env->startSection('title', __('Page Not Found')); ?>

<?php $__env->startSection('image'); ?>
    <div class="image image-404"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('message', __('Sorry, the page you are looking for could not be found.')); ?>

<?php echo $__env->make('errors.error', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>