<!-- Start aside -->
<aside class="col-lg-3">
    <div class="list-group">
        <?php
        if ($_SESSION['role'] === 'admin'){
        ?>
            <a class="list-group-item disabled"><b>الوصول السريع</b></a>
            <a href="index.php" class="list-group-item"><i class="fa fa-tachometer"></i>  لوحه التحكم  </a>
            <a href="setting.php" class="list-group-item"><i class="fa fa-cog"></i>  اعدادات الموقع  </a>
            <a href="category.php" class="list-group-item"><i class="fa fa-list"></i>  التصنيفات  </a>
            <a href="new_post.php" class="list-group-item"><i class="fa fa-pencil"></i>  اضافه مقال جديد  </a>
            <a href="posts.php" class="list-group-item"><i class="fa fa-file-o"></i>  المقالات  </a>
            <a href="users.php" class="list-group-item"><i class="fa fa-user"></i>  الأعضاء  </a>
            <a href="comments.php" class="list-group-item"><i class="fa fa-comments-o"></i>  التعليقات  </a>
            <a href="profile.php" class="list-group-item"><i class="fa fa-star"></i>  الصفحه الشخصيه  </a>
        <?php
        }else{
        ?>
            <a class="list-group-item disabled"><b>الوصول السريع</b></a>
            <a href="index.php" class="list-group-item"><i class="fa fa-tachometer"></i>  لوحه التحكم  </a>
            <a href="category.php" class="list-group-item"><i class="fa fa-list"></i>  التصنيفات  </a>
            <a href="new_post.php" class="list-group-item"><i class="fa fa-pencil"></i>  اضافه مقال جديد  </a>
            <a href="posts.php" class="list-group-item"><i class="fa fa-file-o"></i>  المقالات  </a>
            <a href="comments.php" class="list-group-item"><i class="fa fa-comments-o"></i>  التعليقات  </a>
            <a href="profile.php" class="list-group-item"><i class="fa fa-star"></i>  الصفحه الشخصيه  </a>
        <?php
        }
        ?>
    </div>
</aside>
<!-- End aside -->