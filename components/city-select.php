<?php
    // @param   String $default     default selection

    if(!isset($default))
        $default = '';
?>
<select class="form-select" name="city">
    <option value="no-selection"><?= $MSG['city-no-selection']; ?></option>
<?php
    foreach($CITY as $key => $val):
?>
    <option value="<?= $key; ?>"<?= $default === $key ? ' selected' : ''; ?>><?= $val; ?></option>
<?php
    endforeach;
?>
</select>
