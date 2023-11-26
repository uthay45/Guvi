

document.getElementById("registerForm").addEventListener("submit", function (event) {
    event.preventDefault();

    // Get form data
    var name = document.getElementById("name").value;
    var password = document.getElementById("password").value;
    var dob = document.getElementById("dob").value;
    var contact = document.getElementById("contact").value;
    var age = document.getElementById("age").value;

    // Perform validation (you can add more validation logic)

    // Send data to the server using AJAX
    // Example using fetch API
    fetch("./php/register.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            name: name,
            password: password,
            dob: dob,
            contact: contact,
            age: age,
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
