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
    // $handle2 = fopen($filename, "r+");
    //print "<br>--<br>";
    //var_dump($handle2); print "<br>--<br>";
    // Recolectar indices
    $indices=[];
    while(list($var, $val) = each($handle2)){ // var =0, val = test.txt|68
        //print "$var: $val <br/>"; // test.txt|68
        $val = explode("|", trim($val));
        //print "$var: $val[1] <br/>"; // 0: 68
        $indices[]=$val; 
        //$indices[]=$val[1]; 
    }
    //var_dump($indices);
    //print "<br>--<br>";
    closedir($handle2);
    // mostrar indices
    //while(list($var, $val) = each($indices)){ // var =0, val = 68
    //    print "$var: $val <br/>";
    //}
    return $indices;
}
function obtenerDetalles($user)
{
	$filename = "./$user/detalles.txt";
    //$handle2 = file($filename);
    $handle2 = fopen($filename, "r+");
    print "<br><br>";
    //var_dump($handle2); print "<br>--<br>";
    // Recolectar indices
    $indices=obtenerIndices($user); // se obtienen nombre e indice o cant de car en detalle
    //var_dump($indices);
    $detalleCad="";
    $acumSeekInd=0;
    $detalles=[];
    while(list($var, $val) = each($indices)){ // var =0, val = test.txt|68
        //print "$var: $val <br/>"; // test.txt|68
        //$val = explode("|", trim($val));
        //print "$var: $val[1] <br/>"; // 0: 68
        //$indices[]=$val[1]; 
        //print "$var: $val[1]--"; // funciona
    	//$val=explode('|', $val);
    	//$val=$val[1];

    	fseek($handle2, 0); // detalles.txt
        if ($var==0) {
        	//print("arg");
        	
        	//$detalleCad=fread($handle2, $val);
        	$detalleCad=trim(fread($handle2, $val[1]));
        	$detalles[] = explode('|', $detalleCad);
        	//print "$detalleCad <br>";
        }else{		        	
        	//print "$acumSeekInd<br>";
        	fseek($handle2, $acumSeekInd+2); // detalles.txt
        	$detalleCad=trim(fread($handle2, $val[1]));
        	//$detalleCad=fread($handle2, $val);
        	$detalles[] = explode('|', $detalleCad);
        }
        $acumSeekInd+=$val[1];
    }
    //print($detalleCad)."<br>--<br>";
    //print($acumSeekInd)."<br>--<br>";
    //var_dump($detalles);
    //print "<br>--<br>";
    closedir($handle2);
    return $detalles;
}

function borrar($user, $nom){
	$indices = obtenerIndices($user); // indices = []
    $detalles= obtenerDetalles($user);
    $posNom=-1;
    while(list($var, $val) = each($indices)){ // var =0, val = 68
        //print $val[0];
        //print implode('|', $val);
        if ($val[0]==$nom) {
        	$posNom=$var;
        	// $indices = obtenerDetalles($user); //
        }
        //print "<br>";	
    }
    unset($indices[$posNom]);
    //var_dump($indices);

    unset($detalles[$posNom]);
    //var_dump($detalles);

    //print "$posNom";
    
    // sobreescribir ficheros con los arrays recibidos
    //escribirFicheros($indices);
    //escribirFicheros($detalles);

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
        //print implode('|', $val);
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
function existe($user, $nomArchivo)
{
	$indices = obtenerIndices($user); // indices = []
    while(list($var, $val) = each($indices)){ // var =0, val = 68
        if ($val[0]==$nomArchivo) {
	    	return true;
	    }
    }
}
function agregar($user, $nom, $autor, $fecha, $tam, $usuario, $descripcion, $clasificacion)
{
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

    //print $bytes;
    //print "$val <br>--<br>";

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
            <title>User Dashboard</title>
    </head>
        
    <body>
        
        <?php echo "Bienvenido, " . $_SESSION['user'];?>
        
        <ul>
        	<li><a href="log_out.php">Salir</a></li>
        </ul>

        <form enctype="multipart/form-data" method="post" action="">
        Send this file: <input name="userfile" type="file" /></br></br>

        <label>Nombre </label><input value="" type="input" name="txt_nombre"></br>
        <label>Autor </label><input value="autor" type="input" name="txt_autor"></br>
        <label>Fecha </label><input value="20-03-2018" type="input" name="txt_fecha"></br>
        <label>Tamaño </label><input value="12.22 Kb" type="input" name="txt_tamano"></br>
        <label>Usuario </label><input value="user" type="input" name="txt_usuario"></br>
        <label>Descripcion </label><input value="No tiene" type="input" name="txt_descripcion"></br>
        <label>Clasificacion </label><input value="2" type="input" name="txt_clasificacion"></br></br>        

        <input type="submit" name="btn_enviar" value="Send File"/>
        </form></br>
        <?php
            $user=$_SESSION['user'];
            error_reporting(E_ERROR | E_PARSE);
            //print $_FILES['userfile']['tmp_name']. "</br>";
            if (isset($_POST['btn_enviar'])) {
            	if ($_POST['txt_nombre']=='') {
	           		print "Favor ingrese nombre del archivo";
            	}else{
            		if (existe($user, $_POST['txt_nombre'])) {
            			print "El nombre del archivo ya existe favor intente con otro.";
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
	            //print $_POST['txt_nombre']."-";
            }
            

            if (isset($_POST['btn_eliminar'])) 
            {  
                // Borrar un archivo
			    // ubicar nombre en indices y obtener pos ej 2
			    // unset en detalles la pos 2
			    $nom= $_POST['btn_eliminar'];
			    borrar($user, $nom);

                //print $user.'/'.$_POST['btn_eliminar'].'<br>';
                if(unlink($user.'/'.$_POST['btn_eliminar'])){
                    print 'Deleted '.$_POST['btn_eliminar'].'<br>';
                }else{
                    print "Delete of ". $_POST['btn_eliminar'] ." failed!<br>";
                }
                //unset($_SESSION['sesion_eventos'][$_POST['eliminar']]);
                //header('Location: index.php'); //../
            }

            // devuelve tam de indice en detalles
            //$indices = obtenerIndices($user); // es necesario enviar $user 
            //var_dump($indices);

            $detalles= obtenerDetalles($user);
            //var_dump($detalles);
		    // mostrar detalles

            print "<table><thead><tr>
            <th>Nombre</th><th>Autor</th><th>Fecha</th><th>Tamaño</th><th>Usuario</th><th>Descripcion</th><th>Clasificacion</th><th>Accion</th>
            </tr></thead><tbody><form method='POST' action='index.php'>";
		    while(list($var, $val) = each($detalles)){ // var =0, val = 68
			    print "<tr>";
		        //print "$var: $val[$var] <br/>";
		        //var_dump($val);		        
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