<?php  
	function saber_dia($nombredia) {
        $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
        $fecha = $dias[date('N', strtotime($nombredia)) - 1 ];
        return $fecha;
	}

	$dia = saber_dia(date('Y-m-d'));
	
	if($dia != 'Domingo' && $dia != 'Sabado' ){

		$con = mysqli_connect('localhost', 'asweb2oy_gestion', '10238799', 'asweb2oy_getionh');
	
		$query = mysqli_query($con, "UPDATE quejas_reclamos SET dias_restantes = dias_restantes - 1  WHERE estado = 'Pendiente' AND dias_restantes > 0");
		mysqli_close($con);
	}

	//TODO: Enviar email de los manteniminetod programados



	// $fecha_actual = idate('Y').'-'.idate('m').'-'.idate('d');

	// while($row = mysqli_fetch_array($query)){
	//  	$id                    = $row['id'];
	// 	$segundosFechaActual   = strtotime($fecha_actual);
	// 	$segundosFechaRegistro = strtotime($row['fecha_limite']);
	// 	$segundosTranscurridos = $segundosFechaRegistro - $segundosFechaActual;
	// 	$diasTranscurridos     = $segundosTranscurridos / 86400;
	// 	// echo $diasTranscurridos .' - '.$row['dias_restantes'];
	// 	if($diasTranscurridos <= 0){
	// 		$query_update = mysqli_query($con, "UPDATE quejas_reclamos SET dias_restantes = 0 WHERE id = '$id'");	
	// 	} else {
	// 		$query_update = mysqli_query($con, "UPDATE quejas_reclamos SET dias_restantes = '$diasTranscurridos' WHERE id = '$id'");
	// 	}
	// }

	// mysqli_close($con);

?>