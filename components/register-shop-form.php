<div>
    <p><?= $MSG['not-shopkeeper-msg']; ?></p>

    <form action="register-shop.php" method="post">
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $MSG['shop_name']; ?></span>
            <input id="shop_name" class="form-control" name="shop_name" type="text" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $MSG['city']; ?></span>
            <input id="city" class="form-control" name="city" type="text" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $MSG['mask_price']; ?></span>
            <input id="mask_price" class="form-control" name="mask_price" type="number" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="input-group has-validation w-75 mt-2">
            <span class="input-group-text"><?= $MSG['mask_amount']; ?></span>
            <input id="mask_amount" class="form-control" name="mask_amount" type="number" />
            <div class="invalid-feedback">{{ feedback }}</div>
        </div>
        <div class="w-75 mt-2 position-relative">
            <input class="btn btn-primary position-absolute end-0" type="submit" value="<?= $MSG['submit']; ?>" />
        </div>
    </form>
</div>
