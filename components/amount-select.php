<select class="form-select" aria-label="amount select" name="amount_range">
    <option value='out-of-stock' <?= isset($_GET['amount_range']) && ($_GET['amount_range'] === 'out-of-stock') ? 'selected' : ''; ?>>
        <?= $TEXT['out-of-stock']; ?>
    </option>
    <option value='few' <?= isset($_GET['amount_range']) && ($_GET['amount_range'] === 'few') ? 'selected' : ''; ?>>
        <?= $TEXT['few']; ?>
    </option>
    <option value='sufficient' <?= isset($_GET['amount_range']) && ($_GET['amount_range'] === 'sufficient') ? 'selected' : ''; ?>>
        <?= $TEXT['sufficient']; ?>
    </option>
</select>
