<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nosotros - Usuarios Registrados</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2 class="mb-4">CRUD de Estudiantes</h2>
    <div class="mb-3">
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal" onclick="nuevoEstudiante()">Nuevo estudiante</button>
      <button class="btn btn-primary" onclick="abrirReporte('fpdf')">Reporte FPDF</button>
      <button class="btn btn-primary" onclick="abrirReporte('jasper')">Reporte iReport</button>
      <button class="btn btn-secondary" onclick="mostrarBusqueda('fpdf')">Reporte Ced fpdf</button>
      <button class="btn btn-secondary" onclick="mostrarBusqueda('jasper')">Reporte Ced ireport</button>
      <span id="busquedaCedula" style="display:none; margin-left:10px;">
        <input type="text" id="inputCedula" placeholder="Ingrese Cédula" class="form-control d-inline-block" style="width:180px; height:32px;">
        <button class="btn btn-info btn-sm" onclick="generarReporteCedula()">Ver reporte</button>
        <button class="btn btn-outline-secondary btn-sm" onclick="ocultarBusqueda()">Cancelar</button>
      </span>
    </div>
    <!-- Tabla de datos -->
    <table class="table table-striped" id="tablaEstudiantes">
      <thead>
        <tr>
          <th>Cédula</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Dirección</th>
          <th>Teléfono</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <!-- Aquí van los datos dinámicos -->
      </tbody>
    </table>
  </div>

  <!-- Modal para agregar/editar -->
  <div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content" id="formEstudiante">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Nuevo estudiante</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="CED_EST_OLD" name="CED_EST_OLD">
          <input type="text" class="form-control mb-2" id="CED_EST" name="CED_EST" placeholder="Cédula" required>
          <input type="text" class="form-control mb-2" id="NOM_EST" name="NOM_EST" placeholder="Nombre" required>
          <input type="text" class="form-control mb-2" id="APE_EST" name="APE_EST" placeholder="Apellido" required>
          <input type="text" class="form-control mb-2" id="DIR_EST" name="DIR_EST" placeholder="Dirección" required>
          <input type="text" class="form-control mb-2" id="TEL_EST" name="TEL_EST" placeholder="Teléfono" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let estudiantes = [];
    let modoEdicion = false;
    let estudianteEditando = null;

    // Cargar estudiantes al iniciar
    function cargarEstudiantes() {
      fetch('models/select.php')
        .then(response => response.json())
        .then(data => {
          estudiantes = data;
          renderTabla();
        })
        .catch(error => {
          console.error('Error al cargar usuarios:', error);
        });
    }

    function renderTabla() {
      const tbody = document.getElementById('tableBody');
      tbody.innerHTML = '';
      estudiantes.forEach(est => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
          <td>${est.CED_EST}</td>
          <td>${est.NOM_EST}</td>
          <td>${est.APE_EST}</td>
          <td>${est.DIR_EST}</td>
          <td>${est.TEL_EST}</td>
          <td>
            <button class="btn btn-primary btn-sm" onclick="editarEstudiante('${est.CED_EST}')">Editar</button>
            <button class="btn btn-danger btn-sm" onclick="eliminarEstudiante('${est.CED_EST}')">Eliminar</button>
          </td>
        `;
        tbody.appendChild(fila);
      });
    }

    // Modal: Nuevo estudiante
    function nuevoEstudiante() {
      modoEdicion = false;
      estudianteEditando = null;
      document.getElementById('modalTitle').textContent = 'Nuevo estudiante';
      document.getElementById('formEstudiante').reset();
      document.getElementById('CED_EST').disabled = false;
      document.getElementById('CED_EST_OLD').value = '';
    }

    // Modal: Editar estudiante
    function editarEstudiante(cedula) {
      modoEdicion = true;
      estudianteEditando = estudiantes.find(e => e.CED_EST === cedula);
      if (estudianteEditando) {
        document.getElementById('modalTitle').textContent = 'Editar estudiante';
        document.getElementById('CED_EST').value = estudianteEditando.CED_EST;
        document.getElementById('CED_EST_OLD').value = estudianteEditando.CED_EST;
        document.getElementById('NOM_EST').value = estudianteEditando.NOM_EST;
        document.getElementById('APE_EST').value = estudianteEditando.APE_EST;
        document.getElementById('DIR_EST').value = estudianteEditando.DIR_EST;
        document.getElementById('TEL_EST').value = estudianteEditando.TEL_EST;
        document.getElementById('CED_EST').disabled = true;
        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
      }
    }

    // Guardar estudiante (nuevo o edición)
    document.getElementById('formEstudiante').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      let url = modoEdicion ? 'models/editar.php' : 'models/guardar.php';
      if (modoEdicion) {
        formData.append('CED_EST', document.getElementById('CED_EST_OLD').value);
      }
      fetch(url, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(msg => {
        alert(msg);
        cargarEstudiantes();
        var modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
        modal.hide();
      });
    });

    // Eliminar estudiante
    function eliminarEstudiante(cedula) {
      if (confirm('¿Está seguro de eliminar este estudiante?')) {
        fetch('models/eliminar.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'CED_EST=' + encodeURIComponent(cedula)
        })
        .then(res => res.json())
        .then(msg => {
          alert(msg);
          cargarEstudiantes();
        });
      }
    }

    // Reportes
    function abrirReporte(tipo) {
      if (tipo === 'fpdf') {
        window.open('./reporteEstudiante.php');
      } else if (tipo === 'jasper') {
        window.open('./reporteEstudiantesJasper.php');
      }
    }

    // Reporte por cédula
    let tipoReporte = '';
    function mostrarBusqueda(tipo) {
      tipoReporte = tipo;
      document.getElementById('busquedaCedula').style.display = 'inline-block';
      document.getElementById('inputCedula').value = '';
      document.getElementById('inputCedula').focus();
    }
    function ocultarBusqueda() {
      document.getElementById('busquedaCedula').style.display = 'none';
      tipoReporte = '';
    }
    function generarReporteCedula() {
      var cedula = document.getElementById('inputCedula').value.trim();
      if (!cedula) {
        alert('Ingrese una cédula');
        return;
      }
      if (tipoReporte === 'fpdf') {
        window.open('./reporteEstudiante.php?cedula=' + encodeURIComponent(cedula), '_blank');
      } else if (tipoReporte === 'jasper') {
        window.open('./jaspers.php?cedula=' + encodeURIComponent(cedula), '_blank');
      }
      ocultarBusqueda();
    }

    // Inicializar
    cargarEstudiantes();
  </script>
</body>
</html>
