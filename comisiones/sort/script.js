// Función para exportar a Excel (sin cambios)
function exportToExcel(tableId){
    let tableData = document.getElementById(tableId).outerHTML;
    tableData = tableData.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
    tableData = tableData.replace(/<input[^>]*>|<\/input>/gi, ""); //remove input params

    let a = document.createElement('a');
    a.href = `data:application/vnd.ms-excel, ${encodeURIComponent(tableData)}`
    // Cambié el nombre del archivo para que sea más consistente con el informe
    a.download = 'Comisiones_' + getRandomNumbers() + '.xls'
    a.click()
}

// Función auxiliar para el nombre de archivo (sin cambios)
function getRandomNumbers() {
    let dateObj = new Date()
    let dateTime = `${dateObj.getHours()}${dateObj.getMinutes()}${dateObj.getSeconds()}`

    return `${dateTime}${Math.floor((Math.random().toFixed(2)*100))}`
}        

// --- NUEVO CÓDIGO DE ORDENAMIENTO ---
// Esto reemplaza completamente a la función antigua sortTable()

 $(document).ready(function() {
    // Asignar el evento click a todos los encabezados ordenables
    $('.sortable-header').on('click', function() {
        var columnName = $(this).data('column');
        var currentSort = $("#sort").val();
        var newSort = (currentSort === 'asc') ? 'desc' : 'asc';

        // 1. Actualizar indicadores visuales (flechas ▲ ▼)
        $('.sort-indicator').remove(); // Quitar indicadores anteriores
        var arrow = (newSort === 'asc') ? ' ▲' : ' ▼';
        $(this).append('<span class="sort-indicator" style="color: #ccc; font-size: 0.8em;">' + arrow + '</span>');

        // 2. Actualizar el campo oculto con el nuevo orden
        $("#sort").val(newSort);

        // 3. Llamada AJAX para ordenar
        $.ajax({
            url: 'fetch_details.php',
            type: 'post',
            data: {
                columnName: columnName,
                sort: newSort
            },
            success: function(response) {
                // Actualizar la tabla con los nuevos datos ordenados
                // Es más seguro reemplazar solo el cuerpo (tbody) de la tabla
                $("#empTable tbody").html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error en la ordenación:", error);
                alert("Ocurrió un error al ordenar los datos. Revisa la consola para más detalles.");
            }
        });
    });
});