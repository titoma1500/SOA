<div style="background-color: white; padding: 20px; color: black;">
    <h2>Gestión de Estudiantes</h2>
    
    <div style="margin-bottom: 20px;">
        <button id="btnNuevoEstudiante" type="button" style="background-color: green; color: white; padding: 10px 15px; border: none; cursor: pointer; margin-right: 10px;">
            Nuevo Estudiante
        </button>
        <button id="btnBuscar" type="button" style="background-color: blue; color: white; padding: 10px 15px; border: none; cursor: pointer;">
            Buscar por Cédula
        </button>
    </div>

    <div id="busquedaContainer" style="display: none; margin-bottom: 20px; padding: 15px; background-color: #f0f0f0; border: 1px solid #ccc;">
        <label for="txtBuscarCedula">Ingrese cédula a buscar:</label>
        <input type="text" id="txtBuscarCedula" style="padding: 5px; margin: 0 10px; width: 200px;">
        <button id="btnEjecutarBusqueda" type="button" style="background-color: blue; color: white; padding: 5px 15px; border: none; cursor: pointer;">
            Buscar
        </button>
        <button id="btnCancelarBusqueda" type="button" style="background-color: red; color: white; padding: 5px 15px; border: none; cursor: pointer; margin-left: 5px;">
            Cancelar
        </button>
    </div>

    <div id="resultado" style="margin-bottom: 20px; padding: 10px; color: black;"></div>
    
    <!-- Modal para Estudiante -->
    <div id="modalEstudiante" style="display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
        <div style="background-color: white; margin: 10% auto; padding: 20px; width: 400px;">
            <h3 id="modalTitulo">Nuevo Estudiante</h3>
            
            <form id="formEstudiante">
                <input type="hidden" id="estudianteId">
                <div>
                    <label>Cédula:</label><br>
                    <input type="text" id="modalCedula" style="width: 100%; padding: 5px; margin-bottom: 10px;" required>
                </div>
                
                <div>
                    <label>Nombre:</label><br>
                    <input type="text" id="modalNombre" style="width: 100%; padding: 5px; margin-bottom: 10px;" required>
                </div>
                
                <div>
                    <label>Apellido:</label><br>
                    <input type="text" id="modalApellido" style="width: 100%; padding: 5px; margin-bottom: 10px;" required>
                </div>
                
                <div>
                    <label>Dirección:</label><br>
                    <input type="text" id="modalDireccion" style="width: 100%; padding: 5px; margin-bottom: 10px;" required>
                </div>
                
                <div>
                    <label>Teléfono:</label><br>
                    <input type="text" id="modalTelefono" style="width: 100%; padding: 5px; margin-bottom: 15px;" required>
                </div>
                
                <div>
                    <button type="button" id="btnCancelar" style="background-color: gray; color: white; padding: 8px 15px; border: none; margin-right: 10px; cursor: pointer;">Cancelar</button>
                    <button type="submit" id="btnGuardar" style="background-color: blue; color: white; padding: 8px 15px; border: none; cursor: pointer;">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal de Confirmación -->
    <div id="modalEliminar" style="display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
        <div style="background-color: white; margin: 15% auto; padding: 20px; width: 300px;">
            <h3>Confirmar Eliminación</h3>
            <p>¿Eliminar al estudiante <strong id="estudianteEliminar"></strong>?</p>
            
            <div>
                <button type="button" id="btnCancelarEliminar" style="background-color: gray; color: white; padding: 8px 15px; border: none; margin-right: 10px; cursor: pointer;">Cancelar</button>
                <button type="button" id="btnConfirmarEliminar" style="background-color: red; color: white; padding: 8px 15px; border: none; cursor: pointer;">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-action {
        padding: 5px 8px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        margin: 0 2px;
    }
    .btn-edit {
        background-color: orange;
        color: black;
    }
    .btn-delete {
        background-color: red;
        color: white;
    }
</style>

<script>
    const apiUrl = "api_soa.php";
    let estudianteAEliminar = null;
    let modoEdicion = false;
    
    function mostrarMensaje(mensaje, tipo = 'info') {
        const resultado = document.getElementById("resultado");
        const color = tipo === 'error' ? 'red' : tipo === 'success' ? 'green' : 'blue';
        
        const mensajesAnteriores = resultado.querySelectorAll('div p');
        mensajesAnteriores.forEach(msg => {
            if (msg.parentElement) {
                msg.parentElement.remove();
            }
        });
        
        const mensajeDiv = document.createElement('div');
        mensajeDiv.innerHTML = `<p style='color: ${color};'>${mensaje}</p>`;
        
        resultado.insertBefore(mensajeDiv, resultado.firstChild);
    }
    
    function cargarEstudiantes() {
        fetch(apiUrl, {
            method: "GET"
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                mostrarMensaje(data.error, 'error');
                return;
            }
            
            let tabla = `<h3>Lista de Estudiantes</h3>
                        <table border="1" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 8px;">Cedula</th>
                        <th style="padding: 8px;">Nombre</th>
                        <th style="padding: 8px;">Apellido</th>
                        <th style="padding: 8px;">Dirección</th>
                        <th style="padding: 8px;">Teléfono</th>
                        <th style="padding: 8px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>`;
            
            if (data.length === 0) {
                tabla += `<tr><td colspan="6" style="padding: 15px; text-align: center;">No hay estudiantes</td></tr>`;
            } else {
                data.forEach(function(estudiante) {
                    tabla += `<tr>
                        <td style="padding: 8px;">${estudiante.cedula}</td>
                        <td style="padding: 8px;">${estudiante.nombre}</td>
                        <td style="padding: 8px;">${estudiante.apellido}</td>
                        <td style="padding: 8px;">${estudiante.direccion}</td>
                        <td style="padding: 8px;">${estudiante.telefono}</td>
                        <td style="padding: 8px; text-align: center;">
                            <button class="btn-action btn-edit" onclick="editarEstudiante('${estudiante.cedula}', '${estudiante.nombre}', '${estudiante.apellido}', '${estudiante.direccion}', '${estudiante.telefono}')">Editar</button>
                            <button class="btn-action btn-delete" onclick="confirmarEliminar('${estudiante.cedula}', '${estudiante.nombre} ${estudiante.apellido}')">Eliminar</button>
                        </td>
                    </tr>`;
                });
            }
            tabla += `</tbody></table>`;
            
            const mensajes = document.getElementById("resultado").querySelectorAll('div');
            document.getElementById("resultado").innerHTML = tabla;
            
            mensajes.forEach(mensaje => {
                document.getElementById("resultado").insertBefore(mensaje, document.getElementById("resultado").firstChild);
            });
        })
        .catch(error => {
            console.error("Error al cargar estudiantes", error);
            mostrarMensaje("Error al obtener los datos", 'error');
        });
    }
    
    function buscarEstudiantePorCedula(cedula) {
        fetch(`${apiUrl}?search=cedula&cedula=${encodeURIComponent(cedula)}`, {
            method: "GET"
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                mostrarMensaje(data.error, 'error');
                return;
            }
            
            let tabla = `<h3>Resultado de Búsqueda</h3>
                        <table border="1" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="padding: 8px;">Cedula</th>
                        <th style="padding: 8px;">Nombre</th>
                        <th style="padding: 8px;">Apellido</th>
                        <th style="padding: 8px;">Dirección</th>
                        <th style="padding: 8px;">Teléfono</th>
                        <th style="padding: 8px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>`;
            
            data.forEach(function(estudiante) {
                tabla += `<tr>
                    <td style="padding: 8px;">${estudiante.cedula}</td>
                    <td style="padding: 8px;">${estudiante.nombre}</td>
                    <td style="padding: 8px;">${estudiante.apellido}</td>
                    <td style="padding: 8px;">${estudiante.direccion}</td>
                    <td style="padding: 8px;">${estudiante.telefono}</td>
                    <td style="padding: 8px; text-align: center;">
                        <button class="btn-action btn-edit" onclick="editarEstudiante('${estudiante.cedula}', '${estudiante.nombre}', '${estudiante.apellido}', '${estudiante.direccion}', '${estudiante.telefono}')">Editar</button>
                        <button class="btn-action btn-delete" onclick="confirmarEliminar('${estudiante.cedula}', '${estudiante.nombre} ${estudiante.apellido}')">Eliminar</button>
                    </td>
                </tr>`;
            });
            tabla += `</tbody></table>`;
            
            const mensajes = document.getElementById("resultado").querySelectorAll('div');
            document.getElementById("resultado").innerHTML = tabla;
            
            mensajes.forEach(mensaje => {
                document.getElementById("resultado").insertBefore(mensaje, document.getElementById("resultado").firstChild);
            });
        })
        .catch(error => {
            console.error("Error al buscar estudiante", error);
            mostrarMensaje("Error al buscar estudiante", 'error');
        });
    }
    
    function nuevoEstudiante() {
        modoEdicion = false;
        document.getElementById("modalTitulo").textContent = "Nuevo Estudiante";
        document.getElementById("btnGuardar").textContent = "Guardar";
        document.getElementById("formEstudiante").reset();
        document.getElementById("estudianteId").value = "";
        document.getElementById("modalCedula").disabled = false;
        document.getElementById("modalEstudiante").style.display = "block";
    }
    
    function editarEstudiante(cedula, nombre, apellido, direccion, telefono) {
        modoEdicion = true;
        document.getElementById("modalTitulo").textContent = "Editar Estudiante";
        document.getElementById("btnGuardar").textContent = "Actualizar";
        
        document.getElementById("estudianteId").value = cedula;
        document.getElementById("modalCedula").value = cedula;
        document.getElementById("modalNombre").value = nombre;
        document.getElementById("modalApellido").value = apellido;
        document.getElementById("modalDireccion").value = direccion;
        document.getElementById("modalTelefono").value = telefono;
        document.getElementById("modalCedula").disabled = true;
        
        document.getElementById("modalEstudiante").style.display = "block";
    }
    
    function confirmarEliminar(cedula, nombreCompleto) {
        estudianteAEliminar = cedula;
        document.getElementById("estudianteEliminar").textContent = `${nombreCompleto}`;
        document.getElementById("modalEliminar").style.display = "block";
    }
    
    function cerrarModales() {
        document.getElementById("modalEstudiante").style.display = "none";
        document.getElementById("modalEliminar").style.display = "none";
        estudianteAEliminar = null;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        cargarEstudiantes();
        
        document.getElementById("btnNuevoEstudiante").addEventListener("click", nuevoEstudiante);
        
        document.getElementById("btnBuscar").addEventListener("click", function() {
            document.getElementById("busquedaContainer").style.display = "block";
            document.getElementById("txtBuscarCedula").focus();
        });
        
        document.getElementById("btnCancelarBusqueda").addEventListener("click", function() {
            document.getElementById("busquedaContainer").style.display = "none";
            document.getElementById("txtBuscarCedula").value = "";
            cargarEstudiantes();
        });
        
        document.getElementById("btnEjecutarBusqueda").addEventListener("click", function() {
            const cedula = document.getElementById("txtBuscarCedula").value.trim();
            if (!cedula) {
                mostrarMensaje("Ingrese una cédula para buscar", 'error');
                return;
            }
            buscarEstudiantePorCedula(cedula);
            document.getElementById("busquedaContainer").style.display = "none";
        });
        
        document.getElementById("txtBuscarCedula").addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                document.getElementById("btnEjecutarBusqueda").click();
            }
        });
        
        document.getElementById("btnCancelar").addEventListener("click", cerrarModales);
        document.getElementById("btnCancelarEliminar").addEventListener("click", cerrarModales);
        
        window.addEventListener("click", function(event) {
            if (event.target === document.getElementById("modalEstudiante") || 
                event.target === document.getElementById("modalEliminar")) {
                cerrarModales();
            }
        });
        
        document.getElementById("formEstudiante").addEventListener("submit", function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('cedula', document.getElementById("modalCedula").value.trim());
            formData.append('nombre', document.getElementById("modalNombre").value.trim());
            formData.append('apellido', document.getElementById("modalApellido").value.trim());
            formData.append('direccion', document.getElementById("modalDireccion").value.trim());
            formData.append('telefono', document.getElementById("modalTelefono").value.trim());
            
            const url = modoEdicion ? 
                `${apiUrl}?cedula=${encodeURIComponent(document.getElementById("modalCedula").value)}&nombre=${encodeURIComponent(document.getElementById("modalNombre").value)}&apellido=${encodeURIComponent(document.getElementById("modalApellido").value)}&direccion=${encodeURIComponent(document.getElementById("modalDireccion").value)}&telefono=${encodeURIComponent(document.getElementById("modalTelefono").value)}` :
                apiUrl;
            
            const method = modoEdicion ? "PUT" : "POST";
            const body = modoEdicion ? null : formData;
            
            fetch(url, { method: method, body: body })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    mostrarMensaje(data.error, 'error');
                } else {
                    mostrarMensaje(data, 'success');
                    cerrarModales();
                    cargarEstudiantes();
                }
            })
            .catch(error => {
                console.error(error);
                mostrarMensaje(`Error al ${modoEdicion ? 'actualizar' : 'insertar'} datos`, 'error');
            });
        });
        
        document.getElementById("btnConfirmarEliminar").addEventListener("click", function() {
            if (estudianteAEliminar) {
                fetch(`${apiUrl}?cedula=${encodeURIComponent(estudianteAEliminar)}`, {
                    method: "DELETE"
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        mostrarMensaje(data.error, 'error');
                    } else {
                        mostrarMensaje(data, 'success');
                        cerrarModales();
                        cargarEstudiantes();
                    }
                })
                .catch(error => {
                    console.error(error);
                    mostrarMensaje("Error al eliminar datos", 'error');
                });
            }
        });
    });
</script>