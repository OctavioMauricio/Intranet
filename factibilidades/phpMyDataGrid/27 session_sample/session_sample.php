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
			<h2>Session data and default value for fields</h2>
			<div id='descripcion'>
				Este ejemplo enseña a utilizar sessiones, y definir valores por defecto para campos
			</div>
			<div id='description'>
				This sample teach how to use sessions, and to define default values for fields
			</div>
			<div id='dg' align="center"> 
            	<form method="post" action="session_sample_login.php">
                    <table border="1">
                        <tr>
                            <td>Username (demo):</td>
                            <td><input type="text" name="username" value="demo" /></td>
                        </tr>
                        <tr>
                            <td>Password (demo):</td>
                            <td><input type="password" name="password" value="demo" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><input type="submit" name="submit" value="Submit" /></td>
                        </tr>
                    </table>
                </form>
			</div>
		    <input type="button" onclick='location.href="../samples.php"' style="float:right;" value="&lt;--" />
		</td>
	  </tr>
	</table>
</body>
</html>