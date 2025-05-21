


<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Withdrawal Request History</h2>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Processed By</th>
                <th>Note</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $processedRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                    <td><?php echo e($request->amount); ?></td>
                    <td>
                        <?php if($request->status == 2): ?>
                            <span class="badge badge-success">Approved</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($request->admin->name ?? 'N/A'); ?></td>
                    <td><?php echo e($request->note); ?></td>
                    <td><?php echo e($request->updated_at->format('Y-m-d H:i')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    
    <?php echo e($processedRequests->links()); ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\uber\resources\views/admin/withdrawals/history.blade.php ENDPATH**/ ?>