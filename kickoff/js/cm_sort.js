// ===========================================================
// /intranet/kickoff/js/cm_sort.js
// Ordenamiento de columnas para cm_tareas_pendientes.php
// Autor: mAo + ChatGPT
// Codificación: UTF-8 sin BOM
// ===========================================================

document.addEventListener("DOMContentLoaded", () => {
    const tabla = document.getElementById("tareas");
    if (!tabla) {
        console.warn("cm_sort.js: tabla #tareas no encontrada");
        return;
    }

    // La segunda fila (después del título) es la fila de <th>
    const headerRow = tabla.querySelectorAll("tr")[1];
    if (!headerRow) {
        console.warn("cm_sort.js: fila de encabezados no encontrada");
        return;
    }

    const headers = headerRow.querySelectorAll("th");
    headers.forEach((th, colIndex) => {
        th.style.cursor = "pointer";
        th.addEventListener("click", () => ordenarColumna(tabla, colIndex, th));
    });

    console.log("cm_sort.js: inicializado correctamente");
});

// -----------------------------------------------------------
// Obtiene valor comparable desde una celda
// -----------------------------------------------------------
function obtenerValorCelda(td) {
    if (!td) return "";

    // SELECT → texto visible
    const sel = td.querySelector("select");
    if (sel) {
        const txt = sel.options[sel.selectedIndex]?.text || "";
        return txt.trim().toLowerCase();
    }

    // INPUT TEXT
    const inpText = td.querySelector("input[type='text']");
    if (inpText) {
        return inpText.value.trim().toLowerCase();
    }

    // INPUT DATE
    const inpDate = td.querySelector("input[type='date']");
    if (inpDate) {
        // formato YYYY-MM-DD → ya es ordenable como string
        return inpDate.value || "";
    }

    // TEXTO PLANO
    return td.textContent.trim().toLowerCase();
}

// -----------------------------------------------------------
// Ordena filas por columna
// -----------------------------------------------------------
function ordenarColumna(tabla, colIndex, th) {

    // Dirección actual
    const actual = th.dataset.orden === "asc" ? "asc" : "desc";
    const nuevaDireccion = actual === "asc" ? "desc" : "asc";

    // Limpiar estado de otros th
    const allTh = tabla.querySelectorAll("tr:nth-child(2) th");
    allTh.forEach(h => h.dataset.orden = "");

    // Guardar estado en el th clickeado
    th.dataset.orden = nuevaDireccion;

    // Filas de datos (tareas)
    const filas = Array.from(tabla.querySelectorAll("tr[data-id]"));
    if (filas.length === 0) return;

    filas.sort((a, b) => {
        const A = obtenerValorCelda(a.children[colIndex]);
        const B = obtenerValorCelda(b.children[colIndex]);

        if (A < B) return (nuevaDireccion === "asc") ? -1 : 1;
        if (A > B) return (nuevaDireccion === "asc") ? 1 : -1;
        return 0;
    });

    const tbody = tabla.tBodies[0] || tabla;

    // Reinsertar filas en el nuevo orden
    filas.forEach(f => tbody.appendChild(f));
}