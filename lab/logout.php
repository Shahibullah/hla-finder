<?php
session_start();
session_destroy();

header("Location: lab_login.php");
exit;
