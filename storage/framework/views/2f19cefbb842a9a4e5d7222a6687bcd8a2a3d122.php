<?php $__env->startSection('title', __('messages.View_Driver')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('messages.View_Driver')); ?></h1>
        <div>
            <a href="<?php echo e(route('drivers.edit', $driver->id)); ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> <?php echo e(__('messages.Edit')); ?>

            </a>
            <a href="<?php echo e(route('drivers.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.Back_to_List')); ?>

            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- Driver Profile -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Profile')); ?></h6>
                </div>
                <div class="card-body text-center">
                    <?php if($driver->photo): ?>
                    <img src="<?php echo e(asset('assets/admin/uploads/' . $driver->photo)); ?>" alt="<?php echo e($driver->name); ?>" class="img-profile rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                    <img src="<?php echo e(asset('assets/admin/img/undraw_profile.svg')); ?>" alt="No Image" class="img-profile rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php endif; ?>
                    <h4 class="font-weight-bold"><?php echo e($driver->name); ?></h4>
                    <p class="text-muted mb-1"><?php echo e($driver->phone); ?></p>
                    <?php if($driver->email): ?>
                    <p class="text-muted mb-1"><?php echo e($driver->email); ?></p>
                    <?php endif; ?>
                    <div class="mt-3">
                        <?php if($driver->activate == 1): ?>
                        <span class="badge badge-success px-3 py-2"><?php echo e(__('messages.Active')); ?></span>
                        <?php else: ?>
                        <span class="badge badge-danger px-3 py-2"><?php echo e(__('messages.Inactive')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Car Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Car_Information')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if($driver->photo_of_car): ?>
                        <img src="<?php echo e(asset('assets/admin/uploads/' . $driver->photo_of_car)); ?>" alt="Car Photo" class="img-fluid rounded mb-3" style="max-height: 150px;">
                        <?php else: ?>
                        <div class="bg-light rounded py-5 mb-3">
                            <i class="fas fa-car fa-3x text-gray-300"></i>
                            <p class="mt-2 text-gray-500"><?php echo e(__('messages.No_Car_Photo')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="40%"><?php echo e(__('messages.Car_Model')); ?></th>
                                    <td><?php echo e($driver->model ?? __('messages.Not_Available')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Production_Year')); ?></th>
                                    <td><?php echo e($driver->production_year ?? __('messages.Not_Available')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Color')); ?></th>
                                    <td><?php echo e($driver->color ?? __('messages.Not_Available')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Plate_Number')); ?></th>
                                    <td><?php echo e($driver->plate_number ?? __('messages.Not_Available')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Driver Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Driver_Details')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%"><?php echo e(__('messages.ID')); ?></th>
                                    <td><?php echo e($driver->id); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Name')); ?></th>
                                    <td><?php echo e($driver->name); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Phone')); ?></th>
                                    <td><?php echo e($driver->phone); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Email')); ?></th>
                                    <td><?php echo e($driver->email ?? __('messages.Not_Available')); ?></td>
                                </tr>
                                <tr>
                                   
                                      <th> <?php echo e(__('messages.Options')); ?></th>
                                    <td>
                                       <?php $__currentLoopData = $driver->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           <span class="badge badge-success m-1">
                                               <?php echo e($option->name); ?> 
                                           </span>
                                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                         
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Balance')); ?></th>
                                    <td><?php echo e($driver->balance); ?></td>
                                </tr>
                             
                                <tr>
                                    <th><?php echo e(__('messages.Status')); ?></th>
                                    <td>
                                        <?php if($driver->activate == 1): ?>
                                        <span class="badge badge-success"><?php echo e(__('messages.Active')); ?></span>
                                        <?php else: ?>
                                        <span class="badge badge-danger"><?php echo e(__('messages.Inactive')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Created_At')); ?></th>
                                    <td><?php echo e($driver->created_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Updated_At')); ?></th>
                                    <td><?php echo e($driver->updated_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Documents -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.Documents')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <?php echo e(__('messages.Driving_License_Front')); ?>

                                </div>
                                <div class="card-body text-center">
                                    <?php if($driver->driving_license_front): ?>
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $driver->driving_license_front)); ?>" alt="Driving License Front" class="img-fluid rounded" style="max-height: 200px;">
                                    <a href="<?php echo e(asset('assets/admin/uploads/' . $driver->driving_license_front)); ?>" target="_blank" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-eye"></i> <?php echo e(__('messages.View_Full_Size')); ?>

                                    </a>
                                    <?php else: ?>
                                    <div class="bg-light rounded py-5">
                                        <i class="fas fa-id-card fa-3x text-gray-300"></i>
                                        <p class="mt-2 text-gray-500"><?php echo e(__('messages.Not_Available')); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <?php echo e(__('messages.Driving_License_Back')); ?>

                                </div>
                                <div class="card-body text-center">
                                    <?php if($driver->driving_license_back): ?>
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $driver->driving_license_back)); ?>" alt="Driving License Back" class="img-fluid rounded" style="max-height: 200px;">
                                    <a href="<?php echo e(asset('assets/admin/uploads/' . $driver->driving_license_back)); ?>" target="_blank" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-eye"></i> <?php echo e(__('messages.View_Full_Size')); ?>

                                    </a>
                                    <?php else: ?>
                                    <div class="bg-light rounded py-5">
                                        <i class="fas fa-id-card fa-3x text-gray-300"></i>
                                        <p class="mt-2 text-gray-500"><?php echo e(__('messages.Not_Available')); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <?php echo e(__('messages.Car_License_Front')); ?>

                                </div>
                                <div class="card-body text-center">
                                    <?php if($driver->car_license_front): ?>
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $driver->car_license_front)); ?>" alt="Car License Front" class="img-fluid rounded" style="max-height: 200px;">
                                    <a href="<?php echo e(asset('assets/admin/uploads/' . $driver->car_license_front)); ?>" target="_blank" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-eye"></i> <?php echo e(__('messages.View_Full_Size')); ?>

                                    </a>
                                    <?php else: ?>
                                    <div class="bg-light rounded py-5">
                                        <i class="fas fa-file-alt fa-3x text-gray-300"></i>
                                        <p class="mt-2 text-gray-500"><?php echo e(__('messages.Not_Available')); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <?php echo e(__('messages.Car_License_Back')); ?>

                                </div>
                                <div class="card-body text-center">
                                    <?php if($driver->car_license_back): ?>
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $driver->car_license_back)); ?>" alt="Car License Back" class="img-fluid rounded" style="max-height: 200px;">
                                    <a href="<?php echo e(asset('assets/admin/uploads/' . $driver->car_license_back)); ?>" target="_blank" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-eye"></i> <?php echo e(__('messages.View_Full_Size')); ?>

                                    </a>
                                    <?php else: ?>
                                    <div class="bg-light rounded py-5">
                                        <i class="fas fa-file-alt fa-3x text-gray-300"></i>
                                        <p class="mt-2 text-gray-500"><?php echo e(__('messages.Not_Available')); ?></p>
                                    </div>
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\taksi\resources\views/admin/drivers/show.blade.php ENDPATH**/ ?>