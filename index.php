<?php

/*
	Prueba Grupo Educar
	@autor: Nelson Cuellar
*/

//Arreglo Lista de Clientes
$clientes = array (
  array(1234,"Juan Acosta",22000,"A"),
  array(4567,"Fernanda Alvarez",15000,"A"),
  array(3745,"Luis Benitez",5000,"I"),
  array(4878,"Juan Santos",15000,"A"),
  array(3622,"Maria Uribe",5000,"I"),
  array(1987,"Brandon Suarez",15000,"A"),
  array(2998,"Julian Galindo",5000,"I"),
  array(1126,"Oscar Roa",15000,"A"),
  array(9667,"Lizet Buenahora",5000,"I"),
  array(8230,"Laura Ramirez",17000,"A")
);



//Metodo de Consultas 1-Listar Clientes 2-Listar cliente especifico
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    
		//Metodo de consultar lista de Clientes
		if($_GET['action']=="listar" && $_GET['id']==0){
			
			$resulClie = array();
			
			for($b=0;$b<count($clientes);$b++){
				$resulClie[$b]= array("documento"=>$clientes[$b][0],"nombre"=>$clientes[$b][1],"estado"=>$clientes[$b][3]);
			}
			
			header("HTTP/1.1 200 OK");
			echo "Clientes: ",json_encode($resulClie), "\n";
			exit();
		}
		
		//Metodo Consultar Cliente especifico
		if($_GET['action']=="listar" && $_GET['id']<>0){
			
			
			$key = array_search($_GET['id'], array_column($clientes, 0));
			
			if(is_numeric($key)){
				$result = array("documento"=>$clientes[$key][0],"nombre"=>$clientes[$key][1],"Saldo"=>$clientes[$key][2],"estado"=>$clientes[$key][3]);
			}else{
				$key = array_search($_GET['id'], array_column($clientes, 0));
				$result = array("Sin Resultado",$key);
			}
			
			header("HTTP/1.1 200 OK");
			echo json_encode($result);
			exit();
		}
      
	  
}


//Metodo para realizar actualizacion 1-recargar cuenta 2-Transferencia entre cuentas
if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
	//Metodo de recargar cuenta
	if($_GET['action']=="recargar"){
			
			$numDocRec = $_GET['document'];
			$valorRec = $_GET['valor'];
			
			$key = array_search($numDocRec, array_column($clientes, 0));
			
			if(is_numeric($key)){
				$clientes[$key][2]=$clientes[$key][2]+$valorRec;
				$result = array("Documento Recarga"=>$clientes[$key][0],"Estado"=>"Aprobado","Nuevo Saldo"=>$clientes[$key][2]);
			}else{
				$result = array("El documento no existe ",$numDocRec);
			}
						
			header("HTTP/1.1 200 OK");
			echo "Resultado Recarga: ",json_encode($result), "\n";
			exit();
	}
	
	//Metodo transferencia entre cuentas
	if($_GET['action']=="transferir"){
			
			$numDoc1 = $_GET['documentOrigen'];
			$numDoc2 = $_GET['documentDestino'];
			$valorRec = $_GET['valor'];
			
			$key1 = array_search($numDoc1, array_column($clientes, 0));
			$key2 = array_search($numDoc2, array_column($clientes, 0));
			
			if(is_numeric($key1) && is_numeric($key2)){
				if($valorRec <= $clientes[$key1][2]  && $valorRec>0){
					$clientes[$key2][2] = $clientes[$key2][2]+$valorRec;
					$clientes[$key1][2] = $clientes[$key1][2]-$valorRec;
					$result = array("Estado"=>"Aprobado","Valor Transferido"=>$valorRec,"Nuevo Saldo Cuenta Origen"=>$clientes[$key1][2]);
				}else{
					$result = array("Error ", "Saldo Insuficinte");
				}
			}else{
				$result = array("Error ", "El documento de Origen o Destino no existe!");
			}
						
			header("HTTP/1.1 200 OK");
			echo "Resultado transferencia: ",json_encode($result), "\n";
			exit();
	}

}


header("HTTP/1.1 400 Bad Request");

?>
