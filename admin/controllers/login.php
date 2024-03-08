<?php
class Controladorlogin
{
    public $pagina;
    public $view;
    private $modeloLogin;
    public function __construct()
    {
        require_once 'models/login.php';
        $this->view = '';
        $this->modeloLogin = new Login();

    }
    public function mostrarLogin()
    {
        $this->view = 'login';
        $this->comprobarInstalacion();
    }
    public function IniciarSesion()
    {
        session_start();

        // Verificar si nombre de usuario o contraseña están vacíos
        if (empty($_POST['nombreUsuario']) || empty($_POST['contrasena'])) {
            // Nombre de usuario o contraseña vacíos, redirigir a login
            header('Location: index.php');
            return;
        }

        $nombreUsuario = $_POST['nombreUsuario'];
        $_SESSION['usuario'] = $nombreUsuario;
        $contrasena = $_POST['contrasena'];

        $resultado = $this->modeloLogin->iniciarSesion($nombreUsuario, $contrasena);




        // print("<pre>".print_r($resultado,true)."</pre>");
        if ($resultado !== false) {
            // Inicio de sesión exitoso, obtener los datos del usuario
            $datosUsuario = $resultado;

            // Verificar la contraseña utilizando password_verify
            if (password_verify($contrasena, $datosUsuario['contrasena'])) {
                $_SESSION['idUsuario'] = $resultado['idUsuario'];
                header('Location: index.php?controller=clase&action=inicio');

            }
        } else {

            $this->view = 'login';
            return ['mensaje' => 'Contraseña Incorrecta'];

        }
    }
    public function cerrarSesion()
    {
        session_start();
        $_SESSION = array(); // Limpiar todas las variables de sesión
        session_destroy(); // Destruir la sesión
        header('Location: index.php');
        exit();
    }


    public function mostrarRegistro()
    {
        $this->view = 'registro';
    }

    public function crearUsuario()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre = $_POST["nombreUsuario"];
            $contrasena = $_POST["contrasena"];

            // Validar longitud máxima de usuario y contraseña
            if (strlen($nombre) > 50 || strlen($contrasena) > 50) {
                // Si la longitud excede, mostrar un mensaje de error
                $this->view = 'registro';
                return '<p>El nombre de usuario y la contraseña no pueden exceder los 50 caracteres.</p>';
            }

            $resultado = $this->modeloLogin->crearUsuario($nombre, $contrasena);

            if ($resultado == true) {
                $this->view = 'login';
            } else {
                $this->view = 'registro';
                return '<p>Error al crear el Usuario.</p>';
            }
        }
    }

    public function comprobarSesion()
    {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php');
        }
    }

    public function comprobarInstalacion()
    {
        if ($this->modeloLogin->instalacion() === false) {
            $this->view = 'registroa';

        }
    }
}
