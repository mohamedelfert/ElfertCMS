<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

if ($_SESSION['role'] != 'admin'){
    header('Location: index.php');
}

if (isset($_POST['submit'])){
    $query = $conn->query("SELECT * FROM setting");
    $count = $query->rowCount();
    if ($count != 1){
        $image = $_FILES['image']; /*هنا استخدمت $_FILES عشان اجيب كل بيانات الصوره*/
        $image_name = $image['name']; /*هنا اسم الصوره*/
        $image_temp = $image['tmp_name']; /*هنا مسار رفع الصوره */
        $image_size = $image['size']; /*هنا حجم الصوره */
        $image_error = $image['error']; /* لو فيه اخطاء لو مفيش يبقي ب 0*/
        if ($image_name != '') {/*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
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
                        $newName = uniqid('logo', false) . "." . $image_Exe;
                        /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                        $image_dir = '../images/' . $newName;
                        $image_db = 'images/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                        if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                            $insert = "INSERT INTO setting 
                                                          (
                                                            site_name,
                                                            site_logo,
                                                            slide,
                                                            slide_value,
                                                            section_a,
                                                            section_a_value,
                                                            section_b,
                                                            section_b_value,
                                                            tab_a,
                                                            tab_a_value,
                                                            tab_b,
                                                            tab_b_value,
                                                            tab_c,
                                                            tab_c_value,
                                                            facebook,
                                                            twitter,
                                                            google,
                                                            instegram) 
                                                    VALUE 
                                                          (
                                                            '$_POST[site_name]',
                                                            $image_db,
                                                            '$_POST[slide]',
                                                            '$_POST[slide_num]',
                                                            '$_POST[section_a]',
                                                            '$_POST[section_a_num]',
                                                            '$_POST[section_b]',
                                                            '$_POST[section_b_num]',
                                                            '$_POST[tab_a]',
                                                            '$_POST[tab_a_num]',
                                                            '$_POST[tab_b]',
                                                            '$_POST[tab_b_num]',
                                                            '$_POST[tab_c]',
                                                            '$_POST[tab_c_num]',
                                                            '$_POST[facebook]',
                                                            '$_POST[twitter]',
                                                            '$_POST[google]',
                                                            '$_POST[instegram]')";
                            $stmnt = $conn->prepare($insert);
                            $stmnt->execute();
                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                echo '<div class="alert alert-success text-center" role="alert"><b> تم تحديث اعدادات الموقع بنجاح :) </b></div>';
                                echo '<meta http-equiv="refresh" content="1; \'setting.php\'">';
                            }
                        }else {
                            echo '<div class="alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                        }
                    }else {
                        echo '<div class="alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                    }
                }else {
                    echo '<div class="alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                }
            }else {
                echo '<div class="alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
            }
        }else{
            $insert = "INSERT INTO setting 
                                          (
                                            site_name,
                                            site_logo,
                                            slide,
                                            slide_value,
                                            section_a,
                                            section_a_value,
                                            section_b,
                                            section_b_value,
                                            tab_a,
                                            tab_a_value,
                                            tab_b,
                                            tab_b_value,
                                            tab_c,
                                            tab_c_value,
                                            facebook,
                                            twitter,
                                            google,
                                            instegram) 
                                    VALUE 
                                          (
                                            '$_POST[site_name]',
                                            'images/logo.png',
                                            '$_POST[slide]',
                                            '$_POST[slide_num]',
                                            '$_POST[section_a]',
                                            '$_POST[section_a_num]',
                                            '$_POST[section_b]',
                                            '$_POST[section_b_num]',
                                            '$_POST[tab_a]',
                                            '$_POST[tab_a_num]',
                                            '$_POST[tab_b]',
                                            '$_POST[tab_b_num]',
                                            '$_POST[tab_c]',
                                            '$_POST[tab_c_num]',
                                            '$_POST[facebook]',
                                            '$_POST[twitter]',
                                            '$_POST[google]',
                                            '$_POST[instegram]')";
            $stmnt = $conn->prepare($insert);
            $stmnt->execute();
            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                echo '<div class="alert alert-success text-center" role="alert"><b> تم تحديث اعدادات الموقع بنجاح :) </b></div>';
                echo '<meta http-equiv="refresh" content="1; \'setting.php\'">';
            }
        }
    }else{
        $image = $_FILES['image']; /*هنا استخدمت $_FILES عشان اجيب كل بيانات الصوره*/
        $image_name = $image['name']; /*هنا اسم الصوره*/
        $image_temp = $image['tmp_name']; /*هنا مسار رفع الصوره */
        $image_size = $image['size']; /*هنا حجم الصوره */
        $image_error = $image['error']; /* لو فيه اخطاء لو مفيش يبقي ب 0*/
        if ($image_name != '') {/*هنا بشوف ان كان حاطت صوره للمقال ولا لا*/
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
                        $newName = uniqid('logo', false) . "." . $image_Exe;
                        /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                        $image_dir = '../images/' . $newName;
                        $image_db = 'images/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                        if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                            $update = "UPDATE setting SET  
                                                        site_name       = '$_POST[site_name]' ,
                                                        site_logo       = '$image_db' ,
                                                        slide           = '$_POST[slide]' , 
                                                        slide_value     = '$_POST[slide_num]' ,
                                                        section_a       = '$_POST[section_a]' ,
                                                        section_a_value = '$_POST[section_a_num]' ,
                                                        section_b       = '$_POST[section_b]' ,
                                                        section_b_value = '$_POST[section_b_num]' ,
                                                        tab_a           = '$_POST[tab_a]',
                                                        tab_a_value     = '$_POST[tab_a_num]',
                                                        tab_b           = '$_POST[tab_b]',
                                                        tab_b_value     = '$_POST[tab_b_num]',
                                                        tab_c           = '$_POST[tab_c]',
                                                        tab_c_value     = '$_POST[tab_c_num]',
                                                        facebook        = '$_POST[facebook]',
                                                        twitter         = '$_POST[twitter]',
                                                        google          = '$_POST[google]',
                                                        instegram       = '$_POST[instegram]'";
                            $stmnt = $conn->prepare($update);
                            $stmnt->execute();
                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                echo '<div class="alert alert-success text-center" role="alert"><b> تم تحديث اعدادات الموقع بنجاح :) </b></div>';
                                echo '<meta http-equiv="refresh" content="1; \'setting.php\'">';
                            }
                        }else {
                            echo '<div class="alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                        }
                    }else {
                        echo '<div class="alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                    }
                }else {
                    echo '<div class="alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                }
            }else {
                echo '<div class="alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
            }
        }else{
            $update = "UPDATE setting SET  
                                        site_name       = '$_POST[site_name]' ,
                                        site_logo       = 'images/logo.png' ,
                                        slide           = '$_POST[slide]' , 
                                        slide_value     = '$_POST[slide_num]' ,
                                        section_a       = '$_POST[section_a]' ,
                                        section_a_value = '$_POST[section_a_num]' ,
                                        section_b       = '$_POST[section_b]' ,
                                        section_b_value = '$_POST[section_b_num]' ,
                                        tab_a           = '$_POST[tab_a]',
                                        tab_a_value     = '$_POST[tab_a_num]',
                                        tab_b           = '$_POST[tab_b]',
                                        tab_b_value     = '$_POST[tab_b_num]',
                                        tab_c           = '$_POST[tab_c]',
                                        tab_c_value     = '$_POST[tab_c_num]',
                                        facebook        = '$_POST[facebook]',
                                        twitter         = '$_POST[twitter]',
                                        google          = '$_POST[google]',
                                        instegram       = '$_POST[instegram]'";
            $stmnt = $conn->prepare($update);
            $stmnt->execute();
            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                echo '<div class="alert alert-success text-center" role="alert"><b> تم تحديث اعدادات الموقع بنجاح :) </b></div>';
                echo '<meta http-equiv="refresh" content="1; \'setting.php\'">';
            }
        }
    }
}

$select_setting = $conn->query("SELECT * FROM setting");
$row_setting    = $select_setting->fetch(PDO::FETCH_OBJ);

function category($x){
    global $conn;
    /*هنا عملت استعلام بسيط عشان اجيب اسم التصنيف من الجدول وبعدين عملت لوب تكرر وتحط في select*/
    $stmnt = $conn->query("SELECT * FROM category");
    while ($row = $stmnt->fetch(PDO::FETCH_OBJ)){
        /*هنا بيجيب قيمه value من الجدول الخاص ب category ويحطها في option*/
        echo '<option value="'.$row->cat_name.'" '.($x == $row->cat_name ? 'selected' : '').'>'.$row->cat_name.'</option>';
    }
}
?>

<!-- Start article -->
<article class="col-lg-9">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="panel panel-info">
            <div class="panel-heading"><b>اعدادات الموقع</b></div>
            <div class="panel-body">
                <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="site_name" class="col-sm-2 control-label">اسم الموقع : </label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="site_name" value="<?php echo $row_setting->site_name; ?>" id="site_name" placeholder="أدخل اسم الموقع هنا">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">شعار الموقع : </label>
                        <div class="col-sm-5">
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="slide" class="col-sm-2 control-label">السلايد شو : </label>
                        <div class="col-sm-3">
                            <select class="form-control" id="slide" name="slide">
                                <option value="">اختر التصنيف</option>
                                <?php category($row_setting->slide); ?>
                            </select>
                        </div>
                        <label for="slide_num" class="col-sm-2 control-label">عدد المقالات : </label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="slide_num" name="slide_num" value="<?php echo ($row_setting->slide_value == '' ? '3' : $row_setting->slide_value); ?>" min="3" max="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="section_a" class="col-sm-2 control-label">القسم الأول : </label>
                        <div class="col-sm-3">
                            <select class="form-control" id="section_a" name="section_a">
                                <option value="">اختر التصنيف</option>
                                <?php category($row_setting->section_a); ?>
                            </select>
                        </div>
                        <label for="section_a_num" class="col-sm-2 control-label">عدد المقالات : </label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="section_a_num" name="section_a_num" value="<?php echo ($row_setting->section_a_value == '' ? '3' : $row_setting->section_a_value); ?>" min="3" max="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="section_b" class="col-sm-2 control-label">القسم الثاني : </label>
                        <div class="col-sm-3">
                            <select class="form-control" id="section_b" name="section_b">
                                <option value="">اختر التصنيف</option>
                                <?php category($row_setting->section_b); ?>
                            </select>
                        </div>
                        <label for="section_b_num" class="col-sm-2 control-label">عدد المقالات : </label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="section_b_num" name="section_b_num" value="<?php echo ($row_setting->section_b_value == '' ? '3' : $row_setting->section_b_value); ?>" min="3" max="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tab_a" class="col-sm-2 control-label">التاب الأول : </label>
                        <div class="col-sm-3">
                            <select class="form-control" id="tab_a" name="tab_a">
                                <option value="">اختر التصنيف</option>
                                <?php category($row_setting->tab_a); ?>
                            </select>
                        </div>
                        <label for="tab_a_num" class="col-sm-2 control-label">عدد المقالات : </label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="tab_a_num" name="tab_a_num" value="<?php echo ($row_setting->tab_a_value == '' ? '3' : $row_setting->tab_a_value); ?>" min="3" max="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tab_b" class="col-sm-2 control-label">التاب الثاني : </label>
                        <div class="col-sm-3">
                            <select class="form-control" id="tab_b" name="tab_b">
                                <option value="">اختر التصنيف</option>
                                <?php category($row_setting->tab_b); ?>
                            </select>
                        </div>
                        <label for="tab_b_num" class="col-sm-2 control-label">عدد المقالات : </label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="tab_b_num" name="tab_b_num" value="<?php echo ($row_setting->tab_b_value == '' ? '3' : $row_setting->tab_b_value); ?>" min="3" max="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tab_c" class="col-sm-2 control-label">التاب الثالث : </label>
                        <div class="col-sm-3">
                            <select class="form-control" id="tab_c" name="tab_c">
                                <option value="">اختر التصنيف</option>
                                <?php category($row_setting->tab_c); ?>
                            </select>
                        </div>
                        <label for="tab_c_num" class="col-sm-2 control-label">عدد المقالات : </label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" id="tab_c_num" name="tab_c_num" value="<?php echo ($row_setting->tab_c_value == '' ? '3' : $row_setting->tab_c_value); ?>" min="3" max="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="facebook" class="col-sm-2 control-label">Facebook : </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="facebook" value="<?php echo $row_setting->facebook; ?>" id="facebook" placeholder="أدخل رابط الفيس بوك هنا">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="twitter" class="col-sm-2 control-label">Twitter : </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="twitter" value="<?php echo $row_setting->twitter; ?>" id="twitter" placeholder="أدخل رابط تويتر هنا">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="google" class="col-sm-2 control-label">Google+ : </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="google" value="<?php echo $row_setting->google; ?>" id="google" placeholder="أدخل رابط جوجل بلس هنا">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="instegram" class="col-sm-2 control-label">Instegram : </label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="instegram" value="<?php echo $row_setting->instegram; ?>" id="instegram" placeholder="أدخل رابط انستجرام هنا">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="submit" class="btn btn-danger"><b>تحديث الاعدادات</b></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</article>
<!-- End article -->

<?php include_once 'include/footer.php'; ?>