<?php

    // Conexión a la BD
    include ('../../global/conexion.php');
    // Numeros al letras
    include("../numaLetras.php");

    // Variables para nota de venta
    $Id_Venta = isset($_POST['Id_Venta']) ? $_POST['Id_Venta'] : "";
    $Cliente = isset($_POST['Cliente']) ? $_POST['Cliente'] : "";
    $Cliente = str_replace("'", "''", $Cliente);
    $Tel = isset($_POST['Tel']) ? $_POST['Tel'] : "";
    $Tel = str_replace("'", "''", $Tel);
    $Correo = isset($_POST['Correo']) ? $_POST['Correo'] : "";
    $Correo = str_replace("'", "''", $Correo);
    $Direccion = isset($_POST['Direccion']) ? $_POST['Direccion'] : "";
    $Direccion = str_replace("'", "''", $Direccion);
    $Obs = isset($_POST['Obs']) ? $_POST['Obs'] : "";
    $Obs = str_replace("'", "''", $Obs);
    $Descuento = isset($_POST['Descuento']) ? $_POST['Descuento'] : 0;
    $Fecha = date("Y-m-d H-i-s");
    $Id_User = 1;
    

    /**
     *                  A : Activo,
     *      Status  =   U : Finalizado
     *                  b : Cancelado
     */

    //Variables para materiales de venta
    $Id_Mat = isset($_POST['Id_Mat']) ? $_POST['Id_Mat'] : "";
    $Cons = isset($_POST['Cons']) ? $_POST['Cons'] : "";
    $Cant = isset($_POST['Cant']) ? $_POST['Cant'] : 0;
    $Devolucion = isset($_POST['Devolucion']) ? $_POST['Devolucion'] : 0;
    $Costo = isset($_POST['Costo']) ? $_POST['Costo'] : "";
    $Ganancia = isset($_POST['Ganancia']) ? $_POST['Ganancia'] : "";

    switch ($_GET['op']){
        // Caso para retonar el siguiente id de venta
        case 'Id_Venta':
            $Id_Venta = ejecutarConsultaSimpleFila("SELECT Id_Venta FROM Ventas ORDER BY Id_Venta DESC LIMIT 1")['Id_Venta'] + 1;
            echo json_encode($Id_Venta);
            break;

        // Caso para validar si existe una venta
        case 'valVenta':
            $val = ejecutarConsultaSimpleFila("SELECT COUNT(*) Count FROM Ventas WHERE Id_Venta='$Id_Venta'")['Count'];
            echo json_encode($val);
            break;


        case 'save_Venta':
            if (empty($Id_Venta)){
                $insert = ejecutarConsulta("INSERT INTO Ventas (Id_User, Cliente, Tel, Correo, Direccion, Fec_Alta, Obs, Descuento, Status)
                                            VALUES ('$Id_User','$Cliente','$Tel','$Correo','$Direccion', '$Fecha', '$Obs', 0, 'A');");
                echo $insert ? "El registro de venta se guardó correctamente" : "Ocurrió un error al guardar el registro de venta :(";
            } else {
                empty($Descuento) ? $Descuento = 0 : "";
                $update = ejecutarConsulta("UPDATE Ventas SET Cliente='$Cliente', Tel='$Tel', Correo='$Correo', Direccion='$Direccion'
                                            , Obs='$Obs', Descuento='$Descuento' WHERE Id_Venta='$Id_Venta'");
                echo $update ? "El registro de venta se actualizo correctamente" : "Ocurrió un error al actualizar el registro de venta :(";
            }
            break;


        // Caso para listado de materiales
        case 'listar_Ventas':
            $sql = ejecutarConsulta("SELECT * FROM Ventas");
            $data = array();

            while($rst = $sql -> fetch_object()){                
                $Status = "";
                $print = "<button class='btn btn-sm btn-outline-danger' title='Imprimir venta' onclick='impVenta($rst->Id_Venta)'><i class='fas fa-file-pdf fa-beat'></i></button>";
                switch($rst->Status){
                    case 'A': $Status = "<div class='badge badge-info'>En venta</div>"; break;
                    case 'U': $Status = "<div class='badge badge-success'>Finalizado</div>"; break;
                    case 'B': $Status = "<div class='badge badge-secondary'>Cancelado</div>"; $print =""; break;
                }

                $btn = "<div class='text-center'>
                    <button class='btn btn-sm btn-outline-info' title='Ver venta' onclick='subir();verVenta($rst->Id_Venta)'><i class='fa-solid fa-arrow-up-right-from-square fa-beat'></i></i></i></button>
                    $print
                </div>";


                $Imp_Desc = 0;
                if ($rst->Descuento > 0){
                    $Imp_Desc = $rst->Total * ($rst->Descuento / 100);
                }

                $Sub = $rst -> Total;

                $Total = $Sub - $Imp_Desc;

                $data[] = array(
                    "0" => $rst->Id_Venta,
                    "1" => $rst->Cliente,
                    "2" => $rst->Fec_Venta,                    
                    "3" => "$".number_format($Sub, 2),
                    "4" => "$".number_format($Imp_Desc, 2),
                    "5" => "$".number_format($Total, 2),
                    "6" => $Status,
                    "7" => $btn
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

        // Caso para ver información de venta
        case 'verVenta':
            $data = ejecutarConsultaSimpleFila("SELECT * FROM Ventas WHERE Id_Venta='$Id_Venta'");
            $data['TotLetra'] = convertir(floatval($data['Total']));
            echo json_encode($data);
            break;


        // Caso para ver un material
        case 'ver_Mat':
            $data = ejecutarConsultaSimpleFila("SELECT CM.*, Abrev UM FROM Cat_Materiales CM LEFT JOIN Cat_Unidad_Medida UM ON (CM.Id_UM2=UM.Id_UM) WHERE Id_Mat=$Id_Mat");
            $Ganancia = $data['Costo'] * ($data['Ganancia'] / 100);
            $data['Cost'] = $data['Costo'] + $Ganancia;

            $r = ($data['Cost'] - intval($data['Cost']))*100; 
                
            if ($r >= 30){
                $data['Cost'] = ceil($data['Cost']);
            } else {
                if ($data['Cost'] <= 0.3){
                    $data['Cost'] = 0.5;
                } else {
                    $data['Cost'] = floor($data['Cost']);
                }
            }

            echo json_encode($data);
            break;

        // Caso para validar la existencia de un material en la nota de venta
        case 'valMat':
            $val = ejecutarConsultaSimpleFila("SELECT COUNT(*) FROM Ventas_Mat WHERE Id_Mat=$Id_Mat")[0];
            echo $val > 0 ? json_encode(true) : json_encode(false);
            break;


        // Caso para agregar materiales a la venta
        case 'save_Mat':
            // Validamos si el material ya existe
            $val = ejecutarConsultaSimpleFila("SELECT COUNT(*) Existe FROM Ventas_Mat WHERE Id_Venta='$Id_Venta' AND Id_Mat='$Id_Mat'")['Existe'];

            if ($val == 0){
                $Cons = ejecutarConsultaSimpleFila("SELECT Cons FROM Ventas_Mat WHERE Id_Venta='$Id_Venta' ORDER BY Cons DESC LIMIT 1");
                $Cons = $Cons != null ? $Cons[0]+1 : 1;
                $insert = ejecutarConsulta("INSERT INTO Ventas_Mat (Id_Venta, Cons, Id_Mat, Cant, Devolucion, Costo, Ganancia)
                                            VALUES('$Id_Venta', '$Cons', '$Id_Mat', '$Cant', 0, '$Costo', '$Ganancia')");
                if ($insert) {
                    ejecutarConsulta("UPDATE Cat_Materiales SET Stock=Stock-$Cant WHERE Id_Mat=$Id_Mat");
                    echo "El material se agregó correctamente";
                } else {
                    echo "Ocurrió un error al agregar el material :(";
                }
            } else {
                echo "El material ya ha sido agregado anteriormente! ";
            }
            break;

        
        //caso para listar materiales de venta
        case 'mat_Venta':
            $sql = ejecutarConsulta("SELECT V.*, Desc_Mat, Abrev FROM Ventas_Mat V LEFT JOIN Cat_Materiales M ON (V.Id_Mat=M.Id_Mat)
                                    LEFT JOIN Cat_Unidad_Medida U ON (Id_UM2=Id_UM) WHERE Id_Venta='$Id_Venta' ORDER BY Cons DESC");
            $data = array();

            while($rst = $sql -> fetch_object()){
                $Status = ejecutarConsultaSimpleFila("SELECT Status FROM Ventas WHERE Id_Venta=$Id_Venta")['Status'];

                $sts = "";
                switch($Status) {
                    case 'A': $sts = "<div class='badge badge-info'>En venta</div>"; break;
                    case 'U': $sts = "<div class='badge badge-success'>Vendido</div>"; break;
                }

                if ($rst ->Devolucion > 0){
                    $sts = "<div class='badge badge-secondary'><b>Devuelto: $rst->Devolucion</b></div>";
                }


                $btn = "";
                if ($Status == 'A'){
                    $btn = "<div class='text-center' 'Quitar articulo'>
                        <button class='btn btn-sm btn-outline-danger' onclick='delMat($Id_Venta, $rst->Cons, $rst->Id_Mat, $rst->Cant)'>
                            <i class='fas fa-times'></i></button>
                    </div>";
                } else if ($Status == 'U'){
                    $btn = "<div class='text-center' title='Devolver articulo'>
                        <button class='btn btn-sm btn-outline-warning' data-toggle='modal' data-target='#Dev'
                            onclick='devMat($Id_Venta, $rst->Cons, $rst->Id_Mat, $rst->Cant)'>
                            <i class='fa-solid fa-hand-holding-dollar fa-flip-horizontal text-dark'></i></button>
                    </div>";
                }

                $Ganancia = $rst -> Costo * ($rst->Ganancia / 100);
                $Costo = $rst -> Costo + $Ganancia;

                $r = ($Costo - intval($Costo))*100; 

                if ($r >= 30){
                    $Costo = ceil($Costo);
                } else {
                    if ($Costo <= 0.3){
                        $Costo = 0.5;
                    } else {
                        $Costo = floor($Costo);
                    }
                }

                $Imp = $rst -> Cant * $Costo;

                $data[] = array(
                    "0" => $rst -> Cons,
                    "1" => $rst -> Desc_Mat,
                    "2" => $rst -> Abrev,
                    "3" => $rst -> Cant,
                    "4" => "$". number_format($Costo, 2),
                    "5" => "$". number_format($Imp, 2),
                    "6" => $sts,
                    "7" => $btn
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


        // Caso para actualizar el total
        case 'updateTot':
            $sql = ejecutarConsulta("SELECT * FROM Ventas_Mat WHERE Id_Venta=$Id_Venta");
            $Total = 0;

            while($rst = $sql -> fetch_object()){
                $Ganancia = $rst -> Costo * ($rst->Ganancia / 100);

                $Costo = $rst->Costo + $Ganancia;

                $r = ($Costo - intval($Costo))*100; 
                
                if ($r >= 30){
                    $Costo = ceil($Costo);
                } else {
                    if ($Costo <= 0.3){
                        $Costo = 0.5;
                    } else {
                        $Costo = floor($Costo);
                    }
                }

                $Imp = $rst -> Cant * $Costo;
                $Total += $Imp;
            }

            ejecutarConsulta("UPDATE Ventas SET Total=$Total WHERE Id_Venta=$Id_Venta");
            echo json_encode(array("Total" => $Total, "TotLetra" => convertir($Total)));
            break;


        // Caso para select de materiales
        case 'select_Mat':
            $sql = ejecutarConsulta("SELECT * FROM Cat_Materiales WHERE Status='A' ORDER BY Desc_Mat ASC");

            while ($rst = $sql -> fetch_object()){
                echo "<option class='text-dark' value=$rst->Id_Mat data-subtext='Stock: $rst->Stock'>$rst->Desc_Mat</option>";
            }
            break;
            

        // Caso para validar la existencia de un material
        case 'existMat':
            $existe = ejecutarConsulta("SELECT EXISTS(SELECT * FROM Cat_Mat WHERE Cve_Mat='$Cve_Mat' AND $Id_Mat!='$Id_Mat')");
            echo $existe? json_encode(true) : json_encode(false);
            break;
            
        // Caso par eliminar un material de la venta
        case 'delMat':
            $delete = ejecutarConsulta("DELETE FROM Ventas_Mat WHERE Id_Venta=$Id_Venta AND Id_Mat=$Id_Mat AND Cons=$Cons");
            if ($delete){
                // Regresamos el material al inventario
                ejecutarConsulta("UPDATE Cat_Materiales SET Stock=Stock+$Cant WHERE Id_Mat=$Id_Mat");
                echo "El material se eliminó correctamente";
            } else {
                echo "Ocurrió un error al eliminar un material :(";
            }
            break;

        // Caso para finalizar una venta
        case 'finalizar':
            $finalizar = ejecutarConsulta("UPDATE Ventas SET Status='U', Fec_Venta='$Fecha' WHERE Id_Venta=$Id_Venta");
            echo $finalizar ? "La venta se finalizó correctamente" : "Ocurrió un error al fonalizar la venta :(";
            break;
            

        // Caso para cancelar una venta
        case 'cancelar':
            $cancelar = ejecutarConsulta("UPDATE Ventas SET Status='B' WHERE Id_Venta=$Id_Venta");

            if ($cancelar) {
                // Devolvemos el material al inventario
                $sql = ejecutarConsulta("SELECT * FROM Ventas_Mat WHERE Id_Venta=$Id_Venta");
                while($rst = $sql -> fetch_object()){
                    ejecutarConsulta("UPDATE Cat_Materiales SET Stock=Stock + $rst->Cant WHERE Id_Mat=$rst->Id_Mat");
                }
                // Eliminamos los articulos agregados
                ejecutarConsulta("DELETE FROM Ventas_Mat WHERE Id_Venta=$Id_Venta");

                echo "La venta se canceló correctamente";
            } else {
                echo "Ocurrió un error al cancelar la venta :(";
            }
            break;


        // Caso para devolver materiales
        case 'devMat':
            $dev = ejecutarConsulta("UPDATE Ventas_Mat SET Cant=Cant-$Devolucion, Devolucion='$Devolucion', Fec_Dev='$Fecha'
                    WHERE Id_Venta=$Id_Venta AND Id_Mat=$Id_Mat AND Cons=$Cons");
            if ($dev){
                ejecutarConsulta("UPDATE Cat_Materiales SET Stock=Stock+$Cant WHERE Id_Mat=$Id_Mat");
                echo "El articulo se devolvio correctamente";
            } else {
                echo "Ocurrió un error al devolver el articulo";
            }
            break;

        // Caso para filtro de ventas
        case 'ventas':
            $Filtro =  isset($_POST['Filtro']) ? $_POST['Filtro'] : "";
            $Inicio =  isset($_POST['Inicio']) ? $_POST['Inicio'] : "";
            $Fin =  isset($_POST['Fin']) ? $_POST['Fin'] : "";
            $Total = 0;

            if ($Filtro ==''){ // Filtramos por periodo
                $Total = ejecutarConsultaSimpleFila("SELECT SUM(Total - Total*(Descuento/100)) Total FROM Ventas
                        WHERE CONVERT(Fec_Venta,date) >='$Inicio' && CONVERT(Fec_Venta,date) <= '$Fin'")['Total'];
            } else {
                switch($Filtro){
                    case 'Hoy'; $Filtro = "WHERE Fec_Venta LIKE '%".date('Y-m-d')."%'"; break;
                    case 'Semana'; $Filtro = "WHERE WEEK(Fec_Venta)=".date('W'); break;
                    case 'Mes'; $Filtro =  "WHERE MONTH(Fec_Venta)=".date('m'); break;
                    case 'Year'; $Filtro = "WHERE YEAR(Fec_Venta)=".date('Y'); break;
                    default: $Filtro = " WHERE Status='U' "; break;
                }

                $Total = ejecutarConsultaSimpleFila("SELECT SUM(Total - Total*(Descuento/100)) Total FROM Ventas $Filtro")['Total'];
            }

            echo "$".number_format($Total, 2);            
            break;

        // caso para editar el precio de un material
        case 'editPU':
            $edit = ejecutarConsulta("UPDATE Cat_Materiales SET Ganancia='$Ganancia' WHERE Id_Mat=$Id_Mat");
            echo $edit? "El precio del artículo se actualizó correctamente" : "Ocurrió un error al actualizar el precio del artículo :(";
            break;

        // Caso para listado de materiales
        case 'listar_Mat':
            $sql = ejecutarConsulta("SELECT CM.*, Abrev UM FROM Cat_Materiales CM LEFT JOIN Cat_Unidad_Medida UM ON (CM.Id_UM2=UM.Id_UM)");
            $data = array();

            while($rst = $sql -> fetch_object()){      
                $btn = "<div class='text-center'>
                    <button class='btn btn-sm btn-outline-info' onclick='verMat($rst->Id_Mat)'><i class='fa-regular fa-hand-pointer fa-beat'></i></button>
                </div>";
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
                    "0" => $btn,
                    "1" => "<div onclick='verMat($rst->Id_Mat)'>$rst->Desc_Mat</div>",
                    "2" => "<div onclick='verMat($rst->Id_Mat)'>$rst->UM</div>",
                    "3" => "<div onclick='verMat($rst->Id_Mat)'>".number_format($rst -> Stock, 2)."</div>",
                    "4" => "<div onclick='verMat($rst->Id_Mat)'>$".number_format($Ganancia, 2)."</div>",
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
    }

?>