<?php

    // Conexión a la BD
    include ('../../global/conexion.php');

    $Id_Mat = isset($_POST['Id_Mat']) ? $_POST['Id_Mat'] : "";
    $Id_Mat = str_replace(" ", "", $Id_Mat);
    $Cve_Mat = isset($_POST['Cve_Mat']) ? $_POST['Cve_Mat'] : "";
    $Desc_Mat = isset($_POST['Desc_Mat']) ? $_POST['Desc_Mat'] : "";
    $Desc_Mat = str_replace("'", "''", $Desc_Mat);
    $Tipo = isset($_POST['Tipo']) ? $_POST['Tipo'] : "";
    $Id_Fam = isset($_POST['Id_Fam']) ? $_POST['Id_Fam'] : "";
    $Id_Prov = isset($_POST['Id_Prov']) ? $_POST['Id_Prov'] : "";
    $Id_UM1 = isset($_POST['Id_UM1']) ? $_POST['Id_UM1'] : "";
    $Id_UM2 = isset($_POST['Id_UM2']) ? $_POST['Id_UM2'] : "";
    $Stock = isset($_POST['Stock']) ? $_POST['Stock'] : "";
    $Min = isset($_POST['Min']) ? $_POST['Min'] : "";
    $Max = isset($_POST['Max']) ? $_POST['Max'] : "";
    $Costo = isset($_POST['Costo']) ? $_POST['Costo'] : "";
    $Ganancia = isset($_POST['Ganancia']) ? $_POST['Ganancia'] : "";
    $Status = isset($_POST['Status']) ? $_POST['Status'] : "";
    $Fecha = date("Y-m-d H-i-s");
    $Id_User = 1;

    /**
     *      Status  =   Activo  / Suspendido
     */
    
    // Valiables para tabla de Familias
    $Desc_Fam = isset($_POST['Desc_Fam']) ? $_POST['Desc_Fam'] : "";
    $Pre_Vta = isset($_POST['Pre_Vta']) ? $_POST['Pre_Vta'] : "";

    // Variables para tabla de unidades
    $Id_UM = isset($_POST['Id_UM']) ? $_POST['Id_UM'] : "";
    $Desc_UM = isset($_POST['Desc_UM']) ? $_POST['Desc_UM'] : "";
    $Abrev = isset($_POST['Abrev']) ? $_POST['Abrev'] : "";

    switch ($_GET['op']){
        case 'save_Mat':
            if (empty($Id_Mat)){
                $insert = ejecutarConsulta("INSERT INTO Cat_Materiales (Cve_Mat,Desc_Mat,Tipo,Id_Fam,Id_Prov,Id_UM1,Id_UM2,Stock
                ,Min,Max,Costo,Ganancia,Fec_Alta,Fec_Susp,Fec_Mod,U_Alta,U_Mod,Status) VALUES ('$Cve_Mat','$Desc_Mat','$Tipo','$Id_Fam','$Id_Prov'
                ,'$Id_UM1','$Id_UM2','$Stock','$Min','$Max','$Costo', '$Ganancia','$Fecha',NULL,'$Fecha','$Id_User','$Id_User','A');");

                echo $insert ? "El material se guardo correctamente" : "Ocurrió un error al guardar el material :(";
            } else {
                $update = ejecutarConsulta("UPDATE Cat_Materiales SET Desc_Mat='$Desc_Mat', Tipo='$Tipo', Id_Fam='$Id_Fam', Id_UM1='$Id_UM1', Id_UM2='$Id_UM2'
                , Id_Prov='$Id_Prov', Stock='$Stock', Min='$Min', Max='$Max', Costo='$Costo', Ganancia='$Ganancia', Fec_Mod='$Fecha', U_Mod='$Id_User' WHERE Id_Mat='$Id_Mat';");
                echo $update ? "El material se actualizó correctamente" : "Ocurrió un errro al actualizar el material :(";
            }
            break;


        // Caso para listado de materiales
        case 'listar_Mat':
            $sql = ejecutarConsulta("SELECT CM.*, Abrev UM FROM Cat_Materiales CM LEFT JOIN Cat_Unidad_Medida UM ON (CM.Id_UM2=UM.Id_UM)");
            $data = array();

            while($rst = $sql -> fetch_object()){                
                $sts = "";
                if ($rst -> Status == 'A'){ // Activo
                    $Status = "<div class='badge badge-success'>Activo</div>";
                    $sts = "<button class='btn btn-sm btn-outline-secondary' onclick='suspMat($rst->Id_Mat)' title='Susupender material'>
                        <i class='fas fa-cancel'></i></button>";
                } else {    // Suspendido
                    $Status = "<div class='badge badge-secondary'>Suspendido</div>";
                    $sts = "<button class='btn btn-sm btn-outline-success' onclick='actMat($rst->Id_Mat)' title='Activar material'>
                        <i class='fas fa-check'></i></button>";
                }

                $btn = "<div class='text-center'>
                    <button class='btn btn-sm btn-outline-info' onclick='verMat($rst->Id_Mat)'><i class='fas fa-edit fa-beat'></i></button>
                    $sts
                </div>";

                $Stock = "";
                if ($rst -> Stock >= $rst -> Max){
                    $Stock = "<div class='badge badge-warning' title='Se al alcanzado el máximo del material en al inventario'><i class='fa-solid fa-arrow-trend-up fa-beat'></i> Max</div>";
                } else if ($rst -> Stock <= $rst -> Min){
                    $Stock = "<div class='badge badge-danger' title='Se al alcanzado el minimo del material en al inventario'><i class='fa-solid fa-arrow-trend-down fa-beat'></i> Min</div>";
                }


                $Ganancia = $rst->Costo * ($rst->Ganancia / 100);

                $Gan = "$".number_format($Ganancia, 2);
                $Por = number_format($rst -> Ganancia, 1)."%";

                $Ganancia = $rst->Costo + $Ganancia;

                $r = ($Ganancia - intval($Ganancia))*100; 

                if ($r >= 25){
                    $Ganancia = ceil($Ganancia);
                } else {
                    if ($Ganancia <= 0.3){
                        $Ganancia = 0.5;
                    } else {
                        $Ganancia = floor($Ganancia);
                    }
                    
                }

                $data[] = array(
                    "0" => $rst->Id_Mat,
                    "1" => $rst->Cve_Mat,
                    "2" => $rst->Desc_Mat,
                    "3" => $rst->UM,
                    "4" => number_format($rst -> Stock, 2),
                    "5" => number_format($rst -> Max, 2),
                    "6" => number_format($rst -> Min, 2),
                    "7" => "$".number_format($rst -> Costo, 2),
                    "8" => "$".number_format($Ganancia, 2)."<br><small class='text-primary'>+ $Gan ($Por)</small>",
                    "9" => $Status.$Stock,
                    "10" => $btn
                );
            }

            $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            );

            echo json_encode($results);
            break;


        // Caso para ver un material
        case 'verMat':
            $data = ejecutarConsultaSimpleFila("SELECT * FROM Cat_Materiales WHERE Id_Mat=$Id_Mat");
            $data['Status'] = $data['Status'] == 'A' ? "Activo" : "Suspendido";
            echo json_encode($data);
            break;

        // Caso para activar un material
        case 'actMat':
            $act = ejecutarConsulta("UPDATE Cat_Materiales SET Status='A' WHERE Id_Mat=$Id_Mat");
            echo $act ? "El material de activo correctamente" : "Ocurrió un error al activar el material :(";
            break;

        // Caso para suspender un material
        case 'suspMat':
            $susp = ejecutarConsulta("UPDATE Cat_Materiales SET Status='U', Fec_Mod='$Fecha', U_Mod=$Id_User WHERE Id_Mat=$Id_Mat");
            echo $susp ? "El mterial se suspendió correctamente" : "Ocurrió un error al suspender el material :(";
            break;


        // Caso para select de familias
        case 'select_Fam':
            $sql = ejecutarConsulta("SELECT * FROM Cat_Familias ORDER BY Desc_Fam ASC");

            while ($rst = $sql -> fetch_object()){
                echo "<option value=$rst->Id_Fam data-subtext='$rst->Ganancia%'>$rst->Desc_Fam</option>";
            }
            break;

        // Select de unidades de medida
        case 'select_UM';
            $sql = ejecutarConsulta("SELECT * FROM Cat_Unidad_Medida ORDER BY Abrev ASC");

            while ($rst = $sql -> fetch_object()){
                echo "<option value=$rst->Id_UM data-subtext='$rst->Abrev'>$rst->Desc_UM</option>";
            }
            break;

        // Select de proveedores
        case 'select_Prov';
            $sql = ejecutarConsulta("SELECT * FROM Proveedores ORDER BY Nombre ASC");

            while ($rst = $sql -> fetch_object()){
                $dis = $rst -> Status == 'B' ? " disabled " : "";
                echo "<option class='text-dark' value=$rst->Id $dis>$rst->Nombre</option>";
            }
            break;

        // Caso para validar la existencia de un material
        case 'existMat':
            $existe = ejecutarConsulta("SELECT EXISTS(SELECT * FROM Cat_Mat WHERE Cve_Mat='$Cve_Mat' AND $Id_Mat!='$Id_Mat')");
            echo $existe? json_encode(true) : json_encode(false);
            break;


        // Caso para listado de familias
        case 'listar_Fam':
            $sql = ejecutarConsulta("SELECT * FROM Cat_Familias");
            $data = array();

            while($rst = $sql -> fetch_object()){
                $btn = "<div class='text-center'>
                    <button class='btn btn-sm btn-outline-info' onclick='verFam($rst->Id_Fam)'><i class='fas fa-edit fa-beat'></i></button>
                </div>";

                $data[] = array(
                    "0" => $rst->Desc_Fam,
                    "1" => number_format($rst->Ganancia, 2)."%",
                    "2" => $btn
                );
            }

            $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            );

            echo json_encode($results);
            break;

        // Caso para mostar infromación de un afamilia
        case 'verFam':
            $data = ejecutarConsultaSimpleFila("SELECT * FROM Cat_Familias WHERE Id_Fam=$Id_Fam");
            echo json_encode($data);
            break;

        // Caso part guardar y / o actualizar familias
        case 'save_Fam':
            if (empty($Id_Fam)){ // Insertamos
                $insert = ejecutarConsulta("INSERT INTO Cat_Familias (Desc_Fam, Ganancia) VALUES ('$Desc_Fam', '$Ganancia')");
                echo $insert ? "La familia se agregó correctamente" : "Ocurrio un erro al agregar la familia :(";
            } else {    // Actualizamos
                $update = ejecutarConsulta("UPDATE Cat_Familias SET Desc_Fam='$Desc_Fam', Ganancia='$Ganancia' WHERE Id_Fam='$Id_Fam'");
                echo $update ? "La familia se actualizó correctamente" : "Ocurrio un erro al actualiza la familia :(";
            }
            break;


        // Caso para listado de unidades
        case 'listar_UM':
            $sql = ejecutarConsulta("SELECT * FROM Cat_Unidad_Medida");
            $data = array();

            while($rst = $sql -> fetch_object()){
                $btn = "<div class='text-center'>
                    <button class='btn btn-sm btn-outline-info' onclick='verUM($rst->Id_UM)'><i class='fas fa-edit fa-beat'></i></button>
                </div>";

                $data[] = array(
                    "0" => $rst->Desc_UM,
                    "1" => $rst->Abrev,
                    "2" => $btn
                );
            }

            $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            );

            echo json_encode($results);
            break;

        // Caso para mostar infromación de un afamilia
        case 'verUM':
            $data = ejecutarConsultaSimpleFila("SELECT * FROM Cat_Unidad_Medida WHERE Id_UM=$Id_UM");
            echo json_encode($data);
            break;

        // Caso part guardar y / o actualizar familias
        case 'save_UM':
            if (empty($Id_UM)){ // Insertamos
                $insert = ejecutarConsulta("INSERT INTO Cat_Unidad_Medida (Desc_UM, Abrev) VALUES ('$Desc_UM', '$Abrev')");
                echo $insert ? "La unidad de medida se agregó correctamente" : "Ocurrio un erro al agregar la unidad de medida :(";
            } else {    // Actualizamos
                $update = ejecutarConsulta("UPDATE Cat_Unidad_Medida SET Desc_UM='$Desc_UM', Abrev='$Abrev' WHERE Id_UM='$Id_UM'");
                echo $update ? "La unidad de medida se actualizó correctamente" : "Ocurrio un erro al actualiza la unidad de medida :(";
            }
            break;

        // Caso para listado de Maximos
        case 'listar_Max':
            $sql = ejecutarConsulta("SELECT CM.*, Abrev UM FROM Cat_Materiales CM LEFT JOIN Cat_Unidad_Medida UM ON (CM.Id_UM2=UM.Id_UM)
                                    WHERE Stock >= Max AND Status='A'");
            $data = array();

            while($rst = $sql -> fetch_object()){
                $Ganancia = $rst->Costo * ($rst->Ganancia / 100);
                $Gan = "$".number_format($Ganancia, 2);
                $Por = number_format($rst -> Ganancia, 1)."%";

                $Ganancia = $rst->Costo + $Ganancia;

                $data[] = array(
                    "0" => $rst->Id_Mat,
                    "1" => $rst->Cve_Mat,
                    "2" => $rst->Desc_Mat,
                    "3" => $rst->UM,
                    "4" => number_format($rst -> Stock, 2),
                    "5" => number_format($rst -> Max, 2),
                    "6" => "$".number_format($rst -> Costo, 2),
                );
            }

            $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            );

            echo json_encode($results);
            break;

        // Caso para listado de Maximos
        case 'listar_Min':
            $sql = ejecutarConsulta("SELECT CM.*, Abrev UM FROM Cat_Materiales CM LEFT JOIN Cat_Unidad_Medida UM ON (CM.Id_UM2=UM.Id_UM)
                                    WHERE Stock <= Min AND Status='A'");
            $data = array();

            while($rst = $sql -> fetch_object()){     
                $Ganancia = $rst->Costo * ($rst->Ganancia / 100);
                $Gan = "$".number_format($Ganancia, 2);
                $Por = number_format($rst -> Ganancia, 1)."%";

                $Ganancia = $rst->Costo + $Ganancia;

                $data[] = array(
                    "0" => $rst->Id_Mat,
                    "1" => $rst->Cve_Mat,
                    "2" => $rst->Desc_Mat,
                    "3" => $rst->UM,
                    "4" => number_format($rst -> Stock, 2),
                    "5" => number_format($rst -> Min, 2),
                    "6" => "$".number_format($rst -> Costo, 2),
                );
            }

            $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            );

            echo json_encode($results);
            break;

        // Total de inventario
        case 'tot_inv':
            $data = ejecutarConsultaSimpleFila("SELECT SUM(Costo * Stock) Compra, SUM((Costo + (Costo * (Ganancia/100))) * Stock) Venta FROM Cat_Materiales WHERE Status='A' AND Stock > 0");

            $dif = $data['Compra'] / $data['Venta'] * 100;
            $dif = number_format($dif, 2)."%";
            $data['Diferencia'] = "$".number_format($data['Venta'] - $data['Compra'], 2);
            
            $data['Compra'] = "$".number_format($data['Compra'], 2);
            $data['Venta'] = "$".number_format($data['Venta'], 2);

            echo json_encode($data);
            break;
    }

?>