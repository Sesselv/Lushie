document.addEventListener("turbo:load", () => {
    document.querySelectorAll(".feature-box").forEach((box) => {
        const btn = box.querySelector(".toggle-btn");

        btn.addEventListener("click", () => {
            box.classList.toggle("active");
            btn.textContent = box.classList.contains("active") ? "–" : "+";
        });
    });
});
