<?php
    date_default_timezone_set("Asia/Taipei");

    $dbhostname = getenv('MYSQL_HOST');
    $dbport = '3306';
    $dbname = getenv('MYSQL_DATABASE');
    $dbusername = getenv('MYSQL_USER');
    $dbpassword = getenv('MYSQL_PASSWORD');

    $SITENAME = '口罩達人';
    $DESC = '輕鬆購買口罩一點也不困難！';

    $MSG = array(
        // register
        'invalid-username' => "帳號名稱只接受 0-9, '.', a-z, A-Z",
        'user-already-exist' => '帳號已被註冊',
        'invalid-password' => '密碼驗證≠ 密碼或是密碼包含 ASCII-standard 以外的字元',
        'invalid-phone_num' => '電話號碼只能包含 0-9 的數字且長度應介於 7 ~ 10 個數字',
        'register-success' => '註冊成功',

        // login
        'login-failed' => '登入失敗',

        // shop - not register
        'not-shopkeeper' => '你是口罩賣家嗎？可以在下方註冊並管理你的店家喔',
        'invalid-shop-name' => '店家名稱空白或是開頭或結尾包含空白',
        'shop-already-exist' => '該商店名稱已被註冊',
        'is-not-number' => '口罩價格和口罩數量請輸入正整數或零',

        // shop - registered
        'is-shopkeeper' => '店長不必再設定自己為員工',
        'not-exist' => '不存在的使用者名稱',
        'already-been-employee' => '該使用者已經是本店員工了',
        'add-successfully' => '成功新增員工',
        'remove-successfully' => '成功移除員工',
        'no-employee' => '還沒有員工嗎？趕快來招兵買馬吧',

        // search shop
        'invalid-price-range' => '價格範圍不合理，下限的值須小於或等於上限',
        'no-shop' => '搜尋無結果OAO',

        // specify the shop
        'only-shops-I-work' => '只顯示我工作的店家',

        // no selection
        'city-no-selection' => '請選擇一個縣市',
        'work-shop-no-selection' => '請選擇一個店家',
        'status-no-selection' => '請選擇一個狀態',

        // order
        'no-order' => '這裡沒有訂單喔',
        'invalid-OID' => '發送的訂單編號包含已處理過的訂單或是沒有權限修改的訂單',

        // during ordering
        'order-fail' => '數量不足，無法訂購',
        'order-success' => '成功訂購',
        
        // invalid GET parameters
        'invalid-GET-param' => '不要亂打喔:)',
    );

    $TEXT = array(
        // index
        'register' => '註冊',
        'login' => '登入',
        'username' => '帳號名稱',
        'password' => '密碼',
        'password_again' => '確認密碼',
        'phone_num' => '聯絡電話',
        
        // general button
        'submit' => '送出',
        'edit' => '編輯',
        'add' => '新增',
        'delete' => '刪除',
        'confirm' => '確認',
        'cancel' => '取消',
        'logout' => '登出',
        
        // page name
        'home' => '資訊主頁',
        'shop' => '店家管理',
        'my-order' => '我的訂單',
        'shop-order' => '店家訂單',
        'order-mask' => '訂購數量',
        'order' => '訂購',

        // mask amount range
        'all' => '請選擇一個範圍',
        'out-of-stock' => '售完',
        'few' => '稀少(不足 100)',
        'sufficient' => '充足(100+)',

        // home & shop
        'profile' => '個人資料',
        'shop_list' => '店家列表',
        'shop_name' => '商店名稱',
        'city' => '所在縣市',
        'mask-price' => '口罩價格',
        'mask-amount' => '口罩數量',
        'shop-info' => '商店資訊',
        'employee-list' => '員工列表',
        'add-employee' => '新增員工',

        // order
        'OID' => '訂單編號',
        'work-shop' => '工作店家',
        'status' => '狀態',
        'created-time' => '建立時間',
        'completed-time' => '完成/取消時間',
        'order-maker' => '建立者',
        'order-completer' => '完成/取消者',
        'total-price' => '總價',
        'pending' => '待處理',
        'completed' => '已完成',
        'canceled' => '已取消',
        'cancel-selected' => '取消選取的訂單',
    );

    $CITY = array(
        'taipei-city' => '臺北市',
        'new-taipei-city' => '新北市',
        'keelung-city' => '基隆市',
        'taoyuan-city' => '桃園市',
        'hsinchu-city' => '新竹市',
        'hsinchu-county' => '新竹縣',
        'miaoli-county' => '苗栗縣',
        'taichung-city' => '臺中市',
        'changhua-county' => '彰化縣',
        'nantou-county' => '南投縣',
        'yunlin-county' => '雲林縣',
        'chiayi-city' => '嘉義市',
        'chiayi-county' => '嘉義縣',
        'tainan-city' => '臺南市',
        'kaohsiung-city' => '高雄市',
        'pingtung-county' => '屏東縣',
        'yilan-county' => '宜蘭縣',
        'hualien-county' => '花蓮縣',
        'taitung-county' => '臺東縣',
        'penghu-county' => '澎湖縣',
        'kinmen-county' => '金門縣',
        'lienchiang-county' => '連江縣'
    );

    function includeWith($filename, $vars) {
        global $SITENAME, $MSG, $TEXT, $CITY;
        extract($vars);
        include $filename;
    }

    function sendPopupAndGoto($msg, $page) {
        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="refresh" content="5; url=$page">
                </head>
                <body>
                    <script>
                        alert("$msg");
                        window.location.replace("$page");
                    </script>
                    <p>$msg</p>
                    <p>網頁將在 5 秒後自動跳轉，或者你可以點擊此<a href="$page">連結</a></p>
                </body>
            </html>
EOT;
    }

    function getOwnedShopID() {

        global $dbhostname, $dbport, $dbname, $dbusername, $dbpassword;

        if(isset($_SESSION['owned_shop_id']))
            return $_SESSION['owned_shop_id'];

        try {
            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare('SELECT SID FROM shops WHERE shopkeeper_id = :UID');

            $stmt->execute(array('UID' => $_SESSION['UID']));

            if($stmt->rowCount() == 1)
                $SID = $stmt->fetch()['SID'];
            else
                throw new Exception($_SESSION['Username'] . ' is not a shopkeeper.');

        } catch(PDOException $e) {
            throw $e;
        }

        $_SESSION['owned_shop_id'] = $SID;

        return $SID;
    }

    function isShopkeeper($UID) {

        global $dbhostname, $dbport, $dbname, $dbusername, $dbpassword;

        try {
            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT SID FROM shops WHERE shopkeeper_id = :UID");
            $stmt->execute(array('UID' => $UID));
            return $stmt->rowCount() == 1;
        } catch(PDOException $e) {
            echo 'Internal Error: ' . $e;
            exit();
        }

    }
?>
