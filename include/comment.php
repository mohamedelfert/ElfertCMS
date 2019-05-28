<?php
session_start();
require_once 'connect.php';

if (isset($_POST['submit'])){
    $title   = filter_var($_POST['comment'] , FILTER_SANITIZE_STRING);
    $content = filter_var($_POST['content'] , FILTER_SANITIZE_STRING);
    $post    = (int)$_POST['id'];
    $date    = date('Y-m-d : h-i-sa');
    if (empty($title)){
        echo'<div class="alert alert-danger text-center" role="alert"><b>يجب وضع عنوان للتعليق</b></div>';
    }elseif (empty($content)) {
        echo'<div class="alert alert-danger text-center" role="alert"><b>يجب كتابه محتوي التعليق</b></div>';
    }else{
        $insert_comment = $conn->query("INSERT INTO comments (
                                                              post_id,
                                                              user_id,
                                                              title,
                                                              comment,
                                                              status,
                                                              comm_date) 
                                                      VALUES (
                                                              '$post',
                                                              '$_SESSION[id]',
                                                              '$title',
                                                              '$content',
                                                              'dreft',
                                                              '$date')");
        if (isset($insert_comment)) { /*هنا بشيك لو جمله $sql بتاعتي اتنفذت يكمل اللي بعد كدا*/
            echo'<div class="alert alert-success" role="alert"><b> تم ارسال التعليق بنجاح , سوف يتم اظهاره بعد الموافقه عليه :) </b></div>';
        }
    }
}
?>