<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$Msg  = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/
/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = delete بيقوم يلغي بناء علي id اللي جاي في الرابط برضه*/
if (@$_GET['box'] == 'delete'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("DELETE FROM posts WHERE post_id = '$id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف المقال بنجاح </b></div>';
    header("refresh:2; profile.php");

}

/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = Disable or Published  بيقوم يعدل في الحاله بتاعته بناء علي id اللي جاي في الرابط برضه*/
if (@$_GET['box'] === 'Disable'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("UPDATE posts SET post_status = '$_GET[box]' WHERE post_id = '$id'");
    header("Location: profile.php");

}elseif(@$_GET['box'] === 'Published'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("UPDATE posts SET post_status = '$_GET[box]' WHERE post_id = '$id'");
    header("Location: profile.php");
}
?>

<!-- Start article -->
<article class="col-lg-9">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="panel panel-info">
            <?php
            /*هنا باعمل استعلام بسيط عشان اجيب بيانات العضو من الداتا بيس */
            $select_user = $conn->query("SELECT * FROM users WHERE user_id = '$_SESSION[id]'");
            $row_select  = $select_user->fetch(PDO::FETCH_OBJ);
            ?>
            <div class="panel-heading"><b>المعلومات الشخصيه</b></div>
            <div class="panel-body">
                <div class="col-md-9">
                    <p><b>اسم المستخدم : </b> <?php echo $row_select->username; ?> </p>
                    <p><b>البريد الالكتروني : </b> <?php echo $row_select->email; ?> </p>
                    <p><b>الجنس : </b> <?php if ($row_select->gender == 'male'){echo '<img src="../images/male.png" width="45px">';}else{echo '<img src="../images/female.png" width="45px">';} ?> </p>
                    <p><b>تاريخ التسجيل : </b> <?php echo $row_select->register_date; ?> </p>
                    <p><b>روابط الاتصال لديك : </b>
                        <a href="<?php echo $row_select->facebook; ?>" target="_blank"> <i class="fa fa-facebook-square fa-lg" style="color: #3B5998;margin: 0 5px;" aria-hidden="true"></i> </a> |
                        <a href="<?php echo $row_select->twitter; ?>" target="_blank"> <i class="fa fa-twitter-square fa-lg" style="color: #31B0D5;margin: 0 5px;" aria-hidden="true"></i> </a> |
                        <a href="<?php echo $row_select->youtube; ?>" target="_blank"> <i class="fa fa-youtube-square fa-lg" style="color: #E62117;margin: 0 5px;" aria-hidden="true"></i> </a>
                    </p>
                </div>
                <div class="col-md-3">
                    <img src="../<?php echo $row_select->avatar; ?>" class="img-thumbnail" width="100%">
                </div>
                <div class="col-md-12">
                    <hr>
                    <p><b>وصف مختصر عنك : </b></p>
                    <p><?php echo strip_tags($row_select->about_user); ?></p>
                    <a href="edite_user.php?box=edite&id=<?php echo $row_select->user_id; ?>" class="btn btn-danger pull-left btn-sm">تعديل البيانات</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12"></div>
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <?php echo $Msg; ?> <!-- هنا لعرض رساله النجاح الخاصه بالحذف -->
        <div class="panel panel-danger">
            <div class="panel-heading"><b>أخر المواضيع التي قمت بنشرها</b></div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصوره</th>
                            <th>عنوان المقال</th>
                            <th>تاريخ النشر</th>
                            <th>مشاهده</th>
                            <th>الحاله</th>
                            <th>تعديل</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /*هنا باعمل استعلام بسيط عشان اجيب بيانات من الداتا بيس */
                        $stmnt = $conn->query("SELECT * FROM posts WHERE author = '$_SESSION[id]' ORDER BY post_id DESC LIMIT 5");
                        $count = $stmnt->rowCount();
                        /*هنا بشوف ان كان فيه عناصر ولا لا*/
                        if ($count > 0){
                            $num = 1; /*متغير فيه القيمه 1 عشان ازود عليه حسب while*/
                            /*هنا في الحلقه دي عشان التكرار علي حسب البيانات اللي بيجيبها من الاستعلام اللي فوق*/
                            while ($row = $stmnt->fetch(PDO::FETCH_OBJ)){
                                echo '
                                        <tr>
                                            <td>'.$num.'</td>
                                            <td><img src="../'.$row->post_image.'" class="img-rounded" width="70px"></td>
                                            <td>'.substr($row->title , 0 , 40).' ...</td>  
                                            <td>'.$row->post_date.'</td>
                                            <td><a href="../post.php?id='.$row->post_id.'" class="btn btn-primary btn-xs">مشاهده المقال</a></td>
                                            <!-- هنا عامل if بشوف لو كان post_status = Published يوديه لرابط فيه متغير box = Disable ولو كان post_status = Disable يوديه لرابط فيه متغير box = Published -->                                            
                                            <td>'.($row->post_status == 'Published' ? '<a href="profile.php?box=Disable&id='.$row->post_id.'" class="btn btn-info btn-xs">تعطيل</a>' : '<a href="profile.php?box=Published&id='.$row->post_id.'" class="btn btn-success btn-xs">نشر</a>').'</td>
                                            <td><a href="edite_post.php?box=edite&id='.$row->post_id.'" class="btn btn-warning btn-xs">تعديل</a></td>
                                            <td><a href="profile.php?box=delete&id='.$row->post_id.'" class="btn btn-danger btn-xs">حذف</a></td>
                                        </tr>
                                    ';
                                $num++; /*هنا في حاله انه فيه عناصر اخري بيزود رقم علي المتغير ده*/
                            }
                        }else{
                            echo '<div class="alert alert-danger text-center" role="alert" style="font-size: 20px;"><b>لايوجد أي مقالات في الموقع حاليا </b></div>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</article>
<!-- End article -->

<?php include_once 'include/footer.php'; ?>