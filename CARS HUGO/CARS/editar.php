<?php
require 'db_config.php';

// Variable para almacenar mensajes de alerta
$alertMessage = '';
$alertType = '';
$alertTitle = '';
$redirect = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Recuperar los datos del personaje desde la base de datos
    $sql = "SELECT * FROM personajes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $personaje = $stmt->fetch();

    if (!$personaje) {
        $alertType = 'error';
        $alertTitle = 'Error';
        $alertMessage = '❌ Personaje no encontrado.';
    } else {
        // Verificar si el formulario fue enviado para editar los datos
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recoger los datos del formulario
            $nombre = $_POST["nombre"];
            $color = $_POST["color"];
            $tipo = $_POST["tipo"];
            $nivel = $_POST["nivel"];
            $foto = $_POST["foto"]; // URL de la foto del personaje

            // Validación de los datos
            if (empty($nombre) || empty($color) || empty($tipo) || empty($nivel) || empty($foto)) {
                $alertType = 'warning';
                $alertTitle = 'Campos vacíos';
                $alertMessage = '❌ Todos los campos son obligatorios.';
            } else {
                // Actualizar los datos del personaje en la base de datos
                $sql = "UPDATE personajes SET nombre = ?, color = ?, tipo = ?, nivel = ?, foto = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);

                if ($stmt->execute([$nombre, $color, $tipo, $nivel, $foto, $id])) {
                    $alertType = 'success';
                    $alertTitle = 'Éxito';
                    $alertMessage = '✅ Personaje actualizado correctamente.';
                    $redirect = true;
                } else {
                    $alertType = 'error';
                    $alertTitle = 'Error';
                    $alertMessage = '❌ Hubo un problema al actualizar el personaje.';
                }
            }
        }
    }
} else {
    $alertType = 'error';
    $alertTitle = 'Error';
    $alertMessage = '❌ No se ha proporcionado un ID válido.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Personaje - Cars</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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

<?php if ($alertMessage): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: '<?php echo $alertType; ?>',
            title: '<?php echo $alertTitle; ?>',
            text: '<?php echo $alertMessage; ?>',
            <?php if ($redirect): ?>
            timer: 2000,
            showConfirmButton: false
            <?php endif; ?>
        })<?php if ($redirect): ?>.then(function() {
            window.location.href = 'index.php';
        })<?php endif; ?>;
    });
</script>
<?php endif; ?>

<div class="container">
    <?php if ($personaje): ?>
    <div class="car-card p-5 max-w-3xl mx-auto">
        <h1 class="cars-font text-5xl text-yellow-300 text-center mb-6" style="text-shadow: 3px 3px 5px rgba(0,0,0,0.5);">
            <i class="fas fa-car-side"></i> Pit Stop: Editar Personaje
        </h1>

        <form action="editar.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="nombre">
                    <i class="fas fa-id-card"></i> Nombre del Corredor:
                </label>
                <input type="text" name="nombre" id="nombre" class="form-control p-3 speedometer-input bg-white-800 text-black border-gray-600" value="<?php echo htmlspecialchars($personaje['nombre']); ?>" required>
            </div>

            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="color">
                    <i class="fas fa-fill-drip"></i> Color de Carrocería:
                </label>
                <div class="input-group">
                    <input type="text" name="color" id="color" class="form-control p-3 speedometer-input bg-white-800 text-black border-gray-600" value="<?php echo htmlspecialchars($personaje['color']); ?>" required>
                    <input type="color" id="colorPicker" class="form-control form-control-color h-100" value="<?php echo htmlspecialchars($personaje['color']); ?>" onchange="document.getElementById('color').value = this.value">
                </div>
            </div>

            <!-- Dropdown para Tipo de Personaje -->
            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="tipo">
                    <i class="fas fa-cogs"></i> Categoría del Personaje:
                </label>
                <select name="tipo" id="tipo" class="form-select p-3 bg-white-800 text-black border-gray-600" required>
                    <option value="Corredor" <?php if ($personaje['tipo'] == 'Corredor') echo 'selected'; ?>>Corredor de Piston Cup</option>
                    <option value="Mecánico" <?php if ($personaje['tipo'] == 'Mecánico') echo 'selected'; ?>>Mecánico de Pit</option>
                    <option value="Fanático" <?php if ($personaje['tipo'] == 'Fanático') echo 'selected'; ?>>Fanático de las Carreras</option>
                    <option value="Villano" <?php if ($personaje['tipo'] == 'Villano') echo 'selected'; ?>>Rival en la Pista</option>
                    <option value="Aliado" <?php if ($personaje['tipo'] == 'Aliado') echo 'selected'; ?>>Aliado de Rayo McQueen</option>
                    <option value="Juez" <?php if ($personaje['tipo'] == 'Juez') echo 'selected'; ?>>Oficial de Carreras</option>
                    <option value="Comentarista" <?php if ($personaje['tipo'] == 'Comentarista') echo 'selected'; ?>>Comentarista Deportivo</option>
                    <option value="Patrocinador" <?php if ($personaje['tipo'] == 'Patrocinador') echo 'selected'; ?>>Patrocinador Corporativo</option>
                </select>
            </div>

            <!-- Dropdown para Nivel -->
            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="nivel">
                    <i class="fas fa-tachometer-alt"></i> Nivel:
                </label>
                <input type="range" name="nivel" id="nivel" min="1" max="10" value="<?php echo $personaje['nivel']; ?>" oninput="updateNivelSelect(this.value)" class="form-control" required>
                <span class="text-yellow-300">Nivel: <span id="nivelLabel"><?php echo $personaje['nivel']; ?></span></span>
            </div>

            <!-- Foto -->
            <div class="form-group bg-black bg-opacity-50 p-4 rounded-lg">
                <label class="cars-font text-lg text-yellow-300 mb-2 block" for="foto">
                    <i class="fas fa-image"></i> URL de la Foto:
                </label>
                <input type="url" name="foto" id="foto" class="form-control p-3 speedometer-input bg-white-800 text-black border-gray-600" value="<?php echo htmlspecialchars($personaje['foto']); ?>" required>
                <div class="mt-2">
                    <img src="<?php echo htmlspecialchars($personaje['foto']); ?>" alt="Vista previa" class="h-32 rounded border border-gray-600">
                </div>
            </div>

            <button type="submit" class="tire-button text-white font-bold text-lg py-2 px-4 rounded-md w-full">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
    function updateNivelSelect(value) {
        document.getElementById('nivelLabel').innerText = value;
    }
    
    // Inicializar el selector de color con el valor actual
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('colorPicker').value = document.getElementById('color').value;
    });
</script>

</body>
</html>