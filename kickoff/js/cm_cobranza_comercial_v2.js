// ==========================================================
// Cobranza Comercial – Eventos Estado y Comentario
// /kickoff/js/cm_cobranza_comercial.js
// Autor: Mauricio Araneda
// Fecha: 2025-11-20
// Versión: Dinámico + UTF-8 + Sin Bordes
// Codificación: UTF-8 sin BOM
// ==========================================================

console.log("cm_cobranza_comercial.js v8 loaded");

// ------------------------------
// Evento para Estado Sweet
// ------------------------------
function bindEventosEstado() {
    // Buscar todos los selects en celdas con clase estado-sweet-cell
    const cells = document.querySelectorAll('td.estado-sweet-cell');
    
    cells.forEach(cell => {
        const select = cell.querySelector('select');
        if (!select) return;
        
        // Evitar duplicar eventos
        if (select.dataset.bound) return;
        select.dataset.bound = "true";
        
        select.addEventListener('change', function () {
            const rut = cell.dataset.rut;
            const valor = this.value;
            const icono = cell.querySelector('.estado-icono');

            fetch('update_estado_sweet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `rut=${encodeURIComponent(rut)}&estado=${encodeURIComponent(valor)}`
            })
                .then(r => r.json())
                .then(d => {
                    if (icono) {
                        icono.textContent = d.success ? "✅" : "❌";
                        icono.style.color = d.success ? "limegreen" : "red";
                    }
                })
                .catch(err => {
                    if (icono) {
                        icono.textContent = "❌";
                        icono.style.color = "red";
                    }
                    console.error("Error actualizando estado:", err);
                });
        });
    });
}


// ------------------------------
// Evento para Comentario
// ------------------------------
function bindEventosComentario() {
    // Buscar todos los inputs en celdas con clase comentario-cell
    const cells = document.querySelectorAll('td.comentario-cell');
    
    cells.forEach(cell => {
        const input = cell.querySelector('input');
        if (!input) return;
        
        // Evitar duplicar eventos
        if (input.dataset.bound) return;
        input.dataset.bound = "true";
        
        input.addEventListener('change', function () {
            const rut = cell.dataset.rut;
            const valor = this.value;
            const icono = cell.querySelector('.comentario-icono');

            fetch('update_comentario_sweet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `rut=${encodeURIComponent(rut)}&comentario=${encodeURIComponent(valor)}`
            })
                .then(r => r.json())
                .then(d => {
                    if (icono) {
                        icono.textContent = d.success ? "✅" : "❌";
                        icono.style.color = d.success ? "limegreen" : "red";
                    }
                })
                .catch(err => {
                    if (icono) {
                        icono.textContent = "❌";
                        icono.style.color = "red";
                    }
                    console.error("Error actualizando comentario:", err);
                });
        });
    });
}


// =======================================================
// ORDENAMIENTO DEFINITIVO (SELECT + INPUT + TEXTO + NÚMEROS)
// =======================================================

document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("cobranza");
    if (!table) return;

    const headers = table.querySelectorAll("th.sortable");

    headers.forEach(th => {
        th.addEventListener("click", function () {
            const colType = th.dataset.col;
            
            // Calcular el índice correcto basado en el tipo de columna
            let colIndex;
            switch(colType) {
                case "razon": colIndex = 1; break;
                case "estado": colIndex = 2; break;
                case "comentario": colIndex = 3; break;
                case "monto": colIndex = 4; break;
                case "docs": colIndex = 5; break;
                case "dias_venc": colIndex = 6; break;
                case "ejecutivo": colIndex = 7; break;
                case "fecha": colIndex = 8; break;
                case "dias": colIndex = 9; break;
                default: colIndex = th.cellIndex;
            }

            // Determinar orden actual
            const isAsc = !th.classList.contains("asc");

            // Limpiar clases e indicadores de otros headers
            headers.forEach(h => {
                h.classList.remove("asc", "desc");
                const indicator = h.querySelector(".order-indicator");
                if (indicator) indicator.textContent = "";
            });

            // Aplicar clase e indicador al header actual
            th.classList.toggle("asc", isAsc);
            th.classList.toggle("desc", !isAsc);
            const indicator = th.querySelector(".order-indicator");
            if (indicator) indicator.textContent = isAsc ? "↑" : "↓";

            // Obtener filas (excluyendo encabezados)
            const tbody = table.querySelector("tbody") || table;
            const rows = Array.from(tbody.querySelectorAll("tr:not(.subtitulo)"));

            // Ordenar filas
            rows.sort((rowA, rowB) => {
                const valA = getCellValue(rowA, colIndex, colType);
                const valB = getCellValue(rowB, colIndex, colType);
                return compareValues(valA, valB, isAsc);
            });

            // Reinsertar filas ordenadas
            rows.forEach(row => tbody.appendChild(row));
            
            console.log(`Ordenado por ${colType} (${isAsc ? 'ASC' : 'DESC'}), columna índice ${colIndex}`);
        });
    });
    
    // Ejecutar enlaces después de que el DOM esté listo
    bindEventosEstado();
    bindEventosComentario();
});

/**
 * Obtiene el valor de una celda según el tipo de columna
 */
function getCellValue(row, index, type) {
    const cell = row.cells[index];
    if (!cell) {
        console.warn(`Celda ${index} no encontrada en fila`);
        return "";
    }

    switch (type) {
        case "estado":
            // Buscar select dentro de la celda (SIN data-campo)
            const select = cell.querySelector("select");
            if (select && select.selectedIndex >= 0) {
                const selectedOption = select.options[select.selectedIndex];
                const text = selectedOption ? selectedOption.text.trim().toLowerCase() : "";
                return text;
            }
            return "";

        case "comentario":
            // Buscar input dentro de la celda (SIN data-campo)
            const input = cell.querySelector("input");
            if (input) {
                const value = input.value.trim().toLowerCase();
                return value;
            }
            return "";

        case "monto":
        case "docs":
        case "dias_venc":
        case "dias":
            // Obtener texto y limpiar
            let text = cell.innerText.trim();
            // Remover símbolo de peso y espacios
            text = text.replace(/[$\s]/g, "");
            // Remover puntos (separadores de miles)
            text = text.replace(/\./g, "");
            // Mantener solo números y signo negativo
            text = text.replace(/[^0-9-]/g, "");
            
            const numValue = text === "" || text === "-" ? 0 : parseInt(text, 10);
            return numValue;

        case "razon":
        case "ejecutivo":
        case "fecha":
        default:
            // Texto normal - tomar el innerText completo
            return cell.innerText.trim().toLowerCase();
    }
}

/**
 * Compara dos valores para el ordenamiento
 */
function compareValues(a, b, isAsc) {
    // Si ambos son números
    if (typeof a === "number" && typeof b === "number") {
        return isAsc ? a - b : b - a;
    }

    // Manejo de valores vacíos
    if (a === "" && b === "") return 0;
    if (a === "") return isAsc ? 1 : -1;
    if (b === "") return isAsc ? -1 : 1;

    // Comparación de cadenas
    return isAsc
        ? a.toString().localeCompare(b.toString(), "es", { numeric: true })
        : b.toString().localeCompare(a.toString(), "es", { numeric: true });
}