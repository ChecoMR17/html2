<?php
include "../../global/conexion.php";
$Fecha_Actual = date("Y-m-d H:i:s");
$Id_Proveedores = isset($_POST['Id_Proveedores']) ? $_POST['Id_Proveedores'] : "";
$Nombre_Proveedores = isset($_POST['Nombre_Proveedores']) ? $_POST['Nombre_Proveedores'] : "";
$Ganancia = isset($_POST['Ganancia']) ? $_POST['Ganancia'] : "";

$Id = isset($_POST['Id']) ? $_POST['Id'] : "";
$T_Persona = isset($_POST['T_Persona']) ? $_POST['T_Persona'] : "";
$Nombre_Proveedor = isset($_POST['Nombre_Proveedor']) ? $_POST['Nombre_Proveedor'] : "";
$Apellido_p = isset($_POST['Apellido_p']) ? $_POST['Apellido_p'] : "";
$Apellido_M = isset($_POST['Apellido_M']) ? $_POST['Apellido_M'] : "";
$RFC = isset($_POST['RFC']) ? $_POST['RFC'] : "";
$C_Pago = isset($_POST['C_Pago']) ? $_POST['C_Pago'] : "";
$Giro = isset($_POST['Giro']) ? $_POST['Giro'] : "";
$Observaciones = isset($_POST['Observaciones']) ? $_POST['Observaciones'] : "";

$Id_Fam = isset($_POST['Id_Fam']) ? $_POST['Id_Fam'] : "";
$Familias = isset($_POST['Familias']) ? $_POST['Familias'] : "";

$Id_Sucursal = isset($_POST['Id_Sucursal']) ? $_POST['Id_Sucursal'] : "";
$Nombre_Sucursal = isset($_POST['Nombre_Sucursal']) ? $_POST['Nombre_Sucursal'] : "";
$Nombre_contacto = isset($_POST['Nombre_contacto']) ? $_POST['Nombre_contacto'] : "";
$Nombre_Contacto2 = isset($_POST['Nombre_Contacto2']) ? $_POST['Nombre_Contacto2'] : "";
$Calle_Sucursal = isset($_POST['Calle_Sucursal']) ? $_POST['Calle_Sucursal'] : "";
$Numero_Exterior = !empty($_POST['Numero_Exterior']) ? $_POST['Numero_Exterior'] : 0;
$Numero_Interior = !empty($_POST['Numero_Interior']) ? $_POST['Numero_Interior'] : 0;
$Colonia = isset($_POST['Colonia']) ? $_POST['Colonia'] : "";
$Codigo_Postal = isset($_POST['Codigo_Postal']) ? $_POST['Codigo_Postal'] : "";
$Estado = isset($_POST['Estado']) ? $_POST['Estado'] : "";
$Municipio = isset($_POST['Municipio']) ? $_POST['Municipio'] : "";
$Celular = isset($_POST['Celular']) ? $_POST['Celular'] : "";
$Telefono = isset($_POST['Telefono']) ? $_POST['Telefono'] : "";
$Correo = isset($_POST['Correo']) ? $_POST['Correo'] : "";
$Correo_P = isset($_POST['Correo_P']) ? $_POST['Correo_P'] : "";
$Id_DBancarios = isset($_POST['Id_DBancarios']) ? $_POST['Id_DBancarios'] : "";
$Banco = isset($_POST['Banco']) ? $_POST['Banco'] : "";
$Sucursal_Banco = isset($_POST['Sucursal_Banco']) ? $_POST['Sucursal_Banco'] : "";
$Cuenta_Banco = isset($_POST['Cuenta_Banco']) ? $_POST['Cuenta_Banco'] : "";
$Clave_Banco = isset($_POST['Clave_Banco']) ? $_POST['Clave_Banco'] : "";
$Referencia = isset($_POST['Referencia']) ? $_POST['Referencia'] : "";
$datos = array();
$salida = "";
switch ($_GET['op']) {
    case 'Guardar_Proveedores':
        if ($Id == "") { //Insert
            //Validamos que no exista
            $COUNT = ejecutarConsultaSimpleFila("SELECT COUNT(*) FROM Proveedores WHERE RFC='$RFC'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Proveedores(Tipo_Persona,Nombre,Apellido_P,Apellido_M,RFC,Pago,Giro,Observaciones,Fecha_alta,Status) 
                    VALUES('$T_Persona','$Nombre_Proveedor','$Apellido_p','$Apellido_M','$RFC','$C_Pago','$Giro','$Observaciones','$Fecha_Actual','A')");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else { //Update
            $query = ejecutarConsulta("UPDATE Proveedores SET Tipo_Persona='$T_Persona',Nombre='$Nombre_Proveedor',Apellido_P='$Apellido_p',Apellido_M='$Apellido_M',RFC='$RFC',Pago='$C_Pago',Giro='$Giro',Observaciones='$Observaciones',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id'");
            echo $query ? 200 : 201;
        }
        break;
    case 'Datos_Modificar_Prov':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Proveedores WHERE Id='$Id';");
        echo json_encode($query);
        break;
    case 'Baja_Proveedor':
        $query = ejecutarConsulta("UPDATE Proveedores SET Fecha_Baja='$Fecha_Actual',Status='B' WHERE Id='$Id'");
        echo $query ? 200 : 201;
        break;
    case 'Reactivar_Proveedor':
        $query = ejecutarConsulta("UPDATE Proveedores SET Fecha_Modificacion='$Fecha_Actual',Status='A' WHERE Id='$Id'");
        echo $query ? 200 : 201;
        break;
    case 'Mostrar_Lista_Proveedores':
        $query = ejecutarConsulta("SELECT*FROM Proveedores;");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '';
            $status = "";
            if ($fila->Status == "A") {
                $status = '<div class="badge text-white bg-primary">Activo</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Modificar_Prov(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                <button type="button" class="btn btn-primary btn-sm mr-2" title="Agregar familias" data-toggle="modal" data-target="#Agregar_Fam" onclick="Mostar_Lista_Familias_Prov(' . $fila->Id . ')"><i class="fa-regular fa-rectangle-list fa-beat"></i></button>
                <button type="button" class="btn btn-secondary btn-sm mr-2" title="Agregar sucursales" data-toggle="modal" data-target="#Agregar_Sucursales" onclick="Mostar_Sucursales(' . $fila->Id . ')"><i class="fa-solid fa-store fa-beat"></i></button>
                <button type="button" class="btn btn-success btn-sm mr-2" title="Agregar bancos" data-toggle="modal" data-target="#Agregar_Bancos" onclick="Mostar_D_Bancarios(' . $fila->Id . ')"><i class="fa-solid fa-building-columns fa-beat"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm" title="Baja" onclick="Baja_Proveedor(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
                ';
            } else {
                $status = '<div class="badge text-white bg-danger">Baja</div>';
                $Botones = '
                <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Reactivar" onclick="Reactivar_Proveedor(' . $fila->Id . ')"><i class="fa-solid fa-check-double fa-beat"></i></button>
                ';
            }

            $datos[] = array(
                "0" => "<div class='text-center'>$fila->Id</div>",
                "1" => "<div class='text-left'>$fila->Tipo_Persona</div>",
                "2" => "<div class='text-left'>$fila->Nombre $fila->Apellido_P $fila->Apellido_M</div>",
                "3" => "<div class='text-left'>$fila->Giro</div>",
                "4" => "<div class='text-left'>$fila->RFC</div>",
                "5" => "<div class='text-left'>$fila->Pago</div>",
                "6" => "<div class='text-left'>$fila->Observaciones</div>",
                "7" => $status,
                "8" => "<div class='justify-content-center d-flex'>$Botones</div>"
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
    case 'Guardar_Familias_Prov':
        if ($Id_Fam == "") { //Insert
            $COUNT = ejecutarConsultaSimpleFila("SELECT count(*) FROM Familias_Proveedores WHERE Id_Proveedor='$Id' AND Id_Familia='$Familias'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Familias_Proveedores(Id_Proveedor,Id_Familia) VALUES('$Id','$Familias')");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else { // Update
            $query = ejecutarConsulta("UPDATE Familias_Proveedores SET Id_Familia='$Familias' WHERE Id='$Id_Fam';");
            echo $query ? 200 : 201;
        }

        break;
    case 'Datos_Fam_Prov':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Familias_Proveedores WHERE Id='$Id'");
        echo json_encode($query);
        break;
    case 'Eliminar_Fam_Prov':
        $query = ejecutarConsulta("DELETE FROM Familias_Proveedores WHERE Id='$Id'");
        echo $query ? 200 : 201;
        break;
    case 'Mostrar_Lista_Familias_Prov':
        $query = ejecutarConsulta("SELECT FP.Id, concat_ws('',P.Nombre,P.Apellido_P,P.Apellido_M) AS Proveedor,F.Desc_Fam AS Familia FROM Familias_Proveedores FP
            LEFT JOIN Proveedores P ON(FP.Id_Proveedor=P.Id)
            LEFT JOIN Cat_Familias F ON(FP.Id_Familia=F.Id_Fam) WHERE FP.Id_Proveedor='$Id'");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '
            <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Fam_Prov(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
            <button type="button" class="btn btn-outline-danger btn-sm" title="Baja" onclick="Eliminar_Fam_Prov(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
            ';

            $datos[] = array(
                "0" => "<div class='text-center'>$fila->Id</div>",
                "1" => "<div class='text-left'>$fila->Familia</div>",
                "2" => "<div class='justify-content-center d-flex'>$Botones</div>"
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
    case 'Guardar_Familias':
        if ($Id_Proveedores == "") {
            //VAlidamos que no exista
            $COUNT = ejecutarConsultaSimpleFila("SELECT COUNT(*) FROM Cat_Familias WHERE Desc_Fam='$Nombre_Proveedores'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Cat_Familias(Desc_Fam,Ganancia) VALUES('$Nombre_Proveedores','$Ganancia')");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else {
            $query = ejecutarConsulta("UPDATE Cat_Familias SET Desc_Fam='$Nombre_Proveedores', Ganancia='$Ganancia' WHERE Id_Fam='$Id_Proveedores'");
            echo $query ? 200 : 201;
        }
        break;
    case 'Mostrar_Lista_Familias':
        $query = ejecutarConsulta("SELECT*FROM Cat_Familias;");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '
            <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Modificar_F(' . $fila->Id_Fam . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
            ';

            $datos[] = array(
                "0" => "<div class='text-center'>$fila->Id_Fam</div>",
                "1" => "<div class='text-left'>$fila->Desc_Fam</div>",
                "2" => "<div class='text-left'>" . number_format($fila->Ganancia, 2) . " %</div>",
                "3" => "<div class='justify-content-center d-flex'>$Botones</div>"
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
    case 'Datos_Modificar_F':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Cat_Familias WHERE Id_Fam='$Id_Proveedores'");
        echo json_encode($query);
        break;
    case 'Mostrar_Familias':
        $query = ejecutarConsulta("SELECT*FROM Cat_Familias");
        while ($fila = mysqli_fetch_object($query)) {
            $salida .= "<option class='text-dark' value='$fila->Id_Fam'>$fila->Desc_Fam</option>";
        }
        echo $salida;
        break;
    case 'Guardar_Sucursales';
        if ($Id_Sucursal == "") { // Insert
            // Validamos si ya existe
            $COUNT = ejecutarConsultaSimpleFila("SELECT count(*) FROM Sucursales_Proveedores WHERE Nombre='$Nombre_Sucursal' AND Id_Proveedor='$Id'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Sucursales_Proveedores(Id_Proveedor,Nombre,P_Contacto,S_Contacto,Calle,N_Exterior,N_Interior,Colonia,CP,Id_Estado,Id_Municipios,Celular,Telefono,Correo_C,Correo_P,Fecha_Alta,Status) 
                VALUES('$Id','$Nombre_Sucursal','$Nombre_contacto','$Nombre_Contacto2','$Calle_Sucursal','$Numero_Exterior','$Numero_Interior','$Colonia','$Codigo_Postal','$Estado','$Municipio','$Celular','$Telefono','$Correo','$Correo_P','$Fecha_Actual','A');");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else { // Update
            $query = ejecutarConsulta("UPDATE Sucursales_Proveedores SET Nombre='$Nombre_Sucursal',P_Contacto='$Nombre_contacto',S_Contacto='$Nombre_Contacto2',Calle='$Calle_Sucursal',N_Exterior='$Numero_Exterior',N_Interior='$Numero_Interior',Colonia='$Colonia',CP='$Codigo_Postal',Id_Estado='$Estado',Id_Municipios='$Municipio',Celular='$Celular',Telefono='$Telefono',Correo_C='$Correo',Correo_P='$Correo_P',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id_Sucursal'");
            echo $query ? 200 : 201;
        }
        break;
    case 'Datos_Sucursal':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Sucursales_Proveedores WHERE Id='$Id'");
        echo json_encode($query);
        break;

    case 'Mostrar_Estados':
        $query = ejecutarConsulta("SELECT*FROM Estados ORDER BY Id_Estado ASC;");
        while ($fila = mysqli_fetch_object($query)) {
            $salida .= "<option class='text-dark' value='$fila->Id_Estado'>$fila->Nombre</option>";
        }
        echo $salida;
        break;
    case 'Mostrar_Municipios':
        $query = ejecutarConsulta("SELECT*FROM Municipios WHERE Id_Estado='$Id';");
        while ($fila = mysqli_fetch_object($query)) {
            $salida .= "<option class='text-dark' value='$fila->Id_Municipios'>$fila->Nombre</option>";
        }
        echo $salida;
        break;
    case 'Mostar_Tbl_Sucursales':

        $query = ejecutarConsulta("SELECT S.Id,S.Nombre,S.P_Contacto,S.S_Contacto,S.Calle,S.N_Exterior,S.N_Interior,S.Colonia,S.CP,E.Nombre AS Estado,M.Nombre AS Municipio,S.Celular,
        S.Telefono,S.Correo_C,S.Correo_P,S.Status FROM Sucursales_Proveedores S
        LEFT JOIN Estados E ON(S.Id_Estado=E.Id_Estado)
        LEFT JOIN Municipios M ON(S.Id_Municipios=M.Id_Municipios) WHERE S.Id_Proveedor='$Id'");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '';
            $status = "";

            if ($fila->Status == "A") {
                $status = '<div class="badge text-white bg-primary">Activo</div>';
                $Botones = '
                    <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_Sucursal(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-sm" title="Baja" onclick="Baja_Sucursal(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
                    ';
            } else {
                $status = '<div class="badge text-white bg-danger">Baja</div>';
                $Botones = '
                    <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Reactivar" onclick="Reactivar_Sucursal(' . $fila->Id . ')"><i class="fa-solid fa-check-double fa-beat"></i></button>
                    ';
            }

            $fila->N_Exterior = ($fila->N_Exterior == "0") ? "S/N" : $fila->N_Exterior;
            $fila->N_Interior = ($fila->N_Interior != "0") ? ", # " . $fila->N_Interior . " " : "";

            $Direccion = "C " . $fila->Calle . ", # " . $fila->N_Exterior . $fila->N_Interior . ", Loc. " . $fila->Colonia . ", CP. " . $fila->CP . ", " . $fila->Municipio . ", " . $fila->Estado;
            $Telefonos = '<div class="alert alert-success" role="alert">
                <b>Celular: </b> ' . $fila->Celular . ' <br>
                <b>Telefono: </b> ' . $fila->Telefono . '
            </div>';

            $Contacto = '<div class="alert alert-success" role="alert">
                <b>Primer contacto: </b> ' . $fila->P_Contacto . ' <br>
                <b>Segundo contacto: </b> ' . $fila->S_Contacto . '
            </div>';
            $datos[] = array(
                "0" => "<div class='text-left'>$fila->Nombre</div>",
                "1" => "<div class='text-left'>$Contacto</div>",
                "2" => "<div class='text-left'>$Direccion</div>",
                "3" => "<div class='text-left'>$Telefonos</div>",
                "4" => $status,
                "5" => "<div class='justify-content-center d-flex'>$Botones</div>"
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
    case 'Baja_Sucursal':
        $query = ejecutarConsulta("UPDATE Sucursales_Proveedores SET Status='B',Fecha_Baja='$Fecha_Actual' WHERE Id='$Id';");
        echo $query ? 200 : 201;
        break;
    case 'Reactivar_Sucursal':
        $query = ejecutarConsulta("UPDATE Sucursales_Proveedores SET Status='A',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id';");
        echo $query ? 200 : 201;
        break;
    case 'Mostrar_Bancos':
        $query = ejecutarConsulta("SELECT*FROM Bancos;");
        while ($fila = mysqli_fetch_object($query)) {
            $salida .= "<option class='text-dark' value='$fila->Id'>$fila->Nombre</option>";
        }
        echo $salida;
        break;
    case 'Guardar_D_Bancarios':
        if ($Id_DBancarios == "") { // Insert
            $COUNT = ejecutarConsultaSimpleFila("SELECT count(*) FROM Bancos_Proveedores WHERE Cuenta='$Cuenta_Banco' AND Id_Proveedor='$Id'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Bancos_Proveedores(Id_Proveedor,Id_Banco,Sucursal,Cuenta,Clave,Referencia,Fecha_Alta,Status) 
                VALUES('$Id','$Banco','$Sucursal_Banco','$Cuenta_Banco','$Clave_Banco','$Referencia','$Fecha_Actual','A')");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else {
            $query = ejecutarConsulta("UPDATE Bancos_Proveedores SET Id_Banco='$Banco',Sucursal='$Sucursal_Banco',Cuenta='$Cuenta_Banco',Clave='$Clave_Banco',Referencia='$Referencia',Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id_DBancarios';");
            echo $query ? 200 : 201;
        }
        break;
    case 'Mostar_Tbl_D_Bancarios':

        $query = ejecutarConsulta("SELECT BP.Id,B.Nombre,BP.Sucursal,BP.Cuenta,BP.Clave,BP.Referencia,BP.Status FROM Bancos_Proveedores BP
        LEFT JOIN Bancos B ON(BP.Id_Banco=B.Id) WHERE BP.Id_Proveedor='$Id'");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '';
            $status = "";

            if ($fila->Status == "A") {
                $status = '<div class="badge text-white bg-primary">Activo</div>';
                $Botones = '
                    <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_D_Editar(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-sm" title="Baja" onclick="Baja_DBancarios(' . $fila->Id . ')"><i class="fa-solid fa-xmark fa-beat"></i></button>
                    ';
            } else {
                $status = '<div class="badge text-white bg-danger">Baja</div>';
                $Botones = '
                    <button type="button" class="btn btn-outline-success btn-sm mr-2" title="Reactivar" onclick="Reactivar_DBancarios(' . $fila->Id . ')"><i class="fa-solid fa-check-double fa-beat"></i></button>
                    ';
            }


            $datos[] = array(
                "0" => "<div class='text-left'>$fila->Nombre</div>",
                "1" => "<div class='text-left'>$fila->Sucursal</div>",
                "2" => "<div class='text-left'>$fila->Cuenta</div>",
                "3" => "<div class='text-left'>$fila->Clave</div>",
                "4" => "<div class='text-left'>$fila->Referencia</div>",
                "5" => $status,
                "6" => "<div class='justify-content-center d-flex'>$Botones</div>"
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
    case 'Baja_DBancarios':
        $query = ejecutarConsulta("UPDATE Bancos_Proveedores SET Status='B', Fecha_Baja='$Fecha_Actual' WHERE Id='$Id';");
        echo $query ? 200 : 201;
        break;
    case 'Reactivar_DBancarios':
        $query = ejecutarConsulta("UPDATE Bancos_Proveedores SET Status='A', Fecha_Modificacion='$Fecha_Actual' WHERE Id='$Id';");
        echo $query ? 200 : 201;
        break;
    case 'Datos_D_Editar':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Bancos_Proveedores WHERE Id='$Id';");
        echo json_encode($query);
        break;
    case 'Guardar_A_Bancos':
        $Id_Bancos = isset($_POST['Id_Bancos']) ? $_POST['Id_Bancos'] : "";
        $Nombre_Banco = isset($_POST['Nombre_Banco']) ? $_POST['Nombre_Banco'] : "";
        if ($Id_Bancos == "") { // Insert
            $COUNT = ejecutarConsultaSimpleFila("SELECT COUNT(*) FROM Bancos WHERE Nombre='$Nombre_Banco'")[0];
            if ($COUNT == 0) {
                $query = ejecutarConsulta("INSERT INTO Bancos(Nombre) values('$Nombre_Banco');");
                echo $query ? 200 : 201;
            } else {
                echo 202;
            }
        } else { // Update
            $query = ejecutarConsulta("UPDATE Bancos SET Nombre='$Nombre_Banco' WHERE Id='$Id_Bancos'");
            echo $query ? 200 : 201;
        }
        break;
    case 'Buscar_Lista_Bancos':
        $query = ejecutarConsulta("SELECT*FROM Bancos ORDER BY Nombre asc;");
        while ($fila = mysqli_fetch_object($query)) {
            $Botones = '';
            $Botones = '
                    <button type="button" class="btn btn-outline-info btn-sm mr-2" title="Modificar" onclick="Datos_M_Banco(' . $fila->Id . ')"><i class="fa-solid fa-user-pen fa-beat"></i></button>
                    ';
            $datos[] = array(
                "0" => "<div class='text-left'>$fila->Id</div>",
                "1" => "<div class='text-left'>$fila->Nombre</div>",
                "2" => "<div class='justify-content-center d-flex'>$Botones</div>"
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
    case 'Datos_M_Banco':
        $query = ejecutarConsultaSimpleFila("SELECT*FROM Bancos WHERE Id='$Id'");
        echo json_encode($query);
        break;
}
