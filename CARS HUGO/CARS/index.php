<?php
require 'db_config.php';

// Obtener todos los personajes desde la base de datos
$sql = "SELECT * FROM personajes";
$stmt = $pdo->query($sql);
$personajes = $stmt->fetchAll();

// Función para mostrar el texto del nivel
function getNivelTexto($nivel) {
    $niveles = [
        1 => "Protagonista - Ka-Chow!",
        2 => "Secundario - Casi tan rápido",
        3 => "Recurrente - A media velocidad",
        4 => "Invitado - De paso por Radiador Springs",
        5 => "Leyenda - ¡Doc Hudson!",
        6 => "Novato - Aún con ruedas de entrenamiento",
        7 => "Experto - Veterano de la pista"
    ];
    return isset($niveles[$nivel]) ? $niveles[$nivel] : $nivel;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garaje de Personajes - Cars</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Racing Sans One -->
    <link href="https://fonts.googleapis.com/css2?family=Racing+Sans+One&display=swap" rel="stylesheet">
    
    <style>
        /* Fondo y estilos temáticos de Cars */
        body {
            background-color: #1a1a1a;
            position: relative;
            font-family: 'Arial', sans-serif;
        }
        
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 49%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 51%, rgba(255,255,255,0) 100%),
                repeating-linear-gradient(0deg, #333 0px, #333 30px, #555 30px, #555 60px);
            background-size: 60px 60px;
            opacity: 0.5;
            z-index: -1;
        }
        
        .cars-font {
            font-family: 'Racing Sans One', cursive;
        }
        
        .car-card {
            background: linear-gradient(145deg, #d92b2b 0%, #8c0303 100%);
            border-radius: 20px;
            border-bottom: 8px solid #700;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            position: relative;
            overflow: hidden;
        }
        
        .car-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 80px;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 100%);
            transform: skewY(-20deg) translateY(-20px);
        }
        
        .piston-cup {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: linear-gradient(145deg, #ffd700, #ffaa00);
            border-radius: 40% 40% 20% 20%;
            border: 3px solid #cc8800;
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .piston-cup::before {
            content: "🏆";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 32px;
        }
        
        .tire-button {
            background: radial-gradient(circle, #222 0%, #000 100%);
            border: 3px solid #444;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .tire-button:hover {
            transform: scale(1.05);
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
        
        .checkered-header {
            background-image: repeating-linear-gradient(45deg, 
                #000 0, #000 10px, 
                #fff 10px, #fff 20px);
            padding: 8px;
            position: relative;
        }
        
        .checkered-content {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 15px;
        }
        
        .car-card-item {
            background: linear-gradient(145deg, #2a2a2a 0%, #1a1a1a 100%);
            border-radius: 15px;
            border-bottom: 4px solid #000;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .car-card-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }
        
        .car-image {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            border: 3px solid #666;
            transition: all 0.3s;
        }
        
        .car-image:hover {
            transform: scale(1.1);
            border-color: #ffaa00;
        }
        
        .color-sample {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            vertical-align: middle;
            border: 2px solid #fff;
            margin-right: 8px;
        }
        
        .action-buttons .btn {
            position: relative;
            overflow: hidden;
        }
        
        .action-buttons .btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(30deg);
            transition: all 0.3s;
            opacity: 0;
        }
        
        .action-buttons .btn:hover::after {
            opacity: 1;
        }
        
        /* Colores mejorados para los niveles */
        .nivel-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .nivel-1 { background-color: #ff3b30; color: white; } /* Protagonista */
        .nivel-2 { background-color: #ff9500; color: white; } /* Secundario */
        .nivel-3 { background-color: #ffcc00; color: black; } /* Recurrente */
        .nivel-4 { background-color: #4cd964; color: black; } /* Invitado */
        .nivel-5 { background-color: #5ac8fa; color: black; } /* Leyenda */
        .nivel-6 { background-color: #007aff; color: white; } /* Novato */
        .nivel-7 { background-color: #5856d6; color: white; } /* Experto */
        
        .add-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #ffcc00;
            color: #d92b2b;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
            z-index: 100;
            border: none;
        }
        
        .add-button:hover {
            transform: scale(1.1) rotate(360deg);
            background: #ffaa00;
        }
        
        /* Animación de luces de semáforo */
        @keyframes trafficLights {
            0%, 50%, 100% { box-shadow: 0 0 20px #ff3b30; }
            16.66%, 66.66% { box-shadow: 0 0 20px #ffcc00; }
            33.33%, 83.33% { box-shadow: 0 0 20px #4cd964; }
        }
        
        .traffic-light-animation {
            animation: trafficLights 3s infinite;
        }
        
        /* ----- MEJORAS DE COLOR PARA LEGIBILIDAD ----- */
        /* Nombre del personaje con mejor contraste */
        .character-name {
            color: #ffffff;
            font-size: 1.4rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        /* Fondo para información de personaje con mejor contraste */
        .car-details {
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 15px;
        }
        
        /* Mejorar visibilidad de texto blanco */
        .text-data {
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .text-data i {
            width: 20px;
            color: #ffcc00;
            margin-right: 8px;
            text-align: center;
        }
        
        /* Título principal con mejor visibilidad */
        .main-title {
            color: #ffffff;
            text-shadow: 0 0 10px rgba(0,0,0,0.8), 2px 2px 4px rgba(0,0,0,0.9);
            letter-spacing: 1px;
        }
        
        /* Botones con mejor visibilidad */
        .action-button {
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.6);
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
        
        .action-button.edit {
            background-color: #f0b400;
            border-color: #e0a800;
            color: #000000;
        }
        
        .action-button.delete {
            background-color: #e62e2e;
            border-color: #d92020;
            color: #ffffff;
        }
        
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.4);
        }
        
        /* Modo tablero para pantallas pequeñas */
        @media (max-width: 768px) {
            .car-list {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            
            .car-card-item {
                display: flex;
                align-items: center;
                padding: 10px;
            }
            
            .car-image {
                width: 60px;
                height: 60px;
                margin-right: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="car-card p-4 mb-5">
        <div class="piston-cup traffic-light-animation"></div>
        <h1 class="cars-font main-title text-center mb-4" style="font-size: 3rem;">
            <i class="fas fa-car-side me-2"></i> Garaje de Radiador Springs
        </h1>
        
        <p class="text-center text-white mb-4" style="font-size: 1.1rem; font-weight: 500;">Catálogo oficial de personajes de la película Cars</p>
        
        <div class="checkered-header rounded-top">
            <div class="checkered-content rounded-top">
                <?php if (count($personajes) > 0): ?>
                    <div class="row car-list">
                        <?php foreach ($personajes as $personaje): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="car-card-item p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?php echo htmlspecialchars($personaje['foto']); ?>" 
                                             alt="<?php echo htmlspecialchars($personaje['nombre']); ?>" 
                                             class="car-image me-3">
                                        <div>
                                            <h3 class="character-name mb-0"><?php echo htmlspecialchars($personaje['nombre']); ?></h3>
                                            <div class="text-warning small">ID #<?php echo $personaje['id']; ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="car-details">
                                        <div class="text-data">
                                            <i class="fas fa-palette"></i>
                                            <span class="color-sample" style="background-color: <?php echo htmlspecialchars($personaje['color']); ?>"></span>
                                            <span><?php echo htmlspecialchars($personaje['color']); ?></span>
                                        </div>
                                        
                                        <div class="text-data">
                                            <i class="fas fa-tag"></i>
                                            <?php echo htmlspecialchars($personaje['tipo']); ?>
                                        </div>
                                        
                                        <div class="nivel-badge nivel-<?php echo $personaje['nivel']; ?> mb-2 mt-2">
                                            <i class="fas fa-tachometer-alt me-1"></i>
                                            <?php echo getNivelTexto($personaje['nivel']); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="action-buttons d-flex justify-content-between mt-auto">
                                        <a href="editar.php?id=<?php echo $personaje['id']; ?>" class="btn action-button edit btn-sm">
                                            <i class="fas fa-wrench me-1"></i> Ajustar
                                        </a>
                                        <a href="generar_pdf.php?id=<?php echo $personaje['id']; ?>" class="btn btn-success action-button btn-sm">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </a>
                                        <a href="eliminar.php?id=<?php echo $personaje['id']; ?>" 
                                           class="btn action-button delete btn-sm">
                                            <i class="fas fa-trash-alt me-1"></i> Desguace
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-white">
                        <i class="fas fa-car-crash fa-4x mb-3 text-warning"></i>
                        <h3 class="cars-font">¡El garaje está vacío!</h3>
                        <p>Parece que no hay ningún personaje en la pista aún.</p>
                        <a href="agregar.php" class="btn btn-warning mt-3 fw-bold">
                            <i class="fas fa-plus-circle me-2"></i> Agregar el primer corredor
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Botón flotante para agregar nuevo personaje -->
    <a href="agregar.php" class="add-button cars-font">
        <i class="fas fa-plus"></i>
    </a>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="carDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title cars-font text-warning" id="modalCarTitle">Nombre del Personaje</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="modalCarImage" src="" alt="Imagen del personaje" class="img-fluid rounded" style="max-height: 200px;">
                </div>
                <div id="modalCarDetails"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    // Función para mostrar detalles en el modal
    function showCarDetails(id, nombre, foto, color, tipo, nivel) {
        document.getElementById('modalCarTitle').textContent = nombre;
        document.getElementById('modalCarImage').src = foto;
        
        const niveles = [
            "Protagonista - Ka-Chow!",
            "Secundario - Casi tan rápido",
            "Recurrente - A media velocidad",
            "Invitado - De paso por Radiador Springs",
            "Leyenda - ¡Doc Hudson!",
            "Novato - Aún con ruedas de entrenamiento",
            "Experto - Veterano de la pista"
        ];
        
        const nivelTexto = niveles[nivel-1] || nivel;
        
        document.getElementById('modalCarDetails').innerHTML = `
            <div class="mb-3">
                <strong><i class="fas fa-palette me-2"></i> Color:</strong>
                <span class="color-sample" style="background-color: ${color}"></span>
                ${color}
            </div>
            <div class="mb-3">
                <strong><i class="fas fa-tag me-2"></i> Tipo:</strong> ${tipo}
            </div>
            <div class="mb-3">
                <strong><i class="fas fa-tachometer-alt me-2"></i> Nivel:</strong>
                <span class="nivel-badge nivel-${nivel}">${nivelTexto}</span>
            </div>
        `;
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('carDetailModal'));
        modal.show();
    }
</script>

</body>
</html>