<?php
include_once 'connect.php';
session_start();

if (isset($_POST['login'])) {
    $user     = stripslashes(strip_tags($_POST['user']));
    $password = md5($_POST['password']);

    if (empty($user)){

        echo '<div class="alert alert-danger" role="alert"><b>الرجاء ادخال اسم المستخدم او البريد الاكتروني </b></div>';

    }elseif (empty($_POST['password'])) {

        echo '<div class="alert alert-danger" role="alert"><b>يجب ادخال كلمه المرور </b></div>';

    }else{

        $stmnt = $conn->query("SELECT * FROM users WHERE ( username = '$user' OR email = '$user' ) AND password = '$password'");
        $count = $stmnt->rowCount();
        if ($count > 0){

            $row = $stmnt->fetch(PDO::FETCH_OBJ);
            $_SESSION['id']            = $row->user_id;
            $_SESSION['username']      = $row->username;
            $_SESSION['email']         = $row->email;
            $_SESSION['gender']        = $row->gender;
            $_SESSION['avatar']        = $row->avatar;
            $_SESSION['about_user']    = $row->about_user;
            $_SESSION['facebook']      = $row->facebook;
            $_SESSION['twitter']       = $row->twitter;
            $_SESSION['youtube']       = $row->youtube;
            $_SESSION['register_date'] = $row->register_date;
            $_SESSION['role']          = $row->role;
            echo'<div class="alert alert-success" role="alert"><b> تم تسجيل دخولك بنجاح جاري تحديث الصفحه :) </b></div>';
            echo '<meta http-equiv="refresh" content="3; \'index.php\'">';

        }else{

            echo '<div class="alert alert-danger" role="alert"><b>عفوا اسم المستخدم او كلمه المرور غير صحيح </b></div>';

        }
    }
}

?>