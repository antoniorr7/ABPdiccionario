<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>Diccionario</title>
</head>
<body>
<nav>
<div>
      <form action="index.php?action=buscarPalabra&controller=palabra" method="POST">
        <input type="text" id="busqueda" name="busqueda" placeholder="Buscar palabras...">
      </form>
    </div>
    <div>
      <h1><a href="index.php?action=inicio&controller=clase">Diccionario</a></h1>
    </div>
    <div>
      <ul>
      
        <li><a href="index.php?action=listarClases&controller=clase">Clases</a></li>
        <!-- <li><a href="#">Palabras</a></li> -->
        <li><a href="index.php?action=cerrarSesion&controller=login">Cerrar Sesión</a></li>
      
        <li><h3>¡Bienvenido <?php echo $_SESSION['usuario']?>!</h3></li>
        <li><a href="index.php?controller=login&action=mostrarRegistro" class="register-link">Regístrar nuevo admin</a></li>
      </ul>
      
    </div>
  
  </nav>