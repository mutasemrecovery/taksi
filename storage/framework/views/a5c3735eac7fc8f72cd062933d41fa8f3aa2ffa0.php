<?php $__env->startSection('title', __('messages.Services')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.Services')); ?></h1>
        <a href="<?php echo e(route('services.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> <?php echo e(__('messages.Add_New_Service')); ?>

        </a>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- Services Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Services_List')); ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('messages.ID')); ?></th>
                            <th><?php echo e(__('messages.Photo')); ?></th>
                            <th><?php echo e(__('messages.Name')); ?></th>
                            <th><?php echo e(__('messages.Start_Price')); ?></th>
                            <th><?php echo e(__('messages.Price_Per_KM')); ?></th>
                            <th><?php echo e(__('messages.Commission')); ?></th>
                            <th><?php echo e(__('messages.Payment_Method')); ?></th>
                            <th><?php echo e(__('messages.Capacity')); ?></th>
                            <th><?php echo e(__('messages.Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($service->id); ?></td>
                            <td>
                                <?php if($service->photo): ?>
                                <img src="<?php echo e(asset('assets/admin/uploads/' . $service->photo)); ?>" alt="<?php echo e($service->getName()); ?>" width="50" height="50" class="img-thumbnail">
                                <?php else: ?>
                                <img src="<?php echo e(asset('assets/admin/img/no-image.png')); ?>" alt="No Image" width="50" height="50" class="img-thumbnail">
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?php echo e($service->name_en); ?></div>
                                <div class="text-muted"><?php echo e($service->name_ar); ?></div>
                            </td>
                            <td><?php echo e($service->start_price); ?></td>
                            <td><?php echo e($service->price_per_km); ?></td>
                            <td>
                                <?php echo e($service->admin_commision); ?>

                                <span class="badge badge-info"><?php echo e($service->getCommisionTypeText()); ?></span>
                            </td>
                            <td>
                                <span class="badge badge-primary"><?php echo e($service->getPaymentMethodText()); ?></span>
                            </td>
                            <td><?php echo e($service->capacity); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('services.show', $service->id)); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('services.edit', $service->id)); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('<?php echo e(__('messages.Delete_Confirm')); ?>')) document.getElementById('delete-form-<?php echo e($service->id); ?>').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-form-<?php echo e($service->id); ?>" action="<?php echo e(route('services.destroy', $service->id)); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\uber\resources\views/admin/services/index.blade.php ENDPATH**/ ?>