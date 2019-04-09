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
  	border-radius: 5px;
    border: 1px solid;
    background-color: seashell;
  }

  #ui-header {
    font-size: xx-large;
    color: azure;
    text-align: center;
  }

  #logout-btn {
    font-size: small;
  }

  #ros-connect-info {
    background-color: #e0e0eb;
    text-align: center;
    font-size: 20px;
  }

  .hand-info {
  	font-size: large;
  }

  .footer {
    text-align: center;
    color: white;
  }
</style>
</head>

<body class="bg-dark">
	<nav id="ui-header">机械手控制界面 <a type="button" href="logout.php" id="logout-btn" class="btn btn-outline-info">退出登录</a></nav>
  	<p id="ros-connect-info"></p>
	<div class="container">
      <div class="row window">
        <div class="col-md-12">
          <h2 class="detail-title">机械手简介</h2>
          <img src="../img/hand.jpg" style="float: left; width: 130px;"/>
          <div class="hand-info" style="margin: 30px 40px 30px 40px">
            该手具有5个手指，采用合理的驱动和传动方式，配合相关的传感器实现闭环控制系统，力控制精度达1N；手指从张开到完全握紧的反应时间小于1.5s，可抓取2kg的物品，外形拟人化，动作灵巧、轻便，能够模仿人手的基本动作；便于与不同机械臂的安装，可作为工业机器人、服务机器人等的末端执行机构；具有结构简单、制造成本低的特点。
          </div>
        </div>
      </div>
      <div id="row-1" class="row">
        <div id="sliders" class="col-md-4 window">
          <h2 class="detail-title">机械手控制中心</h2>
        </div>
        <div class="col-md-8 window">
          <h2 class="detail-title">实时监控</h2>
          <canvas id="video-canvas"></canvas>
        </div>
      </div>
      <div id="row-2" class="row">
        <div id="hand-posture" class="col-md-4 window">
          <h2 class="detail-title">手势按钮</h2>
          <button type="button" class="btn btn-outline-success" onclick="handPosture_1()">手势“一”</button>
          <button type="button" class="btn btn-outline-success" onclick="handPosture_2()">手势“二”</button>
          <button type="button" class="btn btn-outline-success" onclick="handPosture_3()">手势“三”</button>
          <button type="button" class="btn btn-outline-success" onclick="handPosture_4()">手势“四”</button>
          <button type="button" class="btn btn-outline-success" onclick="handPosture_5()">手势“五”</button>
          <button type="button" class="btn btn-outline-success" onclick="handPosture_6()">手势“六”</button>
          <button type="button" class="btn btn-outline-success" onclick="handPosture_ok()">手势“OK”</button>
        </div>
        <div class="col-md-8 window">
          <h2 class="detail-title">机械手模型</h2>
          <div id="urdf"></div>
        </div>
      </div>
      <div id="row-3" class="row">
        <div class="col-md-12 window">
          <h2 class="detail-title">机械手关节数据显示</h2>
          <div id="serial-receive">
            <table class="table table-hover">
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
                <td>命令数据</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr id="joint-val-from-serial">
                <td>传感器实时数据</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
              </tr>
              <tr id="error-rate">
                <td>误差率</td>
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
      <div id="row-4" class="row">
        <div class="col-md-12 window">
          <h2 class="detail-title">LeapMotion控制</h2>
          <div id="leap-status">Stopped</div>
          <div><button type="button" class="btn btn-outline-success" onclick="startLeapControl()">开始控制</button></div>
          <div><button type="button" class="btn btn-outline-danger" onclick="stopLeapControl()">停止控制</button></div>
          <div>
            <table class="table table-hover">
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
                <td>Leapmotion手关节数据</td>
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
<script type="text/javascript" src="../build/leapmotion_controller.js"></script>>
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
      width : 640,
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

  });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
<?php 

?>
