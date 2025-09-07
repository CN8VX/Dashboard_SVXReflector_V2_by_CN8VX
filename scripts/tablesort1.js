/* 
Fonctionnement général
- Interaction utilisateur
Quand tu cliques sur l'en-tête "Callsign client" ou "TX off", la fonction tabSort("...") est appelée :
tabSort("EAR") si tu cliques sur "Callsign client"
tabSort("TOP") si tu cliques sur "TX off"*/

function tabSort(type) {
    console.log("tabSort %s", type);

    // Correspondance entre nom logique et index de colonne
    const columnMap = {
        "EAR": 0,   // Callsign client
        "TOP": 5    // TX off
        // Tu peux ajouter d'autres correspondances ici si nécessaire
    };

    const colIndex = columnMap[type];
    if (colIndex === undefined) {
        console.warn("Colonne non définie pour :", type);
        return;
    }

    const table = document.getElementById("logtable");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr"));

    // Déterminer l'ordre du tri
    let currentDir = table.getAttribute("data-sort-dir") || "asc";
    let lastCol = parseInt(table.getAttribute("data-sort-col") || "-1");
    let direction = "asc";

    if (lastCol === colIndex && currentDir === "asc") {
        direction = "desc";
    }

    table.setAttribute("data-sort-dir", direction);
    table.setAttribute("data-sort-col", colIndex);

    // Tri des lignes
    rows.sort((a, b) => {
        const cellA = a.cells[colIndex]?.innerText.trim().toLowerCase() || "";
        const cellB = b.cells[colIndex]?.innerText.trim().toLowerCase() || "";

        if (direction === "asc") {
            return cellA.localeCompare(cellB, 'en', { numeric: true });
        } else {
            return cellB.localeCompare(cellA, 'en', { numeric: true });
        }
    });

    // Réinsertion dans le tbody
    rows.forEach(row => tbody.appendChild(row));
}
createCookie("svxrdb", escape(type), 1);
