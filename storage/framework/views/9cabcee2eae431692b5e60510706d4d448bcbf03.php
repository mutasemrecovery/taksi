<?php $__env->startSection('title', __('messages.View_Service')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.View_Service')); ?></h1>
        <div>
            <a href="<?php echo e(route('services.edit', $service->id)); ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> <?php echo e(__('messages.Edit')); ?>

            </a>
            <a href="<?php echo e(route('services.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back_to_List')); ?>

            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Service Image -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Service_Image')); ?></h6>
                </div>
                <div class="card-body text-center">
                    <?php if($service->photo): ?>
                    <img src="<?php echo e(asset('assets/admin/uploads/' . $service->photo)); ?>" alt="<?php echo e($service->getName()); ?>" class="img-fluid rounded mb-3" style="max-height: 250px;">
                    <?php else: ?>
                    <img src="<?php echo e(asset('assets/admin/img/no-image.png')); ?>" alt="No Image" class="img-fluid rounded mb-3" style="max-height: 250px;">
                    <?php endif; ?>
                    <h4 class="font-weight-bold"><?php echo e($service->name_en); ?></h4>
                    <p class="text-muted mb-1"><?php echo e($service->name_ar); ?></p>
                    <p class="mb-2"><?php echo e(__('messages.Capacity')); ?>: <?php echo e($service->capacity); ?></p>
                </div>
            </div>
            
            <!-- Payment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Payment_Information')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                      <div class="form-group">
                            <label><?php echo e(__('Payment Methods')); ?></label>
                            <div>
                                <?php $__currentLoopData = $service->servicePayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge badge-success m-1">
                                        <?php if($payment->payment_method == 1): ?>
                                            <?php echo e(__('Cash')); ?>

                                        <?php elseif($payment->payment_method == 2): ?>
                                            <?php echo e(__('Visa')); ?>

                                        <?php elseif($payment->payment_method == 3): ?>
                                            <?php echo e(__('Wallet')); ?>

                                        <?php endif; ?>
                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="60%"><?php echo e(__('messages.Admin_Commission')); ?></th>
                                    <td>
                                        <?php echo e($service->admin_commision); ?>

                                        <span class="badge badge-info"><?php echo e($service->getCommisionTypeText()); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Cancellation_Fee')); ?></th>
                                    <td><?php echo e($service->cancellation_fee); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Waiting_Time')); ?></th>
                                    <td><?php echo e($service->waiting_time); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Service Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Service_Details')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%"><?php echo e(__('messages.ID')); ?></th>
                                    <td><?php echo e($service->id); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Name_English')); ?></th>
                                    <td><?php echo e($service->name_en); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Name_Arabic')); ?></th>
                                    <td><?php echo e($service->name_ar); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Capacity')); ?></th>
                                    <td><?php echo e($service->capacity); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Pricing Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Pricing_Details')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('messages.Start_Price')); ?></h5>
                                    <h2 class="text-primary"><?php echo e($service->start_price); ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo e(__('messages.Price_Per_KM')); ?></h5>
                                    <h2 class="text-primary"><?php echo e($service->price_per_km); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <?php echo e(__('messages.Example_Trip_Cost')); ?>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><?php echo e(__('messages.For_5_KM_Trip')); ?>:</p>
                                    <h4><?php echo e($service->start_price + ($service->price_per_km * 5)); ?></h4>
                                </div>
                                <div class="col-md-4">
                                    <p><?php echo e(__('messages.For_10_KM_Trip')); ?>:</p>
                                    <h4><?php echo e($service->start_price + ($service->price_per_km * 10)); ?></h4>
                                </div>
                                <div class="col-md-4">
                                    <p><?php echo e(__('messages.For_15_KM_Trip')); ?>:</p>
                                    <h4><?php echo e($service->start_price + ($service->price_per_km * 15)); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <?php echo e(__('messages.Admin_Fee_Example')); ?>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php if($service->type_of_commision == 1): ?>
                                    <p><?php echo e(__('messages.Fixed_Amount_Per_Trip')); ?>: <strong><?php echo e($service->admin_commision); ?></strong></p>
                                    <?php else: ?>
                                    <p><?php echo e(__('messages.For_100_Trip_Cost')); ?>:</p>
                                    <h4><?php echo e(($service->admin_commision / 100) * 100); ?></h4>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\taksi\resources\views/admin/services/show.blade.php ENDPATH**/ ?>