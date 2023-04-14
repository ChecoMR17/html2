<?php
session_start();
if (isset($_SESSION['Id_Empleado'])) {
    include "../global/Header.php"; ?>
    <title>Clientes</title>
    </head>

    <body>
        <?php include "../global/menu.php"; ?>
        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-left d-flex row was-validated" id="Agregar_Clientes" name="Agregar_Clientes">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                <h1 class="alert alert-primary rounded-pill" role="alert">Alta de clientes</h1>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1" hidden>
                <label for="Id_Cliente">Id</label>
                <input type="text" class="form-control form-control-sm" id="Id_Cliente" name="Id_Cliente" readonly>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1">
                <label for="T_Persona">Tipo de persona </label>
                <select name="T_Persona" id="T_Persona" class="form-control form-control-sm selectpicker " onchange="Validar_T_Cliente()" title="---------------------------" required>
                    <option class="text-dark" value="Persona física">Persona física</option>
                    <option class="text-dark" value="Persona moral">Persona moral</option>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="Nombre_Cliente" id="Nombre_T">Nombre </label>
                <input type="text" class="form-control form-control-sm " id="Nombre_Cliente" name="Nombre_Cliente" maxlength="50" required>
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
                <label for="RFC">RFC </label>
                <input type="text" class="form-control form-control-sm " id="RFC" name="RFC" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="Correo_C">Correo corporativo</label>
                <input type="email" class="form-control form-control-sm " id="Correo_C" name="Correo_C" maxlength="100">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="Correo_P">Correo personal</label>
                <input type="email" class="form-control form-control-sm " id="Correo_P" name="Correo_P" maxlength="100">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-1">
                <label for="Celular">Celular </label>
                <input type="tel" pattern="[0-9]{10}" title="El numero telefónico debe tener 10 dígitos" class="form-control form-control-sm " id="Celular" name="Celular" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-1">
                <label for="Telefono">Teléfono </label>
                <input type="tel" pattern="[0-9]{10}" title="El numero telefónico debe tener 10 dígitos" class="form-control form-control-sm " id="Telefono" name="Telefono">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="Estado">Estado </label>
                <select name="Estado" id="Estado" class="form-control form-control-sm " onchange="Buscar_Municipios()" title="-------------------------" data-live-search="true" required></select>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="Municipio">Municipio </label>
                <select name="Municipio" id="Municipio" class="form-control form-control-sm " title="-------------------------" data-live-search="true" required></select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                <label for="Colonia">Colonia </label>
                <input type="text" class="form-control form-control-sm " id="Colonia" name="Colonia" required>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                <label for="Calle">Calle </label>
                <input type="text" class="form-control form-control-sm " id="Calle" name="Calle" maxlength="100" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1">
                <label for="N_Exterior"> N° Exterior </label>
                <input type="number" step="0.001" class="form-control form-control-sm " id="N_Exterior" name="N_Exterior" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1">
                <label for="N_Interior"> N° Interior</label>
                <input type="number" step="0.001" class="form-control form-control-sm " id="N_Interior" name="N_Interior">
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1">
                <label for="CP"> Código postal </label>
                <input type="number" class="form-control form-control-sm " id="CP" name="CP" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <label for="Observaciones"> Observaciones</label>
                <textarea name="Observaciones" id="Observaciones" rows="3" class="form-control " maxlength="500"></textarea>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                <button type="reset" class="btn btn-outline-secondary btn-sm" id="Btn_Limpiar_C">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                <table class="table table-hover table-sm" id="Tbl_Clientes">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>T. Persona</th>
                            <th>RFC</th>
                            <th>Correos</th>
                            <th>Teléfonos</th>
                            <th>Direction</th>
                            <th>Observaciones</th>
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
        <div class="modal fade" id="Guardar_contactos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Alta de contactos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Btn_Limpiar_C_C()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2" hidden>
                            <label for="Id_Cliente_C">Id cliente</label>
                            <input type="text" class="form-control form-control-sm" id="Id_Cliente_C" name="Id_Cliente_C" readonly>
                        </div>
                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex row was-validated" id="Form_Contacto">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2" hidden>
                                <label for="Id_contacto">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_contacto" name="Id_contacto" readonly>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Nombre_Contacto">Nombre </label>
                                <input type="text" class="form-control form-control-sm " id="Nombre_Contacto" name="Nombre_Contacto" maxlength="150" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Apellido_P_Contacto">Apellido paterno </label>
                                <input type="text" class="form-control form-control-sm " id="Apellido_P_Contacto" name="Apellido_P_Contacto" maxlength="150" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Apellido_M_Contacto">Apellido materno </label>
                                <input type="text" class="form-control form-control-sm " id="Apellido_M_Contacto" name="Apellido_M_Contacto" maxlength="150" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                <label for="Celular_Contacto">Celular </label>
                                <input type="tel" pattern="[0-9]{10}" title="El numero telefónico debe tener 10 dígitos" class="form-control form-control-sm " id="Celular_Contacto" name="Celular_Contacto" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2">
                                <label for="Telefono_Contacto">Teléfono</label>
                                <input type="tel" pattern="[0-9]{10}" title="El numero telefónico debe tener 10 dígitos" class="form-control form-control-sm " id="Telefono_Contacto" name="Telefono_Contacto">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Correo_C_C">Correo corporativo</label>
                                <input type="email" class="form-control form-control-sm " id="Correo_C_C" name="Correo_C_C" maxlength="100" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <label for="Correo_C_P">Correo personal</label>
                                <input type="email" class="form-control form-control-sm " id="Correo_C_P" name="Correo_C_P" maxlength="100">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="Observaciones_Contactos">Observaciones</label>
                                <textarea name="Observaciones_Contactos" id="Observaciones_Contactos" rows="3" class="form-control " maxlength="500"></textarea>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="reset" class="btn btn-outline-secondary btn-sm" onclick="Btn_Limpiar_C_C()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_Contactos">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Teléfonos</th>
                                            <th>Correos</th>
                                            <th>Observaciones</th>
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
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="Guardar_Obras" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Alta de obras</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Limpiar_Formulario_O()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2" hidden>
                            <label for="Id_Cliente_O">Id cliente</label>
                            <input type="text" class="form-control form-control-sm" id="Id_Cliente_O" name="Id_Cliente_O" readonly>
                        </div>
                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-start d-flex row was-validated" id="Form_Obras">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2" hidden>
                                <label for="Id_Obra">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_Obra" name="Id_Obra" readonly>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                <label for="Descripcion_Obras">Obra </label>
                                <input type="text" class="form-control form-control-sm " id="Descripcion_Obras" name="Descripcion_Obras" maxlength="500" required>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Estado_O"> Estado</label>
                                <select name="Estado_O" id="Estado_O" class="form-control form-control-sm " onchange="Buscar_Municipios_O()" title="-------------------------" data-live-search="true" required></select>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Municipio_O"> Municipio</label>
                                <select name="Municipio_O" id="Municipio_O" class="form-control form-control-sm " title="-------------------------" data-live-search="true" required></select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Colonia_O"> Colonia</label>
                                <input type="text" class="form-control form-control-sm " id="Colonia_O" name="Colonia_O" maxlength="100" required>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="Calle_O"> Calle</label>
                                <input type="text" class="form-control form-control-sm " id="Calle_O" name="Calle_O" maxlength="100" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="N_Exterior_O"> N° Exterior </label>
                                <input type="number" step="0.001" class="form-control form-control-sm " id="N_Exterior_O" name="N_Exterior_O" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="N_Interior_O"> N° Interior</label>
                                <input type="number" step="0.001" class="form-control form-control-sm " id="N_Interior_O" name="N_Interior_O">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <label for="CP_O"> Código postal </label>
                                <input type="number" class="form-control form-control-sm " id="CP_O" name="CP_O" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="Observaciones_O"> Observaciones</label>
                                <textarea name="Observaciones_O" id="Observaciones_O" rows="3" class="form-control " maxlength="500"></textarea>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="reset" class="btn btn-outline-secondary btn-sm" onclick="Limpiar_Formulario_O()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>



                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_Obra">
                                    <thead>
                                        <tr>
                                            <th>Obra</th>
                                            <th>Dirección</th>
                                            <th>Observaciones</th>
                                            <th>--------</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <?php include "../global/Fooder.php"; ?>
        <script src="../js/clientes.js"></script>
    </body>

    </html>
<?php
} else {
    header("location:../index.php");
}
?>