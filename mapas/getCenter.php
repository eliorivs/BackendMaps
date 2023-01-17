<?php
    header("Content-Type: application/json");
    require ('../../conexion/conexion.php');
    require ('utm.php');

    $coor = avgcoordinates();

    print json_encode($coor);

    function avgcoordinates()
    {
        global $conn;
        $sql="SELECT utm_norte AS utm_norte, utm_este AS utm_este FROM estaciones where id_estacion='4' ";    
        $result = $conn->query($sql); 
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
 
        
         
            $utm_este = $row['utm_este'];
            $utm_norte = $row['utm_norte']-15;
            $coordenadas = ToLL($utm_norte,$utm_este,'19');
          

            #$estaciones=array('latitud'=>$coordenadas['lat'],'longitud'=>$coordenadas['lon']);

            $estaciones[]=array('latitud'=>$coordenadas['lat'],
                                'longitud'=>$coordenadas['lon'],
                                );
 
        }
        return $estaciones;
 
    }


    

?>