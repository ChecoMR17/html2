let Tbl_Clientes;
let Tbl_Contacto;

let Tbl_Obras;
$(document).ready(() => {
  $("#Agregar_Clientes").on("submit", function (e) {
    Guardar_Cliente(e);
  });
  $("#Form_Contacto").on("submit", function (e) {
    Guardar_Contacto(e);
  });

  $("#Form_Obras").on("submit", function (e) {
    Guardar_Obras(e);
  });
  Mostrar_Estados();
  Mostrar_Lista_Clientes();
});

let Guardar_Cliente = (e) => {
  e.preventDefault();
  let data = new FormData($("#Agregar_Clientes")[0]);
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
          url: "../Archivos/Clientes/Operaciones.php?op=Guardar_Cliente",
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
              Tbl_Clientes.ajax.reload();
              $("#Btn_Limpiar_C").click();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡El cliente que intenta registrar ya existe!",
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

let Validar_T_Cliente = () => {
  //console.log("Validar");
  if ($("#T_Persona").val() == "Persona física") {
    $(".PF").attr("hidden", false);
    $("#Apellido_p").attr("required", true);
    $("#Apellido_M").attr("required", true);
    $("#RFC").attr("maxlength", 13);
    $("#RFC").attr("minlength", 13);
  } else {
    $(".PF").attr("hidden", true);
    $("#Apellido_p").attr("required", false);
    $("#Apellido_M").attr("required", false);
    $("#RFC").attr("maxlength", 12);
    $("#RFC").attr("minlength", 12);
  }
};

let Mostrar_Estados = () => {
  $.post(
    "../Archivos/Empleados/Operaciones.php?op=Mostrar_Estados",
    (result) => {
      $("#Estado").html(result);
      $("#Estado_O").html(result);
      $("#Estado").selectpicker("refresh");
      $("#Estado_O").selectpicker("refresh");
    }
  );
};

let Buscar_Municipios = () => {
  Estado = $("#Estado").val();
  $.post(
    "../Archivos/Empleados/Operaciones.php?op=Buscar_Municipios",
    { Estado },
    (result) => {
      $("#Municipio").html(result);
      $("#Municipio").selectpicker("refresh");
    }
  );
};

let Mostrar_Lista_Clientes = () => {
  Tbl_Clientes = $("#Tbl_Clientes")
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
        url: "../Archivos/Clientes/Operaciones.php?op=Mostrar_Lista_Clientes",
        type: "post",
        dataType: "json",
        error: (e) => {
          console.log("Error función listar() \n" + e.responseText);
        },
      },
      bDestroy: true,
      iDisplayLength: 20,
      order: [[0, "asc"]],
    })
    .DataTable();
};

let Mostar_datos = (Id_Cliente) => {
  $.post(
    "../Archivos/Clientes/Operaciones.php?op=Mostar_datos",
    { Id_Cliente },
    (result) => {
      //console.log(result);
      result = JSON.parse(result);
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Listo para modificar!",
        showConfirmButton: false,
        timer: 1000,
      });
      $("#Apellido_p").val("");
      $("#Apellido_M").val("");
      $("#Id_Cliente").val(Id_Cliente);
      $("#T_Persona").val(result.T_Persona);
      $("#Nombre_Cliente").val(result.Nombre);
      $("#Apellido_p").val(result.Apellido_P);
      $("#Apellido_M").val(result.Apellido_M);
      $("#RFC").val(result.RFC);
      $("#Correo_C").val(result.Correo_C);
      $("#Correo_P").val(result.Correo_P);
      $("#Celular").val(result.Celular);
      $("#Telefono").val(result.Telefono);
      $("#Estado").val(result.Id_Estado);
      $("#Colonia").val(result.Colonia);
      $("#Calle").val(result.Calle);
      $("#N_Exterior").val(result.N_Exterior);
      $("#N_Interior").val(result.N_Interior);
      $("#CP").val(result.Codigo_P);
      $("#Observaciones").val(result.Observaciones);
      Validar_T_Cliente();
      $("#T_Persona").selectpicker("refresh");
      $("#Estado").selectpicker("refresh");
      Buscar_Municipios();
      setTimeout(() => {
        $("#Municipio").val(result.Id_Municipios);
        $("#Municipio").selectpicker("refresh");
      }, 250);
    }
  );
};

let Baja_Cliente = (Id_Cliente) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja al cliente?",
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
          "../Archivos/Clientes/Operaciones.php?op=Baja_Cliente",
          { Id_Cliente },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡El cliente se dio de baja!",
                showConfirmButton: false,
                timer: 1500,
              });
              Tbl_Clientes.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Intentalo más tarde!",
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

let Alta_Cliente = (Id_Cliente) => {
  Swal.fire({
    title: "¿Estás seguro(a) de reactivar al cliente?",
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
          "../Archivos/Clientes/Operaciones.php?op=Alta_Cliente",
          { Id_Cliente },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡El cliente reactivado!",
                showConfirmButton: false,
                timer: 1500,
              });
              Tbl_Clientes.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Intentalo más tarde!",
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
$("#Btn_Limpiar_C").on("click", function () {
  $("#Estado").val("");
  $("#Municipio").html("");
  $("#Municipio").selectpicker("refresh");
  $("#Estado").selectpicker("refresh");
});

/** --------------------------------------------- CONTACTOS -------------------------------------------------------- */

let Mostrar_Id_Cliente = (Id_Cliente) => {
  $("#Id_Cliente_C").val(Id_Cliente);

  setTimeout(() => {
    Tbl_Contacto = $("#Tbl_Contactos")
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
          url: "../Archivos/Clientes/Operaciones.php?op=Mostrar_Id_Cliente",
          type: "post",
          dataType: "json",
          data: { Id_Cliente },
          error: (e) => {
            console.log("Error función listar() \n" + e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 20,
        order: [[0, "asc"]],
      })
      .DataTable();
  }, 250);
};
let Guardar_Contacto = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Contacto")[0]);
  Id_Cliente = $("#Id_Cliente_C").val();
  data.append("Id_Cliente", Id_Cliente);

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
          url: "../Archivos/Clientes/Operaciones.php?op=Guardar_Contacto",
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
              Tbl_Contacto.ajax.reload();
              Btn_Limpiar_C_C();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡El contacto que intenta registrar ya existe!",
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

let Datos_Contacto = (Id_contacto) => {
  $.post(
    "../Archivos/Clientes/Operaciones.php?op=Datos_Contacto",
    { Id_contacto },
    (result) => {
      result = JSON.parse(result);
      //console.log(result);
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Listo para modificar!",
        showConfirmButton: false,
        timer: 1000,
      });
      $("#Id_contacto").val(Id_contacto);
      $("#Nombre_Contacto").val(result.Nombre);
      $("#Apellido_P_Contacto").val(result.Apellido_P);
      $("#Apellido_M_Contacto").val(result.Apellido_M);
      $("#Celular_Contacto").val(result.Celular);
      $("#Telefono_Contacto").val(result.Telefono);
      $("#Correo_C_C").val(result.Correo_C);
      $("#Correo_C_P").val(result.Correo_P);
      $("#Observaciones_Contactos").val(result.Observaciones);
    }
  );
};

let Alta_Contacto = (Id_contacto) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja al contacto?",
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
          "../Archivos/Clientes/Operaciones.php?op=Alta_Contacto",
          { Id_contacto },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡El contacto se reactivo!",
                showConfirmButton: false,
                timer: 1500,
              });
              Tbl_Contacto.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Intentalo más tarde!",
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

let Baja_Contacto = (Id_contacto) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja al contacto?",
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
          "../Archivos/Clientes/Operaciones.php?op=Baja_Contacto",
          { Id_contacto },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡El contacto se dio de baja!",
                showConfirmButton: false,
                timer: 1500,
              });
              Tbl_Contacto.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, Intentalo más tarde!",
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

let Btn_Limpiar_C_C = () => {
  $("#Id_contacto").val("");
  $("#Nombre_Contacto").val("");
  $("#Apellido_P_Contacto").val("");
  $("#Apellido_M_Contacto").val("");
  $("#Celular_Contacto").val("");
  $("#Telefono_Contacto").val("");
  $("#Correo_C_C").val("");
  $("#Correo_C_P").val("");
  $("#Observaciones_Contactos").val("");
};

/** --------------------------------------------- OBRAS -------------------------------------------------------- */

let Datos_F_Obras = (Id_Cliente) => {
  $("#Id_Cliente_O").val(Id_Cliente);
  setTimeout(() => {
    Tbl_Obras = $("#Tbl_Obra")
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
          url: "../Archivos/Clientes/Operaciones.php?op=Mostrar_Tbl_Obras",
          type: "post",
          data: { Id_Cliente },
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
let Guardar_Obras = (e) => {
  e.preventDefault();
  //console.log("Obras");
  let data = new FormData($("#Form_Obras")[0]);
  Id_Cliente = $("#Id_Cliente_O").val();
  data.append("Id_Cliente", Id_Cliente);
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
          url: "../Archivos/Clientes/Operaciones.php?op=Guardar_Obras",
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
              Limpiar_Formulario_O();
              Tbl_Obras.ajax.reload();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡La obra que intenta registrar ya existe!",
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

let Buscar_Municipios_O = () => {
  Estado = $("#Estado_O").val();
  $.post(
    "../Archivos/Empleados/Operaciones.php?op=Buscar_Municipios",
    { Estado },
    (result) => {
      $("#Municipio_O").html(result);
      $("#Municipio_O").selectpicker("refresh");
    }
  );
};

let Eliminar_Obra = (Id_Obra) => {
  Swal.fire({
    title: "¿Estás seguro(a) de eliminar la obra?",
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
          "../Archivos/Clientes/Operaciones.php?op=Eliminar_Obra",
          { Id_Obra },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡La clasificación se elimino!",
                showConfirmButton: false,
                timer: 1500,
              });
              Tbl_Obras.ajax.reload();
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

let Datos_Obra = (Id_Obra) => {
  //console.log(Id_Obra);
  $.post(
    "../Archivos/Clientes/Operaciones.php?op=Datos_Obra",
    { Id_Obra },
    (result) => {
      result = JSON.parse(result);
      //console.log(result);
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Listo para modificar!",
        showConfirmButton: false,
        timer: 1000,
      });
      $("#Id_Obra").val(Id_Obra);
      $("#Descripcion_Obras").val(result.Nombre_Obra);
      $("#Estado_O").val(result.Id_Estado);
      $("#Colonia_O").val(result.Colonia);
      $("#Calle_O").val(result.Calle);
      $("#N_Exterior_O").val(result.N_Exterior);
      $("#N_Interior_O").val(result.N_Interior);
      $("#CP_O").val(result.Codigo_P);
      $("#Observaciones_O").val(result.Observaciones);

      Buscar_Municipios_O();
      setTimeout(() => {
        $("#Municipio_O").val(result.Id_Municipios);
        $("#Municipio_O").selectpicker("refresh");
      }, 250);
      $("#Estado_O").selectpicker("refresh");
    }
  );
};
let Limpiar_Formulario_O = () => {
  $("#Id_Obra").val("");
  $("#Descripcion_Obras").val("");
  $("#Colonia_O").val("");
  $("#Calle_O").val("");
  $("#N_Exterior_O").val("");
  $("#N_Interior_O").val("");
  $("#CP_O").val("");
  $("#Observaciones_O").val("");
  $("#Clasificacion").val("");
  $("#Estado_O").val("");
  $("#Municipio_O").html("");
  $("#Clasificacion").selectpicker("refresh");
  $("#Municipio_O").selectpicker("refresh");
  $("#Estado_O").selectpicker("refresh");
};
