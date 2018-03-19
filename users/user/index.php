<?php

require'../class/sessions.php';
$objses = new Sessions();
$objses->init();

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;

if($user == ''){
	header('Location: ../index.php?error=2');
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
        Send this file: <input name="userfile" type="file" /></br>
        <input type="submit" value="Send File"/>
        </form>
        <?php
            $user=$_SESSION['user'];
            error_reporting(E_ERROR | E_PARSE);
            //print $_FILES['userfile']['tmp_name']. "</br>";
            if(move_uploaded_file($_FILES['userfile']['tmp_name'], "$user/".$_FILES['userfile']['name'])){
                print "Received {$_FILES['userfile']['name']} -
                        its size is {$_FILES['userfile']['size']}";
            }else{
                print "Upload failed!";
            }


            print "<br><br>";

            print "<br>";

            if (isset($_POST['btn_eliminar'])) 
            {  
                //print $user.'/'.$_POST['btn_eliminar'].'<br>';
                if(unlink($user.'/'.$_POST['btn_eliminar'])){
                    print 'Deleted '.$_POST['btn_eliminar'].'<br>';
                }else{
                    print "Delete of ". $_POST['btn_eliminar'] ." failed!<br>";
                }
                //unset($_SESSION['sesion_eventos'][$_POST['eliminar']]);
                //header('Location: index.php'); ../
            }
            
            $handle = opendir("./".$user); // cadena un poco diferente
            if($handle){ 
                print "<form enctype='multipart/form-data' method='post' action='./index.php'>"; // problemas al eliminar un archivo se devuelve al index
                //print "http:// ". $_SERVER['SERVER_NAME'] .':'. $_SERVER['SERVER_PORT'] .'CCC'.$_SERVER['SERVER_SELF'];
                while(false !== ($file = readdir($handle))){
                    if($file =='.' || $file =='..'){

                    }else{
                        //print "<a href='folder/$file' >".$file."</a> <br />\n";
                        print "<a href='$user/$file' >".$file."</a> <button name='btn_eliminar' value='$file' type='submit'>Eliminar</button> <br />\n";
                    }
                }
                closedir($handle);
                print '</form>';
            }        
        ?>
        
    </body>
    
</html>