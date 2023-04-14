let init = () => {
  $('#Form_Mat')[0].reset();
  $('#Form_Mat').on("submit", function (e) { save_Mat(e) })
  $('#formFamilias').on("submit", function (e) { save_Fam(e) })
  $('#formUnidades').on("submit", function (e) { save_UM(e) })
  $('#Fomr_Stock').on("submit", function (e) { updateStock(e) })
  tot_inv()
  listar_Mat();
  select_Fam();
  select_um();
  select_prov();
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


$('#btnAdd').click(function () { 
  $('#Form_Mat').attr('hidden', false)
})


let tbl_Mat;

// Listado de materiales
let listar_Mat = () => {
  tbl_Mat=$('#tbl_Mat').dataTable({
      "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
      "ajax":
              {
                  url: '../Archivos/Cat_Materiales/cat_materiales.php?op=listar_Mat',
                  type : "post",
                  dataType : "json"
              },
      "bDestroy": true,
      "iDisplayLength": 1000,//Paginación
      "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
  }).DataTable();
}

// Listado de familias
let select_Fam = () => {
  $.post("../Archivos/Cat_Materiales/cat_materiales.php?op=select_Fam"
    , resp => {
      $("#Id_Fam").html(resp)
      $("#Id_Fam").selectpicker();
      $("#Id_Fam").selectpicker('refresh');
    }, 'html'
  )
}

// Listado de Unidades
let select_um = () => {
  $.post("../Archivos/Cat_Materiales/cat_materiales.php?op=select_UM"
    , resp => {
      $("#Id_UM1").html(resp)
      $("#Id_UM2").html(resp)
      $("#Id_UM1").selectpicker();
      $("#Id_UM2").selectpicker();
      $("#Id_UM1").selectpicker('refresh');
      $("#Id_UM2").selectpicker('refresh');
    }, 'html'
  )
}


// Listado de Proveedores
let select_prov = () => {
  $.post("../Archivos/Cat_Materiales/cat_materiales.php?op=select_Prov"
    , resp => {
      $("#Id_Prov").html(resp)
      $("#Id_Prov").selectpicker();
    }, 'html'
  )
}


// Función para vaciar campos
let limpiar = () => {
  $('#Form_Mat')[0].reset();
  $("#Id_Fam").selectpicker("refresh");
  $("#Id_UM1").selectpicker("refresh");
  $("#Id_UM2").selectpicker("refresh");
  $("#Id_Prov").selectpicker("refresh");
}

$('#btnErase').click(function(){
  $('#Form_Mat').attr('hidden', true)
  limpiar()
})


// fFunción para mostra informacion de un material
let verMat = (Id_Mat) => {
  $('#btnAdd').click()
  $.post('../Archivos/Cat_Materiales/cat_materiales.php?op=verMat'
    , { Id_Mat }, data => {
      subir();
      $("#Id_Mat").val(data.Id_Mat);
      $("#Cve_Mat").val(data.Cve_Mat);
      $("#Id_Fam").val(data.Id_Fam);
      $("#Id_UM1").val(data.Id_UM1);
      $("#Id_UM2").val(data.Id_UM2);
      $("#Id_Prov").val(data.Id_Prov);
      $("#Stock").val(data.Stock);
      $("#Min").val(data.Min);
      $("#Max").val(data.Max);
      $("#Costo").val(data.Costo);
      $("#Ganancia").val(data.Ganancia);
      $("#Status").val(data.Status);
      $("#Desc_Mat").val(data.Desc_Mat);
      $("#Id_Fam").selectpicker("refresh");
      $("#Id_UM1").selectpicker("refresh");
      $("#Id_UM2").selectpicker("refresh");
      $("#Id_Prov").selectpicker("refresh");
    }, 'json'
  )
}


// Función para guardar y/o actualizar un material
let save_Mat = (e) => {
  e.preventDefault();

  let data = new FormData($("#Form_Mat")[0])

  $.ajax({
    type: "post",
    url: "../Archivos/Cat_Materiales/cat_materiales.php?op=save_Mat",
    data: data,
    processData: false,
    contentType: false,
    success: resp => {
      tbl_Mat.ajax.reload();
      if (resp.includes('correctamente')){
        swal.fire(resp, "", "success")
        limpiar()
      } else {
        swal.fire(resp, "", "error")
      }
    }, error: e =>{
      console.log(e.responseText)
    }
  });
}

// Funcion para subir al formulario de partidas
let subir = () => {
  var target_offset = $(".box").offset();
  var target_top = target_offset.top;
  $("html,body").animate({ scrollTop: target_top }, { duration: "slow" });
};


// Función para suspender un material
let suspMat = (Id_Mat) => {
  Swal.fire({
    title: 'Se suspenderá el material!',
    text: "",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: "Cancelar",
    confirmButtonText: 'Continuar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../Archivos/Cat_Materiales/cat_materiales.php?op=suspMat"
        , { Id_Mat }, resp => {
          tbl_Mat.ajax.reload();
          if (resp.includes('correctamente')){
            swal.fire(resp, "", "success")
            tot_inv()
          } else {
            swal.fire(resp, "", "error")
          }
        }
      )
    }
  })
}


// Funciín para activar un material
let actMat = (Id_Mat) => {
  Swal.fire({
    title: 'Se activará el material!',
    text: "",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: "Cancelar",
    confirmButtonText: 'Continuar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.post("../Archivos/Cat_Materiales/cat_materiales.php?op=actMat"
        , { Id_Mat }, resp => {
          tbl_Mat.ajax.reload();
          if (resp.includes('correctamente')){
            swal.fire(resp, "", "success")
            tot_inv()
          } else {
            swal.fire(resp, "", "error")
          }
        }
      )
    }
  })
}


// Funciónp ar obtener el total del inventario
let tot_inv = () => {
  $.post('../Archivos/Cat_Materiales/cat_materiales.php?op=tot_inv'
    , data => {
      console.log(data)
      $("#Compra").html(data.Compra)
      $("#Venta").html(data.Venta)
      $("#Diferencia").html(data.Diferencia)
    }, 'json'
  )
}

/*====================================================================================================================================\
|                                                  Modal Familias                                                                     |
\====================================================================================================================================*/

$('#Familias').on('show.bs.modal', function () {
  if (!$.fn.DataTable.isDataTable("#tblFam")) {
    familias();
  } else {
    tblFam.ajax.reload()
  }
})

let tblFam;

$('#Familias').on('hidden.bs.modal', function () {
  $('#formFamilias')[0].reset()
  select_Fam();
})


// Función para listado de familias
let familias = () => {
  setTimeout(()=>{
    tblFam=$('#tblFam').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
        "ajax":
                {
                    url: '../Archivos/Cat_Materiales/cat_materiales.php?op=listar_Fam',
                    type : "post",
                    dataType : "json"
                },
        "bDestroy": true,
        "iDisplayLength": 1000,//Paginación
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
  }, 300)
}

// Caso para ver una familia
let verFam = (Id_Fam) => {
  $.post('../Archivos/Cat_Materiales/cat_materiales.php?op=verFam'
    , { Id_Fam }, data => {
      $("#Id_F").val(data.Id_Fam);
      $("#Desc_Fam").val(data.Desc_Fam);
      $("#Gan").val(data.Ganancia);
    }, 'json'
  )
}

/*
$('#Id_Fam').change(function(){
  let Id_Fam = this.value;
  $.post('../Archivos/Cat_Materiales/cat_materiales.php?op=verFam'
    , { Id_Fam }, data => { $("#Ganancia").val(data.Ganancia) }
    , 'json'
  )
})
*/


// Función par guardar familias
let save_Fam = (e) => {
  e.preventDefault();

  let data = new FormData($("#formFamilias")[0])

  $.ajax({
    type: "post",
    url: "../Archivos/Cat_Materiales/cat_materiales.php?op=save_Fam",
    data: data,
    processData: false,
    contentType: false,
    success: resp => {
      tblFam.ajax.reload();
      if (resp.includes('correctamente')){
        swal.fire(resp, "", "success")
        $('#formFamilias')[0].reset()
        tot_inv()
      } else {
        swal.fire(resp, "", "error")
      }
    }
  });
}

/*====================================================================================================================================\
|                                                  Modal Unidades                                                                     |
\====================================================================================================================================*/

$('#Unidades').on('show.bs.modal', function () {
  if (!$.fn.DataTable.isDataTable("#tblUM")) {
    unidades();
  } else {
    tblUM.ajax.reload()
  }
})

let tblUM;

$('#Unidades').on('hidden.bs.modal', function () {
  $('#formUnidades')[0].reset();
  select_um();
})

// Función para listado de unidades
let unidades = () => {
  setTimeout(()=>{
    tblUM=$('#tblUM').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
        "ajax":
                {
                    url: '../Archivos/Cat_Materiales/cat_materiales.php?op=listar_UM',
                    type : "post",
                    dataType : "json"
                },
        "bDestroy": true,
        "iDisplayLength": 1000,//Paginación
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
  }, 300)
}

// Caso para ver una familia
let verUM = (Id_UM) => {
  $.post('../Archivos/Cat_Materiales/cat_materiales.php?op=verUM'
    , { Id_UM }, data => {
      $("#Id_UM").val(data.Id_UM);
      $("#Desc_UM").val(data.Desc_UM);
      $("#Abrev").val(data.Abrev);
    }, 'json'
  )
}


// Función par guardar familias
let save_UM = (e) => {
  e.preventDefault();

  let data = new FormData($("#formUnidades")[0])

  $.ajax({
    type: "post",
    url: "../Archivos/Cat_Materiales/cat_materiales.php?op=save_UM",
    data: data,
    processData: false,
    contentType: false,
    success: resp => {
      tblUM.ajax.reload();
      if (resp.includes('correctamente')){
        swal.fire(resp, "", "success")
        $('#formUnidades')[0].reset()
      } else {
        swal.fire(resp, "", "error")
      }
    }
  });
}



/*====================================================================================================================================\
|                                                  Modal Maximos                                                                      |
\====================================================================================================================================*/

$('#Maxi').on('show.bs.modal', function () {
  if (!$.fn.DataTable.isDataTable("#tblMax")) {
    max();
  } else {
    tblMax.ajax.reload()
  }
})

let tblMax;

// Función para listado de Maxios
let max = () => {
  setTimeout(()=>{
    console.log('maxiomos')
    tblMax=$('#tblMax').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
        "ajax":
                {
                    url: '../Archivos/Cat_Materiales/cat_materiales.php?op=listar_Max',
                    type : "post",
                    dataType : "json"
                },
        "bDestroy": true,
        "iDisplayLength": 1000,//Paginación
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
  }, 300)
}


/*====================================================================================================================================\
|                                                  Modal Minimos                                                                      |
\====================================================================================================================================*/

$('#Mini').on('show.bs.modal', function () {
  if (!$.fn.DataTable.isDataTable("#tblMin")) {
    min();
  } else {
    tblMin.ajax.reload()
  }
})

let tblMin;

// Función para listado de Minimos
let min = () => {
  setTimeout(()=>{
    tblMin=$('#tblMin').dataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdf' ],
        "ajax":
                {
                    url: '../Archivos/Cat_Materiales/cat_materiales.php?op=listar_Min',
                    type : "post",
                    dataType : "json"
                },
        "bDestroy": true,
        "iDisplayLength": 1000,//Paginación
        "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
    }).DataTable();
  }, 300)
}


// Función para actualizar el stock
let updateStock = (e) => {
  e.preventDefault()

  let data = new FormData($('#Fomr_Stock')[0])

  $.ajax({
      type: "post",
      url: "../Archivos/Cat_Materiales/updateStock.php",
      data: data,
      contentType: false,
      processData: false,
      success: resp => {
        tbl_Mat.ajax.reload();
        resp = JSON.parse(resp)
        if (resp.msg.includes('actualizado')) {
          swal.fire(resp.msg, "", "success")
          $('#Fomr_Stock')[0].reset()
        } else {
          swal.fire(resp.msg, "", "error")
        }
        $('#diverror').html(resp.tbl)

        setTimeout(() => {
          $('#tblerror').dataTable({
              "aServerSide": true,//Paginación y filtrado realizados por el servidor
              dom: 'Bfrtip',//Definimos los elementos del control de tabla
              buttons: [  'copyHtml5', 'excelHtml5', 'csvHtml5'],
              "bDestroy": true,
              "iDisplayLength": 1000,//Paginación
              "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
          }).DataTable();
        }, 300)
      }
  });
}

$('#Update').on('hidden.bs.modal', function () {
  $('#Fomr_Stock')[0].reset();
  $('#diverror').html('')
})

init()