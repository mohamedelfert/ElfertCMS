<aside class="col-md-3 col-leg-3 sid_bg">
    <div class="col-md-12">
        <div class="row">

            <?php login(); ?> <!--فانكشن انا عاملها مستدعيها هنا عشان صفحه login-->

            <div class="panel panel-info">
                <div class="panel panel-heading">
                    <p><b>آخر المشاركات</b></p>
                </div>
                <div class="panel-body">
                    <!-- tab -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">
                                    <?php
                                    $select_tab_a = $conn->query("SELECT * FROM posts WHERE post_status = 'Published' ORDER BY post_id DESC LIMIT 5");
                                    while ($row_tab_a = $select_tab_a->fetch(PDO::FETCH_OBJ)) {
                                        ?>
                                        <div class="bg_tab_topic">
                                            <div class="col-md-3">
                                                <img src="<?php echo $row_tab_a->post_image; ?>" width="100%" class="img-thumbnail"/>
                                            </div>
                                            <div class="col-md-9">
                                                <p class="col-md-12 text-justify" style="margin-top: 3px;background: #197d9c;padding: 5px;">
                                                    <a href="post.php?id=<?php echo $row_tab_a->post_id; ?>" class="a_1"><?php echo substr($row_tab_a->title , 0 , 150); ?>...</a>
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
                    <!-- end Tabs -->
                </div>
            </div>

        </div>
    </div>
</aside>