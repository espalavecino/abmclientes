<?php

if (file_exists("datos.txt")){
    $jsonClientes = file_get_contents("datos.txt");
    $aClientes = json_decode($jsonClientes, true);
        
} else {
    $aClientes = [];

}

$id = isset($_GET["id"]) ? $_GET["id"] : "";
$aMsg = array("mensaje" => "", "codigo" => "");


    if(isset($_GET["do"]) && $_GET["do"] == "eliminar"){
        if($aClientes[$id]["imagen"] != ""){
            unlink("files/" . $aClientes[$id]["imagen"]);
        }
        unset($aClientes[$id]);
        $jsonClientes = json_encode($aClientes);
        file_put_contents("datos.txt", $jsonClientes);
        $id="";
        $aMsg = array("mensaje" => "Eliminado correctamente", "codigo" => "warning");
    }


if ($_POST) {
    $dni= trim($_POST["txtDni"]);
    $nombre= trim($_POST["txtNombre"]);
    $telefono= trim($_POST["txtTelefono"]);
    $correo= trim($_POST["txtCorreo"]);
    $nombreImagen = "";



    if ($_FILES["archivo"]["error"] === UPLOAD_ERR_OK) {
        $nombreRandom = date("Ymdhmsi");
        $archivoTmp = $_FILES["archivo"]["tmp_name"];
        $nombreArchivo = $_FILES["archivo"]["name"];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreImagen = "$nombreRandom.$extension";
        move_uploaded_file($archivoTmp, "files/$nombreImagen");

    } 

    if(isset($_GET["id"])) {

        $imagenPrevia = $aClientes[$id]["imagen"];

        if ($_FILES["archivo"]["error"] === UPLOAD_ERR_OK) {
            if($imagenPrevia != ""){
                unlink("files/$imagenPrevia");
            }
        }

        if ($_FILES["archivo"]["error"] !== UPLOAD_ERR_OK) {
            $nombreImagen = $imagenPrevia;
        }      
    }
   

    if(isset($_GET["id"]) && isset($_GET["id"]) >= 0) {
        $aClientes[$id] = array ("dni" => $dni,
                        "nombre" => $nombre,
                        "telefono" => $telefono,
                        "correo" => $correo,
                        "imagen" => $nombreImagen);
                        $aMsg = array("mensaje" => "Editado correctamente", "codigo" => "primary");
    } else {
        $aClientes[] = array ("dni" => $dni,
                        "nombre" => $nombre,
                        "telefono" => $telefono,
                        "correo" => $correo,
                        "imagen" => $nombreImagen);
                        $aMsg = array("mensaje" => "Cargado correctamente", "codigo" => "success");
                       
    }

   $jsonClientes = json_encode($aClientes);
   file_put_contents("datos.txt", $jsonClientes);
}


?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de clientes</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
        <link rel="stylesheet" href="CSS/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="CSS/fontawesome/css/fontawesome.min.css"> 
        <link rel="stylesheet" href="css/estilos.css">      
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">         
                <div class="col-12 text-center py-3">
                    <h1>Registro de clientes</h1>
                </div>
            </div>
            <?php if (isset($aMsg) != ""): ?>
                    <div class="row">
                        <div class="col-6">
                            <div class="alert alert-<?php echo $aMsg["codigo"]; ?>" role="alert">
                                <?php echo $aMsg["mensaje"]; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <div class="row">
                <div class="col-12 col-sm-6">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <labe for="txtDni">DNI:</label>
                                <input type="text" id="txtDni" name="txtDni" class="form-control"  required value="<?php echo isset($aClientes[$id])? $aClientes[$id]["dni"] : ""; ?>">
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="txtNombre">Nombre:</label>
                                <input type="text" id="txtNombre" name="txtNombre" class="form-control" required value="<?php echo isset($aClientes[$id])? $aClientes[$id]["nombre"] : ""; ?>">
                            </div>                              
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for ="txtTelefono">Teléfono:</label>
                                <input type="text" id="txtTelefono" name="txtTelefono" class="form-control" required value="<?php echo isset($aClientes[$id])? $aClientes[$id]["telefono"] : ""; ?>">
                            </div>                               
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for ="txtCorreo">Correo:</label>
                                <input type="email" id="txtCorreo" name="txtCorreo" class="form-control" required value="<?php echo isset($aClientes[$id])? $aClientes[$id]["correo"] : ""; ?>">
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for ="txtCorreo">Archivo adjunto:</label>
                                <input type="file" id="archivo" name="archivo" class="form-control-file">
                            </div>                               
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" id="btnGuardar" name="btnGuardar" class="btn btn-primary">GUARDAR</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6 col-12">
                    <table class="table table-hover border">
                        <tr>
                            <th>Imagen</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Acciones</th>                            
                        </tr> 
                        <?php foreach ($aClientes as $id => $cliente): ?>
                            <tr>
                                <td><img src="files/<?php echo $cliente["imagen"]; ?>" alt="imagen" class="img-thumbnail"></td>
                                <td><?php echo $cliente["dni"]; ?></td> 
                                <td><?php echo $cliente["nombre"]; ?></td>
                                <td><?php echo $cliente["correo"]; ?></td>
                                <td style="width: 110px;">
                                    <a href="index.php? id=<?php echo $id; ?>"><i class="fas fa-edit"></i></a>
                                    <a href="index.php? id=<?php echo $id; ?>&do=eliminar"><i class="fas fa-trash-alt"></i></a>
                                </td>                              
                            </tr>
                        <?php endforeach; ?>                                                         
                    </table>                    
                    <a href="index.php"><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>             
    </body>
</html>