<?php
  session_start();
  header('Content-Type: text/html; charset=utf-8');

  echo("错误的用户名或密码，2秒后返回登录页面……");
  header("refresh:2; url=index.php");

?>
