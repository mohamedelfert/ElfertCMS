<?php

include_once 'include/header.php';
include_once 'include/sidbar.php'

?>

<div class="col-lg-9">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="index.php">الرئيسيه</a></li>
            <li class="active">تسجيل عضويه جديده</li>
        </ol>
    </div>
</div>

<article class="col-md-9 col-leg-9 art_bg">
    <div class="page-header">
        <h1><i class="fa fa-user" aria-hidden="true"></i>
            تسجيل عضويه جديده <small>الحقول المؤشر عليها ب (<span style="color: red;">*</span>) مطلوبه</small></h1>
    </div>

    <div class="col-md-12">
        <?php register(); ?> <!-- هنا انا مستدعي فانكشن عاملها register تعرض الصفحه بناء ع الحاله -->
    </div>
</article>

<?php include_once 'include/footer.php'?>
