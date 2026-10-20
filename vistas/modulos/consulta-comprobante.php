<div class="content-wrapper panel-medio-principal">

<div style="padding:5px"></div>
<section class="container-fluid">
<section class="content-header dashboard-header">
     <button class=""><i class="fas fa-file-invoice-dollar fa-lg"></i> CONSULTAR COMPROBANTE</button>
     
    </section>
</section>



<!-- <section class="content"> -->
      <section class="container-fluid panel-medio">
          <!-- BOX INI -->
          <div class="box rounded">

                <div class="box-header ">
              <h3 class="box-title">CONSULTE EL ESTADO DE SU COMPROBANTE</h3>

             
           
            </div>
            <!-- /.box-header -->
            <div class="box-body table-user">         

     
                        <form id="formConsulta">
                        <div class="col-md-4">
                                <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-file-invoice-dollar fa-lg"></i></span>
                            <select class="form-control" id="tipocomprobante" name="tipocomprobante">
                                <option value="01">Factura</option>
                                <option value="03">Boleta</option>
                                <option value="07">Nota de crédito</option>
                                <option value="08">Nota de débito</option>
                                </select>
                                </div>
                                </div>
                        </div>
            <!-- ENTRADA TIPO MONEDA-->
                        <div class="col-md-4">
                                <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-file-invoice-dollar fa-lg"></i></span>
                                    <input type="text" class="form-control" name="seriec" id="seriec" placeholder="Serie..." value="F001">
                                </div>
                                </div>
                        </div>
            <!-- ENTRADA TIPO MONEDA-->
                        <div class="col-md-4">
                                <div class="form-group">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fas fa-file-invoice-dollar fa-lg"></i></span>
                                    <input type="text" class="form-control" name="correlativoc" id="correlativoc" placeholder="CORRELATIVO...">
                                </div>
                                </div>
                        </div>
                        <div class="btn-container-consultar">
                          <button id="btnConsulta" class="btn btn-primary"><i class="fas fa-file-invoice-dollar fa-lg"></i> CONSULTAR <i class="fas fa-angle-double-right fa-lg"></i></button>
                        </div>
                        
                        </form>
                 <button class="btn btn-primary" id="btnConsultar">print</button>   
            </div>

        <!-- <button class="btn btn-primary btn-printer">IMPRIMIR</button> -->
<div id="resultConsulta"></div>
            </div>
            <!-- BOX FIN -->
            <!-- /.box-footer -->
          </section>
          
            </div>


