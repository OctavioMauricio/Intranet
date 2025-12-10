// ========================================================
// Kickoff AJAX – cm_tareas_pendientes.js
// Guarda cada cambio vía AJAX con íconos  ✅ ❌
// ========================================================

console.log("cm_tareas_pendientes.js (AJAX + ICONOS) cargado");

// -----------------------------------------------
// Insertar una celda para mostrar el estado AJAX
// -----------------------------------------------
function prepararFila(fila) {

    // Evitar duplicar
    if (fila.querySelector(".estado-ajax")) return;

    const td = document.createElement("td");
    td.className = "estado-ajax";
    td.style.width = "22px";
    td.style.textAlign = "center";
    td.style.fontSize = "18px";
    td.style.color = "#666";

    // Insertar como primer td
    fila.insertBefore(td, fila.firstElementChild);
}

// -----------------------------------------------
// Mostrar resultado  ✅ o ❌
// -----------------------------------------------
function mostrarIconoOK(fila) {
    const celda = fila.querySelector(".estado-ajax");
    if (!celda) return;

    celda.textContent = " ✅";
    celda.style.color = "limegreen";

    fila.classList.add("tarea-ok");
    setTimeout(() => {
        fila.classList.remove("tarea-ok");
    }, 800);
}

function mostrarIconoError(fila) {
    const celda = fila.querySelector(".estado-ajax");
    if (!celda) return;

    celda.textContent = "❌";
    celda.style.color = "red";

    fila.classList.add("tarea-error");
    setTimeout(() => {
        fila.classList.remove("tarea-error");
    }, 1200);
}

// -----------------------------------------------
// AJAX: guardar fila
// -----------------------------------------------
function guardarFila(fila) {

    const id = fila.dataset.id;
    if (!id) return;

    let datos = new URLSearchParams();
    datos.append("id", id);

    fila.querySelectorAll("[data-campo]").forEach(c => {
        datos.append(c.dataset.campo, c.value);
    });

    fetch("ajax/update_tarea.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: datos
    })
        .then(r => r.json())
        .then(j => {
            if (j.success) {
                mostrarIconoOK(fila);
                if (j.mail_info) {
                    alert("📧 " + j.mail_info);
                }
            } else {
                mostrarIconoError(fila);
                console.error("Error al guardar:", j.error);
            }
        })
        .catch(err => {
            mostrarIconoError(fila);
            console.error("Error AJAX:", err);
            alert("Error AJAX: " + err); // <--- DEBUG VISTO POR EL USUARIO
        });
}

// -----------------------------------------------
// Inicialización
// -----------------------------------------------
// Inicialización inmediata (AJAX)
// No usamos DOMContentLoaded porque al cargar vía AJAX el evento ya pasó.

// Preparar filas y asignar eventos
document.querySelectorAll("tr[data-id]").forEach(fila => {

    prepararFila(fila); // añade columna de icono

    fila.querySelectorAll("[data-campo]").forEach(campo => {
        campo.addEventListener("change", () => {
            guardarFila(fila);
        });
    });

});
