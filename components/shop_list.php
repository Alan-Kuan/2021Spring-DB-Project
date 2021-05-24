<?php
    $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
    $query_var = array();

    if(!empty($_GET)) {

        if (!isset($_GET['shop_name']) || !isset($_GET['city']) || !isset($_GET['price_lower_bound']) || !isset($_GET['price_upper_bound']) 
            || !isset($_GET['amount_range']) || !isset($_GET['sort-shop_name']) || !isset($_GET['sort-city'])
            || !isset($_GET['sort-mask_price']) || !isset($_GET['sort-mask_amount'])) {    
            sendPopupAndGoto($MSG['invalid-GET-param'], 'home.php');
            exit();
        }
        
        $shop_name = $_GET['shop_name'];
        $city = $_GET['city'];
        $price_lower_bound = $_GET['price_lower_bound'];
        $price_upper_bound = $_GET['price_upper_bound'];
        $amount_range = $_GET['amount_range'];
        $sort_shop_name = $_GET['sort-shop_name'];
        $sort_city = $_GET['sort-city'];
        $sort_mask_price = $_GET['sort-mask_price'];
        $sort_mask_amount = $_GET['sort-mask_amount'];
            
        if(!validateCity($city) || !validateSort($sort_shop_name) || !validateSort($sort_city) || !validateSort($sort_mask_price)
           || !validateSort($sort_mask_amount)) {
            sendPopupAndGoto($MSG['invalid-GET-param'], 'home.php');
            exit();
        }    

        if(!validatePriceRange($price_lower_bound, $price_upper_bound)) {
            sendPopupAndGoto($MSG['invalid-price-range'], 'home.php');
            exit();
        }

        $v_amount = validateAmount($amount_range);
        if($v_amount === -1) {
            sendPopupAndGoto($MSG['invalid-GET-param'], 'home.php');
            exit();
        }
        $amount_range = $v_amount;

        if(isset($_GET['work-shop']) && !validateWorkShop($_GET['work-shop'])) {
            sendPopupAndGoto($MSG['invalid-GET-param'], 'home.php');
            exit();
        }

        $query = "SELECT s.shop_name, s.city, s.mask_price, s.mask_amount, s.SID ";
        $query2 = "SELECT s.shop_name, s.city, s.mask_price, s.mask_amount, s.SID ";

        if(isset($_GET['work-shop']) && $_GET['work-shop'] === 'on') {
            $query .= "FROM shops AS s JOIN employee_shop AS e_s ON (s.SID = e_s.shop_id)
                       WHERE e_s.employee_id = :UID
                       AND s.shop_name LIKE :shop_name ";
            $query2 .= "FROM shops AS s
                        WHERE s.shopkeeper_id = :UID
                        AND s.shop_name LIKE :shop_name ";
            $query_var['UID'] = $_SESSION['UID'];
            $query_var['shop_name'] = '%' . $shop_name . '%';
        } else {
            $query .= "FROM shops AS s
                       WHERE s.shop_name LIKE :shop_name ";
            $query2 .= "FROM shops AS s
                       WHERE s.shop_name LIKE :shop_name ";
            $query_var['shop_name'] = '%' . $shop_name . '%';
        }

        if($city !== "no-selection") {
            $query .= "AND s.city = :city ";
            $query2 .= "AND s.city = :city ";
            $query_var['city'] = $city;
        }

        if($price_lower_bound !== '') {
            $query .= "AND s.mask_price >= :price_lower_bound ";
            $query2 .= "AND s.mask_price >= :price_lower_bound ";
            $query_var['price_lower_bound'] = $price_lower_bound;
        }

        if($price_upper_bound !== '') {
            $query .= "AND s.mask_price <= :price_upper_bound ";
            $query2 .= "AND s.mask_price <= :price_upper_bound ";
            $query_var['price_upper_bound'] = $price_upper_bound;
        }
        
        if($amount_range != 1) {
            $query .= ($amount_range == 101 ? "AND s.mask_amount >= :amount_range"
                                            : "AND s.mask_amount <= :amount_range");
            $query2 .= ($amount_range == 101 ? "AND s.mask_amount >= :amount_range"
                                            : "AND s.mask_amount <= :amount_range");
            $query_var['amount_range'] = $amount_range;
        }

        $query .= " UNION " . $query2;

        $first = true;

        if($sort_shop_name !== 'no-sort') {
            if($first) {
                $query .= " ORDER BY shop_name " . $sort_shop_name;
                $first = false;
            } else {
                $query .= ", shop_name " . $sort_shop_name;
            }
        }
        if($sort_city !== 'no-sort') {
            if($first) {
                $query .= " ORDER BY city " . $sort_city;
                $first = false;
            } else {
                $query .= ", city " . $sort_city;
            }
        }
        if($sort_mask_price !== 'no-sort') {
            if($first) {
                $query .= " ORDER BY mask_price " . $sort_mask_price;
                $first = false;
            } else {
                $query .= ", mask_price " . $sort_mask_price;
            }
        }
        if($sort_mask_amount !== 'no-sort') {
            if($first) {
                $query .= " ORDER BY mask_amount " . $sort_mask_amount;
                $first = false;
            } else {
                $query .= ", mask_amount " . $sort_mask_amount;
            }
        }

    } else {

        $query = "SELECT * FROM shops";

    }

    $stmt = $conn->prepare($query);
    $stmt->execute($query_var);

    $shop_arr = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($shop_arr, array(
            'shop_name' => $row['shop_name'],
            'city' => $row['city'],
            'mask_price' => $row['mask_price'],
            'mask_amount' => $row['mask_amount'],
            'shop_id' => $row['SID'])
        );
    }

    function validatePriceRange($price_lower_bound, $price_upper_bound) {

        if(!preg_match('/^[+-]?[0-9]*$/', $price_lower_bound))
            return false;
    
        if(!preg_match('/^[+-]?[0-9]*$/', $price_upper_bound))
            return false;
        
        if($price_lower_bound === "" && $price_upper_bound === "")
            return true;
        
        if($price_upper_bound === "" && intval($price_lower_bound, 10) >= 0)
            return true;
        
        if($price_lower_bound === "" && intval($price_upper_bound, 10) >= 0)
            return true;

        if(intval($price_lower_bound, 10) < 0 || intval($price_upper_bound, 10) < 0)
            return false;
        
        return $price_lower_bound <= $price_upper_bound;

    }

    function validateCity($city) {

        $cities = array(
            'no-selection',
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

        if(!in_array($amount_range, array('out-of-stock', 'few', 'sufficient', 'all')))
            return -1;
        
        if($amount_range === 'sufficient')
            return 101;
        else if($amount_range === 'few')
            return 100;
        else IF($amount_range === 'out-of-stock')
            return 0;
        else
            return 1;

    }

    function validateWorkShop($work_shop) {
        return ($work_shop === 'on' || $work_shop === 'off');
    }

    function validateSort($status) {
        return in_array($status, array('no-sort', 'asc', 'desc'));
    }
?>

<div class="mt-3">
    <h2><?= $TEXT['shop_list']; ?></h2>
    <form id="search-shop" method="get">
        <input type="hidden" id="sort-shop_name" name="sort-shop_name"
               value="<?= isset($_GET['sort-shop_name']) ? $_GET['sort-shop_name'] : 'no-sort'; ?>" />
        <input type="hidden" id="sort-city" name="sort-city"
               value="<?= isset($_GET['sort-city']) ? $_GET['sort-city'] : 'no-sort'; ?>" />
        <input type="hidden" id="sort-mask_price" name="sort-mask_price"
               value="<?= isset($_GET['sort-mask_price']) ? $_GET['sort-mask_price'] : 'no-sort'; ?>" />
        <input type="hidden" id="sort-mask_amount" name="sort-mask_amount"
               value="<?= isset($_GET['sort-mask_amount']) ? $_GET['sort-mask_amount'] : 'no-sort'; ?>" />
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['shop_name']; ?></span>
            <input class="form-control" type="text" id="shop_name" name="shop_name"
                   value="<?= isset($_GET['shop_name']) ? $_GET['shop_name'] : ""; ?>" />
        </div>
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['city']; ?></span>
            <?php includeWith('./components/city-select.php', array('default' => isset($_GET['city']) ? $_GET['city'] : '')); ?>
        </div>
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask-price']; ?></span>
            <input class="form-control" type="number" id="price_lower_bound" name="price_lower_bound"
                   value="<?= isset($_GET['price_lower_bound']) ? $_GET['price_lower_bound'] : ''; ?>" min="0" />
            <span class="input-group-text">~</span>
            <input class="form-control" type="number" id="price_upper_bound" name="price_upper_bound"
                   value="<?= isset($_GET['price_upper_bound']) ? $_GET['price_upper_bound'] : ''; ?>" min="0" />
        </div>
        <div class="input-group w-75 mt-2">
            <span class="input-group-text"><?= $TEXT['mask-amount']; ?></span>
            <?php include './components/amount-select.php'; ?>
        </div>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" id="work-shop" name="work-shop"
             <?= isset($_GET['work-shop']) && $_GET['work-shop'] === 'on' ? 'checked' : ''; ?> />
            <label class="form-check-label" for="work-shop">
                <?= $MSG['only-shops-I-work']; ?>
            </label>
        </div>
        <div class="w-75 mt-2 d-flex justify-content-end">
            <button class="btn btn-dark" type="submit" style="width: 5rem;">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
</div>

<?php
    function getIcon($status) {

        switch($status) {
        case 'asc':
            return 'bi-sort-down-alt';
        case 'desc':
            return 'bi-sort-up';
        default:
            return '';
        }

    }
?>
<div class="mt-5">
    <?php
        if(count($shop_arr)):
    ?>
    <table id="query-output" class="table table-striped table-hover w-75 mt-3">
        <thead class="table-dark">
            <tr>
                <th id="shop_name">
                    <?= $TEXT['shop_name']; ?>
                <i class="bi <?= isset($_GET['sort-shop_name']) ? getIcon($_GET['sort-shop_name']) : ''; ?>"></i>
                </th>
                <th id="city">
                    <?= $TEXT['city']; ?>
                    <i class="bi <?= isset($_GET['sort-city']) ? getIcon($_GET['sort-city']) : ''; ?>"></i>
                </th>
                <th id="mask_price">
                    <?= $TEXT['mask-price']; ?>
                    <i class="bi <?= isset($_GET['sort-mask_price']) ? getIcon($_GET['sort-mask_price']) : ''; ?>"></i>
                </th>
                <th id="mask_amount">
                    <?= $TEXT['mask-amount']; ?>
                    <i class="bi <?= isset($_GET['sort-mask_amount']) ? getIcon($_GET['sort-mask_amount']) : ''; ?>"></i>
                </th>
                <th id="order_mask">
                    <?= $TEXT['order-mask']; ?>
                </th>
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
                <td class="align-middle">
                    <form action="makeOrder.php" method="post">
                        <div class="input-group">
                            <input class="form-control" type="number" id="order_amount" name="order_amount" min="0" />
                            <input type="hidden" name="orderer" value="<?= $_SESSION['UID']; ?>" />
                            <input type="hidden" name="shop_id" value="<?= $shop_info['shop_id']; ?>" />
                            <button type="submit" class="btn btn-primary"><?= $TEXT['order']; ?></button>
                        </div>
                    </form>
                </td>
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
