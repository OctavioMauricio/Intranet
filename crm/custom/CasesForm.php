<?php

// activo mostrar errores
     error_reporting(E_ALL);
   ini_set('display_errors', '1');


if($_POST) {
	foreach ($_POST as $clave=>$valor) {
        echo "El valor de ".$clave." es: ".$valor." <br>";
   	}
}
// if(!defined('sugarEntry') || !sugarEntry) die('No válido');
$date = new DateTime();
// echo $date->format('r');
global $db;
global $current_user;
$default_assigned_user_id = "1";
$script="";

if($_SERVER['REQUEST_METHOD']==='GET'){
    if(isset($_GET['account_id']) && $_GET['account_id']!==''){
        $account_id = cleanInput($_GET['account_id']);
        $product = cleanInput($_GET['servicio']);
        $contact_id = cleanInput($_GET['contact_id']);
        $account = BeanFactory::getBean('Accounts', $_GET['account_id']);
        $account_name = isset($account) ? $account->name :  "";
        $product_name = !empty($product) ? $product : "No Encontrado";
        $proveedor = !empty($_GET['proveedor']) ? $_GET['proveedor'] : "No Encontrado";

        $body = "<div class='alert alert-success' role='alert'>";
        $body .= "<br/>ID Cuenta recibida: $account_id";
        $body .= "<br/>AOS Product recibido: $product_id";
        $body .= "</div>";
        $body .= createFormHTML($product_name, $account_name, $account_id, $proveedor, $contact_id);
    }
    else{
        $body = "<div class='alert alert-warning' role='alert'>";
        $body .= "<br/><h3> No se han detectado los datos de Cuenta y Producto </h3>";
    }
}else if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['account_id']) && $_POST['account_id']!==''){
        echo "Creando el Caso";
        echo "<p>".$_POST['account_id']."</p>";
        echo "<p>".$_POST['servicio_afectado']."</p>";
        try{
            $caso = new Case();
            $caso->name =  $_POST['asunto'];  
            $caso->account_id = $_POST['account_id'];
            $caso->servicio_afectado_c = $_POST['servicio_afectado'];
            $caso->proveedor_c = $_POST['proveedor'];
            $caso->codigo_servicio_c=$_POST['codigo_servicio'];
            $caso->fecha_resolucion_estimada_c = $_POST['fecharesol'];
            $caso->state = $_POST['estado'];
            $caso->responsable_c = $_POST['responsable'];
            $caso->contact_id_c = $_POST['contacto_id'];
            $caso->priority = $_POST['prioridad'];
            $caso->horario_c = $_POST['horario'];
            $caso->tipo_caso_c = $_POST['casotipo'];
            $caso->categoria_c = $_POST['categoria'];
            $caso->description = $_POST['descripcion'];
            $caso->created_by = $current_user->id;  
            $caso->assigned_user_id = $current_user->id;
            $caso->save();
            echo "Sali";
            SugarApplication::redirect('index.php?module=Cases&action=DetailView&record='.$caso->id);
            // echo "<div class='alert alert-success' role='alert'>
            // <br/>ID Caso Creado: $caso->id
            // </div>
            // <p> Redireccionando al caso....</p>";
            

        }catch(Exception $e){
            echo "<div class='alert alert-danger' role='alert'><p> Error en la creacion del caso </p></div>".$e->getMessage();
        }
        
    }else{
        echo "<div class='alert alert-danger' role='alert'>
        No se pudo crear el Caso revisar datos ".$_POST['product_id'];
        echo "<p>Product id: ".$_POST['product_id']."</p>";
        echo "<p>Account id: ".$_POST['account_id']."</p></div>";
        //header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}

//Mostrar la pagina
renderHTML($body,$script);


function cleanInput($data){
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $data = trim($data);
    $data = filter_var($data, FILTER_SANITIZE_STRING);
    $data = preg_replace('/[^a-zA-Z0-9\-]/i', '', $data);
    return $data;
}

function createFormHTML($product_name, $account_name, $account_id, $proveedor, $contact_id){

    $users_options = createUsersDropDown();
    date_default_timezone_set("America/Santiago");
    $hoy = date("d-m-Y H:i");
    $fecha_est_Resolucion = strtotime ( '+4 hour' , strtotime ($hoy) );
    $fecha_est_Resolucion = date ( 'Y-m-d' , $fecha_est_Resolucion);
    
    return "
        <form method='post'>
            <div class='row g-3'>
                <input type='hidden' name='account_id' id='account_id' value='$account_id'>
                <input type='hidden' name='contact_id' id='contact_id' value='$contact_id'>
                <input type='hidden' name='product_name' value='$product_name'>
                <input type='hidden' name='proveedor' value='$proveedor'>
                <div class='col-md-6'>
                    <strong>Producto:</strong><span> $product_name </span>
                </div>
                <div class='col-md-6'>
                    <strong>Cuenta:</strong><span id='account_name'> $account_name </span>
                </div>
                <div class='col-md-6'>
                    <strong>Proveedor:</strong><span> $proveedor </span>
                </div>
            </div>
            <div class='row g-3'>
                <div class='col-md-12'>
                    <label for='inputSubject'>Titulo</label>
                    <input type='text' class='form-control' id='inputSubject' name='inputSubject' placeholder='Titulo' required>
                </div>
            </div>
            <div class='row g-3'>
                <div class='col-md-6'>
                    <label for='inputPriority'>Prioridad</label>
                    <select id='inputPriority' name='inputPriority' class='form-control' required>
                        <option value = 'Alta'>Alta</option>
                        <option value = 'Media' selected>Media</option> 
                        <option value = 'Baja' selected>Baja</option>
                    </select>
                </div>
                <div class='col-md-6'>
                    <label for='inputStatus'>Estado</label>
                    <select id='inputStatus' name='inputStatus' class='form-control'>
                        <option value='Abierto' selected>Abierto</option>
                        <option value='Open_Assigned'>Asignado</option>
                        <option value='Open_Pending Input'>Pendiente de Datos</option>
                        <option value='Closed'>Cerrado</option>
                    </select>
                </div>
                <div class='col-md-6'>
                    <label for='inputType'>Tipo</label>
                    <select id='inputType' name='inputType' class='form-control'>
                        <option value = 'Continuidad operacional' selected>Continuidad Operacional</option>
                        <option value = 'Sujeto a Cobro'>Sujeto a Cobro</option> 
                        <option value = 'Termino de servicio'>Término de Servicio</option>
                        <option value = 'proyectodemo'>02 Proyecto DEMO</option>
                        <option value = 'esperafact'>02 Esperando Factibilidad</option>
                    </select>
                </div>
                <div class='col-md-6'>
                    <label for='responsable'>Responsable</label>
                    <select id='responsable' name='responsable' class='form-control'>
                        <option value = 'Validando' selected>Validando</option>
                        <option value = 'TNA'>TNA</option> 
                        <option value = 'Preoveedor'>Proveedor</option>
                        <option value = 'Cliente'>Cliente</option>
                    </select>
                </div>
                <div class='col-md-6'>
                    <label for='categoria'>Categoria</label>
                    <select name='categoria' id='categoria' class='form-control'>
                            <option value = 'Cableado' selected>Cableado</option>
                            <option value = 'Enlace'>Enlace</option> 
                            <option value = 'Enlace Caido'>Enlace Caido</option>
                            <option value = 'Facturacion'>Facturacion</option>
                            <option value = 'Fuera de Horario'>Fuera de Horario</option>
                            <option value = 'Hosting / Correos'>Hosting / Correos</option>
                            <option value = 'Nuevo requerimiento / oportunidad'>Nuevo requerimiento / oportunidad</option>
                            <option value = 'Otros'>Otros</option>
                            <option value = 'Soporte'>Soporte</option>
                            <option value = 'Soporte contrato mensual'>Soporte contrato mensual</option>
                            <option value = 'Sujeto a cobre'>Sujeto a cobro</option>
                            <option value = 'Telefonia'>Telefonia</option>                                         
                            <option value = 'Termino de contrato'>Termino de contrato</option>
                    </select>
                </div>
                <div class='col-md-6'>
                    <label for='assignedUser'>Usuario Asignado</label>
                    $users_options
                </div>
                <div class='col-md-6'>
                    <label for='horario'>Horario</label>
                    <input type='radio' id='horario' name='horario' value='Horario habil'>Habil<br>
                    <input type='radio' id='horario' name='horario' value='Fuera de horario'>Fuera de Horario
                </div>
                <div class='col-md-6'>
                    <label for='fecharesol'>Fecha Estimada Resolucion</label>
                    <input name='fecharesol' type='date' id='fecharesol' size='30' value = '$fecha_est_Resolucion'>
                </div>
            </div>
            <div class='row g-3'>
                <div class='col-md-12'>
                    <label for='description'>Descripción</label>
                    <textarea class='form-control' aria-label='With textarea' id='description' name='description' rows='8' required></textarea>
                </div>
                
            </div>
            </br>
            <div class='row'>
                <div class='col-12'>
                    <button type='submit' class='btn btn-primary'>Guardar</button>
                </div>
            <div class='row g-3'>
        </form>";

        
}

function renderHTML($body,$scipt){
    
    $html .= <<<EOQ
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
            <link href="custom/style.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <title>TNA - Formulario de Casos -> Productos</title>
            <script>
                $script
            </script>
        </head>
        <body>
            <div class="container" style="max-width: 1100px">
                $body
            </div>
        </body>
    </html>
EOQ;
    echo $html;
}

function createUsersDropDown(){
    $users_html = "";
    $sql = "SELECT id, concat(first_name,' ', last_name) as name FROM users WHERE deleted = 0 AND id <> 1";
    $result = $GLOBALS['db']->query($sql);
    while($row = $GLOBALS['db']->fetchByAssoc($result) )
    {
        //Use $row['id'] to grab the id fields value
        $id = $row['id'];
        $name = $row['name'];
        $users_html .= "<option value='$id'>$name</option>";
    }
    $html = "<select id='assignedUser' name='assignedUser' class='form-control' required>
            <option value='' selected>Elige un Usuario</option>
                $users_html
            </select>";

    return $html;
}