<?php   
	require_once __DIR__ . '/session_config.php';
    include_once("includes/config.php");
    $sql ="Select * from recurrencias where 1 ";
    if(isset($_POST["Recurrencia"])) $Recurrencia = genera_condicion($_POST["Recurrencia"], "recurrencia");
    if(isset($_POST["Categoria"])) $Categoria = genera_condicion($_POST["Categoria"], "categoria");
    if(isset($_POST["Producto"])) $Producto = genera_condicion($_POST["Producto"], "producto");
     $_SESSION["query"] = "Select * from recurrencias where 1 " . $Recurrencia . $Categoria . $Producto;       
    $_SESSION["query_contactos"] = "Select
        a.name 									as empresa, 
        a.phone_office 							as office_tel,
        ac.estatusfinanciero_c 					as estado,
        CONCAT(ct.first_name,' ',ct.last_name)	as contacto,
        ct.title 								as cargo,
        ct.lead_source 							as tipo_contacto,
        ct.phone_mobile 						as celular,
        ct.phone_work 							as telefono,
        e.email_address 						as email
    from recurrencias 				as re
    LEFT JOIN accounts 				as a  ON a.id	= re.cliente_id
    LEFT JOIN accounts_contacts 	as co ON a.id	= co.account_id
    LEFT JOIN contacts				as ct ON co.contact_id = ct.id
    LEFT JOIN accounts_cstm 		as ac ON ac.id_c = a.id
    LEFT JOIN email_addr_bean_rel 	as ea ON ea.bean_id = ct.id
    LEFT JOIN email_addresses		as e  ON e.id = ea.email_address_id 
    where 1
    && ! (ac.estatusfinanciero_c = 'Baja')
    && !co.deleted 
    && !ct.deleted 
    && !ea.deleted 
    && !e.deleted " . $Recurrencia . $Categoria . $Producto ;
    $_SESSION["agrupar"]= " GROUP BY co.id ";
    $_SESSION['orden'] = " ORDER BY `cliente` ASC";
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
<?PHP include_once("../meta_data/meta_data.html"); ?>   
  <title>Servicios Activos iContel Telecomunicaciones</title>
   <style type="text/css">
   	html, body, div {
   		margin:0;
   		padding:0;
   		height:100%;
   		margin-left: 0px;
   		margin-top: 0px;
   		margin-right: 0px;
   		margin-bottom: 0px;
   	}
    table{
           border: none;
           color: #1F1D3E;
           color: white;
           font-size: 15px;
           border-collapse: collapse;
           background-color: #19173C;
           border-collapse: collapse;

       }   
   	iframe {
        border: none;
        border-collapse: collapse;
        padding: 0;
        margin: 0;
        display:block; 
        width:100%;  
        height: 90%;
    }
        footer {
          background-color: white;
          position: absolute;
          bottom: 0;
          width: 100%;
          height: 25px;
          color: gray;
          font-size: 12px;
        }
        /* unvisited link */
        a:link {
          color: darkslategrey;
        }

        /* visited link */
        a:visited {
          color: white;
        }

        /* mouse over link */
        a:hover {
          color: darkgrey;
          font-size: 20px;
          font-weight: bold;
        }

        /* selected link */
        a:active {
            color: blue;        
	    }		
  </style>
  </head>
  <body>
   <table align="center" border="0" width="100%" bgcolor="#1F1D3E">
        <tr align="center" style="color: white;background-color: #1F1D3E;">
          <th width="200"  valign="top" align="left"><img src="images/logo_icontel_azul.jpg"  height="60" alt=""/></th>
          <td>
              <table width="100%" height="100%" border="0" bgcolor="#1F1D3E">
                  <tr>
                      <th colspan="2" align="center" style="font-size: 20px;">Contactos por cliente con Servicios Activos</th>
                  </tr>
                  <tr style="color: white;background-color: #1F1D3E;">
                      <td align="center" style="font-size: 12px;">(click sobre los ti&aacute;tulos para ordenar)</td>
                      <td align="right" bgcolor="#1F1D3E"><a href="index.php"><img src="../images/volver_azul.png" width="30" height="30" alt=""/></a></td>
                  </tr>
              </table>
          </td>    
        </tr>
    </table>
  <iframe style="margin: 0 0 0 0;" src="sort_contactos/tabla.php"></iframe>
   </body>
    <?PHP echo $footer;?>   
</html>
