<div class="w-75 mt-5 mx-auto">

    <dl class="row">
        <h2><?= $MSG['profile']; ?></h2>
        
        <dd class="col-sm-3"><?= $MSG['username']; ?></dd>
        <dd class="col-sm-9"><?= $_SESSION['Username'] ?></dd>
        
        <dd class="col-sm-3"><?= $MSG['phone_num'] ?></dd>
        <dd class="col-sm-9">Search in DB.</dd>

        <h2><?= $MSG['shop_list']; ?></h2>
        <dd class="col-sm-3"><?= $MSG['shop_name']; ?></dd>
        <dd class="col-sm-9"></dd>

        <dd class="col-sm-3"><?= $MSG['city']; ?></dd>
        <dd class="col-sm-9">
            <select class="form-select" aria-label="city select">
                <option selected>選擇一個城市</option>
                <option value="1">天龍國</option>
                <option value="2">偉大大學城</option>
                <option value="3">其他=w=</option>
            </select>
        </dd>

        <dd class="col-sm-3"><?= $MSG['mask_price']; ?></dd>
        <dd class="col-sm-9"></dd>

        <dd class="col-sm-3"><?= $MSG['mask_amount']; ?></dd>
        <dd class="col-sm-9">
        <select class="form-select" aria-label="amount select">
                <option selected>選擇數量範圍</option>
                <option value="1">售完</option>
                <option value="2">稀少(不足 100)</option>
                <option value="3">充足(100+)</option>
            </select>
        </dd>

    </dl>

    </div> <!-- tab content -->

</div> <!-- rounded box -->
