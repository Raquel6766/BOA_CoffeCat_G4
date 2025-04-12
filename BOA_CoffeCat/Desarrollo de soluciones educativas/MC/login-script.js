document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const user = document.getElementById('username').value;
    const pass = document.getElementById('password').value;

    // Valida usuario
    if (user === 'admin' && pass === '1234') {
        localStorage.setItem('usuario', 'maestro');
        window.location.href = 'maestro.html';
    } else if (user === '123456' && pass === 'estudiante') {
        localStorage.setItem('usuario', '123456');
        window.location.href = 'estudiante.html';
    } else {
        alert('Credenciales inválidas');
    }
});
