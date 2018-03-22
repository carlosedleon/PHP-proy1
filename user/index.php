<?php

require'../class/sessions.php';
$objses = new Sessions();
$objses->init();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;

if($user == ''){
    header('Location: ../index.php?error=2');
}

function CrearTabla($sesion_eventos)
{
    $table_str='';
    foreach ($sesion_eventos as $obj_key => $obj){ 
        $table_str.='<tr id="'.$obj_key.'">';
        while(list ($key, $value)=each ($obj)){
            $table_str.='<td>'.$value.'</td>';
        }
        $table_str.='<td><form method="post">
        <button type="submit" name="eliminar" value="'.$obj_key.'">Eliminar</button>
        </td>';
        $table_str.='</form></tr>';      
    }
    return $table_str; 
}

function obtenerIndices($user)
{
    $filename = "./$user/indices.txt";
    $handle2 = file($filename);
    $indices=[];
    while(list($var, $val) = each($handle2)){ 
        $val = explode("|", trim($val));
        $indices[]=$val; 
    }
    closedir($handle2);
    return $indices;
}
function obtenerDetalles($user){
    $filename = "./$user/detalles.txt";
    $handle2 = fopen($filename, "r+");
    print "<br><br>";
    $indices=obtenerIndices($user); // se obtienen nombre e indice o cant de car en detalle
    $detalleCad="";
    $acumSeekInd=0;
    $detalles=[];
    while(list($var, $val) = each($indices)){ // var =0, val = test.txt|68
    fseek($handle2, 0); // detalles.txt
    if ($var==0) {
        $detalleCad=trim(fread($handle2, $val[1]));
        $detalles[] = explode('|', $detalleCad);
    }else{		        	
    fseek($handle2, $acumSeekInd+2); // detalles.txt
    $detalleCad=trim(fread($handle2, $val[1]));
    $detalles[] = explode('|', $detalleCad);
    }
    $acumSeekInd+=$val[1];
    }
    closedir($handle2);
    return $detalles;
}

function borrar($user, $nom){
    $indices = obtenerIndices($user); // indices = []
    $detalles= obtenerDetalles($user);
    $posNom=-1;
    while(list($var, $val) = each($indices)){ // var =0, val = 68
        if ($val[0]==$nom) {
            $posNom=$var;
        }
    }
    unset($indices[$posNom]);
    unset($detalles[$posNom]);

    $filename = "./$user/indices.txt";
    $handle2 = fopen($filename, "w");
    foreach ($indices as $key => $value) {
        print " $key: $value[0] $value[1] <br>";
        if ($key==0 && $value[0]=='') {
            $value= implode('|', $value);
            fwrite($handle2, $value);
        }else{
            $value= implode('|', $value);
            fwrite($handle2, "\r\n".$value);
        }
    }
    closedir($handle2);

    $filename = "./$user/detalles.txt";
    $handle2 = fopen($filename, "w");
    while(list($var, $val) = each($detalles)){ // var =0, val = 68
        if ($key==0 && $value[0]=='') {
            $val= implode('|', $val);
            fwrite($handle2, $val);
        }else{
            $val= implode('|', $val);
            fwrite($handle2, "\r\n".$val);
        }
    }
    closedir($handle2);
}
function existe($user, $nomArchivo){
    $indices = obtenerIndices($user); // indices = []
    while(list($var, $val) = each($indices)){ // var =0, val = 68
        if ($val[0]==$nomArchivo) {
            return true;
        }
    }
}
function agregar($user, $nom, $autor, $fecha, $tam, $usuario, $descripcion, $clasificacion){
    $indices = obtenerIndices($user); // indices = []

    $filename = "./$user/detalles.txt";
    $handle2 = fopen($filename, "a");
    $val = $nom.'|'.$autor.'|'.$fecha.'|'.$tam.'|'.$usuario.'|'.$descripcion.'|'.$clasificacion;
    if (count($indices)>0) {
        $bytes= fwrite($handle2, "\r\n".$val);
    }else{
        $bytes= fwrite($handle2, $val);
    }

    closedir($handle2);
    $filename = "./$user/indices.txt";
    $handle2 = fopen($filename, "a");
    $val = $nom.'|'.$bytes;
    if (count($indices)>0) {
        fwrite($handle2, "\r\n".$val);
    }else{
        fwrite($handle2, $val);
    }
    closedir($handle2);
}
?>

<!DOCTYPE html>
<html lang="esp">
<head>
    <meta charset="utf-8" />
    <title>Pagina de Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h4>Administrador de archivos</h4>
    <div id="cerrarSesion">
        <?php echo "Bienvenido, " . $_SESSION['user'];?>
        <li><a href="log_out.php">Cerrar Sesión</a></li>
    </div>
    <br><br>   

    <form id="formArchivo" enctype="multipart/form-data" method="post" action="">
        Send this file: <input name="userfile" type="file" /></br></br>

        <label>Nombre </label><input value="" type="input" name="txt_nombre">
        <label>Autor </label><input value="autor" type="input" name="txt_autor">
        <label>Fecha </label><input value="20-03-2018" type="input" name="txt_fecha">
        <label>Tamaño </label><input value="12.22 Kb" type="input" name="txt_tamano"><br>
        <label>Usuario </label><input value="user" type="input" name="txt_usuario">
        <label>Descripcion </label><input value="No tiene" type="input" name="txt_descripcion">
        <label>Clasificacion </label><input value="2" type="input" name="txt_clasificacion"></br></br>        

        <input type="submit" name="btn_enviar" value="Send File"/>
    </form></br>
    <?php

    $user=$_SESSION['user'];
    error_reporting(E_ERROR | E_PARSE);
    if (isset($_POST['btn_enviar'])) {
        if ($_POST['txt_nombre']=='') {
            print "<label id='alerta'>Favor ingrese nombre del archivo</label>";
        }else{
            if (existe($user, $_POST['txt_nombre'])) {
                print "<label id='alerta'>El nombre del archivo ya existe favor intente con otro.</label>";
            }else{
                $nom=$_POST['txt_nombre'];
                $extension = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
                $uploadfile = "$user/".$nom.".".$extension;
                if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
                    agregar($user, $_POST['txt_nombre'].".".$extension,
                        $_POST['txt_autor'],
                        $_POST['txt_fecha'],
                        $_POST['txt_tamano'],
                        $_POST['txt_usuario'],
                        $_POST['txt_descripcion'],
                        $_POST['txt_clasificacion']
                    );
                    print "Received {$_FILES['userfile']['name']} -
                    its size is {$_FILES['userfile']['size']}";

                }else{
                    print "Upload failed!-";
                }
            }        			
        }
    }

    if (isset($_POST['btn_eliminar'])) 
    {  
        // Borrar un archivo
        // ubicar nombre en indices y obtener pos ej 2
        // unset en detalles la pos 2
        $nom= $_POST['btn_eliminar'];
        borrar($user, $nom);
        if(unlink($user.'/'.$_POST['btn_eliminar'])){
            print 'Deleted '.$_POST['btn_eliminar'].'<br>';
        }else{
            print "Delete of ". $_POST['btn_eliminar'] ." failed!<br>";
        }
    }
    $detalles= obtenerDetalles($user);
    print "<table><thead><tr>
    <th>Nombre</th><th>Autor</th><th>Fecha</th><th>Tamaño</th><th>Usuario</th><th>Descripcion</th><th>Clasificacion</th><th>Accion</th>
    </tr></thead><tbody><form method='POST' action='index.php'>";
    while(list($var, $val) = each($detalles)){ // var =0, val = 68
        print "<tr>";        
        print "<td><a href='$user/$val[0]'>$val[0]</a></td>";
        for ($i=1; $i < 7; $i++) { 
            print "<td>$val[$i]</td>";
        }		   
        print "<td><button name='btn_eliminar' value='".$val[0]."' type='submit'>Eliminar</button></td></tr>";
    }
    print "</form></tbody></table>";

?>

</body>
</html>