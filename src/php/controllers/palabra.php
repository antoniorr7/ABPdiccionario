<?php
class Controladorpalabra{
    public $pagina;
    public $view;
    private $objPalabra;
    public function __construct() {
        require_once 'models/palabra.php';
        $this->view = '';
        $this->objPalabra = new Palabra();
    }
  public function listarPalabras(){
        $this->view = 'palabra';
        return $this->objPalabra->listarPalabras($_GET['idClase']);
    }
    public function aniadirPalabra(){
        $this->view = 'aniadirpalabra';
    }
    public function aniadirTraducciones(){
        $this->view = 'aniadirtraducciones';
       print("<pre>".print_r($_POST,true)."</pre>");
    }
    public function guardarPalabra(){
        $this->view = ' ';
        print("<pre>".print_r($_POST,true)."</pre>");
        $this->objPalabra->aniadirDatos($_POST);
       
        header("Location: index.php?controller=palabra&action=listarPalabras&idClase=".$_POST['idClase']);
    }
 
    public function eliminarPalabra(){
        $this->view = 'eliminarpalabra';
        $idClase=$this->objPalabra->obtenerIdClase($_GET['idPalabra']);
            if (isset($_POST['confirmarBorrado']) && $_POST['confirmarBorrado'] === 'si') {
             
                    $this->objPalabra->eliminarPalabra($_GET['idPalabra']);
                    header("Location: index.php?controller=palabra&action=listarPalabras&idClase=".$idClase);

            }
            if (isset($_POST['confirmarBorrado']) && $_POST['confirmarBorrado'] === 'no') {
                header("Location: index.php?controller=palabra&action=listarPalabras&idClase=".$idClase);
            }

    }
    public function rellenarEditar(){
        $this->view = 'editarpalabra';
        
        return $this->objPalabra->obtenerPalabra($_GET['idPalabra']);

    }
    public function editarPalabra(){
        $idClase=$this->objPalabra->obtenerIdClase($_GET['idPalabra']);
        $this->objPalabra->editarPalabra($_POST);
        header("Location: index.php?controller=palabra&action=listarPalabras&idClase=".$idClase);
    }
    public function eliminarTraduccion(){
        $this->view = 'editarpalabra';
        
         $this->objPalabra->eliminarTraduccion($_GET['idTraduccion']);
         header("Location: index.php?controller=palabra&action=rellenarEditar&idPalabra=".$_GET['idPalabra']);
    }
   public function aniadirTraduccion(){
        $this->view = 'editarpalabra';

         $this->objPalabra->aniadirTraduccion($_GET['idPalabra']);
         header("Location: index.php?controller=palabra&action=rellenarEditar&idPalabra=".$_GET['idPalabra']);
    }
}
