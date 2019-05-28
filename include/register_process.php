<?php
include_once 'connect.php';
session_start(); /*بدات session هنا*/

if (isset($_POST['send'])){
    /*هنا باجيب كل المدخلات اللي في الصفحه عندي واحطها في فاريبل عشان استخدمها*/
    $username     = filter_var($_POST['username'],FILTER_SANITIZE_STRING); /*هنا استخدمت فلتر للاسم عشان امنع كتابه اي تاجات html*/
    $email        = $_POST['email'];
    $gender       = $_POST['gender'];
    $about        = filter_var($_POST['about'],FILTER_SANITIZE_STRING); /*هنا استخدمت فلتر للاسم عشان امنع كتابه اي تاجات html*/
    $facebook     = htmlspecialchars($_POST['facebook']); /*هنا استخدمت فلتر عشان اي رموز خاصه زي %&#$! مثلا */
    $twitter      = htmlspecialchars($_POST['twitter']); /*هنا استخدمت فلتر عشان اي رموز خاصه زي %&#$! مثلا */
    $youtube      = htmlspecialchars($_POST['youtube']); /*هنا استخدمت فلتر عشان اي رموز خاصه زي %&#$! مثلا */
    $date         = date("Y-m-d");

    /*هنا باتحقق من المدخلات وكده اذا كانت فاضيه ولا لا*/
    if (empty($username)){
        echo '<div class="alert alert-danger" role="alert"><b>الرجاء ادخال اسم المستخدم </b></div>';
    }elseif (empty($email)) {
        echo '<div class="alert alert-danger" role="alert"><b>الرجاء ادخال البريد الالكتروني </b></div>';
    }elseif (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){  /*هنا استخدمت فلتر للايميل عشان أتأكد ان كان المدخل ايميل صحيح ولا لا*/
        echo '<div class="alert alert-danger" role="alert"><b>الرجاء ادخال بريد الكتروني صحيح </b></div>';
    }elseif (empty($_POST['password'])){
        echo '<div class="alert alert-danger" role="alert"><b>الرجاء ادخال كلمه المرور </b></div>';
    }elseif (empty($_POST['con_password'])){
        echo '<div class="alert alert-danger" role="alert"><b>الرجاء اعاده كتابه كلمه المرور </b></div>';
    }elseif ($_POST['password'] !== $_POST['con_password']){
        echo '<div class="alert alert-danger" role="alert"><b>الرجاء التأكد من كلمه المرور </b></div>';
    }else{
        $sql_username = $conn->query("SELECT username FROM users WHERE username = '$username'");
        $count_user = $sql_username->rowCount();
        $sql_email    = $conn->query("SELECT email FROM users WHERE email = '$email'");
        $count_email = $sql_email->rowCount();
        if ($count_user > 0){
            echo '<div class="alert alert-danger" role="alert"><b>عفوا اسم المستخدم مسجل مسبقا </b></div>';
        }elseif ($count_email > 0){
            echo '<div class="alert alert-danger" role="alert"><b>عفوا هذا البريد مستخدم مسبقا </b></div>';
        }else{
            if (isset($_FILES['image'])){ /*هنا استخدمت $_FILES عشان عندي هنا المدخل ملف صوره*/
                $image = $_FILES['image']; /*هنا استخدمت $_FILES عشان اجيب كل بيانات الصوره*/
                $image_name = $image['name']; /*هنا اسم الصوره*/
                $image_temp = $image['tmp_name']; /*هنا مسار رفع الصوره */
                $image_size = $image['size']; /*هنا حجم الصوره */
                $image_error = $image['error']; /* لو فيه اخطاء لو مفيش يبقي ب 0*/

                /*هنا جبت extention بتاع الصوره عن طريق Pathinfo وبعد كده حولت كل الحمروف لحروف small عن طريق strtolower*/
                /*              or this
                  $image_Exe = explode('.' , $image_name);
                  $image_Exe = strtolower(end($image_Exe));
                */
                $image_Exe = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));
                $valid_exe = array('png','jpeg','jpg','gif'); /*هنا عملت array عشان احط فيها الصيغ اللي انا عايزها بس محدش يرفع غيرها*/
                if (in_array($image_Exe,$valid_exe)){
                    if ($image_error === 0){
                        if ($image_size <= 3000000){
                            /*هنا باعمل اسم جديد للصوره عن طريق استخدام الداله unique لعمل اسم عشوائي يبدأ ب user*/
                            $newName = uniqid('user',false).".".$image_Exe;
                            /*هنا الملف اللي هارفع عليه الصور وهنا رجعت عشان ملف register_process.php جوا فايل include*/
                            $image_dir = '../images/avatar/'.$newName;
                            $image_db  = 'images/avatar/'.$newName; /*هنا فاريبل جديد عشان استخدمه لادخال الصوره لقاعده البيانات*/
                            if (move_uploaded_file($image_temp,$image_dir)){ /*هنا بشيك ان كان رفع الصوره يكمل اللي جوا if بقا*/
                                $password = md5($_POST['password']); /*هنا عملت تشفير للباسوورد من نوع md5*/
                                $insert = "INSERT INTO users (username,
                                                              email,
                                                              password,
                                                              gender,
                                                              avatar,
                                                              about_user,
                                                              facebook,
                                                              twitter,
                                                              youtube,
                                                              register_date,
                                                              role) 
                                                    VALUES ('$username',
                                                            '$email',
                                                            '$password',
                                                            '$gender',
                                                            '$image_db',
                                                            '$about',
                                                            '$facebook',
                                                            '$twitter',
                                                            '$youtube',
                                                            '$date',
                                                            'user')";
                                $stmnt = $conn->prepare($insert);
                                $stmnt->execute();
                                if (isset($stmnt)){ /*هنا بشيك لو جمله $stmnt بتاعتي اتنفذت يكمل اللي بعد كدا*/
                                    $user_info = $conn->prepare("SELECT * FROM users WHERE username = :username");
                                    $user_info->bindParam(':username',$username,PDO::PARAM_STR);
                                    $user_info->execute();
                                    $row = $user_info->fetch(PDO::FETCH_OBJ);
                                    $_SESSION['id']            = $row->user_id;
                                    $_SESSION['username']      = $row->username;
                                    $_SESSION['email']         = $row->email;
                                    $_SESSION['gender']        = $row->gender;
                                    $_SESSION['avatar']        = $row->avatar;
                                    $_SESSION['about_user']    = $row->about_user;
                                    $_SESSION['facebook']      = $row->facebook;
                                    $_SESSION['twitter']       = $row->twitter;
                                    $_SESSION['youtube']       = $row->youtube;
                                    $_SESSION['register_date'] = $row->register_date;
                                    $_SESSION['role']          = $row->role;
                                    echo'<div class="alert alert-success" role="alert"><b> تم التسجيل بنجاح جاري تحويلك للصفحه الرئيسيه :) </b></div>';
                                    echo '<meta http-equiv="refresh" content="3; \'index.php\'">';
                                }
                            }else{
                                echo '<div class="alert alert-danger" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                            }
                        }else{
                            echo '<div class="alert alert-danger" role="alert"><b>عذرا يجب أن يكون حجم الصوره أقل من 3 ميجابايت :( </b></div>';
                        }
                    }else{
                        echo '<div class="alert alert-danger" role="alert"><b>نأسف حصل خطأ أثناء رفع الصوره :( </b></div>';
                    }
                }else{
                    echo '<div class="alert alert-danger" role="alert"><b>الرجاء رفع صوره تكون بصيفه من (png , gif , jpeg , jpg) </b></div>';
                }
            }else{
                $password = md5($_POST['password']);
                $insert = "INSERT INTO users (username,
                                              email,
                                              password,
                                              gender,
                                              avatar,
                                              about_user,
                                              facebook,
                                              twitter,
                                              youtube,
                                              register_date,
                                              role) 
                                      VALUES ('$username',
                                              '$email',
                                              '$password',
                                              '$gender',
                                              'images/non-avatar.png',
                                              '$about',
                                              '$facebook',
                                              '$twitter',
                                              '$youtube',
                                              '$date',
                                              'user')";
                $stmnt = $conn->prepare($insert);
                $stmnt->execute();
                if (isset($stmnt)){
                    $user_info = $conn->prepare("SELECT * FROM users WHERE username = :username");
                    $user_info->bindParam(':username',$username,PDO::PARAM_STR);
                    $user_info->execute();
                    $row = $user_info->fetch(PDO::FETCH_OBJ);
                    $_SESSION['id']            = $row->user_id;
                    $_SESSION['username']      = $row->username;
                    $_SESSION['email']         = $row->email;
                    $_SESSION['gender']        = $row->gender;
                    $_SESSION['avatar']        = $row->avatar;
                    $_SESSION['about_user']    = $row->about_user;
                    $_SESSION['facebook']      = $row->facebook;
                    $_SESSION['twitter']       = $row->twitter;
                    $_SESSION['youtube']       = $row->youtube;
                    $_SESSION['register_date'] = $row->register_date;
                    $_SESSION['role']          = $row->role;
                    echo'<div class="alert alert-success" role="alert"><b> تم التسجيل بنجاح جاري تحويلك للصفحه الرئيسيه :) </b></div>';
                    echo '<meta http-equiv="refresh" content="3; \'index.php\'">';
                }
            }
        }
    }

}

?>






