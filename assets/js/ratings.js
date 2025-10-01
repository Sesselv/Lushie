//Attends que tout le HTML de la page soit chargé avant d’ex            //Envoie la nouvelle note au serveur via fetch().
fetch(SOAP_RATE_URL, {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify({ soapId: soapId, note: currentRating }),
});
ipt//Sélectionne le premier élément du DOM qui possède la classe .rating.
//Si aucun élément .rating n'est trouvé, le script s'arrête (évite les erreurs JS).
.document
    .addEventListener("turbo:load", () => {
        const ratingDiv = document.querySelector(".rating");
        if (!ratingDiv) return;

        //Sélectionne toutes les étoiles (.star) à l'intérieur du conteneur .rating.
        //Récupère l’attribut data-soap-id du conteneur.

        const stars = ratingDiv.querySelectorAll(".star");
        const soapId = ratingDiv.dataset.soapId;

        //Initialise une variable pour stocker la note actuellement active (par défaut, 0).
        //Si elle a la classe active, on lit sa valeur (data-value).

        //On garde la plus grande valeur rencontrée.
        // currentRating reflète la note déjà enregistrée (utile si la page est chargée avec une note préexistante).
        let currentRating = 0;
        stars.forEach((s) => {
            if (s.classList.contains("active")) {
                const val = parseInt(s.dataset.value);
                if (val > currentRating) currentRating = val;
            }
        });

        //Nouveau parcours de chaque étoile pour leur attacher des événements.
        stars.forEach((star) => {
            const val = parseInt(star.dataset.value);

            //Quand on survole une étoile : On active toutes les étoiles dont la valeur est inférieure ou égale à celle survolée.
            //toutes les étoiles jusqu’à celle survolée s’allument.
            star.addEventListener("mouseover", () => {
                stars.forEach((s) =>
                    s.classList.toggle(
                        "active",
                        parseInt(s.dataset.value) <= val
                    )
                );
            });

            //On réactive seulement les étoiles correspondant à la note actuellement enregistrée (currentRating). Ca annule l'effet temporaire du survol.
            star.addEventListener("mouseout", () => {
                stars.forEach((s) =>
                    s.classList.toggle(
                        "active",
                        parseInt(s.dataset.value) <= currentRating
                    )
                );
            });

            //Si l’utilisateur reclique sur la même étoile, on remet la note à 0 (supprimer la note). Sinon, on met à jour currentRating avec la nouvelle valeur.
            star.addEventListener("click", () => {
                currentRating = currentRating === val ? 0 : val;

                //Met à jour l'affichage des étoiles après le clic. Seules les étoiles jusqu'à currentRating restent actives.
                stars.forEach((s) =>
                    s.classList.toggle(
                        "active",
                        parseInt(s.dataset.value) <= currentRating
                    )
                );

                //Envoie la nouvelle note au serveur via fetch().
                //Méthode : POST.
                //En-têtes : JSON + X-Requested-With (précise que c’est une requête AJAX).
                //Corps (body) : objet JSON contenant soapId et note.
                fetch(SOAP_RATE_URL, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    //Je met soapID et le rating actuel dans le body json pr l'envoyer de l'autre côté
                    body: JSON.stringify({
                        soapId: soapId,
                        note: currentRating,
                    }),
                })
                    .then((response) => {
                        if (response.ok) {
                            window.location.reload();
                        }
                    })
                    .catch((error) => {
                        console.error(
                            "Erreur lors de l'envoi de la note:",
                            error
                        );
                    });
            });
        });
    });
