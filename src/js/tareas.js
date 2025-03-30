(function() {
    // Boton para agregar tarea: 
    const nuevaTareaBoton = document.querySelector('#agregar-tarea');
    nuevaTareaBoton.addEventListener('click', mostrarFormulario);

    function mostrarFormulario() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class='formulario nueva-tarea'>
                <legend> Añade una nueva Tarea </legend>
                <div class='campo'>
                    <label for='tarea'>Tarea</label>
                    <input
                        type='text'
                        name='tarea'
                        id='tarea'
                        placeholder='Añadir Tarea'/>
                </div>
                <div class='opciones'>
                    <input
                        type='submit'
                        class='submit-nueva-tarea'
                        value='Añade Tarea'/>
                    <button type='button' class='cerrar-modal'>Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        modal.addEventListener('click', function(e) {
            e.preventDefault();
            if (e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                setTimeout(() => {
                    modal.remove();
                }, 500);
            } else if (e.target.classList.contains('submit-nueva-tarea')){
                submitFormularioNuevaTarea();
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea(){
        const tarea = document.querySelector('#tarea').value.trim();
        // Validacion: 
        if (tarea === '') {
            mostrarAlerta('El nombre de la tarea es obligatorio', 'error', document.querySelector('.formulario legend'));
            return;
        }

        agregarTarea(tarea);
    }

    // Muesta un mensaje en la interfaz:
    function mostrarAlerta(mensaje, tipo, referencia) {

        // Previene la creacion de multiples alertas:
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        // Eliminar la alerta despues de un tiempo: 
        setTimeout(() => {
            alerta.remove();
        }, 4000);
    }

    // Consultar servidor para agregar una nueva tarea:
    function agregarTarea() {
        
    };
})();



