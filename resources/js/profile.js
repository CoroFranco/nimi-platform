
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

