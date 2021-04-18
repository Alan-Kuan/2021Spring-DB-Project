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
        'city' => '城市',
        'price' => '價格',
        'amount' => '數量'
    );

    function includeWith($filename, $vars) {
        global $SITENAME, $MSG;
        extract($vars);
        include $filename;
    }
?>
