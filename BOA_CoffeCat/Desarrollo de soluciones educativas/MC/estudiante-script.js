function cerrarSesion() {
    localStorage.removeItem('usuario');
    window.location.href = 'index.html';
}

document.addEventListener('DOMContentLoaded', function() {
    const tablaNotasEstudiante = document.getElementById('tablaNotasEstudiante').getElementsByTagName('tbody')[0];
    const promedioElemento = document.getElementById('promedio');
    const nombreElemento = document.getElementById('nombreEstudiante');

    const usuarioActual = localStorage.getItem('usuario'); // Este es el ID, ej: "123456"
    const registros = JSON.parse(localStorage.getItem('notasEstudiantes')) || {};
    const datosEstudiante = registros[usuarioActual];

    if (!datosEstudiante || !datosEstudiante.notas) {
        promedioElemento.textContent = `No hay notas registradas.`;
        return;
    }

    nombreElemento.textContent = `Estudiante: ${datosEstudiante.nombre}`;

    let suma = 0;
    let cantidad = 0;

    for (let curso in datosEstudiante.notas) {
        const nota = datosEstudiante.notas[curso];
        const fila = tablaNotasEstudiante.insertRow();
        fila.insertCell(0).textContent = curso;
        fila.insertCell(1).textContent = nota;
        suma += nota;
        cantidad++;
    }

    const promedio = (suma / cantidad).toFixed(2);
    promedioElemento.textContent = `Promedio: ${promedio}`;
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
