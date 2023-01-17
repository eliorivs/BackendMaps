<?php

 //require ('../conexion/conexion.php');

 function showpicture($estacion)
 {
    global $conn;
    $sql="select esquema, img from estaciones where id_estacion='".$estacion."'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
                 
         $data= array('esquema'=>$row['esquema'], 'imagen'=>$row['img']);
 
    }
    return $data; 
 }

 function thirdlevel($subsistemas,$s_open, $ss_open)
 {

    $process_sistema =  $subsistemas[0]['id'];
    $collapse='';
    if($process_sistema==$s_open)
    {
      $lista = '<ul class="nav nav-second-level collapse in">';
    }
    else
    {
      $lista = '<ul class="nav nav-second-level">';
    }   

    foreach($subsistemas as $subsistema)
    {

       if($subsistema['id']==$ss_open)
       {
         $collapse='collapse in';
         $active ='class="active"';     
       }
       else
       {
         $collapse='';
         $active ='';      
       }
      $lista .='<li '.$active.'  style="border-bottom: 1px #e7e7e7 solid !important;">
      <a href="#" ><i class="fas fa-solid fa-layer-group" style="color:'.$subsistema['color'].'"></i> '.$subsistema['name'].'<span class="fa arrow"></span></a>
      <ul class="nav nav-third-level '.$collapse.'">';

      $pozos = pozosSubsistema($subsistema['id']);
      foreach ($pozos as $pozo){

         $lista.='<li style="border-bottom: 1px #e7e7e7 solid !important;" ><a href="location.php?estacion='.$pozo['id'].'&sistema='.$pozo['sistema'].'&subsistema='.$pozo['subsistema'].'"><i style="color:'.$subsistema['color'].'" class="fas fa-map-marker-alt"></i><span style="font-weight: bold"> '.$pozo['name'].'</span></a></li>';
      }
      
      $lista.='</ul> </li>';
    }

    $lista .= '</ul>';

    return($lista);
    
 }
 function pozosSistema()
 {

 }
 function pozosSubsistema($id_subsistema)
 {
     global $conn;
     $sql="select * from estaciones where subsistema='".$id_subsistema."' order by nombre_estacion";
     $result = $conn->query($sql); 
     while ($row = $result->fetch_array(MYSQLI_ASSOC))
     {
                  
          $data[]= array('id'=>$row['id_pozo'],
                         'name'=>$row['nombre_estacion'],
                         'subsistema'=>$row['subsistema'],
                         'sistema'=>$row['sistema']);
  
     }
     return $data;

 }


 function StructureList($sistemas,$s_open, $ss_open,$estacion)
 { 
   $lista='';
   $collapse='';
 
   foreach($sistemas as $sistema)
   {   
       if($sistema['id']==$s_open)
       {
         $collapse='collapse in';  
         $active ='class="active"';        
       }
       else
       {
         $collapse=''; 
         $active =''; 
       }
       $lista.='<li '.$active.'>';
       $lista.='<a style="background-color:'.$sistema['rgb'].'"  class="resize" data-id="'.$sistema['id'].'" href="#"><i class="fas fa-solid fa-layer-group resize" style="font-size: large;color: '.$sistema['color'].'"></i>&nbsp;<b>'.$sistema['name'].'</b><span class="fa arrow"></span></a>';
       if($sistema['subsistema']=='1')
       {
         $subsistemas = (ObtenerSubsistemas($sistema['id']));
         $lista.=thirdlevel($subsistemas,$s_open, $ss_open);
       }
       if($sistema['subsistema']=='0')
       {
         $pozos = (PozoSistema($sistema['id']));
       
         $lista.='<ul class="nav nav-second-level '.$collapse.'">';
         foreach ($pozos as $pozo)
         {
            if($pozo['id']==$estacion)
            {
              $lista.='<li style="border-bottom: 1px #e7e7e7 solid !important; background-color: '.$sistema['fondo'].' "><a href="location.php?estacion='.$pozo['id'].'&sistema='.$pozo['sistema'].'&subsistema='.$pozo['subsistema'].'"><i style="color: '.$sistema['color'].'" class="fas fa-map-marker-alt"></i> <span style="font-weight:bold"> '.$pozo['name'].' <i style="float: right ; color: '.$sistema['color'].'" class="fa fa-dot-circle-o"  aria-hidden="true"></i> </span></a></li>';
         
            }
            else
            {
              $lista.='<li style="border-bottom: 1px #e7e7e7 solid !important;"><a href="location.php?estacion='.$pozo['id'].'&sistema='.$pozo['sistema'].'&subsistema='.$pozo['subsistema'].'"><i style="color: '.$sistema['color'].'" class="fas fa-map-marker-alt"></i> <span style="font-weight:bold"> '.$pozo['name'].'</span></a></li>';
         
            }
          }
         $lista.='</ul>';        
        
       }
       $lista.='</li>';       
      
   }
   echo($lista);

 }




 #############################################
 
 function  ObtenerSistemas()
 {
    global $conn;
    $sql="select * from sistemas";
    $result = $conn->query($sql); 
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
                 
         $data[]= array('id'=>$row['id_sistema'], 
                        'name'=>$row['name_sistema'],
                        'subsistema'=>$row['has_subsistem'],
                        'color'=>$row['color'],
                        'rgb'=>$row['rgb'],
                        'fondo'=>$row['background']);
 
    }
    return $data;

 }

 function  ObtenerSubsistemas($sistema)
 {
    global $conn;
    $sql="select * from subsistemas where sistema='".$sistema."'";
    $result = $conn->query($sql); 
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
                 
         $data[]= array('id'=>$row['id_subsistema'], 
                        'name'=>$row['nombre_subsistema'],
                        'sistema'=>$row['sistema'],
                        'color'=>$row['color']);
 
    }
    return $data;

 }

 function PozoSistema($sistema)
 {
    global $conn;
    $data = []; 
    $sql="select * from estaciones where sistema='".$sistema."' order by nombre_estacion";
    $result = $conn->query($sql); 
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
                 
         $data[]= array('id'=>$row['id_estacion'], 'name'=>$row['nombre_estacion'],'sistema'=>$row['sistema'],'subsistema'=>$row['subsistema']);
 
    }
    return $data;

 }


?>