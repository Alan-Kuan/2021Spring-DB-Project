<div>
    <p><?= $MSG['not-shopkeeper']; ?></p>

    <form id="register-shop" action="register-shop.php" method="post">
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['shop_name']; ?></span>
            <input id="shop_name" class="form-control" name="shop_name" type="text" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['city']; ?></span>
            <?php include './components/city-select.php'; ?>
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask-price']; ?></span>
            <input id="mask_price" class="form-control" name="mask_price" type="number" min="0" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask-amount']; ?></span>
            <input id="mask_amount" class="form-control" name="mask_amount" type="number" min="0" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="w-75 mt-2 d-flex justify-content-end">
            <input class="btn btn-primary" type="submit" value="<?= $TEXT['submit']; ?>" />
        </div>
    </form>
</div>
