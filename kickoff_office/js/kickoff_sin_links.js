// ==========================================================
// 🧠 KICKOFF – Control de Capas, Reloj y Ordenamiento
// Autor: mAo / TNA Group
// Codificación: UTF-8
// ==========================================================

var ptr = 0;
var timeout = false;

// ==========================================================
// 🔁 AUTO REFRESH CONTROL (desactivado por defecto)
// ==========================================================
var refreshId = setInterval(() => window.location.reload(), 60000);
clearInterval(refreshId);

// ==========================================================
// 🚀 Auto submit para select
// ==========================================================
function autoSubmit() {
    var formObject = document.forms['form_select'];
    if (formObject) formObject.submit();
}

// ==========================================================
// 🎛️ Control de capas principales (sin afectar HEADER)
// ==========================================================
const CAPAS_PRINCIPALES = ['capa_casos', 'capa_iconos', 'capa_buscadores'];

function ocultarCapasPrincipales() {
    CAPAS_PRINCIPALES.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.hidden = true;
            el.style.display = 'none';
        }
    });
}

/* 🔥 Nueva versión SEGURA: NO oculta capas que no están definidas */
function mostrarSolo(capaId) {
    CAPAS_PRINCIPALES.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        if (id === capaId) {
            el.hidden = !el.hidden;
            el.style.display = el.hidden ? 'none' : 'block';
        } else {
            el.hidden = true;
            el.style.display = 'none';
        }
    });
}

// Mostrar todas las capas internas (debug)
function mostrarTodasLasCapas() {
    CAPAS_PRINCIPALES.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.hidden = false;
            el.style.display = "block";
        }
    });
}

// ==========================================================
// ⏰ Reloj inteligente con emojis según la hora
// ==========================================================
function obtenerEmojiHora(hora) {
    if (hora >= 9 && hora < 11) return "💼";      // trabajo
    if (hora >= 12 && hora < 14) return "🍽️";    // almuerzo
    if (hora >= 15 && hora < 18) return "⚙️";     // tarde laboral
    if (hora >= 0 && hora < 7) return "🌙";        // madrugada
    return "⏰";                                   // default
}

function mueveReloj() {
    const reloj = document.getElementById("reloj");

    if (!reloj) {
        setTimeout(mueveReloj, 1000);
        return;
    }

    const momentoActual = new Date();
    const hora = momentoActual.getHours().toString().padStart(2, "0");
    const minuto = momentoActual.getMinutes().toString().padStart(2, "0");
    const segundo = momentoActual.getSeconds().toString().padStart(2, "0");

    const fecha = momentoActual.toLocaleDateString("es-CL", {
        day: "2-digit", month: "2-digit", year: "numeric"
    });

    const emoji = obtenerEmojiHora(parseInt(hora));

    reloj.innerHTML = `${emoji} ${hora}:${minuto}:${segundo} — ${fecha}`;

    setTimeout(mueveReloj, 1000);
}

// ==========================================================
// 🌐 Navegación por selects del header
// ==========================================================
function abrirNuevaVentana(url) {
    if (url) {
        window.open(url, '_blank');
    } else {
        alert('Por favor, proporciona una URL válida.');
    }
}

function manejarCambio(selectElement) {
    const urlSeleccionada = selectElement.value;
    if (urlSeleccionada !== "") abrirNuevaVentana(urlSeleccionada);
}

// ==========================================================
// 📊 Ordenamiento de tablas dinámico
// ==========================================================
function inicializarOrdenamiento(context = document) {
    const tables = context.querySelectorAll("table");

    tables.forEach((table) => {
        // No ordenar header
        if (table.closest("#header") || table.classList.contains("no-sort")) return;

        const allRows = Array.from(table.querySelectorAll("tr"));
        const headerRow = allRows.find(r => r.querySelector("th"));
        if (!headerRow) return;

        const headerCells = Array.from(headerRow.querySelectorAll("th,td"));

        headerCells.forEach(cell => {
            cell.classList.add("sortable");
            cell.style.cursor = "pointer";
            cell.title = "Ordenar por esta columna";
        });

        const toNumber = (s) => {
            if (!s) return null;
            const n = parseFloat(s.replace(/[^\d.-]/g, "").replace(",", "."));
            return isNaN(n) ? null : n;
        };

        const parseTime = (timePart) => {
            if (!timePart) return "000000";
            const tm = timePart.match(/^(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?/);
            if (!tm) return "000000";
            return tm.slice(1).map(v => v ? v.padStart(2, "0") : "00").join("");
        };

        const toDateKey = (s) => {
            if (!s) return null;
            let [datePart, timePart] = s.trim().split(/\s+/);
            if (!datePart) return null;

            let m = datePart.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/);
            if (m) {
                let [_, d, mo, y] = m;
                if (y.length === 2) y = (parseInt(y) < 50 ? "20" : "19") + y;
                return parseInt(`${y}${mo.padStart(2,"0")}${d.padStart(2,"0")}${parseTime(timePart)}`, 10);
            }

            m = datePart.match(/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/);
            if (m) {
                let [_, y, mo, d] = m;
                return parseInt(`${y}${mo.padStart(2,"0")}${d.padStart(2,"0")}${parseTime(timePart)}`, 10);
            }
            return null;
        };

        const sortState = new Map();

        if (!table.dataset.sortBound) {
            table.addEventListener("click", (ev) => {
                const cell = ev.target.closest(".sortable");
                if (!cell) return;

                const cells = Array.from(headerRow.children);
                const colIndex = cells.indexOf(cell);
                if (colIndex < 0) return;

                const currentDir = sortState.get(colIndex) || "desc";
                const asc = currentDir === "desc";
                sortState.set(colIndex, asc ? "asc" : "desc");

                const allData = allRows.slice(allRows.indexOf(headerRow) + 1)
                    .filter(tr => tr.querySelectorAll("td").length > 0);

                allData.sort((a, b) => {
                    const ra = (a.children[colIndex]?.innerText || "").trim();
                    const rb = (b.children[colIndex]?.innerText || "").trim();

                    const da = toDateKey(ra);
                    const db = toDateKey(rb);

                    if (da && db) return (da - db) * (asc ? 1 : -1);

                    const na = toNumber(ra);
                    const nb = toNumber(rb);
                    if (na !== null && nb !== null) return (na - nb) * (asc ? 1 : -1);

                    return ra.localeCompare(rb) * (asc ? 1 : -1);
                });

                allData.forEach(tr => table.tBodies[0].appendChild(tr));
            });

            table.dataset.sortBound = "1";
        }
    });
}

// ==========================================================
// 🧩 OnLoad principal
// ==========================================================
document.addEventListener("DOMContentLoaded", () => {
    ocultarCapasPrincipales();
    inicializarOrdenamiento();
    mueveReloj();
});