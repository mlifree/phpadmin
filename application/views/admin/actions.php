<form action="" class="form-inline">
    <select name="actions" class="form-control input-sm">
        <option value=""></option>
        <?php foreach ($actions as $key => $value): ?>
        <option value="<?php echo $value ?>"><?php echo is_int($key) ? $value :$key; ?></option>
        <?php endforeach ?>
        <?php if(!$disable_del):?>
        <option value="delete_selected">删除选中项</option>
        <?php endif ?>
    </select>
    <button class="btn btn-sm btn-primary submit-action">执行</button>
</form>