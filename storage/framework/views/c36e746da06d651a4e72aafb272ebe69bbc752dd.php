

<?php $__env->startSection('title'); ?>
<?php echo e(__('sentence.Doctorino Settings')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
   <div class="col">
      <?php if($errors->any()): ?>
      <div class="alert alert-danger">
         <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         </ul>
      </div>
      <?php endif; ?>
      <?php if(session('success')): ?>
      <div class="alert alert-success">
         <?php echo e(session('success')); ?>

      </div>
      <?php endif; ?>
   </div>
</div>
<div class="row justify-content-center">
   <div class="col-md-8">
      <div class="card shadow mb-4">
         <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">UdaipurMed Settings</h6>
         </div>
         <div class="card-body">
            <form method="post" action="<?php echo e(route('doctorino_settings.store')); ?>">
               <!-- <div class="form-group row">
                  <label for="system_name" class="col-sm-3 col-form-label"><?php echo e(__('sentence.System Name')); ?> </label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="system_name" name="system_name" value="<?php echo e(App\Setting::get_option('system_name')); ?>">
                     <?php echo e(csrf_field()); ?>

                  </div>
               </div> -->
              <?php echo e(csrf_field()); ?>

               <div class="form-group row">
                  <label for="Title" class="col-sm-3 col-form-label">Service Pincodes (Comma Separated or Single)</label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="pincodes" name="pincodes" value="<?php echo e($pincodes); ?>">
                  </div>
               </div>
              
               <div class="form-group row">
                  <div class="col-sm-9">
                     <button type="submit" class="btn btn-primary"><?php echo e(__('sentence.Save')); ?></button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\medicaladmin\resources\views/settings/doctorino_settings.blade.php ENDPATH**/ ?>