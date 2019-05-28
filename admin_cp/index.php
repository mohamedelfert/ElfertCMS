<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$Msg  = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/

/*الاستعلام ده عشان اجيب بيانات المستخدم من الداتا بيس عشان استخدمها في العرض*/
$select_user = $conn->query("SELECT * FROM users WHERE user_id = $_SESSION[id]");
$row_user    = $select_user->fetch(PDO::FETCH_OBJ);

/*استعلام عشان اجيب عدد المقالات اللي عندي في الداتا بيس*/
$posts = $conn->query("SELECT * FROM posts");
$post  = $posts->rowCount();

/*استعلام عشان اجيب عدد التعليقات اللي عندي في الداتا بيس*/
$comments = $conn->query("SELECT * FROM comments");
$comment  = $comments->rowCount();

/*استعلام عشان اجيب عدد الأعضاء اللي عندي في الداتا بيس*/
$users = $conn->query("SELECT * FROM users");
$user  = $users->rowCount();

/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = deleteuser بيقوم يلغي بناء علي id اللي جاي في الرابط برضه*/
if (@$_GET['box'] === 'deleteuser'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("DELETE FROM users WHERE user_id = '$id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف العضو بنجاح </b></div>';
    header("refresh:1; index.php");

}

/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = deletepost بيقوم يلغي بناء علي id اللي جاي في الرابط برضه*/
if (@$_GET['box'] == 'deletepost'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("DELETE FROM posts WHERE post_id = '$id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف المقال بنجاح </b></div>';
    header("refresh:1; index.php");

}

/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = Disable or Published  بيقوم يعدل في الحاله بتاعته بناء علي id اللي جاي في الرابط برضه*/
if (@$_GET['box'] === 'Disable'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("UPDATE posts SET post_status = '$_GET[box]' WHERE post_id = '$id'");

}elseif(@$_GET['box'] === 'Published'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("UPDATE posts SET post_status = '$_GET[box]' WHERE post_id = '$id'");

}
?>

<!-- Start article -->
<article class="col-lg-9">
    <?php echo $Msg; ?> <!-- هنا لعرض رساله النجاح الخاصه بالحذف -->
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-3"> <!-- الديف دا خاص بعرض بيانات المستخدم -->
                <div class="panel panel-primary">
                    <div class="panel-heading"><b>أهلا وسهلا بك يا</b> { <?php echo $row_user->username; ?> }</div>
                    <div class="panel-body">
                        <div class="text-center">
                            <img src="../<?php echo $row_user->avatar; ?>" width="40%" max-width="150px" class="img-thumbnail">
                            <hr>
                        </div>
                        <div class="text-right">
                            <p><b>البريد : </b><?php echo $row_user->email; ?></p>
                            <p><b>الصلاحيه : </b><?php echo ($row_user->role == 'admin' ? 'المدير العام' : 'كاتب'); ?></p>
                            <p class="text-left"><a href="edite_user.php?id=<?php echo $row_user->user_id; ?>" class="btn btn-danger btn-xs"><b>تعديل البيانات</b></a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3"> <!-- الديف دا خاص بعرض عدد المقالات -->
                <div class="panel panel-info">
                    <div class="panel-heading"><b>المقالات</b></div>
                    <div class="panel-body">
                        <div class="col-md-8">
                            <i class="fa fa-list-alt fa-5x" style="color: #31708F"></i>
                        </div>
                        <div class="col-md-4">
                            <p><b><?php echo $post; ?></b></p>
                        </div>
                    </div>
                    <div class="panel-footer text-center"><i class="fa fa-eye"></i> <a href="posts.php"><b>مشاهده</b></a></div>
                </div>
            </div>
            <div class="col-md-3"> <!-- الديف دا خاص بعرض عدد التعليقات -->
                <div class="panel panel-danger">
                    <div class="panel-heading"><b>التعليقات</b></div>
                    <div class="panel-body">
                        <div class="col-md-8">
                            <i class="fa fa-comments-o fa-5x" style="color: #A94442"></i>
                        </div>
                        <div class="col-md-4">
                            <p><b><?php echo $comment; ?></b></p>
                        </div>
                    </div>
                    <div class="panel-footer text-center"><i class="fa fa-eye"></i> <a href="comments.php"><b>مشاهده</b></a></div>
                </div>
            </div>
            <div class="col-md-3"> <!-- الديف دا خاص بعرض عدد الأعضاء -->
                <div class="panel panel-success">
                    <div class="panel-heading"><b>الأعضاء</b></div>
                    <div class="panel-body">
                        <div class="col-md-8">
                            <i class="fa fa-users fa-5x" style="color: #3C763D"></i>
                        </div>
                        <div class="col-md-4">
                            <p><b><?php echo $user; ?></b></p>
                        </div>
                    </div>
                    <div class="panel-footer text-center"><i class="fa fa-eye"></i> <a href="users.php"><b>مشاهده</b></a></div>
                </div>
            </div>
            <!-- الديف دا خاص بالجزء بتاع عرض المقالات -->
            <div class="col-md-12">
                <div class="panel panel-danger">
                    <div class="panel-heading"><b>أخر المقالات المضافه</b></div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>الصوره</th>
                                <th>عنوان المقال</th>
                                <th>الكاتب</th>
                                <th>تاريخ النشر</th>
                                <th>مشاهده</th>
                                <th>الحاله</th>
                                <th>تعديل</th>
                                <?php
                                if ($_SESSION['role'] == 'admin'){
                                    ?>
                                    <th>حذف</th>
                                    <?php
                                }
                                ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /*هنا باعمل استعلام بسيط عشان اجيب بيانات من الداتا بيس وعملت ربط ما بين جدول posts و users وربطت بينهم بعلاقه وعملت شرط ان حقل author في جدول posts يساوي حقل user_id في جدول usres */
                            $stmnt = $conn->query("SELECT * FROM posts INNER JOIN users WHERE posts.author = users.user_id ORDER BY post_id DESC LIMIT 4");
                            $count = $stmnt->rowCount();
                            /*هنا بشوف ان كان فيه عناصر ولا لا*/
                            if ($count > 0){
                                $num = 1; /*متغير فيه القيمه 1 عشان ازود عليه حسب while*/
                                /*هنا في الحلقه دي عشان التكرار علي حسب البيانات اللي بيجيبها من الاستعلام اللي فوق*/
                                while ($row = $stmnt->fetch(PDO::FETCH_OBJ)){
                                    echo '
                                        <tr>
                                            <td>'.$num.'</td>
                                            <td><img src="../'.$row->post_image.'" class="img-rounded" width="80px" height="60px"></td>
                                            <td>'.substr($row->title , 0 , 40).' ...</td>  
                                            <td>'.$row->username.'</td>  <!-- هنا بقا استدعيت username اللي هو في جدول users بناء ع الاستعلام اللي فوق -->
                                            <td>'.$row->post_date.'</td>
                                            <td><a href="../post.php?id='.$row->post_id.'" class="btn btn-primary btn-xs">مشاهده المقال</a></td>
                                            <!-- هنا عامل if بشوف لو كان post_status = Published يوديه لرابط فيه متغير box = Disable ولو كان post_status = Disable يوديه لرابط فيه متغير box = Published -->                                           
                                            <td>'.($row->post_status == 'Published' ? '<a href="index.php?box=Disable&id='.$row->post_id.'" class="btn btn-info btn-xs">تعطيل</a>' : '<a href="index.php?box=Published&id='.$row->post_id.'" class="btn btn-success btn-xs">نشر</a>').'</td>
                                            <td><a href="edite_post.php?box=edite&id='.$row->post_id.'" class="btn btn-warning btn-xs">تعديل</a></td>
                                            '.($_SESSION['role'] == 'admin' ? '<td><a href="index.php?box=deletepost&id='.$row->post_id.'" class="btn btn-danger btn-xs">حذف</a></td>' : '').'
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
            <?php
            if ($_SESSION['role'] === 'admin'){
            ?>
                <!-- الديف دا خاص بالجزء بتاع عرض الأعضاء -->
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><b>جديد الأعضاء</b></div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصوره الرمزيه</th>
                                    <th>اسم العضو</th>
                                    <th>البريد الالكتروني</th>
                                    <th>الجنس</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الصفحه الشخصيه</th>
                                    <th>تعديل البيانات</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                /*هنا باعمل استعلام بسيط عشان اجيب بيانات من الداتا بيس*/
                                $stmnt = $conn->query("SELECT * FROM users ORDER BY user_id DESC LIMIT 4");
                                $count = $stmnt->rowCount();
                                /*هنا بشوف ان كان فيه عناصر ولا لا*/
                                if ($count > 0){
                                    $num = 1; /*متغير فيه القيمه 1 عشان ازود عليه حسب while*/
                                    /*هنا في الحلقه دي عشان التكرار علي حسب البيانات اللي بيجيبها من الاستعلام اللي فوق*/
                                    while ($row = $stmnt->fetch(PDO::FETCH_OBJ)){
                                        echo '
                                        <tr>
                                            <td>'.$num.'</td>
                                            <td><img src="../'.$row->avatar.'" style="width: 70px; height: 55px; border-radius:30%;"></td>
                                            <td>'.$row->username.'</td>
                                            <td>'.$row->email.'</td>
                                            <td>'.($row->gender == 'male' ? '<img src="../images/male.png" width="40px">' : '<img src="../images/female.png" width="40px">').'</td>
                                            <td>'.$row->register_date.'</td>
                                            <td><a href="../profile.php?user='.$row->user_id.'" class="btn btn-primary btn-xs" target="_balnk">مشاهده</a></td>
                                            <td><a href="edite_user.php?box=edite&id='.$row->user_id.'" class="btn btn-warning btn-xs">تعديل</a></td>
                                            <td><a href="index.php?box=deleteuser&id='.$row->user_id.'" class="btn btn-danger btn-xs">حذف</a></td>
                                        </tr>
                                    ';
                                        $num++; /*هنا في حاله انه فيه عناصر اخري بيزود رقم علي المتغير ده*/
                                    }
                                }else{
                                    echo '<div class="alert alert-danger text-center" role="alert" style="font-size: 20px;"><b>لايوجد أي أعضاء في الموقع حاليا </b></div>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</article>
<!-- End article -->

<?php include_once 'include/footer.php';