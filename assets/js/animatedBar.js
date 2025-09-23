document.addEventListener("turbo:load", () => {
    const bar = document.querySelector(".animated-bar");
    if (bar) {
        const content = document.createElement("div");
        content.className = "animated-content";
        // Duplique le contenu pour l'animation continue
        content.innerHTML = bar.innerHTML + bar.innerHTML;
        // Vide la barre puis ajoute le nouveau contenu
        bar.innerHTML = "";
        bar.appendChild(content);
    }
});
