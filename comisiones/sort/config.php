<?php
   // activo mostrar errores
//    error_reporting(E_ALL);
//    ini_set('display_errors', '1');
$host     = "localhost";
$user     = "tnasolut_data_studio";
$password = "P3rf3ct0.,";
$dbname   = "tnasolut_sweet";
date_default_timezone_set('UTC'); // hora de la base de datos
$userTimeZone = new DateTimeZone('America/Santiago');  // hora de chile
$hoy = date("d-m-Y H:i:s");


function DbConnect($dbname){
    $server   = "localhost";
    $user     = "tnasolut_data_studio";
    $password = "P3rf3ct0.,";
    // me conecto a la Base de Datos
    $conn = new mysqli($server, $user, $password);
    if ($conn->connect_error) { die("No me pude conectar a servidor localhost: " . $conn->connect_error); }
    $dummy = mysqli_set_charset ($conn, "utf8");    
    $bd_seleccionada = mysqli_select_db($conn, $dbname);
    if (!$bd_seleccionada) { die ('No se puede usar '.$dbname.' : ' . mysql_error()); }
    return($conn);
}

function horacl($date) {
    global $userTimeZone;
    $dateNeeded = new DateTime($date); 
    $dateNeeded->setTimeZone($userTimeZone);
    return($dateNeeded->format('d-m-Y H:i:s')) ;
}


function datos_de_venta($fecha_ini, $fecha_fin, $vendedores, $tipo_factura) {
	$conn = DbConnect("tnasolut_sweet");

    // Eliminar la tabla temporal si ya existe en la sesión
    $conn->query("DROP TEMPORARY TABLE IF EXISTS ventas_comisiones_tmp;");
    
    // Crear la tabla temporal con los datos procesados
    $sql = 'CREATE TEMPORARY TABLE ventas_comisiones_tmp AS 
    SELECT
		@dolar := COALESCE(ROUND((SELECT valor_moneda FROM tnasolut_monedas.valor_moneda USE INDEX (id_moneda) 
                          WHERE DATE(fecha_moneda) = CURDATE() AND id_moneda = 2), 2), 1) * 1 AS dolar,
		@uf := COALESCE(ROUND((SELECT valor_moneda FROM tnasolut_monedas.valor_moneda USE INDEX (id_moneda) 
                       WHERE DATE(fecha_moneda) = CURDATE() AND id_moneda = 6), 2), 1) * 1 AS uf,
		@dolar_a_uf := ROUND(@dolar / @uf,4)  as dolar_a_uf,				  
		@moneda := CASE
		              WHEN cu.symbol = "USD" THEN "USD"
		              WHEN cu.symbol = "$" THEN "$"
		              ELSE "UF"
		           END AS moneda,
		fa.number					  AS fac_num,
		CONCAT("https://sweet.icontel.cl/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DAOS_Invoices%26action%3DDetailView%26record%3D",fa.id) AS fac_url,		
		fa.invoice_date	              AS fac_fecha, 
		ROUND(fa.subtotal_amount,2)*1 AS fac_neto,
		@fac_costo := ROUND(SUM(pr.product_cost_price),2)*1	AS fac_costo,
		@fechacierre  := if(aqc.fechadecierre_c >= "2023-08-01",aqc.fechadecierre_c, CAST("2016-04-30" as DATE)) AS fechacierre,
		@anomescierre := CONCAT(YEAR(@fechacierre),	LPAD(MONTH(@fechacierre), 2, "0")) AS anomescierre,
		@cierre_ini   := IF( DAY(@fechacierre) > 25, 
						   date(CONCAT(YEAR(@fechacierre), "-", MONTH(@fechacierre), "-","26")),
						   date(CONCAT(YEAR(DATE_ADD(@fechacierre,INTERVAL -1 MONTH)),
						   "-",MONTH(DATE_ADD(aqc.fechadecierre_c,INTERVAL -1 MONTH)),  "-","26"))) AS cierre_ini,
		@cierre_fin  := IF( DAY(@fechacierre) > 25, 
						  date(CONCAT(YEAR(DATE_ADD(@fechacierre,INTERVAL 1 MONTH)),						  
						  "-",MONTH(DATE_ADD(@fechacierre,INTERVAL 1 MONTH)), "-","25")),	  		
						  date(CONCAT(YEAR(@fechacierre), "-", MONTH(@fechacierre), "-","25")) )  AS cierre_fin,
		@meta_uf 	:= (select meta_por_ejecutivo from metas_de_venta WHERE anomes = @anomescierre)*1  AS meta_uf,
		@cierre_uf  := ROUND((SELECT  
			CASE  
				WHEN @moneda = "USD" THEN sum(aq.subtotal_amount * @dolar / @uf)   
				WHEN @moneda = "$" THEN sum(aq.subtotal_amount / @uf)	  
				ELSE sum(aq.subtotal_amount)*1
			END	 						AS neto_uf 
			FROM aos_quotes  			As aq 	  
			LEFT JOIN aos_quotes_cstm	AS aqc 	ON aqc.id_c = aq.id
			LEFT JOIN currencies 		AS cu 	ON cu.id = aq.currency_id	
			where 1  
			&& !aq.deleted
			&& aqc.fechadecierre_c >= "2023-01-01"
			&& aqc.etapa_cotizacion_c = "cerrado_aceptado_cot" 
			&& aqc.fechadecierre_c BETWEEN @cierre_ini AND @cierre_fin
			&& aq.assigned_user_id = fa.assigned_user_id
			Group by   aq.assigned_user_id 	 ) ,2)*1 as cierre_uf,		
		@recurrente   := if(fa.status IN ("vigente","Vigente_Anual", "vigente_bienal"), TRUE,FALSE) as recurrente,	
		@cumplimiento := ROUND(if(@recurrente, (@cierre_uf/@meta_uf)*100, "100.00"),2)*1  AS cumplimiento,
		@comision := (CASE 
						WHEN (@recurrente) 
							THEN (SELECT mc.comi_ejecutiva 
									FROM metas_cumplimiento as mc 
									WHERE 1  
									&& mc.tipo = "recurrente"  
									&& @cumplimiento >= mc.tramo_ini
									&& @cumplimiento <= mc.tramo_fin)
							ELSE (SELECT comi_ejecutiva FROM metas_cumplimiento WHERE tipo = "unico" )
						END)*1 					AS comision,
		@neto_uf := ROUND(CASE  
                    WHEN @moneda = "USD" THEN fa.subtotal_amount * @dolar_a_uf  
                    WHEN @moneda = "$" THEN fa.subtotal_amount / @uf  
                    ELSE fa.subtotal_amount*1  
                  END, 2) * 1 AS neto_uf,
		@costo_uf := ROUND(CASE  
					WHEN cu.symbol = "USD" THEN ROUND(SUM(pr.product_cost_price)*@dolar_a_uf,2)*1
					WHEN cu.symbol = "$"   THEN SUM(pr.product_cost_price) / @uf
					ELSE SUM(pr.product_cost_price)*1
				 END,2)*1 AS costo_uf,
		@neto_comi_uf := ROUND(CASE 
									WHEN fa.status = "vigente" 		 	THEN @neto_uf*1 
									WHEN fa.status = "Facturado" 		THEN @neto_uf*1	
									WHEN fa.status = "Vigente_Anual" 	THEN @neto_uf/12
									WHEN fa.status = "vigente_bienal"  	THEN @neto_uf/24
								END,2)*1 AS neto_comi_uf,
		ROUND(@neto_comi_uf * (@comision/100),2)*1 as comision_uf,
		ROUND(IF(@recurrente, @neto_comi_uf * 0.25, @neto_comi_uf * 0.04),2)*1 as comi_sgv_uf,
		CASE
			WHEN fa.status = "Pendiente" 		THEN "Pendiente" 
			WHEN fa.status = "Lista" 			THEN "Lista para Facturar" 
			WHEN fa.status = "vigente" 			THEN "Mensual"  
			WHEN fa.status = "Vigente_Anual" 	THEN "Anual" 
			WHEN fa.status = "vigente_bienal" 	THEN "Bienal"  
			WHEN fa.status = "Facturado" 		THEN "Unica" 
		    ELSE fa.status 
		END												AS fac_estado,	
		fc.num_nota_venta1_c							AS fac_nv, 
		fa.quote_number									AS fac_coti,
		ac.name											AS fac_cliente,
		CASE 
			WHEN us.first_name IS NULL OR us.first_name = "" THEN us.last_name 
       		ELSE concat(us.first_name," ",us.last_name)
       	END AS fac_vendedor
	FROM aos_invoices 				AS fa USE INDEX(fecha_fac)
		LEFT JOIN users   				AS us 	ON fa.assigned_user_id = us .id
		LEFT JOIN accounts				AS ac 	ON ac.id = fa .billing_account_id
		LEFT JOIN aos_invoices_cstm		AS fc	ON fa.id = fc.id_c 
		LEFT JOIN aos_quotes 			AS qu 	ON qu.number = fa.quote_number
		LEFT JOIN aos_quotes_cstm		AS aqc  ON aqc.id_c = qu.id
		LEFT JOIN currencies 			AS cu 	ON cu.id = fa.currency_id
		LEFT JOIN aos_products_quotes 	AS pr 	ON pr.parent_id = fa.id
	WHERE 1
		&& fa.invoice_date BETWEEN "'.$fecha_ini.'" AND "'.$fecha_fin.'"
		&& fa.status != "Anulada" 
		&& fa.status != ""
		&& !fa.deleted
	GROUP BY fa.number
	ORDER BY us.user_name, fa.status;';
    
    if ($conn->query($sql) === TRUE) {
        // Ejecutar la consulta de informe.php sobre la tabla temporal
        $query = 'SELECT  
                vc.fac_num,
                vc.fac_url,
                vc.fac_fecha,
                vc.fechacierre,
                vc.meta_uf,
                vc.cierre_uf,
                vc.cumplimiento,
                vc.comision,
                SUM(vc.neto_uf) AS neto_uf,
                SUM(vc.costo_uf) AS costo_uf,
                SUM(vc.neto_uf) - SUM(vc.costo_uf) AS margen_uf,
                SUM(vc.neto_comi_uf) AS neto_comi_uf,
                SUM(vc.comision_uf) AS comision_uf,
                SUM(vc.comi_sgv_uf) AS comi_sgv_uf,
                vc.fac_estado,
                vc.fac_coti,
                vc.fac_nv,
                vc.fac_cliente,
                vc.fac_vendedor
            FROM ventas_comisiones_tmp AS vc 
            WHERE vc.fac_fecha BETWEEN "'.$fecha_ini.'" AND "'.$fecha_fin.'"'. 
                  $vendedores . $tipo_factura . '
            GROUP BY vc.fac_num  ;';
        $result = $conn->query($query);
		$conn->close();       
        return $result;
    } else {
        die("Error al crear la tabla temporal: " . $conn->error);
    }
    
}


