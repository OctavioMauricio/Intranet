// ==========================================================
// Cobranza Comercial – Eventos Estado y Comentario
// /kickoff/cm_cobranza_comercial.js
// Autor: Mauricio Araneda
// Fecha: 2025-11-20
// Versión: Dinámico + UTF-8 + Sin Bordes
// Codificación: UTF-8 sin BOM
// ==========================================================


// ------------------------------
// Evento para Estado Sweet
// ------------------------------
function bindEventosEstado() {

    document.querySelectorAll('.estado-sweet').forEach(sel => {

        sel.addEventListener('change', function () {

            const cell  = this.closest('td.estado-sweet-cell');
            const rut   = cell.dataset.rut;
            const valor = this.value;
            const icono = cell.querySelector('.estado-icono');

            fetch('update_estado_sweet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `rut=${encodeURIComponent(rut)}&estado=${encodeURIComponent(valor)}`
            })
            .then(r => r.json())
            .then(d => {
                icono.textContent = d.success ? "✅" : "❌";
                icono.style.color = d.success ? "limegreen" : "red";
            })
            .catch(err => {
                icono.textContent = "❌";
                icono.style.color = "red";
                console.error("Error actualizando estado:", err);
            });
        });
    });
}



// ------------------------------
// Evento para Comentario
// ------------------------------
function bindEventosComentario() {

    document.querySelectorAll('.comentario-input').forEach(inp => {

        inp.addEventListener('change', function () {

            const cell  = this.closest('td.comentario-cell');
            const rut   = cell.dataset.rut;
            const valor = this.value;
            const icono = cell.querySelector('.comentario-icono');

            fetch('update_comentario_sweet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `rut=${encodeURIComponent(rut)}&comentario=${encodeURIComponent(valor)}`
            })
            .then(r => r.json())
            .then(d => {
                icono.textContent = d.success ? "✅" : "❌";
                icono.style.color = d.success ? "limegreen" : "red";
            })
            .catch(err => {
                icono.textContent = "❌";
                icono.style.color = "red";
                console.error("Error actualizando comentario:", err);
            });
        });
    });
}



// ------------------------------
// Ejecutar enlaces al cargar
// ------------------------------
bindEventosEstado();
bindEventosComentario();

// =======================================================
// ORDENAMIENTO DEFINITIVO (SELECT + INPUT + TEXTO + NÚMEROS)
// =======================================================

document.querySelectorAll("th.sortable").forEach(th => {

    th.addEventListener("click", function () {

        const table = document.getElementById("cobranza");
        const colName = th.dataset.col;

        const rows = Array.from(table.querySelectorAll("tr")).slice(1);

        // Toggle ASC/DESC
        const asc = th.classList.toggle("asc");

        rows.sort((a, b) => {

            let va = "";
            let vb = "";

            switch (colName) {

                case "comentario":
                    va = a.querySelector(".comentario-input")?.value.toLowerCase() || "";
                    vb = b.querySelector(".comentario-input")?.value.toLowerCase() || "";
                    break;

                case "estado":
                    va = a.querySelector(".estado-sweet")?.value.toLowerCase() || "";
                    vb = b.querySelector(".estado-sweet")?.value.toLowerCase() || "";
                    break;

                case "monto":
                    va = parseInt(a.querySelector("td:nth-child(5)").innerText.replace(/\D/g, "")) || 0;
                    vb = parseInt(b.querySelector("td:nth-child(5)").innerText.replace(/\D/g, "")) || 0;
                    break;

                case "num":
                    va = parseInt(a.querySelector("td:nth-child(1)").innerText) || 0;
                    vb = parseInt(b.querySelector("td:nth-child(1)").innerText) || 0;
                    break;

                case "docs":
                    va = parseInt(a.querySelector("td:nth-child(6)").innerText) || 0;
                    vb = parseInt(b.querySelector("td:nth-child(6)").innerText) || 0;
                    break;

                case "dias_venc":
                    va = parseInt(a.querySelector("td:nth-child(7)").innerText) || 0;
                    vb = parseInt(b.querySelector("td:nth-child(7)").innerText) || 0;
                    break;

                case "ejecutivo":
                    va = a.querySelector("td:nth-child(8)").innerText.toLowerCase();
                    vb = b.querySelector("td:nth-child(8)").innerText.toLowerCase();
                    break;

                case "fecha":
                    va = a.querySelector("td:nth-child(9)").innerText.toLowerCase();
                    vb = b.querySelector("td:nth-child(9)").innerText.toLowerCase();
                    break;

                case "dias":
                    va = parseInt(a.querySelector("td:nth-child(10)").innerText) || 0;
                    vb = parseInt(b.querySelector("td:nth-child(10)").innerText) || 0;
                    break;

                default:
                    va = a.cells[th.cellIndex].innerText.toLowerCase();
                    vb = b.cells[th.cellIndex].innerText.toLowerCase();
            }

            return asc
                ? va.localeCompare(vb, "es", { numeric: true })
                : vb.localeCompare(va, "es", { numeric: true });
        });

        // Reinsertar filas
        rows.forEach(r => table.appendChild(r));

        // Actualizar flechas
        document.querySelectorAll(".order-indicator").forEach(i => i.innerHTML = "");
        th.querySelector(".order-indicator").innerHTML = asc ? "↑" : "↓";
    });
});