<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpMyDataGrid Sample</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<?php 
	#es: esta es la forma RECOMENDADA de incluir los archivos auxiliares (CSS,JS)
	#en: this is the recommended way to include the auxiliary files (CSS,JS)
	include_once('class/phpmydatagrid.class.php'); 
	echo set_DG_Header("js/","css/", " /", "bluesky");
?>
<style type="text/css">
	.activedata{background:#D3FFC9;}
	.sinfactibilidad{background:lightgrey;}
	.inactivo{font-weight:bold;color:grey;}
	
    #formFiltro { padding:0px; }
    #formFiltro label{ margin-left:10px; display:block; width:80px; float:left; }
    #formFiltro input{ margin-right:10px; float: left; width:130px; }
    
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<script type="text/javascript" language="javascript">

	function Notificaciones(){ window.location = "envia_notificaciones.php";;	}	
	function Estado(){ window.location = "index.php?tabla=estado";;	}
	function Enlaces(){ window.location = "cab_det.php";;	}
	function Proveedor(){ window.location = "index.php?tabla=proveedor";;	}
	function Tipo_Enlace(){ window.location = "index.php?tabla=tipo_enlace";;	}
    function activateSearchBox(){
        curDisplay = document.getElementById('formFiltro').style.display;
        DG_Slide("formFiltro",{duration:.2}).swap()
        if (curDisplay=='none') document.getElementById('fname').focus();
    }

</script>


<script type='text/javascript' language='javascript'>
	var lastID = ""; /* Used to store the last selected Row ID */
	var currentGrid= ""; /* Used to store the Grid ID */
	
	function viewDetails(id){
		/* Restore last selected row */
        oldClass = DG_gvv('dg' + currentGrid+'Choc'+lastID);
		if (lastID!="" && typeof(oldClass) != 'undefined') DG_goo('dg' + currentGrid +'TR'+lastID).className = oldClass;
		
		/* Store Last selected row info */
		currentGrid = ac(); lastID = id;
        
		/* Set the new class (background) for the selected row */ 
		DG_goo('dg' + currentGrid + 'TR' + id).className='dgSelRowDetails'; 

		/* Select details Grid */
		DG_set_working_grid("2");  /* This must be the code of the grid to be updated */
		
		/* Execute Grid call */
		DG_Do("", "&e_id="+id);
	}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
	<table border="0" id="bg">
	  <tr>
		<td id="content2" style="vertical-align:top; text-align:left">
			<div id='dg'> 
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php include_once("mantenedor_cuentas.php"); ?>
			</div>
		</td>
      </tr>
	</table>
</body>
</html>