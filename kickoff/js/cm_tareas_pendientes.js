// ===========================================================
// /intranet/kickoff/js/cm_tareas_pendientes.js
// Edición completa de tareas desde Kickoff (multicampo)
// + Orden por columnas (incluye <select> e <input>)
// Autor: mAo + ChatGPT
// Codificación: UTF-8 sin BOM
// ===========================================================

// -----------------------------------------------------------
// Enviar TODA la fila completa al backend
// -----------------------------------------------------------
function guardarFila(fila) {

    const id = fila.dataset.id;

    if (!id) {
        console.error("Fila sin data-id, no se puede guardar");
        return;
    }

    const categoria  = fila.querySelector("select[data-campo='categoria']")?.value || "";
    const prioridad  = fila.querySelector("select[data-campo='prioridad']")?.value || "";
    const asignado   = fila.querySelector("select[data-campo='assigned_user_id']")?.value || "";
    const estado     = fila.querySelector("select[data-campo='estado']")?.value || "";
    const en_espera  = fila.querySelector("input[data-campo='en_espera']")?.value.trim() || "";
    const fechaSQL   = fila.querySelector("input[data-campo='date_due']")?.value.trim() || ""; 
    // input type="date" ya viene como YYYY-MM-DD

    const datos = new URLSearchParams();
    datos.append("id", id);
    datos.append("categoria", categoria);
    datos.append("prioridad", prioridad);
    datos.append("assigned_user_id", asignado);
    datos.append("estado", estado);
    datos.append("en_espera", en_espera);
    datos.append("date_due", fechaSQL);

    fetch("/kickoff/ajax/update_tarea.php", {
        method: "POST",
        body: datos,
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
    })
    .then(r => r.json())
    .then(j => {
        if (j.success) {
            marcarOK(fila);

            // Si backend informa notificación → alert
            if (j.mail_info) {
                alert("✔ " + j.mail_info);
            }

        } else {           
            marcarError(fila, j.error || "Error desconocido");
        }   
    })
    .catch(e => marcarError(fila, e.message));
}

// -----------------------------------------------------------
// Marca en verde suavemente → guardado OK
// -----------------------------------------------------------
function marcarOK(fila) {
    fila.style.backgroundColor = "#d6ffd6";
    setTimeout(() => fila.style.backgroundColor = "", 700);
}

// -----------------------------------------------------------
// Marca en rojo → error en guardado
// -----------------------------------------------------------
function marcarError(fila, error) {
    fila.style.backgroundColor = "#ffd4d4";
    console.error("ERROR:", error);
    alert("❌ Error al guardar:\n" + error);
    setTimeout(() => fila.style.backgroundColor = "", 2000);
}

// -----------------------------------------------------------
// BINDERS → cuando cambia un campo, enviamos la fila completa
// -----------------------------------------------------------
function bindTareasPendientes() {

    document.querySelectorAll("tr[data-id]").forEach(fila => {

        // SELECTS
        fila.querySelectorAll("select[data-campo]").forEach(sel => {
            sel.addEventListener("change", function() {
                guardarFila(fila);
            });
        });

        // INPUT TEXT
        fila.querySelectorAll("input[data-campo][type='text']").forEach(inp => {
            inp.addEventListener("change", function() {
                guardarFila(fila);
            });
        });

        // FECHA (input type="date")
        fila.querySelectorAll("input[data-campo='date_due']").forEach(inp => {
            inp.addEventListener("change", function() {
                guardarFila(fila);
            });
        });

    });
}

// ===========================================================
// ORDENAR TABLA POR COLUMNAS (incluye select / input)
// ===========================================================

// Obtiene "valor lógico" de una celda:
// - Si tiene <select>: etiqueta seleccionada
// - Si tiene <input>: value
// - Si no: texto plano
function getCellValue(row, index) {
    const cell = row.cells[index];
    if (!cell) return "";

    const sel = cell.querySelector("select[data-campo]");
    if (sel) {
        return (sel.options[sel.selectedIndex]?.text || "").toLowerCase();
    }

    const inp = cell.querySelector("input[data-campo]");
    if (inp) {
        return (inp.value || "").toLowerCase();
    }

    return (cell.textContent || cell.innerText || "").trim().toLowerCase();
}

// Comparador genérico, con detección simple de números
function makeComparator(index, asc) {
    return function(a, b) {
        const va = getCellValue(a, index);
        const vb = getCellValue(b, index);

        // ¿Ambos numéricos? (para la columna DÍAS, por ejemplo)
        const na = parseFloat(va.replace(",", "."));
        const nb = parseFloat(vb.replace(",", "."));
        const ambosNumeros = !isNaN(na) && !isNaN(nb);

        if (ambosNumeros) {
            if (na < nb) return asc ? -1 : 1;
            if (na > nb) return asc ?  1 : -1;
            return 0;
        }

        // Comparación alfabética
        if (va < vb) return asc ? -1 : 1;
        if (va > vb) return asc ?  1 : -1;
        return 0;
    };
}

function activarOrdenPorColumnas() {
    const tabla = document.getElementById("tareas");
    if (!tabla) return;

    // Segunda fila = encabezados (la primera es el título morado)
    const headers = tabla.querySelectorAll("tr:nth-child(2) th");
    if (!headers.length) return;

    headers.forEach((th, index) => {
        th.style.cursor = "pointer";
        th.title = "Ordenar por esta columna";

        let asc = true;

        th.addEventListener("click", function() {

            const rows = Array.from(tabla.querySelectorAll("tr[data-id]"));
            if (!rows.length) return;

            rows.sort(makeComparator(index, asc));
            asc = !asc;

            const tbody = tabla.tBodies[0] || tabla;
            rows.forEach(r => tbody.appendChild(r));
        });
    });
}

// -----------------------------------------------------------
// Activar cuando la página esté lista
// -----------------------------------------------------------
document.addEventListener("DOMContentLoaded", function () {
    bindTareasPendientes();
    activarOrdenPorColumnas();
});