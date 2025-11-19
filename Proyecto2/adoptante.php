<?php
session_start();
require_once "conexion.php";


if (!isset($_SESSION['idUsuario'])) {
    header("Location: loginUsuario.html"); 
    exit();
}

$usuario_id = $_SESSION['idUsuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <form action="cerrarSesion.php" method="post">
    <button type="submit">Cerrar sesión</button>
  </form> 
  <meta charset="UTF-8">
  <title>Catálogo de Mascotas</title>
  <style>
    .catalogo { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
    .card { border: 1px solid #ccc; border-radius: 8px; padding: 10px; width: 220px; text-align: center; box-shadow: 2px 2px 8px #eee; }
    .card img { max-width: 180px; max-height: 120px; border-radius: 6px; }
    .filtros { text-align: center; margin-bottom: 20px; }
    .filtros input, .filtros select { margin: 0 5px 10px 5px; }
    .modal { display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-content { background: #fff; padding: 20px; border-radius: 5px; min-width: 320px; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Catálogo de Mascotas en Proceso de Adopción</h2>
  <p style="text-align:center;">Bienvenido!</p>

  <div class="filtros">
    <input type="text" id="filtro_titulo" placeholder="Título">
    <input type="text" id="filtro_raza" placeholder="Raza">
    <input type="number" id="filtro_edad" placeholder="Edad (meses)">
    <input type="text" id="filtro_peso" placeholder="Peso">
    <select id="filtro_genero">
      <option value="">Género</option>
      <option value="macho">Macho</option>
      <option value="hembra">Hembra</option>
    </select>
    <select id="filtro_tipo">
      <option value="">Tipo</option>
      <option value="perro">Perro</option>
      <option value="gato">Gato</option>
    </select>
    <button onclick="cargarCatalogo()">Buscar</button>
    <button onclick="limpiarFiltros()">Limpiar</button>
  </div>

  <a href="pedirTurno.php"><button>Pedir Turno</button></a>
  <a href="turnosAdoptante.php"><button>Mis Turnos</button></a>

  


  
 
<div class="volver">
    <a href="adoptante_index.html"><button>atras</button></a>
  </div>

  <div class="catalogo" id="catalogoMascotas">
    <!-- Aquí se cargan las mascotas -->
  </div>

  <!-- Modal de detalles -->
  <div class="modal" id="modalDetalles">
    <div class="modal-content" id="contenidoDetalles"></div>
  </div>

  <!-- Modal de adopción -->
  <div class="modal" id="modalAdopcion">
    <div class="modal-content">
      <h3>Formulario de Adopción</h3>
      <form id="formAdopcion">
        <input type="hidden" name="mascota_id" id="adopcion_mascota_id">
        <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>"> <!-- ID del usuario -->
        <label>DNI: <input type="text" name="dni" required></label><br><br>
        <label>Domicilio: <input type="text" name="domicilio" required></label><br><br>
        <label>Teléfono: <input type="text" name="telefono" required></label><br><br>
        <label>Edad: <input type="number" name="edad" id="adopcion_edad" required></label><br><br>
        <label>Convivencia familiar: <input type="text" name="convivencia" required></label><br><br>
        <label>Motivación para adoptar: <input type="text" name="motivacion" required></label><br><br>
        <button type="submit">Enviar Solicitud</button>
        <button type="button" onclick="cerrarModal('modalAdopcion')">Cancelar</button>
      </form>
    </div>
  </div>

<script>
function cargarCatalogo() {
  const params = new URLSearchParams({
    titulo: document.getElementById('filtro_titulo').value,
    raza: document.getElementById('filtro_raza').value,
    edad: document.getElementById('filtro_edad').value,
    peso: document.getElementById('filtro_peso').value,
    genero: document.getElementById('filtro_genero').value,
    tipo: document.getElementById('filtro_tipo').value
  });
  fetch('catalogo_mascotas.php?' + params.toString())
    .then(res => res.text())
    .then(html => {
      document.getElementById('catalogoMascotas').innerHTML = html;
      agregarEventosCatalogo();
    });
}

function limpiarFiltros() {
  document.getElementById('filtro_titulo').value = '';
  document.getElementById('filtro_raza').value = '';
  document.getElementById('filtro_edad').value = '';
  document.getElementById('filtro_peso').value = '';
  document.getElementById('filtro_genero').value = '';
  document.getElementById('filtro_tipo').value = '';
  cargarCatalogo();
}

function agregarEventosCatalogo() {
  document.querySelectorAll('.btn-detalles').forEach(btn => {
    btn.onclick = function() {
      fetch('detalle_mascota.php?id=' + btn.dataset.id)
        .then(res => res.text())
        .then(html => {
          document.getElementById('contenidoDetalles').innerHTML = html;
          document.getElementById('modalDetalles').style.display = 'flex';
        });
    };
  });
  document.querySelectorAll('.btn-adoptar').forEach(btn => {
    btn.onclick = function() {
      document.getElementById('adopcion_mascota_id').value = btn.dataset.id;
      document.getElementById('modalAdopcion').style.display = 'flex';
    };
  });
}

function cerrarModal(id) {
  document.getElementById(id).style.display = 'none';
  if(id === 'modalAdopcion') document.getElementById('formAdopcion').reset();
}

document.getElementById('formAdopcion').onsubmit = function(e) {
  e.preventDefault();
  const edad = parseInt(document.getElementById('adopcion_edad').value, 10);
  if (edad < 18) {
    alert('Debes ser mayor de 18 años para adoptar.');
    return;
  }
  const formData = new FormData(this);
  fetch('procesar_adopcion.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(msg => {
    alert(msg);
    cerrarModal('modalAdopcion');
    cargarCatalogo();
  });
};

cargarCatalogo();


window.onclick = function(event) {
  if (event.target == document.getElementById('modalDetalles')) cerrarModal('modalDetalles');
  if (event.target == document.getElementById('modalAdopcion')) cerrarModal('modalAdopcion');
};
</script>
</body>
</html>
