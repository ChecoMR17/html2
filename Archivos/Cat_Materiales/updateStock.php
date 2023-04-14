<?php
    
    //session_start();
    include_once '../../Library/PHPExcel/autoload.php';
	include '../../global/conexion.php';

    $Archivo = $_FILES['Archivo']['tmp_name'];    
    $tbl = "<table class='table table-sm table-striped table-hover compact' id='tblerror'>
            <thead class='table-warning'>
                <tr>
                    <th>Clave</th>
                    <th>Stock</th>
                    <th>PU</th>
                    <th>Error</th>
                </tr>
            </thead>
            <tbody>";
    $j = 0;

    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
    $reader->setReadDataOnly(TRUE);
    $spreadsheet = $reader->load($Archivo);

    $worksheet = $spreadsheet->getActiveSheet();
    // Get the highest row and column numbers referenced in the worksheet
    $highestRow = $worksheet->getHighestRow(); // e.g. 10
    //$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
    //$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
    
    if ($highestRow > 0){
        for ($row = 1; $row <= $highestRow; ++$row) {
            $Cve_Mat = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            $Stock = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $Costo = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

            $Cve_Mat = str_replace(" ", "", $Cve_Mat);
            // Primero validamos que la clave no este vacia
            if(strlen($Cve_Mat) > 0){
                //OBTENER EL ID DEL MATERIAL
                $Id_Mat = ejecutarConsultaSimpleFila("SELECT COUNT(*) Reg FROM Cat_Materiales WHERE Cve_Mat='$Cve_Mat'")['Reg'];
                if ($Id_Mat > 0 ){
                    $Id_Mat = ejecutarConsultaSimpleFila("SELECT Id_Mat FROM Cat_Materiales WHERE Cve_Mat='$Cve_Mat'")['Id_Mat'];
                    $sql= ejecutarConsulta("UPDATE Cat_Materiales SET Stock=Stock+$Stock WHERE Id_Mat=$Id_Mat");
                    if(!$sql){
                        $tbl .= "<tr>
                            <td>$Cve_Mat</td>
                            <td>$Stock</td>
                            <td>$Costo</td>
                            <td>Error al actualizar</td>
                        </tr>";                        
                    } else {
                        $j++;
                    }
                } else {
                    $tbl .= "<tr>
                            <td>$Cve_Mat</td>
                            <td>$Stock</td>
                            <td>$Costo</td>
                            <td>La clave no existe en el inventario</td>
                        </tr>";
                }               
            }
        }

        $tbl .= "</tbody></table>";

        if ($j > 0){
            echo json_encode(array("msg" => "Inventario actualizado", "tbl" => $tbl));
        } else {
            echo json_encode(array("msg" => "Ocurrió un error al actualizar el inventario", "tbl" => $tbl));
        }
    } else {
        echo json_encode(array("msg" => "El archivo esta vacio", "tbl" => $tbl));
    }
?>