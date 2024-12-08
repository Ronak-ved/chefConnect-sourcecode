<?php
session_start();
session_destroy();
header('location:chef_login.php');