$(document).ready(function () {
    $("#loginBtn").click(function () {
        // Gather user input
        var username = $("#username").val();
        var password = $("#password").val();

        // AJAX request
        $.ajax({
            type: "POST",
            url: "./php/login.php",
            data: {
                username: username,
                password: password
            },
            success: function (response) {
                if (response === "Login successful") {
                    window.location.href = "profile.html"; // Redirect to profile page on success
                } else {
                    alert(response); // You can handle the response as needed
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});
