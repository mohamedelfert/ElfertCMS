<?php

include_once 'include/header.php';
include_once 'include/sidbar.php'

?>


<article class="col-md-9 col-leg-9 art_bg">

    <!-- Start Carousel -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-top: 20px; margin-bottom: 30px;">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" style="height: 400px;">
            <?php
            $select_slide = $conn->query("SELECT * FROM posts WHERE post_status = 'Published' AND post_category = '$row_setting->slide' ORDER BY post_id DESC LIMIT $row_setting->slide_value");
            $count_slide  = $select_slide->rowCount();
            $x = 0;
            while ($row_slide = $select_slide->fetch(PDO::FETCH_OBJ)) {
                ?>
                <div class="item <?php echo ($x == 0 ? 'active' : ''); ?>" style="height: 400px;">
                    <img src="<?php echo $row_slide->post_image; ?>" width="100%" style="height: 400px;">
                    <div class="carousel-caption">
                        <span style="background-color: rgba(51, 51, 51, 0.58);display: inline-block;margin-bottom: 5px;color: #FFFFFF;padding: 10px 15px;margin-top: 5px">
                            <h3><a href="post.php?id=<?php echo $row_slide->post_id; ?>" style="color: rgb(0,0,0)"><?php echo strip_tags($row_slide->title); ?></a></h3>
                            <p><b><?php echo strip_tags(substr($row_slide->post, 0, 350)); ?> ...</b></p>
                        </span>
                    </div>
                </div>
                <?php
                $x++;
            }
            ?>
        </div>
        <!-- End Carousel Inner -->

        <ul class="nav nav-pills nav-justified sliddd">
            <?php
            for ($i = 0 ; $i < $count_slide ; $i++){
                echo '<li data-target="#myCarousel" data-slide-to="'.$i.'" '.($i == 0 ? 'class="active"' : '').'><a href="#"><i class="fa fa-star fa-lx"></i></a></li>';
            }
            ?>
        </ul>

    </div>
    <!-- End Carousel -->

    <hr />

    <!-- category A -->
    <div class="row">
        <h2 class="tit_cat1"><?php echo $row_setting->section_a; ?></h2>
        <?php
        $section_a = $conn->query("SELECT * FROM posts INNER JOIN users ON posts.author = users.user_id WHERE post_status = 'Published' AND post_category = '$row_setting->section_a' ORDER BY post_id DESC LIMIT $row_setting->section_a_value");
        while ($row_section_a = $section_a->fetch(PDO::FETCH_OBJ)){
        ?>
        <div class="col-sm-4 col-md-4" style="margin-bottom: 20px">
            <div class="post">
                <div class="post-img-content">
                    <img src="<?php echo $row_section_a->post_image; ?>" class="img-responsive" style="width: 100%;height: 200px;"/>
                    <span class="post-title"><b><?php echo $row_section_a->title; ?></b>
                </div>
                <div class="content">
                    <div class="author"><b>بواسطه</b><a href="profile.php?user=<?php echo $row_section_a->author; ?>"><b> <b><?php echo $row_section_a->username; ?></b></a> | <b>بتاريخ</b> <time datetime="2014-01-20"><?php echo $row_section_a->post_date; ?></time>
                    </div>
                    <div class="text-justify">
                        <?php echo strip_tags(substr($row_section_a->post,0,150)); ?>
                    </div>
                    <hr />
                    <div class="text-left">
                        <a href="post.php?id=<?php echo $row_section_a->post_id; ?>" class="btn btn-warning btn-sm">اقرأ المزيد &larr;</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <hr />
    <!-- end category A -->

    <!-- tab -->
    <div class="col-md-12">
        <div class="row">
            <div class="tabbable-panel">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab_default_1" data-toggle="tab"><?php echo $row_setting->tab_a; ?></a>
                        </li>
                        <li>
                            <a href="#tab_default_2" data-toggle="tab"><?php echo $row_setting->tab_b; ?></a>
                        </li>
                        <li>
                            <a href="#tab_default_3" data-toggle="tab"><?php echo $row_setting->tab_c; ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_default_1">
                            <?php
                            $select_tab_a = $conn->query("SELECT * FROM posts WHERE post_status = 'Published' AND post_category = '$row_setting->tab_a' ORDER BY post_id DESC LIMIT $row_setting->tab_a_value");
                            while ($row_tab_a = $select_tab_a->fetch(PDO::FETCH_OBJ)) {
                            ?>
                            <div class="bg_tab_topic">
                                <div class="col-md-3">
                                    <img src="<?php echo $row_tab_a->post_image; ?>" width="100%" class="img-thumbnail"/>
                                </div>
                                <div class="col-md-9">
                                    <h3 class="col-md-12 text-justify" style="margin-top: 8px;background: #197d9c;padding: 8px;">
                                        <a href="post.php?id=<?php echo $row_tab_a->post_id; ?>" class="a_1"><?php echo $row_tab_a->title; ?></a>
                                    </h3>
                                    <p class="col-md-12 text-justify">
                                        <?php echo strip_tags(substr($row_tab_a->post,0,300)); ?> ....
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="tab-pane" id="tab_default_2">
                            <?php
                            $select_tab_b = $conn->query("SELECT * FROM posts WHERE post_status = 'Published' AND post_category = '$row_setting->tab_b' ORDER BY post_id DESC LIMIT $row_setting->tab_b_value");
                            while ($row_tab_b = $select_tab_b->fetch(PDO::FETCH_OBJ)) {
                            ?>
                            <div class="bg_tab_topic">
                                <div class="col-md-3">
                                    <img src="<?php echo $row_tab_b->post_image; ?>" width="100%" class="img-thumbnail" />
                                </div>
                                <div class="col-md-9">
                                    <h3 class="col-md-12 text-justify" style="margin-top: 8px;background: #197d9c;padding: 8px;">
                                        <a href="post.php?id=<?php echo $row_tab_b->post_id; ?>" class="a_1"><?php echo $row_tab_b->title; ?></a>
                                    </h3>
                                    <p class="col-md-12 text-justify">
                                        <?php echo strip_tags(substr($row_tab_b->post,0,300)); ?> ....
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="tab-pane" id="tab_default_3">
                            <?php
                            $select_tab_c = $conn->query("SELECT * FROM posts WHERE post_status = 'Published' AND post_category = '$row_setting->tab_c' ORDER BY post_id DESC LIMIT $row_setting->tab_c_value");
                            while ($row_tab_c = $select_tab_c->fetch(PDO::FETCH_OBJ)) {
                            ?>
                            <div class="bg_tab_topic">
                                <div class="col-md-3">
                                    <img src="<?php echo $row_tab_c->post_image; ?>" width="100%" class="img-thumbnail" />
                                </div>
                                <div class="col-md-9">
                                    <h3 class="col-md-12 text-justify" style="margin-top: 8px;background: #197d9c;padding: 8px;">
                                        <a href="post.php?id=<?php echo $row_tab_c->post_id; ?>" class="a_1"><?php echo $row_tab_c->title; ?></a>
                                    </h3>
                                    <p class="col-md-12 text-justify">
                                        <?php echo strip_tags(substr($row_tab_c->post,0,300)); ?> ....
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end Tabs -->

    <!-- start category B -->
    <div class="col-lg-12">
        <h2 class="tit_cat2"><?php echo $row_setting->section_b; ?></h2>
        <div class="row  bg_cat2">
            <?php
            $section_b = $conn->query("SELECT * FROM posts WHERE post_status = 'Published' AND post_category = '$row_setting->section_b' ORDER BY post_id DESC LIMIT $row_setting->section_b_value");
            while ($row_section_b = $section_b->fetch(PDO::FETCH_OBJ)) {
            ?>
            <div class="bg_tab_topic col-md-6">
                <div class="col-md-4">
                    <img src="<?php echo $row_section_b->post_image; ?>" width="100%" class="circle"/>
                </div>
                <div class="col-md-8">
                    <h3 class="col-md-12 text-justify" style="margin-right: -30px;margin-top: 8px;">
                        <a href="post.php?id=<?php echo $row_section_b->post_id; ?>"><?php echo $row_section_b->title; ?></a>
                    </h3>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php
            }
            ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end category B -->

</article>


<?php include_once 'include/footer.php' ?>