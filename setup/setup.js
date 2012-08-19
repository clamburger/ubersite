$(function () {

	var completed = 0;

	$('.tabs').tabs();

	function submitHandler(event) {

		event.preventDefault();
				
		var form = $(this);
		
		console.log(form);
				
		var inputs = form.find("input");
		var formName = form[0].name;
		
		// Clear the class of all the inputs
		$.each(form.find(".clearfix"), function(value){$(this).removeClass("success error warning")});
		$.each(form.find(".help-inline"), function(Value){$(this).remove()});
		form.find(".btn").removeClass("success danger");
		
		$.post("verify.php", $(this).serialize() + "&formName=" + formName, function (data) {
			data = jQuery.parseJSON(data);
			
			var results = data.results;
			$.each(results, function(key, value) {
			
				var input = form.find("#"+key);
				var parent = input.parent();
				var clearfix = parent.parent();
				clearfix.addClass(value);
				
				input.after("<span class='help-inline'>" + data.details[key] + "</span>");
				
				
			});
			
			var submit = form.find(".btn");
			var currentTab = $(".tabs .active a");
			
			currentTab.removeClass("success error");
			
			if (data.success) {
				submit.addClass("success");
				currentTab.addClass("success");
			} else {
				submit.addClass("danger");
				currentTab.addClass("error");
			}
			
			completed = $(".tabs").find(".success").length;
			
		});
		
	}

	$("form").submit(submitHandler);
	
	$('.tabs').bind('change', function (e) {
		if (e.target.id == "finish-tab") {
		
			if (completed == 4) {
				$("#finish #save-config").removeAttr("disabled");
				$("#finish #save-config").addClass("primary");
				$("#not-complete").hide();
			} else {
				$("#finish #save-config").attr("disabled", "disabled");
				$("#finish #save-config").removeClass("primary");
				$("#not-complete").show();
			}
		
		}
    });
    
    $('#save-config').click( function (e) {
    
		$("#save-config").attr("disabled", "disabled");
		$("#save-config").removeClass("primary");
		
		$("#processing").show();
    
		$.get('finish.php', function(data) {
		
			$("#before-msg").hide();
			$("#save-config").hide();
			
			$("#after-msg").show();
			$("#refresh-page").show();
			
			$("#processing").html("<strong>Complete!</strong>");
			$("#processing").removeClass("info");
			$("#processing").addClass("success");
				
		});
	});
	
	$('#refresh-page').click( function(e) {
		
		location.reload();
	
	});
	
});