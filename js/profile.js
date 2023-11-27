
function fetchUserDetails() {
    $.ajax({
        type: "GET",
        url: "./php/profile.php",
        dataType: "json", 
        success: function (data) {
            updateProfileDetails(data);
        },
        error: function (error) {
            console.log("Error fetching user details:", error);
        }
    });
}

function updateProfileDetails(userDetails) {
    if (userDetails.hasOwnProperty('error')) {
        console.log("Error:", userDetails.error);
        return;
    }

    $("#username").text(userDetails.username);

    if (userDetails.hasOwnProperty('dob')) {
        $("#dob").text(userDetails.dob);
    }

    if (userDetails.hasOwnProperty('contact')) {
        $("#contact").text(userDetails.contact);
    }

    if (userDetails.hasOwnProperty('age')) {
        $("#age").text(userDetails.age);
    }
}

$(document).ready(function () {
    fetchUserDetails();

    $("#updateProfileForm").submit(function (event) {
        event.preventDefault();

        var updateDob = $("#updateDob").val();
        var updateContact = $("#updateContact").val();
        var updateAge = $("#updateAge").val();

        $.ajax({
            type: "POST",
            url: "./php/profile.php",
            data: {
                updateDob: updateDob,
                updateContact: updateContact,
                updateAge: updateAge
            },
            dataType: "json",
            success: function (data) {
                updateProfileDetails(data);
                $("#updateDob").val('');
                $("#updateContact").val('');
                $("#updateAge").val('');
            },
            error: function (xhr, status, error) {
                console.log("Error updating/fetching profile:", xhr.responseText);
                console.log("Status:", status);
                console.log("Error:", error);
            }
        });
    });
    
});
