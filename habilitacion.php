<?php 
    header("Content-Type: application/json");
    require ('../conexion/conexion.php');
    
    $id=$_POST['estacion'];
    $series =  fillDataTable($id);
    $plotlines =fillPlotlines($id); 
    $estacion = dataStation($id);
    $result = array('series'=>$series, 'plotlines'=>$plotlines,'estacion'=>$estacion);
    print json_encode($result,JSON_PRETTY_PRINT);



    function  dataStation($id)
    {
        global $conn;
        $sql="select * from estaciones where id_pozo='".$id."'";
        $result = $conn->query($sql); 
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
 
        
         
         $data= array('name'=>$row['nombre_estacion'], 'profundidad'=>floatval($row['prof_estacion']));
 
        }
     return $data;
 
    }



    function fillDataTable($id)
    {
        global $conn;
        $sql="select * from habilitacion_series where ok='1' and id_pozo='".$id."' order by orden ";
        $result = $conn->query($sql); 
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
 
        
         if($row['tipo']=='R')
         {
             $color = array(
                'pattern'=>array(

                    'path'=>array("d"=>$row['patron']),
                    'width'=>intval($row['width']),
                    'height'=>intval($row['height']),
                    'opacity'=>intval(1),
                    'color'=>$row['color']

                )
                );
         }
         if($row['tipo']=='L')
         {  
                $color = $row['color'];
         }
        
         $data[]= array('name'=>$row['name'],
                        'data'=>array(floatval($row['data'])),
                        'stack'=>$row['stack'],
                        'color'=>$color);
 
        }
     return $data;
 
    }

    function fillPlotlines($id)
    {
        global $conn;
        $sql="select * from habilitacion_plotlines where id_pozo='".$id."' ";
        $result = $conn->query($sql); 
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
 
        
         
         $data[]= array('color'=>$row['color'],
                        'dashStyle'=>$row['dashstyle'],
                        'value'=>intval($row['value']),
                        'width'=>1,
                        'zIndex'=>5,
                        'label'=>array(
                            'text'=>$row['text'],
                            'align'=>'left',
                            'y'=>intval($row['y']),
                            'style'=>array('fontSize'=>'10px')));
 
        }
     return $data;

    }


?>