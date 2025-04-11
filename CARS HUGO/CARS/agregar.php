<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $color = $_POST["color"];
    $tipo = $_POST["tipo"];
    $nivel = $_POST["nivel"];
    $foto = $_POST["foto"]; // URL de la foto del personaje

    // Insertar los datos del personaje en la base de datos
    $sql = "INSERT INTO personajes (nombre, color, tipo, nivel, foto) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nombre, $color, $tipo, $nivel, $foto])) {
        echo "<p class='alert alert-success'>✅ Personaje agregado correctamente.</p>";
    } else {
        echo "<p class='alert alert-danger'>❌ Error al agregar el personaje.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Personaje - Cars</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Fuente especial temática de Cars */
        @import url('https://fonts.googleapis.com/css2?family=Racing+Sans+One&display=swap');
        
        .cars-font {
            font-family: 'Racing Sans One', cursive;
        }
        
        /* Efecto de carretera para el fondo */
        .road-bg {
            background-color: #333;
            background-image: 
                linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 50%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0.4) 100%),
                repeating-linear-gradient(0deg, #333 0px, #333 30px, #555 30px, #555 60px);
            background-size: 60px 60px;
        }
        
        /* Botones con aspecto de neumático */
        .tire-button {
            background: radial-gradient(circle, #222 0%, #000 100%);
            border: 3px solid #444;
            position: relative;
            overflow: hidden;
        }
        
        .tire-button::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 80%;
            height: 80%;
            border-radius: 50%;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
        }
        
        /* Animación de velocímetro para los inputs */
        .speedometer-input:focus {
            border-color: #f00;
            box-shadow: 0 0 0 0.25rem rgba(255, 0, 0, 0.25);
            transition: all 0.3s;
        }
        
        /* Tarjeta con estilo de carrocería de auto */
        .car-card {
            background: linear-gradient(145deg, #d92b2b 0%, #8c0303 100%);
            border-radius: 20px;
            border-bottom: 8px solid #700;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.6);
            position: relative;
            overflow: hidden;
        }
        
        .car-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 60px;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 100%);
            transform: skewY(-20deg) translateY(-20px);
        }
    </style>
</head>
<body class="road-bg min-h-screen py-8">

<div class="container">
    <div class="car-card p-5 max-w-3xl mx-auto">
        <h1 class="cars-font text-5xl text-yellow-300 text-center mb-6 text-shadow" style="text-shadow: 3px 3px 5px rgba(0,0,0,0.5);">
            <i class="fas fa-car-side"></i> Pit Stop: Agregar Personaje
        </h1>

        <form action="agregar.php" method="post" enctype="multipart/form-data" class="space-y-6">
            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="nombre">
                    <i class="fas fa-id-card"></i> Nombre del Corredor:
                </label>
                <input type="text" name="nombre" id="nombre" class="form-control p-3 speedometer-input bg-white-800 text-black border-gray-600" required>
            </div>

            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="color">
                    <i class="fas fa-fill-drip"></i> Color de Carrocería:
                </label>
                <div class="input-group">
                    <input type="text" name="color" id="color" class="form-control p-3 speedometer-input bg-white-800 text-black border-gray-600" required>
                    <input type="color" id="colorPicker" class="form-control form-control-color h-100" onchange="document.getElementById('color').value = this.value">
                </div>
            </div>

            <!-- Dropdown para Tipo de Personaje -->
            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="tipo">
                    <i class="fas fa-cogs"></i> Categoría del Personaje:
                </label>
                <select name="tipo" id="tipo" class="form-select p-3 bg-white-800 text-black border-gray-600" required>
                    <option value="Corredor">Corredor de Piston Cup</option>
                    <option value="Mecánico">Mecánico de Pit</option>
                    <option value="Fanático">Fanático de las Carreras</option>
                    <option value="Villano">Rival en la Pista</option>
                    <option value="Aliado">Aliado de Rayo McQueen</option>
                    <option value="Juez">Oficial de Carreras</option>
                    <option value="Comentarista">Comentarista Deportivo</option>
                    <option value="Patrocinador">Patrocinador Corporativo</option>
                </select>
            </div>

            <!-- Dropdown para Nivel -->
            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="nivel">
                    <i class="fas fa-tachometer-alt"></i> Nivel de Velocidad:
                </label>
                <div class="range">
                    <input type="range" class="form-range" min="1" max="7" id="nivelRange" onchange="updateNivelSelect(this.value)">
                </div>
                <select name="nivel" id="nivel" class="form-select p-3 bg-white-800 text-black border-gray-600 mt-2" required>
                    <option value="1">Protagonista - Ka-Chow!</option>
                    <option value="2">Secundario - Casi tan rápido</option>
                    <option value="3">Recurrente - A media velocidad</option>
                    <option value="4">Invitado - De paso por Radiador Springs</option>
                    <option value="5">Leyenda - ¡Doc Hudson!</option>
                    <option value="6">Novato - Aún con las ruedas de entrenamiento</option>
                    <option value="7">Experto - Veterano de la pista</option>
                </select>
            </div>

            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="foto">
                    <i class="fas fa-camera"></i> Foto para el Carnet de Piloto:
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white-700 text-black border-gray-600"><i class="fas fa-link"></i></span>
                    <input type="text" name="foto" id="foto" class="form-control p-3 speedometer-input bg-white-800 text-black border-gray-600" placeholder="https://ejemplo.com/foto.jpg" required>
                </div>
                <div id="previewContainer" class="mt-3 hidden">
                    <p class="text-yellow-300 mb-2">Vista previa:</p>
                    <img id="imagePreview" src="" alt="Vista previa" class="rounded-lg max-h-40 border border-gray-600">
                </div>
            </div>

            <button type="submit" class="w-full tire-button p-4 rounded-full cars-font text-xl text-yellow-300 hover:text-yellow-400 transition duration-300 shadow-lg">
                <i class="fas fa-flag-checkered"></i> ¡Llevarlo a la Pista!
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="index.php" class="inline-block cars-font text-yellow-300 hover:text-yellow-400 transition-all duration-300 transform hover:-translate-x-2">
                <i class="fas fa-arrow-left"></i> Volver al Garaje
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    // Función para actualizar el selector de nivel basado en el rango
    function updateNivelSelect(value) {
        document.getElementById('nivel').value = value;
    }
    
    // Función para mostrar vista previa de la imagen
    document.getElementById('foto').addEventListener('input', function() {
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const imageUrl = this.value;
        
        if (imageUrl) {
            imagePreview.src = imageUrl;
            previewContainer.classList.remove('hidden');
            
            // Verificar si la imagen carga correctamente
            imagePreview.onload = function() {
                previewContainer.classList.remove('hidden');
            };
            
            imagePreview.onerror = function() {
                previewContainer.classList.add('hidden');
            };
        } else {
            previewContainer.classList.add('hidden');
        }
    });
    
    // Sincronizar el color picker con el campo de texto
    document.getElementById('colorPicker').addEventListener('input', function() {
        document.getElementById('color').value = this.value;
    });
</script>

</body>
</html>