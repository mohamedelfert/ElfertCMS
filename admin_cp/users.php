<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

if ($_SESSION['role'] != 'admin'){
    header('Location: index.php');
}

$Msg  = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/
/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = delete بيقوم يلغي بناء علي id اللي جاي في الرابط برضه*/
if (@$_GET['box'] === 'delete'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("DELETE FROM users WHERE user_id = '$id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف العضو بنجاح </b></div>';

}

?>

<!-- Start article -->
<article class="col-lg-9">
    <?php echo $Msg; ?> <!-- هنا لعرض رساله النجاح الخاصه بالحذف -->
    <div class="row">
        <div class="col-md-12"> <!-- الديف دا خاص بالجزء بتاع عرض الأعضاء -->
            <div class="panel panel-info">
                <div class="panel-heading"><b>الأعضاء</b></div>
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

                        /*الجزء دا خاص لعمل تعدد للصفحات للأعضاء*/
                        $per_user = 10; /*عدد الأعضاء اللي عايزهم يظهرو في الصفحه*/
                        /*هنا باتاكد ان مفيش متغير اسمه page جاي في الرابط ولا لا*/
                        if (!isset($_GET['page'])){
                            $page = 1; //هنا لو مفيش باعمل انا متغير واديله قيمه بدايه
                        }else{
                            $page = (int)$_GET['page']; //اما هنا لو فيه متغير جاي في الرابط هقوم اجيب قيمته في المتغير ده
                        }
                        $start_from = ($page - 1) * $per_user;
                        /***************النهايه******************/

                        /*هنا باعمل استعلام بسيط عشان اجيب بيانات من الداتا بيس*/
                        $stmnt = $conn->query("SELECT * FROM users ORDER BY user_id DESC LIMIT $start_from , $per_user");
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
                                        <td><a href="users.php?box=delete&id='.$row->user_id.'&page='.$page.'" class="btn btn-danger btn-xs">حذف</a></td>
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

                    <!-- باقي الجزء اللي فوق الخاص لعمل تعدد للصفحات للأعضاء -->
                    <?php
                    $users       = $conn->query("SELECT * FROM users");
                    $count_user  = $users->rowCount();
                    $total_pages = ceil($count_user / $per_user);
                    ?>
                    <nav class="text-center">
                        <ul class="pagination">
                            <?php
                            for ($i = 1 ; $i <= $total_pages ; $i++){
                                echo'<li '.($page == $i ? 'class="active"' : '').'><a href="users.php?page='.$i.'">'.$i.'</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                    <!-- النهايه -->

                </div>
            </div>
        </div>
    </div>
</article>
<!-- End article -->

<?php include_once 'include/footer.php'; ?>