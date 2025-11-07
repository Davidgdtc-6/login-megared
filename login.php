<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}

require_once 'db-connect.php';

$mensaje_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    if (empty($correo) || empty($contrasena)) {
        $mensaje_error = "Error: Correo y contraseña son requeridos.";
    } else {
        
        $stmt = $conn->prepare("SELECT id, nombre, apellidos, contrasena FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($contrasena, $usuario['contrasena'])) {
                session_regenerate_id(true);

                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_apellidos'] = $usuario['apellidos'];
                $_SESSION['usuario_correo'] = $correo;

                header("Location: dashboard.php");
                exit();

            } else {
                $mensaje_error = "Error: Correo o contraseña incorrectos.";
            }
        } else {
            $mensaje_error = "Error: Correo o contraseña incorrectos.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Megared</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; display: grid; place-items: center; min-height: 100vh; }
        .container { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; }
        form div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        button:hover { background: #218838; }
        .mensaje { padding: 10px; text-align: center; border-radius: 4px; margin-bottom: 15px; }
        .error { background: #f8d7da; color: #721c24; }
        p.registro-link { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Iniciar Sesión</h2>

        <?php if (!empty($mensaje_error)): ?>
            <p class="mensaje error">
                <?php echo $mensaje_error; ?>
            </p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div>
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit">Entrar</button>
        </form>

        <p class="registro-link">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </p>
    </div>

</body>
</html>