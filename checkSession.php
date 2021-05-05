<?php
session_start();

//判斷是否登入 (確認先前指派的 session 索引是否存在)
if( !isset($_SESSION['username']) ) {
    //關閉 session
    session_destroy();
    
    //3 秒後跳頁
    header("Location:./loginPage.php");
    exit();
}