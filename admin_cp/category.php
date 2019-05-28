<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$Msg  = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/
$Msg2 = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الحذف وليس قبله*/
/*هنا بشوف ان كان ضغط علي زرار الاضافه بتاع الفورم ولا لا*/
if (isset($_POST['add_cat'])){

    if (empty($_POST['category'])){ /*هنا بشوف ان كان الحقل فاضي ولا لا*/

        $Msg = '<div class="alert alert-danger text-center" role="alert"><b>يجب اضافه اسم للتصنيف </b></div>';

    }else{
        /*هنا لما الحقل مكانش فاضي يقوم ينفذ الاستعلام ده*/
        $category_name = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
        $stmnt = $conn->query("INSERT INTO category (cat_name) VALUES ('$category_name')");
        if (isset($stmnt)){

            $Msg = '<div class="alert alert-success text-center" role="alert"><b>تم اضافه التصنيف بنجاح </b></div>';

        }
    }

}

/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = delete بيقوم يلغي بناء علي id اللي جاي في الرابط برضه*/
if (@$_GET['box'] === 'delete'){

    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $query = $conn->query("DELETE FROM category WHERE cat_id = '$id'");
    $Msg2 = '<div class="alert alert-success text-center" role="alert"><b>تم حذف التصنيف بنجاح </b></div>';
    header("refresh:2; category.php");

}
?>

<!-- Start article -->
<article class="col-lg-9">
    <div class="row">
        <div class="col-md-8"> <!-- الديف دا خاص بالجزء بتاع عرض التصنيفات -->
            <?php echo $Msg2; ?> <!-- هنا لعرض رساله النجاح الخاصه بالحذف -->
            <div class="panel panel-info">
                <div class="panel-heading"><b>التصنيفات</b></div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم التصنيف</th>
                                <th>مشاهده التصنيف</th>
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
                            /*هنا باعمل استعلام بسيط عشان اجيب بيانات من الداتا بيس*/
                            $stmnt = $conn->query("SELECT * FROM category ORDER BY cat_id DESC");
                            $count = $stmnt->rowCount();
                            /*هنا بشوف ان كان فيه عناصر ولا لا*/
                            if ($count > 0){
                                $num = 1; /*متغير فيه القيمه 1 عشان ازود عليه حسب while*/
                                /*هنا في الحلقه دي عشان التكرار علي حسب البيانات اللي بيجيبها من الاستعلام اللي فوق*/
                                while ($row = $stmnt->fetch(PDO::FETCH_OBJ)){
                                    echo '
                                        <tr>
                                            <td>'.$num.'</td>
                                            <td>'.$row->cat_name.'</td>
                                            <td><a href="../category.php?cat='.$row->cat_name.'" class="btn btn-info btn-xs" target="_blank">مشاهده</a></td>
                                            <td><a href="edite_category.php?box=edite&id='.$row->cat_id.'" class="btn btn-warning btn-xs">تعديل</a></td>
                                            '.($_SESSION['role'] == 'admin' ? '<td><a href="category.php?box=delete&id='.$row->cat_id.'" class="btn btn-danger btn-xs">حذف</a></td>' : '').'
                                        </tr>
                                    ';
                                    $num++; /*هنا في حاله انه فيه عناصر اخري بيزود رقم علي المتغير ده*/
                                }
                            }else{
                                echo '<div class="alert alert-danger text-center" role="alert" style="font-size: 20px;"><b>لايوجد أي تصنيفات في الموقع حاليا </b></div>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">اضافه تصنيف جديد</div>
                <div class="panel-body">
                    <!-- الفورم دي خاصه باضافه تصنيفات جديده للموقع -->
                    <form action="" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label for="category" class="col-sm-4 control-label">اسم التصنيف</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="category" name="category" placeholder="أدخل اسم التنصيف الجديد">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <?php echo $Msg; ?> <!-- هنا لعرض رسائل الخطأ والنجاح للفورم -->
                            <div class="col-sm-offset-4 col-sm-8">
                                <input type="submit" name="add_cat" class="btn btn-info" value="اضافه التصنيف">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</article>
<!-- End article -->

<?php include_once 'include/footer.php'; ?>