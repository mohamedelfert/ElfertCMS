<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$id = intval($_GET['id']); /*هنا باجيب id اللي انا واقف عنده او اللي جاي في الرابط*/
/*هنا عملت كويري بسيط عشان اجيب بيانات من جدول users*/
$stmnt = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
$row_select = $stmnt->fetch(PDO::FETCH_OBJ);

if ($_SESSION['id'] != $id){
    header('Location: index.php');
}

/*هنا باشوف ان ضغط زرار الارسال ينفذ اللي فيه بقا*/
if (isset($_POST['send'])){
    /*هنا باجيب كل المدخلات اللي في الصفحه عندي واحطها في فاريبل عشان استخدمها*/
    $username     = filter_var($_POST['username'],FILTER_SANITIZE_STRING); /*هنا استخدمت فلتر للاسم عشان امنع كتابه اي تاجات html*/
    $email        = $_POST['email'];

    /*هنا باتحقق من المدخلات وكده اذا كانت فاضيه ولا لا*/
    if (empty($username)){
        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء ادخال اسم المستخدم </b></div>';
    }elseif (empty($email)) {
        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء ادخال البريد الالكتروني </b></div>';
    }elseif (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){  /*هنا استخدمت فلتر للايميل عشان أتأكد ان كان المدخل ايميل صحيح ولا لا*/
        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء ادخال بريد الكتروني صحيح </b></div>';
    }else {
        $sql = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$email'");
        $count = $sql->rowCount();
        if ($count > 0) {
            if ($username == $row_select->username AND $email == $row_select->email){
                if ($_POST['password'] != '' OR $_POST['con_password'] != ''){
                    if ($_POST['password'] != $_POST['con_password']){
                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عفوا كلمه المرور غير متطابقه</b></div>';
                    }else{
                        $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
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
                                        $newName = uniqid('user', false) . "." . $image_Exe;
                                        /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                                        $image_dir = 'images/avatar/' . $newName;
                                        $image_db = 'images/avatar/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                                        if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                            $update = "UPDATE users SET 
                                                                        password = '$password' , 
                                                                        gender = '$_POST[gender]' , 
                                                                        avatar = '$image_db' ,
                                                                        about_user = '$_POST[about]' ,
                                                                        facebook = '$_POST[facebook]' ,
                                                                        twitter = '$_POST[twitter]' ,
                                                                        youtube = '$_POST[youtube]'   
                                                                  WHERE 
                                                                        user_id = '$id'";
                                            $stmnt = $conn->prepare($update);
                                            $stmnt->execute();
                                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                                session_unset();
                                                $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                                $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']            = $row_info->user_id;
                                                $_SESSION['username']      = $row_info->username;
                                                $_SESSION['email']         = $row_info->email;
                                                $_SESSION['gender']        = $row_info->gender;
                                                $_SESSION['avatar']        = $row_info->avatar;
                                                $_SESSION['about_user']    = $row_info->about_user;
                                                $_SESSION['facebook']      = $row_info->facebook;
                                                $_SESSION['twitter']       = $row_info->twitter;
                                                $_SESSION['youtube']       = $row_info->youtube;
                                                $_SESSION['register_date'] = $row_info->register_date;
                                                $_SESSION['role']          = $row_info->role;
                                                echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                                echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                            }
                                        }else {
                                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else {
                                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                                    }
                                }else {
                                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                }
                            }else {
                                echo '<div class="alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
                            $update = "UPDATE users SET 
                                                        password = '$password' , 
                                                        gender = '$_POST[gender]' ,           
                                                        about_user = '$_POST[about]' ,
                                                        facebook = '$_POST[facebook]' ,
                                                        twitter = '$_POST[twitter]' ,
                                                        youtube = '$_POST[youtube]' 
                                                  WHERE 
                                                        user_id = '$id'";
                            $stmnt = $conn->prepare($update);
                            $stmnt->execute();
                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                session_unset();
                                $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']            = $row_info->user_id;
                                $_SESSION['username']      = $row_info->username;
                                $_SESSION['email']         = $row_info->email;
                                $_SESSION['gender']        = $row_info->gender;
                                $_SESSION['avatar']        = $row_info->avatar;
                                $_SESSION['about_user']    = $row_info->about_user;
                                $_SESSION['facebook']      = $row_info->facebook;
                                $_SESSION['twitter']       = $row_info->twitter;
                                $_SESSION['youtube']       = $row_info->youtube;
                                $_SESSION['register_date'] = $row_info->register_date;
                                $_SESSION['role']          = $row_info->role;
                                echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                            }
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
                                    $newName = uniqid('user', false) . "." . $image_Exe;
                                    /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                                    $image_dir = 'images/avatar/' . $newName;
                                    $image_db = 'images/avatar/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                                    if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                        $update = "UPDATE users SET  
                                                                    gender = '$_POST[gender]' , 
                                                                    avatar = '$image_db' ,
                                                                    about_user = '$_POST[about]' ,
                                                                    facebook = '$_POST[facebook]' ,
                                                                    twitter = '$_POST[twitter]' ,
                                                                    youtube = '$_POST[youtube]' 
                                                              WHERE 
                                                                    user_id = '$id'";
                                        $stmnt = $conn->prepare($update);
                                        $stmnt->execute();
                                        if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                            session_unset();
                                            $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                            $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                            $_SESSION['id']            = $row_info->user_id;
                                            $_SESSION['username']      = $row_info->username;
                                            $_SESSION['email']         = $row_info->email;
                                            $_SESSION['gender']        = $row_info->gender;
                                            $_SESSION['avatar']        = $row_info->avatar;
                                            $_SESSION['about_user']    = $row_info->about_user;
                                            $_SESSION['facebook']      = $row_info->facebook;
                                            $_SESSION['twitter']       = $row_info->twitter;
                                            $_SESSION['youtube']       = $row_info->youtube;
                                            $_SESSION['register_date'] = $row_info->register_date;
                                            $_SESSION['role']          = $row_info->role;
                                            echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                            echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                        }
                                    }else {
                                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                    }
                                }else {
                                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                                }
                            }else {
                                echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                            }
                        }else {
                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                        }
                    }else{
                        $update = "UPDATE users SET  
                                                    gender = '$_POST[gender]' , 
                                                    about_user = '$_POST[about]' ,
                                                    facebook = '$_POST[facebook]' ,
                                                    twitter = '$_POST[twitter]' ,
                                                    youtube = '$_POST[youtube]' 
                                                WHERE 
                                                    user_id = '$id'";
                        $stmnt = $conn->prepare($update);
                        $stmnt->execute();
                        if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                            session_unset();
                            $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                            $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                            $_SESSION['id']            = $row_info->user_id;
                            $_SESSION['username']      = $row_info->username;
                            $_SESSION['email']         = $row_info->email;
                            $_SESSION['gender']        = $row_info->gender;
                            $_SESSION['avatar']        = $row_info->avatar;
                            $_SESSION['about_user']    = $row_info->about_user;
                            $_SESSION['facebook']      = $row_info->facebook;
                            $_SESSION['twitter']       = $row_info->twitter;
                            $_SESSION['youtube']       = $row_info->youtube;
                            $_SESSION['register_date'] = $row_info->register_date;
                            $_SESSION['role']          = $row_info->role;
                            echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                            echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                        }
                    }
                }
            }elseif ($username != $row_select->username AND $email == $row_select->email){
                $sql = $conn->query("SELECT username FROM users WHERE username = '$username'");
                $count = $sql->rowCount();
                if ($count > 0){
                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>اسم المستخدم مسجل بالفعل</b></div>';
                }else{
                    if ($_POST['password'] != '' OR $_POST['con_password'] != ''){
                        if ($_POST['password'] != $_POST['con_password']){
                            echo '<div class="alert alert-danger text-center" role="alert"><b>عفوا كلمه المرور غير متطابقه</b></div>';
                        }else{
                            $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
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
                                            $newName = uniqid('user', false) . "." . $image_Exe;
                                            /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                                            $image_dir = 'images/avatar/' . $newName;
                                            $image_db = 'images/avatar/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                                            if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                                $update = "UPDATE users SET 
                                                                            username = '$username' ,
                                                                            password = '$password' , 
                                                                            gender = '$_POST[gender]' , 
                                                                            avatar = '$image_db' ,
                                                                            about_user = '$_POST[about]' ,
                                                                            facebook = '$_POST[facebook]' ,
                                                                            twitter = '$_POST[twitter]' ,
                                                                            youtube = '$_POST[youtube]' 
                                                                      WHERE 
                                                                            user_id = '$id'";
                                                $stmnt = $conn->prepare($update);
                                                $stmnt->execute();
                                                if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                                    session_unset();
                                                    $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                                    $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                                    $_SESSION['id']            = $row_info->user_id;
                                                    $_SESSION['username']      = $row_info->username;
                                                    $_SESSION['email']         = $row_info->email;
                                                    $_SESSION['gender']        = $row_info->gender;
                                                    $_SESSION['avatar']        = $row_info->avatar;
                                                    $_SESSION['about_user']    = $row_info->about_user;
                                                    $_SESSION['facebook']      = $row_info->facebook;
                                                    $_SESSION['twitter']       = $row_info->twitter;
                                                    $_SESSION['youtube']       = $row_info->youtube;
                                                    $_SESSION['register_date'] = $row_info->register_date;
                                                    $_SESSION['role']          = $row_info->role;
                                                    echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                                    echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                                }
                                            }else {
                                                echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                            }
                                        }else {
                                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                                        }
                                    }else {
                                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                    }
                                }else {
                                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                                }
                            }else{
                                $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
                                $update = "UPDATE users SET
                                                            username = '$username' ,
                                                            password = '$password' , 
                                                            gender = '$_POST[gender]' , 
                                                            about_user = '$_POST[about]' ,
                                                            facebook = '$_POST[facebook]' ,
                                                            twitter = '$_POST[twitter]' ,
                                                            youtube = '$_POST[youtube]' 
                                                      WHERE 
                                                            user_id = '$id'";
                                $stmnt = $conn->prepare($update);
                                $stmnt->execute();
                                if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                    session_unset();
                                    $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                    $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                    $_SESSION['id']            = $row_info->user_id;
                                    $_SESSION['username']      = $row_info->username;
                                    $_SESSION['email']         = $row_info->email;
                                    $_SESSION['gender']        = $row_info->gender;
                                    $_SESSION['avatar']        = $row_info->avatar;
                                    $_SESSION['about_user']    = $row_info->about_user;
                                    $_SESSION['facebook']      = $row_info->facebook;
                                    $_SESSION['twitter']       = $row_info->twitter;
                                    $_SESSION['youtube']       = $row_info->youtube;
                                    $_SESSION['register_date'] = $row_info->register_date;
                                    $_SESSION['role']          = $row_info->role;
                                    echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                    echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                }
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
                                        $newName = uniqid('user', false) . "." . $image_Exe;
                                        /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                                        $image_dir = 'images/avatar/' . $newName;
                                        $image_db = 'images/avatar/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                                        if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                            $update = "UPDATE users SET
                                                                        username = '$username' ,
                                                                        gender = '$_POST[gender]' , 
                                                                        avatar = '$image_db' ,
                                                                        about_user = '$_POST[about]' ,
                                                                        facebook = '$_POST[facebook]' ,
                                                                        twitter = '$_POST[twitter]' ,
                                                                        youtube = '$_POST[youtube]' 
                                                                  WHERE 
                                                                        user_id = '$id'";
                                            $stmnt = $conn->prepare($update);
                                            $stmnt->execute();
                                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                                session_unset();
                                                $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                                $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']            = $row_info->user_id;
                                                $_SESSION['username']      = $row_info->username;
                                                $_SESSION['email']         = $row_info->email;
                                                $_SESSION['gender']        = $row_info->gender;
                                                $_SESSION['avatar']        = $row_info->avatar;
                                                $_SESSION['about_user']    = $row_info->about_user;
                                                $_SESSION['facebook']      = $row_info->facebook;
                                                $_SESSION['twitter']       = $row_info->twitter;
                                                $_SESSION['youtube']       = $row_info->youtube;
                                                $_SESSION['register_date'] = $row_info->register_date;
                                                $_SESSION['role']          = $row_info->role;
                                                echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                                echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                            }
                                        }else {
                                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else {
                                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                                    }
                                }else {
                                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                }
                            }else {
                                echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $update = "UPDATE users SET  
                                                        username = '$username' ,
                                                        gender = '$_POST[gender]' , 
                                                        about_user = '$_POST[about]' ,
                                                        facebook = '$_POST[facebook]' ,
                                                        twitter = '$_POST[twitter]' ,
                                                        youtube = '$_POST[youtube]' 
                                                    WHERE 
                                                        user_id = '$id'";
                            $stmnt = $conn->prepare($update);
                            $stmnt->execute();
                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                session_unset();
                                $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']            = $row_info->user_id;
                                $_SESSION['username']      = $row_info->username;
                                $_SESSION['email']         = $row_info->email;
                                $_SESSION['gender']        = $row_info->gender;
                                $_SESSION['avatar']        = $row_info->avatar;
                                $_SESSION['about_user']    = $row_info->about_user;
                                $_SESSION['facebook']      = $row_info->facebook;
                                $_SESSION['twitter']       = $row_info->twitter;
                                $_SESSION['youtube']       = $row_info->youtube;
                                $_SESSION['register_date'] = $row_info->register_date;
                                $_SESSION['role']          = $row_info->role;
                                echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                            }
                        }
                    }
                }
            }elseif ($username == $row_select->username AND $email != $row_select->email){
                $sql = $conn->query("SELECT email FROM users WHERE email = '$email'");
                $count = $sql->rowCount();
                if ($count > 0){
                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b> البريد الالكتروني مسجل بالفعل</b></div>';
                }else{
                    if ($_POST['password'] != '' OR $_POST['con_password'] != ''){
                        if ($_POST['password'] != $_POST['con_password']){
                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عفوا كلمه المرور غير متطابقه</b></div>';
                        }else{
                            $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
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
                                            $newName = uniqid('user', false) . "." . $image_Exe;
                                            /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                                            $image_dir = 'images/avatar/' . $newName;
                                            $image_db = 'images/avatar/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                                            if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                                $update = "UPDATE users SET 
                                                                            email = '$email' ,
                                                                            password = '$password' , 
                                                                            gender = '$_POST[gender]' , 
                                                                            avatar = '$image_db' ,
                                                                            about_user = '$_POST[about]' ,
                                                                            facebook = '$_POST[facebook]' ,
                                                                            twitter = '$_POST[twitter]' ,
                                                                            youtube = '$_POST[youtube]' 
                                                                      WHERE 
                                                                            user_id = '$id'";
                                                $stmnt = $conn->prepare($update);
                                                $stmnt->execute();
                                                if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                                    session_unset();
                                                    $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                                    $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                                    $_SESSION['id']            = $row_info->user_id;
                                                    $_SESSION['username']      = $row_info->username;
                                                    $_SESSION['email']         = $row_info->email;
                                                    $_SESSION['gender']        = $row_info->gender;
                                                    $_SESSION['avatar']        = $row_info->avatar;
                                                    $_SESSION['about_user']    = $row_info->about_user;
                                                    $_SESSION['facebook']      = $row_info->facebook;
                                                    $_SESSION['twitter']       = $row_info->twitter;
                                                    $_SESSION['youtube']       = $row_info->youtube;
                                                    $_SESSION['register_date'] = $row_info->register_date;
                                                    $_SESSION['role']          = $row_info->role;
                                                    echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                                    echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                                }
                                            }else {
                                                echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                            }
                                        }else {
                                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                                        }
                                    }else {
                                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                    }
                                }else {
                                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                                }
                            }else{
                                $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
                                $update = "UPDATE users SET
                                                            email = '$email' ,
                                                            password = '$password' , 
                                                            gender = '$_POST[gender]' , 
                                                            about_user = '$_POST[about]' ,
                                                            facebook = '$_POST[facebook]' ,
                                                            twitter = '$_POST[twitter]' ,
                                                            youtube = '$_POST[youtube]' 
                                                      WHERE 
                                                            user_id = '$id'";
                                $stmnt = $conn->prepare($update);
                                $stmnt->execute();
                                if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                    session_unset();
                                    $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                    $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                    $_SESSION['id']            = $row_info->user_id;
                                    $_SESSION['username']      = $row_info->username;
                                    $_SESSION['email']         = $row_info->email;
                                    $_SESSION['gender']        = $row_info->gender;
                                    $_SESSION['avatar']        = $row_info->avatar;
                                    $_SESSION['about_user']    = $row_info->about_user;
                                    $_SESSION['facebook']      = $row_info->facebook;
                                    $_SESSION['twitter']       = $row_info->twitter;
                                    $_SESSION['youtube']       = $row_info->youtube;
                                    $_SESSION['register_date'] = $row_info->register_date;
                                    $_SESSION['role']          = $row_info->role;
                                    echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                    echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                }
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
                                        $newName = uniqid('user', false) . "." . $image_Exe;
                                        /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                                        $image_dir = 'images/avatar/' . $newName;
                                        $image_db = 'images/avatar/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                                        if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                            $update = "UPDATE users SET
                                                                        email = '$email' ,
                                                                        gender = '$_POST[gender]' , 
                                                                        avatar = '$image_db' ,
                                                                        about_user = '$_POST[about]' ,
                                                                        facebook = '$_POST[facebook]' ,
                                                                        twitter = '$_POST[twitter]' ,
                                                                        youtube = '$_POST[youtube]' 
                                                                  WHERE 
                                                                        user_id = '$id'";
                                            $stmnt = $conn->prepare($update);
                                            $stmnt->execute();
                                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                                session_unset();
                                                $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                                $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                                $_SESSION['id']            = $row_info->user_id;
                                                $_SESSION['username']      = $row_info->username;
                                                $_SESSION['email']         = $row_info->email;
                                                $_SESSION['gender']        = $row_info->gender;
                                                $_SESSION['avatar']        = $row_info->avatar;
                                                $_SESSION['about_user']    = $row_info->about_user;
                                                $_SESSION['facebook']      = $row_info->facebook;
                                                $_SESSION['twitter']       = $row_info->twitter;
                                                $_SESSION['youtube']       = $row_info->youtube;
                                                $_SESSION['register_date'] = $row_info->register_date;
                                                $_SESSION['role']          = $row_info->role;
                                                echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                                echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                            }
                                        }else {
                                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                        }
                                    }else {
                                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                                    }
                                }else {
                                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                }
                            }else {
                                echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                            }
                        }else{
                            $update = "UPDATE users SET  
                                                        email = '$email' ,
                                                        gender = '$_POST[gender]' , 
                                                        about_user = '$_POST[about]' ,
                                                        facebook = '$_POST[facebook]' ,
                                                        twitter = '$_POST[twitter]' ,
                                                        youtube = '$_POST[youtube]' 
                                                    WHERE 
                                                        user_id = '$id'";
                            $stmnt = $conn->prepare($update);
                            $stmnt->execute();
                            if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                session_unset();
                                $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                $_SESSION['id']            = $row_info->user_id;
                                $_SESSION['username']      = $row_info->username;
                                $_SESSION['email']         = $row_info->email;
                                $_SESSION['gender']        = $row_info->gender;
                                $_SESSION['avatar']        = $row_info->avatar;
                                $_SESSION['about_user']    = $row_info->about_user;
                                $_SESSION['facebook']      = $row_info->facebook;
                                $_SESSION['twitter']       = $row_info->twitter;
                                $_SESSION['youtube']       = $row_info->youtube;
                                $_SESSION['register_date'] = $row_info->register_date;
                                $_SESSION['role']          = $row_info->role;
                                echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                            }
                        }
                    }
                }
            }else{
                echo '<div class="alert alert-danger text-center" role="alert"><b>اسم المستخدم أو البريد الالكتروني مسجل بالفعل</b></div>';
            }
        }else {
            /*هنا هنغير الاسم والبريد مع بعض وهما مش موجودين في القاعده عندنا*/
            if ($_POST['password'] != '' OR $_POST['con_password'] != ''){
                if ($_POST['password'] != $_POST['con_password']){
                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عفوا كلمه المرور غير متطابقه</b></div>';
                }else{
                    $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
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
                                    $newName = uniqid('user', false) . "." . $image_Exe;
                                    /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                                    $image_dir = 'images/avatar/' . $newName;
                                    $image_db = 'images/avatar/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                                    if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                        $update = "UPDATE users SET 
                                                                    username = '$username' ,
                                                                    email = '$email' ,
                                                                    password = '$password' , 
                                                                    gender = '$_POST[gender]' , 
                                                                    avatar = '$image_db' ,
                                                                    about_user = '$_POST[about]' ,
                                                                    facebook = '$_POST[facebook]' ,
                                                                    twitter = '$_POST[twitter]' ,
                                                                    youtube = '$_POST[youtube]' 
                                                              WHERE 
                                                                    user_id = '$id'";
                                        $stmnt = $conn->prepare($update);
                                        $stmnt->execute();
                                        if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                            session_unset();
                                            $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                            $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                            $_SESSION['id']            = $row_info->user_id;
                                            $_SESSION['username']      = $row_info->username;
                                            $_SESSION['email']         = $row_info->email;
                                            $_SESSION['gender']        = $row_info->gender;
                                            $_SESSION['avatar']        = $row_info->avatar;
                                            $_SESSION['about_user']    = $row_info->about_user;
                                            $_SESSION['facebook']      = $row_info->facebook;
                                            $_SESSION['twitter']       = $row_info->twitter;
                                            $_SESSION['youtube']       = $row_info->youtube;
                                            $_SESSION['register_date'] = $row_info->register_date;
                                            $_SESSION['role']          = $row_info->role;
                                            echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                            echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                        }
                                    }else {
                                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                    }
                                }else {
                                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                                }
                            }else {
                                echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                            }
                        }else {
                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                        }
                    }else{
                        $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
                        $update = "UPDATE users SET
                                                    username = '$username' ,
                                                    email = '$email' ,
                                                    password = '$password' , 
                                                    gender = '$_POST[gender]' , 
                                                    about_user = '$_POST[about]' ,
                                                    facebook = '$_POST[facebook]' ,
                                                    twitter = '$_POST[twitter]' ,
                                                    youtube = '$_POST[youtube]' 
                                              WHERE 
                                                    user_id = '$id'";
                        $stmnt = $conn->prepare($update);
                        $stmnt->execute();
                        if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                            session_unset();
                            $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                            $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                            $_SESSION['id']            = $row_info->user_id;
                            $_SESSION['username']      = $row_info->username;
                            $_SESSION['email']         = $row_info->email;
                            $_SESSION['gender']        = $row_info->gender;
                            $_SESSION['avatar']        = $row_info->avatar;
                            $_SESSION['about_user']    = $row_info->about_user;
                            $_SESSION['facebook']      = $row_info->facebook;
                            $_SESSION['twitter']       = $row_info->twitter;
                            $_SESSION['youtube']       = $row_info->youtube;
                            $_SESSION['register_date'] = $row_info->register_date;
                            $_SESSION['role']          = $row_info->role;
                            echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                            echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                        }
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
                                $newName = uniqid('user', false) . "." . $image_Exe;
                                /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف edite_user.php جوا فايل admin_cp*/
                                $image_dir = 'images/avatar/' . $newName;
                                $image_db = 'images/avatar/' . $newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                                if (move_uploaded_file($image_temp, $image_dir)) { /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                    $update = "UPDATE users SET
                                                                username = '$username' ,
                                                                email = '$email' ,
                                                                gender = '$_POST[gender]' , 
                                                                avatar = '$image_db' ,
                                                                about_user = '$_POST[about]' ,
                                                                facebook = '$_POST[facebook]' ,
                                                                twitter = '$_POST[twitter]' ,
                                                                youtube = '$_POST[youtube]' 
                                                          WHERE 
                                                                user_id = '$id'";
                                    $stmnt = $conn->prepare($update);
                                    $stmnt->execute();
                                    if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                        session_unset();
                                        $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                                        $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                                        $_SESSION['id']            = $row_info->user_id;
                                        $_SESSION['username']      = $row_info->username;
                                        $_SESSION['email']         = $row_info->email;
                                        $_SESSION['gender']        = $row_info->gender;
                                        $_SESSION['avatar']        = $row_info->avatar;
                                        $_SESSION['about_user']    = $row_info->about_user;
                                        $_SESSION['facebook']      = $row_info->facebook;
                                        $_SESSION['twitter']       = $row_info->twitter;
                                        $_SESSION['youtube']       = $row_info->youtube;
                                        $_SESSION['register_date'] = $row_info->register_date;
                                        $_SESSION['role']          = $row_info->role;
                                        echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                                        echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                                    }
                                }else {
                                    echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                                }
                            }else {
                                echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                            }
                        }else {
                            echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                        }
                    }else {
                        echo '<div class="col-lg-9 alert alert-danger text-center" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                    }
                }else{
                    $update = "UPDATE users SET  
                                                username = '$username' ,
                                                email = '$email' ,
                                                gender = '$_POST[gender]' , 
                                                about_user = '$_POST[about]' ,
                                                facebook = '$_POST[facebook]' ,
                                                twitter = '$_POST[twitter]' ,
                                                youtube = '$_POST[youtube]' 
                                          WHERE 
                                                user_id = '$id'";
                    $stmnt = $conn->prepare($update);
                    $stmnt->execute();
                    if (isset($stmnt)) { /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                        session_unset();
                        $user_info = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
                        $row_info  = $user_info->fetch(PDO::FETCH_OBJ);
                        $_SESSION['id']            = $row_info->user_id;
                        $_SESSION['username']      = $row_info->username;
                        $_SESSION['email']         = $row_info->email;
                        $_SESSION['gender']        = $row_info->gender;
                        $_SESSION['avatar']        = $row_info->avatar;
                        $_SESSION['about_user']    = $row_info->about_user;
                        $_SESSION['facebook']      = $row_info->facebook;
                        $_SESSION['twitter']       = $row_info->twitter;
                        $_SESSION['youtube']       = $row_info->youtube;
                        $_SESSION['register_date'] = $row_info->register_date;
                        $_SESSION['role']          = $row_info->role;
                        echo '<div class="col-lg-9 alert alert-success text-center" role="alert"><b> تم تحديث بيانات العضو بنجاح , جاري تحويلك للصفحه الشخصيه :) </b></div>';
                        echo '<meta http-equiv="refresh" content="3; \'profile.php?user='.$id.'\'">';
                    }
                }
            }
        }
    }
}

/*هنا عملت كويري بسيط عشان اجيب بيانات من جدول users*/
$stmnt = $conn->query("SELECT * FROM users WHERE user_id = '$id'");
$row_select = $stmnt->fetch(PDO::FETCH_OBJ);
?>

<!-- Start article -->
<article class="col-lg-9">
    <div class="panel panel-info">
        <div class="panel-heading"><b>تعديل بيانات العضو</b> { <?php echo $row_select->username; ?> }</div>
        <div class="panel-body">
            <form action="" method="post" class="form-horizontal col-md-9" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label"><span style="color: red;">*</span> اسم العضو :</label>
                    <div class="col-sm-5">
                        <input type="text" name="username" class="form-control" id="username" value="<?php echo $row_select->username; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label"><span style="color: red;">*</span> البريد الالكتروني :</label>
                    <div class="col-sm-5">
                        <input type="text" name="email" class="form-control" id="email" value="<?php echo $row_select->email; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label"><span style="color: red;">*</span> كلمه المرور :</label>
                    <div class="col-sm-4">
                        <input type="password" name="password" class="form-control" id="password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="con_password" class="col-sm-2 control-label"><span style="color: red;">*</span> تأكيد كلمه المرور :</label>
                    <div class="col-sm-4">
                        <input type="password" name="con_password" class="form-control" id="con_password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="gender" class="col-sm-2 control-label">الجنس :</label>
                    <div class="col-sm-3">
                        <select name="gender" class="form-control" id="gender">
                            <option value="">اختر الجنس</option>
                            <option value="male" <?php if ($row_select->gender == 'male'){echo 'selected';} ?>>ذكر</option>
                            <option value="female" <?php if ($row_select->gender == 'female'){echo 'selected';} ?>>أنثي</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="avatar" class="col-sm-2 control-label">الصوره الرمزيه :</label>
                    <div class="col-sm-5">
                        <input type="file" name="image" class="form-control" id="avatar">
                    </div>
                </div>
                <div class="form-group">
                    <label for="about_you" class="col-sm-2 control-label">الوصف :</label>
                    <div class="col-sm-9">
                        <textarea name="about" class="form-control" id="about_you" rows="4"><?php echo strip_tags($row_select->about_user); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="facebook" class="col-sm-2 control-label"><i class="fa fa-facebook-square fa-2x" style="color: #3B5998;" aria-hidden="true"></i>
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="facebook" class="form-control" id="facebook" value="<?php echo $row_select->facebook; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="twitter" class="col-sm-2 control-label"><i class="fa fa-twitter-square fa-2x" style="color: #31B0D5;" aria-hidden="true"></i>
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="twitter" class="form-control" id="twitter" value="<?php echo $row_select->twitter; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="youtube" class="col-sm-2 control-label"><i class="fa fa-youtube-square fa-2x" style="color: #E62117;" aria-hidden="true"></i>
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="youtube" class="form-control" id="youtube" value="<?php echo $row_select->youtube; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <button type="submit" name="send" class="btn btn-danger btn-block"><b><i class="fa fa-pencil" aria-hidden="true"></i> تحديث البيانات </b></button>
                    </div>
                </div>
            </form>
            <div class="panel panel-default col-md-3">
                <div class="panel-body">
                    <img src="<?php echo $row_select->avatar; ?>" width="100%">
                </div>
            </div>
        </div>
    </div>
</article>
<!-- End article -->

<?php include_once 'include/footer.php'; ?>