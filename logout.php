<?php

session_start();
unset($_SESSION['sqleditorlogin']);
//session_unset();
//session_destroy();

header("location:apilogin.php");

?>