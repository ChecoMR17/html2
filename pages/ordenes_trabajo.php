<?php
session_start();
if (isset($_SESSION['Id_Empleado'])) {
    include "../global/Header.php"; ?>
    <title>Ordenes de trabajo</title>
    </head>

    <body>
        <?php include "../global/menu.php"; ?>
        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-start d-flex row was-validated" id="Form_Ordenes_Trabajo">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                <h1 class="alert alert-primary rounded-pill" role="alert">Ordenes de trabajo <i class="fa-solid fa-person-digging fa-beat"></i></h1>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-1 col-xl-1">
                <label for="Id">Folio</label>
                <input type="text" class="form-control form-control-sm" id="Id" name="Id" readonly>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"></div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                <label for="Cliente">Cliente </label>
                <select name="Cliente" id="Cliente" class="form-control form-control-sm " onchange="Buscar_Obras()" data-live-search="true" title="----------------------------------------------" required></select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                <label for="Obras">Obras </label>
                <select name="Obras" id="Obras" class="form-control form-control-sm " title="----------------------------------------------" required></select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                <label for="Contactos">Contactos</label>
                <select name="Contactos" id="Contactos" class="form-control form-control-sm " title="----------------------------------------------" required></select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                <label for="Clasificacion">Clasificación</label>
                <select name="Clasificacion" id="Clasificacion" class="form-control form-control-sm  selectpicker show-tick" title="----------------------------------------------" required></select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                <label for="Prioridad">Prioridad</label>
                <select name="Prioridad" id="Prioridad" class="form-control form-control-sm  selectpicker show-tick" title="----------------------------------------------" required>
                    <option class="text-danger" value="Alto">Alto</option>
                    <option class="text-warning" value="Mediano">Mediano</option>
                    <option class="text-success" value="Bajo">Bajo</option>
                </select>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <label for="Proyecto">Proyecto</label>
                <input type="text" class="form-control form-control-sm " id="Proyecto" name="Proyecto" maxlength="200" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                <label for="Fecha_Inicio">Fecha de inicio</label>
                <input type="date" class="form-control form-control-sm " id="Fecha_Inicio" name="Fecha_Inicio" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                <label for="Fecha_Final">Fecha de final</label>
                <input type="date" class="form-control form-control-sm " id="Fecha_Final" name="Fecha_Final" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 justify-content-center d-flex mt-auto row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <label for="Fecha_Inicio">Status</label>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="Opciones_Status" id="S_Ejecucion" value="U">
                        <label class="form-check-label text-success" for="S_Ejecucion">En Ejecución</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="Opciones_Status" id="S_Concluido" value="C" disabled>
                        <label class="form-check-label text-secondary" for="S_Concluido">Concluido</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="Opciones_Status" id="S_Cancelado" value="B" disabled>
                        <label class="form-check-label text-danger" for="S_Cancelado">Cancelado</label>
                    </div>

                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <label for="Observaciones">Observaciones</label>
                <textarea name="Observaciones" id="Observaciones" rows="5" class="form-control " maxlength="5000"></textarea>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                <button type="reset" class="btn btn-outline-secondary btn-sm" id="" onclick="Limpiar_F_OT()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-end d-flex mt-3">
                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#Guardar_Clasificaciones" onclick="Mostrar_Tbl_Clasificaciones()">Agregar clasificaciones <i class="fa-regular fa-file-lines fa-beat"></i></button>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                <table class="table table-hover table-sm" id="Tbl_Ordenes_Trabajo">
                    <thead>
                        <tr class="text-center">
                            <th rowspan="2">Folio</th>
                            <th rowspan="2">Cliente</th>
                            <th colspan="4">Datos de obra</th>
                            <th rowspan="2">Fechas</th>
                            <th rowspan="2">Detalles</th>
                            <th rowspan="2">Status</th>
                            <th rowspan="2">--------</th>
                        </tr>
                        <tr>
                            <th>Obra</th>
                            <th>Proyecto</th>
                            <th>Contacto</th>
                            <th>Prioridad</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Modal -->
        <div class="modal fade" id="Guardar_Clasificaciones" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg modal-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Clasificaciones</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Limpiar_Formulario_Calcificaciones()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">

                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex row was-validated" id="Form_Clasificaciones">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2" hidden>
                                <label for="Id_Clasificacion">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_Clasificacion" name="Id_Clasificacion" readonly>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="Nombre_Clasificacion">Nombre </label>
                                <input type="text" class="form-control form-control-sm " id="Nombre_Clasificacion" name="Nombre_Clasificacion" maxlength="150" required>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Limpiar_Formulario_Calcificaciones()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_Clasificaciones">
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
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="Guardar_Actividades" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Lista de actividades</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Btn_Limpiar_A()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2" hidden>
                            <label for="OT">Id</label>
                            <input type="text" class="form-control form-control-sm" id="OT" name="OT" readonly>
                        </div>

                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-start d-flex row was-validated" id="Form_Actividades">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2" hidden>
                                <label for="Id_Actividad">Id</label>
                                <input type="text" class="form-control form-control-sm" id="Id_Actividad" name="Id_Actividad" readonly>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-3">
                                <label for="Fecha_Actividad">Fecha </label>
                                <input type="date" class="form-control form-control-sm " id="Fecha_Actividad" name="Fecha_Actividad" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-9">
                                <label for="Nombre_Actividad">Actividad </label>
                                <input type="text" class="form-control form-control-sm " id="Nombre_Actividad" name="Nombre_Actividad" maxlength="200" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="Descripcion_Actividad">Descripción </label>
                                <textarea name="Descripcion_Actividad" id="Descripcion_Actividad" rows="5" class="form-control " maxlength="5000"></textarea>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2" id="Btn_GA">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Btn_Limpiar_A()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 table-responsive mt-4">
                                <table class="table table-hover table-sm" id="Tbl_Actividades">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
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
        <div class="modal fade" id="Guardar_Documentos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl modal-center">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="">Subir documentos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="Limpiar_Form_Archivos()"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2" hidden>
                            <label for="OT_D">Id</label>
                            <input type="text" class="form-control form-control-sm" id="OT_D" name="OT_D" readonly>
                        </div>

                        <form action="" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-start d-flex row was-validated" id="Form_Documentos">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <label for="Nombre_documento">Tipo de documento </label>
                                <input type="text" class="form-control form-control-sm " id="Nombre_documento" name="Nombre_documento" maxlength="100" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <label for="Documento">Archivo </label>
                                <input type="file" class="form-control form-control-sm " id="Documento" name="Documento" accept=".pdf,.png,.jpg" required>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <label for="Descripcion_Documento">Observaciones </label>
                                <textarea name="Descripcion_Documento" id="Descripcion_Documento" rows="5" class="form-control " maxlength="1000"></textarea>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3">
                                <button type="submit" class="btn btn-outline-success btn-sm mr-2" id="Btn_GA">Guardar <i class="fa-solid fa-floppy-disk fa-beat"></i></button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="Limpiar_Form_Archivos()">Limpiar <i class="fa-solid fa-eraser fa-beat"></i></button>
                            </div>

                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 justify-content-center d-flex mt-3" id="Id_Mostrar_Documentos"></div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <?php include "../global/Fooder.php"; ?>
        <script src="../js/ot.js"></script>
    </body>

    </html>
<?php
} else {
    header("location:../index.php");
}
?>