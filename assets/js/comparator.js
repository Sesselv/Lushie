document.addEventListener("turbo:load", () => {
    const selectedSoaps = [null, null];

    const dropdowns = document.querySelectorAll(".select-soap-dropdown");
    if (dropdowns) {
        dropdowns.forEach((select) => {
            select.addEventListener("change", (e) => {
                const i = e.target.dataset.index;
                const option = e.target.selectedOptions[0];
                if (!option.value) return;

                selectedSoaps[i] = {
                    name: option.text,
                    image: option.dataset.image,
                    action: option.dataset.action,
                    usage: option.dataset.usage,
                    skin: option.dataset.skin,
                    effect: option.dataset.effect,
                    risk: option.dataset.risk,
                };

                if (e.target.nextElementSibling) {
                    e.target.nextElementSibling.src = selectedSoaps[i].image;
                }
            });
        });
    }

    const compareBtn = document.querySelector(".compare-btn");
    if (compareBtn) {
        compareBtn.addEventListener("click", () => {
            if (!selectedSoaps[0] || !selectedSoaps[1]) {
                alert("Veuillez sélectionner deux savons !");
                return;
            }

            const table = document.querySelector(".comparison-table");
            if (table) table.style.display = "table";

            // noms
            const soap1Name = document.querySelector(".soap1-name");
            const soap2Name = document.querySelector(".soap2-name");
            if (soap1Name) soap1Name.textContent = selectedSoaps[0].name;
            if (soap2Name) soap2Name.textContent = selectedSoaps[1].name;

            // autres infos
            ["action", "usage", "skin", "effect", "risk"].forEach((key) => {
                const el1 = document.querySelector(`.soap1-${key}`);
                const el2 = document.querySelector(`.soap2-${key}`);
                if (el1) el1.textContent = selectedSoaps[0][key];
                if (el2) el2.textContent = selectedSoaps[1][key];
            });
        });
    }
});
