<div class="content-wrapper panel-medio-principal">
  <?php

  use Controladores\ControladorEmpresa;

  $emisor = ControladorEmpresa::ctrEmisor();

  ?>
  <input type="hidden" id="id_empresa" name="id_empresa" value="<?php echo $emisor['id'] ?>">
  <input type="hidden" id="empresa_igv" name="empresa_igv" value="<?php echo $emisor['afectoigv'] ?>">
  <?php
  if ($_SESSION['perfil'] == 'Vendedor') {

    echo '
      <section class="container-fluid panel-medio">
      <div class="box alert-dangers text-center">
     <div><h3> Área restringida, solo el administrador puede tener acceso</h3></div>
    <div class="img-restringido"></div>
     
     </div>
     </div>';
  } else {


  ?>
    <div style="padding:5px"></div>
    <section class="container-fluid">
      <section class="content-header dashboard-header">

        <div class="box container-fluid" style="border:0px; margin:0px; padding:0px;">
          <div class="col-lg-12 col-xs-12" style="border:0px; margin:0px; padding:0px; border-radius:10px;">

            <div class="col-md-3 hidden-sm hidden-xs">
              <button class=""><i class="fas fa-bars"></i>Configuración de Empresa</button>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 btns-dash">
              <a href="crear-factura" class="btn pull-right" style="margin-left:10px"><i class="fas fa-file-invoice"></i> Emitir factura</a>
              <a href="crear-boleta" class="btn pull-right"><i class="fas fa-file-invoice"> </i> Emitir boleta</a>
            </div>
          </div>
        </div>
      </section>
    </section>



    <!-- <section class="content"> -->
    <section class="container-fluid panel-medio">
      <!-- BOX INI -->
      <div class="box rounded " style="margin:0px; padding:0px;">

        <div class="box-header ">
          <!-- ENTRADA PARA SUBIR FOTO -->

          <div class="img-contenedor-logo">
            <form action="" id="formLogo" method="post" enctype="multipart/form-data">

              <label for="editarLogo">
                <?php if (isset($emisor['logo']) && $emisor['logo'] == '') {
                  echo '<img src="vistas/img/logo/default/logo.svg" class="img-thumbnail previsualizar" width="140px">';
                } else {
                  $rand = rand(22, 99999);
                  echo '<img src="vistas/img/logo/' . $emisor['logo'] . '?n=' . $rand . '" class="img-thumbnail previsualizar" width="150px">';
                } ?>

              </label>
              <div class="panel" style="text-align: center; font-weight: bold; letter-spacing:1px; font-size: 14px">SUBIR LOGO <br />

                <button class="btn btn-danger eliminar-logo" idEmpresa="<?php echo $emisor['id'] ?>"><i class="fas fa-trash-alt"></i>
                  <i class="far fa-image"></i></button>
              </div>


              <input type="file" class="nuevoLogo" name="editarLogo" id="editarLogo">
              <input type="hidden" name="logoActual" id="logoActual" value="<?php echo $emisor['logo']; ?>">
            </form>
          </div>

          <div class="box-tools pull-right ">
            <div class="modo-contenedor">
              <label for="si" id="sim">Producción</label>
              <input type="radio" class="modo" id="si" name="modo" value="s">
              <label for="no" id="nom">Prueba</label>
              <input type="radio" class="modo mno" id="no" name="modo" value="n">
            </div>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="margin:0px; padding:0px;">
          <div class="box" style="margin-top:5px;">



            <form action="" id="formEmpresa" method="post" enctype="multipart/form-data">
              <input type="hidden" name="idEmisor" id="idEmisor" value="<?php echo $emisor['id']; ?>">

              <div class="col col-xs-12">
                <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                    <h3>1</h3>
                  </label> Configuración del R.U.C.:</legend>

                <!-- ENTRADA 1 -->
                <div class="col-md-4">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-address-card"></i></span>
                      <input type="text" class="form-control ruc-empresa" id="ruc" name="ruc" placeholder="Ruc..." value="<?php echo $emisor['ruc']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 2-->
                <div class="col-md-8">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-address-card"></i></span>
                      <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón Social..." value="<?php echo $emisor['razon_social']; ?>">
                    </div>
                  </div>
                </div>

              </div>
              <!-- SEGUNDA ENTRADA} -->
              <div class="col col-xs-12">
                <!-- ENTRADA 1-->
                <div class="col-md-5">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                      <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección..." value="<?php echo $emisor['direccion']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 2 -->
                <div class="col-md-5">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-address-card"></i></span>
                      <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" placeholder="Nombre comercial..." value="<?php echo $emisor['nombre_comercial']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 3 -->
                <div class="col-md-2">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                      <input type="text" class="form-control" id="ubigeo" name="ubigeo" placeholder="Ubigeo..." value="<?php echo $emisor['ubigeo']; ?>">
                    </div>
                  </div>
                </div>
              </div>


              <!-- TERCERA ENTRADA -->
              <div class="col col-xs-12">

                <!-- ENTRADA 1 -->
                <div class="col-md-3">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-flag"></i></span>
                      <input type="text" class="form-control" id="pais" name="pais" placeholder="País..." value="<?php echo $emisor['pais']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 2 -->
                <div class="col-md-3">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                      <input type="text" class="form-control" id="departamento" name="departamento" placeholder="Departamento..." value="<?php echo $emisor['departamento']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 3 -->
                <div class="col-md-3">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                      <input type="text" class="form-control" id="provincia" name="provincia" placeholder="Província..." value="<?php echo $emisor['provincia']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 4-->
                <div class="col-md-3">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-map-marker-alt"></i></span>
                      <input type="text" class="form-control" id="distrito" name="distrito" placeholder="Distrito..." value="<?php echo $emisor['distrito']; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="col col-xs-12">
                <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                    <h3>2</h3>
                  </label> Configuración para producción:</legend>
                <!-- ENTRADA 1 -->
                <div class="col-md-2">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <label for="" class="lbl-text">Celular</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                      <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Província..." value="<?php echo $emisor['telefono']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 2 -->
                <div class="col-md-2">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <label for="" class="lbl-text">Usuario sol SUNAT</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="text" class="form-control" id="usuario_sol" name="usuario_sol" placeholder="Usuario sol..." value="<?php echo $emisor['usuario_sol']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 3-->
                <div class="col-md-2">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <label for="" class="lbl-text">Clave sol SUNAT</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="password" class="form-control" id="clave_sol" name="clave_sol" placeholder="Clave sol..." value="<?php echo $emisor['clave_sol']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 3-->
                <div class="col-md-2">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <label for="" class="lbl-text">Clave certificado CDT</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="password" class="form-control" id="clave_certificado" name="clave_certificado" placeholder="Clave certificado..." value="<?php echo $emisor['clave_certificado']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 4-->
                <div class="col-md-2">

                  <div id="reloadC"></div>
                  <div class="form-group">
                    <label for="" class="lbl-text">¿Afecto a IGV?</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-exchange-alt"></i></span>

                      <select class="form-control" name="afectoigv" id="afectoigv">
                        <option value="s">Si</option>
                        <option value="n">No</option>
                      </select>

                    </div>
                  </div>
                </div>





                <div class="col-md-2">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <label for="" class="lbl-text">IGV</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-">%</i></span>
                      <input type="text" class="form-control" id="igvp" name="igvp" placeholder="Província..." value="<?php echo $emisor['igv']; ?>">
                    </div>
                  </div>
                </div>
              </div>


              <div class="col col-xs-12">
                <!-- ENTRADA 2 -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="" class="lbl-text">Cliente id</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="text" class="form-control" id="client_id" name="client_id" placeholder="Cliente id..." value="<?php echo $emisor['client_id']; ?>">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="" class="lbl-text">Client secret</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="text" class="form-control" id="secret_id" name="secret_id" placeholder="Client secret..." value="<?php echo $emisor['secret_id']; ?>">
                    </div>
                  </div>
                </div>
              </div>


              <!-- BIENES Y SERVICIOS REGION SELVA -->
              <div class="col-xs-12">
                <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                    <h3>3</h3>
                  </label> Configuración para la Amazonia:</legend>
                <div class="contenedor-selva">

                  <div class="modo-contenedor-selva bienes-selva">
                    <label for="">¿Bienes Región Selva?</label>
                    <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" data-onstyle="success" data-offstyle="danger" id="bienesSelva" name="bienesSelva" value="s" data-size="small" data-width="80" <?php if ($emisor['bienesSelva'] == 'n') {
                                                                                                                                                                                                                          } else { ?>checked <?php } ?>>
                  </div>


                  <div class="modo-contenedor-selva servicios-selva">
                    <label for="">¿Servicios Región Selva?</label>
                    <input type="checkbox" data-toggle="toggle" data-on="Sí" data-off="No" data-onstyle="success" data-offstyle="danger" id="serviciosSelva" name="serviciosSelva" value="s" data-size="small" data-width="80" <?php if ($emisor['serviciosSelva'] == 'n') {
                                                                                                                                                                                                                                } else { ?>checked <?php } ?>>
                  </div>
                </div>
              </div>
              <!-- ÁREA DE CORREOS====================== -->
              <div class="col col-xs-12">

                <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                    <h3>4</h3>
                  </label> Configuración de envío de correo:</legend>
                <!-- ENTRADA 1 -->
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-at"></i></span>
                      <input type="text" class="form-control" id="correo_ventas" name="correo_ventas" placeholder="Ingrese correo de ventas..." value="<?php echo @$emisor['correo_ventas']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 2 -->
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-at"></i></span>
                      <input type="text" class="form-control" id="correo_soporte" name="correo_soporte" placeholder="Ingrese correo de soporte técnico..." value="<?php echo @$emisor['correo_soporte']; ?>">
                    </div>
                  </div>
                </div>

              </div>

              <!-- ÁREA DE CONFIGURACION WEBMAIL====================== -->
              <div class="col col-xs-12">
                <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;">Configuración de envío de emails:</legend>
                <!-- ENTRADA 1 -->
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-network-wired"></i></span>
                      <input type="text" class="form-control" id="servidor" name="servidor" placeholder="Ingrese servidor..." value="<?php echo @$emisor['servidor']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 2 -->
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-key"></i></span>
                      <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese contraseña..." value="<?php echo @$emisor['contrasena']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 3 -->
                <div class="col-md-2">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-wifi"></i></span>
                      <input type="text" class="form-control" id="puerto" name="puerto" placeholder="Ingrese puerto..." value="<?php echo @$emisor['puerto']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 4-->
                <div class="col-md-2">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-lock"></i></span>

                      <select class="form-control seguridad-envio" idSeguridad="<?php echo @$emisor['seguridad'];  ?>" name="seguridad" id="seguridad">
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                      </select>

                    </div>
                  </div>
                </div>
                <!-- ENTRADA 5-->
                <div class="col-md-2">
                  <div id="reloadC"></div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fas fa-wifi"></i></span>

                      <select class="form-control tipo-envio" idtipoEnvio="<?php echo @$emisor['tipo_envio'];  ?>" name="tipo_envio" id="tipo_envio">
                        <option value="smtp">isSMTP</option>
                        <option value="mail">isMAIL</option>
                      </select>

                    </div>
                  </div>
                </div>

              </div>
              <!-- ÁREA DE CONFIGURACION RECAPTCHA====================== -->
              <div class="col col-xs-12">

                <legend class="text-bold" style="margin-left:15px; font-size:1.3em; letter-spacing: 1px;"><label class="number-guiar">
                    <h3>5</h3>
                  </label> Configurar seguridad RECAPTCHA:</legend>
                <!-- ENTRADA 1 -->
                <div class="col-md-2">
                  <label for="" class="lbl-text">activar RECAPTCHA</label>
                  <select class="form-control rseguridad" idSeguridad="<?php echo @$emisor['conexion'];  ?>" name="rseguridad" id="rseguridad">
                    <option value="s">Sí</option>
                    <option value="n">No</option>
                  </select>
                </div>
                <!-- ENTRADA 2 -->
                <div class="col-md-5">
                  <label for="" class="lbl-text">Clave de Sitio web</label>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="text" class="form-control" id="clavePublica" name="clavePublica" placeholder="Ingrese clave web..." value="<?php echo @$emisor['clavePublica']; ?>">
                    </div>
                  </div>
                </div>
                <!-- ENTRADA 3 -->
                <div class="col-md-5">
                  <label for="" class="lbl-text">Clave Secreta</label>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                      <input type="text" class="form-control" id="clavePrivada" name="clavePrivada" placeholder="Ingrese clave secreta..." value="<?php echo @$emisor['clavePrivada']; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="contenedor-archivo">
                <span>SUBIR TU CERTIFICADO</span>
                <label for="certificado"></label>
                <input type="hidden" id="certificadobd" name="certificadobd" value="<?php echo $emisor['certificado']; ?>">
                <input type="file" name="certificado" id="certificado" accept=".pfx">
              </div>
              <div class="content-fluid update-empresa" style="text-align: center; ">
                <input type="hidden" name="logoBD" id="logoBD" value="<?php echo $emisor['logo']; ?>">

                <button type="submit" class="btn btn-primary bt btn-radius pull-center" style="font-size:1em !important;"><i class="far fa-edit fa-lg"></i> ACTUALIZAR DATOS</button>
              </div>
              <div class="content-fluid update-empresa" style="text-align: center; margin-top: 5px;">

                <button id="actualizarBaseD" class="btn btn-primary bt btn-radius pull-center" style="font-size:1em !important;"><i class="far fa-edit fa-lg"></i> ACTUALIZAR BASE DE DATOS</button>

              </div>

              <?php
              $actualizar = ControladorEmpresa::ctrActualizarEmpresa();

              ?>

            </form>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
</div>
</div>
<!-- /.box -->



</div>

</div>
<!-- BOX FIN -->
<!-- /.box-footer -->
</section>

<?php } ?>
</div>