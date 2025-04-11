<?php
require 'db_config.php';

// Variable para almacenar mensajes de alerta
$alertMessage = '';
$alertType = '';
$alertTitle = '';
$redirect = false;

// Verificar si se ha proporcionado un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Verificar si el ID existe en la base de datos
    $sql = "SELECT * FROM personajes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $personaje = $stmt->fetch();
    
    if (!$personaje) {
        $alertType = 'error';
        $alertTitle = 'Error';
        $alertMessage = '❌ Personaje no encontrado.';
    } else {
        // Si se ha confirmado la eliminación
        if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
            // Eliminar el personaje de la base de datos
            $sql = "DELETE FROM personajes WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$id])) {
                $alertType = 'success';
                $alertTitle = 'Éxito';
                $alertMessage = '✅ ¡Personaje eliminado correctamente!';
                $redirect = true;
            } else {
                $alertType = 'error';
                $alertTitle = 'Error';
                $alertMessage = '❌ Hubo un problema al eliminar el personaje.';
            }
        }
    }
} else {
    $alertType = 'error';
    $alertTitle = 'Error';
    $alertMessage = '❌ No se ha proporcionado un ID válido.';
    $redirect = true;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Personaje - Cars</title>

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
        
        /* Efectos adicionales para botones */
        .danger-button {
            background: linear-gradient(145deg, #ff3b3b 0%, #cc0000 100%);
            border-bottom: 4px solid #900;
        }
        
        .cancel-button {
            background: linear-gradient(145deg, #4b4b4b 0%, #2c2c2c 100%);
            border-bottom: 4px solid #222;
        }
    </style>
</head>
<body class="road-bg min-h-screen py-8">

<?php if (isset($_GET['id']) && !isset($_GET['confirm']) && $personaje): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¿Estás seguro?',
            html: `¿Realmente quieres eliminar a <strong>${'<?php echo htmlspecialchars($personaje['nombre']); ?>'}</strong>?<br>
                  <div class="mt-4">
                    <img src="${'<?php echo htmlspecialchars($personaje['foto']); ?>'}" alt="Imagen del personaje" class="mx-auto h-32 rounded">
                  </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '¡Sí, eliminar!',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'danger-button',
                cancelButton: 'cancel-button'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'eliminar.php?id=<?php echo $id; ?>&confirm=true';
            } else {
                window.location.href = 'index.php';
            }
        });
    });
</script>
<?php endif; ?>

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
    <div class="car-card p-5 max-w-3xl mx-auto text-center">
        <h1 class="cars-font text-5xl text-yellow-300 text-center mb-6" style="text-shadow: 3px 3px 5px rgba(0,0,0,0.5);">
            <i class="fas fa-trash-alt"></i> Pit Stop: Eliminar Personaje
        </h1>
        
        <?php if (!$alertMessage && !isset($_GET['confirm'])): ?>
            <div class="bg-black bg-opacity-50 p-5 rounded-lg">
                <p class="text-yellow-300 text-xl mb-4">Procesando tu solicitud...</p>
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-yellow-300"></div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="mt-6">
            <a href="index.php" class="inline-block tire-button text-white font-bold text-lg py-2 px-4 rounded-md">
                <i class="fas fa-home"></i> Volver al Garaje Principal
            </a>
        </div>
    </div>
</div>

</body>
</html>