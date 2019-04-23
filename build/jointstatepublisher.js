var JOINTSTATEPUBLISHER = JOINTSTATEPUBLISHER || {
  REVISION : '0.0.1-JOINTSTATEPUBLISHER'
};

var INTERNAL_EXTERNAL_JOINT_MAP = new Map([
	['base_thumb', '大拇指1'],
	['prox_thumb', '大拇指2'],
	['prox_finger_1', '食指'],
	['prox_finger_2', '中指'],
	['prox_finger_3', '无名指'],
	['prox_finger_4', '小指']
]);

/** Replicating the joint_state_publisher node's functionality in the browser 
 * @constructor
 * @param options - object with following keys:
 *   * ros - the ROSLIB.Ros connection handle
 *   * paramName - the parameter to read the robot description from
 *   * topicName - topic to publish joint states on
 *   * divID - the ID of the div to place the sliders
 *   * 
 */
JOINTSTATEPUBLISHER.JointStatePublisher = function(options) {
  var that = this;
  options = options || {};
  var ros = options.ros;
  var paramName = options.paramName || 'robot_description';
  var topicName = options.topicName || '/web_joint_states';
  
  var sliders = [];
  
  var param = new ROSLIB.Param({
    ros : ros,
    name : paramName
  });
  
  function updateInput(event) {
    var name = event.target.name;
    var target;
    var jointName;

    if( name.indexOf('_text') >= 0) {
        target = name.replace('_text', '');
        jointName = target;
    }else{
        target = name + '_text';
        jointName = name;
    }

    document.getElementById(target).value = event.target.value;
    var matchedJointsRegex = new RegExp('^' + name.replace('prox_', '((mid_)|(dist_))') + '$');

	for(var index = 0; index < sliders.length; index++) { // 同步同一个手指其他关节的值
		var slider = sliders[index];

        if (matchedJointsRegex.test(slider.name)) {
	    	slider.value = event.target.value;
	    }
    }

    updateJointTable();
    
    publishAll();
  };
   
  var load_model = function(param) {
    var parser = new DOMParser();
    var xmlDoc = parser.parseFromString(param, 'text/xml');
    
    // See https://developer.mozilla.org/docs/XPathResult#Constants
    var XPATH_FIRST_ORDERED_NODE_TYPE = 9;
    
    var robotXml = xmlDoc.evaluate('//robot', xmlDoc, null, XPATH_FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
    var c = 0;
    var container = document.getElementById('sliders');
    container.setAttribute('style', 'font-size: 26px');

    for (var nodes = robotXml.childNodes, i = 0; i < nodes.length; i++) {
        var node = nodes[i];
        if(node.tagName==='joint'){
            if(node.getAttribute('type')!=='fixed'){
  
                var minval, maxval;
                var limit = node.getElementsByTagName('limit')[0];
                minval = 0;
                maxval = 90;
                var val = minval;
                var name = node.getAttribute('name');

                if (name.startsWith('prox_') || name.startsWith('base_thumb')) {
  		        	container.appendChild(document.createTextNode(INTERNAL_EXTERNAL_JOINT_MAP.get(name)));
  		    	}

                var x = document.createElement('input');
                x.setAttribute('name', name + '_text');
                x.setAttribute('id', name + '_text');
                x.setAttribute('style', 'width: 120px; float: right');
  		        x.setAttribute('value', val);
                container.appendChild(x);

                if (!name.startsWith('prox_') && !name.startsWith('base_thumb')) {
  		        	x.style.display = 'none';
  		    	}
                
                x = document.createElement('input');
                x.setAttribute('name', name);
                x.setAttribute('id', name + '_slider');
                x.setAttribute('type', 'range');
                x.setAttribute('min', minval);
                x.setAttribute('max', maxval);
                x.setAttribute('step', 1);
                x.setAttribute('style', 'width: 100%');
                x.setAttribute('value', val);
                x.onchange = updateInput;
                container.appendChild(x);

                if (!name.startsWith('prox_') && !name.startsWith('base_thumb')) {
  		        	x.style.display = 'none';
  		    	} else {
  		    		container.appendChild( document.createElement('br') );
  		    	}

                sliders[ sliders.length ] = x;
            }
        }
    }  
  };
  
  param.get(load_model);
    
  var topic = new ROSLIB.Topic({
    ros : ros,
    name : topicName,
    messageType : 'sensor_msgs/JointState'
  });
  
  function publishOne(jointName, jointValue)
  {
      var js = new ROSLIB.Message({
        name: [jointName], position: [parseFloat(jointValue)]
      });
      topic.publish(js);
  }

  // Publish arc value instead of angle
  function publishAll() {
      var names = [];
      var values = [];

      for(var index = 0; index < sliders.length; index++) {
        var slider = sliders[index];
        names[ names.length ] = slider.name;
        values[ values.length ] = parseFloat(slider.value) / 180 * Math.PI;
      }

      var js = new ROSLIB.Message({
        name: names, position: values
      });
      topic.publish(js);
  }

  function updateJointTable() {
  	var cmdValues = [];

  	INTERNAL_EXTERNAL_JOINT_MAP.forEach(function(value, key, map){
  		cmdValues[cmdValues.length] = document.getElementById(key + '_slider').value;
  	});

  	var cols = $('#joint-val-from-cmd').children();

  	for (var i = 1; i < cols.length; i++) {
  		cols[i].innerHTML = cmdValues[i - 1];
  	}
  }


};




