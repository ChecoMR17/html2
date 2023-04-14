let init = () => {
    $('#Form_Ventas')[0].reset();
    $('#Form_Ventas').on('submit', function(e) { save_Venta(e) } )
    $('#Form_Mat').on('submit', function(e) { save_Mat(e) } )
    $('#ModalPU').on('submit', function(e) { editPU(e) } )
    $('#Form_Devolucion').on('submit', function(e) {devolver(e)})
    select_Mat();
    listar_Ventas();

    $('#Inicio').val('')
    $('#Fin').val('')
    vendido('Hoy');
}


// Funcion para ir arriba
$(".ir-arriba").click(function () {
    $("body, html").animate( { scrollTop: "0px", }, 300 );
});

$(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
        $(".ir-arriba").slideDown(300);
    } else {
        $(".ir-arriba").slideUp(300);
    }
});


$('#btnAdd').click(function(){
    $('#div_Ventas').attr('hidden', true);
    $('#div_Form').attr('hidden', false);

    $('#btnSave').attr('disabled', false)
    $('#btnFinalizar').attr('disabled', false)
    $('#btnCancelar').attr('disabled', false)
    $('#btnPrint').attr('disabled', false)
    $('#btnAddMat').attr('disabled', false)

    // Consultamos la siguiente venta
    $.post("../Archivos/Ventas/ventas.php?op=Id_Venta"
        , data => {
            $('#Id_Venta').val(data)

            mat_Venta(data);
/*
            $.post("../Archivos/Ventas/ventas.php?op=valVentas"
                , { data }, data => {
                    if (data > 0) {
                    } else {
                        
                    }
                }
            )*/
        }
    );

    $('#btnAdd').attr('hidden', true)
})

$('#btnBack').click(function(){
    tbl_Venta.ajax.reload();
    $('#div_Ventas').attr('hidden', false);
    $('#div_Form').attr('hidden', true);
    $('#Form_Ventas')[0].reset();
    $('#Form_Mat')[0].reset();
    $('#Id_Mat').selectpicker('refresh')
    $('#tbl_Mat body').html('')
    $('#Imp_Desc').html('')
    $('#btnAdd').attr('hidden', false)
})

let tbl_Venta = "";
let tbl_Mat ="";

// Listado de materiales
let listar_Ventas = () => {
    tbl_Venta=$('#tbl_Venta').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
        "ajax":
                {
                    url: '../Archivos/Ventas/ventas.php?op=listar_Ventas',
                    type : "post",
                    dataType : "json"
                },
        "bDestroy": true,
        "iDisplayLength": 1000,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}

// Caso para guardar y/o actualizar una venta
let save_Venta = (e) =>{
    e.preventDefault();
    let data = new FormData($("#Form_Ventas")[0]);
    let Id_Venta = $('#Id_Venta').val();

    $.ajax({
        type: "post",
        url: "../Archivos/Ventas/ventas.php?op=save_Venta",
        data: data,
        processData: false,
        contentType: false,
        success: resp => {
            if (resp.includes('correctamente')){
                swal.fire(resp, "", "success")
            } else {
                swal.fire(resp, "", "error")
            }
            updateTot(Id_Venta);
        }
    });
}


// Función par amostar información de una venta
let verVenta = (Id_Venta) => {
    $('#div_Ventas').attr('hidden', true);
    $('#div_Form').attr('hidden', false);

    $.post("../Archivos/Ventas/ventas.php?op=verVenta"
        , { Id_Venta } , data => {
            $("#Id_Venta").val(Id_Venta)
            $("#Cliente").val(data.Cliente)
            $("#Tel").val(data.Tel)
            $("#Correo").val(data.Correo)
            $("#Direccion").val(data.Direccion)
            $("#Obs").val(data.Obs)
            $("#Descuento").val(data.Descuento)
            $("#Total").val(data.Total)
            $("#TotLetra").val(data.TotLetra)

            if (data.Status !='A'){
                $('#btnSave').attr('disabled', true)
                $('#btnFinalizar').attr('disabled', true)
                $('#btnCancelar').attr('disabled', true)
                $('#btnAddMat').attr('disabled', true)
            } else {
                $('#btnSave').attr('disabled', false)
                $('#btnFinalizar').attr('disabled', false)
                $('#btnCancelar').attr('disabled', false)
                $('#btnAddMat').attr('disabled', false)
                $('#btnPrint').attr('disabled', false)
            }

            data.Status =='B' ? $('#btnPrint').attr('disabled', true) : $('#btnPrint').attr('disabled', false);
        }, 'json'
    )

    mat_Venta(Id_Venta);
}


// Listado de materilaes de venta
let mat_Venta = (Id_Venta) => {
    tbl_Mat=$('#tbl_Mat').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
        "ajax":
                {
                    url: '../Archivos/Ventas/ventas.php?op=mat_Venta',
                    type : "post",
                    data : { Id_Venta },
                    dataType : "json",
                },
        "bDestroy": true,
        "iDisplayLength": 1000,//Paginación
        "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
    }).DataTable();
}


$('#profile-tab').click(function () { 
    if (!$.fn.DataTable.isDataTable("#tbl_Materiales")) {
        mat_Inv();
    } else {
        tbl_Materiales.ajax.reload()
    }
})


// Listado de materilaes de Inventario
let tbl_Materiales;

let mat_Inv = () => {
    setTimeout( () => {
        tbl_Materiales=$('#tbl_Materiales').dataTable({
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
            "ajax":
                    {
                        url: '../Archivos/Ventas/ventas.php?op=listar_Mat',
                        type : "post",
                        dataType : "json",
                    },
            "bDestroy": true,
            "iDisplayLength": 100,//Paginación
            "order": [[ 1, "asc" ]]//Ordenar (columna,orden)
        }).DataTable();
    }, 300)  
}


// Listado de Materiales
let select_Mat = () => {
    $.post("../Archivos/Ventas/ventas.php?op=select_Mat"
        , resp => {
            $("#Id_Mat").html(resp)
            $("#Id_Mat").selectpicker();
            $("#Id_Mat").selectpicker('refresh');
        }, 'html'
    )
}


let verMat = (Id_Mat) => {
    $('#home-tab').click()
    $('#Id_Mat').val(Id_Mat)
    $('#Id_Mat').change()
}


$('#Id_Mat').change(function () { 
    let Id_Mat = this.value;
    $.post("../Archivos/Ventas/ventas.php?op=ver_Mat"
        , { Id_Mat }, data => {
            $('#Ganancia').val(data.Ganancia)
            $('#Gan').val(data.Ganancia)
            $('#Costo').val(data.Costo)
            $('#Cost').val(data.Cost)
            $('#PU').val(data.Cost)
            $('#UM').val(data.UM)
            $('#Cant').attr('max', data.Stock)
        }, 'json'
    )
});


// Función para agregar y/o actualizar un material
let save_Mat = (e) => {
    e.preventDefault();

    let Id_Venta = $('#Id_Venta').val();

    // Primero validamos que la informacion de la venta este guardada
    $.post("../Archivos/Ventas/ventas.php?op=valVenta"
        , { Id_Venta }, data => {
            console.log(data)
            if (data == 0 || data == '') {
                subir();
                swal.fire("Primero debe guardar la información de la venta", "", "warning")
            } else {
                let data = new FormData($("#Form_Mat")[0])
                data.append('Id_Venta', Id_Venta)

                $.ajax({
                    type: "post",
                    url: "../Archivos/Ventas/ventas.php?op=save_Mat",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: resp => {
                        updateTot(Id_Venta);
                        if (resp.includes('correctamente')){
                            swal.fire(resp, "", "success")
                            select_Mat();
                            $('#Form_Mat')[0].reset()
                        } else {
                            swal.fire(resp, "", "error")
                        }

                        if (!$.fn.DataTable.isDataTable("#tbl_Mat")) {
                            mat_Venta(Id_Venta);
                        } else {
                            tbl_Mat.ajax.reload();
                        }
                    }
                });
            }
        }, 'json'
    )
}


// Función para eliminar materiales de la venta
let delMat = (Id_Venta, Cons, Id_Mat, Cant) => {
    Swal.fire({
        title: 'Se quitará este material!',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        cancelButtonText: "Cancelar",
        confirmButtonText: 'Aceptar',
      }).then((result) => {
        if (result.isConfirmed) {
            $.post("../Archivos/Ventas/ventas.php?op=delMat"
                , { Id_Venta, Id_Mat, Cons, Cant }
                , resp => {
                    tbl_Mat.ajax.reload();
                    updateTot(Id_Venta);
                    if (resp.includes('correctamente')){
                        swal.fire(resp, "", "success")
                        select_Mat();
                    } else {
                        swal.fire(resp, "", "error")
                    }
                }
            )
        }
    })
}


// Función para finalizar una venta
$('#btnFinalizar').click(function(){
    let Id_Venta = $('#Id_Venta').val();

    if ($('#Total').val() == '' || $('#Total').val() == 0){
        swal.fire("Aun no se han agregado materiales a la venta!", "", "warning")
    }else {
        Swal.fire({
            title: 'Se finalizará esta venta!',
            text: "No se podrán agragar más atículos a la venta!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            cancelButtonText: "Cancelar",
            confirmButtonText: 'Continuar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("../Archivos/Ventas/ventas.php?op=finalizar"
                    , { Id_Venta: Id_Venta } , resp => {
                        verVenta(Id_Venta);
                        updateTot(Id_Venta);
                        vendido('Hoy');
                        if (resp.includes('correctamente')){
                            swal.fire(resp, "", "success")
                        } else {
                            swal.fire(resp, "", "error")
                        }
                    }
                )
            }
        })
    }
})


// Función para cancelar una venta
$('#btnCancelar').click(function(){
    let Id_Venta = $('#Id_Venta').val()
    Swal.fire({
        title: 'Se Cancelar esta venta!',
        text: "Se eliminarán los materiales agregados en esta venta!\nEste proceso es irreversible!",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonColor: '#3085d6',
        cancelButtonText: "Cancelar",
        confirmButtonText: 'Continuar',
      }).then((result) => {
        if (result.isConfirmed) {
            $.post("../Archivos/Ventas/ventas.php?op=cancelar"
                , { Id_Venta } , resp => {
                    verVenta(Id_Venta);
                    updateTot(Id_Venta);
                    if (resp.includes('correctamente')){
                        swal.fire(resp, "", "success")
                        select_Mat();
                    } else {
                        swal.fire(resp, "", "error")
                    }
                }
            )
        }
    })
})

// Función para actualizzar el total
let updateTot = (Id_Venta) => {
    $.post("../Archivos/Ventas/ventas.php?op=updateTot"
        , { Id_Venta }, data => {
            $('#Total').val(data.Total)
            $('#TotLetra').val(data.TotLetra)
        }, 'json'
    )
}


$('#btnPrint').click(function(){
    impVenta(Id_Venta.value)
})


// Función par imprimir la nota de venta
let impVenta = (Id_Venta) => {
    window.open("../Archivos/Ventas/pdf_venta.php?Id_Venta="+btoa(Id_Venta), "blank")
}

// Calculamos el descuento
$('#Descuento').keyup(function () {
    let Descuento = parseFloat(this.value);
    let Total = parseFloat($('#Total').val())
    descuento(Descuento, Total)
});


let descuento = (Descuento, Total) => {
    let Imp_Desc = Total * (Descuento / 100)
    if (Imp_Desc != isNaN){
        $('#Imp_Desc').html(Imp_Desc.toLocaleString('en-MX'))
    }
}


// Función par adevolver articulos
let devMat = (Id_Venta, Cons, Id_Mat, Cant) => {
    $('#Vent').val(Id_Venta)
    $('#Id').val(Id_Mat)
    $('#Cant_Act').val(Cant)
    $('#Cons_Dev').val(Cons)
    $('#Devolucion').attr('max', Cant)
    $.post("../Archivos/Ventas/ventas.php?op=ver_Mat"
        , { Id_Mat }, data => {
            $('#DevLabel').html('<i class="fa-solid fa-screwdriver-wrench"></i> ' + data.Desc_Mat)
        }, 'json'
    )    
}


let devolver = (e) => {
    e.preventDefault()

    let data = new FormData($('#Form_Devolucion')[0])

    $.ajax({
        type: "post",
        url: "../Archivos/Ventas/ventas.php?op=devMat",
        data: data,
        contentType: false,
        processData: false,
        success: resp => {
            tbl_Mat.ajax.reload();
            updateTot(Id_Venta.value);
            if (resp.includes('correctamente')){
                swal.fire(resp, "", "success")
                select_Mat();
                $('#Form_Devolucion')[0].reset()
                $('#Dev').modal('hide')
            } else {
                swal.fire(resp, "", "error")
            }
        }
    });
}


$('#Filtro').change(function () { 
    vendido(this.value)
    $('#Inicio').val("") ;
    $('#Fin').val("") ;
});


$('#Inicio').change(function () {
    let Fin = $('#Fin').val()

    if (Fin != ""){
        if(this.value <= Fin) {
            vendido('', this.value, Fin)
            $('#Filtro').val("") ;
        } else {
            $('#Fin').val("");
        }
    }    
});


$('#Fin').change(function () { 
    let Inicio = $('#Inicio').val()

    if(Inicio != ""){
        if (this.value >= Inicio) {
            vendido('', Inicio, this.value)  
            $('#Filtro').val("") ;
        } else {
            $('#Inicio').val("") ;
        } 
    }
});


// Filtro de ventas
let vendido = (Filtro, Inicio, Fin) => {
    $.post('../Archivos/Ventas/ventas.php?op=ventas',
        { Filtro, Inicio, Fin }, data => {
            $('#Vendido').html("<b>"+data+"</b>");
        }
    )
}


// Boton para imprimir el reporte de venta
$('#btnRep').click(function () {
    let Filtro = $('#Filtro').val()
    let Inicio = $('#Inicio').val()
    let Fin = $('#Fin').val()

    window.open("../Archivos/Ventas/pdf_repVentas.php?Filtro="+btoa(Filtro)
        + "&Inicio=" + btoa(Inicio) + "&Fin=" + btoa(Fin), "blank")
})


// Función  pra editar el precio de un articulo
$('#editPU').click(function() {
    let Id_Mat = $('#Id_Mat option:selected').val()
    let Desc = $('#Id_Mat option:selected').text()

    if (Id_Mat != null && Id_Mat > 0){
        $('#ModalPULabel').html(Desc)
        $('#ModalPU').modal('show')
    }
})

$('#Gan').keyup(function(){
    let Ganancia = parseFloat(this.value)
    let Costo = parseFloat($('#Costo').val())

    if (Ganancia > 0){
        Ganancia /= 100
        Costo = Costo + (Costo * Ganancia)
        r = parseInt(Costo - parseInt(Costo)) * 100;
        
        if (r >= 30){
            Costo = Math.ceil(Costo);
        } else {
            if (Costo <= 0.3){
                Costo = 0.5;
            } else {
                Costo = Math.floor(Costo);
            }
        }

        $('#PU').val(Costo)
    }
})


// Funciaon para actualizar precios
let editPU = (e) => {
    e.preventDefault()

    let Id_Mat = $('#Id_Mat option:selected').val()
    let Ganancia = $('#Gan').val()

    $.post('../Archivos/Ventas/ventas.php?op=editPU'
        , { Id_Mat, Ganancia }, resp => {
            if (resp.includes('correctamente')){
                swal.fire(resp, "", "success")
                $('#ModalPU').modal('hide')
            } else {
                swal.fire(resp, "", "error")
            }
            $('#Id_Mat').change()
        }
    )
}


// Funcion para subir al formulario de partidas
let subir = () => {
    var target_offset = $("body").offset();
    var target_top = target_offset.top;
    $("html,body").animate({ scrollTop: target_top }, { duration: "slow" });
};


init()