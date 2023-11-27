$(document).ready(function () {
    $("#loginBtn").click(function (event) {
        event.preventDefault();

        var username = $("#username").val();
        var password = $("#password").val();

        $.ajax({
            type: "POST",
            url: "./php/login.php",
            data: {
                username: username,
                password: password
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Login successful!");
                    window.location.href = "/Guvi/profile.html";
                } else {
                    alert(response.error);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});
