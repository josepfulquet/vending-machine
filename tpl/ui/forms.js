$(document).ready(function(){

	$("form").on("submit", function(e){
		e.preventDefault();
		var form = $(this);
		var formData = new FormData($('form')[0]);
		$.ajax({
			method		: form.attr("method"),
			url			: form.attr("action"),
			data		: formData,
			dataType	: "json",
			contentType	: false,
			processData	: false,
			success		: function(response){
//console.log(response);
				if (response.action === "msg"){
					showAlert(response);
					if (response.status === "OK"){
						form.hide();
					}
				}
				else if (response.action === "location"){
					location.href= response.url;
				}
			}
		});
	});


	/*
		ALERTS
	*/


	$("input, select, textarea").on("click", function(){
		hideAlert();
	});



	function showAlert(data){
		$("#alert-box").removeClass();
		var _class = "";
		if (data.status === "OK"){
			_class = "alert alert-success";
		}
		else if (data.status === "KO"){
			_class = "alert alert-danger";
		}
		$("#alert-box").addClass(_class)
						.html("<p>"+data.msg+"</p>")
						.show();
	}

	function hideAlert(){
		$("#alert-box").hide()
						.html("");

	}

});
