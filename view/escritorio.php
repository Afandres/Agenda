<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'template/header.php';

if ($_SESSION['escritorio']==1)
{




?>
<br>
<br>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
      <?php
include_once '../model/conexionuno.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();


$consulta = "SELECT * FROM doctor";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$doctor = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
include "../model/config.php";

$sql = $con->query("SELECT * FROM usuario");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Agendamiento</title>
    <!--Iconos-->
    <link rel="apple-touch-icon" sizes="180x180" href="../img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon-16x16.png">
    <link rel="manifest" href="../img/site.webmanifest">

    <!--css-->
    <link rel="stylesheet" href="./css/style.css">

</head>
<body>
  <header>
    <div class="container_nav">
      <p class="logo">Agendamiento!</p>
      
    </div>
  </header>

 <div class="container">
  <div class="form">
  <form action="" method="post">
    <?php  foreach ($sql as $id){ ?>
      <input class="id_user" type="number" name="id" id="id" value="<?php echo $id['id'] ?>">
      <?php } ?>
    <label>#Documento usuario:</label>
    <input type="number" name="documento" id="documento">
    <label>Nombres usuario:</label>
    <input type="text" name="nom_user" id="nom_user">
    <label>Apellidos usuario:</label>
    <input type="text" name="apellido_user" id="apellido_user">
    <label>Teléfono:</label>
    <input type="number" name="phone" id="phone">
    <label>Correo:</label>
    <input type="email" name="email" id="email">
    <label>Nombres doctor:</label>
    <select class="form-control" name="doctor">
            <option id="doctor">---Seleccione---</option>
            <?php
              foreach ($doctor as $doc){
                ?>
                <option><?php echo $doc['id']?> <?php echo $doc['nombre']?> <?php echo $doc['apellido'] ?></option>
                <?php
              }
              ?>
          </select>       
    <label>Fecha:</label>
    <input type="date" name="fecha" id="fecha">
    <label>Hora:</label>
    <input type="time" name="hora" id="hora">

    <input class="boton" type="submit" name="agendar" value="Agendar">
    <?php
          $conexion = mysqli_connect('localhost', 'root', '', 'agendamiento');
          if(isset($_POST['agendar'])){
            $documento=$_POST['documento'];
            $nombre_user=$_POST['nom_user'];
            $apellido_user=$_POST['apellido_user'];
            $phone=$_POST['phone'];
            $email=$_POST['email'];
            $doctor=$_POST['doctor'];
            $fecha=$_POST['fecha'];
            $hora=$_POST['hora'];
              
            
            
            $query="INSERT INTO usuario(identificacion, nombre, apellido, telefono, correo) 
            VALUES ('$documento', '$nombre_user','$apellido_user','$phone', '$email')";

            if(mysqli_query($conexion, $query)){
              
              $id_usuario = mysqli_insert_id($conexion);

              $query2 = "SELECT COUNT(*) as count FROM citas WHERE fecha = '$fecha' AND hora = '$hora'";
              $res= mysqli_query($conexion, $query2);
              $row=mysqli_fetch_assoc($res);

              if($row['count'] == 0){
                $query2 = "INSERT INTO citas(fecha, hora, id_usuario, id_doctor) 
                VALUES ('$fecha', '$hora', '$id_usuario','$doctor')";
                  }
                  else{
                    
                    echo "<script> alert('Fecha y hora ya agendadas, agende cita en otro horario.')
                        location.href = '../vista/index.php';</script>";
              
              }
              if(mysqli_query($conexion,$query2)){
                echo "<script> alert('Registro exitoso')
                location.href = '../vista/index.php';</script>";
              }
              else{
                echo "Error: " . $query2 . "<br>" . mysqli_error($conexion);
              }
            } else {
              echo "Error: " . $query . "<br>" . mysqli_error($conexion);
            }
            
            
            mysqli_close($conexion);
              
            }
          ?>
  </form>
  

  </div>
 </div>


</body>
      </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
}

require 'template/footer.php';
?>

<?php 
}
ob_end_flush();
?>

