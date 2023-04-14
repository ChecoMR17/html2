<?php
session_start();
if (isset($_SESSION['Id_Empleado'])) {
    include "../global/Header.php"; ?>
    <title>Proveedores</title>
    </head>

    <body>
        <?php include "../global/menu.php"; ?>
        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-left d-flex row was-validated" id="Form_Proveedores" name="Form_Proveedores">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                <h1 class="alert alert-primary rounded-pill" role="alert">Alta de Proveedores <i class="fa-solid fa-people-carry-box fa-beat"></i></h1>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1" hidden>
                <label for="Id">Id</label>
                <input type="text" class="form-control form-control-sm" id="Id" name="Id" readonly>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1">
                <label for="T_Persona">Tipo de persona </label>
                <select name="T_Persona" id="T_Persona" class="form-control form-control-sm selectpicker " onchange="Validar_T_Proveedor()" title="---------------------------" required>
                    <option class="text-dark" value="Persona física">Persona física</option>
                    <option class="text-dark" value="Persona moral">Persona moral</option>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="Nombre_Proveedor">Nombre </label>
                <input type="text" class="form-control form-control-sm " id="Nombre_Proveedor" name="Nombre_Proveedor" maxlength="50" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 PF" hidden>
                <label for="Apellido_p">Apellido paterno </label>
                <input type="text" class="form-control form-control-sm " id="Apellido_p" name="Apellido_p" maxlength="50">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 PF" hidden>
                <label for="Apellido_M">Apellido materno </label>
                <input type="text" class="form-control form-control-sm " id="Apellido_M" name="Apellido_M" maxlength="50">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="RFC">FRC </label>
                <input type="text" class="form-control form-control-sm " id="RFC" name="RFC" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="C_Pago">Condiciones de pago</label>
                <select name="C_Pago" id="C_Pago" class="form-control form-control-sm selectpicker" title="--------------------">
                    <option class="text-dark" value="CONTADO">CONTADO</option>
                    <option class="text-dark" value="CRÉDITO">CRÉDITO</option>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="Giro">Giro </label>
                <input type="text" class="form-control form-control-sm " id="Giro" name="Giro" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <label for="Observaciones">Observaciones</label>
                <textarea name="Observaciones" id="Observaciones" rows="3" class="form-control form-control-sm"></textarea>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                <button type="reset" class="btn btn-outline-secondary btn-sm" id="Btn_Limpiar_Prov">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                <table class="table table-hover table-sm" id="Tbl_Proveedores">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Giro</th>
                            <th>RFC</th>
                            <th>Pago</th>
                            <th>Descripción</th>
                            <th>Status</th>
                            <th>--------</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Modal -->
        <div class="modal fade" id="Agregar_Fam" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Agregar Familias</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick=""><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3" hidden>
                            <label for="Id_Prov">Id</label>
                            <input type="text" class="form-control form-control-sm" id="Id_Prov" name="Id_Prov" readonly>
                        </div>
                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-left d-flex row was-validated" id="Form_Familias_Prov" name="Form_Familias_Prov">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3" hidden>
                                <label for="Id_Fam">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_Fam" name="Id_Fam" readonly>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="Familias">Familias</label>
                                <select name="Familias" id="Familias" class="form-control form-control-sm selectpicker" data-live-search="true" title="--------------------"></select>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="reset" class="btn btn-outline-secondary btn-sm" id="Btn_Limpiar_Fam_Prov">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-end d-flex mt-3">
                                <button type="button" class="btn btn-warning btn-sm" id="" onclick="Mostrar_Lista_Familias()" data-toggle="modal" data-target="#Agregar_Familias">Agregar mas familias <i class="fa-solid fa-grip-lines fa-beat"></i></button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_Fam_Prov">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>--------</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="Agregar_Familias" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Alta de familias</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Limpiar()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-left d-flex row was-validated" id="Form_Familias" name="Form_Familias">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3" hidden>
                                <label for="Id_Proveedores">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_Proveedores" name="Id_Proveedores" readonly>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                <label for="Nombre_Proveedores">Nombre</label>
                                <input type="text" class="form-control form-control-sm" id="Nombre_Proveedores" name="Nombre_Proveedores" maxlength="50" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Ganancia">Ganancia</label>
                                <input type="number" step="0.001" class="form-control form-control-sm" id="Ganancia" name="Ganancia" value="30" min="1" max="100" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="reset" class="btn btn-outline-secondary btn-sm" id="Btn_Limpiar_Pr">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_Familias">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>Ganancia</th>
                                            <th>--------</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="Agregar_Sucursales" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Agregar sucursales</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Limpiar_FS()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3" hidden>
                            <label for="Id_PS">Id</label>
                            <input type="text" class="form-control form-control-sm" id="Id_PS" name="Id_PS" readonly>
                        </div>
                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-left d-flex row was-validated" id="Form_Sucursales" name="Form_Sucursales">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3" hidden>
                                <label for="Id_Sucursal">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_Sucursal" name="Id_Sucursal" readonly>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Nombre_Sucursal">Nombre de la sucursal</label>
                                <input type="text" class="form-control form-control-sm" id="Nombre_Sucursal" name="Nombre_Sucursal" maxlength="100" required>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Nombre_contacto">Nombre del primer contacto</label>
                                <input type="text" class="form-control form-control-sm" id="Nombre_contacto" name="Nombre_contacto" maxlength="100" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Nombre_Contacto2">Nombre del segundo contacto</label>
                                <input type="text" class="form-control form-control-sm" id="Nombre_Contacto2" name="Nombre_Contacto2" maxlength="100">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Calle_Sucursal">Calle</label>
                                <input type="text" class="form-control form-control-sm" id="Calle_Sucursal" name="Calle_Sucursal" maxlength="100" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                <label for="Numero_Exterior">N° Exterior</label>
                                <input type="number" step="0.001" class="form-control form-control-sm" id="Numero_Exterior" name="Numero_Exterior" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                <label for="Numero_Interior">N° Interior</label>
                                <input type="number" step="0.001" class="form-control form-control-sm" id="Numero_Interior" name="Numero_Interior">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Colonia">Colonia</label>
                                <input type="text" class="form-control form-control-sm" id="Colonia" name="Colonia" maxlength="100" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Codigo_Postal">CP</label>
                                <input type="number" step="0.001" class="form-control form-control-sm" id="Codigo_Postal" name="Codigo_Postal" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Estado">Estado</label>
                                <select name="Estado" id="Estado" class="form-control form-control-sm" title="--------------------------" onchange="Mostrar_Municipios()" data-live-search="true" required></select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Municipio">Municipio</label>
                                <select name="Municipio" id="Municipio" class="form-control form-control-sm" title="--------------------------" data-live-search="true" required></select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                <label for="Celular">Celular </label>
                                <input type="tel" pattern="[0-9]{10}" title="El numero telefónico debe tener 10 dígitos" class="form-control form-control-sm " id="Celular" name="Celular" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                <label for="Telefono">Teléfono </label>
                                <input type="tel" pattern="[0-9]{10}" title="El numero telefónico debe tener 10 dígitos" class="form-control form-control-sm " id="Telefono" name="Telefono">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Correo">Correo </label>
                                <input type="email" class="form-control form-control-sm " id="Correo" name="Correo" maxlength="100">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Correo_P">Correo personal</label>
                                <input type="email" class="form-control form-control-sm " id="Correo_P" name="Correo_P" maxlength="100">
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Limpiar_FS()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_Sucursales">
                                    <thead>
                                        <tr>
                                            <th>Sucursal</th>
                                            <th>Nombre del contacto</th>
                                            <th>Dirección</th>
                                            <th>Teléfono</th>
                                            <th>Correos</th>
                                            <th>--------</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="Agregar_Bancos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Agregar datos bancarios</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Limpiar_DB()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3" hidden>
                            <label for="Id_PB">Id</label>
                            <input type="text" class="form-control form-control-sm" id="Id_PB" name="Id_PB" readonly>
                        </div>
                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-left d-flex row was-validated" id="Form_Bancos" name="Form_Bancos">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3" hidden>
                                <label for="Id_DBancarios">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_DBancarios" name="Id_DBancarios" readonly>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Banco">Banco</label>
                                <select name="Banco" id="Banco" class="form-control form-control-sm" title="--------------------------" data-live-search="true" required></select>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Sucursal_Banco">Sucursal</label>
                                <input type="number" class="form-control form-control-sm" id="Sucursal_Banco" name="Sucursal_Banco" maxlength="50">
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Cuenta_Banco">Cuenta</label>
                                <input type="number" class="form-control form-control-sm" id="Cuenta_Banco" name="Cuenta_Banco" maxlength="50" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Clave_Banco">Clave</label>
                                <input type="number" class="form-control form-control-sm" id="Clave_Banco" name="Clave_Banco" maxlength="50" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Referencia">CIE/Referencia</label>
                                <input type="number" class="form-control form-control-sm" id="Referencia" name="Referencia" maxlength="50">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Limpiar_DB()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-end d-flex mt-3">
                                <button type="button" class="btn btn-warning btn-sm" id="" onclick="Buscar_Lista_Bancos()" data-toggle="modal" data-target="#Form_Agregar_Bancos">Agregar bancos <i class="fa-solid fa-building-columns fa-bounce"></i></button>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_DBancarios">
                                    <thead>
                                        <tr>
                                            <th>Banco</th>
                                            <th>Sucursal</th>
                                            <th>Cuenta</th>
                                            <th>Clave</th>
                                            <th>CIE/Referencia</th>
                                            <th>Status</th>
                                            <th>--------</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="Form_Agregar_Bancos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Agregar bancos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Limpiar_FB()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-left d-flex row was-validated" id="Form_A_Bancos" name="Form_A_Bancos">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3" hidden>
                                <label for="Id_Bancos">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_Bancos" name="Id_Bancos" readonly>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="Nombre_Banco">Nombre</label>
                                <input type="text" class="form-control form-control-sm" id="Nombre_Banco" name="Nombre_Banco" maxlength="50">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Limpiar_FB()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_Lista_Bancos">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>--------</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>



        <?php include "../global/Fooder.php"; ?>
        <script src="../js/proveedores.js"></script>
    </body>

    </html>
<?php
} else {
    header("location:../index.php");
}
?>