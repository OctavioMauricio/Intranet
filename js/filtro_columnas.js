function initFiltroColumna(tableSelector, config = {}) {
  const table = document.querySelector(tableSelector);
  if (!table) return;

  const head = table.querySelector("thead");
  const rows = Array.from(table.querySelectorAll("tbody tr"));
  const headerCells = head.querySelectorAll("tr:first-child th");

  // Eliminar fila previa de filtros si ya existe
  const oldFilterRow = head.querySelector("tr.filters");
  if (oldFilterRow) oldFilterRow.remove();

  // Crear fila de filtros
  const filterRow = document.createElement("tr");
  filterRow.classList.add("filters");

  headerCells.forEach((th, i) => {
    const cell = document.createElement("th");
    if (config.excludedCols && config.excludedCols.includes(i)) {
      cell.innerHTML = "";
    } else {
      const input = document.createElement("input");
      input.type = "text";
      input.classList.add("filter-input");
      input.placeholder = "🔎";
      input.dataset.col = i;

      input.addEventListener("input", () => filtrarTabla());

      cell.appendChild(input);
    }
    filterRow.appendChild(cell);
  });

  head.appendChild(filterRow);

  function filtrarTabla() {
    const filtros = table.querySelectorAll(".filter-input");

    rows.forEach(row => {
      const celdas = row.querySelectorAll("td");
      let mostrar = true;

      filtros.forEach(input => {
        const colIndex = parseInt(input.dataset.col);
        const valorCelda = (celdas[colIndex]?.textContent || "").toLowerCase();
        const valorFiltro = input.value.toLowerCase();

        if (valorFiltro && !valorCelda.includes(valorFiltro)) {
          mostrar = false;
        }
      });

      row.style.display = mostrar ? "" : "none";
    });
  }
}