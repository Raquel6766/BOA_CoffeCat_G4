function cerrarSesion() {
    localStorage.removeItem('usuario');
    window.location.href = 'index.html';
}

document.getElementById('formularioNotas').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nombre = document.getElementById('nombreEstudiante').value.trim();
    const curso = document.getElementById('curso').value;
    const nota = parseFloat(document.getElementById('nota').value);
    const estudianteId = "123456"; // ID fijo por ahora

    if (curso && nombre && !isNaN(nota)) {
        // 1. Mostrar en tabla visual
        const tablaNotas = document.getElementById('tablaNotas').getElementsByTagName('tbody')[0];
        let filaEstudiante = Array.from(tablaNotas.rows).find(fila => fila.cells[0].textContent === nombre);

        if (!filaEstudiante) {
            filaEstudiante = tablaNotas.insertRow();
            filaEstudiante.insertCell(0).textContent = nombre;
            filaEstudiante.insertCell(1).textContent = '';
            filaEstudiante.insertCell(2).textContent = '';
            filaEstudiante.insertCell(3).textContent = '';
            filaEstudiante.insertCell(4).textContent = '';
        }

        const cursoColumna = {
            'Ciencias': 1,
            'Matemáticas': 2,
            'Lenguaje': 3
        };

        filaEstudiante.cells[cursoColumna[curso]].textContent = nota;

        // Calcular promedio
        let suma = 0, cantidad = 0;
        for (let i = 1; i <= 3; i++) {
            const val = parseFloat(filaEstudiante.cells[i].textContent);
            if (!isNaN(val)) {
                suma += val;
                cantidad++;
            }
        }
        const promedio = (suma / cantidad).toFixed(2);
        filaEstudiante.cells[4].textContent = promedio;

        // 2. Guardar en localStorage bajo el ID
        let registros = JSON.parse(localStorage.getItem('notasEstudiantes')) || {};
        if (!registros[estudianteId]) {
            registros[estudianteId] = {
                nombre: nombre,
                notas: {}
            };
        } else {
            registros[estudianteId].nombre = nombre; // En caso de cambio de nombre
        }

        registros[estudianteId].notas[curso] = nota;
        localStorage.setItem('notasEstudiantes', JSON.stringify(registros));
    } else {
        alert("Por favor, complete todos los campos.");
    }

    // Limpiar formulario
    document.getElementById('nombreEstudiante').value = '';
    document.getElementById('curso').value = '';
    document.getElementById('nota').value = '';
});
function toggleMenu() {
    const popup = document.getElementById('popupMenu');
    popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
}


window.addEventListener('click', function(e) {
    const popup = document.getElementById('popupMenu');
    const icon = document.querySelector('.menu-icon');
    if (!popup.contains(e.target) && !icon.contains(e.target)) {
        popup.style.display = 'none';
    }
});

