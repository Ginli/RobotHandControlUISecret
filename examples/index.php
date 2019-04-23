<?php 
  session_start();

  $session_life_in_seconds = 60*60;

  if (!$_SESSION['last_acted_on']) {
    session_unset();
    session_destroy();
    header("Location:login.php");
  } elseif (time() - $_SESSION['last_acted_on'] > $session_life_in_seconds) {
    header('Content-Type: text/html; charset=utf-8');
    echo("用户登录时间到期，返回登录页面……");
    session_unset();
    session_destroy();
    header("refresh:2; url=login.php");
  } else {
    session_regenerate_id(true);
    $_SESSION['last_acted_on'] = time();
  }
  
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
  .bg-dark {
  }

  .detail-title {
    color: brown;
    text-align: center;
  }

  .window {
    background-color: white;
    padding-bottom: 10px;
  }

  #ui-header {
    font-size: xx-large;
    color: #2cc185;
    text-align: center;
  }

  #logout-btn {
    color: grey;
    text-decoration: none;
    outline-style: none; 
    -moz-outline-style:none;
  }

  #logout-btn:hover {
    color: black;
  }

  #ros-connect-info {
    background-color: thistle;
    text-align: center;
    font-size: 20px;
  }

  .hand-info {
  	font-size: large;
  }

  .footer {
    text-align: center;
  }

  .padd {
    padding: 0 20px;
  }

  .current {
    border-radius: 10px 10px 0 0;
    background-color: white;
  }

  .menu-icon {
    width: 25px;
  }

  .nav-item > a {
    outline-style: none; 
    -moz-outline-style:none;
  }

  .dot {
    height: 25px;
    width: 25px;
    background-color: white;
    border-radius: 50%;
    display: inline-block;
  }

  .title-1 {
    background-color: #eedeb7;
  }

  #controller-section button {
    font-size: 20px;
  }

  #hand-posture table {
    width: 100%;
  }

  #hand-posture th,td {
    padding: 15px;
  }

  @media (max-width: 992px) {
    .col-md-4 {
      -ms-flex: unset;
      flex: unset;
      max-width: unset;
    }
  }
</style>
</head>

<body style="background-color: #e3f2fd">
	<nav id="ui-header">SHU Hand遥操作界面</nav>
  <p id="ros-connect-info"></p>
  <nav class="navbar navbar-expand-lg navbar-light" style="padding: 0 1rem">
    <div class="container">
      <div class="navbar-collapse" id="navbarText" style="font-size: x-large;">
        <ul id="grand-menu" class="navbar-nav mr-auto">
          <li class="nav-item active padd current">
            <a class="nav-link" href="#" id="home-option"><img src="../svg/home.svg" alt="home icon" class="menu-icon">首页 <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item padd">
            <a class="nav-link" href="#" id="controller-option"><img src="../svg/command.svg" alt="command icon" class="menu-icon">控制中心</a>
          </li>
          <li class="nav-item padd">
            <a class="nav-link" href="#" id="document-option"><img src="../svg/document.svg" alt="document icon" class="menu-icon">资料中心</a>
          </li>
          <li class="nav-item padd">
            <a class="nav-link" href="#" id="user-option"><img src="../svg/person.svg" alt="user-center icon" class="menu-icon">个人中心</a>
          </li>
        </ul>
        <span class="navbar-text">
          <a class="button" type="nav-link" href="logout.php" id="logout-btn" class="btn btn-outline-danger"><img src="../svg/account-logout.svg" alt="logout icon" class="menu-icon">退出登录</a>
        </span>
      </div>
    </div>
  </nav>
  <div style="background-color: #EEEDED">
    <div class="container toggle-section" id="home-section">
      <div class="row window" style="min-height: 600px">
        <div class="col-md-12">
          <h2 class="detail-title">机械手简介</h2>
          <img src="../img/hand.jpg" style="float: left; width: 300px;"/>
          <div class="hand-info" style="margin: 30px 40px 30px 40px">
            该手具有5个手指，采用合理的驱动和传动方式，配合相关的传感器实现闭环控制系统，力控制精度达1N；手指从张开到完全握紧的反应时间小于1.5s，可抓取2kg的物品，外形拟人化，动作灵巧、轻便，能够模仿人手的基本动作；便于与不同机械臂的安装，可作为工业机器人、服务机器人等的末端执行机构；具有结构简单、制造成本低的特点。
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid toggle-section window" id="controller-section">
      <br>
      <h1 class="detail-title title-1">控制区</h1>
      <div id="row-1" class="row">
        <div class="col-md-4 window">
          <h2 class="detail-title">关节滑块控制</h2>
          <div id="sliders"></div>
        </div>
        <div id="hand-posture" class="col-md-4 window">
          <h2 class="detail-title">手势按钮控制</h2>
          <table>
            <tr>
              <td><button type="button" class="btn btn-outline-success" onclick="handPosture_1()">手势“一”</button></td>
              <td><button type="button" class="btn btn-outline-success" onclick="handPosture_2()">手势“二”</button></td>
              <td><button type="button" class="btn btn-outline-success" onclick="handPosture_3()">手势“三”</button></td>
              <td><button type="button" class="btn btn-outline-success" onclick="handPosture_4()">手势“四”</button></td>
            </tr>
            <tr>
              <td><button type="button" class="btn btn-outline-success" onclick="handPosture_5()">手势“五”</button></td>
              <td><button type="button" class="btn btn-outline-success" onclick="handPosture_6()">手势“六”</button></td>
              <td><button type="button" class="btn btn-outline-success" onclick="handPosture_ok()">手势“OK”</button></td>
              <td><button type="button" class="btn btn-outline-success">手势“棒”</td>
            </tr>
          </table>
        </div>
        <div class="col-md-4 window" style="text-align: center">
          <h2 class="detail-title">LeapMotion控制</h2>
          <div>
            <table class="table table-hover" style="font-size: 20px">
              <tr>
                <th></th>
                <th>大拇指1</th>
                <th>大拇指2</th>
                <th>食指</th>
                <th>中指</th>
                <th>无名指</th>
                <th>小指</th>
              </tr>
              <tr id="joint-val-from-leap">
                <td>Leap<br>Motion<br>数据</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </table>
          </div>
          <div id="leap-status">Stopped</div>
          <div>
            <button type="button" class="btn btn-outline-primary" onclick="startLeapControl()">开始控制</button>
            <button type="button" class="btn btn-outline-danger" onclick="stopLeapControl()">停止控制</button>
          </div>
        </div>
      </div>
      <h1 class="detail-title title-1">监控区</h1>
      <div class="row">
        <div class="col-md-4">
          <h2 class="detail-title">实时视频监控</h2>
          <canvas id="video-canvas"></canvas>
        </div>
        <div class="col-md-4">
          <h2 class="detail-title">3D仿真模型</h2>
          <div id="urdf"></div>
        </div>
        <div class="col-md-4">
          <h2 class="detail-title">关节数据显示</h2>
          <div id="serial-receive">
            <table class="table table-hover" style="font-size: 20px">
              <tr>
                <th></th>
                <th>大拇指1</th>
                <th>大拇指2</th>
                <th>食指</th>
                <th>中指</th>
                <th>无名指</th>
                <th>小指</th>
              </tr>
              <tr id="joint-val-from-cmd">
                <td>命令</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr id="joint-val-from-serial">
                <td>传感器</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
              </tr>
              <tr id="error-rate">
                <td>误差</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <h1 class="detail-title title-1">故障警报区</h1>
      <div class="row">
        <div class="col-md-12">
          <table class="table" style="font-size: large;">
            <tr class="table-info">
              <td>通信状态</td><td><span class="dot" id="com-good"></span><span>正常</span></td><td><span class="dot" id="com-bad"></span><span>异常</span></td>
            </tr>
            <tr class="table-info">
              <td>振动状态</td><td><span class="dot" id="vib-good"></span><span>正常</span></td><td><span class="dot" id="vib-bad"></span><span>异常</span></td>
            </tr>
            <tr class="table-info">
              <td>误差状态</td><td><span class="dot" id="err-good"></span><span>正常</span></td><td><span class="dot" id="err-bad"></span><span>异常</span></td>
            </tr>
            <tr class="table-info">
              <td><span>报警时间</span><input type="text" value=""></td><td></td><td></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <form>
            <div class="form-group">
              <label for="exampleInputPassword1">密码</label><input type="password" class="form-control" id="exampleInputPassword1" placeholder="请输入二次验证密码">
	            <small class="form-text text-muted">请妥善保管二次验证密码。我们不会向任何人透漏您的密码。</small>
            </div>
            <button type="submit" class="btn btn-success">登录</button>
            <button type="submit" class="btn btn-success">取消</button>
          </form>
        </div>
      </div>
    </div>
    <div class="container toggle-section window" id="doc-section">
      <h1 class="detail-title title-1">用户资料中心</h1>
      <div class="row">
        <div class="col-md-12">
          <button type="button" class="btn btn-primary">上传文件</button><span>待上传文件：</span>
          <button type="button" class="btn btn-info" style="float: right">下载机械手最近1小时关节数据</button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <h2 class="detail-title">用户文件列表</h2>
          <button type="button" class="btn btn-outline-info" stype="float: left;" disabled>上一页</button>
          <button type="button" class="btn btn-outline-info" stype="float: right;" disabled>下一页</button>
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action">SHU_Hand参数.pdf</a>
            <a href="#" class="list-group-item list-group-item-action">Leapmotion使用手册.pdf</a>
            <a href="#" class="list-group-item list-group-item-action">基于Web的移动机器人控制系统研究与实现.pdf</a>
          </div>
          <button type="button" class="btn btn-outline-info" stype="float: left;" disabled>上一页</button>
          <button type="button" class="btn btn-outline-info" stype="float: right;" disabled>下一页</button>
        </div>
      </div>
    </div>
    <div class="container toggle-section window" id="user-center-section">
      <h1 class="detail-title title-1">个人中心</h1>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-hover">
              <tr>
                <td>真实姓名</td>
                <td>肖梓轩</td>
                <td><a href="#">修改</a></td>
              </tr>
              <tr>
                <td>用户名</td>
                <td>xiaozixuan</td>
                <td><a href="#">修改</a></td>
              </tr>
              <tr>
                <td>密码</td>
                <td>**********</td>
                <td><a href="#">修改</a></td>
              </tr>
              <tr>
                <td>学校</td>
                <td>上海大学</td>
                <td><a href="#">修改</a></td>
              </tr>
              <tr>
                <td>学号</td>
                <td>111111111111</td>
                <td><a href="#">修改</a></td>
              </tr>
              <tr>
                <td>个人邮箱</td>
                <td>example@163.com</td>
                <td><a href="#">修改</a></td>
              </tr>
              <tr>
                <td>现住址</td>
                <td>上海市宝山区上大路99号上海大学</td>
                <td><a href="#">修改</a></td>
              </tr>
              <tr>
                <td>联系方式</td>
                <td>18866666666</td>
                <td><a href="#">修改</a></td>
              </tr>
            </table>
        </div>
      </div>
    </div>
  </div>
	
  <div class="footer">
    <div>本站归肖梓轩所有。Copyright 2019-2020</div>
    <div><a href="">联系我们</a></div>
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script src="../build/three.min.js"></script>
<script src="../build/ColladaLoader2.js"></script>
<script src="../build/STLLoader.js"></script>
<script src="../build/eventemitter2.js"></script>
<script src="../build/roslib.min.js"></script>
<script src="../build/ros3d.min.js"></script>
<script src="../build/jointstatepublisher.js"></script>
<script src="../build/serial_receiver.js"></script>
<script src="../build/hand_posture.js"></script>
<script type="text/javascript" src="../jsmpeg/jsmpeg.min.js"></script>
<script type="text/javascript" src="../build/leap-0.6.4.min.js"></script>
<script type="text/javascript" src="../build/leapmotion_controller.js"></script>
<script src="../build/babel.min.js"></script> <!--JSX framework -->
<script>
  /**
   * Setup all visualization elements when the page is loaded.
   */
  $(document).ready(function() {
    var topic;
    // Connect to ROS.
    var ros = new ROSLIB.Ros({
      url: 'ws://10.0.0.10:9090'
    });

    ros.on('connection', function() {
      $('#ros-connect-info').css('color', 'green');
      $('#ros-connect-info').text('已连接至ROS系统');
    });

    ros.on('error', function(error) {
      $('#ros-connect-info').css('color', 'red');
      $('#ros-connect-info').text('尝试与ROS系统建立连接发生错误，刷新页面重试');
    });

    ros.on('close', function() {
      $('#ros-connect-info').css('color', 'red');
      $('#ros-connect-info').text('未连接ROS系统，刷新页面重试');
    });

    // Create the main viewer.
    var viewer = new ROS3D.Viewer({
      divID : 'urdf',
      width : 580,
      height : 480,
      antialias : true
    });

    // Add a grid.
    viewer.addObject(new ROS3D.Grid());

    // Setup a client to listen to TFs.
    var tfClient = new ROSLIB.TFClient({
      ros : ros,
      angularThres : 0.01,
      transThres : 0.01,
      rate : 10.0,
      fixedFrame : '/base_link'
    });

    // Setup the URDF client.
    var urdfClient = new ROS3D.UrdfClient({
      ros : ros,
      tfClient : tfClient,
      rootObject : viewer.scene,
      path : 'http://10.0.0.10:80/~ginli/robot_hand_3/',
      loader : ROS3D.COLLADA_LOADER_2,

    });
        
    var jsp = new JOINTSTATEPUBLISHER.JointStatePublisher({
        ros : ros,
        divID : 'sliders'
    });
       
    var serialReceiver = SerialReceiver({ros: ros});

    var canvas = document.getElementById('video-canvas');
		var url = 'ws://'+document.location.hostname+':8082/';
		var player = new JSMpeg.Player(url, {canvas: canvas});

    runLeapMotion();

    $('.toggle-section').hide();
    $('#home-section').show();

    $('#grand-menu li a').click(function(){
      $('#grand-menu li').removeClass('active current');
      $(this).parent().addClass('active current');
      $('.toggle-section').hide();

      switch($(this).attr('id')) {
        case 'home-option':
          $('#home-section').show();
          break;
        case 'controller-option':
          $('#controller-section').show();
          break;
        case 'document-option':
          $('#doc-section').show();
          break;
        case 'user-option':
          $('#user-center-section').show();
          break;
        default:
          $('.toggle-section').hide();
      }
    });

    $('#com-good').click(function(){
      $(this).css('background-color', 'green');
      $('#com-bad').css('background-color', 'white');
    });

    $('#com-bad').click(function(){
      $(this).css('background-color', 'red');
      $('#com-good').css('background-color', 'white');
    });

    $('#vib-good').click(function(){
      $(this).css('background-color', 'green');
      $('#vib-bad').css('background-color', 'white');
    });

    $('#vib-bad').click(function(){
      $(this).css('background-color', 'red');
      $('#vib-good').css('background-color', 'white');
    });

    $('#err-good').click(function(){
      $(this).css('background-color', 'green');
      $('#err-bad').css('background-color', 'white');
    });

    $('#err-bad').click(function(){
      $(this).css('background-color', 'red');
      $('#err-good').css('background-color', 'white');
    });

  });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
