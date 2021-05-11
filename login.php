<?php
// 啟動 session
session_start();

// 引用資料庫連線
// require_once會讀取或跳轉到括弧內網址一次,也可以寫成 require_once './db.inc.php'
require_once('./db.inc.php');

if (isset($_POST['username']) && isset($_POST['pwd'])) {
    // SQL 語法,?讓pdo處理後續程式(data finding 資料細節)
    $sql = "SELECT `username`, `pwd` 
            FROM `admin` 
            WHERE `username` = ? 
            AND `pwd` = ? ";

    $arrParam = [
        $_POST['username'],
        sha1($_POST['pwd'])
    ];

    // $pdo_stmt 存放資料細節
    // ->是php的物件導向(使用物件的方法)近似於JS的'.'
    $pdo_stmt = $pdo->prepare($sql); // prepare 新增跳脫字元讓之後程式判讀特殊字元為字串
    $pdo_stmt->execute($arrParam); // execute 執行前面prepare模擬好的狀態

    if ($pdo_stmt->rowCount() > 0) {
        // 將傳送過來的 post 變數資料，放到 session，
        $_SESSION['username'] = $_POST['username'];

        // 3秒後跳頁
        header("Refresh: 1; url=./eventList.php");
        // echo "登入成功!!! 1秒後自動進入後端頁面";
    } else {
        // 關閉session
        session_destroy();

        header("Refresh: 3; url=./index.php");
        // echo "登入失敗…3秒後自動回登入頁";
    }
} else {
    // 關閉session
    session_destroy();

    header("Refresh: 3; url=./index.php");
    echo "請確實登入…3秒後自動回登入頁";
}
