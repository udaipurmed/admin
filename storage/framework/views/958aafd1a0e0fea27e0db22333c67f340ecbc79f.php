

<?php $__env->startSection('title'); ?>
    <?php echo e(__('sentence.New Doctor')); ?>

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


        <div class="col-xl-10 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('sentence.New Doctor')); ?></h6>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo e(route('doctor.store')); ?>" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>

                        <div class="row">
                            <div class="col-xl-12">
                                
                                <div class="box" style="width:50%;margin: 0 auto;">
                                    <div class="js--image-preview"></div>
                                    <div class="upload-options">
                                        <label>
                                            <input type="file" class="image-upload" accept="image/png, image/svg, image/jpeg" name="image" />
                                        </label>
                                    </div>
                                </div>
                                <br>

                            </div>
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-form-label"><?php echo e(__('sentence.Full Name')); ?>

                                                <font color="red">*</font>
                                            </label>
                                            <input  type="text" class="form-control" id="inputEmail3" name="name"
                                                placeholder="<?php echo e(__('sentence.Full Name')); ?>">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-form-label">Consultation Fee
                                                <font color="red">*</font>
                                            </label>
                                            <input  type="text" class="form-control" id="fee" name="fee"
                                                placeholder="Please enter consultation fee">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-form-label">Video Consultation?
                                                <font color="red">*</font>
                                            </label>
                                            <input style="    width: 27px;" type="checkbox" class="form-control" id="video_call" name="video_call"
                                                placeholder="Please check if doctor can provide video consultation also">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-form-label">In-Clinic Consultation?
                                                <font color="red">*</font>
                                            </label>
                                            <input style="    width: 27px;" type="checkbox" class="form-control" id="in-clinic" name="in_clinic"
                                                placeholder="Please check if doctor can provide In-Clinic consultation">
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputPassword3"
                                                class="col-form-label"><?php echo e(__('sentence.Email Address')); ?><font
                                                    color="red">
                                                    *</font></label>
                                            <input type="email" class="form-control" id="inputPassword3" name="email"
                                                placeholder="<?php echo e(__('sentence.Email Address')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputPassword3"
                                                class="col-form-label"><?php echo e(__('sentence.Birthday')); ?><font color="red">*
                                                </font></label>
                                            <input max="2021-06-01" type="date" class="form-control"  
                                                name="birthday" autocomplete="off"
                                                placeholder="<?php echo e(__('sentence.Birthday')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputPassword3"
                                                class="col-form-label"><?php echo e(__('sentence.Phone')); ?></label>
                                            <input type="number" class="form-control" id="inputPassword3" name="phone"
                                                placeholder="<?php echo e(__('sentence.Phone')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputPassword3"
                                                class="col-form-label"><?php echo e(__('sentence.Gender')); ?><font color="red">*
                                                </font></label>
                                            <select class="form-control" name="gender">
                                                <option value="Male"><?php echo e(__('sentence.Male')); ?></option>
                                                <option value="Female"><?php echo e(__('sentence.Female')); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="inputPassword3"
                                                class="col-form-label"><?php echo e(__('sentence.Address')); ?></label>
                                            <input type="text" class="form-control" id="inputPassword3" name="address"
                                                placeholder=" <?php echo e(__('sentence.Address')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="experience"
                                                class="col-form-label"><?php echo e(__('sentence.Experience In Years')); ?>

                                                <font color="red">
                                                    *
                                                </font>
                                            </label>
                                            <input type="number" class="form-control" id="experience" name="experience"
                                                placeholder="<?php echo e(__('sentence.Experience In Years')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="speciality"
                                                class="col-form-label"><?php echo e(__('sentence.Speciality')); ?><font color="red">
                                                    *
                                                </font></label>
                                            <select class="form-control" name="speciality" id="speciality">
                                                <option value=""><?php echo e(__('sentence.Select Speciality')); ?></option>
                                                <?php $__currentLoopData = $specialities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $speciality): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($speciality->name); ?>"><?php echo e($speciality->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="lat"
                                                class="col-form-label"><?php echo e(__('sentence.Lattitude')); ?></label>
                                            <input type="text" class="form-control" id="lat" name="lat"
                                                placeholder="<?php echo e(__('sentence.Lattitude')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-12 col-md-4">
                                        <div class="form-group">
                                            <label for="long"
                                                class="col-form-label"><?php echo e(__('sentence.Longitude')); ?></label>
                                            <input type="text" class="form-control" id="long" name="long"
                                                placeholder="<?php echo e(__('sentence.Longitude')); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-12 col-md-6">
                                <div class="form-group">
                                    <label for="city" class="col-form-label"><?php echo e(__('sentence.City')); ?>

                                        <font color="red">*
                                        </font>
                                    </label>
                                    <input type="text" class="form-control" id="city" name="city"
                                        placeholder="<?php echo e(__('sentence.City')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-12 col-md-6">
                                <div class="form-group">
                                    <label for="state" class="col-form-label"><?php echo e(__('sentence.State')); ?>

                                        <font color="red">*</font>
                                    </label>
                                    <input type="text" class="form-control" id="state" name="state"
                                        placeholder="<?php echo e(__('sentence.State')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-12 col-md-6">
                                <div class="form-group">
                                    <label for="country" class="col-form-label"><?php echo e(__('sentence.Country')); ?>

                                        <font color="red">*
                                        </font>
                                    </label>
                                    <input type="text" class="form-control" id="country" name="country" autocomplete="off"
                                        value="India" disabled>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-12 col-md-6">
                                <div class="form-group">
                                    <label for="registration" class="col-form-label"><?php echo e(__('sentence.Registration')); ?>

                                        <font color="red">*</font>
                                    </label>
                                    <input type="text" class="form-control" id="registration" name="registration"
                                        placeholder="<?php echo e(__('sentence.Registration')); ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-12 col-md-6">
                                <div class="form-group">
                                    <label for="qualification" class="col-form-label"><?php echo e(__('sentence.Qualification')); ?>

                                        <font color="red">*
                                        </font>
                                    </label>
                                    <input type="text" class="form-control" id="qualification" name="qualification"
                                        autocomplete="off" placeholder="<?php echo e(__('sentence.Qualification')); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description"
                                        class="col-form-label"><?php echo e(__('sentence.Description')); ?></label>
                                    <textarea rows="3" class="form-control" id="description" name="description"
                                        placeholder="<?php echo e(__('sentence.Description')); ?>"></textarea>
                                </div>
                            </div>
                        </div>
         
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6>Select Availablity</h6>
                                </div>
                            </div>
                           
                            <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for=""><?php echo e(__('sentence.Day')); ?></label>
                                    <input type="text" name="day[]" disabled placeholder="Monday" value="Monday"
                                        class="form-control Monday_day">
                                </div>
                                <div class="col-md-1">
                                    <label for="">Available</label>
                                    <input id="Monday" type="checkbox" name="status[]" class="form-control" 
                                    onclick="changeAttribute('Monday')">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Starting Time</label>
                                    <input type="time" name="start_time[]" step="3600" id="" class="form-control"
                                        placeholder="Starting Time">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Ending Time</label>
                                    <input type="time" name="end_time[]" step="3600" id="" class="form-control"
                                        placeholder="Ending Time">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for=""><?php echo e(__('sentence.Day')); ?></label>
                                    <input type="text" name="day[]" disabled placeholder="Tuesday" value="Tuesday"
                                        class="form-control Tuesday_day">
                                </div>
                                <div class="col-md-1">
                                    <label for="">Available</label>
                                    <input id="Tuesday" type="checkbox" onclick="changeAttribute('Tuesday')" name="status[]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Starting Time</label>
                                    <input type="time" name="start_time[]" step="3600" id="" class="form-control"
                                        placeholder="Starting Time">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Ending Time</label>
                                    <input type="time" name="end_time[]" step="3600" id="" class="form-control"
                                        placeholder="Ending Time">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for=""><?php echo e(__('sentence.Day')); ?></label>
                                    <input type="text" name="day[]" disabled placeholder="Wednesday" value="Wednesday"
                                        class="form-control Wednesday_day">
                                </div>
                                <div class="col-md-1">
                                    <label for="">Available</label>
                                    <input id="Wednesday"  type="checkbox" onclick="changeAttribute('Wednesday')" name="status[]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Starting Time</label>
                                    <input type="time" name="start_time[]" step="3600" id="" class="form-control"
                                        placeholder="Starting Time">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Ending Time</label>
                                    <input type="time" name="end_time[]" step="3600" id="" class="form-control"
                                        placeholder="Ending Time">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for=""><?php echo e(__('sentence.Day')); ?></label>
                                    <input type="text" name="day[]" disabled placeholder="Thursday" value="Thursday"
                                        class="form-control Thursday_day">
                                </div>
                                <div class="col-md-1">
                                    <label for="">Available</label>
                                    <input id="Thursday" type="checkbox" onclick="changeAttribute('Thursday')" name="status[]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Starting Time</label>
                                    <input type="time" name="start_time[]" step="3600" id="" class="form-control"
                                        placeholder="Starting Time">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Ending Time</label>
                                    <input type="time" name="end_time[]" step="3600" id="" class="form-control"
                                        placeholder="Ending Time">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for=""><?php echo e(__('sentence.Day')); ?></label>
                                    <input type="text" name="day[]" disabled placeholder="Friday" value="Friday"
                                        class="form-control Friday_day">
                                </div>
                                <div class="col-md-1">
                                    <label for="">Available</label>
                                    <input id="Friday" type="checkbox" onclick="changeAttribute('Friday')" name="status[]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Starting Time</label>
                                    <input type="time" name="start_time[]" step="3600" id="" class="form-control"
                                        placeholder="Starting Time">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Ending Time</label>
                                    <input type="time" name="end_time[]" step="3600" id="" class="form-control"
                                        placeholder="Ending Time">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for=""><?php echo e(__('sentence.Day')); ?></label>
                                    <input type="text" name="day[]" disabled placeholder="Saturday" value="Saturday"
                                        class="form-control Saturday_day">
                                </div>
                                <div class="col-md-1">
                                    <label for="">Available</label>
                                    <input id="Saturday" type="checkbox" onclick="changeAttribute('Saturday')" name="status[]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Starting Time</label>
                                    <input type="time" name="start_time[]" step="3600" id="" class="form-control"
                                        placeholder="Starting Time">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Ending Time</label>
                                    <input type="time" name="end_time[]" step="3600" id="" class="form-control"
                                        placeholder="Ending Time">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for=""><?php echo e(__('sentence.Day')); ?></label>
                                    <input type="text" name="day[]" disabled placeholder="Sunday" value="Sunday"
                                        class="form-control Sunday_day">
                                </div>
                                <div class="col-md-1">
                                    <label for="">Available</label>
                                    <input id="Sunday" type="checkbox" onclick="changeAttribute('Sunday')" name="status[]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Starting Time</label>
                                    <input type="time" name="start_time[]" step="3600" id="" class="form-control"
                                        placeholder="Starting Time">
                                </div>
                                <div class="col-md-3">
                                    <label for="">Ending Time</label>
                                    <input type="time" name="end_time[]" step="3600" id="" class="form-control"
                                        placeholder="Ending Time">
                                </div>
                            </div>
                        </div>                         
                     
                        <div class="form-group">
                            <div class="text-right">
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\medicaladmin\resources\views/doctor/create.blade.php ENDPATH**/ ?>