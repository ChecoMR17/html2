<?php
include "../../global/conexion.php";
//Varibales
$Fecha_Actual = date("Y-m-d H:i:s");

$Id_Cliente = isset($_POST["Id_Cliente"]) ? $_POST["Id_Cliente"] : "";
$T_Persona = isset($_POST["T_Persona"]) ? $_POST["T_Persona"] : "";
$Nombre_Cliente = isset($_POST["Nombre_Cliente"]) ? $_POST["Nombre_Cliente"] : "";
$Apellido_p = isset($_POST["Apellido_p"]) ? $_POST["Apellido_p"] : "";
$Apellido_M = isset($_POST["Apellido_M"]) ? $_POST["Apellido_M"] : "";
$RFC = isset($_POST["RFC"]) ? $_POST["RFC"] : "";
$Correo_C = isset($_POST["Correo_C"]) ? $_POST["Correo_C"] : "";
$Correo_P = isset($_POST["Correo_P"]) ? $_POST["Correo_P"] : "";
$Celular = isset($_POST["Celular"]) ? $_POST["Celular"] : "";
$Telefono = isset($_POST["Telefono"]) ? $_POST["Telefono"] : "";
$Estado = isset($_POST["Estado"]) ? $_POST["Estado"] : "";
$Municipio = isset($_POST["Municipio"]) ? $_POST["Municipio"] : "";
$Colonia = isset($_POST["Colonia"]) ? $_POST["Colonia"] : "";
$Calle = isset($_POST["Calle"]) ? $_POST["Calle"] : "";
$N_Exterior = !empty($_POST["N_Exterior"]) ? $_POST["N_Exterior"] : 0;
$N_Interior = !empty($_POST["N_Interior"]) ? $_POST["N_Interior"] : 0;
$CP = isset($_POST["CP"]) ? $_POST["CP"] : "";
$Observaciones = isset($_POST["Observaciones"]) ? $_POST["Observaciones"] : "";

$Id_contacto = isset($_POST["Id_contacto"]) ? $_POST["Id_contacto"] : "";
$Nombre_Contacto = isset($_POST["Nombre_Contacto"]) ? $_POST["Nombre_Contacto"] : "";
$Apellido_P_Contacto = isset($_POST["Apellido_P_Contacto"]) ? $_POST["Apellido_P_Contacto"] : "";
$Apellido_M_Contacto = isset($_POST["Apellido_M_Contacto"]) ? $_POST["Apellido_M_Contacto"] : "";
$Celular_Contacto = isset($_POST["Celular_Contacto"]) ? $_POST["Celular_Contacto"] : "";
$Telefono_Contacto = isset($_POST["Telefono_Contacto"]) ? $_POST["Telefono_Contacto"] : "";
$Correo_C_C = isset($_POST["Correo_C_C"]) ? $_POST["Correo_C_C"] : "";
$Correo_C_P = isset($_POST["Correo_C_P"]) ? $_POST["Correo_C_P"] : "";
$Observaciones_Contactos = isset($_POST["Observaciones_Contactos"]) ? $_POST["Observaciones_Contactos"] : "";
$Id_Obra = isset($_POST['Id_Obra']) ? $_POST['Id_Obra'] : "";
$Clasificacion = isset($_POST['Clasificacion']) ? $_POST['Clasificacion'] : "";
$Nombre_Obra = isset($_POST['Descripcion_Obras']) ? $_POST['Descripcion_Obras'] : "";
$Estado_O = isset($_POST['Estado_O']) ? $_POST['Estado_O'] : "";
$Municipio_O = isset($_POST['Municipio_O']) ? $_POST['Municipio_O'] : "";
$Colonia_O = isset($_POST['Colonia_O']) ? $_POST['Colonia_O'] : "";
$Calle_O = isset($_POST['Calle_O']) ? $_POST['Calle_O'] : "";
$N_Exterior_O = !empty($_POST['N_Exterior_O']) ? $_POST['N_Exterior_O'] : 0;
$N_Interior_O = !empty($_POST['N_Interior_O']) ? $_POST['N_Interior_O'] : 0;
$CP_O = isset($_POST['CP_O']) ? $_POST['CP_O'] : "";
$Observaciones_O = isset($_POST['Observaciones_O']) ? $_POST['Observaciones_O'] : "";

$datos = array();
$Salida = "";

switch ($_GET['op']) {
    case 'Guardar_Cliente':
        if ($Id_Cliente == "") { // Insert
            // vALIDAR SI YA EXISTE
            $COUNT = ejecutarConsultaSimpleFila("SELECT COUNT(*) FROM Clientes WHERE RFC='$RFC'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Clientes(T_Persona,Nombre,Apellido_P,Apellido_M,RFC,Correo_C,Correo_P,Telefono,Celular,Id_Estado,Id_Municipios,Colonia,Calle,N_Exterior,N_Interior,Codigo_P,Observaciones,Fecha_Alta,Status)
                VALUES ('$T_Persona','$Nombre_Cliente','$Apellido_p','$Apellido_M','$RFC','$Correo_C','$Correo_P','$Telefono','$Celular','$Estado','$Municipio','$Colonia','$Calle','$N_Exterior','$N_Interior','$CP','$Observaciones','$Fecha_Actual','A')");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else { // Update
            $query = ejecutarConsulta("UPDATE Clientes SET T_Persona='$T_Persona',Nombre='$Nombre_Cliente',Apellido_P='$Apellido_p',Apellido_M='$Apellido_M',RFC='$RFC',Correo_C='$Correo_C',Correo_P='$Correo_P',Telefono='$Telefono',Celular='$Celular',
            Id_Estado='$Estado',Id_Municipios='$Municipio',Colonia='$Colonia',Calle='$Calle',N_Exterior='$N_Exterior',N_Interior='$N_Interior',Codigo_P='$CP',Observaciones='$Observaciones',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id_Cliente';");
            echo $query ? 200 : 201;
        }
        //echo "insert cliente";
        break;
    case 'Mostrar_Lista_Clientes':
        $query = ejecutarConsulta("SELECT C.*,E.Nombre AS Estados, M.Nombre AS Municipio FROM Clientes C
        LEFT JOIN Estados E on(C.Id_Estado=E.Id_Estado)
        LEFT JOIN Municipios M ON(C.Id_Municipios=M.Id_Municipios);");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '';
            $Status = "";
            if ($fila->Status == "A") {
                $Status = '<div class="badge text-white bg-success">Activo</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Mostar_datos(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-secondary btn-sm mr-2" title="Contactos" onclick="Mostrar_Id_Cliente(' . $fila->Id . ')" data-toggle="modal" data-target="#Guardar_contactos"><i class="fa-solid fa-address-book fa-beat"></i></button>
                <button type="button" class="btn btn-warning btn-sm mr-2" title="Asignar obras" onclick="Datos_F_Obras(' . $fila->Id . ')" data-toggle="modal" data-target="#Guardar_Obras"><i class="fa-solid fa-person-digging fa-beat"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm" title="Baja" onclick="Baja_Cliente(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
                ';
            } else {
                $Status = '<div class="badge text-white bg-danger">Baja</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Modificar" onclick="Alta_Cliente(' . $fila->Id . ')"><i class="fa-solid fa-check"></i></button>
                ';
            }
            $fila->N_Exterior = ($fila->N_Exterior != "") ? ", # " . $fila->N_Exterior : ", # S/N";
            $fila->N_Interior = ($fila->N_Interior != "" && $fila->N_Interior != 0) ? ", # " . $fila->N_Interior : "";
            $Direccion = "C. " . $fila->Calle . $fila->N_Exterior . $fila->N_Interior . ", Loc " . $fila->Colonia . ", C.P " . $fila->Codigo_P . ", " . $fila->Municipio . ", " . $fila->Estados;

            $Correos = '<div class="alert alert-success" role="alert">
                <b>Correo C: </b> ' . $fila->Correo_C . ' <br>
                <b>Correo P: </b> ' . $fila->Correo_P . '
            </div>';
            $Telefonos = '<div class="alert alert-success" role="alert">
                <b>Celular: </b> ' . $fila->Celular . ' <br>
                <b>Telefono: </b> ' . $fila->Telefono . '
            </div>';
            $datos[] = array(
                "0" => "<div class='text-left'>$fila->Id</div>",
                "1" => "<div class='text-left'>$fila->Nombre $fila->Apellido_P $fila->Apellido_M</div>",
                "2" => "<div class='text-left'>$fila->T_Persona</div>",
                "3" => "<div class='text-left'>$fila->RFC</div>",
                "4" => "<div class='text-left'>$Correos</div>",
                "5" => "<div class='text-left'>$Telefonos</div>",
                "6" => "<div class='text-left'>$Direccion</div>",
                "7" => "<div class='text-left'>$fila->Observaciones</div>",
                "8" => $Status,
                "9" => "<div class='d-flex justify-content-center'>$Botones</div>"
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
    case 'Mostar_datos':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Clientes WHERE Id='$Id_Cliente'");
        echo json_encode($query);
        break;
    case 'Baja_Cliente':
        $query = ejecutarConsulta("UPDATE Clientes SET Fecha_Baja='$Fecha_Actual',Status='B' WHERE Id='$Id_Cliente'");
        echo $query ? 200 : 201;
        break;
    case 'Alta_Cliente':
        $query = ejecutarConsulta("UPDATE Clientes SET Fecha_Modificacion='$Fecha_Actual',Status='A' WHERE Id='$Id_Cliente'");
        echo $query ? 200 : 201;
        break;
    case 'Guardar_Contacto':
        if ($Id_contacto == "") { // Insert
            // Validamos si existe
            $COUNT = ejecutarConsultaSimpleFila("SELECT count(*) FROM Contactos_Clientes WHERE Nombre='$Nombre_Contacto' AND Apellido_P='$Apellido_P_Contacto' AND Apellido_M='$Apellido_M_Contacto' AND Id_Cliente='$Id_Cliente'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Contactos_Clientes(Id_Cliente,Nombre,Apellido_P,Apellido_M,Telefono,Celular,Correo_C,Correo_P,Observaciones,Fecha_Alta,Status) VALUES('$Id_Cliente','$Nombre_Contacto','$Apellido_P_Contacto','$Apellido_M_Contacto','$Telefono_Contacto','$Celular_Contacto','$Correo_C_C','$Correo_C_P','$Observaciones_Contactos','$Fecha_Actual','A')");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else { // Update
            $query = ejecutarConsulta("UPDATE Contactos_Clientes SET Nombre='$Nombre_Contacto',Apellido_P='$Apellido_P_Contacto',Apellido_M='$Apellido_M_Contacto',Telefono='$Telefono_Contacto',Celular='$Celular_Contacto',Correo_C='$Correo_C_C',Correo_P='$Correo_C_P',Observaciones='$Observaciones_Contactos',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id_contacto'");
            echo $query ? 200 : 201;
        }
        break;
    case 'Mostrar_Id_Cliente':
        $query = ejecutarConsulta("SELECT*FROM Contactos_Clientes WHERE Id_Cliente='$Id_Cliente';");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '';
            $Status = "";
            if ($fila->Status == "A") {
                $Status = '<div class="badge text-white bg-success">Activo</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Contacto(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm" title="Baja" onclick="Baja_Contacto(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
                ';
            } else {
                $Status = '<div class="badge text-white bg-danger">Baja</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Reactivar contacto" onclick="Alta_Contacto(' . $fila->Id . ')"><i class="fa-solid fa-check"></i></button>
                ';
            }
            $Correos = '<div class="alert alert-success" role="alert">
                <b>Correo C: </b> ' . $fila->Correo_C . ' <br>
                <b>Correo P: </b> ' . $fila->Correo_P . '
            </div>';
            $Telefonos = '<div class="alert alert-success" role="alert">
                <b>Celular: </b> ' . $fila->Celular . ' <br>
                <b>Telefono: </b> ' . $fila->Telefono . '
            </div>';

            $datos[] = array(
                "0" => "<div class='text-left'>$fila->Nombre $fila->Apellido_P $fila->Apellido_M</div>",
                "1" => "<div class='text-left'>$Telefonos</div>",
                "2" => "<div class='text-left'>$Correos</div>",
                "3" => "<div class='text-left'>$fila->Observaciones</div>",
                "4" => "<div class='text-left'>$Status</div>",
                "5" => "<div class='d-flex justify-content-center'>$Botones</div>"
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
    case 'Datos_Contacto':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Contactos_Clientes WHERE Id='$Id_contacto'");
        echo json_encode($query);
        break;
    case 'Baja_Contacto':
        $query = ejecutarConsulta("UPDATE Contactos_Clientes SET Status='B', Fecha_baja='$Fecha_Actual' WHERE Id='$Id_contacto'");
        echo $query ? 200 : 201;
        break;
    case 'Alta_Contacto':
        $query = ejecutarConsulta("UPDATE Contactos_Clientes SET Status='A', Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id_contacto'");
        echo $query ? 200 : 201;
        break;
    case 'Mostrar_Contactos':
        $query = ejecutarConsulta("SELECT*FROM Contactos_Clientes WHERE Id_Cliente='$Id_Cliente' AND Status='A';");
        while ($fila = mysqli_fetch_object($query)) {
            $Salida .= "<option class='text-dark' value='$fila->Id'>$fila->Nombre $fila->Apellido_P $fila->Apellido_M</option>";
        }
        echo $Salida;
        break;
    case 'Guardar_Obras':
        if ($Id_Obra == "") { //Insert
            //VAlidamos que no exista
            $COUNT = ejecutarConsultaSimpleFila("SELECT count(*) FROM Obras WHERE Nombre_Obra='$Nombre_Obra'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Obras(Id_Cliente,Nombre_Obra,Id_Estado,Id_Municipios,Colonia,Calle,N_Exterior,N_Interior,Codigo_P,Observaciones,Fecha_Alta,Status) 
                VALUES('$Id_Cliente','$Nombre_Obra','$Estado_O','$Municipio_O','$Colonia_O','$Calle_O','$N_Exterior_O','$N_Interior_O','$CP_O','$Observaciones_O','$Fecha_Actual','A');");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else { // Update
            $query = ejecutarConsulta("UPDATE Obras SET Id_Cliente='$Id_Cliente',Nombre_Obra='$Nombre_Obra',Id_Estado='$Estado_O',Id_Municipios='$Municipio_O',Colonia='$Colonia_O',Calle='$Calle_O',N_Exterior='$N_Exterior_O',N_Interior='$N_Interior_O',Codigo_P='$CP_O',Observaciones='$Observaciones_O',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id_Obra'");
            echo $query ? 200 : 201;
        }
        break;
    case 'Mostrar_Tbl_Obras':
        $query = ejecutarConsulta("SELECT O.Id,O.Nombre_Obra AS Obra,E.Nombre AS Estado,M.Nombre AS Municipio,O.Colonia,O.Calle,O.N_Exterior,O.N_Interior,O.Codigo_P,O.Observaciones,O.Status FROM Obras O
        LEFT JOIN Estados E ON(O.Id_Estado=E.Id_Estado)
        LEFT JOIN Municipios M ON(O.Id_Municipios=M.Id_Municipios)
        WHERE O.Id_Cliente='$Id_Cliente' AND O.Status='A';");
        while ($fila = mysqli_fetch_object($query)) {

            $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Obra(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="Eliminar_Obra(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
                ';

            $fila->N_Exterior = ($fila->N_Exterior == "0") ? "S/N" : $fila->N_Exterior;
            $fila->N_Interior = ($fila->N_Interior != "0") ? ", # " . $fila->N_Interior . " " : "";

            $Direccion = "C " . $fila->Calle . ", # " . $fila->N_Exterior . $fila->N_Interior . ", Loc. " . $fila->Colonia . ", CP. " . $fila->Codigo_P . ", " . $fila->Municipio . ", " . $fila->Estado;
            $datos[] = array(
                "0" => "<div class='text-left'>$fila->Obra</div>",
                "1" => "<div class='text-left'>$Direccion</div>",
                "2" => "<div class='text-left'>$fila->Observaciones</div>",
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
    case 'Datos_Obra':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Obras where id='$Id_Obra'");
        echo json_encode($query);
        break;
    case 'Eliminar_Obra':
        $query = ejecutarConsulta("UPDATE Obras SET Status='B',Fecha_baja='$Fecha_Actual' WHERE Id='$Id_Obra';");
        echo $query ? 200 : 201;
        break;
}
