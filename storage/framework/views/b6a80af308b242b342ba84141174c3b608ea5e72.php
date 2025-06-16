<?php $__env->startSection('title', __('messages.View_Transaction')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.View_Transaction')); ?></h1>
        <a href="<?php echo e(route('wallet_transactions.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back_to_List')); ?>

        </a>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <!-- Transaction Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 text-white <?php echo e($transaction->type_of_transaction == 1 ? 'bg-success' : 'bg-danger'); ?>">
                    <h6 class="m-0 font-weight-bold"><?php echo e($transaction->getTransactionTypeText()); ?></h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="p-4">
                            <h1 class="text-<?php echo e($transaction->type_of_transaction == 1 ? 'success' : 'danger'); ?> font-weight-bold">
                                <?php echo e($transaction->getFormattedAmount()); ?>

                            </h1>
                            <div class="text-muted">
                                <?php echo e($transaction->created_at->format('Y-m-d H:i:s')); ?>

                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6><?php echo e(__('messages.Entity')); ?></h6>
                        <?php if($transaction->user_id): ?>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-info mr-2"><?php echo e(__('messages.User')); ?></span>
                                <span>
                                    <a href="<?php echo e(route('users.show', $transaction->user_id)); ?>">
                                        <?php echo e($transaction->user->name ?? 'N/A'); ?>

                                    </a>
                                    <?php if($transaction->user && $transaction->user->phone): ?>
                                        <small class="text-muted ml-2"><?php echo e($transaction->user->phone); ?></small>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php elseif($transaction->driver_id): ?>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-primary mr-2"><?php echo e(__('messages.Driver')); ?></span>
                                <span>
                                    <a href="<?php echo e(route('drivers.show', $transaction->driver_id)); ?>">
                                        <?php echo e($transaction->driver->name ?? 'N/A'); ?>

                                    </a>
                                    <?php if($transaction->driver && $transaction->driver->phone): ?>
                                        <small class="text-muted ml-2"><?php echo e($transaction->driver->phone); ?></small>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <?php echo e(__('messages.Unknown')); ?>

                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <h6><?php echo e(__('messages.Created_By')); ?></h6>
                        <p><?php echo e($transaction->admin->name ?? __('messages.System')); ?></p>
                    </div>

                    <?php if($transaction->note): ?>
                    <div class="mb-3">
                        <h6><?php echo e(__('messages.Note')); ?></h6>
                        <div class="bg-light p-3 rounded">
                            <?php echo e($transaction->note); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <!-- Entity Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Entity_Details')); ?></h6>
                </div>
                <div class="card-body">
                    <?php if($transaction->user_id && $transaction->user): ?>
                        <div class="row">
                            <div class="col-md-6 text-center mb-4">
                                <?php if($transaction->user->photo): ?>
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $transaction->user->photo)); ?>" alt="<?php echo e($transaction->user->name); ?>" class="img-profile rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets/admin/img/undraw_profile.svg')); ?>" alt="No Image" class="img-profile rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                <?php endif; ?>
                                <h5 class="mt-3"><?php echo e($transaction->user->name); ?></h5>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo e(__('messages.ID')); ?>

                                        <span><?php echo e($transaction->user->id); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo e(__('messages.Phone')); ?>

                                        <span><?php echo e($transaction->user->phone); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo e(__('messages.Current_Balance')); ?>

                                        <span class="badge badge-primary px-3 py-2"><?php echo e($transaction->user->balance); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="<?php echo e(route('wallet_transactions.userTransactions', $transaction->user_id)); ?>" class="btn btn-info">
                                <i class="fas fa-list"></i> <?php echo e(__('messages.View_All_User_Transactions')); ?>

                            </a>
                        </div>
                    <?php elseif($transaction->driver_id && $transaction->driver): ?>
                        <div class="row">
                            <div class="col-md-6 text-center mb-4">
                                <?php if($transaction->driver->photo): ?>
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $transaction->driver->photo)); ?>" alt="<?php echo e($transaction->driver->name); ?>" class="img-profile rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('assets/admin/img/undraw_profile.svg')); ?>" alt="No Image" class="img-profile rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                <?php endif; ?>
                                <h5 class="mt-3"><?php echo e($transaction->driver->name); ?></h5>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo e(__('messages.ID')); ?>

                                        <span><?php echo e($transaction->driver->id); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo e(__('messages.Phone')); ?>

                                        <span><?php echo e($transaction->driver->phone); ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo e(__('messages.Current_Balance')); ?>

                                        <span class="badge badge-primary px-3 py-2"><?php echo e($transaction->driver->balance); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="<?php echo e(route('wallet_transactions.driverTransactions', $transaction->driver_id)); ?>" class="btn btn-info">
                                <i class="fas fa-list"></i> <?php echo e(__('messages.View_All_Driver_Transactions')); ?>

                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <?php echo e(__('messages.Entity_Not_Available')); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Transaction Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Transaction_Details')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%"><?php echo e(__('messages.ID')); ?></th>
                                    <td><?php echo e($transaction->id); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Amount')); ?></th>
                                    <td class="<?php echo e($transaction->type_of_transaction == 1 ? 'text-success' : 'text-danger'); ?> font-weight-bold">
                                        <?php echo e($transaction->getFormattedAmount()); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Transaction_Type')); ?></th>
                                    <td>
                                        <span class="badge badge-<?php echo e($transaction->type_of_transaction == 1 ? 'success' : 'danger'); ?> px-3 py-2">
                                            <?php echo e($transaction->getTransactionTypeText()); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Created_At')); ?></th>
                                    <td><?php echo e($transaction->created_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Created_By')); ?></th>
                                    <td><?php echo e($transaction->admin->name ?? __('messages.System')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\taksi\resources\views/admin/wallet_transactions/show.blade.php ENDPATH**/ ?>