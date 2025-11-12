<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    
    exit();
}

$nombre_usuario = $_SESSION['usuario_nombre'];
$apellidos_usuario = $_SESSION['usuario_apellidos'];
$nombre_completo = htmlspecialchars($nombre_usuario . " " . $apellidos_usuario);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Megared</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        .logo-megared {
            width: 300px;
            height: auto;
            margin-top: 10px;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f4f7fa; 
            color: #333;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #ffffff; 
            color: #333;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sidebar-header h2 {
            color: #333;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 1rem;
        }
        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }
        .sidebar-nav li a:hover{
            background-color: #f4f4f4;
            color: #111;
        }

        .sidebar-nav li.active a {
            background-color: #009900;
            color: #fff;
            font-weight: 600;
        }
        .sidebar-nav li.active a i {
            color: #fff;
        }

        .sidebar-nav li a {
            color: #555;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-nav li a:hover i {
            color: #111;
        }

        .sidebar-nav li i {
            margin-right: 12px;
            font-size: 1.1rem;
            color: #888;
            transition: color 0.2s;
        }

        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 2rem;
            background-color: #fff;
            border-bottom: 1px solid #e0e0e0;
            height: 75px;
        }

        .search-bar {
            position: relative;
        }
        .search-bar input {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-info span {
            font-weight: 500;
        }
        .content-body {
            padding: 2rem;
        }
        .content-body h2 {
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .card h3 {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 0.5rem;
        }
        .card p {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .card span {
            font-weight: 500;
            color: #009900;
        }

        .chart-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .card-large {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .card-large h3 {
            margin-bottom: 1rem;
        }
        .placeholder-chart {
            height: 250px;
            display: grid;
            place-items: center;
            background: #f9f9f9;
            color: #aaa;
            border-radius: 4px;
        }

        .sidebar-footer {
            display: flex;
            flex-direction: column; 
            align-items: center;   
            padding: 1.5rem 0;    
            border-top: 1px solid #e0e0e0;
        }

        .user-icon-footer {
            font-size: 2.8rem; 
            color: #555;
            margin-bottom: 0.5rem;
        }

        .user-name-footer {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
            margin-bottom: 1rem; 
        }

        .logout-button {
            display: flex;          
            align-items: center;    
            justify-content: center; 

            width: 40px;
            height: 40px;
            background-color: #f4f4f4;
            border: none;
            border-radius: 50%;     
            text-decoration: none;
            transition: background-color 0.2s, color 0.2s;
        }

        .logout-button i {
            color: #555;
            font-size: 1rem; 
            margin: 0;
            transition: color 0.2s;
        }

        .logout-button:hover {
            background-color: #dc3545;
            cursor: pointer;
        }

        .logout-button:hover i {
            color: #fff;
        }

        .sidebar-nav {
            flex-grow: 1;
        }

        .user-table-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-top: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: #f9f9f9;
            font-weight: 600;
            color: #555;
            padding: 1rem;
            text-align: left;
        }

        table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        table tr:hover {
            background-color: #f4f7fa;
        }

        table tr:last-child td {
            border-bottom: none;
        }

    </style>
</head>
<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="imagen/megared-logo.png" alt="Logo Megared" class="logo-megared">
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    
                    <li><a href="#"><i class="fas fa-list"></i> Lista de entradas</a></li>
                    <li><a href="#"><i class="fas fa-comments"></i> Mapa</a></li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> Calendario</a></li>
                    
                    <li class="active"><a href="usuarios.php"><i class="fas fa-users"></i> Ver Usuarios</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">

            <i class="fas fa-user-circle user-icon-footer"></i> 

            <span class="user-name-footer"><?php echo $nombre_completo; ?></span>

            <a href="logout.php" class="logout-button" title="Cerrar Sesión">
                <i class="fas fa-sign-out-alt"></i>
            </a>

        </div>
        </aside>

        <main class="main-content">
            
            <header class="main-header">
                <div class="search-bar">
                    <input type="text" placeholder="Buscar...">
                    <i class="fas fa-search"></i>
                </div>
            </header>

            <section class="content-body">
                <h2 data-aos="fade-down">Lista de usuarios</h2>

                <?php
                require_once 'db-connect.php';

                $sql = "SELECT id, nombre, apellidos, correo, cedula, genero, fecha_registro FROM usuarios ORDER BY fecha_registro DESC";

                $result = $conn->query($sql);
                $usuarios = [];

                if ($result && $result->num_rows > 0) {
                    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
                }
                $conn->close();

                ?>

                <div class="user-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Correo Electrónico</th>
                                <th>Cédula</th>
                                <th>Género</th>
                                <th>Fecha de Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($usuarios)): ?>
                                <tr>
                                    <td colspan="6">No hay usuarios registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['apellidos']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['cedula']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['genero']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </section>
        </main>
    </div>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script src="js/main.js"></script>

</body>
</html>