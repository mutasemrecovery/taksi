<?php $__env->startSection('title', __('messages.View_Order')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.View_Order')); ?> #<?php echo e($order->id); ?></h1>
        <div>
            <a href="<?php echo e(route('orders.edit', $order->id)); ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> <?php echo e(__('messages.Edit')); ?>

            </a>
            <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back_to_List')); ?>

            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- Order Status Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Order_Status')); ?></h6>
            <div>
                <span class="badge badge-<?php echo e($order->getStatusClass()); ?> px-3 py-2">
                    <?php echo e($order->getStatusText()); ?>

                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <!-- Status Progress -->
                    <div class="position-relative mb-4">
                        <div class="progress" style="height: 3px;">
                            <?php if($order->status >= 1 && $order->status <= 5): ?>
                                <?php
                                    $progressPercentage = ($order->status - 1) / 4 * 100;
                                ?>
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($progressPercentage); ?>%" 
                                     aria-valuenow="<?php echo e($progressPercentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            <?php elseif($order->status == 6 || $order->status == 7): ?>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" 
                                     aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="text-center" style="width: 20%;">
                                <div class="rounded-circle <?php echo e($order->status >= 1 ? 'bg-success' : 'bg-secondary'); ?> text-white d-inline-flex justify-content-center align-items-center" style="width: 30px; height: 30px; position: relative; top: -15px;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="small"><?php echo e(__('messages.Pending')); ?></div>
                            </div>
                            <div class="text-center" style="width: 20%;">
                                <div class="rounded-circle <?php echo e($order->status >= 2 ? 'bg-success' : 'bg-secondary'); ?> text-white d-inline-flex justify-content-center align-items-center" style="width: 30px; height: 30px; position: relative; top: -15px;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="small"><?php echo e(__('messages.Accepted')); ?></div>
                            </div>
                            <div class="text-center" style="width: 20%;">
                                <div class="rounded-circle <?php echo e($order->status >= 3 ? 'bg-success' : 'bg-secondary'); ?> text-white d-inline-flex justify-content-center align-items-center" style="width: 30px; height: 30px; position: relative; top: -15px;">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="small"><?php echo e(__('messages.On_Way')); ?></div>
                            </div>
                            <div class="text-center" style="width: 20%;">
                                <div class="rounded-circle <?php echo e($order->status >= 4 ? 'bg-success' : 'bg-secondary'); ?> text-white d-inline-flex justify-content-center align-items-center" style="width: 30px; height: 30px; position: relative; top: -15px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="small"><?php echo e(__('messages.In_Progress')); ?></div>
                            </div>
                            <div class="text-center" style="width: 20%;">
                                <div class="rounded-circle <?php echo e($order->status >= 5 ? 'bg-success' : 'bg-secondary'); ?> text-white d-inline-flex justify-content-center align-items-center" style="width: 30px; height: 30px; position: relative; top: -15px;">
                                    <i class="fas fa-flag-checkered"></i>
                                </div>
                                <div class="small"><?php echo e(__('messages.Delivered')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <!-- Update Status Form -->
                    <form action="<?php echo e(route('orders.updateStatus', $order->id)); ?>" method="POST" class="w-100">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="status"><?php echo e(__('messages.Change_Status')); ?></label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" <?php echo e($order->status == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Pending')); ?></option>
                                <option value="2" <?php echo e($order->status == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Accepted')); ?></option>
                                <option value="3" <?php echo e($order->status == 3 ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Going_To_User')); ?></option>
                                <option value="4" <?php echo e($order->status == 4 ? 'selected' : ''); ?>><?php echo e(__('messages.User_With_Driver')); ?></option>
                                <option value="5" <?php echo e($order->status == 5 ? 'selected' : ''); ?>><?php echo e(__('messages.Delivered')); ?></option>
                                <option value="6" <?php echo e($order->status == 6 ? 'selected' : ''); ?>><?php echo e(__('messages.User_Cancelled')); ?></option>
                                <option value="7" <?php echo e($order->status == 7 ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Cancelled')); ?></option>
                            </select>
                        </div>
                        <div class="form-group cancel-reason-container" style="display: <?php echo e(in_array($order->status, [6, 7]) ? 'block' : 'none'); ?>;">
                            <label for="reason_for_cancel"><?php echo e(__('messages.Cancellation_Reason')); ?></label>
                            <textarea class="form-control" id="reason_for_cancel" name="reason_for_cancel" rows="2"><?php echo e($order->reason_for_cancel); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> <?php echo e(__('messages.Update_Status')); ?>

                        </button>
                    </form>
                </div>
            </div>

            <?php if($order->isCancelled() && $order->reason_for_cancel): ?>
            <div class="alert alert-danger mt-3">
                <strong><?php echo e(__('messages.Cancellation_Reason')); ?>:</strong> <?php echo e($order->reason_for_cancel); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Order_Details')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold"><?php echo e(__('messages.Pickup_Location')); ?></h5>
                            <p><?php echo e($order->pick_name); ?></p>
                            <small class="text-muted"><?php echo e(__('messages.Coordinates')); ?>: <?php echo e($order->pick_lat); ?>, <?php echo e($order->pick_lng); ?></small>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold"><?php echo e(__('messages.Dropoff_Location')); ?></h5>
                            <p><?php echo e($order->drop_name); ?></p>
                            <small class="text-muted"><?php echo e(__('messages.Coordinates')); ?>: <?php echo e($order->drop_lat); ?>, <?php echo e($order->drop_lng); ?></small>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="m-0 font-weight-bold"><?php echo e(__('messages.Route_Information')); ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <h6><?php echo e(__('messages.Distance')); ?></h6>
                                    <h3 class="text-primary"><?php echo e($order->getDistance()); ?> <?php echo e(__('messages.KM')); ?></h3>
                                </div>
                                <div class="col-md-8">
                                    <div id="map" style="height: 200px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%"><?php echo e(__('messages.ID')); ?></th>
                                    <td><?php echo e($order->id); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Service')); ?></th>
                                    <td>
                                        <?php if($order->service): ?>
                                        <a href="<?php echo e(route('services.show', $order->service_id)); ?>">
                                            <?php echo e($order->service->name_en); ?> (<?php echo e($order->service->name_ar); ?>)
                                        </a>
                                        <?php else: ?>
                                        <?php echo e(__('messages.Not_Available')); ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Created_At')); ?></th>
                                    <td><?php echo e($order->created_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Updated_At')); ?></th>
                                    <td><?php echo e($order->updated_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pricing Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Pricing_Details')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <h6 class="card-title mb-0"><?php echo e(__('messages.Original_Price')); ?></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <h6 class="mb-0"><?php echo e($order->total_price_before_discount); ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if($order->discount_value > 0): ?>
                            <div class="card mb-3 bg-light">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <h6 class="card-title mb-0 text-success"><?php echo e(__('messages.Discount')); ?></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <h6 class="mb-0 text-success">-<?php echo e($order->discount_value); ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="card mb-3 bg-primary text-white">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <h6 class="card-title mb-0"><?php echo e(__('messages.Final_Price')); ?></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <h6 class="mb-0"><?php echo e($order->total_price_after_discount); ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <h6 class="card-title mb-0"><?php echo e(__('messages.Driver_Earning')); ?></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <h6 class="mb-0"><?php echo e($order->net_price_for_driver); ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <h6 class="card-title mb-0"><?php echo e(__('messages.Admin_Commission')); ?></h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <h6 class="mb-0"><?php echo e($order->commision_of_admin); ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong><?php echo e(__('messages.Payment_Method')); ?></strong>
                                            <div class="mt-1">
                                                <span class="badge badge-primary px-3 py-2">
                                                    <?php echo e($order->getPaymentMethodText()); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <strong><?php echo e(__('messages.Payment_Status')); ?></strong>
                                            <div class="mt-1">
                                                <span class="badge badge-<?php echo e($order->getPaymentStatusClass()); ?> px-3 py-2">
                                                    <?php echo e($order->getPaymentStatusText()); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <form action="<?php echo e(route('orders.updatePaymentStatus', $order->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="status_payment"><?php echo e(__('messages.Update_Payment_Status')); ?></label>
                                <select class="form-control" id="status_payment" name="status_payment">
                                    <option value="1" <?php echo e($order->status_payment == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Pending')); ?></option>
                                    <option value="2" <?php echo e($order->status_payment == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Paid')); ?></option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo e(__('messages.Update_Payment_Status')); ?>

                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- User Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.User_Information')); ?></h6>
                </div>
                <div class="card-body">
                    <?php if($order->user): ?>
                    <div class="text-center mb-3">
                        <?php if($order->user->photo): ?>
                        <img src="<?php echo e(asset('assets/admin/uploads/' . $order->user->photo)); ?>" alt="<?php echo e($order->user->name); ?>" class="img-profile rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                        <img src="<?php echo e(asset('assets/admin/img/undraw_profile.svg')); ?>" alt="No Image" class="img-profile rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php endif; ?>
                        <h5><?php echo e($order->user->name); ?></h5>
                    </div>
                    
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo e(__('messages.Phone')); ?>

                            <span><?php echo e($order->user->phone); ?></span>
                        </li>
                        <?php if($order->user->email): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo e(__('messages.Email')); ?>

                            <span><?php echo e($order->user->email); ?></span>
                        </li>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo e(__('messages.Wallet_Balance')); ?>

                            <span class="badge badge-primary px-3 py-2"><?php echo e($order->user->balance); ?></span>
                        </li>
                    </ul>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('users.show', $order->user_id)); ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-user"></i> <?php echo e(__('messages.View_Profile')); ?>

                        </a>
                        <a href="<?php echo e(route('orders.userOrders', $order->user_id)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-list"></i> <?php echo e(__('messages.View_Orders')); ?>

                        </a>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <?php echo e(__('messages.User_Not_Available')); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Driver Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Driver_Information')); ?></h6>
                </div>
                <div class="card-body">
                    <?php if($order->driver): ?>
                    <div class="text-center mb-3">
                        <?php if($order->driver->photo): ?>
                        <img src="<?php echo e(asset('assets/admin/uploads/' . $order->driver->photo)); ?>" alt="<?php echo e($order->driver->name); ?>" class="img-profile rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                        <img src="<?php echo e(asset('assets/admin/img/undraw_profile.svg')); ?>" alt="No Image" class="img-profile rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php endif; ?>
                        <h5><?php echo e($order->driver->name); ?></h5>
                    </div>
                    
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo e(__('messages.Phone')); ?>

                            <span><?php echo e($order->driver->phone); ?></span>
                        </li>
                        <?php if($order->driver->email): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo e(__('messages.Email')); ?>

                            <span><?php echo e($order->driver->email); ?></span>
                        </li>
                        <?php endif; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo e(__('messages.Wallet_Balance')); ?>

                            <span class="badge badge-primary px-3 py-2"><?php echo e($order->driver->balance); ?></span>
                        </li>
                    </ul>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('drivers.show', $order->driver_id)); ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-user"></i> <?php echo e(__('messages.View_Profile')); ?>

                        </a>
                        <a href="<?php echo e(route('orders.driverOrders', $order->driver_id)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-list"></i> <?php echo e(__('messages.View_Orders')); ?>

                        </a>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <?php echo e(__('messages.No_Driver_Assigned')); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    // Show/hide cancellation reason field based on status
    $(document).ready(function() {
        $('#status').on('change', function() {
            var status = $(this).val();
            if (status == '6' || status == '7') {
                $('.cancel-reason-container').show();
            } else {
                $('.cancel-reason-container').hide();
            }
        });
        
        // Initialize map if Google Maps API is loaded
        if (typeof google !== 'undefined') {
            initMap();
        }
    });
    
    // Initialize map to show route
    function initMap() {
        var pickupLat = <?php echo e($order->pick_lat); ?>;
        var pickupLng = <?php echo e($order->pick_lng); ?>;
        var dropLat = <?php echo e($order->drop_lat); ?>;
        var dropLng = <?php echo e($order->drop_lng); ?>;
        
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: {lat: (pickupLat + dropLat) / 2, lng: (pickupLng + dropLng) / 2}
        });
        
        var pickupMarker = new google.maps.Marker({
            position: {lat: pickupLat, lng: pickupLng},
            map: map,
            title: '<?php echo e($order->pick_name); ?>',
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }
        });
        
        var dropMarker = new google.maps.Marker({
            position: {lat: dropLat, lng: dropLng},
            map: map,
            title: '<?php echo e($order->drop_name); ?>',
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });
        
        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#4e73df',
                strokeWeight: 5
            }
        });
        
        directionsRenderer.setMap(map);
        
        var request = {
            origin: {lat: pickupLat, lng: pickupLng},
            destination: {lat: dropLat, lng: dropLng},
            travelMode: 'DRIVING'
        };
        
        directionsService.route(request, function(result, status) {
            if (status == 'OK') {
                directionsRenderer.setDirections(result);
            }
        });
    }
</script>

<!-- Optional: Load Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\taksi\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>