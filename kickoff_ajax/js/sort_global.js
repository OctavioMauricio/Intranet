/* ==========================================================
   sort_global.js – Motor Universal de Ordenamiento KickOff
   Autor: mAo + ChatGPT
   Versión 3.0 (2025-12)
   ========================================================== */

console.log("🔄 sort_global.js cargado correctamente");

// ==========================================================
//  Obtener VALOR REAL de una celda (<td>)
//  - texto
//  - links
//  - inputs
//  - selects
// ==========================================================
function obtenerValorCelda(td) {

    if (!td) return "";

    // INPUT
    const input = td.querySelector("input");
    if (input) return input.value.trim();

    // SELECT
    const sel = td.querySelector("select");
    if (sel) return sel.options[sel.selectedIndex]?.text.trim() || "";

    // LINK
    const link = td.querySelector("a");
    if (link) return link.textContent.trim();

    // TEXTO PLANO
    return td.textContent.trim();
}

// ==========================================================
//  Parseo inteligente → numero / fecha / texto
// ==========================================================
function parsearValor(valor) {

    if (!valor) return "";

    let v = valor.replace(/\./g, "").replace(/,/g, ".").trim();

    // YYYY-MM-DD
    if (/^\d{4}-\d{2}-\d{2}$/.test(v)) {
        return new Date(v).getTime();
    }

    // DD/MM/YYYY
    if (/^\d{2}\/\d{2}\/\d{4}$/.test(v)) {
        const [d, m, y] = v.split("/");
        return new Date(`${y}-${m}-${d}`).getTime();
    }

    // NÚMEROS
    if (!isNaN(parseFloat(v)) && isFinite(v)) {
        return parseFloat(v);
    }

    return v.toLowerCase();
}

// ==========================================================
//  Aplicar orden
// ==========================================================
function ordenarTabla(tabla, columna, asc) {

    // Obtener todas las filas
    let filas = Array.from(tabla.querySelectorAll("tr"));

    // Filtrar SOLO las filas de datos (filas con <td> y SIN colspan grande)
    filas = filas.filter(fila => {
        // Tiene TD? (descarta header + título modulo)
        if (!fila.querySelector("td")) return false;

        // Evitar filas de título con colspan gigante (tu caso)
        const td = fila.querySelector("td");
        if (td.hasAttribute("colspan")) return false;

        return true;
    });

    // Ordenar filas de datos
    filas.sort((a, b) => {

        let A = parsearValor(obtenerValorCelda(a.children[columna]));
        let B = parsearValor(obtenerValorCelda(b.children[columna]));

        if (A < B) return asc ? -1 : 1;
        if (A > B) return asc ? 1 : -1;
        return 0;
    });

    // Insertar filas ordenadas *después del header real*
    const headerRow = tabla.querySelector("tr:nth-child(2)"); // título módulo = fila 1, header columnas = fila 2

    filas.forEach(f => headerRow.parentNode.appendChild(f));
}
function activarSortEnTablas() {

    console.log("⚙️ Activando Sort Global…");

    document.querySelectorAll("#modulo-contenedor table").forEach(tabla => {

        const ths = tabla.querySelectorAll("th");
        if (!ths.length) return;

        // Asegura un único listener por tabla
        if (tabla.dataset.sortReady === "1") return;
        tabla.dataset.sortReady = "1";

        ths.forEach((th, colIndex) => {

            th.style.cursor = "pointer";
            th.classList.add("sortable");

            // estado inicial
            th.dataset.order = "none";

            th.addEventListener("click", () => {

                // Alterna ASC/DESC
                const asc = (th.dataset.order !== "asc");

                // Reset SOLO dentro de la MISMA tabla
                ths.forEach(x => {
                    x.dataset.order = "none";
                    x.classList.remove("asc", "desc", "active");
                });

                // Nueva dirección
                th.dataset.order = asc ? "asc" : "desc";
                th.classList.add(asc ? "asc" : "desc", "active");

                ordenarTabla(tabla, colIndex, asc);
            });

        });
    });

    console.log("✅ Sort Global Activo");
}
