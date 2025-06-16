<?php $__env->startSection('title', __('messages.Orders')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.Orders')); ?></h1>
        <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> <?php echo e(__('messages.Add_New_Order')); ?>

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

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Filter_Orders')); ?></h6>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('orders.filter')); ?>" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="user_id"><?php echo e(__('messages.User')); ?></label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value=""><?php echo e(__('messages.All_Users')); ?></option>
                                <?php $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?> (<?php echo e($user->phone); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="driver_id"><?php echo e(__('messages.Driver')); ?></label>
                            <select class="form-control" id="driver_id" name="driver_id">
                                <option value=""><?php echo e(__('messages.All_Drivers')); ?></option>
                                <?php $__currentLoopData = $drivers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($driver->id); ?>" <?php echo e(request('driver_id') == $driver->id ? 'selected' : ''); ?>>
                                    <?php echo e($driver->name); ?> (<?php echo e($driver->phone); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="service_id"><?php echo e(__('messages.Service')); ?></label>
                            <select class="form-control" id="service_id" name="service_id">
                                <option value=""><?php echo e(__('messages.All_Services')); ?></option>
                                <?php $__currentLoopData = $services ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($service->id); ?>" <?php echo e(request('service_id') == $service->id ? 'selected' : ''); ?>>
                                    <?php echo e($service->name_en); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status"><?php echo e(__('messages.Status')); ?></label>
                            <select class="form-control" id="status" name="status">
                                <option value="all" <?php echo e(request('status') == 'all' ? 'selected' : ''); ?>><?php echo e(__('messages.All_Statuses')); ?></option>
                                <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.Pending')); ?></option>
                                <option value="2" <?php echo e(request('status') == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Accepted')); ?></option>
                                <option value="3" <?php echo e(request('status') == '3' ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Going_To_User')); ?></option>
                                <option value="4" <?php echo e(request('status') == '4' ? 'selected' : ''); ?>><?php echo e(__('messages.User_With_Driver')); ?></option>
                                <option value="5" <?php echo e(request('status') == '5' ? 'selected' : ''); ?>><?php echo e(__('messages.Delivered')); ?></option>
                                <option value="6" <?php echo e(request('status') == '6' ? 'selected' : ''); ?>><?php echo e(__('messages.User_Cancelled')); ?></option>
                                <option value="7" <?php echo e(request('status') == '7' ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Cancelled')); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="payment_method"><?php echo e(__('messages.Payment_Method')); ?></label>
                            <select class="form-control" id="payment_method" name="payment_method">
                                <option value="all" <?php echo e(request('payment_method') == 'all' ? 'selected' : ''); ?>><?php echo e(__('messages.All_Methods')); ?></option>
                                <option value="1" <?php echo e(request('payment_method') == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.Cash')); ?></option>
                                <option value="2" <?php echo e(request('payment_method') == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.Visa')); ?></option>
                                <option value="3" <?php echo e(request('payment_method') == '3' ? 'selected' : ''); ?>><?php echo e(__('messages.Wallet')); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status_payment"><?php echo e(__('messages.Payment_Status')); ?></label>
                            <select class="form-control" id="status_payment" name="status_payment">
                                <option value="all" <?php echo e(request('status_payment') == 'all' ? 'selected' : ''); ?>><?php echo e(__('messages.All')); ?></option>
                                <option value="1" <?php echo e(request('status_payment') == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.Pending')); ?></option>
                                <option value="2" <?php echo e(request('status_payment') == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.Paid')); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_from"><?php echo e(__('messages.Date_From')); ?></label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo e(request('date_from')); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_to"><?php echo e(__('messages.Date_To')); ?></label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo e(request('date_to')); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> <?php echo e(__('messages.Filter')); ?>

                        </button>
                        <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> <?php echo e(__('messages.Reset')); ?>

                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <?php echo e(__('messages.Total_Orders')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($orders->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <?php echo e(__('messages.Completed_Orders')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($orders->where('status', 5)->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                <?php echo e(__('messages.Cancelled_Orders')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($orders->whereIn('status', [6, 7])->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <?php echo e(__('messages.Total_Revenue')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($orders->where('status', 5)->sum('total_price_after_discount')); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Orders_List')); ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo e(__('messages.ID')); ?></th>
                            <th><?php echo e(__('messages.Date')); ?></th>
                            <th><?php echo e(__('messages.User')); ?></th>
                            <th><?php echo e(__('messages.Driver')); ?></th>
                            <th><?php echo e(__('messages.Service')); ?></th>
                            <th><?php echo e(__('messages.Price')); ?></th>
                            <th><?php echo e(__('messages.Status')); ?></th>
                            <th><?php echo e(__('messages.Payment')); ?></th>
                            <th><?php echo e(__('messages.Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($order->id); ?></td>
                            <td><?php echo e($order->created_at->format('Y-m-d H:i')); ?></td>
                            <td>
                                <?php if($order->user): ?>
                                <a href="<?php echo e(route('users.show', $order->user_id)); ?>">
                                    <?php echo e($order->user->name); ?>

                                </a>
                                <?php else: ?>
                                <?php echo e(__('messages.Not_Available')); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($order->driver): ?>
                                <a href="<?php echo e(route('drivers.show', $order->driver_id)); ?>">
                                    <?php echo e($order->driver->name); ?>

                                </a>
                                <?php else: ?>
                                <?php echo e(__('messages.Not_Assigned')); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($order->service): ?>
                                <a href="<?php echo e(route('services.show', $order->service_id)); ?>">
                                    <?php echo e($order->service->name_en); ?>

                                </a>
                                <?php else: ?>
                                <?php echo e(__('messages.Not_Available')); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo e($order->total_price_after_discount); ?>

                                <?php if($order->discount_value > 0): ?>
                                <span class="badge badge-info">
                                    -<?php echo e($order->getFormattedDiscount()); ?> (<?php echo e($order->getDiscountPercentage()); ?>%)
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($order->getStatusClass()); ?>">
                                    <?php echo e($order->getStatusText()); ?>

                                </span>
                            </td>
                            <td>
                                <div>
                                    <span class="badge badge-primary"><?php echo e($order->getPaymentMethodText()); ?></span>
                                </div>
                                <div class="mt-1">
                                    <span class="badge badge-<?php echo e($order->getPaymentStatusClass()); ?>">
                                        <?php echo e($order->getPaymentStatusText()); ?>

                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="event.preventDefault(); if(confirm('<?php echo e(__('messages.Delete_Confirm')); ?>')) document.getElementById('delete-form-<?php echo e($order->id); ?>').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-form-<?php echo e($order->id); ?>" action="<?php echo e(route('orders.destroy', $order->id)); ?>" method="POST" style="display: none;">
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
        $('#dataTable').DataTable({
            "order": [[0, "desc"]]
        });
        
        // Date validation
        $('#date_to').on('change', function() {
            var startDate = $('#date_from').val();
            var endDate = $(this).val();
            
            if (startDate && endDate && startDate > endDate) {
                alert("<?php echo e(__('messages.Date_Range_Error')); ?>");
                $(this).val('');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\taksi\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>