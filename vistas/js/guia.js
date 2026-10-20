$(document).ready(function () {
  document.getElementById("modalidadTraslado").value = "02";
  document.getElementById("tipoDocTransporte").value = "1";
});

$(".resultado-ubigeos-partida").hide();
$(".resultado-ubigeos-llegada").hide();
$(".resultado-serie").hide();
// LISTAR PRODUCTOS PARA AGREGAR AL CARRO CON BUSCADOR
function loadProductosG(page) {
  let searchProductoG = $("#searchProductoG").val();
  let selectnum = $("#selectnum").val();
  let parametros = {
    action: "ajax",
    page: page,
    searchProductoG: searchProductoG,
    selectnum: selectnum,
    dpg: "dpg",
  };

  $.ajax({
    url: "vistas/tables/dataTables.php",
    // method: 'GET',
    data: parametros,
    // cache: false,
    // contentType: false,
    // processData: false,
    beforeSend: function () {
      //   $("#modalProductosVenta").append(loadcar);
    },
    success: function (data) {
      $(".reloadc").hide();
      $(".body-productos-guia").html(data);
    },
  });
}
loadProductosG(1);

// AGREGAR PRODUCTOS AL CARRO
$(document).on("click", "button.agregarProductoGuia", function () {
  let descripcionProducto = $(this).attr("descripcionP");
  let idProducto = $(this).attr("idProducto");
  let cantidad = $("#cantidad" + idProducto).val();

  let datos = { idProducto: idProducto, cantidad: cantidad };

  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    success: function (respuesta) {
      $(".nuevoProducto table #itemsPG").html(respuesta);

      const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        // width: 600,
        // padding: '3em',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener("mouseenter", Swal.stopTimer);
          toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
      });

      Toast.fire({
        icon: "success",
        title: `<h5>Se ha agregado al carrito</h5>`,
        html: `<div style="font-size: 1.5em; color: #2B5DD2;"><i class="fas fa-shopping-cart"></i> ${descripcionProducto}</div`,
      });
      // comillas invertidas  (``);
      $(".contenedor-items").fadeIn(200);
      $(".tablaVentas thead").fadeIn(200);
    },
  });
});

$(document).on("change", "#modalidadTraslado", function () {
  var motivo = $(this).val();
  //(motivo);
  if (motivo == "01") {
    document.getElementById("tipoDocTransporte").value = "6";
    $("#formGuia .docTransporte").html(
      `N° RUC Empresa Transporte <span style="color:red; border-style: none !important; font-size:20px;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Razón Social <span style="color:red; border-style: none !important; font-size:20px;">*</span>`
    );
    $("#formGuia #docTransporte").val("");
    $("#formGuia #nombreRazon").val("");
    $("#formGuia #apellidosRazon").val("");
    $(".placa-v #placa").val("");
    $(".placa-v").hide();
    $(".input-group-c-apellidos").hide();
    $(".nombre-razon").removeClass("col-md-4");
    $(".nombre-razon").addClass("col-md-6");
    $("#tipoVehiculo").prop("disabled", true);
  } else {
    document.getElementById("tipoDocTransporte").value = "1";
    $("#formGuia .docTransporte").html(
      `N° DNI Conductor <span style="color:red; border-style: none !important; font-size:20px;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Nombre Conductor <span style="color:red; border-style: none !important; font-size:20px;">*</span>`
    );
    $("#formGuia #docTransporte").val("");
    $("#formGuia #nombreRazon").val("");
    $(".placa-v").show();
    $(".input-group-c-apellidos").show();
    $(".nombre-razon").removeClass("col-md-6");
    $(".nombre-razon").addClass("col-md-4");
    $("#tipoVehiculo").prop("disabled", false);
  }
});
$(document).on("change", "#tipoDocTransporte", function () {
  var tipoDoc = $(this).val();

  if (tipoDoc != 1 || tipoDoc != 6) {
    $("#formGuia .docTransporte").html(
      `N° Doc Conductor <span style="color:red; border-style: none !important;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Nombre Conductor <span style="color:red; border-style: none !important;">*</span>`
    );
  }
  if (tipoDoc == 1) {
    $("#formGuia .docTransporte").html(
      `N° DNI Conductor <span style="color:red; border-style: none !important;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Nombre Conductor <span style="color:red; border-style: none !important;">*</span>`
    );
  }
  if (tipoDoc == 6) {
    $("#formGuia .docTransporte").html(
      `N° RUC Empresa Transporte <span style="color:red; border-style: none !important;">*</span>`
    );
    $("#formGuia .nombreRazon").html(
      `Razón Social <span style="color:red; border-style: none !important;">*</span>`
    );
  }
});
$(document).on("change", "#tipoVehiculo", function () {
  tipoVehiculo = $(this).val();
  if (tipoVehiculo != "otros") {
    $(".datos-del-transporte").hide();
  } else {
    $(".datos-del-transporte").show();
  }
});
$(document).on("click", ".btnGuardarGuia", function (e) {
  let dataForm = $("#formGuia").serialize();
  Swal.fire({
    title: "¿Estás seguro en guardar el comprobante?",
    text: "¡Verifica todo antes de confirmar!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, guardar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        method: "POST",
        url: "ajax/crear-guia.ajax.php",
        data: dataForm,
        beforeSend: function () {
          $(".reload-all")
            .fadeIn(50)
            .html("<img src='vistas/img/reload.svg' width='80px'> ");
        },
        success: function (data) {
          Swal.fire({
            icon: "success",
            title: "",
            text: "",
            html: '<div id="successG"></div>',
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Cerrar",
          });
          $(".reload-all").fadeOut(50);
          $("#successG").html(data);
        },
      });
    }
  });
});

$(document).on("keyup", "#ubigeoPartida", function (e) {
  let ubigeopartida = $(this).val();
  let datos = { ubigeopartida: ubigeopartida };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (data) {
      if (ubigeopartida == "") {
        $(".resultado-ubigeos-partida").hide();
      } else {
        $(".resultado-ubigeos-partida").show().html(data);
      }
    },
  });
});
$(document).on("keyup", "#ubigeoLlegada", function (e) {
  let ubigeollegada = $(this).val();
  let datos = { ubigeollegada: ubigeollegada };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (data) {
      if (ubigeollegada == "") {
        $(".resultado-ubigeos-llegada").hide();
      } else {
        $(".resultado-ubigeos-llegada").show().html(data);
      }
    },
  });
});
$(document).on("click", ".btn-ubigeo-partida", function (e) {
  e.preventDefault();
  let codUbigeo = $(this).attr("idUbigeo");
  let datos = { codUbigeo: codUbigeo };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#ubigeoPartida").val(data["id"]);
      $(".resultado-ubigeos-partida").hide();
    },
  });
});
$(document).on("click", ".btn-ubigeo-llegada", function (e) {
  e.preventDefault();
  let codUbigeo = $(this).attr("idUbigeo");
  let datos = { codUbigeo: codUbigeo };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {},
    success: function (data) {
      $("#ubigeoLlegada").val(data["id"]);
      $(".resultado-ubigeos-llegada").hide();
    },
  });
});
$(".buscarDniRuc").on("click", function () {
  let rucCliente = $("#docTransporte").val();
  let tipoDoc = $("#tipoDocTransporte ").val();
  let datos = { rucCliente: rucCliente, tipoDoc: tipoDoc };
  $.ajax({
    method: "POST",
    url: "ajax/clientes.ajax.php",
    data: datos,
    dataType: "json",
    beforeSend: function () {
      if (rucCliente != "") {
        $("#reloadCG").show(5).html("<img src='vistas/img/reload.svg'> ");
        document.getElementById("reloadCG").style.visibility = "visible";
      }
    },
    success: function (respuesta) {
      if (respuesta != "error") {
        $("#reloadCG").hide();

        if (tipoDoc == 6) {
          $("#docTransporte").val(respuesta["ruc"]);
          $("#nombreRazon").val(respuesta["razon_social"]);
        } else {
          $("#docTransporte").val(respuesta["ruc"]);
          $("#nombreRazon").val(respuesta["nombres"]);
          $("#apellidosRazon").val(respuesta["apellidos"]);
        }

        document.getElementById("reloadC").style.visibility = "hidden";
      } else {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "El DNI/RUC no se encuentra",
          showConfirmButton: false,
          timer: 2500,
        });
      }
    },
  });
});

$(document).on("keyup", "#serieCorrelativoReferencial", function (e) {
  let serieCorrelativo = $(this).val();
  let datos = { serieCorrelativo: serieCorrelativo };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (respuesta) {
      $(".resultado-serie").show().html(respuesta);
    },
  });
});
$(document).on("click", ".btn-serie-correlativo", function (e) {
  e.preventDefault();
  let numCorrelativo = $(this).attr("numCorrelativo");
  let datos = { numCorrelativo: numCorrelativo };
  $.ajax({
    method: "POST",
    url: "ajax/crear-guia.ajax.php",
    data: datos,
    beforeSend: function () {},
    success: function (respuesta) {
      $(".nuevoProducto .table #itemsPG").html(respuesta);
      $(".resultado-serie").hide();
      $("#serieCorrelativoReferencial").val(numCorrelativo);
    },
  });
});

function loadGuiasR(page) {
  var searchGuias = $("#searchGuias").val();
  var selectnum = $("#selectnum").val();
  var fechaInicial = $("#fechaInicial").val();
  var fechaFinal = $("#fechaFinal").val();
  var parametros = {
    action: "ajax",
    page: page,
    searchGuias: searchGuias,
    selectnum: selectnum,
    lig: "lig",
    fechaInicial: fechaInicial,
    fechaFinal: fechaFinal,
  };

  $.ajax({
    url: "vistas/tables/dataTables-guias.php",
    // method: 'GET',
    data: parametros,

    beforeSend: function () {
      //   $("#modalProductosVenta").append(loadcar);
    },
    success: function (data) {
      //   $(".reloadc").hide();
      $(".body-listaguias").html(data);
    },
  });
}

loadGuiasR(1);

$("#formGuia").on("keyup", "#placa", function () {
  var placa = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(placa)) {
    //dni = dni.substr(0,(dni.length -1));
    placa = placa.replace(/[^a-zA-Z0-9]/g, "");
    $("#placa").val(placa);
  }
});

$("#formGuia").on("keyup", "#numBrevete", function () {
  var numBrevete = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(numBrevete)) {
    //dni = dni.substr(0,(dni.length -1));
    numBrevete = numBrevete.replace(/[^a-zA-Z0-9]/g, "");
    $("#numBrevete").val(numBrevete);
  }
});
$("#formGuia").on("keyup", "#direccionPartida", function () {
  var placa = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(placa)) {
    //dni = dni.substr(0,(dni.length -1));
    placa = placa.replace(/[^a-zA-Z0-9 ]/g, "");
    $("#direccionPartida").val(placa);
  }
});
$("#formGuia").on("keyup", "#direccionLlegada", function () {
  var placa = $(this).val();

  //this.value = (this.value + '').replace(/[^0-9]/g, '');
  if (!$.isNumeric(placa)) {
    //dni = dni.substr(0,(dni.length -1));
    placa = placa.replace(/[^a-zA-Z0-9 ]/g, "");
    $("#direccionLlegada").val(placa);
  }
});
