<?php
    // @param   String $default     default selection
?>
<select class="form-select" name="city">
<?php
    foreach($CITY as $key => $val):
?>
    <option value="<?= $key; ?>"<?= $default === $key ? ' selected' : ''; ?>><?= $val; ?></option>
<?php
    endforeach;
?>
</select>
