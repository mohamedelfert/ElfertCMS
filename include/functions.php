<?php

/*اول فانكشن هنا عملتها عشان اعرض صفحه register بناء علي session */
function register(){
    if (@$_SESSION['id']){ /*هنا لو فيه session id مفتوح يعمل الجزء دا وحطيت @ هنا عشان امنع ظهور خطأ*/
        echo '<div class="alert alert-danger text-center" role="alert" style="font-size: large;"><b>عفوا يا <sapn style="color: #2e6da4;">' .$_SESSION['username'].'</sapn> لا يمكنك/ى الدخول الي هذه الصفحه حاليا فأنت/ى مسجل/ه بالفعل لدينا<b></div> ';
    }else{ /*طبيعي لو مفيش session مفتوح هينفذ الجزء دا ويظهر فورم التسجيل*/
        echo'
             <form action="include/register_process.php" method="post" class="form-horizontal" id="register" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label"><span style="color: red;">*</span> اسم المستخدم :</label>
                    <div class="col-sm-5">
                        <input type="text" name="username" class="form-control" id="username" placeholder="أدخل اسم المستخدم">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label"><span style="color: red;">*</span> البريد الالكتروني :</label>
                    <div class="col-sm-5">
                        <input type="text" name="email" class="form-control" id="email" placeholder="أدخل البريد الالكتروني">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label"><span style="color: red;">*</span> كلمه المرور :</label>
                    <div class="col-sm-3">
                        <input type="password" name="password" class="form-control" id="password" placeholder="أدخل كلمه المرور">
                    </div>
                </div>
                <div class="form-group">
                    <label for="con_password" class="col-sm-2 control-label"><span style="color: red;">*</span> تأكيد كلمه المرور :</label>
                    <div class="col-sm-3">
                        <input type="password" name="con_password" class="form-control" id="con_password" placeholder="أعد كتابه كلمه المرور">
                    </div>
                </div>
                <div class="form-group">
                    <label for="gender" class="col-sm-2 control-label">الجنس :</label>
                    <div class="col-sm-2">
                        <select name="gender" class="form-control" id="gender">
                            <option value="">اختر الجنس</option>
                            <option value="male">ذكر</option>
                            <option value="female">أنثي</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="avatar" class="col-sm-2 control-label">الصوره الرمزيه :</label>
                    <div class="col-sm-3">
                        <input type="file" name="image" class="form-control" id="avatar">
                    </div>
                </div>
                <div class="form-group">
                    <label for="about_you" class="col-sm-2 control-label">ضع وصف مختصر عنك :</label>
                    <div class="col-sm-5">
                        <textarea name="about" class="form-control" id="about_you" rows="4"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="facebook" class="col-sm-2 control-label"><i class="fa fa-facebook-square fa-2x" style="color: #3B5998;" aria-hidden="true"></i>
                    </label>
                    <div class="col-sm-5">
                        <input type="text" name="facebook" class="form-control" id="facebook" placeholder="أدخل رابط صفحتك علي الفيس بوك">
                    </div>
                </div>
                <div class="form-group">
                    <label for="twitter" class="col-sm-2 control-label"><i class="fa fa-twitter-square fa-2x" style="color: #31B0D5;" aria-hidden="true"></i>
                    </label>
                    <div class="col-sm-5">
                        <input type="text" name="twitter" class="form-control" id="twitter" placeholder="أدخل رابط صفحتك علي تويتر">
                    </div>
                </div>
                <div class="form-group">
                    <label for="youtube" class="col-sm-2 control-label"><i class="fa fa-youtube-square fa-2x" style="color: #E62117;" aria-hidden="true"></i>
                    </label>
                    <div class="col-sm-5">
                        <input type="text" name="youtube" class="form-control" id="youtube" placeholder="أدخل رابط قناتك علي اليوتيوب">
                    </div>
                </div>
                <!-- لعرض loading عندما يتم الضغط علي زرار الارسال وهو خاص ب ajax -->                
                <div class="col-md-12 text-center" style="width: 500px;margin-right: 250px;">
                    <div id="result" style="margin: 20px 0;"></div>
                </div>
                <!-- End -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <button type="submit" name="send" class="btn btn-danger btn-block"><b><i class="fa fa-pencil" aria-hidden="true"></i>
                                  تسجيل</b></button>                    
                    </div>
                </div>
             </form>
        ';
    }
}
/*End */



/* فانكشن دي خاصه بصفحه login*/
function login(){
    if (@$_SESSION['id']){ /*هنا لو فيه session id مفتوح يعمل الجزء دا وحطيت @ هنا عشان امنع ظهور خطأ*/
	    /*الجزء دا لو فيه session id مفتوح هيقوم يظهر بيانات العضو دا من قاعده البيانات */
        echo ' 
            <div class="panel panel-default">
                <div class="panel-heading text-center"><b>أهلا وسهلا بك يا '.$_SESSION['username'].'</b></div>
                <div class="panel-body">
                    <div class="text-center" style="margin-bottom: 20px">
                        <img src="'.$_SESSION['avatar'].'" width="100px" style="border-radius: 50%">
                    </div>

                    <hr>

                    <div class="col-md-12">
                        <p><b>البريد الاكتروني : </b>'.$_SESSION['email'].'</p>
                        <p><b>روابط التواصل لديك : </b>
                            <a href="'.$_SESSION['facebook'].'" target="_blank"> <i class="fa fa-facebook-square fa-2x" style="color: #3B5998;margin: 0 5px;" aria-hidden="true"></i> </a>
                            <a href="'.$_SESSION['twitter'].'" target="_blank"> <i class="fa fa-twitter-square fa-2x" style="color: #31B0D5;margin: 0 5px;" aria-hidden="true"></i> </a>
                            <a href="'.$_SESSION['youtube'].'" target="_blank"> <i class="fa fa-youtube-square fa-2x" style="color: #E62117;margin: 0 5px;" aria-hidden="true"></i> </a>
                        </p>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="col-md-6">
                    ';
                        if ($_SESSION['role'] === 'admin' OR $_SESSION['role'] === 'writer'){ /*هنا لو العضو دا ليه صلاحيه admin هيظهرله الجزء دا */
                            echo '<a href="admin_cp/index.php" class="btn btn-danger pull-left btn-sm">لوحه التحكم</a>';
                        }
                    echo '   
                    </div>
                    <div class="col-md-6">
                        <a href="profile.php?user='.$_SESSION['id'].'" class="btn btn-info pull-right btn-sm">الصفحه الشخصيه</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        ';
    }else{ /*اما بقا لو مفيش session id مفتوح هيظهر فورم الدخول دي */
        echo '
            <div class="panel panel-default">
                <div class="panel-heading text-center"><b>تسجيل الدخول</b></div>
                <div class="panel-body">
                    <div class="text-center" style="margin-bottom: 20px">
                        <img src="images/non-avatar.png" width="85px">
                    </div>

                    <hr>

                    <form action="include/login_process.php" method="post" class="form-horizontal" id="login">
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label"><i class="fa fa-user fa-2x" aria-hidden="true"></i>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" name="user" placeholder="أدخل اسم المستخدم أو البريد الالكتروني">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label"><i class="fa fa-lock fa-2x" aria-hidden="true"></i>
                            </label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password" placeholder="أدخل كلمه المرور">
                            </div>
                        </div>
                        <!-- لعرض loading عندما يتم الضغط علي زرار الارسال وهو خاص ب ajax -->                        
                        <div id="login_result" style="text-align: center;margin: 10px 0;"></div>
                        <!-- End -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="login" class="btn btn-info">تسجيل الدخول</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer"><i class="fa fa-exclamation-circle" aria-hidden="true" style="color: red;"></i> اذا لم تكن مسجل لدينا
                    <a href="register.php">اضغط هنا</a>
                </div>
            </div>
        ';
    }
}
/*End */



/*فانكشن دي خاصه بفورم التعليقات*/
function comment_area(){
    global $id;
    if (!isset($_SESSION['id'])){
        echo'<div class="alert alert-danger text-center" role="alert"><b>لا يمكنك التعليق علي الموضوع الا اذا سجلت دخول للموقع :( </b><small>اذا لم تكن مسجل لدينا <a href="register.php">اضغط هنا</a></small></div>';
    }else{
        echo'
            <form action="include/comment.php" method="post" class="form-horizontal" id="comments">
                <div class="form-group">
                    <div class="col-md-2"></div>
                    <label for="comment" class="col-sm-2 control-label">عنوان التعليق :</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="comment" id="comment" placeholder="أكتب عنوان التعليق">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2"></div>
                    <label for="content" class="col-sm-2 control-label">التعليق :</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" name="content" id="content" rows="5"></textarea>
                    </div>
                </div>
                <input type="hidden" name="id" value="'.$id.'">
                <div class="form-group">
                    <div class="col-md-4"></div>
                    <div class="col-sm-6">      
                        <div id="com_result" class="text-center"></div> <!-- دا خاص بعرض الجزء بتاع ajax -->
                        <button type="submit" name="submit" class="btn btn-info"><b>ارسال التعليق</b></button>
                    </div>
                </div>
            </form>
        ';
    }
}
/*End*/