document.addEventListener("DOMContentLoaded", () => {

    console.log("Dashboard Monitoring BPS Loaded");

    // Animasi card
    document.querySelectorAll(".dashboard-card").forEach((card, index) => {

        card.style.opacity = 0;
        card.style.transform = "translateY(20px)";

        setTimeout(() => {

            card.style.transition = ".4s ease";
            card.style.opacity = 1;
            card.style.transform = "translateY(0)";

        }, index * 120);

    });

});