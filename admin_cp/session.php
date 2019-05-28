<?php
session_start(); /*هنا بدات session*/
include_once '../include/connect.php';

if (isset($_SESSION['id'])){ /*هنا بشوف ان كان فيه session id مفتوح ولا لا*/
    /*هنا بجيب من الداتا بيس user_id اللي بيساوي $_SESSION[id] ده وكمان بشوف role بتاعته بتساوي admin or writer ولا لا*/
    $stmnt = $conn->query("SELECT * FROM users WHERE user_id = '$_SESSION[id]' AND role = ('admin' OR 'writer')");
    $count = $stmnt->rowCount();
    if ($count != 1){ /*هنا بعد ما نفذ الاستعلام اللي فوق ده بيشوف ان كان فيه عناصر ولا لا حسب الاستعلام ده فلو ملقاش عناصر يقوم يحوله لصفحه index الاساسيه بتاعت الموقع كله*/
        header('Location: ../index.php');
    }else{

    }
}else{
    header('Location: ../index.php'); /*هنا لو ملقاش session اصلا مفتوح يوديه علي صفحه index الرئيسيه بتاعت الموقع*/
}