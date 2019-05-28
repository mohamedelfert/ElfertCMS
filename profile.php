<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$id  = (int) $_GET['user'];
/*هنا باعمل استعلام بسيط عشان اجيب بيانات العضو من الداتا بيس */
$sql   = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
$count = $sql->rowCount();
if ($count != 1){
    header('Location: index.php');
}
$row   = $sql->fetch(PDO::FETCH_OBJ);

?>

<!-- Start article -->
<article class="col-lg-9">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="panel panel-info">
            <div class="panel-heading"><b>المعلومات الشخصيه</b></div>
            <div class="panel-body">
                <div class="col-md-9">
                    <p><b>اسم المستخدم : </b> <?php echo $row->username; ?> </p>
                    <p><b>البريد الالكتروني : </b> <?php echo $row->email; ?> </p>
                    <p><b>الصلاحيه : </b> <?php if ($row->role == 'admin'){echo 'المدير العام';}elseif($row->role == 'writer'){echo 'كاتب';}else{echo 'عضو';} ?> </p>
                    <p><b>الجنس : </b> <?php if ($row->gender == 'male'){echo '<img src="images/male.png" width="45px">';}else{echo '<img src="images/female.png" width="45px">';} ?> </p>
                    <p><b>تاريخ التسجيل : </b> <?php echo $row->register_date; ?> </p>
                    <p><b>روابط الاتصال لديك : </b>
                        <a href="<?php echo $row->facebook; ?>" target="_blank"> <i class="fa fa-facebook-square fa-lg" style="color: #3B5998;margin: 0 5px;" aria-hidden="true"></i> </a> |
                        <a href="<?php echo $row->twitter; ?>" target="_blank"> <i class="fa fa-twitter-square fa-lg" style="color: #31B0D5;margin: 0 5px;" aria-hidden="true"></i> </a> |
                        <a href="<?php echo $row->youtube; ?>" target="_blank"> <i class="fa fa-youtube-square fa-lg" style="color: #E62117;margin: 0 5px;" aria-hidden="true"></i> </a>
                    </p>
                </div>
                <div class="col-md-3">
                    <img src="<?php echo $row->avatar; ?>" class="img-thumbnail" width="100%">
                </div>
                <div class="col-md-12">
                    <hr>
                    <p><b>وصف مختصر عنك : </b></p>
                    <p><?php echo strip_tags($row->about_user); ?></p>
                    <?php
                    if (@$_SESSION['id'] == $row->user_id){
                        echo '<a href="edite_profile.php?id='.$row->user_id.'" class="btn btn-danger pull-left btn-sm">تعديل البيانات</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</article>
<!-- End article -->

<?php include_once 'include/footer.php'; ?>