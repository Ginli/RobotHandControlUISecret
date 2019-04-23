var EXTERNAL_JOINT_ORDER_MAP = new Map([
	['thumb_finger_1', 0],
	['thumb_finger_2', 1],
	['index_finger', 2],
	['middle_finger', 3],
	['ring_finger', 4],
	['pinky_finger', 5]
]);

var SerialReceiver = function(options) {
    var ros = options.ros;
    var topicName = options.topicName || '/serial_receive_msgs';
    var paramName = options.paramName || 'robot_description';

    var param = new ROSLIB.Param({
        ros : ros,
        name : paramName
    });

    var listener_topic = new ROSLIB.Topic({
        ros: ros,
        name: topicName,
        messageType: 'std_msgs/String'
    });

    listener_topic.subscribe(function(message) {
        var jointsValues = parseMessage(message);

        jointsValues.forEach(function(value) {
        	try {
        		updateJointTable(value);
            } catch(error) {
                console.log("joint " + value.jointName + " isn't shown up. Ignore...");
            }
        });
    });

    function parseMessage(message) {
        var values = [];
        message.data.split('$').forEach(function(rawValue) {
            var pair = rawValue.split(',');

            if (pair.length != 2) {
            	return values;
            }

            var jointNamePair = pair[0].split(':');

            if (jointNamePair.length != 2 || jointNamePair[0].trim() != 'name') { 
            	return values; 
            }

            var jointValuePair = pair[1].split(':');

            if (jointValuePair.length != 2 || jointValuePair[0].trim() != 'position') { 
            	return values; 
            }

            values.push({jointName: jointNamePair[1].trim(), jointValue: jointValuePair[1].trim()});
        });
        return values;
    }

    function updateJointTable(jointMessage) {
    	var idx = EXTERNAL_JOINT_ORDER_MAP.get(jointMessage.jointName);
    	var angleVal = Math.round(jointMessage.jointValue / Math.PI * 180);
    	$('#joint-val-from-serial').children()[idx + 1].innerHTML = angleVal;

    	var valFromCommand = parseFloat($('#joint-val-from-cmd').children()[idx + 1].innerHTML);
    	$('#error-rate').children()[idx + 1].innerHTML = Math.abs(valFromCommand - angleVal);
    }

}
