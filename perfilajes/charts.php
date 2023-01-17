<?php
    header("Content-Type: application/json");
    require ('../../conexion/conexion.php');
    
   

    $fechas = ['2022-03-24','2022-03-25'];
   ## $fechas = $_POST['fechas'];
    $estacion = 'CRW-01';
    $PerfConductividad =  SelectConductividad($fechas,$estacion);
    $PerfpH =  SelectPH($fechas,$estacion);
    $Perftmp =  SelectTemp($fechas,$estacion);
    $reply =array('profundidad_inicio'=>0,'profundidad_fin'=>prof_estacion(),'unidad'=>'m.bnt','valores_conductividad'=>$PerfConductividad,
                  'valores_pH'=> $PerfpH,
                  'valores_temp'=>$Perftmp);

    print json_encode($reply);

    function prof_estacion()
    {
        global $conn;
        $sql="select prof_estacion from estaciones where id_estacion='1'";
        $result = $conn->query($sql); 
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return floatval(($row['prof_estacion']));



    }


    function SelectConductividad($fechas,$estacion)
    {
        global $conn;
        $arr=[];
        $resultado=[];  
        foreach($fechas as $fecha)
        {
                //echo $fecha;
                $month =  $newDate = date("m", strtotime($fecha));
                $year =  $newDate = date("Y", strtotime($fecha));
                $day =  $newDate = date("d", strtotime($fecha));  
                $j=0; 
                unset($arr);            
                $sql="SELECT DATETIME as datetime,profundidad, conductividad, estacion FROM perfiles WHERE YEAR(datetime)='".$year."' and MONTH(datetime)='".$month."' and DAY(datetime)='".$day."' and  profundidad >0 ORDER BY DATETIME ";
                $result = $conn->query($sql); 
                while ($row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    if ($j == 0)
                    {                   
                      $arr['name']= 'Conductividad ('.$fecha.')';
                      $j++;                   
                            
                     }
                     $profundidad = floatval($row['profundidad']);
                     $conductividad =floatval($row['conductividad']);
                     $datatetime = $row['datetime'];
                     $arr['data'][]=[$profundidad,$conductividad];      
          
                }
                if(sizeof($arr)!= 0)                                                                                                                                                                                                                            
                {
                    array_push($resultado,$arr);
                   
                }       


                
        }
        return $resultado;
       
 
    }

    
    function SelectPH($fechas,$estacion)
    {
        global $conn;
        $arr=[];
        $resultado=[];  
        foreach($fechas as $fecha)
        {
                //echo $fecha;
                $month =  $newDate = date("m", strtotime($fecha));
                $year =  $newDate = date("Y", strtotime($fecha));
                $day =  $newDate = date("d", strtotime($fecha));  
                $j=0; 
                unset($arr);            
                $sql="SELECT DATETIME as datetime,profundidad, ph, estacion FROM perfiles WHERE YEAR(datetime)='".$year."' and MONTH(datetime)='".$month."' and DAY(datetime)='".$day."' and  profundidad >0 ORDER BY DATETIME ";
                $result = $conn->query($sql); 
                while ($row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    if ($j == 0)
                    {                   
                      $arr['name']= 'pH ('.$fecha.')';
                      $j++;                   
                            
                     }
                     $profundidad = floatval($row['profundidad']);
                     $conductividad =floatval($row['ph']);
                     $datatetime = $row['datetime'];
                     $arr['data'][]=[$profundidad,$conductividad];      
          
                }
                if(sizeof($arr)!= 0)                                                                                                                                                                                                                            
                {
                    array_push($resultado,$arr);
                   
                }
        }
        return $resultado;       
 
    }


    function SelectTemp($fechas,$estacion)
    {
        global $conn;
        $arr=[];
        $resultado=[];  
        foreach($fechas as $fecha)
        {
                //echo $fecha;
                $month =  $newDate = date("m", strtotime($fecha));
                $year =  $newDate = date("Y", strtotime($fecha));
                $day =  $newDate = date("d", strtotime($fecha));  
                $j=0; 
                unset($arr);            
                $sql="SELECT DATETIME as datetime,profundidad, temperatura, estacion FROM perfiles WHERE YEAR(datetime)='".$year."' and MONTH(datetime)='".$month."' and DAY(datetime)='".$day."' and  profundidad >0 ORDER BY DATETIME ";
                $result = $conn->query($sql); 
                while ($row = $result->fetch_array(MYSQLI_ASSOC))
                {
                    if ($j == 0)
                    {                   
                      $arr['name']= 'Temperatura ('.$fecha.')';
                      $j++;                   
                            
                     }
                     $profundidad = floatval($row['profundidad']);
                     $conductividad =floatval($row['temperatura']);
                     $datatetime = $row['datetime'];
                     $arr['data'][]=[$profundidad,$conductividad];      
          
                }
                if(sizeof($arr)!= 0)                                                                                                                                                                                                                            
                {
                    array_push($resultado,$arr);
                   
                }       


                
        }
        return $resultado;
 
    }


    

?>