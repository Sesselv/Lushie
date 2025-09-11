import "./bootstrap.js";
document.addEventListener("turbo:load", () => {
    /*
     * Welcome to your app's main JavaScript file!
     *
     * This file will be included onto the page via the importmap() Twig function,
     * which should already be in your base.html.twig.
     */
    // import "/app.css";
    // import "./styles/app.scss";

    console.log(
        "This log comes from assets/app.js - welcome to AssetMapper! 🎉"
    );
    const bar = document.querySelector(".animated-bar");
    if (bar) {
        const content = document.createElement("div");
        content.className = "animated-content";
        content.innerHTML = bar.innerHTML + bar.innerHTML;
        bar.innerHTML = "";
        bar.appendChild(content);
    }

    const soapElements = document.querySelectorAll(".card p");

    soapElements.forEach((el) => {
        const text = el.textContent;
        const firstSentence = text.split(/([.!?])\s/)[0];
        el.textContent =
            firstSentence + (text.length > firstSentence.length ? "." : "");
    });

    document.querySelectorAll(".fav-icon").forEach((icon) => {
        icon.addEventListener("click", () => {
            const soapId = icon.dataset.soapId;

            fetch(`/favorite/soap/${soapId}`, { method: "POST" }).then(() => {
                icon.classList.toggle("active");
            });
        });
    });
});
document.addEventListener("DOMContentLoaded", () => {
    const ratingDiv = document.querySelector(".rating");
    if (!ratingDiv) return;

    const stars = ratingDiv.querySelectorAll(".star");
    const soapId = ratingDiv.dataset.soapId;

    let currentRating = 0;
    stars.forEach((s) => {
        if (s.classList.contains("active")) {
            const val = parseInt(s.dataset.value);
            if (val > currentRating) currentRating = val;
        }
    });

    stars.forEach((star) => {
        const val = parseInt(star.dataset.value);

        star.addEventListener("mouseover", () => {
            stars.forEach((s) =>
                s.classList.toggle("active", parseInt(s.dataset.value) <= val)
            );
        });

        star.addEventListener("mouseout", () => {
            stars.forEach((s) =>
                s.classList.toggle(
                    "active",
                    parseInt(s.dataset.value) <= currentRating
                )
            );
        });

        star.addEventListener("click", () => {
            currentRating = currentRating === val ? 0 : val;

            stars.forEach((s) =>
                s.classList.toggle(
                    "active",
                    parseInt(s.dataset.value) <= currentRating
                )
            );

            fetch(SOAP_RATE_URL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({ soapId: soapId, note: currentRating }),
            });
        });
    });
});
