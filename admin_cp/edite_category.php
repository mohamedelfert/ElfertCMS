<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$Msg  = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/
/*هنا بشوف ان كان فيه متغير في الرابط اسمه box = edite بيقوم يحوله ع صفحه تانيه بناء علي id اللي جاي في الرابط برضه*/
if ($_GET['box'] == 'edite'){
    $id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
    $stmnt = $conn->query("SELECT * FROM category WHERE cat_id = '$id'");
    $row   = $stmnt->fetch(PDO::FETCH_OBJ);

    if (isset($_POST['edite_cat'])){ /*هنا بشوف ان كان ضغط علي زرار التعديل بتاع الفورم ولا لا*/
        if (empty($_POST['category'])){ /*هنا بشوف ان كان ساب الحقل فاضي ولا لا*/
            $Msg = '<div class="alert alert-danger text-center" role="alert"><b>يجب اضافه اسم للتصنيف </b></div>';
        }else{ /*هنا طبعا لو لقي الحقل فيه قيمه يعني مش فاضي هينفذ دا*/
            $category_name = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
            $query = $conn->query("UPDATE category SET cat_name = '$category_name' WHERE cat_id = '$id'");
            header('refresh:2;category.php');
            echo '<div class="alert alert-success text-center" role="alert"><b>تم تعديل التصنيف بنجاح جاري تحويلك للصفحه الرئيسيه </b></div>';
        }
    }
}

?>

<article class="col-md-9">
    <div class="row">
        <div class="col-md-6" style="margin: 100px 260px 0 0;">
            <?php echo $Msg; ?> <!-- هنا لعرض رساله الخطأ -->
            <div class="panel panel-info">
                <div class="panel-heading"><b> تعديل التصنيف ( <?php echo $row->cat_name; ?> )</b></div>
                <div class="panel-body">
                    <!-- الفورم دي خاصه بتعديل التصنيفات -->
                    <form action="" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label for="category" class="col-sm-3 control-label">اسم التصنيف</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="category" name="category" value="<?php echo $row->cat_name; ?>">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                <input type="submit" name="edite_cat" class="btn btn-info" value="تعديل التصنيف">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</article>
<?php include_once 'include/footer.php'; ?>
