<?php
    $SITENAME = '口罩達人';
    $DESC = '輕鬆購買口罩一點也不困難！';

    $MSG = array(
        'register' => '註冊',
        'login' => '登入',
        'username' => '帳號名稱',
        'password' => '密碼',
        'password_again' => '確認密碼',
        'phone_num' => '聯絡電話',
        'submit' => '送出',
        'home' => '資訊主頁',
        'shop' => '店家管理',
        'logout' => '登出',
        'profile' => '個人資料',
        'shop_list' => '店家列表',
        'shop_name' => '商店名稱',
        'city' => '所在縣市',
        'mask_price' => '口罩價格',
        'mask_amount' => '口罩數量',
        'not-shopkeeper-msg' => '你是口罩賣家嗎？可以在下方註冊並管理你的店家喔',
        'shop-info' => '商店資訊',
        'employee-list' => '員工列表',
        'add-employee' => '新增員工',
        'is-shopkeeper' => '店長不必再設定自己為員工',
        'not-exist' => '不存在的使用者名稱',
        'already-been-employee' => '該使用者已經是本店員工了',
        'add-successfully' => '成功新增員工',
        'remove-successfully' => '成功移除員工',
        'no-employee' => '還沒有員工嗎？趕快來招兵買馬吧',
        'edit' => '編輯',
        'add' => '新增',
        'delete' => '刪除'
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
        global $SITENAME, $MSG;
        extract($vars);
        include $filename;
    }

    function sendPopupAndGoto($msg, $page) {
        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                    <script>
                        alert("$msg");
                        window.location.replace("$page");
                    </script>
                    <p>$msg</p>
                    <p>如果沒有被跳轉，請點擊此<a href="$page">連結</a></p>
                </body>
            </html>
EOT;
    }

    function getOwnedShopID() {

        if(isset($_SESSION['owned_shop_id']))
            return $_SESSION['owned_shop_id'];

        $dbhostname = getenv('MYSQL_HOST');
        $dbport = '3306';
        $dbname = getenv('MYSQL_DATABASE');
        $dbusername = getenv('MYSQL_USER');
        $dbpassword = getenv('MYSQL_PASSWORD');

        try {
            $conn = new PDO("mysql:host=$dbhostname;port=$dbport;dbname=$dbname", $dbusername, $dbpassword);
            # set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare('SELECT SID FROM shops JOIN users ON (shops.shopkeeper_id = users.UID) WHERE username = :username');

            $stmt->execute(array('username' => $_SESSION['Username']));

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
?>
