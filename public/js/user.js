$(function() {
    var Form = {
        init: function() {
			this.bindEvents();
		},
		bindEvents: function() {
            $("#submit").on("click", Control.submit.bind(this));
		},
    };

    var Control = {
        submit: function() {
            var username = $("#username").val();
            var password = $("#password").val();
            var url = "/login/username/" + username + "/password/" + password;
            
            if (username && password) {
                alert(url);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(data) {
                    }
                });
            }
        },
    };

    Form.init();
});