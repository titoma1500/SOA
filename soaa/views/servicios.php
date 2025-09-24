<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>UTA Cuarto</title>
</head>

<body>
    <h2>Basic CRUD Application</h2>
    <p>Click the buttons on datagrid toolbar to do crud actions.</p>

    <table id="dg" title="My Users" class="easyui-datagrid" style="width:700px;height:250px"
        url="models/select.php"
        toolbar="#toolbar" pagination="true"
        rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="CED_EST" width="50">Cedula</th>
                <th field="NOM_EST" width="50">Nombre</th>
                <th field="APE_EST" width="50">Apellido</th>
                <th field="DIR_EST" width="50">Direccion</th>
                <th field="TEL_EST" width="50">Telefono</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">Nuevo estudiante</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Editar estudiante</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Eliminar estudiante</a>
        <a href="reporteEstudiante.php" target="_blank" class="easyui-linkbutton" iconCls="icon-remove" plain="true">fpdf</a>
        <a href="jaspers.php" target="_blank" class="easyui-linkbutton" iconCls="icon-remove" plain="true">iReport</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="mostrarBusqueda('fpdf')">Reporte Ced fpdf</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="mostrarBusqueda('jasper')">Reporte Ced ireport</a>
        <span id="busquedaCedula" style="display:none; margin-left:10px;">
            <input type="text" id="inputCedula" placeholder="Ingrese Cédula" style="height:28px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="generarReporteCedula()">Ver reporte</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="ocultarBusqueda()">Cancelar</a>
        </span>
    </div>

    <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
            <h3>User Information</h3>
            <div style="margin-bottom:10px">
                <input name="CED_EST" class="easyui-textbox" required="true" label="Cedula:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="NOM_EST" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="APE_EST" class="easyui-textbox" required="true" label="Apellido:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="DIR_EST" class="easyui-textbox" required="true" label="Direccion:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="TEL_EST" class="easyui-textbox" required="true" label="Telefono:" style="width:100%">
            </div>
        </form>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <script type="text/javascript">
        var url;

        function newUser() {
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo estudiante');
            $('#fm').form('clear');
            url = 'models/guardar.php';
        }

        function editUser() {
            var row = $('#dg').datagrid('getSelected');
            if (row) {
                $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Actualizar los datos del estudiante');
                $('#fm').form('load', row);
                url = 'models/editar.php';
            }
        }

        function saveUser() {
            $('#fm').form('submit', {
                url: url,
                iframe: false,
                onSubmit: function() {
                    return $(this).form('validate');
                },
                success: function(result) {
                    var result = eval('(' + result + ')');
                    if (result.errorMsg) {
                        $.messager.show({
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    } else {
                        $.messager.show({
                            title: 'Se guardo correctamente',
                            msg: result.errorMsg
                        });
                        $('#dlg').dialog('close'); // close the dialog
                        $('#dg').datagrid('reload'); // reload the user data
                    }
                }
            });
        }

        function destroyUser() {
            var row = $('#dg').datagrid('getSelected');
            if (row) {
                $.messager.confirm('Confirmar', 'Quiere eliminar el usuario', function(r) {
                    if (r) {
                        $.post('models/eliminar.php', {
                            CED_EST: row.CED_EST
                        }, function(resultado) {
                            console.log(resultado);
                            if (!resultado.success) {
                                console.log(resultado);
                                $('#dg').datagrid('reload');
                                $.messager.show({
                                    title: 'Se eliminó correctamente',
                                    msg: result.message
                                });
                            } else {
                                console.log(resultado);
                                $.messager.show({
                                    title: 'No se eliminó correctamente',
                                    msg: result.message
                                });
                            }
                        }, 'json');
                    }
                });
            }
        }

        function reporteFPDF() {
            window.open('./reporteEstudiantes.php');
        }

        function reporteJasper() {
            window.open('./reporteEstudiantesJasper.php');
        }
        
        var tipoReporte = '';

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
        window.open('reporteEstudiante.php?cedula=' + encodeURIComponent(cedula), '_blank');
    } else if (tipoReporte === 'jasper') {
        window.open('jaspers.php?cedula=' + encodeURIComponent(cedula), '_blank');
    }
    ocultarBusqueda();
}
    </script>
</body>

</html>