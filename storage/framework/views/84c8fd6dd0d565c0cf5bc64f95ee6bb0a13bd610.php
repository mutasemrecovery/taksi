<?php $__env->startSection('title', __('messages.Edit_Order')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.Edit_Order')); ?> #<?php echo e($order->id); ?></h1>
        <div>
            <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn btn-info">
                <i class="fas fa-eye"></i> <?php echo e(__('messages.View')); ?>

            </a>
            <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back_to_List')); ?>

            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Order_Details')); ?></h6>
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

            <form action="<?php echo e(route('orders.update', $order->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- User and Service Selection -->
                        <div class="form-group">
                            <label for="user_id"><?php echo e(__('messages.User')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="user_id" name="user_id" required>
                                <option value=""><?php echo e(__('messages.Select_User')); ?></option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id', $order->user_id) == $user->id ? 'selected' : ''); ?>>
                                    <?php echo e($user->name); ?> (<?php echo e($user->phone); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="driver_id"><?php echo e(__('messages.Driver')); ?></label>
                            <select class="form-control" id="driver_id" name="driver_id">
                                <option value=""><?php echo e(__('messages.Select_Driver')); ?></option>
                                <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($driver->id); ?>" <?php echo e(old('driver_id', $order->driver_id) == $driver->id ? 'selected' : ''); ?>>
                                    <?php echo e($driver->name); ?> (<?php echo e($driver->phone); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="form-text text-muted"><?php echo e(__('messages.Driver_Selection_Info')); ?></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="service_id"><?php echo e(__('messages.Service')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="service_id" name="service_id" required>
                                <option value=""><?php echo e(__('messages.Select_Service')); ?></option>
                                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($service->id); ?>" <?php echo e(old('service_id', $order->service_id) == $service->id ? 'selected' : ''); ?>

                                        data-price="<?php echo e($service->start_price); ?>" data-price-km="<?php echo e($service->price_per_km); ?>" 
                                        data-commission="<?php echo e($service->admin_commision); ?>" data-commission-type="<?php echo e($service->type_of_commision); ?>">
                                    <?php echo e($service->name_en); ?> (<?php echo e($service->name_ar); ?>)
                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <!-- Status and Payment -->
                        <div class="form-group">
                            <label for="status"><?php echo e(__('messages.Status')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="1" <?php echo e(old('status', $order->status) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Pending')); ?></option>
                                <option value="2" <?php echo e(old('status', $order->status) == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Accepted')); ?></option>
                                <option value="3" <?php echo e(old('status', $order->status) == 3 ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Going_To_User')); ?></option>
                                <option value="4" <?php echo e(old('status', $order->status) == 4 ? 'selected' : ''); ?>><?php echo e(__('messages.User_With_Driver')); ?></option>
                                <option value="5" <?php echo e(old('status', $order->status) == 5 ? 'selected' : ''); ?>><?php echo e(__('messages.Delivered')); ?></option>
                                <option value="6" <?php echo e(old('status', $order->status) == 6 ? 'selected' : ''); ?>><?php echo e(__('messages.User_Cancelled')); ?></option>
                                <option value="7" <?php echo e(old('status', $order->status) == 7 ? 'selected' : ''); ?>><?php echo e(__('messages.Driver_Cancelled')); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group cancel-reason-container" style="display: <?php echo e(in_array(old('status', $order->status), [6, 7]) ? 'block' : 'none'); ?>;">
                            <label for="reason_for_cancel"><?php echo e(__('messages.Cancellation_Reason')); ?></label>
                            <textarea class="form-control" id="reason_for_cancel" name="reason_for_cancel" rows="2"><?php echo e(old('reason_for_cancel', $order->reason_for_cancel)); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_method"><?php echo e(__('messages.Payment_Method')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="1" <?php echo e(old('payment_method', $order->payment_method) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Cash')); ?></option>
                                <option value="2" <?php echo e(old('payment_method', $order->payment_method) == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Visa')); ?></option>
                                <option value="3" <?php echo e(old('payment_method', $order->payment_method) == 3 ? 'selected' : ''); ?>><?php echo e(__('messages.Wallet')); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="status_payment"><?php echo e(__('messages.Payment_Status')); ?> <span class="text-danger">*</span></label>
                            <select class="form-control" id="status_payment" name="status_payment" required>
                                <option value="1" <?php echo e(old('status_payment', $order->status_payment) == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Pending')); ?></option>
                                <option value="2" <?php echo e(old('status_payment', $order->status_payment) == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Paid')); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Location Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold"><?php echo e(__('messages.Pickup_Location')); ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="pick_name"><?php echo e(__('messages.Pickup_Name')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pick_name" name="pick_name" value="<?php echo e(old('pick_name', $order->pick_name)); ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pick_lat"><?php echo e(__('messages.Latitude')); ?> <span class="text-danger">*</span></label>
                                            <input type="number" step="any" class="form-control" id="pick_lat" name="pick_lat" value="<?php echo e(old('pick_lat', $order->pick_lat)); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pick_lng"><?php echo e(__('messages.Longitude')); ?> <span class="text-danger">*</span></label>
                                            <input type="number" step="any" class="form-control" id="pick_lng" name="pick_lng" value="<?php echo e(old('pick_lng', $order->pick_lng)); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold"><?php echo e(__('messages.Dropoff_Location')); ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="drop_name"><?php echo e(__('messages.Dropoff_Name')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="drop_name" name="drop_name" value="<?php echo e(old('drop_name', $order->drop_name)); ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="drop_lat"><?php echo e(__('messages.Latitude')); ?> <span class="text-danger">*</span></label>
                                            <input type="number" step="any" class="form-control" id="drop_lat" name="drop_lat" value="<?php echo e(old('drop_lat', $order->drop_lat)); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="drop_lng"><?php echo e(__('messages.Longitude')); ?> <span class="text-danger">*</span></label>
                                            <input type="number" step="any" class="form-control" id="drop_lng" name="drop_lng" value="<?php echo e(old('drop_lng', $order->drop_lng)); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pricing Information -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold"><?php echo e(__('messages.Pricing_Details')); ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_price_before_discount"><?php echo e(__('messages.Original_Price')); ?> <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control" id="total_price_before_discount" name="total_price_before_discount" value="<?php echo e(old('total_price_before_discount', $order->total_price_before_discount)); ?>" required min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="discount_value"><?php echo e(__('messages.Discount')); ?></label>
                                            <input type="number" step="0.01" class="form-control" id="discount_value" name="discount_value" value="<?php echo e(old('discount_value', $order->discount_value)); ?>" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_price_after_discount"><?php echo e(__('messages.Final_Price')); ?> <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control" id="total_price_after_discount" name="total_price_after_discount" value="<?php echo e(old('total_price_after_discount', $order->total_price_after_discount)); ?>" required min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="commision_of_admin"><?php echo e(__('messages.Admin_Commission')); ?> <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control" id="commision_of_admin" name="commision_of_admin" value="<?php echo e(old('commision_of_admin', $order->commision_of_admin)); ?>" required min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="net_price_for_driver"><?php echo e(__('messages.Driver_Earning')); ?> <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="net_price_for_driver" name="net_price_for_driver" value="<?php echo e(old('net_price_for_driver', $order->net_price_for_driver)); ?>" required min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div id="map" style="height: 200px; width: 100%; margin-bottom: 20px;"></div>
                    </div>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo e(__('messages.Update')); ?>

                    </button>
                    <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">
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
        // Initialize map if Google Maps API is loaded
        if (typeof google !== 'undefined') {
            initMap();
        }
        
        // Show/hide cancellation reason field based on status
        $('#status').on('change', function() {
            var status = $(this).val();
            if (status == '6' || status == '7') {
                $('.cancel-reason-container').show();
                $('#reason_for_cancel').prop('required', true);
            } else {
                $('.cancel-reason-container').hide();
                $('#reason_for_cancel').prop('required', false);
            }
        });
        
        // Calculate distance and price when coordinates change
        $('#pick_lat, #pick_lng, #drop_lat, #drop_lng, #service_id').on('change', function() {
            calculateDistance();
        });
        
        // Calculate final price when discount changes
        $('#discount_value').on('change', function() {
            calculatePrices();
        });
    });
    
    // Calculate distance between pickup and dropoff
    function calculateDistance() {
        var pickLat = parseFloat($('#pick_lat').val()) || 0;
        var pickLng = parseFloat($('#pick_lng').val()) || 0;
        var dropLat = parseFloat($('#drop_lat').val()) || 0;
        var dropLng = parseFloat($('#drop_lng').val()) || 0;
        
        if (pickLat && pickLng && dropLat && dropLng) {
            // Haversine formula to calculate distance
            var earthRadius = 6371; // Radius of the earth in km
            var latDelta = deg2rad(dropLat - pickLat);
            var lngDelta = deg2rad(dropLng - pickLng);
            var a = Math.sin(latDelta/2) * Math.sin(latDelta/2) +
                    Math.cos(deg2rad(pickLat)) * Math.cos(deg2rad(dropLat)) *
                    Math.sin(lngDelta/2) * Math.sin(lngDelta/2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            var distance = earthRadius * c;
            
            // Round to 2 decimal places
            distance = Math.round(distance * 100) / 100;
            
            // Calculate prices based on service selected
            var serviceId = $('#service_id').val();
            if (serviceId) {
                var option = $('#service_id option:selected');
                var startPrice = parseFloat(option.data('price')) || 0;
                var pricePerKm = parseFloat(option.data('price-km')) || 0;
                
                var totalBeforeDiscount = startPrice + (pricePerKm * distance);
                $('#total_price_before_discount').val(totalBeforeDiscount.toFixed(2));
                
                calculatePrices();
            }
            
            // Update map if available
            if (typeof google !== 'undefined') {
                updateMap(pickLat, pickLng, dropLat, dropLng);
            }
        }
    }
    
    // Calculate prices after discount and commission
    function calculatePrices() {
        var totalBeforeDiscount = parseFloat($('#total_price_before_discount').val()) || 0;
        var discount = parseFloat($('#discount_value').val()) || 0;
        
        if (discount > totalBeforeDiscount) {
            alert("<?php echo e(__('messages.Discount_Too_High')); ?>");
            $('#discount_value').val(0);
            discount = 0;
        }
        
        var totalAfterDiscount = totalBeforeDiscount - discount;
        $('#total_price_after_discount').val(totalAfterDiscount.toFixed(2));
        
        // Calculate commission and driver earning
        var serviceId = $('#service_id').val();
        if (serviceId) {
            var option = $('#service_id option:selected');
            var commissionValue = parseFloat(option.data('commission')) || 0;
            var commissionType = option.data('commission-type');
            var adminCommission = 0;
            
            if (commissionType == 1) {
                // Fixed amount
                adminCommission = commissionValue;
            } else {
                // Percentage
                adminCommission = (commissionValue / 100) * totalAfterDiscount;
            }
            
            $('#commision_of_admin').val(adminCommission.toFixed(2));
            $('#net_price_for_driver').val((totalAfterDiscount - adminCommission).toFixed(2));
        }
    }
    
    // Helper function to convert degrees to radians
    function deg2rad(deg) {
        return deg * (Math.PI/180);
    }
    
    // Initialize and update map
    var map, pickupMarker, dropMarker, directionsRenderer;
    
    function initMap() {
        var pickLat = <?php echo e($order->pick_lat); ?>;
        var pickLng = <?php echo e($order->pick_lng); ?>;
        var dropLat = <?php echo e($order->drop_lat); ?>;
        var dropLng = <?php echo e($order->drop_lng); ?>;
        
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: {lat: (pickLat + dropLat) / 2, lng: (pickLng + dropLng) / 2}
        });
        
        pickupMarker = new google.maps.Marker({
            position: {lat: pickLat, lng: pickLng},
            map: map,
            title: '<?php echo e($order->pick_name); ?>',
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }
        });
        
        dropMarker = new google.maps.Marker({
            position: {lat: dropLat, lng: dropLng},
            map: map,
            title: '<?php echo e($order->drop_name); ?>',
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });
        
        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#4e73df',
                strokeWeight: 5
            }
        });
        
        directionsRenderer.setMap(map);
        
        // Display route
        var directionsService = new google.maps.DirectionsService();
        var request = {
            origin: {lat: pickLat, lng: pickLng},
            destination: {lat: dropLat, lng: dropLng},
            travelMode: 'DRIVING'
        };
        
        directionsService.route(request, function(result, status) {
            if (status == 'OK') {
                directionsRenderer.setDirections(result);
            }
        });
    }
    
    function updateMap(pickLat, pickLng, dropLat, dropLng) {
        pickupMarker.setPosition({lat: pickLat, lng: pickLng});
        dropMarker.setPosition({lat: dropLat, lng: dropLng});
        
        map.setCenter({
            lat: (pickLat + dropLat) / 2,
            lng: (pickLng + dropLng) / 2
        });
        
        var directionsService = new google.maps.DirectionsService();
        var request = {
            origin: {lat: pickLat, lng: pickLng},
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\taksi\resources\views/admin/orders/edit.blade.php ENDPATH**/ ?>