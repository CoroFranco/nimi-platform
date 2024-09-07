const updateForm = document.querySelector('#updateForm');
const newPassword = document.querySelector('#newPassword');
const newPasswordBtn = document.querySelector('#newPasswordBtn');
const modal = document.getElementById('confirmModal');
const openModalBtn = document.getElementById('open-modal');
const cancelBtn = document.getElementById('cancelDelete');
const confirmBtn = document.getElementById('confirmDelete');




document.addEventListener('DOMContentLoaded', (event) => {
    // Busca el elemento con la clase 'alert'
    let alert = document.querySelector('.alert');

    // Si existe el elemento, configura un temporizador para ocultarlo
    if (alert) {
        setTimeout(function () {
            alert.classList.add('hidden')
        }, 3000); // Desaparece después de 3 segundos (3000 ms)
    }
});

openModalBtn.addEventListener('click', () => {
    modal.classList.remove('hidden');
});

cancelBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
});


newPasswordBtn.addEventListener('click', function (e) {
    e.preventDefault();
    newPassword.style.display = 'block';
    updateForm.style.display = 'none';
})

// Funcionalidad para las pestañas
const tabs = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.dataset.tab;
        tabs.forEach(t => t.classList.remove('text-[var(--hover-color)]', 'border-[var(--hover-color)]'));
        tab.classList.add('text-[var(--hover-color)]', 'border-[var(--hover-color)]');
        tabContents.forEach(content => {
            content.classList.add('hidden');
            if (content.id === target) {
                content.classList.remove('hidden');
            }
        });
    });
});

// Funcionalidad para abrir y cerrar el modal de edición de perfil
const editProfileBtn = document.getElementById('edit-profile-btn');
const editProfileModal = document.getElementById('edit-profile-modal');
const cancelEditBtn = document.getElementById('cancel-edit');

editProfileBtn.addEventListener('click', () => {
    editProfileModal.classList.remove('hidden');
});

cancelEditBtn.addEventListener('click', () => {
    editProfileModal.classList.add('hidden');
    newPassword.style.display = 'none';
    updateForm.style.display = 'block';
});

// Cerrar el modal si se hace clic fuera de él
editProfileModal.addEventListener('click', (e) => {
    if (e.target === editProfileModal) {
        editProfileModal.classList.add('hidden');
        newPassword.style.display = 'none';
        updateForm.style.display = 'block';
    }
});

//Funcionalidad para cambiar la foto de perfil
const profilePhotoInput = document.getElementById('profile-photo');
profilePhotoInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.querySelector('.w-32.h-32').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

