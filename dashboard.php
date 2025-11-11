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
    width: 200px;
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
                    <li class="active"><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="#"><i class="fas fa-list"></i> Lista de entradas</a></li>
                    <li><a href="#"><i class="fas fa-comments"></i> Charlar</a></li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> Calendario</a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
            <div class="user-profile">
                <i class="fas fa-user-circle user-icon"></i> <span><?php echo $nombre_completo; ?></span>
            </div>
            
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
                <h2>¡Buenos días, <?php echo htmlspecialchars($nombre_usuario); ?>!</h2>

                <div class="stats-cards">
                    <div class="card">
                        <h3>Nuevos clientes</h3>
                        <p>300</p>
                        <span>+18.33%</span>
                    </div>
                    <div class="card">
                        <h3>Ganancias</h3>
                        <p>$18,306</p>
                        <span>+ info</span>
                    </div>
                    <div class="card">
                        <h3>Nuevos proyectos</h3>
                        <p>1538</p>
                        <span>-1.33%</span>
                    </div>
                    <div class="card">
                        <h3>Proyectos</h3>
                        <p>864</p>
                        <span>+ info</span>
                    </div>
                </div>

                <div class="chart-cards">
                    <div class="card-large">
                        <h3>Ventas totales</h3>
                        <div class="placeholder-chart">[Gráfica de Ventas]</div>
                    </div>
                    <div class="card-large">
                        <h3>Utilidad neta</h3>
                        <div class="placeholder-chart">[Gráfica de Utilidad]</div>
                    </div>
                </div>

            </section>
        </main>
    </div>

</body>
</html>