function doUpdate() {
	setTimeout("doUpdate()", 1000 * 60);
	$.get("whats-on.php?a=" + Math.random(), function(data) {
		$("#whatson").html(data);
	});
}
	
doUpdate();