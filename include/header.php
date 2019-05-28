<?php
session_start();
ob_start();
include_once 'include/connect.php';
include_once 'include/functions.php';

$selcet_setting = $conn->query("SELECT * FROM setting");
$row_setting    = $selcet_setting->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $row_setting->site_name; ?></title>
    <link rel="icon" href="images/logo.ico">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-rtl.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand disabled"><?php echo $row_setting->site_name; ?></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">الرئيسيه <span class="sr-only">(current)</span></a></li>
                <?php
                /*هنا عملت استعلام بسيط عشان اجيب اسم التصنيف من الجدول وبعدين عملت لوب تكرر وتحط في لينك*/
                $stmnt = $conn->query("SELECT * FROM category");
                while ($row = $stmnt->fetch(PDO::FETCH_OBJ)){
                    /*هنا باوجهه للينك يروح لصفحه category.php وعامل متغير في الرابط اسمه cat قيمته بتساوي القيمه من الجدول*/
                    echo '<li><a href="category.php?cat='.$row->cat_name.'">'.$row->cat_name.'</a></li>';
                }
                ?>
            </ul>

            <?php
            if (isset($_SESSION['id'])){ /*هنا بشوف ان كان فيه session id اتفتح تقوم تظهر القائمه دي */
            ?>
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">الاعدادات <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">الصفحه الرئيسيه</a></li>
                                <li role="separator" class="divider"></li>
                                <?php 
                                if (@$_SESSION['role'] === 'admin'){ /*هنا بشوف ان كان فيه session role === admin يعني ليه صلاحيه admin يقةم يظهرله الجزء دا */
                                    echo '<li><a href="admin_cp/index.php">لوحه التحكم</a></li>';
                                }
                                ?>
                                <li><a href="logout.php?logout">تسجيل الخروج</a></li>
                            </ul>
                        </li>
                    </ul>
            <?php
            }else{ /*هنا بقا لو ملقاش فيه session اصلا متسجل يقوم يظهر الجزء دا بس*/
                echo '
                    <ul class="nav navbar-nav navbar-left">
                        <li><a href="register.php" style="color: #2e6da4;font-size: 15px;"><b>تسجيل</b></a></li>
                    </ul>
                ';
            }
            ?>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<!-- logo site -->
<section id="logo">
    <img src="<?php echo $row_setting->site_logo; ?>" width="320px" height="90px">
</section>
<!-- end logo site -->

<!-- body site -->
<section class="container-fluid" style="margin-top: 20px;">