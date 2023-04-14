<?php
    session_start();
    include('../global/Header.php');
?>

    <!--    Título    -->
    <title>Inventario</title>
    </head>
<body> 
    
<style>
    .ir-arriba {
        display:none;
        padding:10.3px;
        background:#024959;
        font-size:12px;
        color:#fff;
        cursor:pointer;
        position: fixed;
        bottom:15px;
        right:10px;
    }

    .bootstrap-select .dropdown-menu{ max-width: 100%!important; }
    table { font-size: small; }
</style>
<!--Contenido-->
<!-- Content Wrapper. Contains page content -->
<?php include "../global/menu.php"; ?>
<div class="content-wrapper">    
    <div class="col-md-12">
        <div class="box"> 
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="divProgramaciones">
                <div class="d-flex justify-content-center row">
                    <h2> Inventario
                        <button class='btn btn-outline-success btn-sm' id='btnAdd' title='Agregar nuevo'>Agregar <i class='fas fa-circle-plus fa-beat'></i></button>
                        <button class='btn btn-outline-warning text-dark btn-sm' id='btnMax' data-toggle="modal" data-target="#Maxi" title='Materiales en máximos'>
                            Máximos <i class="fa-solid fa-arrow-trend-up fa-beat"></i></button>
                        <button class='btn btn-outline-danger btn-sm' id='btnMin' data-toggle="modal" data-target="#Mini" title="Materiales en mínimos">
                            Mínimos <i class="fa-solid fa-arrow-trend-down fa-beat"></i></button>
                        <button class='btn btn-outline-success btn-sm' id='btnExcel' data-toggle="modal" data-target="#Update" title='Actualizar Stock'>
                            Atualizar <i class="fa-solid fa-file-excel fa-beat"></i></button>
                    </h2>
                </div>

                <!--        Div de formulario       -->
                <form class='was-validated' id="Form_Mat" name="Form_Mat" hidden>
                    <div class="form-row d-flex justify-content-center">
                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Clave del material">
                            <label for="Cve_Mat">Clave_Mat <span class="text-danger">*</span></label>
                            <input type="number" id='Id_Mat' name='Id_Mat' hidden>
                            <input type="text" id='Cve_Mat' name='Cve_Mat' class='form-control form-control-sm' maxlength=30 required>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Familia del material">
                            <label for="Id_Fam">Familia <span class="text-danger">*</span></label>
                            <select name="Id_Fam" id="Id_Fam" class='form-control form-control-sm'
                                data-live-search="true" data-size=15 title='Seleccionar...' required></select>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Unidad de medida recivida">
                            <label for="Id_UM1">UM. Recibida <span class="text-danger">*</span></label>
                            <select name="Id_UM1" id="Id_UM1" class='form-control form-control-sm ' title='Seleccionar...' 
                                data-live-search="true" data-size=15 required></select>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Unidad de mediad de entrega">
                            <label for="Id_UM2">UM. Entrega <span class="text-danger">*</span></label>
                            <select name="Id_UM2" id="Id_UM2" class='form-control form-control-sm' title='Seleccionar...' 
                                data-live-search="true" data-size=15 required></select>
                        </div>

                        <div class="form-group col-lg-4 col-md-4 col-sm-6 col-12" title="Proveedor">
                            <label for="Id_Prov">Proveedor <span class="text-danger">*</span></label>
                            <select name="Id_Prov" id="Id_Prov" class='form-control form-control-sm' title='Seleccionar...' 
                                data-live-search="true" data-size=15 required></select>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Stock">
                            <label for="Stock">Stock <span class="text-danger">*</span></label>
                            <input type="number" min=0 step='any' name="Stock" id="Stock" class="form-control form-control-sm"
                                    placeholder="0.00" required>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Mínimo">
                            <label for="Min">Mínimo <span class="text-danger">*</span></label>
                            <input type="number" min=0 step='any' name="Min" id="Min" class="form-control form-control-sm"
                                    placeholder="0.00" required>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Máximo">
                            <label for="Max">Máximo <span class="text-danger">*</span></label>
                            <input type="number" min=0 step='any' name="Max" id="Max" class="form-control form-control-sm"
                                    placeholder="0.00" required>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Costo">
                            <label for="Costo">Costo <span class="text-danger">*</span></label>
                            <input type="number" min=0 step='any' name="Costo" id="Costo" class="form-control form-control-sm"
                                    placeholder="$0.00" required>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Porcentaje de ganancia">
                            <label for="Ganancia">Ganancia <span class="text-danger">*</span></label>
                            <input type="number" min=0 step='any' name="Ganancia" id="Ganancia" class="form-control form-control-sm"
                                    placeholder="0.00%" required>
                        </div>

                        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Estado del material">
                            <label for="Status">Estado</label>
                            <input type="text" name="Status" id="Status" class="form-control form-control-sm" placeholder="Estado" readonly>
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12" title="Descripción del material">
                            <label for="Desc_Mat">Descripción <span class="text-danger">*</span></label>
                            <textarea name="Desc_Mat" id="Desc_Mat" rows="2" class="form-control form-control-sm" placeholder='Decripción...' required></textarea>
                        </div>
                    </div>

                    <div class="form-row d-flex justify-content-center">
                        <div class="form-group col-xs-1" title="Guardar">
                            <button type="submit" class="btn btn-outline-success btn-sm">Guardar <i class="fas fa-save fa-beat"></i></button>
                        </div>

                        <div class="form-group col-xs-1" title="Agregar familias">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#Familias" id="btnFam">
                                    Familias <i class="fa-solid fa-warehouse fa-beat"></i></button>
                        </div>

                        <div class="form-group col-xs-1" title="Agregar unidades de medida">
                            <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#Unidades" id="btnUM">
                                    Unidades <i class="fa-solid fa-ruler-combined fa-beat"></i></button>
                        </div>

                        <div class="form-group col-xs-1" title="Limpiar">
                            <button type="button" class="btn btn-outline-dark btn-sm" id="btnErase">Limpiar <i class="fas fa-eraser fa-beat"></i></button>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <div class="alert alert-success" role="alert">
                        <div class='form-row d-felx justify-content-around text-center'>
                            <div class='col-xs-1'>
                                <label>Total inventario (Compra): </label>
                                <b id="Compra">$0.00</b>
                            </div>
                                
                            <div class='col-xs-1'>
                                <label>Total inventario (Venta): </label>
                                <b id="Venta">$0.00</b>
                            </div>

                            <div class='col-xs-1'>
                                <label>Diferencia: </label>
                                <b id="Diferencia">$0.00</b>
                            </div>
                        </div>
                    </div>

                    <table class="table table-sm table-hover table-striped compact" id="tbl_Mat">
                        <thead class="text-center bg-success text-white">
                            <tr>
                                <th>No</th>
                                <th>Clave</th>
                                <th>Descripción</th>
                                <th>UM</th>
                                <th>Stock</th>
                                <th>Max</th>
                                <th>Min</th>
                                <th>PU</th>
                                <th>P.Publico</th>
                                <th>Status</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!--    Modal para agregar familias    -->
            <div class="modal fade" id="Familias" data-keyboard="false" tabindex="-1" aria-labelledby="FamiliasLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-light">
                            <h5 class="modal-title" id="FamiliasLabel"><i class="fa-solid fa-warehouse"></i> Familias</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class='was-validated' id='formFamilias'>
                                <div class="form-row">
                                    <div class="form-group col-lg-10 col-md-8 col-sm-6 col-12" title="Familia">
                                        <label for="Desc_Fam">Familia <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm" id="Id_F" name="Id_Fam" step="any" hidden>
                                        <input type="text" class="form-control form-control-sm" id="Desc_Fam" name="Desc_Fam" placeholder='Familia...' maxlength=50 required>
                                    </div>

                                    <div class="form-group col-lg-2 col-md-4 col-sm-6 col-12" title="Precio de venta">
                                        <label for="Ganancia">Ganancia <span class="text-danger">*</span></label>
                                        <input type="number" min=0 class="form-control form-control-sm" id="Gan" placeholder='0.00%' name="Ganancia" step="any" required>
                                    </div>
                                </div>

                                <div class="form-row d-flex justify-content-center">
                                    <div class="form-group col-xs-1" title="Guardar">
                                        <button type="submit" class="btn btn-sm btn-outline-success">Guardar <i class="fas fa-save fa-beat"></i></button>
                                    </div>

                                    <div class="form-group col-xs-1" title="Cerrar ventana">
                                        <button type="button" class="btn btn-sm btn-outline-dark" data-dismiss="modal">Cancelar <i class="fas fa-times-circle fa-beat"></i></button>
                                    </div>
                                </div>
                            </form>

                            <!--        Tabla de Familias      -->
                            <div class="table-responsive">
                                <table class='table table-sm table-striped compact' id='tblFam'>
                                    <thead class="text-center bg-success text-white">
                                        <tr>
                                            <th>Familias</th>
                                            <th>Pre_Venta</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--        Modal para agregar unidades de medida      -->
            <div class="modal fade" id="Unidades" data-keyboard="false" tabindex="-1" aria-labelledby="UnidadesLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-light">
                            <h5 class="modal-title" id="UnidadesLabel"><i class="fa-solid fa-ruler-combined"></i> Unidades de medida</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form class='was-validated' id='formUnidades'>
                                <div class="form-row">
                                    <div class="form-group col-lg-8 col-md-8 col-sm-6 col-12" title="Unidad de medida">
                                        <label for="Desc_UM">Familia <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm" id="Id_UM" name="Id_UM" step="any" hidden>
                                        <input type="text" class="form-control form-control-sm" id="Desc_UM" name="Desc_UM" placeholder='Unidad de medida...' maxlength=30 required>
                                    </div>

                                    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-12" title="Abreviación">
                                        <label for="Abrev">Abreviación<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="Abrev" placeholder='Abreviación...' name="Abrev" step="any" required>
                                    </div>
                                </div>

                                <div class="form-row d-flex justify-content-center">
                                    <div class="form-group col-xs-1" title="Guardar">
                                        <button type="submit" class="btn btn-sm btn-outline-success">Guardar <i class="fas fa-save"></i></button>
                                    </div>

                                    <div class="form-group col-xs-1" title="Cerrar ventana">
                                        <button type="button" class="btn btn-sm btn-outline-dark" data-dismiss="modal">Cancelar <i class="fas fa-times-circle"></i></button>
                                    </div>
                                </div>
                            </form>

                            <!--        Tabla de Unidades      -->
                            <div class="table-responsive">
                                <table class='table table-sm table-striped compact' id='tblUM'>
                                    <thead class="text-center bg-success text-white">
                                        <tr>
                                            <th>Unidades</th>
                                            <th>Abreviación</th>
                                            <th>Editar</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--        Modal ver los Maximo      -->
            <div class="modal fade" id="Maxi" data-keyboard="false" tabindex="-1" aria-labelledby="MaxiLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-light">
                            <h5 class="modal-title" id="MaxiLabel"><i class="fa-solid fa-arrow-trend-up"></i> Máximos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <!--        Tabla de Max      -->
                            <div class="table-responsive">
                                <table class='table table-sm table-striped compact' id='tblMax'>
                                    <thead class="text-center bg-success text-white">
                                        <tr>
                                            <th>No</th>
                                            <th>Cleve</th>
                                            <th>Descripción</th>
                                            <th>UM</th>
                                            <th>Stock</th>
                                            <th>Máximo</th>
                                            <th>PU</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--        Modal ver los Minimo      -->
            <div class="modal fade" id="Mini" data-keyboard="false" tabindex="-1" aria-labelledby="MiniLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-light">
                            <h5 class="modal-title" id="MiniLabel"><i class="fa-solid fa-arrow-trend-down"></i> Mínimos</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <!--        Tabla de Min      -->
                            <div class="table-responsive">
                                <table class='table table-sm table-striped compact' id='tblMin'>
                                    <thead class="text-center bg-success text-white">
                                        <tr>
                                            <th>No</th>
                                            <th>Clave</th>
                                            <th>Descripción</th>
                                            <th>UM</th>
                                            <th>Stock</th>
                                            <th>Mínimo</th>
                                            <th>PU</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--        Modal actualizar stock      -->
            <div class="modal fade" id="Update" data-keyboard="false" tabindex="-1" aria-labelledby="UpdateLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-light">
                            <h5 class="modal-title" id="UpdateLabel"><i class="fa-solid fa-arrow-trend-down"></i> Actualizar Stock</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form id='Fomr_Stock' name='Form_Stock' class="was-validated">
                                <div class='form-row'>
                                    <div class='form-goup col-6'>
                                        <label for="Archivo">Archivo </label>
                                        <input type="file" name="Archivo" id="Archivo" accept=".xlsx, .xls, .csv" class="form-control form-control-sm" required/>
                                    </div>

                                    <div class='form-goup col-xs-1 mt-4'>
                                        <button type='Submit' class="btn btn-sm btn-outline-success mt-2" title="Actualizar inventario">Actualizar <i class="fa-solid fa-file-excel fa-beat"></i></button>
                                    </div>
                                </div>
                            </form>

                            <!--        Tabla ejemplo      -->
                            <div class="alert alert-success mt-2" role="alert">
                                <i class="fa-solid fa-circle-info"></i> El archivo debe ser un formato de excel con la siguiente estructura:
                            </div>
                            <div class="table-responsive">
                                <table class='table table-sm table-striped table-hover compact'>
                                    <thead class="text-center bg-success text-white">
                                        <tr>
                                            <th>Clave</th>
                                            <th>Stock</th>
                                            <th>PU <small>(Opcional)</small></th>
                                        </tr>
                                    </thead>
                                    <tbody class='text-center'>
                                        <tr>
                                            <td>XXXXX-xx</td>
                                            <td>100</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>YYYYY-yy</td>
                                            <td>200</td>
                                            <td>34.50</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-warning" role="alert">
                                <i class="fa-solid fa-warning"></i> Si hay algun error con algunos materialeles se mostarrán en esta sección
                            </div>

                            <!--        Tabla de error      -->
                            <div class="table-responsive mt-2" id='diverror'>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--    BOTON IR ARRIBA  -->
            <span class="ir-arriba" title="Subir"><i class="fas fa-chevron-up"></i></span>
        <!--Fin centro -->
        </div><!-- /.box --> 
    </div>
</div><!-- /.content-wrapper -->
<!--Fin-Contenido-->
<!--js usuariosConectados-->

<?php include('../global/Fooder.php') ?>
<script src="../js/cat_materiales.js"></script>

</body>
</html>