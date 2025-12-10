<?php
	#######################################################################################
	#######################################################################################
	#######################################################################################
	######                                                                           ######
	###### This sample DO NOT INCLUDE Smarty files, you must download the library    ######
	###### Please download Smarty Library from http://www.smarty.net/                ######
	######                                                                           ######
	###### Este ejemplo NO INCLUYE los archivos de la librería Smarty                ######
	###### Por favor descargue la libreria Smarty desde http://www.smarty.net/       ######
	######                                                                           ######
	#######################################################################################
	#######################################################################################
	#######################################################################################

	#es: Incluir los archivos de las librerias
	#en: Include class files
	include_once('class/phpmydatagrid.class.php'); 
	require('smarty/Smarty.class.php');

	#es: Definir el objeto de las plantillas
	#en: Define template object
	$smarty = new Smarty;

	#es: Asignar el encabezado a la variable "header"
	#en: Assign the header to the "header" variable
	$smarty->assign('header',set_DG_Header());

	#es: Definir que el datagrid retornara el HTML a una variable
	#en: Define the output HTML will be to a variable
	$returnCode = true;
	$dataGridText = "";

	#es: Incluir el archivo de definiciones del datagrid
	#en: Include datagrid definitions file
	include_once("in_templates_sample_grid.php"); 

	#es: Asignar el contenido del datagrid "datagrid"
	#en: Assign the datagrid content to the "datagrid" variable
	$smarty->assign('datagrid',$dataGridText);

	#es: Mostrar el contenido de la plantilla
	#en: Display the template content
	$smarty->display('in_templates_sample.tpl');
?>