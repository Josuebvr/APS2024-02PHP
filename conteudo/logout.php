<?php
if (!isset($_SESSION)) {
    session_start();
}
session_destroy();
echo "<script> location.href='index.php?p=login'; </script>";
?>