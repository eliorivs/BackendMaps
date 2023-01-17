<?php
    header("Content-Type: application/json");
    require ('../../conexion/conexion.php');
    require ('utm.php');

    $estacion=$_POST['estacion']; 
    //$estacion=1;
    $data =infoView($estacion);
    print json_encode($data);
    /*$estaciones = loadSystem($sistema);
    $infoView= infoView($sistema);
    $data =array('estaciones'=>$estaciones ,'infoView'=>$infoView);
    print json_encode($data);*/

    function infoView($estacion)
    {
        global $conn;
       // SELECT * FROM estaciones e, sistemas s WHERE e.sistema=s.id_sistema and e.id_pozo='37'
        $sql="SELECT * FROM estaciones e,sistemas s WHERE e.sistema=s.id_sistema AND e.id_estacion='".$estacion."'";    
        $result = $conn->query($sql); 
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $id= $row['id_estacion'];
        $nombre = $row['nombre_estacion'];
        $utm_este = $row['utm_este'];
        $utm_norte = $row['utm_norte']-15;
        $marker =  $row['marker'];
        $symbol = $row['symbol'];
        $sistema =  $row['sistema'];
        $subsistema = $row['subsistema'];
        $coordenadas = ToLL($utm_norte,$utm_este,'19');

        $info=array(
            'latitud'=>$coordenadas['lat'],
            'longitud'=>$coordenadas['lon'],
            'nombre'=>$nombre,'id'=>$id,
            'sistema'=>$sistema,
            'subsistema'=>$subsistema,
            'marker'=>$marker,'symbol'=>$symbol,'clase'=>$row['clase']);

        return $info;
 
       

    }

    

    

?>