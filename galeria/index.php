<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrusel Premium Pro - Gestión AJAX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a2a6c 0%, #b21f1f 50%, #fdbb2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            color: white;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 30px;
            box-shadow: 0 25px 45px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 850px;
            margin: auto;
        }

        #contenedor-ajax {
            width: 100%;
            height: 400px;
            background: #000;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255,255,255,0.5);
        }

        .img-sustituida { width: 100%; height: 100%; object-fit: cover; }

        /* Panel de Herramientas */
        .admin-tools {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .btn-tool {
            border-radius: 12px;
            padding: 8px 20px;
            font-size: 0.9rem;
            transition: 0.3s;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <div class="glass-card">
        <h2 class="mb-4 fw-bold">Gestión de Galería AJAX</h2>
        
        <div id="contenedor-ajax" class="shadow-lg">
            <div class="spinner-grow text-light" role="status"></div>
        </div>

        <h3 id="nombre-foto" class="mt-3">Cargando...</h3>
        <p id="contador" class="badge bg-dark rounded-pill"></p>

        <div class="d-flex justify-content-between mt-3 px-5">
            <button id="btn-prev" class="btn btn-light btn-sm rounded-circle">❮</button>
            <button id="btn-next" class="btn btn-light btn-sm rounded-circle">❯</button>
        </div>

        <div class="admin-tools d-flex justify-content-center gap-3">
            <button class="btn btn-success btn-tool" data-bs-toggle="modal" data-bs-target="#modalSubir">
                ➕ Subir Nueva
            </button>
            
            <button onclick="cargarImagenes()" class="btn btn-primary btn-tool">
                🔄 Sincronizar
            </button>

            <button id="btn-borrar" class="btn btn-danger btn-tool">
                🗑️ Borrar Actual
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSubir" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Subir Imagen a MariaDB</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="subir.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="text" name="nombre" class="form-control mb-3" placeholder="Nombre de la foto" required>
            <input type="file" name="archivo" class="form-control" required>
          </div>
          <div class="modal-footer border-secondary">
            <button type="submit" class="btn btn-success w-100">Guardar en Servidor</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
    let imagenes = [];
    let indiceActual = 0;

    function cargarImagenes() {
        $.ajax({
            url: 'get_imagenes.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                imagenes = data;
                if (imagenes.length > 0) {
                    actualizarNodo(0);
                } else {
                    $('#contenedor-ajax').html('<p class="p-5">Galería vacía</p>');
                    $('#nombre-foto').text("Sin archivos");
                }
            }
        });
    }

    function actualizarNodo(index) {
        if(imagenes.length === 0) return;
        const foto = imagenes[index];
        $('#contenedor-ajax').empty().append(`<img src="${foto.ruta}" class="img-sustituida">`);
        $('#nombre-foto').text(foto.nombre);
        $('#contador').text(`${index + 1} / ${imagenes.length}`);
    }

    // LÓGICA PARA BORRAR (AJAX)
    $('#btn-borrar').click(function() {
        if (imagenes.length === 0) return;
        const fotoActual = imagenes[indiceActual];
        
        if(confirm(`¿Seguro que quieres borrar "${fotoActual.nombre}"?`)) {
            $.post('borrar.php', { id: fotoActual.id }, function(res) {
                alert("Imagen eliminada correctamente");
                cargarImagenes(); // Recargamos la lista
            });
        }
    });

    $('#btn-next').click(function() { indiceActual = (indiceActual + 1) % imagenes.length; actualizarNodo(indiceActual); });
    $('#btn-prev').click(function() { indiceActual = (indiceActual - 1 + imagenes.length) % imagenes.length; actualizarNodo(indiceActual); });

    $(document).ready(cargarImagenes);
</script>

</body>
</html>
