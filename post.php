<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

$id          = intval($_GET['id']);
$select_post = $conn->query("SELECT * FROM posts LEFT JOIN users ON posts.author = users.user_id WHERE post_status = 'Published' AND post_id = '$id' ORDER BY post_id DESC");
$row_post    = $select_post->fetch(PDO::FETCH_OBJ);
?>

<article class="col-md-9 col-leg-9">

    <ol class="breadcrumb">
        <li><a href="index.php">الرئيسيه</a></li>
        <li><a href="category.php?cat=<?php echo $row_post->post_category; ?>"><?php echo $row_post->post_category; ?></a></li>
        <li class="active"><?php echo $row_post->title; ?></li>
    </ol>

    <div class="col-lg-12 art_bg">

        <div class="cat_post">
            <h2 class="cat_h2"><?php echo $row_post->title; ?></h2>
            <div class="col-md-12">
                <img src="<?php echo $row_post->post_image; ?>" width="100%">
            </div>
            <div class="col-md-12">
                <div class="col-md-12" style="margin:  0 0 10px 0;;padding: 7px 10px 7px 10px;background: #cccccc">
                    <p class="pull-right" style="margin-bottom: 0;"><i class="fa fa-user" aria-hidden="true"></i> الكاتب  : <a href="profile.php?user=<?php echo $row_post->author; ?>"><?php echo $row_post->username; ?></a></p>
                    <p class="pull-left" style="margin-bottom: 0;"><?php echo $row_post->post_date; ?> <i class="fa fa-clock-o" aria-hidden="true"></i></p>
                </div>
                <p>
                    <?php echo strip_tags($row_post->post); ?>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>

    <!-- comment area show -->
    <div class="col-md-12">
        <div class="row">
            <?php
            /*هنا باعمل استعلام بسيط عشان اجيب بيانات من الداتا بيس وعملت ربط ما بين جدول comments و users وربطت بينهم بعلاقه وعملت شرط ان حقل user_id في جدول comments يساوي حقل user_id في جدول usres */
            $stmnt = $conn->query("SELECT * FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE status = 'Published' AND post_id = '$id' ORDER BY comm_id DESC LIMIT 10");
            while ($row_stmnt = $stmnt->fetch(PDO::FETCH_OBJ)){
            ?>
            <div class="cat_post">
                <div class="col-md-2">
                    <img src="<?php echo $row_stmnt->avatar; ?>" width="100%">
                </div>
                <div class="col-md-10">
                    <h2 class="cat_h2"><i class="fa fa-comment" aria-hidden="true"></i> <?php echo $row_stmnt->title; ?> </h2>
                    <p>
                        <?php echo $row_stmnt->comment; ?>
                    </p>
                </div>
                <div class="col-md-12">
                    <hr style="margin-bottom: 10px;margin-top: 0;">
                    <p class="pull-right" style="margin-bottom: 0;"><i class="fa fa-user" aria-hidden="true"></i> تم التعليق بواسطه  : <a href="profile.php?user=<?php echo $row_stmnt->user_id; ?>"><?php echo $row_stmnt->username; ?></a></p>
                    <p class="pull-left" style="margin-bottom: 0;"><?php echo $row_stmnt->comm_date; ?> <i class="fa fa-clock-o" aria-hidden="true"></i></p>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <!-- comment area show -->

    <!-- form comment -->
    <div class="col-lg-12 art_bg" style="margin-top: 25px;padding-top: 15px">
        <h2><i class="fa fa-comments" aria-hidden="true"></i> اضافه تعليق علي الموضوع </h2>
        <hr>
        <?php comment_area(); ?>
    </div>
    <!-- form comment -->

</article>

<?php include_once 'include/footer.php'?>
