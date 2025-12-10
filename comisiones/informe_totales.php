<?php
   require_once __DIR__ . '/session_config.php';
   include_once("includes/config.php");
	if(isset($_SESSION['query'])) {
	   $vendedores 	= $_SESSION['vendedores'];
	   $sql			= $_SESSION['query'];
	   $desde		= $_SESSION['desde'];
	   $hasta		= $_SESSION['hasta'];
	   $groupby		= " GROUP BY vc.fac_vendedor " ;
	   $_SESSION["agrupar"]	= $groupby;
	   $_SESSION['orden'] = " ORDER BY vc.fac_vendedor";
  	} else {
       exit();
   	};

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
<?PHP include_once("../meta_data/meta_data.html"); ?>   
  <title>Totales de Comisiones iContel Telecomunicaciones</title>
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
            color: blue; }   
    </style>
	 <script type="text/x-javascript">
		 function abre_url(url){
			 window.open(url, "_blank");		 
		 } 
	 </script>
  </head>
  <body>
   <table align="center" border="0"  bgcolor="#1F1D3E" width="55%">
        <tr align="center" style="color: white;background-color: #1F1D3E;">
          <th width="200"  valign="top" align="left"><img src="images/logo_icontel_azul.jpg"  height="60" alt=""/></th>
          <td>
              <table width="100%" height="100%" border="0" bgcolor="#1F1D3E">
                  <tr>
                      <th colspan="2" align="center" style="font-size: 20px;">Totales de Comisiones entre el <?PHP echo "{$desde} y el {$hasta}"; ?></th>
                  </tr>
                  <tr style="color: white;background-color: #1F1D3E;">
					<td colspan="2" align="center" style="font-size: 12px;">
					  (click sobre los ti&aacute;tulos para ordenar)
					</td>
                  </tr>
              </table>
          </td>    
        </tr>
    </table>
  <iframe style="margin: 0 0 0 0;" src="sort_totales/tabla.php" ></iframe>
   </body>
   <?PHP echo $footer;?>   
</html>
