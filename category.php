<?php
include_once 'include/header.php';
include_once 'include/sidbar.php';

/*الجزء دا خاص لعمل تعدد للصفحات للأعضاء*/
$per_post = 10; /*عدد المقالات اللي عايزها تظهر في الصفحه*/
/*هنا باتاكد ان مفيش متغير اسمه page جاي في الرابط ولا لا*/
if (!isset($_GET['page'])){
    $page = 1; //هنا لو مفيش باعمل انا متغير واديله قيمه بدايه
}else{
    $page = (int)$_GET['page']; //اما هنا لو فيه متغير جاي في الرابط هقوم اجيب قيمته في المتغير ده
}
$start_from = ($page - 1) * $per_post;
/***************النهايه******************/

$id              = $_GET['cat'];
$select_sections = $conn->query("SELECT * FROM posts INNER JOIN users ON posts.author = users.user_id WHERE post_status = 'Published' AND post_category = '$id' ORDER BY post_id DESC LIMIT $start_from , $per_post");

?>

<article class="col-md-9 col-leg-9">
    <ol class="breadcrumb">
        <li><a href="index.php">الرئيسيه</a></li>
        <li class="active"><?php echo $id; ?></li>
    </ol>
    <div class="col-lg-12 art_bg">
        <?php
        while ($row_sections = $select_sections->fetch(PDO::FETCH_OBJ)){
        ?>
        <div class="cat_post">
            <div class="col-md-3">
                <img src="<?php echo $row_sections->post_image; ?>" width="100%">
            </div>
            <div class="col-md-9">
                <h2 class="cat_h2"><?php echo $row_sections->title; ?></h2>
                <p>
                    <?php echo strip_tags(substr($row_sections->post,0,400)); ?> ...
                </p>
            </div>
            <div class="col-md-12">
                <hr style="margin-bottom: 10px;margin-top: 0;">
                <a href="post.php?id=<?php echo $row_sections->post_id; ?>" class="btn btn-warning btn-sm pull-left">اقرأ المزيد &larr;</a>
                <p class="pull-right">
                    <i class="fa fa-user" aria-hidden="true"></i> : <a href="profile.php?user=<?php echo $row_sections->author; ?>"><?php echo $row_sections->username; ?></a> |
                    <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $row_sections->post_date; ?>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
        }
        ?>
        <!-- باقي الجزء اللي فوق الخاص لعمل تعدد للصفحات للمقالات -->
        <?php
        $posts       = $conn->query("SELECT * FROM posts WHERE post_category = '$id'");
        $count_post  = $posts->rowCount();
        $total_pages = ceil($count_post / $per_post);
        ?>
        <nav class="text-center">
            <ul class="pagination">
                <?php
                for ($i = 1 ; $i <= $total_pages ; $i++){
                    echo'<li '.($page == $i ? 'class="active"' : '').'><a href="category.php?cat='.$id.'&page='.$i.'">'.$i.'</a></li>';
                }
                ?>
            </ul>
        </nav>
        <!-- النهايه -->
    </div>
</article>

<?php include_once 'include/footer.php'?>
