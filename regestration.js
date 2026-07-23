const form = document.getElementById("registerForm");
const message = document.getElementById("message");

form.addEventListener("submit", function(e){

    e.preventDefault();
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let dob = document.getElementById("dob").value;
    let gender = document.getElementById("gender").value;
    let qualification = document.getElementById("qualification").value.trim();
    let skills = document.getElementById("skills").value.trim();
    let resume = document.getElementById("resume").value;
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;
    let terms = document.getElementById("terms").checked;

    if(
        name === "" ||
        email === "" ||
        phone === "" ||
        dob === "" ||
        gender === "" ||
        qualification === "" ||
        skills === "" ||
        resume === "" ||
        password === "" ||
        confirmPassword === ""
    ){
        message.style.color = "red";
        message.textContent = "Please fill all fields.";
        return;
    }

    if(phone.length !== 10){
        message.style.color = "red";
        message.textContent = "Phone number must be 10 digits.";
        return;
    }

    if(password.length < 6){
        message.style.color = "red";
        message.textContent = "Password must be at least 6 characters.";
        return;
    }

    if(password !== confirmPassword){
        message.style.color = "red";
        message.textContent = "Passwords do not match.";
        return;
    }

    if(!terms){
        message.style.color = "red";
        message.textContent = "Please accept the Terms & Conditions.";
        return;
    }

    message.style.color = "green";
    message.textContent = "Registration Successful!";

    form.reset();
    alert("Registration Successful!");
});
