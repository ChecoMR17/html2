<?php
include "../../global/conexion.php";
//Varibales
$Fecha_Actual = date("Y-m-d H:i:s");
$Id = isset($_POST['Id']) ? $_POST['Id'] : "";
$Cliente = isset($_POST['Cliente']) ? $_POST['Cliente'] : "";
$Obras = isset($_POST['Obras']) ? $_POST['Obras'] : "";
$Contactos = isset($_POST['Contactos']) ? $_POST['Contactos'] : "";
$Prioridad = isset($_POST['Prioridad']) ? $_POST['Prioridad'] : "";
$Proyecto = isset($_POST['Proyecto']) ? $_POST['Proyecto'] : "";
$Fecha_Inicio = isset($_POST['Fecha_Inicio']) ? $_POST['Fecha_Inicio'] : "";
$Fecha_Final = isset($_POST['Fecha_Final']) ? $_POST['Fecha_Final'] : "";
$Observaciones = isset($_POST['Observaciones']) ? $_POST['Observaciones'] : "";
$Opciones_Status = !empty($_POST['Opciones_Status']) ? $_POST['Opciones_Status'] : "A";
$Clasificacion = isset($_POST["Clasificacion"]) ? $_POST["Clasificacion"] : "";
$Id_Clasificacion = isset($_POST["Id_Clasificacion"]) ? $_POST["Id_Clasificacion"] : "";
$Nombre_Clasificacion = isset($_POST["Nombre_Clasificacion"]) ? $_POST["Nombre_Clasificacion"] : "";

$Id_Actividad = isset($_POST["Id_Actividad"]) ? $_POST["Id_Actividad"] : "";
$Fecha_Actividad = isset($_POST["Fecha_Actividad"]) ? $_POST["Fecha_Actividad"] : "";
$Nombre_Actividad = isset($_POST["Nombre_Actividad"]) ? $_POST["Nombre_Actividad"] : "";
$Descripcion_Actividad = isset($_POST["Descripcion_Actividad"]) ? $_POST["Descripcion_Actividad"] : "";


$Nombre_documento = isset($_POST["Nombre_documento"]) ? $_POST["Nombre_documento"] : "";
$Descripcion_Documento = isset($_POST["Descripcion_Documento"]) ? $_POST["Descripcion_Documento"] : "";


$salida = "";
$datos = array();
switch ($_GET['op']) {
    case 'Guardar_Ordenes_Trabajo':
        if ($Id == "") { //Insert
            $query = ejecutarConsulta("INSERT INTO Ordenes_Trabajo(Id_Cliente,Id_Obra,Id_Contacto,Id_Clasificacion,Prioridad,Proyecto,Fecha_Inicio,Fecha_Final,Observaciones,Fecha_Alta,Status) 
            VALUES('$Cliente','$Obras','$Contactos','$Clasificacion','$Prioridad','$Proyecto','$Fecha_Inicio','$Fecha_Final','$Observaciones','$Fecha_Actual','$Opciones_Status')");
        } else {
            // Validamos si si ya se había ingresado el status U
            $Validar_Status = ejecutarConsultaSimpleFila("SELECT Status,Fecha_Ejecucion,Fecha_Concluido,Fecha_Cancelacion FROM Ordenes_Trabajo WHERE Id='$Id';");
            $Fecha_Ejecucion = "";
            $Fecha_Concluido = "";
            $Fecha_Cancelacion = "";
            if ($Validar_Status['Status'] != $Opciones_Status) {
                if ($Opciones_Status == "U") {
                    $Fecha_Ejecucion = ($Validar_Status['Fecha_Ejecucion'] == "") ? ",Fecha_Ejecucion='$Fecha_Actual'" : "";
                }
                if ($Opciones_Status == "U") {
                    $Fecha_Concluido = ($Validar_Status['Fecha_Concluido'] == "") ? ",Fecha_Concluido='$Fecha_Actual'" : "";
                }
                if ($Opciones_Status == "B") {
                    $Fecha_Cancelacion = ($Validar_Status['Fecha_Cancelacion'] == "") ? ",Fecha_Cancelacion='$Fecha_Actual'" : "";
                }
            }
            $query = ejecutarConsulta("UPDATE Ordenes_Trabajo SET Id_Cliente='$Cliente',Id_Obra='$Obras',Id_Contacto='$Contactos',Id_Clasificacion='$Clasificacion',Prioridad='$Prioridad',Proyecto='$Proyecto',Fecha_Inicio='$Fecha_Inicio',Fecha_Final='$Fecha_Final',Observaciones='$Observaciones',Fecha_Modificacion='$Fecha_Actual',Status='$Opciones_Status' $Fecha_Ejecucion  $Fecha_Concluido $Fecha_Cancelacion WHERE Id='$Id'");
        }
        echo $query ? 200 : 201;
        break;
    case 'Mostrar_Clientes':
        $query = ejecutarConsulta("SELECT*FROM Clientes WHERE Status='A';");
        while ($fila = mysqli_fetch_object($query)) {
            $salida .= "<option class='text-dark' value='$fila->Id'>$fila->Nombre $fila->Apellido_P $fila->Apellido_M</option>";
        }
        echo $salida;
        break;
    case 'Buscar_Obras':
        $query = ejecutarConsulta("SELECT*FROM Obras WHERE Id_Cliente='$Cliente' AND Status='A';");
        while ($fila = mysqli_fetch_object($query)) {
            $salida .= "<option class='text-dark' value='$fila->Id'>$fila->Nombre_Obra</option>";
        }
        echo $salida;
        break;
    case 'Buscar_Contactos':
        $query = ejecutarConsulta("SELECT*FROM Contactos_Clientes WHERE Id_Cliente='$Cliente' AND Status='A';");
        while ($fila = mysqli_fetch_object($query)) {
            $salida .= "<option class='text-dark' value='$fila->Id'>$fila->Nombre $fila->Apellido_P $fila->Apellido_M</option>";
        }
        echo $salida;
        break;
    case 'Mostrar_Lista_OT':
        $query = ejecutarConsulta("SELECT O.Id,concat_ws(' ',C.Nombre,C.Apellido_P ,C.Apellido_M) AS Cliente,Ob.Nombre_Obra AS Obra,O.Proyecto,concat_ws(' ',CC.Nombre,CC.Apellido_P,CC.Apellido_M) AS Contacto, O.Prioridad,O.Fecha_Inicio,O.Fecha_Final,O.Observaciones,O.Status FROM Ordenes_Trabajo O
        LEFT JOIN Clientes C on(O.Id_Cliente=C.Id)
        LEFT JOIN Obras Ob ON (O.Id_Obra=Ob.Id)
        LEFT JOIN Contactos_Clientes CC ON(O.Id_Contacto=CC.Id);");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '';
            $status = "";
            $Prioridad = "";

            if ($fila->Status == 'A') {
                $status = '<div class="badge text-white bg-primary">Activo</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Modificar(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Agregar actividades" onclick="Mostrar_Id(' . $fila->Id . ',1)" data-toggle="modal" data-target="#Guardar_Actividades"><i class="fa-solid fa-list-check fa-beat"></i></button>
                <button type="button" class="btn btn-primary btn-sm mr-2" title="Agregar documento" onclick="Mostrar_D_D(' . $fila->Id . ',1)" data-toggle="modal" data-target="#Guardar_Documentos"><i class="fa-solid fa-cloud-arrow-up fa-beat"></i></i></button>
                <a type="button" class="btn btn-outline-secondary btn-sm mr-2" href="../Archivos/Ordenes/Formatos.php/Ordenes.php?Num_OT=' . base64_encode($fila->Id) . '" target="_blank" title="Imprimir pdf"><i class="fa-regular fa-file-pdf fa-beat"></i></a>
                ';
            } else if ($fila->Status == 'U') {
                $status = '<div class="badge text-white bg-success">Ejecución</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Modificar(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Agregar actividades" onclick="Mostrar_Id(' . $fila->Id . ',1)" data-toggle="modal" data-target="#Guardar_Actividades"><i class="fa-solid fa-list-check fa-beat"></i></button>
                <button type="button" class="btn btn-primary btn-sm mr-2" title="Agregar documento" onclick="Mostrar_D_D(' . $fila->Id . ',1)" data-toggle="modal" data-target="#Guardar_Documentos"><i class="fa-solid fa-cloud-arrow-up fa-beat"></i></i></button>
                <a type="button" class="btn btn-outline-secondary btn-sm mr-2" href="../Archivos/Ordenes/Formatos.php/Ordenes.php?Num_OT=' . base64_encode($fila->Id) . '" target="_blank" title="Imprimir pdf"><i class="fa-regular fa-file-pdf fa-beat"></i></a>
                ';
            } else if ($fila->Status == 'C') {
                $status = '<div class="badge text-white bg-secondary">Concluido</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Modificar(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Agregar actividades" onclick="Mostrar_Id(' . $fila->Id . ',1)" data-toggle="modal" data-target="#Guardar_Actividades"><i class="fa-solid fa-list-check fa-beat"></i></button>
                <button type="button" class="btn btn-primary btn-sm mr-2" title="Agregar documento" onclick="Mostrar_D_D(' . $fila->Id . ',1)" data-toggle="modal" data-target="#Guardar_Documentos"><i class="fa-solid fa-cloud-arrow-up fa-beat"></i></i></button>
                <a type="button" class="btn btn-outline-secondary btn-sm mr-2" href="../Archivos/Ordenes/Formatos.php/Ordenes.php?Num_OT=' . base64_encode($fila->Id) . '" target="_blank" title="Imprimir pdf"><i class="fa-regular fa-file-pdf fa-beat"></i></a>
                ';
            } else if ($fila->Status == 'B') {
                $status = '<div class="badge text-white bg-danger">Cancelado</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Modificar(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Agregar actividades" onclick="Mostrar_Id(' . $fila->Id . ',0)" data-toggle="modal" data-target="#Guardar_Actividades"><i class="fa-solid fa-list-check fa-beat"></i></button>
                <button type="button" class="btn btn-primary btn-sm mr-2" title="Agregar documento" onclick="Mostrar_D_D(' . $fila->Id . ',1)" data-toggle="modal" data-target="#Guardar_Documentos"><i class="fa-solid fa-cloud-arrow-up fa-beat"></i></i></button>
                <a type="button" class="btn btn-outline-secondary btn-sm mr-2" href="../Archivos/Ordenes/Formatos.php/Ordenes.php?Num_OT=' . base64_encode($fila->Id) . '" target="_blank" title="Imprimir pdf"><i class="fa-regular fa-file-pdf fa-beat"></i></a>
                ';
            }

            if ($fila->Prioridad == "Alto") {
                $Prioridad = '<div class="badge text-white bg-danger">Alto</div>';
            } else if ($fila->Prioridad == "Mediano") {
                $Prioridad = '<div class="badge text-white bg-warning">Mediano</div>';
            } else if ($fila->Prioridad == "Bajo") {
                $Prioridad = '<div class="badge text-white bg-success">Bajo</div>';
            }

            $Fechas = '<div class="alert alert-success" role="alert">
                <b>Fecha de inicio: </b> ' . $fila->Fecha_Inicio . ' <br>
                <b>Fecha final: </b> ' . $fila->Fecha_Final . '
            </div>';

            $datos[] = array(
                "0" => "<div class='text-center'>$fila->Id</div>",
                "1" => "<div class='text-left'>$fila->Cliente</div>",
                "2" => "<div class='text-left'>$fila->Obra</div>",
                "3" => "<div class='text-left'>$fila->Proyecto</div>",
                "4" => "<div class='text-left'>$fila->Contacto</div>",
                "5" => $Prioridad,
                "6" => "<div class='text-left'>$Fechas</div>",
                "7" => "<div class='text-left'>" . nl2br($fila->Observaciones) . "</div>",
                "8" => "<div class='text-center'>$status</div>",
                "9" => "<div class='d-flex justify-content-center'>$Botones</div>",
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datos), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datos), //enviamos el total registros a visualizar
            "aaData" => $datos
        );
        //Enviamos los datos de la tabla 
        echo json_encode($results);
        break;
    case 'Datos_Modificar':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Ordenes_Trabajo WHERE Id='$Id'");
        echo json_encode($query);
        break;
    case 'Ejecucion_Ot':
        $query = ejecutarConsulta("UPDATE Ordenes_Trabajo SET Status='U', Fecha_Ejecucion='$Fecha_Actual',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id'");
        echo $query ? 200 : 201;
        break;
    case 'Guardar_Clasificacion':
        if ($Id_Clasificacion == "") { // Insert
            // Validar existencia
            $COUNT = ejecutarConsultaSimpleFila("SELECT count(*) FROM Clasificaciones WHERE Nombre='$Nombre_Clasificacion' AND Status='A'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Clasificaciones(Nombre,Status) VALUES('$Nombre_Clasificacion','A')");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else { // update
            $query = ejecutarConsulta("UPDATE Clasificaciones SET Nombre='$Nombre_Clasificacion' WHERE Id='$Id_Clasificacion'");
            echo $query ? 200 : 201;
        }
        break;
    case 'Mostrar_Tbl_Clasificaciones':
        $query = ejecutarConsulta("SELECT*FROM Clasificaciones WHERE Status='A';");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Clasificacion(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="Eliminar_Clasificacion(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
                ';
            $datos[] = array(
                "0" => "<div class='text-left'>$fila->Id</div>",
                "1" => "<div class='text-left'>$fila->Nombre</div>",
                "2" => "<div class='d-flex justify-content-center'>$Botones</div>"
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datos), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datos), //enviamos el total registros a visualizar
            "aaData" => $datos
        );
        //Enviamos los datos de la tabla 
        echo json_encode($results);
        break;
    case 'Datos_Clasificacion':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Clasificaciones WHERE Id='$Id_Clasificacion'");
        echo json_encode($query);
        break;
    case 'Eliminar_Clasificacion':
        $query = ejecutarConsulta("UPDATE Clasificaciones SET Status='E' WHERE Id='$Id_Clasificacion'");
        echo $query ? 200 : 201;
        break;
    case 'Buscar_Clasificacion':
        $query = ejecutarConsulta("SELECT*FROM Clasificaciones WHERE Status='A';");
        while ($fila = mysqli_fetch_object($query)) {
            $Salida .= "<option class='text-dark' value='$fila->Id'>$fila->Nombre</option>";
        }
        echo $Salida;
        break;
    case 'Guardar_Actividad':
        if ($Id_Actividad == "") { // Insert
            $query = ejecutarConsulta("INSERT INTO Actividades_OT (Id_OT,Actividad,Descripcion,Fecha_Actividad,Fecha_Alta,Status) VALUES('$Id','$Nombre_Actividad','$Descripcion_Actividad','$Fecha_Actividad','$Fecha_Actual','A');");
        } else { //Update
            $query = ejecutarConsulta("UPDATE Actividades_OT SET Actividad='$Nombre_Actividad',Descripcion='$Descripcion_Actividad',Fecha_Actividad='$Fecha_Actividad',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id_Actividad'");
        }
        echo $query ? 200 : 201;
        break;
    case 'Mostrar_Tbl_Actividades':
        $query = ejecutarConsulta("SELECT*FROM Actividades_OT WHERE Id_OT='$Id' AND Status='A';");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Modificar_A(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="Elimiar_Actividad(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
                ';
            $datos[] = array(
                "0" => "<div class='text-left'>$fila->Fecha_Actividad</div>",
                "1" => "<div class='text-left'>$fila->Actividad</div>",
                "2" => "<div class='text-left'>$fila->Descripcion</div>",
                "3" => "<div class='d-flex justify-content-center'>$Botones</div>"
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($datos), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($datos), //enviamos el total registros a visualizar
            "aaData" => $datos
        );
        //Enviamos los datos de la tabla 
        echo json_encode($results);
        break;
    case 'Datos_Modificar_A':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Actividades_OT WHERE Id='$Id';");
        echo json_encode($query);
        break;
    case 'Elimiar_Actividad':
        $query = ejecutarConsulta("UPDATE Actividades_OT SET Status='E',Fecha_Eliminar='$Fecha_Actual' WHERE Id='$Id_Actividad'");
        echo $query ? 200 : 201;
        break;
    case 'Guardar_Documentos';
        $fileTmpPath = $_FILES['Documento']['tmp_name'];
        $Documento = $_FILES['Documento']['name'];
        /*
        error_reporting(E_ALL);
        ini_set("display_errors", 1);*/
        //Validamos que que exista la carpeta de ordenes
        $Ruta_Principal = "../../Documentos/ordenes";
        $Ruta_Ordenes = "../../Documentos/ordenes/OT_" . $Id;
        //Cambiamos en nombre
        $Nombre_Documento = str_replace(" ", "_", $Documento);
        // Ultimas rutas
        $Path_Destino = "$Ruta_Ordenes/$Nombre_Documento";
        $Path_BD = "../Documentos/ordenes/OT_$Id/$Nombre_Documento";
        if (!is_dir($Ruta_Principal)) {
            if (mkdir($Ruta_Principal, 0777, true)) {
                // Establece los permisos en la carpeta
                chmod($Ruta_Principal, 0777);
            } else {
                echo 201;
            }
        }
        // Validamos que la existencia de una carpeta para la orden de trabajo
        if (!is_dir($Ruta_Ordenes)) {
            if (mkdir($Ruta_Ordenes, 0777, true)) {
                // Establece los permisos en la carpeta
                chmod($Ruta_Ordenes, 0777);
            } else {
                echo 201;
            }
        }
        if (move_uploaded_file($fileTmpPath, $Path_Destino)) {
            $query = ejecutarConsulta("INSERT INTO Documentos_OT(Id_OT,Nombre,Ruta,Observaciones,Fecha_alta) VALUES('$Id','$Nombre_documento','$Path_BD','$Descripcion_Documento','$Fecha_Actual');");
            echo $query ? 200 : 201;
        } else {
            echo 202;
        }
        break;
    case 'Buscar_Archivos':
        $query = ejecutarConsulta("SELECT*FROM Documentos_OT WHERE Id_OT='$Id'");
        while ($fila = mysqli_fetch_object($query)) {
            $boton = '
            <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Descargar" onclick="Descargar_Archivo(' . "'$fila->Ruta'" . ',' . "'$fila->Nombre'" . ')"><i class="fa-solid fa-cloud-arrow-down fa-bounce"></i></button>
            <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Ampliar" onclick="Ampliar_Archivo(' . "'$fila->Ruta'" . ')" ><i class="fa-solid fa-up-right-and-down-left-from-center fa-bounce"></i></button>
            <button type="button" class="btn btn-outline-secondary btn-sm" title="Eliminar" onclick="Eliminar_Archivo(' . $fila->Id . ')"><i class="fa-solid fa-trash-can fa-bounce"></i></button>
            ';
            $Extencion = pathinfo($fila->Ruta, PATHINFO_EXTENSION);
            $View = ($Extencion == "png" || $Extencion == "jpg" || $Extencion == "jpeg") ? '<img src="' . $fila->Ruta . '"  class="card-img-top" alt="' . $fila->Tipo_Documento . '" height="200px">' : '<iframe src="' . $fila->Ruta . '" frameborder="0" style="width: 700px; height: 200px;"></iframe>';
            $salida .= '
                    <div class="card border-primary mr-2 mt-2" style="width: 30rem;">
                        <div class="card-header">
                            <div class="d-flex justify-content-center text-success">' . $fila->Nombre . '</div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                            ' . $View . '
                            </div>
                        </div>
                        <div class="card-footer">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                                   ' . $fila->Tipo_Documento . '
                                </div>
                                <div class="d-flex justify-content-end">
                                    ' . $boton . '
                                </div>
                        </div>
                    </div>';
        }
        echo $salida;
        break;
    case 'Eliminar_Archivo':
        //Comprobamos si existe el archivo/*
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Documentos_OT WHERE Id='$Id';")[3];
        $Ruta = "../" . $query;
        if (file_exists($Ruta)) {
            unlink($Ruta);
        }
        //Eliminamos el registro en la BD
        $sql = ejecutarConsulta("DELETE FROM Documentos_OT WHERE Id='$Id';");
        echo $sql ? 200 : 201;
        break;
}
