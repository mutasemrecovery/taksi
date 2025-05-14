<?php $__env->startSection('title'); ?>
notifications
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> Add New notifications   </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


<div class="row justify-content-center">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('notifications.send')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-group mt-0">
                        <label for="title">Title</label>
                        <input type="text" class="form-control <?php if($errors->has('title')): ?> is-invalid <?php endif; ?>" id="title" name="title" value="<?php echo e(old('title')); ?>">
                        <?php if($errors->has('title')): ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($errors->first('title')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea name="body" id="body" class="form-control <?php if($errors->has('body')): ?> is-invalid <?php endif; ?>"><?php echo e(old('body')); ?></textarea>
                        <?php if($errors->has('body')): ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($errors->first('body')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="type">Notification Type</label>
                        <select name="type" id="type" class="form-control <?php if($errors->has('type')): ?> is-invalid <?php endif; ?>" onchange="toggleUserField()">
                            <option value="0">All Users</option>
                            <option value="1">All Regular Users</option>
                            <option value="2">All Parents</option>
                            <option value="3">All Teachers</option>
                            <option value="4">Specific User</option>
                        </select>
                        <?php if($errors->has('type')): ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($errors->first('type')); ?></strong>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" id="userField" style="display: none;">
                        <label for="user_id">Select User</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Select a User</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Send Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




            </div>




        </div>
      </div>






<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

<script>
    function toggleUserField() {
        var type = document.getElementById("type").value;
        var userField = document.getElementById("userField");
        if (type == "4") {
            userField.style.display = "block";
        } else {
            userField.style.display = "none";
        }
    }
</script>


<?php $__env->stopSection(); ?>







<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\uber\resources\views/admin/notifications/create.blade.php ENDPATH**/ ?>