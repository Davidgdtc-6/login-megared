<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    
    exit();
}

$nombre_usuario = $_SESSION['usuario_nombre'];
$apellidos_usuario = $_SESSION['usuario_apellidos'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Megared</title>
    <style>
        body { font-family: sans-serif; background: #f9f9f9; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        h1 { color: #333; margin: 0; }
        a.logout-btn { 
            text-decoration: none; background: #dc3545; color: white; 
            padding: 10px 15px; border-radius: 4px; font-weight: bold;
        }
        a.logout-btn:hover { background: #c82333; }
        .content { margin-top: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>¡Bienvenido, <?php echo htmlspecialchars($nombre_usuario . " " . $apellidos_usuario); ?>!</h1>
            
            <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
        </div>

        <div class="content">
            <p>Este es tu panel de control de Megared.</p>
            <p>Has iniciado sesión correctamente y tu sesión está activa.</p>
            </div>
    </div>

</body>
</html>