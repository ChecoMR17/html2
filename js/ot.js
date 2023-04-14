let Tbl_OT;
let Tbl_Clasificaciones;
let Tbl_Actividades;
$(document).ready(() => {
  $("#Form_Ordenes_Trabajo").on("submit", function (e) {
    Guardar_Ordenes_Trabajo(e);
  });
  $("#Form_Clasificaciones").on("submit", function (e) {
    Guardar_Clasificacion(e);
  });
  $("#Form_Actividades").on("submit", function (e) {
    Guardar_Actividad(e);
  });

  $("#Form_Documentos").on("submit", function (e) {
    Guardar_Documentos(e);
  });

  Mostrar_Clientes();
  Mostrar_Lista_OT();
  Buscar_Clasificacion();
});

let Guardar_Ordenes_Trabajo = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Ordenes_Trabajo")[0]);
  Swal.fire({
    title: "¿Estás seguro(a) de guardar?",
    text: "",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Continuar!",
  }).then((Opcion) => {
    if (Opcion.isConfirmed) {
      Swal.fire({
        imageUrl: "../img/Cargando.gif",
        imageWidth: 400,
        imageHeight: 400,
        background: "background-color: transparent",
        showConfirmButton: false,
        customClass: "transparente",
      });
      setTimeout(() => {
        $.ajax({
          type: "POST",
          url: "../Archivos/Ordenes/Operaciones.php?op=Guardar_Ordenes_Trabajo",
          data: data,
          contentType: false,
          processData: false,
          success: function (result) {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Guardado!",
                showConfirmButton: false,
                timer: 2500,
              });
              Limpiar_F_OT();
              Tbl_OT.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Inténtalo más tarde!",
                showConfirmButton: false,
                timer: 1500,
              });
            }
          },
        });
      }, 250);
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Ejecucion_Ot = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de ejecutar la orden de trabajo?",
    text: "",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Continuar!",
  }).then((Opcion) => {
    if (Opcion.isConfirmed) {
      Swal.fire({
        imageUrl: "../img/Cargando.gif",
        imageWidth: 400,
        imageHeight: 400,
        background: "background-color: transparent",
        showConfirmButton: false,
        customClass: "transparente",
      });
      setTimeout(() => {
        $.post(
          "../Archivos/Ordenes/Operaciones.php?op=Ejecucion_Ot",
          { Id },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "!En ejecución¡",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_OT.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, inténtelo mas tarde¡",
                showConfirmButton: false,
                timer: 2500,
              });
            }
          }
        );
      }, 250);
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "!Operación cancelada¡",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};
let Datos_Modificar = (Id) => {
  $.post(
    "../Archivos/Ordenes/Operaciones.php?op=Datos_Modificar",
    { Id },
    (result) => {
      result = JSON.parse(result);
      console.log(result);
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Listo para modificar!",
        showConfirmButton: false,
        timer: 1000,
      });
      $("#Id").val(Id);
      $("#Cliente").val(result.Id_Cliente);
      $("#Cliente").selectpicker("refresh");
      $("#Prioridad").val(result.Prioridad);
      $("#Prioridad").selectpicker("refresh");
      $("#Proyecto").val(result.Proyecto);
      $("#Fecha_Inicio").val(result.Fecha_Inicio);
      $("#Fecha_Final").val(result.Fecha_Final);
      $("#Observaciones").val(result.Observaciones);
      $("#Clasificacion").val(result.Id_Clasificacion);
      $(".form-check-input").attr("disabled", false);
      if (result.Status == "U") {
        $("#S_Ejecucion").prop("checked", true);
      } else if (result.Status == "C") {
        $("#S_Concluido").prop("checked", true);
      } else if (result.Status == "B") {
        $("#S_Cancelado").prop("checked", true);
      }
      Buscar_Obras();
      setTimeout(() => {
        $("#Obras").val(result.Id_Obra);
        $("#Contactos").val(result.Id_Contacto);
        $("#Clasificacion").selectpicker("refresh");
        $("#Obras").selectpicker("refresh");
        $("#Contactos").selectpicker("refresh");
      }, 250);
    }
  );
};
let Mostrar_Lista_OT = () => {
  Tbl_OT = $("#Tbl_Ordenes_Trabajo")
    .dataTable({
      language: {
        search: "BUSCAR",
        info: "_START_ A _END_ DE _TOTAL_ ELEMENTOS",
      },
      dom: "Bfrtip",
      buttons: ["copy", "excel", "pdf"],
      autoFill: true,
      colReorder: true,
      rowReorder: true,
      ajax: {
        url: "../Archivos/Ordenes/Operaciones.php?op=Mostrar_Lista_OT",
        type: "post",
        dataType: "json",
        error: (e) => {
          console.log("Error función listar() \n" + e.responseText);
        },
      },
      bDestroy: true,
      iDisplayLength: 200,
      order: [[0, "desc"]],
    })
    .DataTable();
};

let Mostrar_Clientes = () => {
  $.post(
    "../Archivos/Ordenes/Operaciones.php?op=Mostrar_Clientes",
    (result) => {
      $("#Cliente").html(result);
      $("#Cliente").selectpicker("refresh");
    }
  );
};

let Buscar_Obras = () => {
  Cliente = $("#Cliente").val();
  $.post(
    "../Archivos/Ordenes/Operaciones.php?op=Buscar_Obras",
    { Cliente },
    (result) => {
      $("#Obras").html(result);
      $("#Obras").selectpicker("refresh");
      Buscar_Contactos(Cliente);
    }
  );
};
let Buscar_Contactos = (Cliente) => {
  $.post(
    "../Archivos/Ordenes/Operaciones.php?op=Buscar_Contactos",
    { Cliente },
    (result) => {
      $("#Contactos").html(result);
      $("#Contactos").selectpicker("refresh");
    }
  );
};

let Limpiar_F_OT = () => {
  $("#Id").val("");
  $("#Cliente").val("");
  $("#Obras").html("");
  $("#Contactos").html("");
  $("#Prioridad").val("");
  $("#Proyecto").val("");
  $("#Fecha_Inicio").val("");
  $("#Fecha_Final").val("");
  $("#Observaciones").val("");
  $("#Clasificacion").val("");
  $("#S_Concluido").attr("disabled", true);
  $("#S_Cancelado").attr("disabled", true);
  $("#Clasificacion").selectpicker("refresh");
  $("#Cliente").selectpicker("refresh");
  $("#Prioridad").selectpicker("refresh");
  $("#Obras").selectpicker("refresh");
  $("#Contactos").selectpicker("refresh");
};

/** --------------------------------------------- CLASIFICACIONES -------------------------------------------------------- */
let Guardar_Clasificacion = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Clasificaciones")[0]);
  Swal.fire({
    title: "¿Estás seguro(a) de guardar?",
    text: "",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Continuar!",
  }).then((Opcion) => {
    if (Opcion.isConfirmed) {
      Swal.fire({
        imageUrl: "../img/Cargando.gif",
        imageWidth: 400,
        imageHeight: 400,
        background: "background-color: transparent",
        showConfirmButton: false,
        customClass: "transparente",
      });
      setTimeout(() => {
        $.ajax({
          type: "POST",
          url: "../Archivos/Ordenes/Operaciones.php?op=Guardar_Clasificacion",
          data: data,
          contentType: false,
          processData: false,
          success: function (result) {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Guardado!",
                showConfirmButton: false,
                timer: 2500,
              });
              Limpiar_Formulario_Calcificaciones();
              Tbl_Clasificaciones.ajax.reload();
              Buscar_Clasificacion();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡La clasificación que intenta registrar ya existe!",
                showConfirmButton: false,
                timer: 2500,
              });
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Inténtalo más tarde!",
                showConfirmButton: false,
                timer: 1500,
              });
            }
          },
        });
      }, 250);
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Mostrar_Tbl_Clasificaciones = () => {
  setTimeout(() => {
    Tbl_Clasificaciones = $("#Tbl_Clasificaciones")
      .dataTable({
        language: {
          search: "BUSCAR",
          info: "_START_ A _END_ DE _TOTAL_ ELEMENTOS",
        },
        dom: "Bfrtip",
        buttons: ["copy", "excel", "pdf"],
        autoFill: true,
        colReorder: true,
        rowReorder: true,
        ajax: {
          url: "../Archivos/Ordenes/Operaciones.php?op=Mostrar_Tbl_Clasificaciones",
          type: "post",
          dataType: "json",
          error: (e) => {
            console.log("Error función listar() \n" + e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 20,
        order: [[0, "desc"]],
      })
      .DataTable();
  }, 250);
};

let Datos_Clasificacion = (Id_Clasificacion) => {
  $.post(
    "../Archivos/Ordenes/Operaciones.php?op=Datos_Clasificacion",
    { Id_Clasificacion },
    (result) => {
      //console.log(result);
      result = JSON.parse(result);
      $("#Id_Clasificacion").val(Id_Clasificacion);
      $("#Nombre_Clasificacion").val(result.Nombre);
    }
  );
};

let Eliminar_Clasificacion = (Id_Clasificacion) => {
  Swal.fire({
    title: "¿Estás seguro(a) de eliminar la clasificación?",
    text: "",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Continuar!",
  }).then((Opcion) => {
    if (Opcion.isConfirmed) {
      Swal.fire({
        imageUrl: "../img/Cargando.gif",
        imageWidth: 400,
        imageHeight: 400,
        background: "background-color: transparent",
        showConfirmButton: false,
        customClass: "transparente",
      });
      setTimeout(() => {
        $.post(
          "../Archivos/Ordenes/Operaciones.php?op=Eliminar_Clasificacion",
          { Id_Clasificacion },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡La clasificación se elimino!",
                showConfirmButton: false,
                timer: 1500,
              });
              Tbl_Clasificaciones.ajax.reload();
              Buscar_Clasificacion();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Inténtalo más tarde!",
                showConfirmButton: false,
                timer: 1500,
              });
            }
          }
        );
      }, 250);
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Buscar_Clasificacion = () => {
  $.post(
    "../Archivos/Ordenes/Operaciones.php?op=Buscar_Clasificacion",
    (result) => {
      $("#Clasificacion").html(result);
      $("#Clasificacion").selectpicker("refresh");
    }
  );
};

let Limpiar_Formulario_Calcificaciones = () => {
  $("#Id_Clasificacion").val("");
  $("#Nombre_Clasificacion").val("");
};

/** --------------------------------------------- ACTIVIDADES -------------------------------------------------------- */

let Mostrar_Id = (Id, btn) => {
  btn == 0
    ? $("#Btn_GA").attr("disabled", true)
    : $("#Btn_GA").attr("disabled", false);
  $("#OT").val(Id);
  setTimeout(() => {
    Tbl_Actividades = $("#Tbl_Actividades")
      .dataTable({
        language: {
          search: "BUSCAR",
          info: "_START_ A _END_ DE _TOTAL_ ELEMENTOS",
        },
        dom: "Bfrtip",
        buttons: ["copy", "excel", "pdf"],
        autoFill: true,
        colReorder: true,
        rowReorder: true,
        ajax: {
          url: "../Archivos/Ordenes/Operaciones.php?op=Mostrar_Tbl_Actividades",
          type: "post",
          dataType: "json",
          data: { Id },
          error: (e) => {
            console.log("Error función listar() \n" + e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 20,
        order: [[0, "desc"]],
      })
      .DataTable();
  }, 250);
};

let Guardar_Actividad = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Actividades")[0]);
  Id = $("#OT").val();
  data.append("Id", Id);

  Swal.fire({
    title: "¿Estás seguro(a) de guardar?",
    text: "",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Continuar!",
  }).then((Opcion) => {
    if (Opcion.isConfirmed) {
      Swal.fire({
        imageUrl: "../img/Cargando.gif",
        imageWidth: 400,
        imageHeight: 400,
        background: "background-color: transparent",
        showConfirmButton: false,
        customClass: "transparente",
      });
      setTimeout(() => {
        $.ajax({
          type: "POST",
          url: "../Archivos/Ordenes/Operaciones.php?op=Guardar_Actividad",
          data: data,
          contentType: false,
          processData: false,
          success: function (result) {
            //console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Guardado!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Actividades.ajax.reload();
              Btn_Limpiar_A();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Inténtalo más tarde!",
                showConfirmButton: false,
                timer: 1500,
              });
            }
          },
        });
      }, 250);
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Datos_Modificar_A = (Id) => {
  $.post(
    "../Archivos/Ordenes/Operaciones.php?op=Datos_Modificar_A",
    { Id },
    (result) => {
      //console.log(result);
      result = JSON.parse(result);
      //console.log(result);
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Listo para modificar!",
        showConfirmButton: false,
        timer: 1000,
      });
      $("#Id_Actividad").val(Id);
      $("#Fecha_Actividad").val(result.Fecha_Actividad);
      $("#Nombre_Actividad").val(result.Actividad);
      $("#Descripcion_Actividad").val(result.Descripcion);
    }
  );
};

let Elimiar_Actividad = (Id_Actividad) => {
  Swal.fire({
    title: "¿Estás seguro(a) de guardar?",
    text: "",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Continuar!",
  }).then((Opcion) => {
    if (Opcion.isConfirmed) {
      Swal.fire({
        imageUrl: "../img/Cargando.gif",
        imageWidth: 400,
        imageHeight: 400,
        background: "background-color: transparent",
        showConfirmButton: false,
        customClass: "transparente",
      });
      setTimeout(() => {
        $.post(
          "../Archivos/Ordenes/Operaciones.php?op=Elimiar_Actividad",
          { Id_Actividad },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Eliminado!",
                showConfirmButton: false,
                timer: 1500,
              });
              Tbl_Actividades.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error al eliminar!",
                showConfirmButton: false,
                timer: 1500,
              });
            }
          }
        );
      }, 250);
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Btn_Limpiar_A = () => {
  $("#Id_Actividad").val("");
  $("#Fecha_Actividad").val("");
  $("#Nombre_Actividad").val("");
  $("#Descripcion_Actividad").val("");
};

/** --------------------------------------------- GUARDAR DOCUMENTOS -------------------------------------------------------- */

let Mostrar_D_D = (Id, validar) => {
  $("#OT_D").val(Id);
  Buscar_Archivos(Id);
};
let Guardar_Documentos = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Documentos")[0]);
  Id = $("#OT_D").val();
  data.append("Id", Id);
  Swal.fire({
    title: "¿Estás seguro(a) de guardar?",
    text: "",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Continuar!",
  }).then((Opcion) => {
    if (Opcion.isConfirmed) {
      Swal.fire({
        imageUrl: "../img/Cargando.gif",
        imageWidth: 400,
        imageHeight: 400,
        background: "background-color: transparent",
        showConfirmButton: false,
        customClass: "transparente",
      });
      setTimeout(() => {
        $.ajax({
          type: "POST",
          url: "../Archivos/Ordenes/Operaciones.php?op=Guardar_Documentos",
          data: data,
          contentType: false,
          processData: false,
          success: function (result) {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Guardado!",
                showConfirmButton: false,
                timer: 2500,
              });
              Buscar_Archivos(Id);
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Inténtalo más tarde!",
                showConfirmButton: false,
                timer: 1500,
              });
            }
          },
        });
      }, 250);
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Buscar_Archivos = (Id) => {
  //console.log("Mostrar Archivos de " + Id);
  $.post(
    "../Archivos/Ordenes/Operaciones.php?op=Buscar_Archivos",
    { Id },
    (result) => {
      //console.log(result);
      $("#Id_Mostrar_Documentos").html(result);
    }
  );
};

let Descargar_Archivo = (Ruta, nombre) => {
  var a = document.createElement("a");
  a.download = nombre;
  a.target = "_blank";
  a.href = Ruta;
  a.click();
};

let Ampliar_Archivo = (Ruta) => {
  //console.log("Ampliar formato");
  window.open(Ruta, "_blank");
};

let Eliminar_Archivo = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de eliminar el archivo?",
    text: "",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Continuar!",
  }).then((Opcion) => {
    if (Opcion.isConfirmed) {
      Swal.fire({
        imageUrl: "../img/Cargando.gif",
        imageWidth: 400,
        imageHeight: 400,
        background: "background-color: transparent",
        showConfirmButton: false,
        customClass: "transparente",
      });
      setTimeout(() => {
        $.post(
          "../Archivos/Ordenes/Operaciones.php?op=Eliminar_Archivo",
          { Id },
          (result) => {
            //console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Eliminado!",
                showConfirmButton: false,
                timer: 2500,
              });
              Buscar_Archivos(Id);
              Limpiar_Form_Archivos();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Inténtalo más tarde!",
                showConfirmButton: false,
                timer: 1500,
              });
            }
          }
        );
      }, 250);
    } else {
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Limpiar_Form_Archivos = () => {
  $("#Nombre_documento").val("");
  $("#Documento").val("");
  $("#Descripcion_Documento").val("");
};
