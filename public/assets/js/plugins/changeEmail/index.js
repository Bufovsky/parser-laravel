function updateEmail(id)
{
	$(".email" + id).removeAttr("onclick");
	$(".email" + id).html("<div class='input-group'> <input id='changeEmailId' type='hidden' value='" + id + "'/> <input id='changeEmail' class='form-control' type='email' autocomplete='off' autofocus/> </div>");
}

$(document).keypress(function(e) {
	if (e.which == 13) {
		if ($("#changeEmailId").length > 0 && $("#changeEmail").length > 0) {
			var id = $("#changeEmailId").val();
			var email = $("#changeEmail").val();
			$(".email" + id).html(email);
			$(".email" + id).attr("onclick", "changeEmail("+ id +")");
			
			
			var value = email.includes("@");
			$("#checkbox"+ id).prop("checked", value);
			/*
			if (email.includes("@")) {
				$("#checkbox"+ id).prop("checked", true);
			}else{
				$("#checkbox"+ id).prop("checked", false);
			}
			*/

			$.ajax({ 
				type: "GET", 
				url: "//"+ window.location.hostname + "/ajax/changeEmail/"+ id +"/"+ email,
				data: {}, 
				dataType: "json",
				success: function (data) { 
					$("." + type + id).html(data[0][returns]);
				}
			});
		}
	}
});