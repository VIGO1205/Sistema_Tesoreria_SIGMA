document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        // Basic validation
        if (!username || !password) {
            alert('Por favor, preencha todos os campos');
            return;
        }
        
        // Here you would normally send the data to a server
        // For demonstration purposes, we'll just log to console
        console.log('Tentativa de login:', { username });
        
        // Simulate a login process
        simulateLogin(username, password);
    });
    
    // Function to simulate login process
    function simulateLogin(username, password) {
        const loginButton = document.querySelector('.btn-login');
        loginButton.textContent = 'Processando...';
        loginButton.disabled = true;
        
        // Simulate server request with timeout
        setTimeout(() => {
            // This is where you would process the server response
            // For demo, we'll just simulate a successful login
            alert('Login bem-sucedido!');
            loginButton.textContent = 'Entrar';
            loginButton.disabled = false;
        }, 1500);
    }
    
    // Handle "forgot password" click
    const forgotPasswordLink = document.querySelector('.forgot-password a');
    forgotPasswordLink.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Recurso de recuperação de senha em desenvolvimento.');
    });
});