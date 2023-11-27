$(document).ready(function () {
    $("#registerBtn").click(function (event) {
        event.preventDefault(); 

        var name = $("#name").val();
        var password = $("#password").val();
        var dob = $("#dob").val();
        var contact = $("#contact").val();
        var age = $("#age").val();

      
        $.ajax({
            type: "POST",
            url: "./php/register.php",
            data: {
                name: name,
                password: password,
                dob: dob,
                contact: contact,
                age: age
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Registration successful!");
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