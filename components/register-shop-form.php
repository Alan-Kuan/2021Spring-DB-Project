<div>
    <p><?= $MSG['not-shopkeeper']; ?></p>

    <form action="register-shop.php" method="post">
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['shop_name']; ?></span>
            <input id="shop_name" class="form-control" name="shop_name" type="text" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['city']; ?></span>
            <?php include './components/city-select.php'; ?>
        </div>
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask_price']; ?></span>
            <input id="mask_price" class="form-control" name="mask_price" type="number" min="0" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask_amount']; ?></span>
            <input id="mask_amount" class="form-control" name="mask_amount" type="number" min="0" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="w-75 mt-2 position-relative">
            <input class="btn btn-primary position-absolute end-0" type="submit" value="<?= $TEXT['submit']; ?>" />
        </div>
    </form>
</div>
