import "./bootstrap.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
// import "/app.css";
// import "./styles/app.scss";

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
document.addEventListener("DOMContentLoaded", function () {
    const bar = document.querySelector(".animated-bar");
    if (bar) {
        const content = document.createElement("div");
        content.className = "animated-content";
        content.innerHTML = bar.innerHTML + bar.innerHTML;
        bar.innerHTML = "";
        bar.appendChild(content);
    }
});

const soapElements = document.querySelectorAll(".card p");

soapElements.forEach((el) => {
    const text = el.textContent;
    const firstSentence = text.split(/([.!?])\s/)[0];
    el.textContent =
        firstSentence + (text.length > firstSentence.length ? "." : "");
});
