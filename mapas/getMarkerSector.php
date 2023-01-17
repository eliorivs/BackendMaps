<?php
    header("Content-Type: application/json");
    require ('../../conexion/conexion.php');
    require ('utm.php');

    $sistema=$_POST['sistema']; 
    $estaciones = loadSystem($sistema);
    $infoView= infoView($sistema);
    $data =array('estaciones'=>$estaciones ,'infoView'=>$infoView);
    print json_encode($data);

    function infoView($sistema)
    {
        global $conn;
        $sql="select * from sistemas where id_sistema='".$sistema."'";    
        $result = $conn->query($sql); 
        $row = $result->fetch_array(MYSQLI_ASSOC);
       // $data = array('color'=>$row['color']);
        return(array('color'=>$row['color'],'nombre'=>$row['name_sistema']));


    }

    function loadSystem($sistema)
    {
        global $conn;
        $sql="SELECT * FROM estaciones e, sistemas s WHERE e.sistema=s.id_sistema AND  e.sistema='".$sistema."' ";    
        $result = $conn->query($sql); 
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
 
        
            $id= $row['id_estacion'];
            $nombre = $row['nombre_estacion'];
            $utm_este = $row['utm_este'];
            $utm_norte = $row['utm_norte']-15;
            $marker =  $row['marker'];
            $symbol = $row['symbol'];
            $sistema =  $row['sistema'];
            $subsistema = $row['subsistema'];
            $coordenadas = ToLL($utm_norte,$utm_este,'19');
          

            #$estaciones=array('latitud'=>$coordenadas['lat'],'longitud'=>$coordenadas['lon']);

            $estaciones[]=array(
                'latitud'=>$coordenadas['lat'],
                'longitud'=>$coordenadas['lon'],
                'nombre'=>$nombre,'id'=>$id,
                'sistema'=>$sistema,
                'subsistema'=>$subsistema,
                'marker'=>$marker,'symbol'=>$symbol,'clase'=>$row['clase']);
 
        }
        return $estaciones;
 
    }


    

?>