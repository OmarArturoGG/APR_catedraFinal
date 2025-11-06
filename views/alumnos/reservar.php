<?php
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 'alumno') {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Tutoría</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="menu-alumno">
        <a href="alumno.php" class="btn-volver">← Volver al Inicio</a>
        
        <h2>Reservar Tutoría</h2>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje-exito">✅ <?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error">❌ <?php echo $error; ?></div>
        <?php endif; ?>

        <div id="paso1">
            <h3>Paso 1: Selecciona un profesor</h3>
            <select id="selectProfesor" required onchange="cargarMaterias()">
                <option value="">Selecciona un profesor</option>
                <?php foreach ($profesores as $profesor): ?>
                    <option value="<?php echo $profesor['id']; ?>">
                        <?php echo $profesor['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="paso2" style="display: none; margin-top: 20px;">
            <h3>Paso 2: Selecciona una materia</h3>
            <select id="selectMateria" required onchange="cargarHorarios()">
                <option value="">Selecciona una materia</option>
            </select>
        </div>

        <div id="paso3" style="display: none; margin-top: 20px;">
            <h3>Paso 3: Selecciona un horario disponible</h3>
            <div id="horariosContainer"></div>
        </div>
    </div>

    <script>
        function cargarMaterias() {
            var profesorId = document.getElementById('selectProfesor').value;
            var paso2 = document.getElementById('paso2');
            var paso3 = document.getElementById('paso3');
            
            if (profesorId === '') {
                paso2.style.display = 'none';
                paso3.style.display = 'none';
                return;
            }
            
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'obtener_materias.php?profesor_id=' + profesorId, true);
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var materias = JSON.parse(xhr.responseText);
                    var selectMateria = document.getElementById('selectMateria');
                    selectMateria.innerHTML = '<option value="">Selecciona una materia</option>';
                    
                    if (materias.length > 0) {
                        materias.forEach(function(materia) {
                            var option = document.createElement('option');
                            option.value = materia.id;
                            option.textContent = materia.nombre;
                            selectMateria.appendChild(option);
                        });
                        paso2.style.display = 'block';
                        paso3.style.display = 'none';
                    } else {
                        selectMateria.innerHTML = '<option value="">Este profesor no tiene materias</option>';
                        paso3.style.display = 'none';
                    }
                }
            };
            xhr.send();
        }

        function cargarHorarios() {
            var profesorId = document.getElementById('selectProfesor').value;
            var materiaId = document.getElementById('selectMateria').value;
            var paso3 = document.getElementById('paso3');
            
            if (materiaId === '') {
                paso3.style.display = 'none';
                return;
            }
            
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'obtener_horarios_disponibles.php?profesor_id=' + profesorId + '&materia_id=' + materiaId, true);
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var horarios = JSON.parse(xhr.responseText);
                    var container = document.getElementById('horariosContainer');
                    container.innerHTML = '';
                    
                    if (horarios.length > 0) {
                        horarios.forEach(function(horario) {
                            var horarioDiv = document.createElement('div');
                            horarioDiv.className = 'horario-item';
                            horarioDiv.style.margin = '10px 0';
                            horarioDiv.style.padding = '15px';
                            horarioDiv.style.border = '1px solid #ddd';
                            horarioDiv.style.borderRadius = '5px';
                            horarioDiv.style.background = horario.ocupado ? '#f8d7da' : '#d4edda';
                            
                            horarioDiv.innerHTML = `
                                <h4>${horario.dia_semana} - ${horario.hora_inicio} a ${horario.hora_fin}</h4>
                                <p><strong>Estado:</strong> ${horario.ocupado ? '❌ Ocupado' : '✅ Disponible'}</p>
                                ${!horario.ocupado ? 
                                    `<button onclick="reservarHorario(${horario.id})" style="padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;">Reservar este horario</button>` 
                                    : ''
                                }
                            `;
                            container.appendChild(horarioDiv);
                        });
                        paso3.style.display = 'block';
                    } else {
                        container.innerHTML = '<p>No hay horarios disponibles para esta materia.</p>';
                        paso3.style.display = 'block';
                    }
                }
            };
            xhr.send();
        }

        function reservarHorario(horarioId) {
            if (confirm('¿Confirmas la reserva de este horario?')) {
                window.location = 'reservar_horario.php?horario_id=' + horarioId;
            }
        }
    </script>
</body>
</html>