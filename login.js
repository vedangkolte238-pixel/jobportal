const form = document.getElementById("loginForm");
const message = document.getElementById("message");

form.addEventListener("submit", function (e) {

    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    if (email === "" || password === "") {
        message.style.color = "red";
        message.textContent = "Please fill all fields.";
        return;
    }

    const formData = new FormData();

    formData.append("email", email);
    formData.append("password", password);

    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {

        if (data.trim() === "success") {

            message.style.color = "#22c55e";
            message.textContent = "Login Successful!";

            setTimeout(() => {
                window.location.href = "dashboard.html";
            }, 1500);

        } else {

            message.style.color = "red";
            message.textContent = data;

        }

    })
    .catch(error => {

        console.log(error);

        message.style.color = "red";
        message.textContent = "Server Error!";

    });

});