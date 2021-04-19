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

    function includeWith($filename, $vars) {
        global $SITENAME, $MSG;
        extract($vars);
        include $filename;
    }
?>
