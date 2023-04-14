<?php 
    // Conexión a la BD
    include '../../global/conexion.php';
    // Numeros a letras
    include '../numaLetras.php'; 

    $Filtro = isset($_GET['Filtro']) ? base64_decode($_GET['Filtro']) : "";
    $Inicio = isset($_GET['Inicio']) ? base64_decode($_GET['Inicio']) : "";
    $Fin = isset($_GET['Fin']) ? base64_decode($_GET['Fin']) : "";
    $periodo = "";

    if (!empty($Inicio) && !empty($Fin)){ // Filtramos por periodo
        $periodo = $Inicio." A ".$Fin;
        $sql = ejecutarConsulta("SELECT * FROM Ventas WHERE Fec_Venta >='$Inicio' AND Fec_Venta <= '$Fin'");
    } else {
        switch($Filtro){
            case 'Hoy';
                $Filtro = "WHERE Fec_Venta LIKE '%".date('Y-m-d')."%'";
                $periodo = "Hoy ".date('Y-m-d');
                break;
            case 'Semana';
                $Filtro = "WHERE WEEK(Fec_Venta)=".date('W');
                $periodo = "Semana ".date('W')." - ".date('Y');
                break;
            case 'Mes';
                $Filtro =  "WHERE MONTH(Fec_Venta)=".date('m');
                $periodo = "Mes ".date('m')." - ".date('Y');;
                break;
            case 'Year';
                $Filtro = "WHERE YEAR(Fec_Venta)=".date('Y');
                $periodo = "Año ".date('Y');
                break;
            default:
                $Filtro = " WHERE Status='U' ";
                $periodo = "Todos";
                break;
        }

        $sql = ejecutarConsulta("SELECT * FROM Ventas $Filtro");
    }

    $TOTAL = 0;
    $GANANCIA = 0;
    $DESCUENTO = 0;

    $i = 1;
    $vent = "";
    $Resumen = "";

    while($rst = $sql -> fetch_object()){
        // Consultamos los articulos de la venta
        $mat = ejecutarConsulta("SELECT V.*, Desc_Mat, Abrev FROM Ventas_Mat V LEFT JOIN Cat_Materiales M ON (V.Id_Mat=M.Id_Mat)
                                LEFT JOIN Cat_Unidad_Medida U ON (Id_UM2=Id_UM) WHERE Id_Venta=$rst->Id_Venta ORDER BY Cons DESC");        
        $bg = $i % 2 == 0 ? "" : " bg1 ";
        $rowspan = ejecutarConsultaSimpleFila("SELECT COUNT(*) Count FROM Ventas_Mat WHERE Id_Venta=$rst->Id_Venta")['Count'] + 1;
        $rst -> Descuento > 0 ? $rowspan += 2 : "";

        $Total = 0;
        $j = 1;
        while($rst2 = $mat -> fetch_object()){
            $Ganancia = $rst2 -> Costo * ($rst2->Ganancia / 100);
            $Precio = $rst2 -> Costo + $Ganancia;
            $Imp = $rst2 -> Cant * $Precio;

            $Total += $Imp; // Total de venta actual
            $GANANCIA += ($Ganancia * $rst2->Cant); // Ganancia total de ventas

            if ($j == 1){
                $vent .= "<tr class='$bg'>
                    <td align='center' width='25' rowspan='$rowspan'>$i </td>
                    <td colspan='5' width='350' class='justify'>$rst2->Desc_Mat</td>
                    <td  width='40' class='justify'>$rst2->Abrev</td>
                    <td class='text-right' width='60'>".number_format($rst2 -> Cant, 2)."</td>
                    <td class='text-right' width='70'>$".number_format($rst2 -> Costo, 2)."</td>
                    <td class='text-right' width='70'>$".number_format($Precio, 2)."</td>
                    <th class='text-right' width='80'>$".number_format($Imp, 2)."</tH>
                </tr>";
            } else {
                $vent .= "<tr class='$bg'>
                    <td colspan='5' width='350' class='justify'>$rst2->Desc_Mat</td>
                    <td  width='40' class='justify'>$rst2->Abrev</td>
                    <td class='text-right' width='60'>".number_format($rst2 -> Cant, 2)."</td>
                    <td class='text-right' width='70'>$".number_format($rst2 -> Costo, 2)."</td>
                    <td class='text-right' width='70'>$".number_format($Precio, 2)."</td>
                    <th class='text-right' width='80'>$".number_format($Imp, 2)."</tH>
                </tr>";
            }

            $j++;
        }

        // Calculamos el total y el descuento
        if ($rst -> Descuento > 0) {
            $Descuento = $Total * ($rst->Descuento/100); // Descuento de la venta actual
            $DESCUENTO += $Descuento;
            $Tot_Desc = $Total - $Descuento; // Total con descuento de la venta actual
            $TOTAL += $Tot_Desc;  // Total acumulado de todas la ventas

            $vent .= "<tr class='$bg'>
                    <th colspan='9' align='right'>Subotal:</th>
                    <th class='text-right b-top' width='80'>$".number_format($Total, 2)."</tH>
                </tr>
                <tr class='$bg'>
                    <th colspan='9' align='right'>Descuento:</th>
                    <th class='text-right' width='80'>$".number_format($Descuento, 2)."</tH>
                </tr>
                <tr class='$bg'>
                <th colspan='9' align='right'>Total:</th>
                <th class='text-right' width='80'>$".number_format($Tot_Desc, 2)."</tH>
            </tr>";
        } else {
            $TOTAL += $Total;  // Total acumulado de todas la ventas

            $vent .= "<tr class='$bg'>
                <th colspan='9' align='right'>Total: </th>
                <th class='text-right b-top' width='80'>$".number_format($Total, 2)."</tH>
            </tr>";
        }

        $i++;
    }

    $vent .= "<tr>
        <th colspan='10' class='b-top' align='right'>SUBTOTAL: </th>
        <th class='text-right b-top' width='80'>$".number_format($TOTAL, 2)."</tH>
    </tr>
    <tr>
        <th colspan='10' align='right'>DESCUENTOS TOTALES: </th>
        <th class='text-right' width='80'>$".number_format($DESCUENTO, 2)."</tH>
    </tr>
    <tr>
        <th colspan='10' align='right'>GRAN TOTAL: </th>
        <th class='text-right' width='80'>$".number_format($TOTAL - $DESCUENTO, 2)."</tH>
    </tr>
    <tr>
        <th colspan='10' align='right'>GANANCIA SOBRE LA VENTA: </th>
        <th class='text-right b-top' width='80'>$".number_format($GANANCIA, 2)."</tH>
    </tr>";

    ob_start();
    header(' charset=utf-8 ');
?>

<style>
    .padding { padding: 2px; }
    .bg1 { background-color: #f2ffe6; }
    .bg-header { background-color: #33cc33; }
    .b-Top { border-top: 0.5px solid black; }
    .border-b { border-bottom: 0.5px solid black; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }
    .text-top { text-align: start }
    .justify { text-align: justify; }
    .f16 { font-size: 16px; }
    .f14 { font-size: 14px; }
    .f12 { font-size: 12px; }
    .f9 { font-size: 9px; }
    .c-green { color: grey; }
    .lightgray { background-color: #DFFFFF; }

    table {
        font-size: 11px;
        font-family: Arial, Helvetica, sans-serif;
        vertical-align: middle;
        margin: 0px;
        padding: 0px;
    }
</style>

<page backtop="20mm" backbottom="15mm" backleft="5mm" backright="15mm">
    <page_header>
        <table align="center" cellspacing=0>
            <tr>
                <th rowspan="3" width='80' colspan="1"><img src="../../img/Logo.png" width="85" alt='logo'/></th>
                <th width='350' colspan="9" align='center' class='f14'>Automatización y Sistema de Bombeo</th>
            </tr>
            <tr>
                <th  colspan="9" align='center' class='f12'>Reporte de ventas</th>                
            </tr>
            <tr>
                <td colspan=9 align='center'>Periodo: <?php echo $periodo; ?></td>
            </tr>
        </table>
    </page_header>

        <!--    Body    -->
        <table cellspacing=0,5 align='center'>
            <tr class="f12 bg-header">
                <th height='25' align="center" width='25'>N.V</th>
                <th align="center" colspan="5" width='300'>Descripción</th>
                <th width='40'>UM</th>
                <th align="center" width='60'>Cantidad</th>
                <th align="center" width='70'>Costo</th>
                <th align="center" width='70'>PU</th>
                <th align="center" width='80'>Importe</th>
            </tr>

            <?php echo $vent; ?>
        </table>

    <!--    Footer      -->
    <page_footer>
        <table class='c-green f12' align='center'>
            <tr>
                <th>Automatización y Sistema de Bombeo</th>
            </tr>
            <tr>
                <th>Ingeniería aplicada a sistemas de bombeo y Tratamiento de aguas residuales</th>
            </tr>
            <tr>
                <th>Privada Leona Vicario 10, Santa Rosa 30 centro, CP.:627772, Tlatizapán Morelos</th>
            </tr>

            <tr>
                <th>[ <a href='www.asbombeo.com'>www.asbombeo.com</a> | <a href='mailto:ventas@asbombeo.com'>ventas@asbombeo.com</a> | <a href="tel:734-108-9680">Tel:734-108-9680</a> ]</th>
            </tr>
        </table>
    </page_footer>
</page>
<?php
  $content = ob_get_clean();
  require __DIR__ . '../../../Library/vendor/autoload.php';

  use Spipu\Html2Pdf\Html2Pdf;
  $html2pdf = new Html2Pdf('P', 'LETTER', 'es', 'true', 'UTF-8');
  $html2pdf->writeHTML($content);
  $html2pdf->output('Nota de venta.pdf');
?>