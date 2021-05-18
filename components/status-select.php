<?php
    // @param   String $default     default selection

    if(!isset($default))
        $default = '';
?>
<select class="form-select" name="status">
    <option value="no-selection"><?= $MSG['status-no-selection']; ?></option>
    <option value="pending" <?= $default === 'pending' ? 'selected' : ''; ?>><?= $TEXT['pending']; ?></option>
    <option value="completed" <?= $default === 'completed' ? 'selected' : ''; ?>><?= $TEXT['completed']; ?></option>
    <option value="canceled" <?= $default === 'canceled' ? 'selected' : ''; ?>><?= $TEXT['canceled']; ?></option>
</select>
