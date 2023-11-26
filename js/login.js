document.getElementById("loginForm").addEventListener("submit", function (event) {
    event.preventDefault();

    // Get form data
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    // Perform validation (you can add more validation logic)

    // Send data to the server using AJAX
    // Example using fetch API
    fetch("./php/login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            username: username,
            password: password,
        }),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // Handle response from the server (e.g., redirect to profile page on success)
    })
    .catch(error => {
        console.error("Error:", error);
    });
});
