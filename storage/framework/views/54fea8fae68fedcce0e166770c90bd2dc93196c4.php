<?php $__env->startSection('title', __('messages.Driver_Transactions')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.Driver_Transactions')); ?>: <?php echo e($driver->name); ?></h1>
        <div>
            <a href="<?php echo e(route('wallet_transactions.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> <?php echo e(__('messages.Add_New_Transaction')); ?>

            </a>
            <a href="<?php echo e(route('drivers.show', $driver->id)); ?>" class="btn btn-info">
                <i class="fas fa-user"></i> <?php echo e(__('messages.View_Driver_Profile')); ?>

            </a>
            <a href="<?php echo e(route('wallet_transactions.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back_to_List')); ?>

            </a>
        </div>
    </div>

    <!-- Driver Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Driver_Details')); ?></h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center">
                    <?php if($driver->photo): ?>
                        <img src="<?php echo e(asset('assets/admin/uploads/' . $driver->photo)); ?>" alt="<?php echo e($driver->name); ?>" class="img-profile rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <img src="<?php echo e(asset('assets/admin/img/undraw_profile.svg')); ?>" alt="No Image" class="img-profile rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php endif; ?>
                </div>
                <div class="col-md-5">
                    <h5 class="font-weight-bold"><?php echo e($driver->name); ?></h5>
                    <p class="mb-1"><i class="fas fa-phone text-primary"></i> <?php echo e($driver->phone); ?></p>
                    <?php if($driver->email): ?>
                        <p class="mb-1"><i class="fas fa-envelope text-primary"></i> <?php echo e($driver->email); ?></p>
                    <?php endif; ?>
                    <?php if($driver->option): ?>
                        <p class="mb-1"><i class="fas fa-car text-primary"></i> <?php echo e($driver->option->name); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-5 text-center">
                    <div class="h-100 d-flex flex-column justify-content-center">
                        <h4><?php echo e(__('messages.Current_Balance')); ?></h4>
                        <h2 class="text-primary"><?php echo e($driver->balance); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <?php echo e(__('messages.Total_Deposits')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($transactions->where('type_of_transaction', 1)->sum('amount')); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                <?php echo e(__('messages.Total_Withdrawals')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($transactions->where('type_of_transaction', 2)->sum('amount')); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <?php echo e(__('messages.Total_Transactions')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($transactions->count()); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Transaction_History')); ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('messages.ID')); ?></th>
                            <th><?php echo e(__('messages.Date')); ?></th>
                            <th><?php echo e(__('messages.Amount')); ?></th>
                            <th><?php echo e(__('messages.Type')); ?></th>
                            <th><?php echo e(__('messages.Note')); ?></th>
                            <th><?php echo e(__('messages.Created_By')); ?></th>
                            <th><?php echo e(__('messages.Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($transaction->id); ?></td>
                            <td><?php echo e($transaction->created_at->format('Y-m-d H:i')); ?></td>
                            <td class="<?php echo e($transaction->type_of_transaction == 1 ? 'text-success' : 'text-danger'); ?> font-weight-bold">
                                <?php echo e($transaction->getFormattedAmount()); ?>

                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($transaction->type_of_transaction == 1 ? 'success' : 'danger'); ?>">
                                    <?php echo e($transaction->getTransactionTypeText()); ?>

                                </span>
                            </td>
                            <td>
                                <small><?php echo e($transaction->note ?? __('messages.No_Note')); ?></small>
                            </td>
                            <td>
                                <?php echo e($transaction->admin->name ?? __('messages.System')); ?>

                            </td>
                            <td>
                                <a href="<?php echo e(route('wallet_transactions.show', $transaction->id)); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
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
        $('#dataTable').DataTable({
            "order": [[1, "desc"]]
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\taksi\resources\views/admin/wallet_transactions/driver_transactions.blade.php ENDPATH**/ ?>