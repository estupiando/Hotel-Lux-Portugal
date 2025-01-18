// script.js

// Validação do formulário de cadastro (registo.php)
document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault();  // Previne o envio do formulário

    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const num_identificacao = document.getElementById('num_identificacao').value;
    const contacto = document.getElementById('contacto').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;  // Campo de confirmação de senha

    // Validações
    if (nome.trim() === '') {
        alert('O nome não pode estar vazio.');
        return;
    }

    if (!validateEmail(email)) {
        alert('Por favor, insira um email válido.');
        return;
    }

    if (num_identificacao.trim() === '') {
        alert('O número de identificação não pode estar vazio.');
        return;
    }

    if (contacto.trim() === '') {
        alert('O contacto não pode estar vazio.');
        return;
    }

    if (password.length < 6) {
        alert('A senha deve ter pelo menos 6 caracteres.');
        return;
    }

    // Verifica se as senhas coincidem
    if (password !== confirmPassword) {
        alert('A senha e a confirmação de senha devem ser iguais.');
        return;
    }

    this.submit();  // Envia o formulário
});

// Função de validação do email
function validateEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return regex.test(email);
}