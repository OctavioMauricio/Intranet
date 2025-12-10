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
<script type="text/javascript" language="javascript">
	function doHelp(){
		DG_opacity("DG_myOwnDIV", 0, 0, 1); 
		DG_hss("DG_myOwnDIV", "block"); 
		DG_opacity("DG_myOwnDIV", 0, 100, 500); 
		DG_centrar("DG_myOwnDIV");
	}
	
	function closeHelp(){
		DG_opacity("DG_myOwnDIV", 100, 0, 500); 
		setTimeout('DG_hss("DG_myOwnDIV","none")',600);
	}
</script>
</head>

<body>
	<div id='ghead'>
    	<div id='glogo'>
			<a href="index.php">phpMyDataGrid Professional - Sample of use</a>
        </div>
    </div>
	<table border="0" id="bg">
	  <tr>
		<td id="content">
			<h2>HelpBox Sample</h2>
			<div id='descripcion'>
				Este ejemplo enseña como crear una ventana para acciones con el formato de las ventanas del grid
			</div>
			<div id='description'>
				This example shows how to create a window for action with the same feel and look than the grid ones
			</div>
			<div id='dg'>
            	<!-- en: This is the recommended way to include a datagrid into yout proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<div id='DG_myOwnDIV' align='left' class='dgSearchDiv' style="width:400px; height:200px;" >
					<span class='dgSearchTit' onmousedown='DG_clickCapa(event, this, "DG_myOwnDIV")' onmouseup='DG_liberaCapa("DG_myOwnDIV")'>
						<img border='0' src='box_sample/help.png' alt='Help' title='Help' width='16' height='16' />
						Help
					</span>
					<img style='cursor:pointer; float:right' src='box_sample/close.gif' alt='Close' title='Close' onclick='closeHelp();' />
					<div id='DG_subnuevodiv' class='dgInnerDiv' style='text-align:justify; height:170px;'>
                    	<p style="margin:10px">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam elementum magna ut odio ultrices eu vehicula mi convallis. Morbi luctus arcu ac augue sagittis lobortis. Maecenas ut vulputate tellus. Suspendisse mattis eleifend imperdiet. Mauris mollis bibendum quam, eget feugiat justo tempus vitae. Proin feugiat metus non purus ornare sed blandit magna blandit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent dictum adipiscing aliquet. Integer pellentesque porta lacus at interdum. Nam neque libero, varius in commodo vitae, dignissim sed felis. Quisque id arcu nec est consectetur lobortis. Fusce fringilla mi suscipit risus malesuada sit amet vehicula mauris tempor. Suspendisse potenti. Suspendisse tristique lacinia viverra. </p>
					</div>
				</div>
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php include_once("box_sample_grid.php"); ?>
			</div>
            <small><em> Tip: Hold shift key while click the ordering arrows to order by multiple columns<br />
            Sugerencia: Mantenga presionada la tecla Shift mientras hace clic en las flechas de ordenamiento para ordenar por múltiples columnas</em></small>
		    <input type="button" onclick='location.href="../samples.php"' style="float:right;" value="&lt;--" />
		</td>
	  </tr>
	</table>
</body>
</html>