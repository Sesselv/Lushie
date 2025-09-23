document.addEventListener("turbo:load", () => {
    document.querySelectorAll(".comment").forEach((comment) => {
        comment.addEventListener("click", () => {
            // ferme les autres
            document.querySelectorAll(".comment").forEach((c) => {
                if (c !== comment) c.classList.remove("active");
            });

            // toggle la classe active
            comment.classList.toggle("active");
        });
    });
});
