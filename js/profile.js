// Declare fetchUserDetails in the global scope
function fetchUserDetails() {
    $.ajax({
        type: "GET",
        url: "./php/profile.php",
        dataType: "json", // Expect JSON response
        success: function (data) {
            // Update the user details on the page
            updateProfileDetails(data);
        },
        error: function (error) {
            console.log("Error fetching user details:", error);
        }
    });
}

function updateProfileDetails(userDetails) {
    // Check for errors
    if (userDetails.hasOwnProperty('error')) {
        console.log("Error:", userDetails.error);
        return;
    }

    // Update the HTML elements with user details
    $("#username").text(userDetails.username);

    // Check if data is received before updating elements
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

// Wrap your code inside $(document).ready
$(document).ready(function () {
    // Fetch and display user details when the page loads
    fetchUserDetails();

    // Handle profile update form submission
    $("#updateProfileForm").submit(function (event) {
        event.preventDefault();

        // Get the values from the form
        var updateDob = $("#updateDob").val();
        var updateContact = $("#updateContact").val();
        var updateAge = $("#updateAge").val();

        // Perform validation if needed

        // Send the update request to the server
        $.ajax({
            type: "POST",
            url: "./php/profile.php",
            data: {
                updateDob: updateDob,
                updateContact: updateContact,
                updateAge: updateAge
            },
            dataType: "json", // Expect JSON response
            success: function (data) {
                // Update the user details on the page
                updateProfileDetails(data);

                // Clear the form fields
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

    // Function to update the user details on the page
    
});
