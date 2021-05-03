<?php
    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    $user_name = $_SESSION['Username'];

    $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);    
    $stmt = $conn->prepare("SELECT phone_num FROM users WHERE username = :username");
    $stmt->execute(array('username' => $user_name));

    $phone_num = "";

    if($stmt->rowCount() === 1)
        $phone_num = $stmt->fetch()['phone_num'];
?>

<div class="mt-3">
    <h2><?= $TEXT['profile']; ?></h2>
    <div class="input-group w-75 mt-2">
        <span class="input-group-text"><?= $TEXT['username']; ?></span>
        <input class="form-control" type="text" value="<?= $_SESSION['Username']; ?>" disabled />
    </div>
    <div class="input-group w-75 mt-2">
        <span class="input-group-text"><?= $TEXT['phone_num']; ?></span>
        <input class="form-control" type="text" value="<?= $phone_num; ?>" disabled />
    </div>
</div>

<div class="mt-3">
    <h2><?= $TEXT['shop_list']; ?></h2>
    <form id="search-shop" method="get">
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['shop_name']; ?></span>
            <input class="form-control" type="text" id="shop_name" name="shop_name"
                   value="<?= isset($_GET['shop_name']) ? $_GET['shop_name'] : ""; ?>" />
        </div>
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['city']; ?></span>
            <?php includeWith('./components/city-select.php', array('default' => isset($_GET['city']) ? $_GET['city'] : 'taipei-city')); ?>
        </div>
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask_price']; ?></span>
            <input class="form-control" type="number" id="price_lower_bound" name="price_lower_bound"
                   value="<?= isset($_GET['price_lower_bound']) ? $_GET['price_lower_bound'] : 0; ?>" min="0" />
            <span class="input-group-text">~</span>
            <input class="form-control" type="number" id="price_upper_bound" name="price_upper_bound"
                   value="<?= isset($_GET['price_upper_bound']) ? $_GET['price_upper_bound'] : 1000; ?>" min="0" />
        </div>
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask_amount']; ?></span>
            <?php include './components/amount-select.php'; ?>
        </div>
        <div class="w-75 mt-2 d-flex justify-content-end">
            <input class="btn btn-primary" type="submit" value="<?= $TEXT['submit']; ?>" />
        </div>
    </form>
</div>

<?php
    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');
    
    $shop_arr = array();

    $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);

    if(!empty($_GET)) {

        $shop_name = "%" . $_GET['shop_name'] . "%";
        $city = $_GET['city'];
        $price_lower_bound = $_GET['price_lower_bound'];
        $price_upper_bound = $_GET['price_upper_bound'];
        $amount_range = $_GET['amount_range'];

        if(!validatePriceRange($price_lower_bound, $price_upper_bound)) {
            sendPopupAndGoto($MSG['invalid-price-range'], 'hemepage.php');
            exit();
        }

        if($amount_range == 101) {
            $stmt = $conn->prepare("SELECT * FROM shops WHERE UPPER(shop_name) LIKE UPPER(:name)
                                                    AND city = :city
                                                    AND mask_price BETWEEN :price_lower_bound AND :price_upper_bound
                                                    AND mask_amount >= :amount_range");
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM shops WHERE UPPER(shop_name) LIKE UPPER(:name)
                                                        AND city = :city
                                                        AND mask_price BETWEEN :price_lower_bound AND :price_upper_bound
                                                        AND mask_amount <= :amount_range"); 
        }

        $stmt->execute(array(
            'name' => $shop_name,
            'city' => $city,
            'price_lower_bound' => $price_lower_bound,
            'price_upper_bound' => $price_upper_bound,
            'amount_range' => $amount_range
        ));

    }
    else {
        $stmt = $conn->prepare("SELECT * FROM shops;");

        $stmt->execute();
    }

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($shop_arr, array(
            'shop_name' => $row['shop_name'],
            'city' => $row['city'],
            'mask_price' => $row['mask_price'],
            'mask_amount' => $row['mask_amount'])
        );
    }

    function validatePriceRange($price_lower_bound, $price_upper_bound) {
        
        if(!preg_match('/^[+-]?[0-9]+$/', $price_lower_bound))
            return false;
    
        if(!preg_match('/^[+-]?[0-9]+$/', $price_upper_bound))
            return false;

        if(intval($price_lower_bound, 10) < 0 || intval($price_upper_bound, 10) < 0)
            return false;
        
        return $price_lower_bound <= $price_upper_bound;

    }
?>


<div class="mt-5">
    <?php
        if(count($shop_arr)):
    ?>
    <table class="table table-striped table-hover w-75 mt-3">
        <thead class="table-dark">
            <tr>
                <th><?= $TEXT['shop_name']; ?></th>
                <th><?= $TEXT['city']; ?></th>
                <th><?= $TEXT['mask_price']; ?></th>
                <th><?= $TEXT['mask_amount']; ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($shop_arr as $shop_info):
        ?>
            <tr>
                <td class="align-middle"><?= $shop_info['shop_name']; ?></td>
                <td class="align-middle"><?= $CITY[$shop_info['city']]; ?></td>
                <td class="align-middle"><?= $shop_info['mask_price']; ?></td>
                <td class="align-middle"><?= $shop_info['mask_amount']; ?></td>
            </tr>
        <?php
            endforeach;
        ?>
        </tbody>
    </table>
    <?php
        else:
    ?>
    <div class="mt-2">
        <p><?= $MSG['no-shop']; ?></p>
    </div>
    <?php
        endif;
    ?>
</div>
