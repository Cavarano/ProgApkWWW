<?php
//niszczy sesję, wylogowując z panelu admina
session_start();
session_unset();
session_destroy();
header('Location: admin.php');
?>