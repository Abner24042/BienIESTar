<?php
$email = $_POST['email'];
echo "<script>alert('Correo enviado a $email'); window.location.href='../view/auth/login.php';</script>";
?>