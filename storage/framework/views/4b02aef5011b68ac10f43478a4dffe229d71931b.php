


<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Pending Withdrawal Requests</h2>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($request->id); ?></td>
                    <td>
                        <?php if($request->user_id): ?>
                            User
                        <?php else: ?>
                            Driver
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($request->user_id): ?>
                            <?php echo e($request->user->name); ?>

                        <?php else: ?>
                            <?php echo e($request->driver->name); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($request->user_id): ?>
                            <?php echo e($request->user->phone); ?>

                        <?php else: ?>
                            <?php echo e($request->driver->phone); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e($request->amount); ?></td>
                    <td><?php echo e($request->created_at->format('Y-m-d H:i')); ?></td>
                    <td>
                         <?php if($request->user_id): ?>
                        <a href="<?php echo e(route('admin.withdrawals.history', $request->user->id)); ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                         </a>
                        <?php else: ?>
                         <a href="<?php echo e(route('admin.withdrawals.history', $request->driver->id)); ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                         </a>
                        <?php endif; ?>
                     

                        <form method="POST" action="<?php echo e(route('admin.withdrawals.approve', $request->id)); ?>" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this withdrawal?')">Approve</button>
                        </form>
                        
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal<?php echo e($request->id); ?>">
                            Reject
                        </button>
                        
                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal<?php echo e($request->id); ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST" action="<?php echo e(route('admin.withdrawals.reject', $request->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Withdrawal Request</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="note">Reason for rejection</label>
                                                <textarea class="form-control" id="note" name="note" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    
    <?php echo e($pendingRequests->links()); ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\uber\resources\views/admin/withdrawals/index.blade.php ENDPATH**/ ?>