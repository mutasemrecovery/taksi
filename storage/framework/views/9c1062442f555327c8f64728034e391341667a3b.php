<?php $__env->startSection('title', __('messages.Create_Service')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.Create_Service')); ?></h1>
        <a href="<?php echo e(route('services.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back_to_List')); ?>

        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Service_Details')); ?></h6>
        </div>
        <div class="card-body">
            <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <form action="<?php echo e(route('services.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="name_en"><?php echo e(__('messages.Name_English')); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_en" name="name_en" value="<?php echo e(old('name_en')); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="name_ar"><?php echo e(__('messages.Name_Arabic')); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name_ar" name="name_ar" value="<?php echo e(old('name_ar')); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="photo"><?php echo e(__('messages.Photo')); ?> <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="photo" name="photo" required>
                                <label class="custom-file-label" for="photo"><?php echo e(__('messages.Choose_file')); ?></label>
                            </div>
                            <div class="mt-3" id="image-preview"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="capacity"><?php echo e(__('messages.Capacity')); ?> <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo e(old('capacity', 0)); ?>" required min="0">
                            <small class="form-text text-muted"><?php echo e(__('messages.Capacity_Info')); ?></small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Pricing Information -->
                        <div class="form-group">
                            <label for="start_price"><?php echo e(__('messages.Start_Price')); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="start_price" name="start_price" value="<?php echo e(old('start_price', 0)); ?>" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="price_per_km"><?php echo e(__('messages.Price_Per_KM')); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="price_per_km" name="price_per_km" value="<?php echo e(old('price_per_km', 0)); ?>" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="waiting_time"><?php echo e(__('messages.Waiting_Time')); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="waiting_time" name="waiting_time" value="<?php echo e(old('waiting_time', 0)); ?>" required min="0">
                            <small class="form-text text-muted"><?php echo e(__('messages.Waiting_Time_Info')); ?></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="cancellation_fee"><?php echo e(__('messages.Cancellation_Fee')); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="cancellation_fee" name="cancellation_fee" value="<?php echo e(old('cancellation_fee', 0)); ?>" required min="0">
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <!-- Commission and Payment Settings -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="admin_commision"><?php echo e(__('messages.Admin_Commission')); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="admin_commision" name="admin_commision" value="<?php echo e(old('admin_commision', 0)); ?>" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="type_of_commision"><?php echo e(__('messages.Commission_Type')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="type_of_commision" name="type_of_commision" required>
                                <option value="1" <?php echo e(old('type_of_commision', 1) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Fixed_Amount')); ?></option>
                                <option value="2" <?php echo e(old('type_of_commision') == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Percentage')); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type_of_commision"><?php echo e(__('messages.is_electric')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="is_electric" name="is_electric" required>
                                <option value="1" <?php echo e(old('is_electric', 1) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Yes')); ?></option>
                                <option value="2" <?php echo e(old('is_electric') == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.No')); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><?php echo e(__('Payment Methods')); ?></label>
                        <div class="checkbox-list">
                            <label class="checkbox">
                                <input type="checkbox" name="payment_methods[]" value="1" <?php echo e(old('payment_methods') && in_array(1, old('payment_methods')) ? 'checked' : ''); ?>>
                                <span></span><?php echo e(__('Cash')); ?>

                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="payment_methods[]" value="2" <?php echo e(old('payment_methods') && in_array(2, old('payment_methods')) ? 'checked' : ''); ?>>
                                <span></span><?php echo e(__('Visa')); ?>

                            </label>
                            <label class="checkbox">
                                <input type="checkbox" name="payment_methods[]" value="3" <?php echo e(old('payment_methods') && in_array(3, old('payment_methods')) ? 'checked' : ''); ?>>
                                <span></span><?php echo e(__('Wallet')); ?>

                            </label>
                        </div>
                        <?php $__errorArgs = ['payment_methods'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                        <div class="form-group">
                            <label for="activate"><?php echo e(__('messages.Status')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="activate" name="activate" required>
                                <option value="1" <?php echo e(old('activate', 1) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Active')); ?></option>
                                <option value="2" <?php echo e(old('activate') == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Inactive')); ?></option>
                            </select>
                     </div>
                </div>

               

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo e(__('messages.Save')); ?>

                    </button>
                    <a href="<?php echo e(route('services.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> <?php echo e(__('messages.Cancel')); ?>

                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    // Show image preview
    $(document).ready(function() {
        // Show filename on file select
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
            
            // Image preview
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-fluid img-thumbnail" style="max-height: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u833050780/domains/ewformarketing.com/public_html/taksi/resources/views/admin/services/create.blade.php ENDPATH**/ ?>