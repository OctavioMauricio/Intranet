<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>phpMyDataGrid Sample</title>
<link type='text/css' rel='stylesheet' href='style.css' />
<?php 
	#es: esta es la forma RECOMENDADA de incluir los archivos auxiliares (CSS,JS)
	#en: this is the recommended way to include the auxiliary files (CSS,JS)
	include_once('class/phpmydatagrid.class.php'); 
	echo set_DG_Header("js/","css/", " /", "lightgray");
?>
<style type="text/css">
    #formFiltro { padding:0px; }
    #formFiltro label{ margin-left:10px; display:block; width:80px; float:left; }
    #formFiltro input{ margin-right:10px; float: left; width:130px; }
    
</style>
<script type="text/javascript" language="javascript">
    /* function to set new filter */
    function setFilter(){
		parameters = "&fname=" + DG_gvv('fname') + 
					 "&lname=" + DG_gvv('lname') + 
					 "&bdate=" + DG_gvv('bdate') +
                     "&job_id=" + DG_gvv('job_id') +
                     "&top_search=1";    // This variable MUST be included to success
        DG_Do('filter', parameters);
    }

    function resetFilter(){
		DG_svv('fname', '');
		DG_svv('lname', '');
		DG_svv('bdate', '');
		DG_svv('job_id', '');
        DG_Do('filter', "&top_search=1");
        DG_Slide("formFiltro",{duration:.2}).swap()
    }
    
    function activateSearchBox(){
        curDisplay = document.getElementById('formFiltro').style.display;
        DG_Slide("formFiltro",{duration:.2}).swap()
        if (curDisplay=='none') document.getElementById('fname').focus();
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
			<h2>Multi search/filter</h2>
			<div id='descripcion'>
				Este ejemplo muestra una forma fácil de implementar búsquedas o filtros por varios campos
			</div>
			<div id='description'>
				This sample show an easy way to implement search or filter by several fields
			</div>
			<div id='dg'> 
                <div class="dgTable" style="width: 100%;">
                    <form class="dgToolbar" method="post" name="formFiltro" id="formFiltro" style="display: none; height: 80px;">
                    <table style="margin-top: 10px; width: 100%;">
                        <tr>
                            <td style="width: 250px;"><label>First Name:</label> <input id="fname" name="fname" type="text" /></td>
                            <td style="width: 270px;"><label>Last Name:</label> <input id="lname" name="lname" type="text" /></td>
                            <td style="width: 250px;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><label>Birth Date:</label> <input id="bdate" name="bdate" type="text" /></td>
                            <td><label>Job:</label>
                                <?php
                                    #es: En este ejemplo crearemos un objeto temporal del datagrid para conectar a la base de datos y hacer las consultas, pero usted puede usar su propia conexion y proceso de consulta
                                    #en: in this sample will create an temporary object to datagrid to connect with the DB and make queries, but you can use your own connection and query process
                                    $tmpObj = new datagrid();
                                	
                                	#es: Realizar la conexión con la base de datos
                                	#en: Connect with database
                                	$tmpObj -> conectadb("127.0.0.1", "user", "password", "guru_sample_a");
                                    
                                    $strSQL = "select job_id, job_desc from jobs order by job_desc";
                                    $arrData = $tmpObj -> SQL_query($strSQL);
                                    echo "<select id='job_id' name='job_id' style='width:150px'>";
                                    echo "<option value=''>(Select job to search)</option>";
                                    foreach ($arrData as $jobs){
                                        echo "<option value='" . $jobs['job_id'] . "'>" . $jobs['job_desc'] . "</option>";
                                    }
                                    echo "</select>";
                                    unset($tmpObj);
                                ?> 
                            </td>
                            <td>
                                <a class="fbutton" href="javascript:setFilter()"><span class="buttonImage"><img src="images/search.gif" alt="Search" title="Search" class="dgImgLink" border="0" /></span></a>
                                <a class="fbutton" href="javascript:resetFilter()"><span class="buttonImage"><img src="images/cancel_search.gif" alt="Search" title="Search" class="dgImgLink" border="0" /></span></a>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    </form>
                </div>
            	<!-- en: This is the recommended way to include a datagrid into your proyects, 2 files, one for the html template and other for the datagrid, if you're using templates, please check the "templates sample" -->
            	<!-- es: Esta es la forma recomendada de incluir un grid en sus proyectos, 2 archivos, 1 para la plantilla y otro para el datagrid, si esta utilizando "templates" por favor mirar el ejemplo de "plantillas" -->
				<?php include_once("multi_filter_external_sample_grid.php"); ?>
			</div>
            <small><em>Tip: Click over the data to edit it directly in the grid<br />
            Sugerencia: Haga clic sobre los datos para editar directamente en el grid</em></small>
		    <input type="button" onclick='location.href="../samples.php"' style="float:right;" value="&lt;--" />
		</td>
	  </tr>
	</table>
</body>
</html>
