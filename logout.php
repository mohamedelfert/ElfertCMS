<?php

session_start(); /*هنا استكملت session*/

if (!isset($_SESSION['id'])) { /*هنا بشوف ان مكانش فيه session id مفتوح ولا لا*/

    header("Location: index.php");

}elseif (isset($_SESSION['id']) != ""){ /*وهنا لو session id موجود */

    header("Location: index.php");

}

if (isset($_GET['logout'])) { /*هنا بشوف ان كان جاي عن طريق رابط فيه logout*/

    unset($_SESSION['id']); /*بالغي هنا session id اللي مفتوح*/
    session_unset(); /*هنا بامسح كل sessions اللي مفتوحه ومتسجله*/
    session_destroy(); /*بادمر هنا sessions*/
    header("Location: index.php"); /*باوجهه لصفحه index.php*/

}