// Attendre que Turbo (navigation Symfony) ait fini de charger la page
document.addEventListener("turbo:load", () => {
    // Tableau pour stocker les 2 savons sélectionnés (index 0 et 1)
    const selectedSoaps = [null, null];

    // Sélectionner tous les dropdowns de savons (il y en a 2)
    const dropdowns = document.querySelectorAll(".select-soap-dropdown");

    // Vérifier que les dropdowns existent pour éviter les erreurs
    if (dropdowns) {
        // Parcourir chaque dropdown pour lui ajouter un écouteur d'événement
        dropdowns.forEach((select) => {
            // Écouter quand l'utilisateur change la sélection dans le dropdown
            select.addEventListener("change", (e) => {
                // Récupérer l'index du dropdown (0 pour le premier, 1 pour le deuxième)
                const i = e.target.dataset.index;

                // Récupérer l'option sélectionnée dans le dropdown
                const option = e.target.selectedOptions[0];

                // Si aucune option valide n'est sélectionnée, arrêter ici
                if (!option.value) return;

                // Stocker toutes les informations du savon sélectionné
                selectedSoaps[i] = {
                    name: option.text, // Nom du savon (texte affiché)
                    image: option.dataset.image, // URL de l'image (data-image)
                    action: option.dataset.action, // Action du savon (data-action)
                    usage: option.dataset.usage, // Usage recommandé (data-usage)
                    skin: option.dataset.skin, // Type de peau (data-skin)
                    effect: option.dataset.effect, // Effet du savon (data-effect)
                    risk: option.dataset.risk, // Précautions/risques (data-risk)
                };

                // Afficher l'image du savon sélectionné à côté du dropdown
                if (e.target.nextElementSibling) {
                    e.target.nextElementSibling.src = selectedSoaps[i].image;
                }
            });
        });
    }

    // Sélectionner le bouton "Comparer"
    const compareBtn = document.querySelector(".compare-btn");

    // Vérifier que le bouton existe
    if (compareBtn) {
        // Écouter quand l'utilisateur clique sur "Comparer"
        compareBtn.addEventListener("click", () => {
            // Vérifier que 2 savons ont bien été sélectionnés
            if (!selectedSoaps[0] || !selectedSoaps[1]) {
                alert("Veuillez sélectionner deux savons !");
                return; // Arrêter l'exécution si pas assez de savons
            }

            // Sélectionner le tableau de comparaison (caché par défaut)
            const table = document.querySelector(".comparison-table");

            // Rendre le tableau visible
            if (table) table.style.display = "table";

            // === REMPLIR LES NOMS DES SAVONS ===
            // Sélectionner les cellules pour les noms des savons
            const soap1Name = document.querySelector(".soap1-name");
            const soap2Name = document.querySelector(".soap2-name");

            // Remplir avec les noms des savons sélectionnés
            if (soap1Name) soap1Name.textContent = selectedSoaps[0].name;
            if (soap2Name) soap2Name.textContent = selectedSoaps[1].name;

            // === REMPLIR TOUTES LES AUTRES CARACTÉRISTIQUES ===
            // Boucler sur chaque caractéristique à comparer
            ["action", "usage", "skin", "effect", "risk"].forEach((key) => {
                // Sélectionner les cellules pour le savon 1 et savon 2
                const el1 = document.querySelector(`.soap1-${key}`);
                const el2 = document.querySelector(`.soap2-${key}`);

                // Remplir les cellules avec les données ou "Non défini" si vide
                if (el1)
                    el1.textContent = selectedSoaps[0][key] || "Non défini";
                if (el2)
                    el2.textContent = selectedSoaps[1][key] || "Non défini";
            });
        });
    }
});
