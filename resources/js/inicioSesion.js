document.addEventListener('DOMContentLoaded', function () {
    // Elementos del DOM
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const resetForm = document.getElementById('resetForm');
    const toggleRegister = document.getElementById('toggleRegister');
    const toggleReset = document.getElementById('toggleReset');
    const showAlertsDiv = document.getElementById('showAlerts')


    // Funciones auxiliares
    function createMessageContainer(type) {
        const container = document.createElement('div');
        const className = type === 'error'
        ? 'alert mt-10 bg-red-100 border border-red-400 text-red-700'
        : 'alert mt-10 bg-green-100 border border-green-400 text-green-700';
        container.className = `${className} px-4 py-3 rounded relative mb-4 hidden`;
        return container;
    }
    
    // Contenedores de mensajes
    const errorContainer = createMessageContainer('error');
    const successContainer = createMessageContainer('success');
    
    function showForm(formToShow) {
        [loginForm, registerForm, resetForm].forEach(form => {
            if (form) form.style.display = 'none';
        });
        if (formToShow) formToShow.style.display = 'block';
    }

    function displayMessages(container, messages) {
        container.innerHTML = '';
        Object.values(messages).forEach(message => {
            const messageElement = document.createElement('p');
            messageElement.textContent = message[0];
            container.appendChild(messageElement);
        });
        container.classList.remove('hidden');
        setTimeout(() => {
            container.classList.add('hidden');
        }, 3000);
    }

    function clearMessages() {
        errorContainer.classList.add('hidden');
        successContainer.classList.add('hidden');
    }

    // Event Listeners para cambiar entre formularios
    toggleRegister.addEventListener('click', () => {
        if (loginForm.style.display !== 'none') {
            showForm(registerForm);
            toggleRegister.textContent = '¿Ya tienes cuenta? Inicia sesión';
            toggleReset.style.display = 'none';
        } else {
            showForm(loginForm);
            toggleRegister.textContent = '¿No tienes cuenta? Regístrate';
            toggleReset.style.display = 'block';
        }
        clearMessages();
    });

    toggleReset.addEventListener('click', () => {
        if (resetForm.style.display === 'none') {
            showForm(resetForm);
            toggleReset.textContent = 'Volver al inicio de sesión';
            toggleRegister.style.display = 'none';
        } else {
            showForm(loginForm);
            toggleReset.textContent = '¿Olvidaste tu contraseña?';
            toggleRegister.style.display = 'block';
        }
        clearMessages();
    });

    // Manejo del formulario de registro
    registerForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
            .then(response => response.json())

            .then(data => {
                console.log(data)
                if (data.errors) {
                    displayMessages(errorContainer, data.errors);
                } else if (data.success) {
                    showForm(loginForm);
                    displayMessages(successContainer, { message: ['Cuenta creada correctamente. Por favor, inicia sesión.'] });
                    registerForm.reset();
                }
            })
            .catch(error => {
                displayMessages(errorContainer, { general: ['Hubo un problema al procesar tu solicitud. Por favor, inténtalo de nuevo.'] });
            });
    });

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
        .then(response => {
            return response.json().then(data => {
                if (response.ok) {
                    if (data.success) {
                        window.location.href = data.redirect;
                    }
                } else {
                    if (data.errors) {
                        displayMessages(errorContainer, data.errors);
                    }
                }
            });
        })
        .catch(error => {
            displayMessages(errorContainer, { general: ['Hubo un problema al iniciar sesión. Por favor, inténtalo de nuevo.'] });
        });
    });

    // Función global para cambiar entre formularios
    window.toggleForm = function (formId) {
        showForm(document.getElementById(formId));
        clearMessages();
    };

    // Agregar contenedores de mensajes a los formularios
    showAlertsDiv.appendChild(errorContainer);
    showAlertsDiv.appendChild(successContainer);



});