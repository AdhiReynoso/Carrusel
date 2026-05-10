<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrusel AJAX - Sustitución de Nodos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #contenedor-ajax {
            min-height: 550px;
            background-color: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        .img-sustituida {
            height: 550px;
            width: 100%;
            object-fit: cover;
            display: block;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Galería Dinámica (AJAX)</h2>
    
    <div class="card shadow-lg">
        <div id="contenedor-ajax">
            <div class="text-white">Conectando con MariaDB...</div>
        </div>

        <div class="d-flex justify-content-between p-3 bg-white border-top align-items-center">
            <button class="btn btn-dark" id="btn-prev">Anterior</button>

            <!-- ✅ Centro con contador, nombre y botones de acción -->
            <div class="text-center">
                <span id="contador" class="badge bg-primary mb-1">Cargando...</span>
                <div id="nombre-foto" class="fw-bold d-block text-dark"></div>

                <div class="mt-2">
                    <button class="btn btn-sm btn-warning" onclick="prepararActualizacion()">Actualizar</button>
                    <button class="btn btn-sm btn-danger" onclick="confirmarBorrado()">Borrar</button>
                </div>
            </div>

            <button class="btn btn-dark" id="btn-next">Siguiente</button>
        </div>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-dark">Subir Nueva Imagen</h5>
            <form action="subir.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="text" name="nombre_foto" class="form-control" placeholder="Título de la foto" required>
                </div>
                <div class="mb-3">
                    <input type="file" name="imagen" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Cargar a la Galería</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let imagenes = [];
    let indiceActual = 0;

    function cargarImagenes() {
        $.ajax({
            url: 'get_imagenes.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                imagenes = data;
                if (imagenes.length > 0) {
                    actualizarNodo(0);
                } else {
                    $('#contenedor-ajax').html('<p class="text-white text-center p-4">No hay imágenes en la base de datos</p>');
                    $('#contador').text('0 de 0');
                    $('#btn-next, #btn-prev').prop('disabled', true);
                }
            },
            error: function (xhr, status, error) {
                $('#contenedor-ajax').html(
                    '<p class="text-danger text-center p-4">Error al cargar imágenes.<br>Verifica que get_imagenes.php esté disponible.</p>'
                );
                console.error('AJAX Error:', status, error);
            }
        });
    }

    function actualizarNodo(index) {
        if (imagenes.length === 0) return;

        const foto = imagenes[index];
        const contenedor = $('#contenedor-ajax');

        contenedor.empty();

        const nuevaImagen = $('<img>')
            .attr('src', foto.ruta)
            .attr('alt', foto.nombre)
            .addClass('img-sustituida')
            .on('error', function () {
                contenedor.empty().append(
                    '<p class="text-warning text-center p-4">⚠️ No se pudo cargar la imagen: ' + foto.nombre + '</p>'
                );
            });

        contenedor.append(nuevaImagen);
        $('#nombre-foto').text(foto.nombre);
        $('#contador').text('FOTO ' + (index + 1) + ' DE ' + imagenes.length);

        // ✅ Limpiar el formulario al cambiar de imagen para evitar confusión
        resetFormulario();
    }

    $('#btn-next').click(function () {
        if (imagenes.length > 0) {
            indiceActual = (indiceActual + 1) % imagenes.length;
            actualizarNodo(indiceActual);
        }
    });

    $('#btn-prev').click(function () {
        if (imagenes.length > 0) {
            indiceActual = (indiceActual - 1 + imagenes.length) % imagenes.length;
            actualizarNodo(indiceActual);
        }
    });

    // ✅ Función de borrado con confirmación
    window.confirmarBorrado = function () {
        if (imagenes.length === 0) return;
        const foto = imagenes[indiceActual];

        if (confirm('¿Estás seguro de que deseas eliminar la imagen: ' + foto.nombre + '?')) {
            $.ajax({
                url: 'borrar.php',
                type: 'POST',
                data: { id: foto.id },
                success: function () {
                    alert('Imagen eliminada con éxito');
                    location.reload();
                },
                error: function () {
                    alert('Error al eliminar la imagen');
                }
            });
        }
    };

    // ✅ Función de actualización: rellena el formulario con los datos actuales
    window.prepararActualizacion = function () {
        if (imagenes.length === 0) return;
        const foto = imagenes[indiceActual];

        $('input[name="nombre_foto"]').val(foto.nombre);
        $('.card-title').text('Actualizar Imagen (Selecciona archivo nuevo)');

        // Agrega o actualiza el campo oculto con el ID
        if (!$('#id_actualizar').length) {
            $('form').append('<input type="hidden" name="id_actualizar" id="id_actualizar" value="' + foto.id + '">');
        } else {
            $('#id_actualizar').val(foto.id);
        }

        // Scroll suave hacia el formulario
        $('html, body').animate({ scrollTop: $(document).height() }, 'slow');
    };

    // ✅ Resetea el formulario al navegar entre fotos
    function resetFormulario() {
        $('input[name="nombre_foto"]').val('');
        $('.card-title').text('Subir Nueva Imagen');
        $('#id_actualizar').remove();
    }

    cargarImagenes();
});
</script>
</body>
</html>
