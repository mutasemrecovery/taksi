

<?php $__env->startSection('title', __('messages.Create_Coupon')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.Create_Coupon')); ?></h1>
        <a href="<?php echo e(route('coupons.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back_to_List')); ?>

        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Coupon_Details')); ?></h6>
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

            <form action="<?php echo e(route('coupons.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="code"><?php echo e(__('messages.Coupon_Code')); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" value="<?php echo e(old('code')); ?>" required>
                            <small class="form-text text-muted"><?php echo e(__('messages.Coupon_Code_Info')); ?></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="title"><?php echo e(__('messages.Title')); ?> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo e(old('title')); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="coupon_type"><?php echo e(__('messages.Coupon_Type')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="coupon_type" name="coupon_type" required>
                                <option value="1" <?php echo e(old('coupon_type', 1) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.All_Rides')); ?></option>
                                <option value="2" <?php echo e(old('coupon_type') == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.First_Ride')); ?></option>
                                <option value="3" <?php echo e(old('coupon_type') == 3 ? 'selected' : ''); ?>><?php echo e(__('messages.Specific_Service')); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="service_group" style="display: none;">
                            <label for="service_id"><?php echo e(__('messages.Service')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="service_id" name="service_id">
                                <option value=""><?php echo e(__('messages.Select_Service')); ?></option>
                                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($service->id); ?>" <?php echo e(old('service_id') == $service->id ? 'selected' : ''); ?>>
                                    <?php echo e($service->name_en); ?> (<?php echo e($service->name_ar); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="activate"><?php echo e(__('messages.Status')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="activate" name="activate" required>
                                <option value="1" <?php echo e(old('activate', 1) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Active')); ?></option>
                                <option value="2" <?php echo e(old('activate') == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Inactive')); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Discount Information -->
                        <div class="form-group">
                            <label for="discount"><?php echo e(__('messages.Discount_Value')); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="<?php echo e(old('discount', 0)); ?>" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="discount_type"><?php echo e(__('messages.Discount_Type')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="discount_type" name="discount_type" required>
                                <option value="1" <?php echo e(old('discount_type', 1) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Fixed_Amount')); ?></option>
                                <option value="2" <?php echo e(old('discount_type') == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Percentage')); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="minimum_amount"><?php echo e(__('messages.Minimum_Amount')); ?> <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="minimum_amount" name="minimum_amount" value="<?php echo e(old('minimum_amount', 0)); ?>" required min="0">
                            <small class="form-text text-muted"><?php echo e(__('messages.Minimum_Amount_Info')); ?></small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date"><?php echo e(__('messages.Start_Date')); ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo e(old('start_date', date('Y-m-d'))); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><?php echo e(__('messages.End_Date')); ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo e(old('end_date', date('Y-m-d', strtotime('+30 days')))); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo e(__('messages.Save')); ?>

                    </button>
                    <a href="<?php echo e(route('coupons.index')); ?>" class="btn btn-secondary">
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
    $(document).ready(function() {
        // Toggle service select based on coupon type
        $('#coupon_type').on('change', function() {
            if ($(this).val() == '3') {
                $('#service_group').show();
                $('#service_id').prop('required', true);
            } else {
                $('#service_group').hide();
                $('#service_id').prop('required', false);
            }
        });
        
        // Trigger change on page load for initial state
        $('#coupon_type').trigger('change');
        
        // Generate random coupon code
        $('#code').on('click', function() {
            if (!$(this).val()) {
                var randomCode = Math.random().toString(36).substring(2, 8).toUpperCase();
                $(this).val(randomCode);
            }
        });
        
        // Date validation
        $('#end_date').on('change', function() {
            var startDate = $('#start_date').val();
            var endDate = $(this).val();
            
            if (startDate && endDate && startDate > endDate) {
                alert("<?php echo e(__('messages.End_Date_Error')); ?>");
                $(this).val('');
            }
        });
        
        $('#start_date').on('change', function() {
            var startDate = $(this).val();
            var endDate = $('#end_date').val();
            
            if (startDate && endDate && startDate > endDate) {
                $('#end_date').val('');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\uber\resources\views/admin/coupons/create.blade.php ENDPATH**/ ?>