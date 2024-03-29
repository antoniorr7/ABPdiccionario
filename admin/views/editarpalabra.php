<div class="popup">
  <form id="traductor-form" method="post" action="index.php?action=editarPalabra&controller=palabra&idPalabra=<?php echo $_GET['idPalabra']; ?>" enctype="multipart/form-data">
    <?php foreach ($retornado as $index => $data): ?>
      <?php if ($index === 0): ?>
        <input type="hidden" name="idPalabra" value="<?php echo $data['idPalabra']; ?>">
        <div class="input-container">
          <label for="palabra">Palabra:</label>
          <input type="text" id="palabra" name="palabra" value="<?php echo $data['palabra']; ?>">
        </div>
        <label for="audio">Archivo de Audio:</label>
      

    
        <?php
        
          if(isset($data['audio']) && !empty($data['audio'])) {
            $audio_decoded = base64_decode($data['audio']);
            $audio_data_uri = 'data:audio/mpeg;base64,' . base64_encode($audio_decoded);
            echo '<div class=audio-container-e>';
            echo '<audio controls style="width: 100%;">
  
                    <source src="' . $audio_data_uri . '" type="audio/mpeg">
                  </audio>
                  </div>';
                  echo '<a id="eliminar" href="index.php?action=eliminarAudio&controller=palabra&idPalabra=' . $data['idPalabra'] . '&idClase=' . $_GET['idClase'] . '"><img src="../img/borrar.png" alt="eliminar"></a>';
                  echo '<br>';
                  echo '<br>';
                  echo '<br>';
                  echo '<label for="audio">Audio si quieres editar el existente:</label>';
        } 
      
        echo '    <input type="file" id="audio" name="audio" accept="audio/*" >';
        ?>
        
        <label for="traduccion">Traducciones:</label>
      <?php endif; ?>
     <input type= hidden name=idClase value=<?php echo $_GET['idClase']; ?>>
      <div class="input-container" id="traducciones-container">
        <input type="hidden" name="idTraduccion[]" value="<?php echo $data['idTraduccion']; ?>">
        <input type="text" id="traduccion" name="traduccion[]" value="<?php echo isset($data['significados']) ? $data['significados'] : ''; ?>">
        <?php if (count($retornado) > 1): ?>
          <a href="index.php?action=eliminarTraduccion&controller=palabra&idTraduccion=<?php echo $data['idTraduccion']; ?>&idPalabra=<?php echo $data['idPalabra']; ?>&idClase=<?php echo $_GET['idClase']; ?>">eliminar</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <input type="submit" id="editar" value="Guardar">
    <a href="index.php?action=aniadirTraduccion&controller=palabra&idPalabra=<?php echo $_GET['idPalabra']; ?>&idClase=<?php echo $_GET['idClase']; ?>">añadir Traducción</a>
  </form>
  <a  href="index.php?action=listarPalabras&controller=palabra&idClase=<?php echo $_GET['idClase']; ?>"><img id='atras' src="../img/flecha-izquierda.png" alt=""></a>
</div>
