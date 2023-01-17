<?php 
    header("Content-Type: application/json");
    require ('../conexion/conexion.php');

    $id=$_POST['estacion'];
    $series =  fillDataTable($id);
    $plotlines =fillPlotlines($id); 
    $estacion = dataStation($id);
    $result = array('series'=>$series,
                   'plotlines'=>$plotlines,'estacion'=>$estacion);
    print json_encode($result,JSON_PRETTY_PRINT);
    function  dataStation($id)
    {
        global $conn;
        $sql="select * from estaciones e, sistemas s WHERE e.sistema=s.id_sistema AND e.id_estacion='".$id."'";
        $result = $conn->query($sql); 
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        { 
        
         
         $data= array('color'=>$row['color'],
                      'name'=>$row['nombre_estacion'],
                      'sistema'=>$row['name_sistema'],
                      'profundidad'=>floatval($row['prof_estacion']),
                      'utm_este'=>$row['utm_este'],
                      'utm_norte'=>$row['utm_norte'],
                      'msnm'=>$row['elevacion'],
                      'nivel'=>$row['nivel_estatico']
                    );
 
        }
     return $data;
 
    }
    function fillDataTable($id)
    {
        global $conn;
        $data=[];
        $sql="select * from estratigrafia_series where id_pozo='".$id."' order by orden desc ";
        $result = $conn->query($sql); 
        if (mysqli_num_rows($result) == 0)
        {

            $arr=array();
            array_push($data,$arr);
          
            
        }
        else
        {
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
            {
    
            
            
            $data[]= array('name'=>$row['name'],
                            'data'=>array(floatval($row['data'])),
                            'stack'=>$row['stack'],
                            'color'=>array(
                                'pattern'=>array(
                                    'backgroundColor'=>$row['background'],
                                    'path'=>array("d"=>$row['patron']),
                                    'width'=>intval($row['width']),
                                    'height'=>intval($row['height']),
                                    'opacity'=>intval(1),
                                    'color'=>$row['color']

                                )
                            ));
    
            }
        }
     return $data;
 
    }

    function fillPlotlines($id)
    {
        global $conn;
        $data=array();
        $sql="select * from estratigrafia_plotlines where id_pozo='".$id."' ";
        $result = $conn->query($sql); 
        if (mysqli_num_rows($result) == 0)
        {

            $arr=array();
            array_push($data,$arr);
          
            
        }
        else{
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
                                 'style'=>array('fontSize'=>'10px')),                           
                            );
     
            }
        

        }
        return $data;

    }


?>