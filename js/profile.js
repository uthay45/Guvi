// Other existing code for profile.js

// Add the following code for updating the profile
document.getElementById("updateProfileForm").addEventListener("submit", function (event) {
    event.preventDefault();

    // Get form data
    var updateDob = document.getElementById("updateDob").value;
    var updateContact = document.getElementById("updateContact").value;
    var updateAge = document.getElementById("updateAge").value;

    // Perform validation (you can add more validation logic)

    // Send data to the server using AJAX
    // Example using fetch API
    fetch("./php/profile.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            updateDob: updateDob,
            updateContact: updateContact,
            updateAge: updateAge,
        }),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // Handle response from the server (e.g., show success/failure message)
    })
    .catch(error => {
        console.error("Error:", error);
    });
});
