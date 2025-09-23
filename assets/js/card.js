document.addEventListener("turbo:load", () => {
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
