<?php 
session_start();
include('../global/Header.php') ?>

    <!--    Título    -->
    <title>Ventas</title>
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
                    <h2>Ventas <button class='btn btn-outline-success' id='btnAdd'>Agregar <i class="fa-solid fa-cart-plus fa-beat"></i></button>
                    </h2>
                </div>

                <!--        Div de formulario       -->
                <div id='div_Form' hidden>
                    <form class='was-validated' id="Form_Ventas" name="Form_Ventas">
                        <div class="form-row d-flex justify-content-center">
                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Número de venta">
                                <label for="Id_Venta">No. Venta <span class="text-danger">*</span></label>
                                <input type="number" id='Id_Venta' name='Id_Venta' class='form-control form-control-sm' readonly>
                            </div>

                            <div class="form-group col-lg-7 col-md-7 col-sm-4 col-12" title="Nombre del cliente">
                                <label for="Cliente">Cliente</label>
                                <input type="text" id='Cliente' name='Cliente' placeholder='Apellido paterno / Apellido materno / Nombre(s)' class='form-control form-control-sm'>
                            </div>

                            <div class="form-group col-lg-3 col-md-3 col-sm-4 col-12" title="Teléfono del cliente">
                                <label for="Tel">Teléfono</label>
                                <input type="tel" id='Tel' name='Tel' placeholder='' class='form-control form-control-sm' maxlength=15>
                            </div>

                            <div class="form-group col-lg-3 col-md-3 col-sm-4 col-12" title="Correo del cliente">
                                <label for="Correo">Correo</label>
                                <input type="email" id='Correo' name='Correo' placeholder='mail@email.com' class='form-control form-control-sm' maxlength=15>
                            </div>

                            <div class="form-group col-lg-9 col-md-9 col-sm-8 col-12" title="Dirección del cliente">
                                <label for="Direccion">Dirección</label>
                                <input type="text" id='Direccion' name='Direccion' placeholder='Dirección...' class='form-control form-control-sm' maxlength=15>
                            </div>

                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-12" title="Observaciones">
                                <label for="Obs">Observaciones</label>
                                <input type="text" name="Obs" id="Obs" class="form-control form-control-sm" placeholder="Observaciones...">
                            </div>

                            <div class="form-group col-lg-2 col-md-2 col-sm-6 col-12" title="Total">
                                <label for="Descuento">Descuento</label>
                                <input type="number" min=0 id='Descuento' name='Descuento' placeholder='0.00%' class='form-control form-control-sm'>
                                <span id='Imp_Desc'></span>
                            </div>

                            <div class="form-group col-lg-2 col-md-3 col-sm-6 col-12" title="Total">
                                <label for="Total">Total</label>
                                <input type="text" id='Total' name='Total' placeholder='$0.00' class='form-control form-control-sm' readonly>
                            </div>

                            <div class="form-group col-lg-8 col-md-7 col-sm-12 col-12" title="Total en letras">
                                <label for="TotLetra">Total en letras</label>
                                <input type="text" id='TotLetra' name='TotLetra' placeholder='' class='form-control form-control-sm' readonly>
                            </div>
                        </div>

                        <div class="form-row d-flex justify-content-center">
                            <div class="form-group col-xs-1" title="Guardar / Actualizar">
                                <button type="submit" class="btn btn-outline-primary btn-sm" id='btnSave'>Guardar <i class="fa-solid fa-save fa-beat"></i></button>
                            </div>
                            <div class="form-group col-xs-1" title="Finalizar venta">
                                <button type="button" class="btn btn-outline-success btn-sm" id='btnFinalizar'>Finalizar <i class="fa-solid fa-cart-arrow-down fa-beat"></i></button>
                            </div>

                            <div class="form-group col-xs-1" title="Cancelar venta">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnCancelar">Cancelar <i class="fa-solid fa-cancel"></i></button>
                            </div>

                            <div class="form-group col-xs-1" title="Imprimir nota de venta">
                                <button type="button" class="btn btn-outline-danger btn-sm" id='btnPrint'>Imprimir <i class="fa-solid fa-file-pdf fa-beat"></i></button>
                            </div>

                            <div class="form-group col-xs-1" title="Regresar">
                                <button type="button" class="btn btn-outline-info btn-sm" id="btnBack">Regresar <i class="fa-solid fa-circle-chevron-left fa-beat"></i></button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <form class='was-validated' id="Form_Mat" name="Form_Mat">
                        <div class="form-row d-flex justify-content-start">
                            <div class="form-group col-lg-12 col-md-12s col-sm-12 col-12" title="Material">
                                <label for="Id_Mat">Material <span class="text-danger">*</span></label>
                                <select name="Id_Mat" id="Id_Mat" class='form-control form-control-sm ' title='Seleccionar...' 
                                    data-live-search="true" data-size=15 required></select>
                            </div>

                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Unidad de medida">
                                <label for="UM">Unidad de Medida</label>
                                <input type="text" id='UM' name='UM' class='form-control form-control-sm' placeholder='UM' readonly>
                            </div>

                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Número de venta" hidden>
                                <label for="Costo">Costo</label>
                                <input type="number" id='Costo' name='Costo' class='form-control form-control-sm' placeholder='$0.00' readonly>
                            </div>

                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Número de venta" hidden>
                                <label for="Ganancia">Ganancia</label>
                                <input type="number" id='Ganancia' name='Ganancia' class='form-control form-control-sm' placeholder='0.00' readonly>
                            </div>

                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Número de venta">
                                <label for="Cost">Precio</label>
                                <input type="number" id='Cost' name='Cost' class='form-control form-control-sm' placeholder='$0.00' readonly>
                            </div>

                            <div class="form-group col-lg-2 col-md-2 col-sm-4 col-12" title="Número de venta">
                                <label for="Cant">Cantidad <span class="text-danger">*</span></label>
                                <input type="number" min=0 placeholder='0.00' id='Cant' name='Cant' class='form-control form-control-sm' required>
                            </div>

                            <div class="form-group col-xs-1 mt-4" title="Finalizar venta">
                                <button type="submit" class="btn btn-outline-success btn-sm mt-2" id='btnAddMat'>Agregar <i class="fa-solid fa-cart-plus fa-beat"></i></button>
                            </div>
                        </div>
                    </form>


                    <div class="table-responsive col-12 mt-5 mb-5">
                        <table class="table table-sm table-hover table-striped compact" id="tbl_Mat">
                            <thead class="text-center bg-success text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Material</th>
                                    <th>UM</th>
                                    <th>Cant</th>
                                    <th>PU</th>
                                    <th>Importe</th>
                                    <th>Status</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                
                <div class="table-responsive col-12" id='div_Ventas'>
                    <div class="alert alert-success" role="alert">
                        <div class='form-row d-felx justify-content-around text-center'>
                            <div class="form-group col-xs-1">
                                <label for="Filtro">Filtro</label>
                                <select name="Filtro" id="Filtro" data-size='6' class="form-control form-control-sm bg-transparent rounded-pill">
                                    <option value="Hoy">Hoy</option>
                                    <option value="Semana">Semana</option>
                                    <option value="Mes">Mes</option>
                                    <option value="Year">Año</option>
                                    <option value="Toso">Toso</option>
                                </select>
                            </div>

                            <div class="form-group col-xs-1">
                                <label for="Inicio">Inicio</label>
                                <input Id='Inicio' name='Inicio' type="date" class="form-control form-control-sm bg-transparent rounded-pill">
                            </div>

                            <div class="form-group col-xs-1">
                                <label for="Fin">Fin</label>
                                <input Id='Fin' name='Fin' type="date" class="form-control form-control-sm bg-transparent rounded-pill">
                            </div>

                            <div class="form-group col-xs-1">
                                <label>Vendido</label>
                                <label id="Vendido" class="form-control bg-transparent rounded-pill">$0.00</label>
                            </div>

                            <div class="form-group col-xs-1" title='Imprimir reporte de venta'>
                                <button class="btn btn-outline-danger btn-sm mt-4" id="btnRep">Imprimir <i class="fa-solid fa-file-pdf fa-beat"></i></button>
                            </div>
                        </div>
                    </div>


                    <table class="table table-sm table-hover table-striped compact" id="tbl_Venta">
                        <thead class="text-center bg-success text-white">
                            <tr>
                                <th>No</th>
                                <th>Fecha de Venta</th>
                                <th>Subtotal</th>
                                <th>Descuento</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Ver</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>


            <!--        Modal de devoluciones       -->
            <div class="modal fade" id="Dev" data-keyboard="false" tabindex="-1" aria-labelledby="DevLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-md">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-light">
                            <h5 class="modal-title" id="DevLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form class='was-validated' id='Form_Devolucion'>
                                <div class="form-row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" title="Cantidad actual">
                                        <label for="Cant_Act">Catidad ventida <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm" id="Vent" name="Id_Venta" step="any" hidden>
                                        <input type="number" class="form-control form-control-sm" id="Id" name="Id_Mat" step="any" hidden>                                        
                                        <input type="number" class="form-control form-control-sm" id="Cons_Dev" name="Cons" step="any" hidden>
                                        <input type="number" class="form-control form-control-sm" id="Cant_Act" name="Cant" placeholder='0.00' readonly>
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-12" title="Catidad a devolver">
                                        <label for="Devolucion">A devolver<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm" id="Devolucion" placeholder='0.00' name="Devolucion" min=0 step="any" required>
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
<script src="../js/ventas.js"></script>

</body>
</html>