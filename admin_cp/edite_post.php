<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$Msg   = ''; /*هنا لمنع ظهور خطأ من الرساله لأنها تظهر بعد الضغط وليس قبله*/
/*هنا بشوف ان كان ضغط علي زرار الارسال في الفورم ولا لا*/
if (isset($_POST['edite_post'])){

    $title    = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $post     = $_POST['post'];
    $category = $_POST['category'];
    $status   = $_POST['status'];

    if (empty($title)){
        $Msg = '<div class="alert alert-danger text-center" role="alert"><b>يجب وضع عنوان للمقال </b></div>';
    }elseif (empty($post)){
        $Msg = '<div class="alert alert-danger text-center" role="alert"><b>يجب كتابه المقال </b></div>';
    }elseif (empty($category)){
        $Msg = '<div class="alert alert-danger text-center" role="alert"><b>يجب اختيار التصنيف </b></div>';
    }else {
        $image = $_FILES['image']; /*هنا استخدمت $_FILES عشان اجيب كل بيانات الصوره*/
        $image_name = $image['name']; /*هنا اسم الصوره*/
        $image_temp = $image['tmp_name']; /*هنا مسار رفع الصوره */
        $image_size = $image['size']; /*هنا حجم الصوره */
        $image_error = $image['error']; /* لو فيه اخطاء لو مفيش يبقي ب 0*/
        if ($image_name != '') { /*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
            /*هنا جبت extention بتاع الصوره عن طريق Pathinfo وبعد كده حولت كل الحمروف لحروف small عن طريق strtolower*/
            /*              or this
              $image_Exe = explode('.' , $image_name);
              $image_Exe = strtolower(end($image_Exe));
            */
            $image_Exe = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $valid_exe = array('png', 'jpeg', 'jpg', 'gif'); /*هنا عملت array عشان احط فيها الصيغ اللي انا عايزها بس محدش يرفع غيرها*/
            if (in_array($image_Exe, $valid_exe)) {
                if ($image_error === 0) {
                    if ($image_size <= 3000000) {
                        /*هنا باعمل اسم جديد للصوره عن طريق استخدام الداله unique لعمل اسم عشوائي يبدأ ب user*/
                        $newName = uniqid('post', false) . "." . $image_Exe;
                        /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف new_post.php جوا فايل admin_cp*/
                        $image_dir = '../images/post_image/' . $newName;
                        $image_db = 'images/post_image/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                        if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                            $update = "UPDATE posts SET 
                                                        title = '$title' , 
                                                        post = '$post' , 
                                                        post_category = '$category' ,
                                                        post_image = '$image_db' ,
                                                        post_status = '$status' 
                                                  WHERE 
                                                        post_id = '$_GET[id]'";
                            $stmnt = $conn->prepare($update);
                            $stmnt->execute();
                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                $Msg = '<div class="alert alert-success" role="alert"><b> تم تعديل المقال بنجاح , جاري تحويلك لصفحه المقالات :) </b></div>';
                                echo '<meta http-equiv="refresh" content="3; \'posts.php\'">';
                            }
                        } else {
                            $Msg = '<div class="alert alert-danger" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                        }
                    } else {
                        $Msg = '<div class="alert alert-danger" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                    }
                } else {
                    $Msg = '<div class="alert alert-danger" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                }
            } else {
                $Msg = '<div class="alert alert-danger" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
            }
        } else {
            $update = "UPDATE posts SET 
                                        title = '$title' , 
                                        post = '$post' , 
                                        post_category = '$category' ,
                                        post_image = 'images/no-image-post.png' , 
                                        post_status = '$status' 
                                 WHERE 
                                        post_id = '$_GET[id]'";
            $stmnt = $conn->prepare($update);
            $stmnt->execute();
            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                $Msg = '<div class="alert alert-success" role="alert"><b> تم تعديل المقال بنجاح , جاري تحويلك لصفحه المقالات :) </b></div>';
                echo '<meta http-equiv="refresh" content="3; \'posts.php\'">';
            }
        }
    }
}

?>

<!-- Start article -->
<article class="col-lg-9">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10"> <!-- الديف دا خاص بالجزء بتاع عرض التصنيفات -->
            <?php
            echo $Msg;
            /*هنا عملت كويري بسيط عشان اجيب بيانات من جدول posts*/
            $stmnt = $conn->query("SELECT * FROM posts WHERE post_id = '$_GET[id]'");
            $row_select = $stmnt->fetch(PDO::FETCH_OBJ)
            ?> <!-- هنا لعرض رساله الخطأ -->
            <div class="panel panel-info">
                <div class="panel-heading"><b>تعديل المقال :</b> { <?php echo $row_select->title; ?> }</div> <!-- هنا باعرض العنوان اللي جبته من الاستعلام -->
                <div class="panel-body">
                    <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">عنوان المقال</label>
                            <div class="col-sm-5">
                                <!-- هنا باحط العنوان اللي جبته من الاستعلام في value عشان يظهر في الحقل-->
                                <input type="text" class="form-control" name="title" id="title" value="<?php echo $row_select->title; ?>" placeholder="أدخل عنوان المقال">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="post" class="col-sm-2 control-label">المقال</label>
                            <div class="col-sm-10">
                                <!-- هنا باعرض المقال اللي جبته من الاستعلام -->
                                <textarea rows="8" class="form-control" name="post" id="post"><?php echo $row_select->post; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category" class="col-sm-2 control-label">اختر التصنيف</label>
                            <div class="col-sm-3">
                                <select class="form-control" id="category" name="category">
                                    <option value="">اختر التصنيف</option>
                                    <?php
                                    /*هنا عملت استعلام بسيط عشان اجيب اسم التصنيف من الجدول وبعدين عملت لوب تكرر وتحط في select*/
                                    $stmnt = $conn->query("SELECT * FROM category");
                                    while ($row = $stmnt->fetch(PDO::FETCH_OBJ)){
                                        /*هنا بيجيب قيمه value من الجدول الخاص ب category ويحطها في option وباعمل شرط انه لو $row_select->post_category اللي في جدول posts كانت بتساوي $row->cat_name اللي في جدول category يختارها ويجيب قيمتها */
                                        echo '<option value="'.$row->cat_name.'" '.($row_select->post_category == $row->cat_name ? 'selected' : '').'>'.$row->cat_name.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image" class="col-sm-2 control-label">صوره للمقال</label>
                            <div class="col-sm-5">
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">الحاله</label>
                            <div class="col-sm-2">
                                <select class="form-control" id="status" name="status">
                                    <!-- هنا عملت شرط اشوف ان كان $row_select->post_status اللي في جدول posts بتساوي Published بيقوم يحددها ويجيب قيمتها -->
                                    <option value="Published" <?php if ($row_select->post_status == 'Published'){echo 'selected';} ?>>نشر</option>
                                    <!-- هنا عملت شرط اشوف ان كان $row_select->post_status اللي في جدول posts بتساوي Disable بيقوم يحددها ويجيب قيمتها -->
                                    <option value="Disable" <?php if ($row_select->post_status == 'Disable'){echo 'selected';} ?>>تعطيل</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="edite_post" class="btn btn-info">تعديل المقال</button>
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