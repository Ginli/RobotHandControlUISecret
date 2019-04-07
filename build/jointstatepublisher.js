var JOINTSTATEPUBLISHER = JOINTSTATEPUBLISHER || {
  REVISION : '0.0.1-JOINTSTATEPUBLISHER'
};

var INTERNAL_EXTERNAL_JOINT_MAP = new Map([
	['base_thumb', 'thumb_finger_1'],
	['prox_thumb', 'thumb_finger_2'],
	['prox_finger_1', 'index_finger'],
	['prox_finger_2', 'middle_finger'],
	['prox_finger_3', 'ring_finger'],
	['prox_finger_4', 'pinky_finger']
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

	    console.log(slider.name + ": " + slider.value);
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
    for (var nodes = robotXml.childNodes, i = 0; i < nodes.length; i++) {
        var node = nodes[i];
        if(node.tagName==='joint'){
            if(node.getAttribute('type')!=='fixed'){
  
                var minval, maxval;
                var limit = node.getElementsByTagName('limit')[0];
                minval = parseFloat(limit.getAttribute('lower'));
                maxval = parseFloat(limit.getAttribute('upper'));
                var val = (maxval + minval) / 2;
                var name = node.getAttribute('name');

                if (name.startsWith('prox_') || name.startsWith('base_thumb')) {
  		        	container.appendChild(document.createTextNode(INTERNAL_EXTERNAL_JOINT_MAP.get(name)));
  		    	}

                var x = document.createElement('input');
                x.setAttribute('name', name + '_text');
                x.setAttribute('id', name + '_text');
                x.setAttribute('style', 'float: right');
  		        x.setAttribute('value', val);
                container.appendChild(x);

                if (!name.startsWith('prox_') && !name.startsWith('base_thumb')) {
  		        	x.style.display = 'none';
  		    	} else {
  		    		container.appendChild( document.createElement('br') );
  		    	}
                
                x = document.createElement('input');
                x.setAttribute('name', name);
                x.setAttribute('id', name + '_slider');
                x.setAttribute('type', 'range');
                x.setAttribute('min', minval);
                x.setAttribute('max', maxval);
                x.setAttribute('step', (maxval - minval) / 100);
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

  function publishAll() {
      var names = [];
      var values = [];
      for(var index = 0; index < sliders.length; index++) {
        var slider = sliders[index];
        names[ names.length ] = slider.name;
        values[ values.length ] = parseFloat(slider.value);
      }

      console.log(names);
      console.log(values);
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



