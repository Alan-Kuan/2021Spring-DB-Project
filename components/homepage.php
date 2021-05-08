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
            <span class="input-group-text"><?= $TEXT['mask-price']; ?></span>
            <input class="form-control" type="number" id="price_lower_bound" name="price_lower_bound"
                   value="<?= isset($_GET['price_lower_bound']) ? $_GET['price_lower_bound'] : 0; ?>" min="0" />
            <span class="input-group-text">~</span>
            <input class="form-control" type="number" id="price_upper_bound" name="price_upper_bound"
                   value="<?= isset($_GET['price_upper_bound']) ? $_GET['price_upper_bound'] : 1000; ?>" min="0" />
        </div>
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask-amount']; ?></span>
            <?php include './components/amount-select.php'; ?>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" name="work-shop"
             <?= isset($_GET['work-shop']) && $_GET['work-shop'] === 'on' ? 'checked' : ''; ?> />
            <label class="form-check-label" for="flexSwitchCheckDefault">
                <?= $MSG['only-shops-I-work']; ?>
            </label>
        </div>
        <div class="w-75 mt-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-outline-dark">
                <i class="bi bi-search"></i>
            </button>
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
            sendPopupAndGoto($MSG['invalid-price-range'], 'home.php');
            exit();
        }

        if(!validateCity($city)) {
            header('Location: home.php');
            exit();
        }


        $v_amount = validateAmount($amount_range);
        if($v_amount == -1) {
            header('Location: home.php');
            exit();
        }
        else {
            $amount_range = $v_amount;
        }

        if(isset($_GET['work-shop']) && !validateWorkShop($_GET['work-shop'])) {
            header('Location: home.php');
            exit();
        }

        if(isset($_GET['work-shop']) && $_GET['work-shop'] === 'on') {

            if(isShopkeeper($user_name)) {
                if($amount_range == 101) {
                    $stmt = $conn->prepare("SELECT * FROM shops s, users u
                                            WHERE u.username = :user_name
                                            AND s.shopkeeper_id = u.UID
                                            AND s.city = :city
                                            AND s.mask_price BETWEEN :price_lower_bound AND :price_upper_bound
                                            AND s.mask_amount >= :amount_range
                                            AND UPPER(shop_name) LIKE UPPER(:name);");
                }
                else {
                    $stmt = $conn->prepare("SELECT * FROM shops s, users u
                                            WHERE u.username = :user_name
                                            AND s.shopkeeper_id = u.UID
                                            AND s.city = :city
                                            AND s.mask_price BETWEEN :price_lower_bound AND :price_upper_bound
                                            AND s.mask_amount <= :amount_range
                                            AND UPPER(shop_name) LIKE UPPER(:name);");
                }

                $stmt->execute(array(
                    'name' => $shop_name,
                    'user_name' => $user_name,
                    'city' => $city,
                    'price_lower_bound' => $price_lower_bound,
                    'price_upper_bound' => $price_upper_bound,
                    'amount_range' => $amount_range
                ));

                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($shop_arr, array(
                        'shop_name' => $row['shop_name'],
                        'city' => $row['city'],
                        'mask_price' => $row['mask_price'],
                        'mask_amount' => $row['mask_amount'])
                    );
                }

            }

            if($amount_range == 101) {
                $stmt = $conn->prepare("SELECT * FROM shops s, employee_shop e_s, users u
                                        WHERE u.username = :user_name
                                        AND e_s.employee_id = u.UID
                                        AND e_s.shop_id = s.SID
                                        AND s.city = :city
                                        AND s.mask_price BETWEEN :price_lower_bound AND :price_upper_bound
                                        AND s.mask_amount >= :amount_range
                                        AND UPPER(shop_name) LIKE UPPER(:name);");
            }
            else {
                $stmt = $conn->prepare("SELECT * FROM shops s, employee_shop e_s, users u
                                        WHERE u.username = :user_name
                                        AND e_s.employee_id = u.UID
                                        AND e_s.shop_id = s.SID
                                        AND s.city = :city
                                        AND s.mask_price BETWEEN :price_lower_bound AND :price_upper_bound
                                        AND s.mask_amount <= :amount_range
                                        AND UPPER(shop_name) LIKE UPPER(:name);");
            }

            $stmt->execute(array(
                'name' => $shop_name,
                'user_name' => $user_name,
                'city' => $city,
                'price_lower_bound' => $price_lower_bound,
                'price_upper_bound' => $price_upper_bound,
                'amount_range' => $amount_range
            ));
            
        }
        else {

            if($amount_range == 101) {
                $stmt = $conn->prepare("SELECT * FROM shops WHERE city = :city
                                                        AND mask_price BETWEEN :price_lower_bound AND :price_upper_bound
                                                        AND mask_amount >= :amount_range
                                                        AND UPPER(shop_name) LIKE UPPER(:name);");
            }
            else {
                $stmt = $conn->prepare("SELECT * FROM shops WHERE city = :city
                                                        AND mask_price BETWEEN :price_lower_bound AND :price_upper_bound
                                                        AND mask_amount <= :amount_range
                                                        AND UPPER(shop_name) LIKE UPPER(:name);"); 
            }

            $stmt->execute(array(
                'name' => $shop_name,
                'city' => $city,
                'price_lower_bound' => $price_lower_bound,
                'price_upper_bound' => $price_upper_bound,
                'amount_range' => $amount_range
            ));

        }

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

        if(!preg_match('/^[+-]?[0-9]*$/', $price_lower_bound))
            return false;
    
        if(!preg_match('/^[+-]?[0-9]*$/', $price_upper_bound))
            return false;

        if(intval($price_lower_bound, 10) < 0 || intval($price_upper_bound, 10) < 0)
            return false;
        
        return $price_lower_bound <= $price_upper_bound;

    }

    function validateCity($city) {

        $cities = array(
            'taipei-city',
            'new-taipei-city',
            'keelung-city',
            'taoyuan-city',
            'hsinchu-city',
            'hsinchu-county',
            'miaoli-county',
            'taichung-city',
            'changhua-county',
            'nantou-county',
            'yunlin-county',
            'chiayi-city',
            'chiayi-county',
            'tainan-city',
            'kaohsiung-city',
            'pingtung-county',
            'yilan-county',
            'hualien-county',
            'taitung-county',
            'penghu-county',
            'kinmen-county',
            'lienchiang-county'
        );

        return in_array($city, $cities);

    }

    function validateAmount($amount_range) {

        if(!in_array($amount_range, array('out-of-stock', 'few', 'sufficient')))
            return -1;
        
        if($amount_range === 'out-of-stock')
            return 101;
        else if($amount_range === 'few')
            return 100;
        else
            return 0;

    }

    function validateWorkShop($work_shop) {
        return ($work_shop == 'on' || $work_shop == 'off');
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
                <th><?= $TEXT['mask-price']; ?></th>
                <th><?= $TEXT['mask-amount']; ?></th>
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
