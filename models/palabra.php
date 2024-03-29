<?php
require_once 'models/conexion.php';

class Palabra extends Conexion {
    public function __construct() { 
        parent::__construct();
    }
    public function listarPalabras($codigo){
        $query = "SELECT p.idPalabra, p.palabra, t.idTraduccion, t.significados, c.nombreClase,p.audio
        FROM palabras p
        LEFT JOIN traducciones t ON p.idPalabra = t.idPalabra
        LEFT JOIN clase c ON p.idClase = c.id
        WHERE c.codigo = ?";
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            $palabras = array(); // Inicializamos el array de palabras
            while ($row = $resultado->fetch_assoc()) {
            $palabras[] = $row;
            }

            // Comprobar si el array de palabras está vacío
            if (empty($palabras)) {
            $mensajeError = '<h1>No hay palabras asociadas a esta clase</h1>';
            return ['mensaje' => $mensajeError];
            } else {
            return $palabras; // Devolver el array de palabras si hay palabras
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
    
    public function aniadirPalabra($datos) {

        // Insertar la palabra
       $palabra = $datos['palabra'];
       $audio = $datos['audio'];
       $idClase = $datos['idClase'];
       
       $query = "INSERT INTO palabras (idClase, palabra, audio) VALUES ('$idClase', '$palabra', '$audio')";
       
       $this->conexion->query($query);
       
       // Obtener el ID de la palabra insertada
       $idPalabra = $this->conexion->insert_id;
   
       return $idPalabra;
   }
   
   public function aniadirTraducciones($idPalabra, $datos) {
       
       // Insertar las traducciones
       for ($i = 1; $i <= $datos['numTraducciones']; $i++) {
           $traduccion = $datos["traduccion".$i];
           $query = "INSERT INTO traducciones (significados, idPalabra) VALUES ('$traduccion', '$idPalabra')";
           $this->conexion->query($query);
       }
   }
    public function eliminarPalabra($idPalabra) {

    $query = "DELETE FROM palabras WHERE idPalabra = ?";
    $stmt = $this->conexion->prepare($query);
    $stmt->bind_param("i", $idPalabra);
    $stmt->execute();
    $stmt->close();
   
    }
    public function obtenerPalabra($idPalabra) {
        $palabra = array(); // Definimos el array antes del bucle while
        $query = "SELECT p.idPalabra, p.palabra, t.idTraduccion, t.significados, p.audio
                  FROM palabras p
                  JOIN traducciones t ON p.idPalabra = t.idPalabra
                  WHERE p.idPalabra = ".$idPalabra;
    
        $resultado = $this->conexion->query($query);
        while ($fila = $resultado->fetch_assoc()) {
            $palabra[] = $fila; // Añadimos cada fila al array $palabra
        }
        return $palabra; 
    }
    
public function editarPalabra($datos){
    $query = "UPDATE palabras SET palabra = ?";
  
    if (!empty($datos['audio'])) {
        $query .= ", audio = ?";

    }
    $query .= " WHERE idPalabra = ?";
  
    $stmt = $this->conexion->prepare($query);
    if (!empty($datos['audio'])) {
        $stmt->bind_param("ssi", $datos['palabra'],$datos['audio'],$datos['idPalabra']);
      
    }else{ 
        $stmt->bind_param("si", $datos['palabra'],$datos['idPalabra']);
    }
    $stmt->execute();
    $stmt->close();
    $query = "UPDATE traducciones SET significados = ? WHERE idTraduccion = ?";
    $stmt = $this->conexion->prepare($query);
    foreach ($datos['idTraduccion'] as $id => $idTraduccion) {
        $stmt->bind_param("si", $datos['traduccion'][$id], $idTraduccion);
        $stmt->execute();
    }
    $stmt->close();
}




public function eliminarTraduccion($idTraduccion){
    $query = "DELETE FROM traducciones WHERE idTraduccion = ?";
    $stmt = $this->conexion->prepare($query);
    $stmt->bind_param("i", $idTraduccion);
    $stmt->execute();
    $stmt->close();
}
public function aniadirTraduccion($idPalabra){
    $query = "INSERT INTO traducciones (significados, idPalabra) VALUES ('', ?)";
    $stmt = $this->conexion->prepare($query);
    $stmt->bind_param("i", $idPalabra);
    $stmt->execute();
    $stmt->close();
}
public function aniadirTraduccionV($idPalabra){
    $query = "INSERT INTO traducciones (significados, idPalabra) VALUES (NULL, ?)";
    $stmt = $this->conexion->prepare($query);
    $stmt->bind_param("i", $idPalabra);
    $stmt->execute();
    $stmt->close();
}
public function buscarPalabras($palabra, $codigo){
    
    $palabra = $this->conexion->real_escape_string($palabra);

    // Construct the query using prepared statements
    $query = "SELECT p.idPalabra, p.palabra, t.idTraduccion, t.significados, c.nombreClase, p.audio
              FROM palabras p
              LEFT JOIN traducciones t ON p.idPalabra = t.idPalabra
              LEFT JOIN clase c ON p.idClase = c.id
              WHERE p.palabra LIKE ? AND c.codigo = ?";

   
    $stmt = $this->conexion->prepare($query);

    
    if (!$stmt) {
        $mensajeError = '<h1>Error en la preparación de la consulta</h1>';
        return ['mensaje' => $mensajeError];
    }


    $palabra_like = "%" . $palabra . "%";
    $stmt->bind_param("ss", $palabra_like, $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $palabras = array();

    while ($row = $resultado->fetch_assoc()) {
        $palabras[] = $row;
    }

   
    $stmt->close();

    if (empty($palabras)) {
        $mensajeError = '<h1>No hay palabras asociadas a esta clase que coincidan con la búsqueda</h1>';
        return ['mensaje' => $mensajeError];
    } else {
        return $palabras; 
    }
}


}
