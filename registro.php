<?php
session_start();

require_once 'db-connect.php';

$mensaje = "";

if (empty($_SESSION['captcha_code'])) {
    $str_captcha = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $str_captcha = str_shuffle($str_captcha);
    $_SESSION['captcha_code'] = substr($str_captcha, 0, 5);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $cedula = trim($_POST['cedula']);
    $genero = $_POST['genero'];
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $captcha_usuario = trim($_POST['captcha']);

    if (empty($nombre) || empty($apellidos) || empty($cedula) || empty($genero) || empty($correo) || empty($contrasena) || empty($captcha_usuario)) {
        $mensaje = "Error: Todos los campos son obligatorios.";
    } 

    elseif (strtoupper($captcha_usuario) != $_SESSION['captcha_code']) {
        $mensaje = "Error: El código Captcha es incorrecto.";
        unset($_SESSION['captcha_code']);
    }

    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Error: El formato del correo electrónico no es válido.";
    }

    else {
        

        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ? OR cedula = ?");
        $stmt->bind_param("ss", $correo, $cedula);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = "Error: El correo electrónico o la cédula ya están registrados.";
            $stmt->close();
        } else {

            $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt_insert = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, cedula, genero, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?)");

            $stmt_insert->bind_param("ssssss", $nombre, $apellidos, $cedula, $genero, $correo, $hash_contrasena);

            if ($stmt_insert->execute()) {
                $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión.";

                unset($_SESSION['captcha_code']);
            } else {
                $mensaje = "Error: Ocurrió un problema al registrar. " . $stmt_insert->error;
            }
            $stmt_insert->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; display: grid; place-items: center; min-height: 100vh; }
        .container { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        h2 { text-align: center; color: #333; }
        form div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        .captcha-box { display: flex; align-items: center; gap: 10px; }
        .captcha-img {
            background: #222; color: #fff; padding: 10px; border-radius: 4px; 
            font-size: 1.2rem; font-weight: bold; letter-spacing: 5px; 
            text-decoration: line-through;
        }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        button:hover { background: #0056b3; }
        .mensaje { padding: 10px; text-align: center; border-radius: 4px; margin-bottom: 15px; }
        .error { background: #f8d7da; color: #721c24; }
        .exito { background: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Registro de Usuario (Megared)</h2>

        <?php if (!empty($mensaje)): ?>
            <p class="mensaje <?php echo (strpos($mensaje, 'Error') !== false) ? 'error' : 'exito'; ?>">
                <?php echo $mensaje; ?>
            </p>
        <?php endif; ?>

        <form action="registro.php" method="POST">
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div>
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>
            </div>
            <div>
                <label for="cedula">Cédula:</label>
                <input type="text" id="cedula" name="cedula" required>
            </div>
            <div>
                <label for="genero">Género:</label>
                <select id="genero" name="genero" required>
                    <option value="">-- Seleccione --</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div>
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            
            <div>
                <label for="captcha">Código de Verificación:</label>
                <div class="captcha-box">
                    <span class="captcha-img"><?php echo $_SESSION['captcha_code']; ?></span>
                    <input type="text" id="captcha" name="captcha" required placeholder="Escriba el código">
                </div>
            </div>

            <button type="submit">Registrarse</button>
        </form>
    </div>

</body>
</html>