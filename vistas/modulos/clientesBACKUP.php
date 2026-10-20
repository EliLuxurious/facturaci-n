<div class="content-wrapper panel-medio-principal">

<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
</section>



<!-- <section class="content"> -->
      <section class="container-fluid panel-medio">
          <!-- BOX INI -->
          <div class="box rounded">

                <div class="box-header ">
              <h3 class="box-title">Administración de clientes</h3>

              <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarCliente"><i class="fas fa-plus-square"></i>Nuevo cliente <i class="fa fa-th"></i>            
            </button>
            
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         

            <!-- table-bordered table-striped  -->
         <table class="table table-bordered dt-responsive tablas" width="100%">

          <thead>
            <tr>
              <th style="width:10px;">#</th>
              <th>Nombre</th>
              <th>DNI</th>
              <th>Email</th>
              <th>Telefono</th>
              <th>Direccion</th>
              <th>Fecha nacimiento</th>
              <th>Total compras</th>
              <th>Última compra</th>
              <th>Ingreso al sistema</th>
              <th>Acciones</th>
            </tr>
          </thead>

          <tbody>
          <?php
               $item = null;
               $valor = null;
              $clientes = ControladorClientes::ctrMostrarClientes($item, $valor);
              
              foreach($clientes as $key => $value):
              
          ?>
              <tr class="id<?php echo $value['id']; ?>">
                <td><?php echo ++$key; ?></td>
                <td><?php echo $value['nombre']; ?></td>
                <td><?php echo $value['documento']; ?></td>
                <td><?php echo $value['email']; ?></td>
                <td><?php echo $value['telefono']; ?></td>
                <td><?php echo $value['direccion']; ?></td>
                <td><?php echo $value['fecha_nacimiento']; ?></td>
                <td><?php echo $value['compras']; ?></td>
                <td><?php echo $value['ultima_compra']; ?></td>
                <td><?php echo $value['fecha']; ?></td>
                          <td>
                  <div class="btn-group">

                <button class="btn btn-warning btnEditarCliente"  idCliente="<?php echo $value['id']; ?>"  data-toggle="modal" data-target="#modalEditarCliente"><i class="fas fa-user-edit"></i></button>

                <button class="btn btn-danger btnEliminarCliente" idCliente="<?php echo $value['id']; ?>" ><i class="fas fa-trash-alt"></i></button>


                  </div>


                </td>

              </tr>

              <?php 
              endforeach;
              
              ?>                 
          </tbody>

      </table>


              

            </div>

            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
            </div>

            <!-- MODAL AGREGAR CLIENTE-->
  <!-- Modal -->
<div id="modalAgregarCliente" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

    <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoCliente" id="nuevoCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL DNI -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-address-card""></i></span> 

                <input type="number" maxlength="8" class="form-control input-lg" name="nuevoDni" id="nuevoDni" placeholder="Ingresar DNI" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL EMAIL -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-at"></i></span> 

                <input type="email"  class="form-control input-lg" name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL TELÉFONO -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="nuevoTelefono" id="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask='"mask": "(999) 999-999-999"' data-mask required>

              </div>

            </div>
            <!-- ENTRADA PARA EL DIRECCIÓN -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>
            <!-- ENTRADA PARA FECHA NACIMIENTO -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="nuevaFechaNacimiento" id="nuevaFechaNacimiento" placeholder="Ingresar fecha naciminento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>

           

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cliente</button>

        </div>

        <?php

           $crearCliente = new ControladorClientes();
           $crearCliente -> ctrCrearCliente();

        ?>

      </form>


</div>
  </div>
</div>




            <!-- MODAL EDITAR CLIENTE-->
  <!-- Modal -->
  <div id="modalEditarCliente" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

    <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar Cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="hidden" name="id" id="id">

                <input type="text" class="form-control input-lg" name="editarCliente" id="editarCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL DNI -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-address-card""></i></span> 

                <input type="number" maxlength="8" class="form-control input-lg" name="editarDni" id="editarDni" placeholder="Ingresar DNI" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL EMAIL -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-at"></i></span> 

                <input type="email"  class="form-control input-lg" name="editarEmail" id="editarEmail" placeholder="Ingresar email" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL TELÉFONO -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="editarTelefono" id="editarTelefono" placeholder="Ingresar teléfono" data-inputmask='"mask": "(999) 999-999-999"' data-mask required>

              </div>

            </div>
            <!-- ENTRADA PARA EL DIRECCIÓN -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="editarDireccion" id="editarDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>
            <!-- ENTRADA PARA FECHA NACIMIENTO -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span> 

                <input type="text"  class="form-control input-lg" name="editarFechaNacimiento" id="editarFechaNacimiento" placeholder="Ingresar fecha naciminento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>

           

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Editar cliente</button>

        </div>

        <?php

           $editarCliente = new ControladorClientes();
           $editarCliente -> ctrEditarCliente();

        ?>

      </form>


</div>
  </div>
</div>