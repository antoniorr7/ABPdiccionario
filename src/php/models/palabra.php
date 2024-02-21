<?php
require_once 'models/conexion.php';

class Palabra extends Conexion {
    public function __construct() { 
        parent::__construct();
    }
    public function listarPalabras($idClase){
        $query = "SELECT p.idPalabra, p.palabra, t.idTraduccion, t.significados, c.nombreClase
                  FROM palabras p
                  LEFT JOIN traducciones t ON p.idPalabra = t.idPalabra
                  LEFT JOIN clase c ON p.idClase = c.id
                  WHERE p.idClase = $idClase";
    
        $resultado = $this->conexion->query($query); 
        $palabras = array(); // Inicializamos el arreglo de palabras
        while ($row = $resultado->fetch_assoc()) {
            $palabras[] = $row;
        }
        
        // Comprobar si el arreglo de palabras está vacío
        if(empty($palabras)) {
            return false; // Devolver false si no hay palabras
        } else {
            return $palabras; // Devolver el arreglo de palabras si hay palabras
        }
    }
    public function obtenerIdClase($idPalabra) {
        $query = "SELECT idClase FROM palabras WHERE idPalabra = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $idPalabra);
        $stmt->execute();
        $stmt->bind_result($idClase);
        $stmt->fetch();
        $stmt->close();
    
        return $idClase;
    }
    
    
    public function aniadirDatos($datos) {
        // Insertar la palabra
        $query = "INSERT INTO palabras (idClase, palabra) VALUES ({$datos['idClase']}, '{$datos['palabra']}');";
    
        // Obtener el ID de la palabra insertada
        $query .= "SET @idPalabra = LAST_INSERT_ID();";
    
        // Insertar las traducciones
        for ($i = 1; $i <= $datos['numTraducciones']; $i++) {
            $traduccion = $datos["traduccion".$i];
            $query .= "INSERT INTO traducciones (significados, idPalabra) VALUES ('$traduccion', @idPalabra);";
      
        }
    
     
        $this->conexion->multi_query($query);
    }
    
    public function eliminarPalabra($idPalabra) {

    $query = "DELETE FROM palabras WHERE idPalabra = ?";
    $stmt = $this->conexion->prepare($query);
    $stmt->bind_param("i", $idPalabra);
    $stmt->execute();
    $stmt->close();

    }

}
?>