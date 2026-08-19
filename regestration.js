const form = document.getElementById("registerForm");
const message = document.getElementById("message");

form.addEventListener("submit", function (e) {

    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const dob = document.getElementById("dob").value;
    const gender = document.getElementById("gender").value;
    const qualification = document.getElementById("qualification").value.trim();
    const skills = document.getElementById("skills").value.trim();
    const resume = document.getElementById("resume").files[0];
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const terms = document.getElementById("terms").checked;

    if (
        name === "" ||
        email === "" ||
        phone === "" ||
        dob === "" ||
        gender === "" ||
        qualification === "" ||
        skills === "" ||
        !resume ||
        password === "" ||
        confirmPassword === ""
    ) {
        message.style.color = "red";
        message.textContent = "Please fill all fields.";
        return;
    }

    if (phone.length !== 10) {
        message.style.color = "red";
        message.textContent = "Phone number must be 10 digits.";
        return;
    }

    if (password.length < 6) {
        message.style.color = "red";
        message.textContent = "Password must be at least 6 characters.";
        return;
    }

    if (password !== confirmPassword) {
        message.style.color = "red";
        message.textContent = "Passwords do not match.";
        return;
    }

    if (!terms) {
        message.style.color = "red";
        message.textContent = "Please accept the Terms & Conditions.";
        return;
    }

    const formData = new FormData();

    formData.append("name", name);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("dob", dob);
    formData.append("gender", gender);
    formData.append("qualification", qualification);
    formData.append("skills", skills);
    formData.append("resume", resume);
    formData.append("password", password);

    message.style.color = "#67e8f9";
    message.textContent = "Registering...";

    fetch("register.php", {
        method: "POST",
        body: formData
    })
    .then(response => {

        if (response.redirected) {
            window.location.href = response.url;
            return null;
        }

        return response.text();
    })
    .then(data => {

        if (data === null) {
            return;
        }

        if (data.trim() === "success") {

            message.style.color = "#22c55e";
            message.textContent = "Registration Successful!";

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