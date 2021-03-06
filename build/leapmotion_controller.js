var start = false;

function startLeapControl() {
	start = true;
	$('#leap-status').text('Started');
}

function stopLeapControl() {
	start = false;
	$('#leap-status').text('Stopped');
}

function runLeapMotion() {
	var controllerOptions = {};

	Leap.loop(controllerOptions, function(frame){
		if (!start) {
			return; //Skip processing
		}

		console.log('Running leapmotion...');

		if (frame.hands.length > 0) {
			//Only process one hand.
			var hand = frame.hands[0];
			var handDirection = hand.direction;

			if (hand.fingers.length > 0) {
				var thumb_1_direction = hand.thumb.bones[1].direction();
				var thumb_2_direction = hand.thumb.bones[2].direction();
				var thumb_finger_angle = getVec3Angle(thumb_1_direction, thumb_2_direction);

				var thumb_turn_angle = getVec3Angle(
					Leap.vec3.fromValues(thumb_1_direction[0], thumb_1_direction[1], 0), 
					Leap.vec3.fromValues(0, 1, 0));

				var index_angle = getVec3Angle(handDirection, hand.indexFinger.direction);
				var middle_angle = getVec3Angle(handDirection, hand.middleFinger.direction);
				var ring_angle = getVec3Angle(handDirection, hand.ringFinger.direction);
				var pinky_angle = getVec3Angle(handDirection, hand.pinky.direction);

				updateTargetTableCols('joint-val-from-leap', thumb_turn_angle, thumb_finger_angle, index_angle, middle_angle, ring_angle, pinky_angle);
				updateTargetTableCols('joint-val-from-cmd', thumb_turn_angle, thumb_finger_angle, index_angle, middle_angle, ring_angle, pinky_angle);
				updateSliders(thumb_turn_angle, thumb_finger_angle, index_angle, middle_angle, ring_angle, pinky_angle);
			}
		}
	});
}

function getVec3Angle(v1, v2) {
	Leap.vec3.normalize(v1, v1);
	Leap.vec3.normalize(v2, v2);
	var arc = Math.acos(Leap.vec3.dot(v1, v2));
	return Math.round(arc / Math.PI * 180);
}

function updateTargetTableCols(id, thumb_1, thumb_2, index, middle, ring, pinky) {
	var cols = $('#' + id).children();
	//First one is name
	cols[1].innerHTML = thumb_1;
	cols[2].innerHTML = thumb_2;
	cols[3].innerHTML = index;
	cols[4].innerHTML = middle;
	cols[5].innerHTML = ring;
	cols[6].innerHTML = pinky;
}

function updateSliders(thumb_1, thumb_2, index, middle, ring, pinky) {
	document.getElementById("prox_finger_4_slider").value = pinky;
	document.getElementById("prox_finger_3_slider").value = ring;
	document.getElementById("prox_finger_2_slider").value = middle;
	document.getElementById("prox_finger_1_slider").value = index;
	document.getElementById("base_thumb_slider").value = thumb_1;
	document.getElementById("prox_thumb_slider").value = thumb_2;
	triggerChanges();
}