let Tbl_Familias;
let Tbl_Proveedores;
let Tbl_Fam_Prov;
let Tbl_Sucursales;
let Tbl_DBancarios;
let Tbl_Lista_Bancos;
$(document).ready(() => {
  $("#Form_Familias").on("submit", function (e) {
    Guardar_Familias(e);
  });

  $("#Form_Proveedores").on("submit", function (e) {
    Guardar_Proveedores(e);
  });

  $("#Form_Familias_Prov").on("submit", function (e) {
    Guardar_Familias_Prov(e);
  });

  $("#Form_Sucursales").on("submit", function (e) {
    Guardar_Sucursales(e);
  });

  $("#Form_Bancos").on("submit", function (e) {
    Guardar_D_Bancarios(e);
  });

  $("#Form_A_Bancos").on("submit", function (e) {
    Guardar_A_Bancos(e);
  });

  Mostrar_Familias();
  Mostrar_Lista_Proveedores();
  Mostrar_Estados();
  Mostrar_Bancos();
});

let Validar_T_Proveedor = () => {
  $("#Apellido_p").val("");
  $("#Apellido_M").val("");
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

let Guardar_Proveedores = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Proveedores")[0]);
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
          url: "../Archivos/Proveedores/operaciones.php?op=Guardar_Proveedores",
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
              $("#Btn_Limpiar_Prov").click();
              Tbl_Proveedores.ajax.reload();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡El proveedor que intenta registrar ya existe!",
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

let Mostrar_Lista_Proveedores = () => {
  Tbl_Proveedores = $("#Tbl_Proveedores")
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
        url: "../Archivos/Proveedores/operaciones.php?op=Mostrar_Lista_Proveedores",
        type: "post",
        dataType: "json",
        error: (e) => {
          console.log("Error función listar()\n" + e.responseText);
        },
      },
      bDestroy: true,
      iDisplayLength: 20,
      order: [[0, "desc"]],
    })
    .DataTable();
};

let Datos_Modificar_Prov = (Id) => {
  console.log(Id);
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Datos_Modificar_Prov",
    { Id },
    (result) => {
      result = JSON.parse(result);
      console.log(result);
      $("#Id").val(Id);
      $("#T_Persona").val(result.Tipo_Persona);
      $("#Nombre_Proveedor").val(result.Nombre);
      $("#Apellido_p").val(result.Apellido_P);
      $("#Apellido_M").val(result.Apellido_M);
      $("#RFC").val(result.RFC);
      $("#C_Pago").val(result.Pago);
      $("#Giro").val(result.Giro);
      $("#Observaciones").val(result.Observaciones);

      if (result.Tipo_Persona == "Persona física") {
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

      $("#T_Persona").selectpicker("refresh");
      $("#C_Pago").selectpicker("refresh");
    }
  );
};

let Baja_Proveedor = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja al proveedor?",
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
          "../Archivos/Proveedores/operaciones.php?op=Baja_Proveedor",
          { Id },
          (result) => {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡El proveedor se dio de baja!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Proveedores.ajax.reload();
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

let Reactivar_Proveedor = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de reactivar al proveedor?",
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
          "../Archivos/Proveedores/operaciones.php?op=Reactivar_Proveedor",
          { Id },
          (result) => {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡El proveedor se reactivo!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Proveedores.ajax.reload();
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

$("#Btn_Limpiar_Prov").on("click", function () {
  $(".PF").attr("hidden", true);
  $("#Apellido_p").attr("required", false);
  $("#Apellido_M").attr("required", false);
  $("#C_Pago").val("");
  $("#T_Persona").val("");
  $("#T_Persona").selectpicker("refresh");
  $("#C_Pago").selectpicker("refresh");
});

let Guardar_Familias_Prov = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Familias_Prov")[0]);
  Id = $("#Id_Prov").val();
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
          url: "../Archivos/Proveedores/operaciones.php?op=Guardar_Familias_Prov",
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
              $("#Btn_Limpiar_Fam_Prov").click();
              Tbl_Fam_Prov.ajax.reload();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡La familia que intenta registrar ya existe!",
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

let Mostar_Lista_Familias_Prov = (Id) => {
  $("#Id_Prov").val(Id);
  setTimeout(() => {
    Tbl_Fam_Prov = $("#Tbl_Fam_Prov")
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
          url: "../Archivos/Proveedores/operaciones.php?op=Mostrar_Lista_Familias_Prov",
          type: "post",
          dataType: "json",
          data: { Id },
          error: (e) => {
            console.log("Error función listar()\n" + e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 20,
        order: [[0, "asc"]],
      })
      .DataTable();
  }, 250);
};

let Datos_Fam_Prov = (Id) => {
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Datos_Fam_Prov",
    { Id },
    (result) => {
      result = JSON.parse(result);
      Swal.fire({
        position: "center",
        icon: "info",
        title: "¡Listo para modificar!",
        showConfirmButton: false,
        timer: 1000,
      });
      $("#Id_Fam").val(Id);
      $("#Familias").val(result.Id_Familia);
      $("#Familias").selectpicker("refresh");
    }
  );
};

let Eliminar_Fam_Prov = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de eliminar?",
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
          "../Archivos/Proveedores/operaciones.php?op=Eliminar_Fam_Prov",
          { Id },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Eliminado!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Fam_Prov.ajax.reload();
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
$("#Btn_Limpiar_Fam_Prov").on("click", function () {
  $("#Familias").val("");
  $("#Familias").selectpicker("refresh");
});

let Guardar_Familias = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Familias")[0]);
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
          url: "../Archivos/Proveedores/operaciones.php?op=Guardar_Familias",
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
              Tbl_Familias.ajax.reload();
              $("#Btn_Limpiar_Pr").click();
              Mostrar_Familias();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡La familia que intenta registrar ya existe!",
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

let Datos_Modificar_F = (Id_Proveedores) => {
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Datos_Modificar_F",
    { Id_Proveedores },
    (result) => {
      result = JSON.parse(result);
      //console.log(result);
      $("#Id_Proveedores").val(Id_Proveedores);
      $("#Nombre_Proveedores").val(result.Desc_Fam);
      $("#Ganancia").val(result.Ganancia);
    }
  );
};

let Mostrar_Lista_Familias = () => {
  setTimeout(() => {
    Tbl_Familias = $("#Tbl_Familias")
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
          url: "../Archivos/Proveedores/operaciones.php?op=Mostrar_Lista_Familias",
          type: "post",
          dataType: "json",
          error: (e) => {
            console.log("Error función listar()\n" + e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 20,
        order: [[0, "asc"]],
      })
      .DataTable();
  }, 250);
};

let Mostrar_Familias = () => {
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Mostrar_Familias",
    (result) => {
      $("#Familias").html(result);
      $("#Familias").selectpicker("refresh");
    }
  );
};
let Limpiar = () => {
  $("#Btn_Limpiar_Pr").click();
};

/**-------------------------------------------------------------- AGREGAR SUCURSALES ----------------------------------------------------------------------- */

let Mostar_Sucursales = (Id) => {
  $("#Id_PS").val(Id);
  setTimeout(() => {
    Tbl_Sucursales = $("#Tbl_Sucursales")
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
          url: "../Archivos/Proveedores/operaciones.php?op=Mostar_Tbl_Sucursales",
          type: "post",
          dataType: "json",
          data: { Id },
          error: (e) => {
            console.log("Error función listar()\n" + e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 20,
        order: [[0, "asc"]],
      })
      .DataTable();
  }, 250);
};

let Guardar_Sucursales = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Sucursales")[0]);
  Id = $("#Id_PS").val();
  data.append("Id", Id);
  //console.log(data);
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
          url: "../Archivos/Proveedores/operaciones.php?op=Guardar_Sucursales",
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
              Limpiar_FS();
              Tbl_Sucursales.ajax.reload();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡La familia que intenta registrar ya existe!",
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

let Datos_Sucursal = (Id) => {
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Datos_Sucursal",
    { Id },
    (result) => {
      result = JSON.parse(result);
      console.log(result);
      $("#Id_Sucursal").val(Id);
      $("#Nombre_Sucursal").val(result.Nombre);
      $("#Nombre_contacto").val(result.P_Contacto);
      $("#Nombre_Contacto2").val(result.S_Contacto);
      $("#Calle_Sucursal").val(result.Calle);
      $("#Numero_Exterior").val(result.N_Exterior);
      $("#Numero_Interior").val(result.N_Interior);
      $("#Colonia").val(result.Colonia);
      $("#Codigo_Postal").val(result.CP);
      $("#Estado").val(result.Id_Estado);

      $("#Celular").val(result.Celular);
      $("#Telefono").val(result.Telefono);
      $("#Correo").val(result.Correo_C);
      $("#Correo_P").val(result.Correo_P);
      Mostrar_Municipios();
      setTimeout(() => {
        $("#Municipio").val(result.Id_Municipios);
        $("#Municipio").selectpicker("refresh");
      }, 300);
      $("#Estado").selectpicker("refresh");
    }
  );
};

let Mostrar_Estados = () => {
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Mostrar_Estados",
    (result) => {
      //console.log(result);
      $("#Estado").html(result);
      $("#Estado").selectpicker("refresh");
    }
  );
};

let Mostrar_Municipios = () => {
  Id = $("#Estado").val();
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Mostrar_Municipios",
    { Id },
    (result) => {
      //console.log(result);
      $("#Municipio").html(result);
      $("#Municipio").selectpicker("refresh");
    }
  );
};

let Baja_Sucursal = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja la sucursal?",
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
          "../Archivos/Proveedores/operaciones.php?op=Baja_Sucursal",
          { Id },
          (result) => {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡La sucursal se dio de baja!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Sucursales.ajax.reload();
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

let Reactivar_Sucursal = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de reactivar la sucursal?",
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
          "../Archivos/Proveedores/operaciones.php?op=Reactivar_Sucursal",
          { Id },
          (result) => {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡La sucursal se reactivo!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Sucursales.ajax.reload();
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

let Limpiar_FS = () => {
  $("#Id_Sucursal").val("");
  $("#Nombre_Sucursal").val("");
  $("#Nombre_contacto").val("");
  $("#Nombre_Contacto2").val("");
  $("#Calle_Sucursal").val("");
  $("#Numero_Exterior").val("");
  $("#Numero_Interior").val("");
  $("#Colonia").val("");
  $("#Codigo_Postal").val("");
  $("#Estado").val("");
  $("#Municipio").html("");
  $("#Celular").val("");
  $("#Telefono").val("");
  $("#Correo").val("");
  $("#Correo_P").val("");
  $("#Municipio").selectpicker("refresh");
  $("#Estado").selectpicker("refresh");
};

/**-------------------------------------------------------------- DATOS BANCARIOS ----------------------------------------------------------------------- */

let Guardar_D_Bancarios = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Bancos")[0]);
  Id = $("#Id_PB").val();
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
          url: "../Archivos/Proveedores/operaciones.php?op=Guardar_D_Bancarios",
          data: data,
          contentType: false,
          processData: false,
          success: function (result) {
            // console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Guardado!",
                showConfirmButton: false,
                timer: 2500,
              });
              Limpiar_DB();
              Tbl_DBancarios.ajax.reload();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡Los datos bancarios que intenta registrar ya existe!",
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

let Mostrar_Bancos = () => {
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Mostrar_Bancos",
    (result) => {
      $("#Banco").html(result);
      $("#Banco").selectpicker("refresh");
    }
  );
};
let Mostar_D_Bancarios = (Id) => {
  $("#Id_PB").val(Id);

  setTimeout(() => {
    Tbl_DBancarios = $("#Tbl_DBancarios")
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
          url: "../Archivos/Proveedores/operaciones.php?op=Mostar_Tbl_D_Bancarios",
          type: "post",
          dataType: "json",
          data: { Id },
          error: (e) => {
            console.log("Error función listar()\n" + e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 20,
        order: [[0, "asc"]],
      })
      .DataTable();
  }, 250);
};

let Datos_D_Editar = (Id) => {
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Datos_D_Editar",
    { Id },
    (result) => {
      result = JSON.parse(result);
      //console.log(result);
      $("#Id_DBancarios").val(Id);
      $("#Banco").val(result.Id_Banco);
      $("#Sucursal_Banco").val(result.Sucursal);
      $("#Cuenta_Banco").val(result.Cuenta);
      $("#Clave_Banco").val(result.Clave);
      $("#Referencia").val(result.Referencia);
      $("#Banco").selectpicker("refresh");
    }
  );
};
let Baja_DBancarios = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja los datos bancarios?",
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
          "../Archivos/Proveedores/operaciones.php?op=Baja_DBancarios",
          { Id },
          (result) => {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Los datos se dieron de baja!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_DBancarios.ajax.reload();
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

let Reactivar_DBancarios = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de reactivar los datos bancarios?",
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
          "../Archivos/Proveedores/operaciones.php?op=Reactivar_DBancarios",
          { Id },
          (result) => {
            console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Los datos se dieron reactivados!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_DBancarios.ajax.reload();
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

let Limpiar_DB = () => {
  $("#Id_DBancarios").val("");
  $("#Banco").val("");
  $("#Sucursal_Banco").val("");
  $("#Cuenta_Banco").val("");
  $("#Clave_Banco").val("");
  $("#Referencia").val("");
  $("#Banco").selectpicker("refresh");
};

/**-------------------------------------------------------------- Guardar Bancos ----------------------------------------------------------------------- */

let Guardar_A_Bancos = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_A_Bancos")[0]);
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
          url: "../Archivos/Proveedores/operaciones.php?op=Guardar_A_Bancos",
          data: data,
          contentType: false,
          processData: false,
          success: function (result) {
            // console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Guardado!",
                showConfirmButton: false,
                timer: 2500,
              });
              Limpiar_FB();
              Tbl_Lista_Bancos.ajax.reload();
              Mostrar_Bancos();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡El banco que intenta registrar ya existe!",
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

let Buscar_Lista_Bancos = () => {
  setTimeout(() => {
    Tbl_Lista_Bancos = $("#Tbl_Lista_Bancos")
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
          url: "../Archivos/Proveedores/operaciones.php?op=Buscar_Lista_Bancos",
          type: "post",
          dataType: "json",
          error: (e) => {
            console.log("Error función listar()\n" + e.responseText);
          },
        },
        bDestroy: true,
        iDisplayLength: 20,
        order: [[0, "asc"]],
      })
      .DataTable();
  }, 250);
};

let Datos_M_Banco = (Id) => {
  $.post(
    "../Archivos/Proveedores/operaciones.php?op=Datos_M_Banco",
    { Id },
    (result) => {
      result = JSON.parse(result);
      $("#Id_Bancos").val(Id);
      $("#Nombre_Banco").val(result.Nombre);
    }
  );
};
let Limpiar_FB = () => {
  $("#Id_Bancos").val("");
  $("#Nombre_Banco").val("");
};
