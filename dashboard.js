
window.addEventListener("load", () => {
    console.log("Job Portal Dashboard Loaded Successfully!");

    setTimeout(() => {
        alert("🎉 Welcome to Job Portal Dashboard!");
    }, 500);
});




const searchInput = document.querySelector(".search-box input");
const jobCards = document.querySelectorAll(".job-card");

searchInput.addEventListener("keyup", function () {

    const value = this.value.toLowerCase();

    jobCards.forEach(card => {

        const title = card.querySelector("h3").textContent.toLowerCase();
        const company = card.querySelectorAll("p")[0].textContent.toLowerCase();

        if (title.includes(value) || company.includes(value)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }

    });

});




const applyButtons = document.querySelectorAll(".job-card button");

applyButtons.forEach(button => {

    button.addEventListener("click", function () {

        const job =
            this.parentElement.querySelector("h3").textContent;

        alert("Application submitted for\n\n" + job);

    });

});




jobCards.forEach(card => {

    card.addEventListener("mouseenter", () => {

        card.style.transform = "translateY(-10px)";

    });

    card.addEventListener("mouseleave", () => {

        card.style.transform = "translateY(0px)";

    });

});




const stats = document.querySelectorAll(".card h2");

stats.forEach(stat => {

    const target = parseInt(stat.innerText);

    if (isNaN(target)) return;

    let count = 0;

    const update = () => {

        count += Math.ceil(target / 60);

        if (count < target) {

            stat.innerText = count + "+";
            requestAnimationFrame(update);

        } else {

            stat.innerText = target + "+";

        }

    };

    update();

});



const logout = document.querySelector("nav a:last-child");

logout.addEventListener("click", function (e) {

    const confirmLogout = confirm("Do you want to logout?");

    if (!confirmLogout) {

        e.preventDefault();

    }

});




const categories = document.querySelectorAll(".category");

categories.forEach(category => {

    category.addEventListener("click", () => {

        alert(category.innerText + " Jobs Coming Soon!");

    });

});