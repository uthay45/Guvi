$(document).ready(function () {
    $("#loginBtn").click(function (event) {
        event.preventDefault(); // Prevent the default form submission

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
            dataType: "json", // Expect JSON response
            success: function (response) {
                if (response.success) {
                    // Login successful, redirect to the profile page
                    alert("Login successful!");
                    window.location.href = "/Guvi/profile.html";
                } else {
                    // Error logging in
                    alert(response.error);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});
