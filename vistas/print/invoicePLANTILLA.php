<?php
//Consultar los datos necesarios para mostrar en el PDF - FIN
    if($venta['codmoneda'] == 'PEN'){
        $tipoMoneda = 'S/ ';
    }else {
        $tipoMoneda = '$USD ';
    }
    if($venta['tipocomp'] == '01'){
        $rucdni = $cliente['ruc'];
        $razons_nombre = $cliente['razon_social'];
        $tipodoccliente = 6;
        $nombre_comprobante = $tipo_comprobante['descripcion'].' '.'ELECTRÓNICA';
    }
     if($venta['tipocomp'] == '03'){
        $rucdni = $cliente['documento'];
        $razons_nombre = $cliente['nombre'];
        $tipodoccliente = 1;
        $nombre_comprobante = $tipo_comprobante['descripcion'].' '.'DE VENTA ELECTRÓNICA';
    }
     if($venta['tipocomp'] == '02'){
        $rucdni = $cliente['documento'];
        $razons_nombre = $cliente['nombre'];
        $tipodoccliente = 1;
        $nombre_comprobante = $tipo_comprobante['descripcion'];
    }

   ?>

        <style>
     
          #tabla-cabecera, #tabla-cliente, #tabla-items, #tabla-totales, .tabla-importes, .tabla-observacion{
           position: relative;
            width:100%;
            border-collapse: collapse;
           
          }
          #tabla-cabecera{
            text-align: center;
            letter-spacing: 0.5px;
            color: #333;
          }
           #tabla-cabecera h3{
              font-size: 16px;
              margin-bottom: 1px;
              color: #444;
          }
          #tabla-cliente td{
            border: 0.1px solid #333;
            padding: 7px;
            text-align: left;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
          #tabla-totales td{ 
            padding: 7px;
            text-align: left;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
          .tabla-importes td{
            border: 0.1px solid #333;
            padding: 7px;
            text-align: left;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
          
          #tabla-cliente{
              margin-top: 15px;
          }
          #tabla-items{
            margin-top: 10px;
          }
          #tabla-items th{
            border: 0.1px solid #333;
            padding: 6px;
            text-align: center;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
          #tabla-items td{
            border: 0.1px solid #333;
            padding: 6px;
            text-align: center;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding-left: 10px;
          }
              
          .ruc-emisor{
              position: relative;
              border: 0.5px solid #333;
              border-radius: 20px;
              text-align: center;
              vertical-align: top;
             
          }
          .ruc-emisor h4{
              color: #444;
          }
.v10{
    width: 10%;
}
.v15{
    width: 15%;
}
.v20{
    width: 20%;
}
.v25{
    width: 25%;
}

.v30{
    width: 30%;
}
.v35{
    width: 35%;
}
.v40{
    width: 40%;
}
.v45{
    width: 45%;
}
.v50{
    width: 50%;
}
.v55{
    width: 55%;
}
.v60{
    width: 60%;
}
.v65{
    width: 65%;
}
.v70{
    width: 70%;
}
.v75{
    width: 75%;
}
.v80{
    width: 80%;
}
.v100{
    width: 100%;
}
.direccion{
    font-size: 9px;
}
.total-letras{
    width: 100%;
    border: 0.1px solid #333;
    font-size: 9px;
    text-align: left;
    border-radius: 10px;
    padding: 5px;
    margin-top: 5px;
    padding-left: 10px;
}

.tabla-observacion{
    position: relative;
   margin-top: 5px;
    
   
}
.tabla-observacion td{
    position: relative;
    vertical-align: baseline;
    
   
}
.tabla-tipo-pago{
    width: 70%;
    border-collapse: collapse;
}
.tabla-tipo-pago td{
   border-bottom: 0.1px solid #333;
    
    
}
  
.col{
    background-color: #999;
}
.pie-pag{
    padding: 10px;
    font-size: 12px;
    border: 0.3px solid #333;
    margin-top: 10px;
    border-radius: 13px;
}
  </style>


 <page backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm"> 
    <page_header> 
         
     
    </page_header> 
    <page_footer> 
         
    </page_footer>  

<table id="tabla-cabecera">
  <tr>
      <!-- LOGO================== -->
    <td class="v25"><img src=logo.png"  class="v100"></td>
    <!--FIN LOGO================== -->
    <td class="v45">
        <h3>TECHNOLOGY AND MULPTIPLE SERVICES GYE E.I.R.L.</h3>
        <label class="direccion">CAL. TACNA NRO. 336 URB. SAN MIGUEL LIMA - LIMA - SANMIGUEL</label>

    </td>
    <td class="v30" style="text-align: left">
        <div class="ruc-emisor v100">
            <h4>R.U.C. 20601284961</h4>
            <h4>FACTURA ELECTRÓNICA</h4>
            <h4>F001-000001</h4>
        </div>
    </td>
  </tr>

</table>
<table id="tabla-cliente">
    <tr>
        <td class="v20">FECHA DE EMISIÓN:</td>
        <td class="v30">22/10/2021</td>
        <td class="v20">MEDIO DE PAGO:</td>
        <td class="v30">Efectivo</td>
    </tr>
    <tr>
        <td class="v20">CLIENTE:</td>
        <td class="v80" colspan="3">GINO VASQUEZ IBERICO</td>
        
    </tr>
    <tr>
        <td class="v20">R.U.C/D.N.I:</td>
        <td class="v80" colspan="3">20601285769</td>
        
    </tr>
    <tr>
        <td class="v20">DIRECCIÓN:</td>
        <td class="v80" colspan="3">Calle Tacna 335</td>
        
    </tr>

</table>
<table id="tabla-items">
<tr class="">
    <th class="v10">ITEM</th>
    <th class="v10">CANTIDAD</th>
    <th class="v40">DESCRIPCIÓN</th>
    <th class="v20">VALOR U.</th>
    <th class="v20">IMPORTE</th>
</tr>
<tr>
    <td>1</td>
    <td>2</td>
    <td>Lámpara 34 luces flash 2.5 desctop understop wert</td>
    <td>S/ 100120.00</td>
    <td>S/ 100120.00</td>
</tr>

</table>

    <div class="total-letras">
    SON: NOVENTA Y OCHO CON 00/100 SOLES
    </div>

      
    <table id="tabla-totales">
        
     <tr>
         <td class="v65" style="padding: 0px;">
            <table class="tabla-observacion">
                <tr>
                    <td class="v30">
                      
            
                    <qrcode class="barcode" value="421115555|gggggggggg|4444444444445" style="width: 26mm; background-color: white; color: #222; border: none; padding:none"></qrcode>

                </td>
                    
                    <td class="v70">

                       <b>Observación:</b><br>
                       Consulte su documento electrónico en www.sunat.gob.pe
                    </td>
                    
                </tr>
               
            </table>        
        
            <table class="tabla-tipo-pago">
                <tr>
                    <td colspan="2">
                        <b>Información Adicional</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        LEYENDA
                    </td>
                </tr>
                <tr>
                    <td class="v45">FORMA DE PAGO:</td>
                    <td class="v55">Al contado</td>
                </tr>
                <tr>
                    <td>VENDEDOR:</td>
                    <td>V-0065</td>
                </tr>
            </table>

         </td>

         <td class="v35">
             <table class="tabla-importes">
             <tr>               
               <td class="v50">GRAVADAS:</td>
               <td class="v50">S/ 120.00</td>
            </tr>        
           <tr>               
               <td>EXONERADAS:</td>
               <td>S/ 120.00</td>
          </tr>
           <tr>               
               <td>INAFECTAS:</td>
               <td>S/ 120.00</td>
          </tr>
           <tr>               
               <td>GRATUITAS:</td>
               <td>S/ 120.00</td>
          </tr>
           <tr>               
               <td>DESCUENTO(-):</td>
               <td>S/ 120.00</td>
          </tr>
           <tr>               
               <td>IGV(18%):</td>
               <td>S/ 15.00</td>
          </tr>
           <tr>               
               <td>TOTAL:</td>
               <td>S/ 120.00</td>
          </tr>
             </table>
         </td>
     </tr>          
        </table>
<div class="pie-pag">
    Representación impresa de la FACTURA ELECTRÓNICA
</div>
</page>