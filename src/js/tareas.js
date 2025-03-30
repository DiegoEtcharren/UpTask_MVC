(function() {
    // Obtener tareas y desplegar: 
    obtenerTareas();
    let tareas = [];

    // Boton para agregar tarea: 
    const nuevaTareaBoton = document.querySelector('#agregar-tarea');
    nuevaTareaBoton.addEventListener('click', mostrarFormulario);

    async function obtenerTareas() {
        try {
            const urlProyecto = obtenerProyecto();
            const url = `http://localhost:3000/api/tareas?url=${urlProyecto}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;
            mostrarTareas();

        } catch (error) {
            console.log(error);
        }

    };

    function mostrarTareas() {
        limpiarTareas();
        if (tareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay Tareas';
            textoNoTareas.classList.add('no-tareas');
            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0 : 'Pendiente',
            1 : 'Completa'
        }

        tareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            // Botones: 
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function () {
                confirmarEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        });
    }

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
    async function agregarTarea(tarea) {
        // Construir la peticion: 
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method : 'POST',
                body: datos,
            });

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));

            if (resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1000);

                // Agregar la tarea al global de tareas:
                const tareaObj = {
                    id: String(resultado.id),
                    estado: '0',
                    nombre: tarea,
                    proyectoId: resultado.proyectoId
                };

                tareas = [...tareas, tareaObj];
                mostrarTareas();


            }

        } catch (error) {
            console.log(error);
        }

    };

    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === '1' ? '0' : '1';
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        const {estado, id , nombre, proyectoId} = tarea;
        const datos = new FormData();

        datos.append('id', id);
        datos.append('estado', estado);
        datos.append('nombre', nombre);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            
            const resultado = await respuesta.json();

            if (resultado.respuesta.tipo === 'exito') {
                mostrarAlerta(
                    resultado.respuesta.mensaje, 
                    resultado.respuesta.tipo, 
                    document.querySelector('.contenedor-nueva-tarea')
                );

                tareas = tareas.map(tareaMemoria => {
                    if (tareaMemoria.id == id) {
                        tareaMemoria.estado = estado;
                    }
                    return tareaMemoria;
                })

                mostrarTareas();
            };


        } catch (error) {
            console.log(error);
        }

    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: "Deseas Eliminar?",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: 'No'
          }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            } 
          });
    }

    async function eliminarTarea(tarea) {

        const datos = new FormData();
        try {

        } catch (error) {

        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.url;
    }

    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }   
    }
})();



