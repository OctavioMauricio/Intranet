// ==========================================================
// duemint/js/tabla.js
// Autor: Mauricio Araneda + ChatGPT
// Fecha: 2025-11-15
// ==========================================================
(function() {

    const table = document.getElementById('tablaDuemint');
    if (!table) return;

    const thead = table.tHead;
    const tbody = table.tBodies[0];
    if (!thead || !tbody) return;

    // === Tipo de datos por columna ===
    const typeMap = [
        'number',   // 0 #
        'text',     // 1 RUT
        'text',     // 2 Razón Social
        'select',   // 3 Estado Sweet
        'text',     // 4 Comentario Estado
        'text',     // 5 Tipo
        'number',   // 6 Docs
        'currency', // 7 Monto
        'date',     // 8 Fecha Ref
        'number',   // 9 Días
        'text'      // 10 Ver Duemint
    ];

    const headerRow = thead.querySelector('tr:first-child');
    const headers = Array.from(headerRow.querySelectorAll('th'));
    const sortState = new Map();

    const parseNumber = (v) => parseFloat(String(v).replace(/[^\d.-]/g, '')) || 0;

    const parseDateDMY = (v) => {
        if (!v) return null;
        const m = v.match(/^(\d{2})-(\d{2})-(\d{4})$/);
        if (!m) return null;
        return new Date(`${m[3]}-${m[2]}-${m[1]}`);
    };

    const getCellValue = (td, colIndex) => {
        const t = typeMap[colIndex] || 'text';

        if (t === 'select') {
            const s = td.querySelector('select.estado-sweet');
            return s ? s.value.trim().toLowerCase() : td.textContent.trim().toLowerCase();
        }

        const ds = td.getAttribute('data-sort');
        let raw = ds && ds !== '' ? ds : td.textContent.trim();

        switch (t) {
            case 'number':
            case 'currency': return parseNumber(raw);
            case 'date': return parseDateDMY(raw)?.getTime() || 0;
            default: return raw.toLowerCase();
        }
    };

    // ==========================================================
    // ORDENAMIENTO
    // ==========================================================
    headers.forEach((th, i) => {
        th.style.cursor = 'pointer';
        th.addEventListener('click', () => {

            const dir = sortState.get(i) === 'asc' ? 'desc' : 'asc';
            sortState.set(i, dir);

            headers.forEach(h => h.classList.remove('asc', 'active'));
            th.classList.add('active');
            if (dir === 'asc') th.classList.add('asc');

            const rows = Array.from(tbody.querySelectorAll('tr'))
                .sort((a, b) => {
                    const v1 = getCellValue(a.children[i], i);
                    const v2 = getCellValue(b.children[i], i);
                    if (v1 < v2) return dir === 'asc' ? -1 : 1;
                    if (v1 > v2) return dir === 'asc' ? 1 : -1;
                    return 0;
                });

            tbody.append(...rows);
        });
    });

    // ==========================================================
    // FILTROS
    // ==========================================================
    const filterRow = thead.querySelector('tr.filters');
    if (filterRow) {
        const inputs = filterRow.querySelectorAll('.filter-input');

        const applyFilters = () => {
            tbody.querySelectorAll('tr').forEach(row => {
                let visible = true;

                inputs.forEach(inp => {
                    if (!visible) return;

                    const col = parseInt(inp.dataset.col, 10);
                    const type = inp.dataset.type;
                    const val = inp.value.trim();

                    if (!val) return;

                    const cellVal = getCellValue(row.children[col], col);

                    // === TEXTO ===
                    if (type === 'text') {
                        visible = String(cellVal).includes(val.toLowerCase());
                        return;
                    }

                    // === NÚMEROS / DÍAS / MONTO ===
                    if (type === 'number' || type === 'currency') {
                        visible = filtrarNumero(cellVal, val);
                        return;
                    }

                    // === FECHAS ===
                    if (type === 'date') {
                        const expr = val.replace(/\s+/g, "");
                        if (expr.includes("-")) {
                            const [d1, d2] = expr.split("-").map(parseDateDMY);
                            if (d1 && d2) {
                                visible = cellVal >= d1.getTime() && cellVal <= d2.getTime();
                            }
                        }
                        return;
                    }
                });

                row.style.display = visible ? '' : 'none';
            });
        };

        // === Filtro de números corregido ===
        function filtrarNumero(numVal, input) {
            const expr = input.trim().replace(/\s+/g, "");

            if (isNaN(Number(numVal))) return false;

            // >60
            if (/^>\d+$/.test(expr)) return Number(numVal) > Number(expr.substring(1));

            // <60
            if (/^<\d+$/.test(expr)) return Number(numVal) < Number(expr.substring(1));

            // >=60
            if (/^>=\d+$/.test(expr)) return Number(numVal) >= Number(expr.substring(2));

            // <=60
            if (/^<=\d+$/.test(expr)) return Number(numVal) <= Number(expr.substring(2));

            // 30-60
            if (/^\d+-\d+$/.test(expr)) {
                const [a, b] = expr.split('-').map(Number);
                const min = Math.min(a, b);
                const max = Math.max(a, b);
                return numVal >= min && numVal <= max;
            }

            // exacto
            if (/^\d+$/.test(expr)) return Number(numVal) === Number(expr);

            return true;
        }

        inputs.forEach(i => {
            i.addEventListener('input', applyFilters);
            i.addEventListener('change', applyFilters);
        });

        // LIMPIAR FILTROS
        document.getElementById('btnLimpiarFiltros')?.addEventListener('click', () => {
            inputs.forEach(i => i.value = '');
            applyFilters();
        });

    } // fin filtros

    // ==========================================================
    // ACTUALIZAR ESTADO SWEET
    // ==========================================================
    document.querySelectorAll('.estado-sweet').forEach(sel => {
        if (sel.dataset.bound === '1') return;
        sel.dataset.bound = '1';

        sel.addEventListener('change', function() {
            const fila = this.closest('tr');
            const rut = fila.children[1]?.innerText.trim();
            const nuevoEstado = this.value;

            let icono = this.parentNode.querySelector('.estado-icono');
            if (!icono) {
                icono = document.createElement('span');
                icono.className = 'estado-icono';
                icono.style.marginLeft = '6px';
                icono.style.fontSize = '16px';
                this.parentNode.appendChild(icono);
            }

            fetch('update_estado_sweet.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `rut=${encodeURIComponent(rut)}&estado=${encodeURIComponent(nuevoEstado)}`
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    icono.textContent = '✅';
                    icono.style.color = '#4CAF50';
                    this.blur(); // quitar foco
                } else {
                    icono.textContent = '❌';
                    icono.style.color = '#E74C3C';
                    this.blur();
                }
            })
            .catch(() => {
                icono.textContent = '❌';
                icono.style.color = '#E74C3C';
                this.blur();
            });

        });
    });

    // ==========================================================
    // ACTUALIZAR COMENTARIO ESTADO
    // ==========================================================
    document.querySelectorAll('.comentario-estado').forEach(inp => {
        if (inp.dataset.bound === '1') return;
        inp.dataset.bound = '1';

        inp.addEventListener('keydown', function(e){
            if (e.key === "Enter") {
                e.preventDefault();
                this.blur(); // dispara el change
            }
        });

        inp.addEventListener('change', function() {

            const rut = this.dataset.rut;
            const nuevoComentario = this.value.trim();

            let icono = this.parentNode.querySelector('.comentario-icono');
            if (!icono) {
                icono = document.createElement('span');
                icono.className = 'comentario-icono';
                icono.style.marginLeft = '6px';
                icono.style.fontSize = '16px';
                this.parentNode.appendChild(icono);
            }

            fetch('update_comentario_estado.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `rut=${encodeURIComponent(rut)}&comentario=${encodeURIComponent(nuevoComentario)}`
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    icono.textContent = '✅';
                    icono.style.color = '#4CAF50';
                } else {
                    icono.textContent = '❌';
                    icono.style.color = '#E74C3C';
                }
                this.blur();
            })
            .catch(() => {
                icono.textContent = '❌';
                icono.style.color = '#E74C3C';
                this.blur();
            });
        });
    });


})();
