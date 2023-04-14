<?php 
    // Conexión a la BD
    include '../../global/conexion.php';
    // Numeros a letras
    include '../numaLetras.php';    

    $Id_Venta = isset($_GET['Id_Venta']) ? base64_decode($_GET['Id_Venta']) : "";

    // Consultamos información de la nota de venta
    $venta = ejecutarConsultaSimpleFila("SELECT * FROM Ventas WHERE Id_Venta='$Id_Venta'");
    
    // Consultamos los articulos de la venta
    $sql = ejecutarConsulta("SELECT V.*, Desc_Mat, Abrev FROM Ventas_Mat V LEFT JOIN Cat_Materiales M ON (V.Id_Mat=M.Id_Mat)
                            LEFT JOIN Cat_Unidad_Medida U ON (Id_UM2=Id_UM) WHERE Id_Venta='$Id_Venta' ORDER BY Cons DESC");
    $mat = "";
    $i = 1;

    while($rst = $sql -> fetch_object()){
        $Ganancia = $rst -> Costo * ($rst->Ganancia / 100);
        $Costo = $rst -> Costo + $Ganancia;

        $Imp = $rst -> Cant * $Costo;
        
        $bg = $i % 2 == 0 ? "  " : " bg1 ";

        $mat .= "<tr class='$bg'>
            <td align='center' width='20'>$i</td>
            <td colspan='5' width='360' class='justify'>$rst->Desc_Mat</td>
            <td  width='40' class='justify'>$rst->Abrev</td>
            <td class='text-right' width='60'>".number_format($rst->Cant, 2)."</td>
            <td class='text-right' width='70'>$".number_format($Costo, 2)."</td>
            <th class='text-right' width='80'>$".number_format($Imp, 2)."</tH>
        </tr>";
        $i++;
    }
    
    $Fecha = date("Y-m-d",strtotime($venta['Fec_Venta']));

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
        align-content: flex-start;
        margin: 0px;
        padding: 0px;
    }
</style>

<page backtop="40mm" backbottom="15mm" backleft="15mm" backright="15mm">
    <page_header>
        <table align="center" cellspacing=0>
            <tr>
                <th colspan='10'><h1>Nota de venta</h1></th>
            </tr>
            <tr>
                <th rowspan="2" width='75' colspan="1"><img src="../../img/Logo.png" width="60" alt='logo'/></th>
                <th rowspan="2" width='475' colspan="7" align='center' class='f16'>Automatización y Sistema de Bombeo</th>
                <th width='40' align='right'>No.: &nbsp;</th>
                <th width='60' class="border-b"><?php echo $Id_Venta; ?></th>
            </tr>
            <tr>
                <th>Fecha:</th>
                <th class="border-b"><?php echo $Fecha; ?></th>
            </tr>
        </table>

        <table align="center" cellspacing=0>
            <tr>
                <th colspan="2" width='130'>Nombre del cliente: </th>
                <th colspan="8" width='525' class="border-b"><?php echo $venta['Cliente']; ?></th>
            </tr>
            <tr>
                <th colspan="2">Correo: </th>
                <th colspan="4" width='265' class="border-b"><?php echo $venta['Correo']; ?></th>
                <th colspan="1" width='65' align='right'>Teléfono: &nbsp;</th>
                <th colspan="3" width='195' class="border-b"><?php echo $venta['Tel']; ?></th>
            </tr>
            <tr>
                <th colspan="2">Dirección: </th>
                <th colspan="8" width='505' class="border-b"><?php echo $venta['Direccion']; ?></th>
            </tr>
        </table>
    </page_header>

        <!--    Body    -->
        <table cellspacing=0>
            <tr class="f12 bg-header">
                <th height='25' align="center" width='20'>No</th>
                <th align="center" colspan="5" width='380'>Descripción</th>
                <th width='40'>UM</th>
                <th align="center" width='60'>Cantidad</th>
                <th align="center" width='70'>PU</th>
                <th align="center" width='80'>Importe</th>
            </tr>

            <?php echo $mat; ?>

            <?php if ($venta['Descuento'] > 0){ ?>  
                <tr>
                    <th class='b-Top' align="right" colspan="9">Subtotal</th>
                    <th class='b-Top' align="right">$ <?php echo number_format($venta['Total'], 2); ?> </th>
                </tr>

                <?php
                    $Imp_Desc = $venta['Total'] * ($venta['Descuento'] / 100);
                    $venta['Total'] -= $Imp_Desc
                ?>
                
                <tr>
                    <th class='b-Top' align="right" colspan="9">Descuento <?php echo number_format($venta['Descuento'], 2); ?>%</th>
                    <th class='b-Top' align="right">$ <?php echo number_format($Imp_Desc, 2); ?> </th>
                </tr>

                <tr>
                    <th class='b-Top' align="right" colspan="9">Total</th>
                    <th class='b-Top' align="right">$ <?php echo number_format($venta['Total'], 2); ?> </th>
                </tr>
            <?php } else { ?>
                <tr>
                    <th class='b-Top' align="right" colspan="9">Total</th>
                    <th class='b-Top' align="right">$ <?php echo number_format($venta['Total'], 2); ?> </th>
                </tr>
            <?php } ?>

            <tr>
                <th align="right" colspan="10"><?php echo convertir($venta['Total']); ?></th>
            </tr>
        </table>

        <br>

        <table border="0.5" cellspacing=0>
            <tr class="justify">
                <th class='padding' width='655' height='50'>Comentarios: <?php echo $venta['Obs']; ?></th>
            </tr>
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