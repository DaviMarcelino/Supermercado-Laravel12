// resources/js/app.js

import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Funciones personalizadas para el carrito
document.addEventListener('DOMContentLoaded', () => {
    // Mostrar spinner
function mostrarSpinner() {
    const spinner = document.getElementById('spinner-global');
    if (spinner) {
        spinner.classList.remove('hidden');
    }
}

// Ocultar spinner
function ocultarSpinner() {
    const spinner = document.getElementById('spinner-global');
    if (spinner) {
        spinner.classList.add('hidden');
    }
}

window.agregarAlCarrito = function(id) {
    mostrarSpinner(); // 👈 Mostrar antes de enviar la solicitud

    fetch(`/carrito/add/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        ocultarSpinner(); // 👈 Ocultar cuando responda

        if (data.message === 'Agregado') {
            if (document.getElementById('contador-carrito')) {
                document.getElementById('contador-carrito').textContent = data.total;
            }
            // Podrías aquí agregar un pequeño mensaje tipo "toast" si quieres aún más fancy
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        ocultarSpinner(); // 👈 Ocultar aunque haya error
        console.error(err);
        alert('No se pudo agregar al carrito');
    });
};

    
    window.abrirCarrito = function () {
        const modal = document.getElementById('modal-carrito');
        const contenido = document.getElementById('contenido-carrito');
        const spinner = document.getElementById('loading-spinner');
    
        if (!modal || !contenido || !spinner) {
            console.warn('⚠️ Elementos del carrito no encontrados.');
            return;
        }
    
        spinner.classList.remove('hidden');
        contenido.innerHTML = '';
    
        modal.classList.remove('hidden');
    
        fetch('/carrito', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Respuesta del servidor inválida');
            return res.text();
        })
        .then(html => {
            contenido.innerHTML = html;
            spinner.classList.add('hidden');
        })
        .catch(err => {
            contenido.innerHTML = '<p class="text-red-500 text-center">Error al cargar el carrito</p>';
            spinner.classList.add('hidden');
            console.error('⛔ Error al cargar carrito:', err);
        });
        
    };
    
    window.cerrarCarrito = function () {
        const modal = document.getElementById('modal-carrito');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    

    window.cambiarCantidad = function (event, id, cambio) {
        event.preventDefault();
    
        fetch(`/carrito/cambiar/${id}/${cambio}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Error al cambiar cantidad');
            return res.json();
        })
        .then(() => {
            abrirCarrito(); // Vuelve a cargar el contenido actualizado
        })
        .catch(err => {
            console.error('Error al cambiar cantidad:', err);
            alert('No se pudo actualizar la cantidad');
        });
    }
    
    window.eliminarDelCarrito = function (id) {
        fetch(`/carrito/remove/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(() => {
            abrirCarrito(); // recargar el modal
    
            // Después de recargar el carrito, actualizar el contador
            fetch('/carrito/contenido', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (document.getElementById('contador-carrito')) {
                    document.getElementById('contador-carrito').textContent = data.productos.length;
                }
            });
        })
        .catch(() => alert('Error al eliminar producto'));
    }
    window.confirmarVaciarCarrito = function () {
        const modal = document.getElementById('modal-confirmacion');
        if (modal) {
            modal.classList.remove('hidden'); // Mostrar el modal de confirmación
        }
    }
    
    window.cerrarModalConfirmacion = function () {
        const modal = document.getElementById('modal-confirmacion');
        if (modal) {
            modal.classList.add('hidden'); // Ocultar el modal si cancela
        }
    }
    
    // Cuando se haga clic en "Sí, vaciar"
    document.addEventListener('click', function (event) {
        if (event.target && event.target.id === 'confirmar-vaciar-btn') {
            fetch('/carrito/clear', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(() => {
                cerrarModalConfirmacion();
                abrirCarrito();
                const contador = document.getElementById('contador-carrito');
                if (contador) contador.textContent = '0';
            })
            .catch(() => alert('No se pudo vaciar el carrito'));
        }
    });
    
    
    
    
    
});
