// =========================
// ORDENAMIENTO DE TABLAS
// sort_columna.js
// =========================

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll("th.sortable").forEach((th, index) => {
    th.addEventListener("click", function () {
      const table = th.closest("table");
      const tbody = table.querySelector("tbody");
      const rows = Array.from(tbody.querySelectorAll("tr"));
      const isAsc = th.classList.contains("asc");

      // Limpiar estado previo
      table.querySelectorAll("th").forEach(header => {
        header.classList.remove("asc", "active");
      });

      // Aplicar nueva clase
      th.classList.add("active");
      if (!isAsc) th.classList.add("asc");

      const colIndex = Array.from(th.parentNode.children).indexOf(th);

      rows.sort((a, b) => {
        const cellA = a.children[colIndex].textContent.trim();
        const cellB = b.children[colIndex].textContent.trim();

        const numA = parseFloat(cellA.replace(",", "").replace(".", ""));
        const numB = parseFloat(cellB.replace(",", "").replace(".", ""));

        if (!isNaN(numA) && !isNaN(numB)) {
          return isAsc ? numA - numB : numB - numA;
        } else {
          return isAsc ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
        }
      });

      // Aplicar filas ordenadas
      rows.forEach(row => tbody.appendChild(row));
    });
  });
});