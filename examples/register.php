<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
}

button:hover {
    opacity: 0.8;
}

.cancelbtn {
    width: auto;
    padding: 10px 18px;
    background-color: #f44336;
}

.imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
}

img.avatar {
    width: 200px;
    border-radius: 50%;
}

.container {
    padding: 16px;
}

span.psw {
    float: right;
    padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
    span.psw {
       display: block;
       float: none;
    }
    .cancelbtn {
       width: 100%;
    }
}

  #ui-header {
    font-size: xx-large;
    color: darkslategrey;
    text-align: center;
  }
</style>
</head>

<body>
  <nav id="ui-header">用户注册界面</nav>
  <form action="login.php">
    <div class="imgcontainer">
      <img src="../img/register.jpeg" alt="Avatar" class="avatar">
    </div>
    <div class="container" style="text-align: center; background-color: #efeff5">
    请输入下列相关信息并提交申请，经过人工审核批准后会通过您的邮箱通知您用户名和密码生效。
    </div>

    <div class="container">
      <label for="name"><b>真实姓名</b></label>
      <input type="text" placeholder="请输入真实姓名" name="name" required/>

      <label for="school"><b>学校</b></label>
      <input type="text" placeholder="请输入就读学校" name="school" required/>

      <label for="schoolid"><b>学号</b></label>
      <input type="text" placeholder="请输入学号" name="schoolid" required/>

      <label for="email"><b>个人邮箱</b></label>
      <input type="text" placeholder="请输入个人邮箱" name="email" required/>

      <label for="uname"><b>用户名</b></label>
      <input type="text" placeholder="请输入用户名" name="uname" required/>

      <label for="psw"><b>密码</b></label>
      <input id="password" type="password" placeholder="请输入密码" name="psw" required onblur="checkpas1();"/>

      <label for="psw-confirm"><b>请确认密码</b></label>
      <input id="repassword" type="password" placeholder="请再输入一遍密码" name="psw-confirm" required onChange="checkpas();"/>

      <span class="tip" style="color: red;">两次输入的密码不一致</span><br>
        
      <button type="submit">注册</button>
      <a href="login.php">返回登录界面</a>
      
    </div>

    <div class="container" style="background-color:#f1f1f1">
      
    </div>
  </form>

<!--jQuery library-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>
      var passwordSame = false;
      function checkpas1(){//当第一个密码框失去焦点时，触发checkpas1事件
        var pas1=document.getElementById("password").value;
        var pas2=document.getElementById("repassword").value;//获取两个密码框的值
        if(pas1!=pas2&&pas2!="") {//此事件当两个密码不相等且第二个密码不是空的时候会显示错误信息
          $(".tip").show();
          passwordSame = false;
        } else {
          $(".tip").hide();//若两次输入的密码相等且都不为空时，不显示错误信息
          passwordSame = true;
        }
        $('button').trigger('checkPassword');
      }
      function checkpas(){//当第二个密码框失去焦点时，触发checkpas件
        var pas1=document.getElementById("password").value;
        var pas2=document.getElementById("repassword").value;//获取两个密码框的值
        if(pas1!=pas2){
          $(".tip").show();//当两个密码不相等时则显示错误信息
          passwordSame = false;
        }else{
          $(".tip").hide();
          passwordSame = true;
        }
        $('button').trigger('checkPassword');
      }

    var event = new Event('checkPassword');

    $('button').on('checkPassword', function() {
      if (passwordSame) { $('button').prop('disabled', false); }
      else { $('button').prop('disabled', true); }
    });

    $(document).ready(function() {
      $(".tip").hide();
    })
  </script>

 
</body>


</html>
