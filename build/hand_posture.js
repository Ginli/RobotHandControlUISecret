function handPosture_1() {
	document.getElementById("prox_finger_4_slider").value = 88;
	document.getElementById("prox_finger_3_slider").value = 88;
	document.getElementById("prox_finger_2_slider").value = 88;
	document.getElementById("prox_finger_1_slider").value = 0;
	document.getElementById("base_thumb_slider").value = 90;
	document.getElementById("prox_thumb_slider").value = 49;

	triggerChanges();
}

function handPosture_2() {
	document.getElementById("prox_finger_4_slider").value = 88;
	document.getElementById("prox_finger_3_slider").value = 88;
	document.getElementById("prox_finger_2_slider").value = 0;
	document.getElementById("prox_finger_1_slider").value = 0;
	document.getElementById("base_thumb_slider").value = 90;
	document.getElementById("prox_thumb_slider").value = 49;

	triggerChanges();
}

function handPosture_3() {
	document.getElementById("prox_finger_4_slider").value = 88;
	document.getElementById("prox_finger_3_slider").value = 0;
	document.getElementById("prox_finger_2_slider").value = 0;
	document.getElementById("prox_finger_1_slider").value = 0;
	document.getElementById("base_thumb_slider").value = 90;
	document.getElementById("prox_thumb_slider").value = 49;

	triggerChanges();
}

function handPosture_4() {
	document.getElementById("prox_finger_4_slider").value = 0;
	document.getElementById("prox_finger_3_slider").value = 0;
	document.getElementById("prox_finger_2_slider").value = 0;
	document.getElementById("prox_finger_1_slider").value = 0;
	document.getElementById("base_thumb_slider").value = 90;
	document.getElementById("prox_thumb_slider").value = 49;

	triggerChanges();
}

function handPosture_5() {
	document.getElementById("prox_finger_4_slider").value = 0;
	document.getElementById("prox_finger_3_slider").value = 0;
	document.getElementById("prox_finger_2_slider").value = 0;
	document.getElementById("prox_finger_1_slider").value = 0;
	document.getElementById("base_thumb_slider").value = 0;
	document.getElementById("prox_thumb_slider").value = 0;

	triggerChanges();
}

function handPosture_6() {
	document.getElementById("prox_finger_4_slider").value = 0;
	document.getElementById("prox_finger_3_slider").value = 88;
	document.getElementById("prox_finger_2_slider").value = 88;
	document.getElementById("prox_finger_1_slider").value = 88;
	document.getElementById("base_thumb_slider").value = 0;
	document.getElementById("prox_thumb_slider").value = 0;

	triggerChanges();
}

function handPosture_ok() {
	document.getElementById("prox_finger_4_slider").value = 0;
	document.getElementById("prox_finger_3_slider").value = 0;
	document.getElementById("prox_finger_2_slider").value = 0;
	document.getElementById("prox_finger_1_slider").value = 88;
	document.getElementById("base_thumb_slider").value = 89;
	document.getElementById("prox_thumb_slider").value = 56;

	triggerChanges();
}

function triggerChanges() {
	$('#prox_finger_4_slider').trigger('change');
	$('#prox_finger_3_slider').trigger('change');
	$('#prox_finger_2_slider').trigger('change');
	$('#prox_finger_1_slider').trigger('change');
	$('#base_thumb_slider').trigger('change');
	$('#prox_thumb_slider').trigger('change');
}
