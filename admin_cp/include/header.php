<?php include_once 'session.php'; ?>
<!DOCTYPE html>
<html lang="ar">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>لوحه التحكم</title>
        <link rel="icon" href="../images/logo.ico">
        <!-- Bootstrap -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/bootstrap-rtl.min.css" rel="stylesheet">
        <link href="../css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
        <!-- Start Navbar -->
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
                    <a class="navbar-brand" href="index.php"><i class="glyphicon glyphicon-dashboard"></i>  لوحه التحكم</a>
                    <a class="navbar-brand" href="../index.php"><i class="glyphicon glyphicon-home"></i>  الرئيسيه</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">الاعدادات <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">الصفحه الشخصيه</a></li>
                                <li role="separator" class="divider"></li>
                                <!-- دي هتوديني لصفحه الخروج logout.php اللي هيا في الملف بتاع المشروع دا ElfertCMS -->
                                <li><a href="../logout.php?logout">تسجيل الخروج</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <!-- End Navbar -->

        <!-- Start Section -->
        <section class="container-fluid" style="margin-top: 20px;">
            <div class="row">