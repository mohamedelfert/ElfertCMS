<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$Msg  = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/
/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = delete بيقوم يلغي بناء علي id اللي جاي في الرابط برضه*/
if (@$_GET['box'] == 'delete'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("DELETE FROM posts WHERE post_id = '$id'");
    $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم حذف المقال بنجاح </b></div>';

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
    <div class="row">
        <div class="col-md-12"> <!-- الديف دا خاص بالجزء بتاع عرض المقالات -->
            <div class="panel panel-info">
                <div class="panel-heading"><b>المقالات</b></div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصوره</th>
                                <th>عنوان المقال</th>
                                <th>الكاتب</th>
                                <th>التصنيف</th>
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

                            /*الجزء دا خاص لعمل تعدد للصفحات للمقالات*/
                            $per_page = 7; /*عدد المقالات اللي عايزها تظهر في الصفحه*/
                            /*هنا باتاكد ان مفيش متغير اسمه page جاي في الرابط ولا لا*/
                            if (!isset($_GET['page'])){
                                $page = 1; //هنا لو مفيش باعمل انا متغير واديله قيمه بدايه
                            }else{
                                $page = (int)$_GET['page']; //اما هنا لو فيه متغير جاي في الرابط هقوم اجيب قيمته في المتغير ده
                            }
                            $start_from = ($page - 1) * $per_page;
                            /***************النهايه******************/

                            /*هنا باعمل استعلام بسيط عشان اجيب بيانات من الداتا بيس وعملت ربط ما بين جدول posts و users وربطت بينهم بعلاقه وعملت شرط ان حقل author في جدول posts يساوي حقل user_id في جدول usres */
                            $stmnt = $conn->query("SELECT * FROM posts INNER JOIN users WHERE posts.author = users.user_id ORDER BY post_id DESC LIMIT $start_from , $per_page");
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
                                             <td>'.$row->post_category.'</td>
                                            <td>'.$row->post_date.'</td>
                                            <td><a href="../post.php?id='.$row->post_id.'" class="btn btn-primary btn-xs">مشاهده المقال</a></td>
                                            <!-- هنا عامل if بشوف لو كان post_status = Published يوديه لرابط فيه متغير box = Disable ولو كان post_status = Disable يوديه لرابط فيه متغير box = Published -->                                            
                                            <td>'.($row->post_status == 'Published' ? '<a href="posts.php?box=Disable&id='.$row->post_id.'&page='.$page.'" class="btn btn-info btn-xs">تعطيل</a>' : '<a href="posts.php?box=Published&id='.$row->post_id.'&page='.$page.'" class="btn btn-success btn-xs">نشر</a>').'</td>
                                            <td><a href="edite_post.php?box=edite&id='.$row->post_id.'" class="btn btn-warning btn-xs">تعديل</a></td>
                                            '.($_SESSION['role'] == 'admin' ? '<td><a href="posts.php?box=delete&id='.$row->post_id.'&page='.$page.'" class="btn btn-danger btn-xs">حذف</a></td>' : '').'
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

                    <!-- باقي الجزء اللي فوق الخاص لعمل تعدد للصفحات للمقالات -->
                    <?php
                    $pages       = $conn->query("SELECT * FROM posts");
                    $count_page  = $pages->rowCount();
                    $total_pages = ceil($count_page / $per_page);
                    ?>
                    <nav class="text-center">
                        <ul class="pagination">
                    <?php
                    for ($i = 1 ; $i <= $total_pages ; $i++){
                        echo' <li '.($page == $i ? 'class="active"' : '').'><a href="posts.php?page='.$i.'">'.$i.'</a></li>';
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