let Tbl_Areas;
let Tbl_Empleados;
let Tbl_Usuarios;
$(document).ready(() => {
  $("#Agregar_Usuarios").on("submit", function (e) {
    Guardar_Empleado(e);
  });

  $("#Form_Areas").on("submit", function (e) {
    Guardar_Areas(e);
  });

  $("#Form_Usuarios").on("submit", function (e) {
    Guardar_Usuario(e);
  });

  $("#A_Permisos_U").on("submit", function (e) {
    Guardar_Permisos(e);
  });

  Mostrar_Lista_Empleados();
  Mostrar_Estados();
  Mostrar_Empleados();
});

let Guardar_Empleado = (e) => {
  e.preventDefault();
  let data = new FormData($("#Agregar_Usuarios")[0]);
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
          url: "../Archivos/Empleados/Operaciones.php?op=Guardar_Actualizar_Empleados",
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
              Tbl_Empleados.ajax.reload();
              $("#Btn_Limpiar_AE").click();
              Mostrar_Empleados();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡El empleado que intenta registrar ya existe!",
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

let Datos_A_Editar = (Id) => {
  //console.log(Id);
  $.post(
    "../Archivos/Empleados/Operaciones.php?op=Datos_A_Editar",
    { Id },
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
      $("#Estado").val(result.Id_Estado);
      $("#Calle").val(result.Calle);
      $("#N_Exterior").val(result.N_Exterior);
      $("#N_Interior").val(result.N_Interior);
      $("#Colonia").val(result.Colonia);
      $("#CP").val(result.Codigo_P);
      $("#Id").val(Id);
      $("#Nombre").val(result.Nombre);
      $("#A_Paterno").val(result.Apellido_P);
      $("#A_Materno").val(result.Apellido_M);
      $("#Genero").val(result.Genero);
      $("#FRC").val(result.RFC);
      $("#Curp").val(result.Curp);
      $("#N_Seguro").val(result.N_Social);
      $("#Correo").val(result.Correo);
      $("#Celular").val(result.Celular);
      $("#Telefono").val(result.Telefono);
      $("#F_Ingreso").val(result.Fecha_Ingreso);
      $("#Observaciones").val(result.Observaciones);

      Buscar_Municipios();

      setTimeout(() => {
        $("#Municipio").val(result.Id_Municipios);
        $("#Municipio").selectpicker("refresh");
      }, 300);
      $("#Estado").selectpicker("refresh");
      $("#Genero").selectpicker("refresh");
    }
  );
};

let Baja_Empleado = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja al empleado?",
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
          "../Archivos/Empleados/Operaciones.php?op=Baja_Empleado",
          { Id },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Empleado dado de baja!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Empleados.ajax.reload();
              Mostrar_Empleados();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, inténtelo mas tarde!",
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
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Reactivar_Empleado = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de reactivar al empleado?",
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
          "../Archivos/Empleados/Operaciones.php?op=Reactivar_Empleado",
          { Id },
          (result) => {
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Empleado dado de baja!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Empleados.ajax.reload();
              Mostrar_Empleados();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, inténtelo mas tarde!",
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
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Mostrar_Lista_Empleados = () => {
  Tbl_Empleados = $("#Tbl_Empleados")
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
        url: "../Archivos/Empleados/Operaciones.php?op=Mostrar_Tbl_Empleados",
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
};

let Mostrar_Estados = () => {
  //console.log("Estados");
  $.post(
    "../Archivos/Empleados/Operaciones.php?op=Mostrar_Estados",
    (result) => {
      //console.log(result);
      $("#Estado").html(result);
      $("#Estado").selectpicker("refresh");
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

let Mostrar_Empleados = () => {
  $.post(
    "../Archivos/Empleados/Operaciones.php?op=Mostrar_Empleados",
    (result) => {
      $("#Nombre_Emp").html(result);
      $("#Nombre_Emp").selectpicker("refresh");
    }
  );
};
$("#Btn_Limpiar_AE").on("click", function () {
  $("#Genero").val("");
  $("#Estado").val("");
  $("#Municipio").html("");
  $("#Municipio").selectpicker("refresh");
  $("#Genero").selectpicker("refresh");
  $("#Estado").selectpicker("refresh");
});

/**---------------------------------------------------------- USUARIOS --------------------------------------------------------------------------- */

let Guardar_Usuario = (e) => {
  e.preventDefault();
  let data = new FormData($("#Form_Usuarios")[0]);
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
          url: "../Archivos/Empleados/Operaciones.php?op=Guardar_Usuario",
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
              Tbl_Usuarios.ajax.reload();
              Boton_Limpiar_UE();
              //Mostrar_Empleados();
            } else if (result == 202) {
              Swal.fire({
                position: "center",
                icon: "warning",
                title: "¡El usuario que intenta registrar ya existe!",
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

let Datos_A_Editar_Usuarios = (Id) => {
  $.post(
    "../Archivos/Empleados/Operaciones.php?op=Datos_A_Editar_Usuarios",
    { Id },
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
      $("#Id_Usuario").val(Id);
      $("#Nombre_Emp").val(result.Id_Empleado);
      $("#Nombre_Usuario").val(result.Usuario);
      $("#Rol").val(result.Rol);
      $("#Nombre_Emp").selectpicker("refresh");
      $("#Rol").selectpicker("refresh");
    }
  );
};
let Mostrar_Lista_Usuarios = () => {
  setTimeout(() => {
    Tbl_Usuarios = $("#Tbl_Usuarios")
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
          url: "../Archivos/Empleados/Operaciones.php?op=Mostrar_Lista_Usuarios",
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
  }, 350);
};

let Baja_Usuario = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja al usuario?",
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
          "../Archivos/Empleados/Operaciones.php?op=Baja_Usuario",
          { Id },
          (result) => {
            //console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Usuario dado de baja!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Usuarios.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, inténtelo mas tarde!",
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
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Reactivar_Usuario = (Id) => {
  Swal.fire({
    title: "¿Estás seguro(a) de dar de baja al usuario?",
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
          "../Archivos/Empleados/Operaciones.php?op=Reactivar_Usuario",
          { Id },
          (result) => {
            //console.log(result);
            if (result == 200) {
              Swal.fire({
                position: "center",
                icon: "success",
                title: "Usuario dado de baja!",
                showConfirmButton: false,
                timer: 2500,
              });
              Tbl_Usuarios.ajax.reload();
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Error, inténtelo mas tarde!",
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
        title: "¡Operación cancelada!",
        showConfirmButton: false,
        timer: 1500,
      });
    }
  });
};

let Boton_Limpiar_UE = () => {
  $("#Rol").val("");
  $("#Nombre_Emp").val("");
  $("#Nombre_Emp").selectpicker("refresh");
  $("#Rol").selectpicker("refresh");
  $("#Id_Usuario").val("");
  $("#Nombre_Emp").val("");
  $("#Nombre_Usuario").val("");
  $("#Contraseña").val("");
};

let Mostrar_Permisos = (Id) => {
  $("#Id_Usuario_P").val(Id);
  $.post(
    "../Archivos/Empleados/Operaciones.php?op=Mostrar_Permisos",
    { Id },
    (result) => {
      //console.log(result);
      $("#Div_Permisos").html(result);
    }
  );
};

let Guardar_Permisos = (e) => {
  e.preventDefault();
  let data = new FormData($("#A_Permisos_U")[0]);

  let Permisos_A = [];
  $("#A_Permisos_U input[type='checkbox']").each(function () {
    if ($(this).is(":checked")) {
      Permisos_A.push($(this).val());
    }
  });

  if (Permisos_A.length > 0) {
    data.append("Id", $("#Id_Usuario_P").val());
    data.append("Permisos_A", Permisos_A);
  }

  if (Permisos_A.length > 0) {
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
            url: "../Archivos/Empleados/Operaciones.php?op=Guardar_Permisos",
            data: data,
            contentType: false,
            processData: false,
            success: function (result) {
              if (result == 200) {
                Swal.fire({
                  position: "center",
                  icon: "success",
                  title: "¡Permisos guardados!",
                  showConfirmButton: false,
                  timer: 2500,
                });
                Tbl_Usuarios.ajax.reload();
              } else {
                Swal.fire({
                  position: "center",
                  icon: "error",
                  title: "¡Error, inténtelo mas tarde!",
                  showConfirmButton: false,
                  timer: 2500,
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
  } else {
    Swal.fire({
      position: "center",
      icon: "info",
      title: "Es necesario seleccionar almeno un permiso!",
      showConfirmButton: false,
      timer: 1500,
    });
  }
};
