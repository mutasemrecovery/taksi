<?php $__env->startSection('title', __('messages.Drivers')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.Drivers')); ?></h1>
        <a href="<?php echo e(route('drivers.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> <?php echo e(__('messages.Add_New_Driver')); ?>

        </a>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Driver_List')); ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('messages.ID')); ?></th>
                            <th><?php echo e(__('messages.Photo')); ?></th>
                            <th><?php echo e(__('messages.Name')); ?></th>
                            <th><?php echo e(__('messages.Phone')); ?></th>
                            <th><?php echo e(__('messages.Car')); ?></th>
                            <th><?php echo e(__('messages.Option')); ?></th>
                            <th><?php echo e(__('messages.Balance')); ?></th>
                            <th><?php echo e(__('messages.Status')); ?></th>
                            <th><?php echo e(__('messages.Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($driver->id); ?></td>
                            <td>
                                <?php if($driver->photo): ?>
                                <img src="<?php echo e(asset('assets/admin/uploads/' . $driver->photo)); ?>" alt="<?php echo e($driver->name); ?>" width="50">
                                <?php else: ?>
                                <img src="<?php echo e(asset('assets/admin/img/no-image.png')); ?>" alt="No Image" width="50">
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($driver->name); ?></td>
                            <td><?php echo e($driver->phone); ?></td>
                            <td>
                                <?php echo e($driver->model ?? 'N/A'); ?>

                                <?php if($driver->color): ?>
                                <span class="badge badge-info"><?php echo e($driver->color); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($driver->option->name ?? 'N/A'); ?></td>
                            <td><?php echo e($driver->balance); ?></td>
                            <td>
                                <?php if($driver->activate == 1): ?>
                                <span class="badge badge-success"><?php echo e(__('messages.Active')); ?></span>
                                <?php else: ?>
                                <span class="badge badge-danger"><?php echo e(__('messages.Inactive')); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('drivers.show', $driver->id)); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('drivers.edit', $driver->id)); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-warning btn-sm" onclick="event.preventDefault(); document.getElementById('toggle-form-<?php echo e($driver->id); ?>').submit();">
                                        <?php if($driver->activate == 1): ?>
                                        <i class="fas fa-ban"></i>
                                        <?php else: ?>
                                        <i class="fas fa-check"></i>
                                        <?php endif; ?>
                                    </a>
                                    <form id="toggle-form-<?php echo e($driver->id); ?>" action="<?php echo e(route('drivers.toggleActivation', $driver->id)); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('GET'); ?>
                                    </form>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('<?php echo e(__('messages.Delete_Confirm')); ?>')) document.getElementById('delete-form-<?php echo e($driver->id); ?>').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-form-<?php echo e($driver->id); ?>" action="<?php echo e(route('drivers.destroy', $driver->id)); ?>" method="POST" style="display: none;">
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

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\uber\resources\views/admin/drivers/index.blade.php ENDPATH**/ ?>