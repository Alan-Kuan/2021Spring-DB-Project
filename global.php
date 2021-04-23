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
        'not-shopkeeper-msg' => '你是口罩賣家嗎？可以在下方註冊並管理你的店家喔'
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
?>
