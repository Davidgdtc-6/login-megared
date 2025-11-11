<?php
session_start();

require_once 'db-connect.php';

$mensaje_registro = "";
$mensaje_login = "";

if (empty($_SESSION['captcha_code'])) {
    $str_captcha = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $str_captcha = str_shuffle($str_captcha);
    $_SESSION['captcha_code'] = substr($str_captcha, 0, 5);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['accion']) && $_POST['accion'] == 'registro') {
        
        $nombre = trim($_POST['nombre']);
        $apellidos = trim($_POST['apellidos']);
        $cedula = trim($_POST['cedula']);
        $genero = $_POST['genero'];
        $correo = trim($_POST['correo']);
        $contrasena = $_POST['contrasena'];
        $captcha_usuario = trim($_POST['captcha']);

        if (empty($nombre) || empty($apellidos) || empty($cedula) || empty($genero) || empty($correo) || empty($contrasena) || empty($captcha_usuario)) {
            $mensaje_registro = "Error: Todos los campos son obligatorios.";
        } 
        elseif (strtoupper($captcha_usuario) != $_SESSION['captcha_code']) {
            $mensaje_registro = "Error: El código Captcha es incorrecto.";
            unset($_SESSION['captcha_code']);
        } 
        elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $mensaje_registro = "Error: El formato del correo no es válido.";
        } 
        else {
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ? OR cedula = ?");
            $stmt->bind_param("ss", $correo, $cedula);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $mensaje_registro = "Error: El correo o la cédula ya están registrados.";
                $stmt->close();
            } else {
                $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
                $stmt_insert = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, cedula, genero, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("ssssss", $nombre, $apellidos, $cedula, $genero, $correo, $hash_contrasena);

                if ($stmt_insert->execute()) {
                    $mensaje_registro = "¡Registro exitoso! Ya puedes iniciar sesión.";
                    unset($_SESSION['captcha_code']);
                } else {
                    $mensaje_registro = "Error: Ocurrió un problema al registrar. " . $stmt_insert->error;
                }
                $stmt_insert->close();
            }
        }
    }

    if (isset($_POST['accion']) && $_POST['accion'] == 'login') {
        
        $correo = trim($_POST['correo']);
        $contrasena = $_POST['contrasena'];

        if (empty($correo) || empty($contrasena)) {
            $mensaje_login = "Error: Correo y contraseña son requeridos.";
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
                    $mensaje_login = "Error: Correo o contraseña incorrectos.";
                }
            } else {
                $mensaje_login = "Error: Correo o contraseña incorrectos.";
            }
            $stmt->close();
        }
    }

    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Megared</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f6f5f7;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 100vh;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        form {
            background-color: #fff;
            display: flex;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            justify-content: center;
            text-align: center;
        }

        h1 {
            font-weight: bold;
            margin-bottom: 1.5rem;
            color: #333;
        }

        form div {
            margin-bottom: 0.8rem;
            text-align: left;
        }

        label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        input:focus, select:focus {
            outline-color: #009900;
        }

        .captcha-box { display: flex; gap: 10px; }
        .captcha-img {
            background: #eee; color: #333; padding: 10px; border-radius: 5px; 
            font-size: 1rem; font-weight: bold; letter-spacing: 3px; 
            user-select: none; width: 120px; text-align: center;
        }
        
        button {
            border-radius: 20px;
            border: 1px solid #009900;
            background: #009900;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            padding: 12px 45px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: transform 80ms ease-in;
            cursor: pointer;
            margin-top: 1rem;
        }

        button:active {
            transform: scale(0.95);
        }
        button:focus {
            outline: none;
        }
        
        button.ghost {
            background: transparent;
            border-color: #fff;
        }
        
        .social-container {
            margin: 10px 0;
        }
        .social-container a {
            border: 1px solid #ddd;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 5px;
            height: 40px;
            width: 40px;
            color: #333;
            text-decoration: none;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            z-index: 1;
            opacity: 0;
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .overlay {
            background: #009900;
            background: linear-gradient(to right, #007700, #00bb00);
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-panel {
            position: absolute;
            top: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 40px;
            height: 100%;
            width: 50%;
            text-align: center;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }
        
        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }
        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        @keyframes show {
            0%, 49.99% { opacity: 0; z-index: 1; }
            50%, 100% { opacity: 1; z-index: 5; }
        }
        
        .mensaje {
            padding: 8px; font-size: 0.9rem; text-align: center; border-radius: 5px;
            margin-bottom: 10px; font-weight: 500;
        }
        .error { background: #f8d7da; color: #721c24; }
        .exito { background: #d4edda; color: #155724; }

        .megared-logo {
            width: 150px;
            height: auto;
            margin-bottom: 1rem;
            align-self: center; 
}

    </style>
</head>
<body>

    <div class="container" id="container">

        <div class="form-container sign-up-container">
            <form action="index.php" method="POST">
                <img src="imagen/megared-logo.png" alt="Logo Megared" class="logo-megared">

                <h1 data-aos="fade-down">Crear Cuenta</h1>
                
                <?php if (!empty($mensaje_registro)): ?>
                    <p class="mensaje <?php echo (strpos($mensaje_registro, 'Error') !== false) ? 'error' : 'exito'; ?>">
                        <?php echo $mensaje_registro; ?>
                    </p>
                <?php endif; ?>
                
                <div data-aos="fade-left">
                    <label for="reg_nombre">Nombres</label>
                    <input type="text" id="reg_nombre" name="nombre" required placeholder="Escribe tus nombres"/>
                </div>
                <div data-aos="fade-left">
                    <label for="reg_apellidos">Apellidos</label>
                    <input type="text" id="reg_apellidos" name="apellidos" required placeholder="Escribe tus apellidos"/>
                </div>
                <div data-aos="fade-left">
                    <label for="reg_cedula">Cédula</label>
                    <input type="text" id="reg_cedula" name="cedula" required placeholder="Tu número de cédula"/>
                </div>
                <div data-aos="fade-left">
                    <label for="reg_genero">Género</label>
                    <select id="reg_genero" name="genero" required>
                        <option value="">-- Seleccione --</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div data-aos="fade-left">
                    <label for="reg_correo">Correo</label>
                    <input type="email" id="reg_correo" name="correo" required placeholder="ejemplo@correo.com"/>
                </div>
                <div data-aos="fade-left">
                    <label for="reg_pass">Contraseña</label>
                    <input type="password" id="reg_pass" name="contrasena" required placeholder="Crea una contraseña segura"/>
                </div>
                <div data-aos="fade-left">
                    <label for="reg_captcha">Código Captcha</label>
                    <div class="captcha-box">
                        <span class="captcha-img"><?php echo $_SESSION['captcha_code']; ?></span>
                        <input type="text" id="reg_captcha" name="captcha" required placeholder="Escribe el código"/>
                    </div>
                </div>
                <input type="hidden" name="accion" value="registro">
                <button type="submit">Registrarse</button>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form action="index.php" method="POST">
                <img src="imagen/megared-logo.png" alt="Logo Megared" class="logo-megared">        
                    
                <h1 data-aos="fade-down">Iniciar Sesión</h1>
                
                <?php if (!empty($mensaje_login)): ?>
                    <p class="mensaje error"><?php echo $mensaje_login; ?></p>
                <?php endif; ?>
                
                <div data-aos="fade-right" data-aos-delay="100">
                    <label for="log_correo">Correo</albel>
                    <input type="email" id="log_correo" name="correo" required placeholder="ejemplo@correo.com"/>
                </div>
                <div data-aos="fade-right" data-aos-delay="200">
                    <label for="log_pass">Contraseña</label>
                    <input type="password" id="log_pass" name="contrasena" required placeholder="Escribe tu contraseña"/>
                </div>
                <input type="hidden" name="accion" value="login">
                <button type="submit">Entrar</button>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 data-aos="fade-zoom-in">¡Bienvenido de Nuevo!</h1>
                    <p data-aos="fade-zoom-in" data-aos-delay="100">Para mantenerte conectado, inicia sesión con tu cuenta.</p>
                    <button class="ghost" id="signIn">Iniciar Sesión</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1 data-aos="fade-zoom-in">¡Bienvenido a tu portal MEGAred!</h1>
                    <p data-aos="fade-zoom-in" data-aos-delay="100">Ingresa tus datos personales.</p>
                    <button class="ghost" id="signUp">Registrarse</button>
                </div>
            </div>
        </div>

    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
            AOS.init();

        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });

        <?php

        if (!empty($mensaje_registro)) {
            echo "container.classList.add('right-panel-active');";
        }
        ?>
    </script>

</body>
</html>