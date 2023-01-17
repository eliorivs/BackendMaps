<?php
    header("Content-Type: application/json");
    require ('../../conexion/conexion.php');
    error_reporting(0);
    
    
   

   ## $fechas = ['2022-03-22'];
    //$fechas = ['2022-03-24'];
    $fechas = $_POST['fechas'];
    $id_estacion = $_POST['estacion'];
    /*$id_estacion='1';*/
    $estacion = get_name_estacion($id_estacion);
    $PerfConductividad =  SelectConductividad($fechas,$estacion);
    $PerfpH =  SelectPH($fechas,$estacion);
    $Perftmp =  SelectTemp($fechas,$estacion);

    $font_titles ='12px "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
    $font_labels ='11px "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
    

    $xaxis =array('gridLineWidth'=>1,
                  'tickInterval'=>5,
                  'gridLineDashStyle'=> 'ShortDash',
                  'endOnTick'=> true,
                  'showLastLabel'=> true,
                  'startOnTick'=> true,
                  'reversed'=> false,
                  'showLastLabel'=> true,               
                  'max'=> get_elevacion($estacion),
                  'title'=>array('enable'=>true, 'text'=>'Profundidad (m.snm)','style'=>array('color'=>'black','font'=>$font_titles)),
                  'labels'=>array( 'format'=> '{value}', 'style'=>array('color'=>'gray','font'=>$font_labels)));


     $reply =array( 'plt'=>NivelPlt($estacion),'xaxis'=>$xaxis,
                    'habilitacion'=>habilitacion($id_estacion),
                    'estratigrafia'=> estratigrafia($id_estacion),
                    'profundidad_inicio'=>get_elevacion($estacion),
                    'profundidad_fin'=>prof_estacion($estacion),
                    'unidad'=>'m.snm',
                    'valores_conductividad'=>$PerfConductividad,
                    'valores_pH'=> $PerfpH,
                    'valores_temp'=>$Perftmp,'xaxis'=>$xaxis);


    print json_encode($reply, JSON_PRETTY_PRINT);


    

    function get_name_estacion($id_estacion)
    {
        global $conn;
        $sql="select nombre_estacion as nombre from estaciones where id_estacion='".$id_estacion."'";
        $result = $conn->query($sql); 
        $row = $result->fetch_array(MYSQLI_ASSOC);        
        return ($row['nombre']);
    }

    function prof_estacion($estacion)
    {
        global $conn;
        $sql="select prof_estacion,elevacion from estaciones where nombre_estacion='".$estacion."'";
        $result = $conn->query($sql); 
        $row = $result->fetch_array(MYSQLI_ASSOC);        
        return round(floatval(($row['elevacion']))-floatval(($row['prof_estacion'])),2);



    }
    function get_elevacion($estacion)
    {
        global $conn;
        $sql="select elevacion from estaciones where nombre_estacion='".$estacion."'"; 
        $result = $conn->query($sql); 
        $row = $result->fetch_array(MYSQLI_ASSOC);
        return floatval(($row['elevacion']));

    }

    function NivelPlt($estacion)
    {
        global $conn;
        $sql="SELECT nivel,fecha FROM mediciones_puntuales p WHERE p.estacion='".$estacion."' AND p.fecha=(SELECT MAX(p.fecha) FROM mediciones_puntuales p WHERE p.estacion='".$estacion."')";
        $result = $conn->query($sql); 
        if (mysqli_num_rows($result) == 0){
     
            $plt = [];
            //array_push($resultado,$arr);                    
        }
        else
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $plt []= array('value'=> get_elevacion($estacion)-floatval($row['nivel']),
                           'color'=>'navy',
                           'dashStyle'=>'solid',
                           'width'=>2,
                           'zIndex'=> 2,
                           'label'=>array('text'=>$row['fecha'].'<br>'.'N.E :'.$row['nivel']." m",
                                        'style'=>array('fontSize'=> '8px')));    
        }    
      
        return($plt);    
       
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
                $sql="SELECT DATETIME as datetime,profundidad, conductividad, estacion FROM perfilajes WHERE  estacion='".$estacion."' and  YEAR(datetime)='".$year."' and MONTH(datetime)='".$month."' and DAY(datetime)='".$day."' and  profundidad >0 ORDER BY profundidad  ";
                $result = $conn->query($sql);
                if (mysqli_num_rows($result) == 0){
     
                    $arr=array();
                                   
                }
                else
                {             
               
                    while ($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        if ($j == 0)
                        {                   
                            $arr['name']= 'Conductividad ('.$fecha.')';
                            $j++;                  
                                
                        }
                        $profundidad = get_elevacion($estacion)-floatval(($row['profundidad']));
                        $conductividad =floatval($row['conductividad']);                     
                        $arr['data'][]=[round($profundidad,2),$conductividad];      
              
                    }
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
                $year =   $newDate = date("Y", strtotime($fecha));
                $day =    $newDate = date("d", strtotime($fecha));  
                $j=0; 
                unset($arr);            
                $sql="SELECT DATETIME as datetime,profundidad, ph, estacion FROM perfilajes  WHERE   estacion='".$estacion."' and  YEAR(datetime)='".$year."' and MONTH(datetime)='".$month."' and DAY(datetime)='".$day."' and  profundidad >0 ORDER BY profundidad  ";
                $result = $conn->query($sql);
                if (mysqli_num_rows($result) == 0)
                {
     
                    $arr=array();
               
                    
                }
                else
                {             
               
                    while ($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        if ($j == 0)
                        {                   
                            $arr['name']= 'pH ('.$fecha.')';
                            $j++;                  
                                
                        }
                        $profundidad = get_elevacion($estacion)-floatval(($row['profundidad']));
                        $ph =floatval($row['ph']);                     
                        $arr['data'][]=[round($profundidad,2),$ph];      
              
                    }
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
              
                $month =  $newDate = date("m", strtotime($fecha));
                $year =  $newDate = date("Y", strtotime($fecha));
                $day =  $newDate = date("d", strtotime($fecha));  
                $j=0; 
                unset($arr);            
                $sql="SELECT DATETIME as datetime,profundidad, temperatura, estacion FROM perfilajes WHERE  estacion='".$estacion."' and   YEAR(datetime)='".$year."' and MONTH(datetime)='".$month."' and DAY(datetime)='".$day."' and  profundidad >0 ORDER BY profundidad ";
                $result = $conn->query($sql);
                if (mysqli_num_rows($result) == 0)
                {
     
                    $arr=array();
                  
                    
                }
                else
                {             
               
                    while ($row = $result->fetch_array(MYSQLI_ASSOC))
                    {
                        if ($j == 0)
                        {                   
                            $arr['name']= 'Temperatura ('.$fecha.')';
                            $j++;                  
                                
                        }
                        $profundidad = get_elevacion($estacion)-floatval(($row['profundidad']));
                        $conductividad =floatval($row['temperatura']);                     
                        $arr['data'][]=[round($profundidad,2),$conductividad];      
              
                    }
                    array_push($resultado,$arr);
                }      


                
        }
        return $resultado;
 
    }
    function habilitacion($id_estacion)
    {   
        global $conn;
        $segmento =[];
        $prof_estacion = prof_estacion(get_name_estacion($id_estacion)); 
        $sql="select * from habilitacion_series where ok='1' and id_pozo='".$id_estacion."' order by orden desc ";       
        $result = $conn->query($sql);
        if (mysqli_num_rows($result) == 0)
        {

            $arr=array();
            array_push($segmento,$arr);
          
            
        }
        else
        {
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
            
             $segmento[]= array('name'=>$row['name'],
                            'data'=>array(floatval($row['data'])),
                            'stack'=>$row['stack'],
                            'color'=>$color);
     
            }
            $cota = array('name'=>'m.snm',
            'data'=>[$prof_estacion],
            'stack'=>'unique',
            'color'=>array(
                'pattern'=>array(
                    'backgroundColor'=>'white',
                    'path'=>array("d"=>'M 0 0 L 5 5 M 4.5 -0.5 L 5.5 0.5 M -0.5 4.5 L 0.5 5.5'),
                    'width'=>4,
                    'height'=>4,
                    'opacity'=>intval(1),
                    'color'=>'white'
    
                )
            ));
            array_push($segmento,$cota);
            

        }
        return $segmento;
      

        
    }

    function estratigrafia($id_estacion)
    {

        $segmento =[];
        $prof_estacion = prof_estacion(get_name_estacion($id_estacion));      
        global $conn;
        $j=0;

        $data = [];      
        $sql="select * from estratigrafia_series where id_pozo='".$id_estacion."' order by orden desc ; ";
        $result = $conn->query($sql); 
        if (mysqli_num_rows($result) == 0)
        {

            $arr=array();
            array_push($segmento,$arr);
          
            
        }
        else
        {
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
            {
               
            
             
             $segmento[]= array('name'=>$row['name'],
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
       

        $cota = array('name'=>'m.snm',
        'data'=>[$prof_estacion],
        'stack'=>'unique',
        'color'=>array(
            'pattern'=>array(
                'backgroundColor'=>'white',
                'path'=>array("d"=>'M 0 0 L 5 5 M 4.5 -0.5 L 5.5 0.5 M -0.5 4.5 L 0.5 5.5'),
                'width'=>4,
                'height'=>4,
                'opacity'=>intval(1),
                'color'=>'white'

            )
        ));
       array_push($segmento,$cota);
      
     return $segmento;
 
    }


    

?>