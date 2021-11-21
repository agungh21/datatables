<?php
$con = mysqli_connect('localhost', 'root', '', 'db_datatables');
if (mysqli_connect_errno()) {
    echo "Database connection error";
    exit;
}
