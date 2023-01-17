<?php 
    header("Content-Type: application/json");
    require ('../conexion/conexion.php');
    error_reporting(0);

   
   
   $estacion =$_POST['estacion'];
  // $estacion =1;

   $result = array('datalogger'=>selectPrimary($estacion),
                   'fisicoquimica'=>parametrosFisicos(),
                   'insitu'=> parametrosInsitu(),
                   'perfilajes'=>ObtainPerfilajes($estacion));

   print json_encode($result,JSON_PRETTY_PRINT);


  

   function selectPrimary($estacion)
   {
    global $conn;
    $output='';
    $output='<optgroup label="Parámetros">'; 
    $sql = "SELECT s.id_parametro, p.nombre_largo FROM  telemetria_parametros s ,parametros p WHERE s.id_parametro=p.id_parametro AND s.id_estacion='".$estacion."'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        
            
        $output.="<option value='".$row['id_parametro']."'>".$row['nombre_largo']."</option>";


    }

    $output.="</optgroup>";
    return($output);
   }

   function parametrosFisicos()
   {
    global $conn;
    $output='';
    $output='<optgroup label="Parámetros">'; 
    $sql = "SELECT p.id_parametro,p.nombre_largo  FROM parametros p WHERE p.enable='0';";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        
            
        $output.="<option value='".$row['id_parametro']."'>".$row['nombre_largo']."</option>";


    }

    $output.='<optgroup label="Isotopos">'; 
    $sql = "SELECT p.id_parametro,p.nombre_largo  FROM parametros p WHERE p.id_grupo_anomalia='85';";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        
            
        $output.="<option value='".$row['id_parametro']."'>".$row['nombre_largo']."</option>";


    }

    $output.="</optgroup>";
    return($output);
   }

   function parametrosInsitu()
   {
    global $conn;
    $output='';
    $output='<optgroup label="Parámetros">'; 
    $sql = "SELECT p.id_parametro,p.nombre_largo  FROM parametros p WHERE p.insitu='1';";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        
            
        $output.="<option value='".$row['id_parametro']."'>".$row['nombre_largo']."</option>";


    }

    $output.="</optgroup>";
    return($output);
   }

   function formatDate($orgDate)
   {
   
    $newDate = date("d-m-Y", strtotime($orgDate));
    
    return($newDate);
   
   }

   function ObtainName($id_estacion)
   {
        global $conn;
        $sql = "select nombre_estacion from estaciones where id_estacion='".$id_estacion."'";
        $result = $conn->query($sql);
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $name= $row['nombre_estacion'];
        return ($name);
   }

   function ObtainPerfilajes($estacion)
   {
    global $conn;
    $output='';
    $output='<optgroup label="Fechas">'; 
    $sql = "SELECT DISTINCT(DATE_FORMAT(DATETIME,'%Y-%m-%d')) AS fecha FROM perfilajes WHERE estacion='".ObtainName($estacion)."'; ";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        
            
        $output.="<option value='".$row['fecha']."'>". formatDate($row['fecha'])."</option>";


    }

    $output.="</optgroup>";
    return($output);
   }



 


?>