document.addEventListener("turbo:load", () => {
    const burger = document.querySelector(".burger");
    if (burger) {
        burger.addEventListener("click", () => {
            document.querySelector(".nav").classList.toggle("active");
        });
    }
});
