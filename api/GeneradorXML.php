<?php

namespace api;

class GeneradorXML
{
   function CrearXMLFactura($nombrexml, $emisor, $cliente, $comprobante, $detalle)
   {
      $doc = new \DOMDocument(); //clase que permite crear documento archivos, xml
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';

      //crear el texto XML de la factura electronica
      $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
           <ext:UBLExtensions>
              <ext:UBLExtension>
                 <ext:ExtensionContent />
              </ext:UBLExtension>
           </ext:UBLExtensions>
           <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
           <cbc:CustomizationID>2.0</cbc:CustomizationID>
           <cbc:ID>' . $comprobante['serie'] . '-' . $comprobante['correlativo'] . '</cbc:ID>
           <cbc:IssueDate>' . $comprobante['fecha_emision'] . '</cbc:IssueDate>
           <cbc:IssueTime>00:00:00</cbc:IssueTime>
           <cbc:DueDate>' . $comprobante['fecha_emision'] . '</cbc:DueDate>
           <cbc:InvoiceTypeCode listID="0101">' . $comprobante['tipodoc'] . '</cbc:InvoiceTypeCode>

           <cbc:Note languageLocaleID="1000"><![CDATA[' . $comprobante['total_texto'] . ']]></cbc:Note>';
      if (isset($comprobante['bienesSelva']) && $comprobante['bienesSelva'] == 'si') {

         $xml .= '<cbc:Note languageLocaleID="2001">BIENES TRANSFERIDOS EN LA AMAZONIA REGION SELVA PARA SER CONSUMIDOS EN LA MISMA</cbc:Note>';
      }
      if (isset($comprobante['serviciosSelva']) && $comprobante['serviciosSelva'] == 'si') {

         $xml .= '<cbc:Note languageLocaleID="2002">SERVICIOS PRESTADOS EN LA AMAZONIA REGION SELVA PARA SER CONSUMIDOS EN LA MISMA</cbc:Note>';
      }

      $xml .= '<cbc:DocumentCurrencyCode>' . $comprobante['moneda'] . '</cbc:DocumentCurrencyCode>
           <cac:Signature>
              <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
              <cbc:Note><![CDATA[' . $emisor['nombre_comercial'] . ']]></cbc:Note>
              <cac:SignatoryParty>
                 <cac:PartyIdentification>
                    <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor['razon_social'] . ']]></cbc:Name>
                 </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                 <cac:ExternalReference>
                    <cbc:URI>#SIGN-EMPRESA</cbc:URI>
                 </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
           </cac:Signature>
           <cac:AccountingSupplierParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="' . $emisor['tipodoc'] . '">' . $emisor['ruc'] . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor['nombre_comercial'] . ']]></cbc:Name>
                 </cac:PartyName>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $emisor['razon_social'] . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cbc:ID>' . $emisor['ubigeo'] . '</cbc:ID>
                       <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                       <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                       <cbc:CityName>' . $emisor['provincia'] . '</cbc:CityName>
                       <cbc:CountrySubentity>' . $emisor['departamento'] . '</cbc:CountrySubentity>
                       <cbc:District>' . $emisor['distrito'] . '</cbc:District>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . $emisor['direccion'] . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode>' . $emisor['pais'] . '</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
              </cac:Party>
           </cac:AccountingSupplierParty>
           <cac:AccountingCustomerParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="' . $cliente['tipodoc'] . '">' . $cliente['ruc'] . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $cliente['razon_social'] . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . $cliente['direccion'] . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode>' . $cliente['pais'] . '</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
              </cac:Party>
           </cac:AccountingCustomerParty>';

      if ($comprobante['tipopago'] == 'Contado') {
         $xml .= '<cac:PaymentTerms>
               <cbc:ID>FormaPago</cbc:ID>
               <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
           </cac:PaymentTerms>';
      } else {
         $xml .= '<cac:PaymentTerms>
               <cbc:ID>FormaPago</cbc:ID>
               <cbc:PaymentMeansID>' . $comprobante['tipopago'] . '</cbc:PaymentMeansID>
               <cbc:Amount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total'] . '</cbc:Amount>
           </cac:PaymentTerms>';

         $cuotas = json_decode($comprobante['cuotas']);
         $fechacuotas = json_decode($comprobante['fecha_cuota']);
         $k = 0;
         for ($index = 0; $index < count($cuotas); $index++) {
            $fecha = $fechacuotas[$index];
            $fecha2 = str_replace('/', '-', $fecha);
            $fecha_cuota = date('Y-m-d', strtotime($fecha2));

            $xml .= '<cac:PaymentTerms>
            <cbc:ID>FormaPago</cbc:ID>
            <cbc:PaymentMeansID>Cuota00' . ++$k . '</cbc:PaymentMeansID>
            <cbc:Amount currencyID="' . $comprobante['moneda'] . '">' . $cuotas[$index] . '</cbc:Amount>
            <cbc:PaymentDueDate>' . $fecha_cuota . '</cbc:PaymentDueDate>
            </cac:PaymentTerms>';
         }
      }
      //  ==================DESCUENTO GLOBAL================= ";
      if ($comprobante['descuento'] > 0) {
         $xml .= '<cac:AllowanceCharge>   
           <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
           <cbc:AllowanceChargeReasonCode>' . $comprobante['codigo_tipo'] . '</cbc:AllowanceChargeReasonCode>
           <cbc:MultiplierFactorNumeric>' . $comprobante['descuento_factor'] . '</cbc:MultiplierFactorNumeric>
           <cbc:Amount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['descuento'] . '</cbc:Amount>
           <cbc:BaseAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['monto_base'] . '</cbc:BaseAmount>
       </cac:AllowanceCharge>';
      }
      //  ==================FIN DESCUENTO GLOBAL=================    
      $igv = round($comprobante['igv'] + $comprobante['icbper'], 2);
      $xml .= '<cac:TaxTotal>
              <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $igv . '</cbc:TaxAmount>';

      if ($comprobante['total_opgravadas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                 <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opgravadas'] . '</cbc:TaxableAmount>
                 <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['igv'] . '</cbc:TaxAmount>
                 <cac:TaxCategory>
                    <cac:TaxScheme>
                       <cbc:ID>1000</cbc:ID>
                       <cbc:Name>IGV</cbc:Name>
                       <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                 </cac:TaxCategory>
              </cac:TaxSubtotal>';
      }
      if ($comprobante['icbper'] > 0) {
         $xml .= '  <cac:TaxSubtotal>
               <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['icbper'] . '</cbc:TaxAmount>
               <cac:TaxCategory>
                 <cac:TaxScheme>
                   <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>
                   <cbc:Name>ICBPER</cbc:Name>
                   <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                 </cac:TaxScheme>
               </cac:TaxCategory>
             </cac:TaxSubtotal>';
      }

      if ($comprobante['total_opexoneradas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opexoneradas'] . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                          <cbc:Name>EXO</cbc:Name>
                          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      if ($comprobante['total_opinafectas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opinafectas'] . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                          <cbc:Name>INA</cbc:Name>
                          <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }
      if ($comprobante['total_opgratuitas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opgratuitas'] . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['igv_op'] . '</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9996</cbc:ID>
                          <cbc:Name>GRA</cbc:Name>
                          <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }


      $total_antes_de_impuestos = round($comprobante['total_opgravadas'] + $comprobante['total_opexoneradas'] + $comprobante['total_opinafectas'], 2);

      $xml .= '</cac:TaxTotal>
           <cac:LegalMonetaryTotal>
              <cbc:LineExtensionAmount currencyID="' . $comprobante['moneda'] . '">' . $total_antes_de_impuestos . '</cbc:LineExtensionAmount>
              <cbc:TaxInclusiveAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total'] . '</cbc:TaxInclusiveAmount>
              <cbc:PayableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total'] . '</cbc:PayableAmount>
           </cac:LegalMonetaryTotal>';

      foreach ($detalle as $k => $v) :

         $xml .= '<cac:InvoiceLine>
                  <cbc:ID>' . $v['item'] . '</cbc:ID>
                  <cbc:InvoicedQuantity unitCode="' . $v['unidad'] . '">' . $v['cantidad'] . '</cbc:InvoicedQuantity>
                  <cbc:LineExtensionAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_total'] . '</cbc:LineExtensionAmount>
                  <cac:PricingReference>
                     <cac:AlternativeConditionPrice>
                        <cbc:PriceAmount currencyID="' . $comprobante['moneda'] . '">' . $v['precio_unitario'] . '</cbc:PriceAmount>
                        <cbc:PriceTypeCode>' . $v['tipo_precio'] . '</cbc:PriceTypeCode>
                     </cac:AlternativeConditionPrice>
                  </cac:PricingReference>';


         if ($v['descuentos']['monto'] > 0) :

            //  ==================DESCUENTO POR ITEMS=================  

            $xml .= '<cac:AllowanceCharge>
                  <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                  <cbc:AllowanceChargeReasonCode>' . $v['descuentos']['codigoTipo'] . '</cbc:AllowanceChargeReasonCode>
                  <cbc:MultiplierFactorNumeric>' . $v['descuentos']['factor'] . '</cbc:MultiplierFactorNumeric>
                  <cbc:Amount currencyID="' . $comprobante['moneda'] . '">' . $v['descuentos']['monto'] . '</cbc:Amount>
                  <cbc:BaseAmount currencyID="' . $comprobante['moneda'] . '">' . $v['descuentos']['montoBase'] . '</cbc:BaseAmount>
              </cac:AllowanceCharge>';

         //  ==================FIN DE DESCUENTO POR ITEMS=================  
         endif;

         $xml .= '<cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['igv_opi'] . '</cbc:TaxAmount>
                     <cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_total'] . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['igv'] . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                           <cbc:Percent>' . $v['porcentaje_igv'] . '</cbc:Percent>
                           <cbc:TaxExemptionReasonCode>' . $v['codigo_afectacion_alt'] . '</cbc:TaxExemptionReasonCode>
                           <cac:TaxScheme>
                              <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                              <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                              <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                           </cac:TaxScheme>
                        </cac:TaxCategory>
                     </cac:TaxSubtotal>';

         if ($v['icbper'] > 0) :

            $xml .= '<cac:TaxSubtotal>
                     <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['icbper'] . '</cbc:TaxAmount>
                     <cbc:BaseUnitMeasure unitCode="NIU">' . $v['cantidad'] . '</cbc:BaseUnitMeasure>
                     <cac:TaxCategory>
                       <cbc:Percent>0.00</cbc:Percent>
                       <cbc:PerUnitAmount currencyID="' . $comprobante['moneda'] . '">0.30</cbc:PerUnitAmount>
                       <cac:TaxScheme>
                         <cbc:ID>7152</cbc:ID>
                         <cbc:Name>ICBPER</cbc:Name>
                         <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                     </cac:TaxCategory>
                   </cac:TaxSubtotal>';

         endif;

         $xml .= '</cac:TaxTotal>
                  <cac:Item>
                     <cbc:Description><![CDATA[' . $v['descripcion'] . ']]></cbc:Description>
                     <cac:SellersItemIdentification>
                        <cbc:ID>' . $v['codigo'] . '</cbc:ID>
                     </cac:SellersItemIdentification>
                  </cac:Item>
                  <cac:Price>
                     <cbc:PriceAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_unitario'] . '</cbc:PriceAmount>
                  </cac:Price>
               </cac:InvoiceLine>';

      endforeach;

      $xml .= "</Invoice>";

      $doc->loadXML($xml); //crear el xml en base a un texto
      $doc->save($nombrexml . '.XML'); //guarda el xml generado
   }

   function CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $comprobante, $detalle)
   {
      $doc = new \DOMDocument();
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
      <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
         <ext:UBLExtensions>
            <ext:UBLExtension>
               <ext:ExtensionContent />
            </ext:UBLExtension>
         </ext:UBLExtensions>
         <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
         <cbc:CustomizationID>2.0</cbc:CustomizationID>
         <cbc:ID>' . $comprobante['serie'] . '-' . $comprobante['correlativo'] . '</cbc:ID>
         <cbc:IssueDate>' . $comprobante['fecha_emision'] . '</cbc:IssueDate>
         <cbc:IssueTime>00:00:01</cbc:IssueTime>
         <cbc:Note languageLocaleID="1000"><![CDATA[' . $comprobante['total_texto'] . ']]></cbc:Note>
         <cbc:DocumentCurrencyCode>' . $comprobante['moneda'] . '</cbc:DocumentCurrencyCode>
         <cac:DiscrepancyResponse>
            <cbc:ReferenceID>' . $comprobante['serie_ref'] . '-' . $comprobante['correlativo_ref'] . '</cbc:ReferenceID>
            <cbc:ResponseCode>' . $comprobante['codmotivo'] . '</cbc:ResponseCode>
            <cbc:Description>' . $comprobante['descripcion'] . '</cbc:Description>
         </cac:DiscrepancyResponse>
         <cac:BillingReference>
            <cac:InvoiceDocumentReference>
               <cbc:ID>' . $comprobante['serie_ref'] . '-' . $comprobante['correlativo_ref'] . '</cbc:ID>
               <cbc:DocumentTypeCode>' . $comprobante['tipocomp_ref'] . '</cbc:DocumentTypeCode>
            </cac:InvoiceDocumentReference>
         </cac:BillingReference>
         <cac:Signature>
            <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
            <cbc:Note><![CDATA[' . $emisor['nombre_comercial'] . ']]></cbc:Note>
            <cac:SignatoryParty>
               <cac:PartyIdentification>
                  <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA[' . $emisor['razon_social'] . ']]></cbc:Name>
               </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
               <cac:ExternalReference>
                  <cbc:URI>#SIGN-EMPRESA</cbc:URI>
               </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
         </cac:Signature>
         <cac:AccountingSupplierParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="' . $emisor['tipodoc'] . '">' . $emisor['ruc'] . '</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA[' . $emisor['nombre_comercial'] . ']]></cbc:Name>
               </cac:PartyName>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA[' . $emisor['razon_social'] . ']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cbc:ID>' . $emisor['ubigeo'] . '</cbc:ID>
                     <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                     <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                     <cbc:CityName>' . $emisor['provincia'] . '</cbc:CityName>
                     <cbc:CountrySubentity>' . $emisor['departamento'] . '</cbc:CountrySubentity>
                     <cbc:District>' . $emisor['distrito'] . '</cbc:District>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA[' . $emisor['direccion'] . ']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>' . $emisor['pais'] . '</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingSupplierParty>
         <cac:AccountingCustomerParty>';


      $xml .= '<cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="' . $cliente['tipodoc'] . '">' . $cliente['ruc'] . '</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA[' . $cliente['razon_social'] . ']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA[' . $cliente['direccion'] . ']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>' . $cliente['pais'] . '</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingCustomerParty>';


      $igv = round($comprobante['igv'] + $comprobante['icbper'], 2);
      $xml .= '<cac:TaxTotal>
             <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $igv . '</cbc:TaxAmount>';

      if ($comprobante['total_opgravadas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opgravadas'] . '</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['igv'] . '</cbc:TaxAmount>
                <cac:TaxCategory>
                   <cac:TaxScheme>
                      <cbc:ID>1000</cbc:ID>
                      <cbc:Name>IGV</cbc:Name>
                      <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                   </cac:TaxScheme>
                </cac:TaxCategory>
             </cac:TaxSubtotal>';
      }
      if ($comprobante['icbper'] > 0) {
         $xml .= '  <cac:TaxSubtotal>
              <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['icbper'] . '</cbc:TaxAmount>
              <cac:TaxCategory>
                <cac:TaxScheme>
                  <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>
                  <cbc:Name>ICBPER</cbc:Name>
                  <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                </cac:TaxScheme>
              </cac:TaxCategory>
            </cac:TaxSubtotal>';
      }

      if ($comprobante['total_opexoneradas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                   <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opexoneradas'] . '</cbc:TaxableAmount>
                   <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">0.00</cbc:TaxAmount>
                   <cac:TaxCategory>
                      <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                      <cac:TaxScheme>
                         <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                         <cbc:Name>EXO</cbc:Name>
                         <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                      </cac:TaxScheme>
                   </cac:TaxCategory>
                </cac:TaxSubtotal>';
      }

      if ($comprobante['total_opinafectas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                   <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opinafectas'] . '</cbc:TaxableAmount>
                   <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">0.00</cbc:TaxAmount>
                   <cac:TaxCategory>
                      <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                      <cac:TaxScheme>
                         <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                         <cbc:Name>INA</cbc:Name>
                         <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                      </cac:TaxScheme>
                   </cac:TaxCategory>
                </cac:TaxSubtotal>';
      }
      if ($comprobante['total_opgratuitas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                   <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opgratuitas'] . '</cbc:TaxableAmount>
                   <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['igv_op'] . '</cbc:TaxAmount>
                   <cac:TaxCategory>
                      <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                      <cac:TaxScheme>
                         <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9996</cbc:ID>
                         <cbc:Name>GRA</cbc:Name>
                         <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                      </cac:TaxScheme>
                   </cac:TaxCategory>
                </cac:TaxSubtotal>';
      }

      $total_antes_de_impuestos = round($comprobante['total_opgravadas'] + $comprobante['total_opexoneradas'] + $comprobante['total_opinafectas'], 2);

      $xml .= '</cac:TaxTotal>
             <cac:LegalMonetaryTotal>
                <cbc:LineExtensionAmount currencyID="' . $comprobante['moneda'] . '">' . $total_antes_de_impuestos . '</cbc:LineExtensionAmount>
                <cbc:TaxInclusiveAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total'] . '</cbc:TaxInclusiveAmount>
                <cbc:PayableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total'] . '</cbc:PayableAmount>
             </cac:LegalMonetaryTotal>';

      foreach ($detalle as $k => $v) {

         $xml .= '<cac:CreditNoteLine>
               <cbc:ID>' . $v['item'] . '</cbc:ID>
               <cbc:CreditedQuantity unitCode="' . $v['unidad'] . '">' . $v['cantidad'] . '</cbc:CreditedQuantity>
               <cbc:LineExtensionAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_total'] . '</cbc:LineExtensionAmount>
               <cac:PricingReference>
                  <cac:AlternativeConditionPrice>
                     <cbc:PriceAmount currencyID="' . $comprobante['moneda'] . '">' . $v['precio_unitario'] . '</cbc:PriceAmount>
                     <cbc:PriceTypeCode>' . $v['tipo_precio'] . '</cbc:PriceTypeCode>
                  </cac:AlternativeConditionPrice>
               </cac:PricingReference>';

         // if($v['descuentos']['monto'] > 0):

         //    //  ==================DESCUENTO POR ITEMS=================  

         //      $xml.='<cac:AllowanceCharge>
         //       <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
         //       <cbc:AllowanceChargeReasonCode>'.$v['descuentos']['codigoTipo'].'</cbc:AllowanceChargeReasonCode>
         //       <cbc:MultiplierFactorNumeric>'.$v['descuentos']['factor'].'</cbc:MultiplierFactorNumeric>
         //       <cbc:Amount currencyID="'.$comprobante['moneda'].'">'.$v['descuentos']['monto'].'</cbc:Amount>
         //       <cbc:BaseAmount currencyID="'.$comprobante['moneda'].'">'.$v['descuentos']['montoBase'].'</cbc:BaseAmount>
         //   </cac:AllowanceCharge>';

         //    //  ==================FIN DE DESCUENTO POR ITEMS=================  
         //       endif;


         $xml .= '<cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['igv_opi'] . '</cbc:TaxAmount>
                     <cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_total'] . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['igv'] . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                           <cbc:Percent>' . $v['porcentaje_igv'] . '</cbc:Percent>
                           <cbc:TaxExemptionReasonCode>' . $v['codigo_afectacion_alt'] . '</cbc:TaxExemptionReasonCode>
                           <cac:TaxScheme>
                              <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                              <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                              <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                           </cac:TaxScheme>
                        </cac:TaxCategory>
                     </cac:TaxSubtotal>';

         if ($v['icbper'] > 0) :

            $xml .= '<cac:TaxSubtotal>
                     <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['icbper'] . '</cbc:TaxAmount>
                     <cbc:BaseUnitMeasure unitCode="NIU">' . $v['cantidad'] . '</cbc:BaseUnitMeasure>
                     <cac:TaxCategory>
                       <cbc:Percent>0.00</cbc:Percent>
                       <cbc:PerUnitAmount currencyID="' . $comprobante['moneda'] . '">0.30</cbc:PerUnitAmount>
                       <cac:TaxScheme>
                         <cbc:ID>7152</cbc:ID>
                         <cbc:Name>ICBPER</cbc:Name>
                         <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                     </cac:TaxCategory>
                   </cac:TaxSubtotal>';

         endif;

         $xml .= '</cac:TaxTotal>
                 <cac:Item>
                  <cbc:Description><![CDATA[' . $v['descripcion'] . ']]></cbc:Description>
                  <cac:SellersItemIdentification>
                     <cbc:ID>' . $v['codigo'] . '</cbc:ID>
                  </cac:SellersItemIdentification>
               </cac:Item>
               <cac:Price>
                  <cbc:PriceAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_unitario'] . '</cbc:PriceAmount>
               </cac:Price>
            </cac:CreditNoteLine>';
      }
      $xml .= '</CreditNote>';

      $doc->loadXML($xml);
      $doc->save($nombrexml . '.XML');
   }

   function CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $comprobante, $detalle)
   {

      $doc = new \DOMDocument();
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
      <DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
         <ext:UBLExtensions>
            <ext:UBLExtension>
               <ext:ExtensionContent />
            </ext:UBLExtension>
         </ext:UBLExtensions>
         <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
         <cbc:CustomizationID>2.0</cbc:CustomizationID>
         <cbc:ID>' . $comprobante['serie'] . '-' . $comprobante['correlativo'] . '</cbc:ID>
         <cbc:IssueDate>' . $comprobante['fecha_emision'] . '</cbc:IssueDate>
         <cbc:IssueTime>00:00:03</cbc:IssueTime>
         <cbc:Note languageLocaleID="1000"><![CDATA[' . $comprobante['total_texto'] . ']]></cbc:Note>
         <cbc:DocumentCurrencyCode>' . $comprobante['moneda'] . '</cbc:DocumentCurrencyCode>
         <cac:DiscrepancyResponse>
            <cbc:ReferenceID>' . $comprobante['serie_ref'] . '-' . $comprobante['correlativo_ref'] . '</cbc:ReferenceID>
            <cbc:ResponseCode>' . $comprobante['codmotivo'] . '</cbc:ResponseCode>
            <cbc:Description>' . $comprobante['descripcion'] . '</cbc:Description>
         </cac:DiscrepancyResponse>
         <cac:BillingReference>
            <cac:InvoiceDocumentReference>
               <cbc:ID>' . $comprobante['serie_ref'] . '-' . $comprobante['correlativo_ref'] . '</cbc:ID>
               <cbc:DocumentTypeCode>' . $comprobante['tipocomp_ref'] . '</cbc:DocumentTypeCode>
            </cac:InvoiceDocumentReference>
         </cac:BillingReference>
         <cac:Signature>
            <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
            <cbc:Note><![CDATA[' . $emisor['nombre_comercial'] . ']]></cbc:Note>
            <cac:SignatoryParty>
               <cac:PartyIdentification>
                  <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA[' . $emisor['razon_social'] . ']]></cbc:Name>
               </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
               <cac:ExternalReference>
                  <cbc:URI>#SIGN-EMPRESA</cbc:URI>
               </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
         </cac:Signature>
         <cac:AccountingSupplierParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="' . $emisor['tipodoc'] . '">' . $emisor['ruc'] . '</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyName>
                  <cbc:Name><![CDATA[' . $emisor['nombre_comercial'] . ']]></cbc:Name>
               </cac:PartyName>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA[' . $emisor['razon_social'] . ']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cbc:ID>' . $emisor['ubigeo'] . '</cbc:ID>
                     <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                     <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                     <cbc:CityName>' . $emisor['provincia'] . '</cbc:CityName>
                     <cbc:CountrySubentity>' . $emisor['departamento'] . '</cbc:CountrySubentity>
                     <cbc:District>' . $emisor['distrito'] . '</cbc:District>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA[' . $emisor['direccion'] . ']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>' . $emisor['pais'] . '</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingSupplierParty>
            <cac:AccountingCustomerParty>
            <cac:Party>
               <cac:PartyIdentification>
                  <cbc:ID schemeID="' . $cliente['tipodoc'] . '">' . $cliente['ruc'] . '</cbc:ID>
               </cac:PartyIdentification>
               <cac:PartyLegalEntity>
                  <cbc:RegistrationName><![CDATA[' . $cliente['razon_social'] . ']]></cbc:RegistrationName>
                  <cac:RegistrationAddress>
                     <cac:AddressLine>
                        <cbc:Line><![CDATA[' . $cliente['direccion'] . ']]></cbc:Line>
                     </cac:AddressLine>
                     <cac:Country>
                        <cbc:IdentificationCode>' . $cliente['pais'] . '</cbc:IdentificationCode>
                     </cac:Country>
                  </cac:RegistrationAddress>
               </cac:PartyLegalEntity>
            </cac:Party>
         </cac:AccountingCustomerParty>
         <cac:TaxTotal>
            <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['igv'] . '</cbc:TaxAmount>
            <cac:TaxSubtotal>
               <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opgravadas'] . '</cbc:TaxableAmount>
               <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['igv'] . '</cbc:TaxAmount>
               <cac:TaxCategory>
                  <cac:TaxScheme>
                     <cbc:ID>1000</cbc:ID>
                     <cbc:Name>IGV</cbc:Name>
                     <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                  </cac:TaxScheme>
               </cac:TaxCategory>
            </cac:TaxSubtotal>';

      if ($comprobante['total_opexoneradas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opexoneradas'] . '</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                        <cbc:Name>EXO</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
      }

      if ($comprobante['total_opinafectas'] > 0) {
         $xml .= '<cac:TaxSubtotal>
                  <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total_opinafectas'] . '</cbc:TaxableAmount>
                  <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">0.00</cbc:TaxAmount>
                  <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>
                        <cbc:Name>INA</cbc:Name>
                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                     </cac:TaxScheme>
                  </cac:TaxCategory>
               </cac:TaxSubtotal>';
      }

      $xml .= '</cac:TaxTotal>
         <cac:RequestedMonetaryTotal>
            <cbc:PayableAmount currencyID="' . $comprobante['moneda'] . '">' . $comprobante['total'] . '</cbc:PayableAmount>
         </cac:RequestedMonetaryTotal>';

      foreach ($detalle as $k => $v) {

         $xml .= '<cac:DebitNoteLine>
               <cbc:ID>' . $v['item'] . '</cbc:ID>
               <cbc:DebitedQuantity unitCode="' . $v['unidad'] . '">' . $v['cantidad'] . '</cbc:DebitedQuantity>
               <cbc:LineExtensionAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_total'] . '</cbc:LineExtensionAmount>
               <cac:PricingReference>
                  <cac:AlternativeConditionPrice>
                     <cbc:PriceAmount currencyID="' . $comprobante['moneda'] . '">' . $v['precio_unitario'] . '</cbc:PriceAmount>
                     <cbc:PriceTypeCode>' . $v['tipo_precio'] . '</cbc:PriceTypeCode>
                  </cac:AlternativeConditionPrice>
               </cac:PricingReference>
               <cac:TaxTotal>
                  <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['igv'] . '</cbc:TaxAmount>
                  <cac:TaxSubtotal>
                     <cbc:TaxableAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_total'] . '</cbc:TaxableAmount>
                     <cbc:TaxAmount currencyID="' . $comprobante['moneda'] . '">' . $v['igv'] . '</cbc:TaxAmount>
                     <cac:TaxCategory>
                        <cbc:Percent>' . $v['porcentaje_igv'] . '</cbc:Percent>
                        <cbc:TaxExemptionReasonCode>' . $v['codigo_afectacion_alt'] . '</cbc:TaxExemptionReasonCode>
                        <cac:TaxScheme>
                           <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                           <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                           <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                     </cac:TaxCategory>
                  </cac:TaxSubtotal>
               </cac:TaxTotal>
               <cac:Item>
                  <cbc:Description><![CDATA[' . $v['descripcion'] . ']]></cbc:Description>
                  <cac:SellersItemIdentification>
                     <cbc:ID>' . $v['codigo'] . '</cbc:ID>
                  </cac:SellersItemIdentification>
               </cac:Item>
               <cac:Price>
                  <cbc:PriceAmount currencyID="' . $comprobante['moneda'] . '">' . $v['valor_unitario'] . '</cbc:PriceAmount>
               </cac:Price>
            </cac:DebitNoteLine>';
      }

      $xml .= '</DebitNote>';

      $doc->loadXML($xml);
      $doc->save($nombrexml . '.XML');
   }

   function CrearXMLResumenDocumentos($emisor, $cabecera, $detalle, $nombrexml)
   {
      // var_dump($emisor);
      $doc = new \DOMDocument();
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
           <SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2">
          <ext:UBLExtensions>
              <ext:UBLExtension>
                  <ext:ExtensionContent />
              </ext:UBLExtension>
          </ext:UBLExtensions>
          <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
          <cbc:CustomizationID>1.1</cbc:CustomizationID>
          <cbc:ID>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:ID>
          <cbc:ReferenceDate>' . $cabecera['fecha_emision'] . '</cbc:ReferenceDate>
          <cbc:IssueDate>' . $cabecera['fecha_envio'] . '</cbc:IssueDate>
          <cac:Signature>
              <cbc:ID>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:ID>
              <cac:SignatoryParty>
                  <cac:PartyIdentification>
                      <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyName>
                      <cbc:Name><![CDATA[' . $emisor['razon_social'] . ']]></cbc:Name>
                  </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                  <cac:ExternalReference>
                      <cbc:URI>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:URI>
                  </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
          </cac:Signature>
          <cac:AccountingSupplierParty>
              <cbc:CustomerAssignedAccountID>' . $emisor['ruc'] . '</cbc:CustomerAssignedAccountID>
              <cbc:AdditionalAccountID>' . $emisor['tipodoc'] . '</cbc:AdditionalAccountID>
              <cac:Party>
                  <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA[' . $emisor['razon_social'] . ']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
              </cac:Party>
          </cac:AccountingSupplierParty>';

      foreach ($detalle as $k => $v) {
         $xml .= '<sac:SummaryDocumentsLine>
                 <cbc:LineID>' . $v['item'] . '</cbc:LineID>
                 <cbc:DocumentTypeCode>' . $v['tipodoc'] . '</cbc:DocumentTypeCode>
                 <cbc:ID>' . $v['serie'] . '-' . $v['correlativo'] . '</cbc:ID>
                 <cac:AccountingCustomerParty>
                  <cbc:CustomerAssignedAccountID>' . $v['numdoc'] . '</cbc:CustomerAssignedAccountID>
                  <cbc:AdditionalAccountID>' . $v['coddoc'] . '</cbc:AdditionalAccountID>
                  </cac:AccountingCustomerParty>
                 <cac:Status>
                    <cbc:ConditionCode>' . $v['condicion'] . '</cbc:ConditionCode>
                 </cac:Status> 

                 <sac:TotalAmount currencyID="' . $v['moneda'] . '">' . $v['importe_total'] . '</sac:TotalAmount>';
         if ($v['op_gravadas'] > 0) {
            $xml .= '<sac:BillingPayment>
                           <cbc:PaidAmount currencyID="' . $v['moneda'] . '">' . $v['op_gravadas'] . '</cbc:PaidAmount>
                           <cbc:InstructionID>01</cbc:InstructionID>
                       </sac:BillingPayment>';
         }
         if ($v['op_exoneradas'] > 0) {
            $xml .= '<sac:BillingPayment>
                           <cbc:PaidAmount currencyID="' . $v['moneda'] . '">' . $v['op_exoneradas'] . '</cbc:PaidAmount>
                           <cbc:InstructionID>02</cbc:InstructionID>
                       </sac:BillingPayment>';
         }
         if ($v['op_inafectas'] > 0) {
            $xml .= '<sac:BillingPayment>
                           <cbc:PaidAmount currencyID="' . $v['moneda'] . '">' . $v['op_inafectas'] . '</cbc:PaidAmount>
                           <cbc:InstructionID>03</cbc:InstructionID>
                       </sac:BillingPayment>';
         }
         if ($v['op_gratuitas'] > 0) {
            $xml .= '<sac:BillingPayment>
                           <cbc:PaidAmount currencyID="' . $v['moneda'] . '">' . $v['op_gratuitas'] . '</cbc:PaidAmount>
                           <cbc:InstructionID>05</cbc:InstructionID>
                       </sac:BillingPayment>';
         }

         $xml .= '<cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>';


         if ($v['codigo_afectacion'] != '1000') {
            $xml .= '<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>' . $v['codigo_afectacion'] . '</cbc:ID>
                                 <cbc:Name>' . $v['nombre_afectacion'] . '</cbc:Name>
                                 <cbc:TaxTypeCode>' . $v['tipo_afectacion'] . '</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';
         }

         $xml .= '<cac:TaxSubtotal>
                         <cbc:TaxAmount currencyID="' . $v['moneda'] . '">' . $v['igv_total'] . '</cbc:TaxAmount>
                         <cac:TaxCategory>
                             <cac:TaxScheme>
                                 <cbc:ID>1000</cbc:ID>
                                 <cbc:Name>IGV</cbc:Name>
                                 <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                             </cac:TaxScheme>
                         </cac:TaxCategory>
                     </cac:TaxSubtotal>';

         $xml .= '</cac:TaxTotal>
             </sac:SummaryDocumentsLine>';
      }

      $xml .= '</SummaryDocuments>';

      $doc->loadXML($xml);
      $doc->save($nombrexml . '.XML');
   }

   function CrearXmlBajaDocumentos($emisor, $cabecera, $detalle, $nombrexml)
   {
      $doc = new \DOMDocument();
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
          <ext:UBLExtensions>
              <ext:UBLExtension>
                  <ext:ExtensionContent />
              </ext:UBLExtension>
          </ext:UBLExtensions>
          <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
          <cbc:CustomizationID>1.0</cbc:CustomizationID>
          <cbc:ID>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:ID>
          <cbc:ReferenceDate>' . $cabecera['fecha_emision'] . '</cbc:ReferenceDate>
          <cbc:IssueDate>' . $cabecera['fecha_envio'] . '</cbc:IssueDate>
          <cac:Signature>
              <cbc:ID>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:ID>
              <cac:SignatoryParty>
                  <cac:PartyIdentification>
                      <cbc:ID>' . $emisor['ruc'] . '</cbc:ID>
                  </cac:PartyIdentification>
                  <cac:PartyName>
                      <cbc:Name><![CDATA[' . $emisor['razon_social'] . ']]></cbc:Name>
                  </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                  <cac:ExternalReference>
                      <cbc:URI>' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'] . '</cbc:URI>
                  </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
          </cac:Signature>
          <cac:AccountingSupplierParty>
              <cbc:CustomerAssignedAccountID>' . $emisor['ruc'] . '</cbc:CustomerAssignedAccountID>
              <cbc:AdditionalAccountID>' . $emisor['tipodoc'] . '</cbc:AdditionalAccountID>
              <cac:Party>
                  <cac:PartyLegalEntity>
                      <cbc:RegistrationName><![CDATA[' . $emisor['razon_social'] . ']]></cbc:RegistrationName>
                  </cac:PartyLegalEntity>
              </cac:Party>
          </cac:AccountingSupplierParty>';

      foreach ($detalle as $k => $v) {
         $xml .= '<sac:VoidedDocumentsLine>
                 <cbc:LineID>' . $v['item'] . '</cbc:LineID>
                 <cbc:DocumentTypeCode>' . $v['tipodoc'] . '</cbc:DocumentTypeCode>
                 <sac:DocumentSerialID>' . $v['serie'] . '</sac:DocumentSerialID>
                 <sac:DocumentNumberID>' . $v['correlativo'] . '</sac:DocumentNumberID>
                 <sac:VoidReasonDescription><![CDATA[' . $v['motivo'] . ']]></sac:VoidReasonDescription>
             </sac:VoidedDocumentsLine>';
      }

      $xml .= '</VoidedDocuments>';

      $doc->loadXML($xml);
      $doc->save($nombrexml . '.XML');
   }

   function CrearXMLGuiaRemisionAntiguo($nombrexml, $datosGuia, $detalle)
   {
      $doc = new \DOMDocument();
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
     <DespatchAdvice xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2">
<ext:UBLExtensions>
		<ext:UBLExtension>
			<ext:ExtensionContent/>
		</ext:UBLExtension>
	</ext:UBLExtensions>
   <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
   <cbc:CustomizationID>2.0</cbc:CustomizationID>
	<cbc:ID>' . $datosGuia['guia']['serie'] . '-' . $datosGuia['guia']['correlativo'] . '</cbc:ID>
	<cbc:IssueDate>' . $datosGuia['guia']['fechaEmision'] . '</cbc:IssueDate>
	<cbc:IssueTime>' . $datosGuia['guia']['horaEmision'] . '</cbc:IssueTime>
	<cbc:DespatchAdviceTypeCode>' . $datosGuia['guia']['tipoDoc'] . '</cbc:DespatchAdviceTypeCode>';

      if ($datosGuia['guia']['observacion'] != '') :
         $xml .= '<cbc:Note><![CDATA[' . $datosGuia['guia']['observacion'] . ']]></cbc:Note>';
      endif;


      if ($datosGuia['relDoc']['nroDoc'] != '') :
         $xml .= '<cac:AdditionalDocumentReference>
		<cbc:ID>' . $datosGuia['relDoc']['nroDoc'] . '</cbc:ID>
		<cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="SUNAT:Identificador de documento relacionado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo21">' . $datosGuia['relDoc']['tipoDoc'] . '</cbc:DocumentTypeCode>
      <cbc:DocumentType>Factura</cbc:DocumentType>
      <cac:IssuerParty>
      <cac:PartyIdentification>
      <cbc:ID schemeID="6" schemeAgencyName="PE:SUNAT" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
      </cac:PartyIdentification>
      </cac:IssuerParty>
	</cac:AdditionalDocumentReference>';
      endif;

      $xml .= '<cac:DespatchSupplierParty>
		<cbc:CustomerAssignedAccountID schemeID="6">' . $datosGuia['remitente']['ruc'] . '</cbc:CustomerAssignedAccountID>
		<cac:Party>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName><![CDATA[' . $datosGuia['remitente']['razonsocial'] . ']]></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:DespatchSupplierParty>
   
	<cac:DeliveryCustomerParty>
		<cbc:CustomerAssignedAccountID schemeID="' . $datosGuia['destinatario']['tipoDoc'] . '">' . $datosGuia['destinatario']['numDoc'] . '</cbc:CustomerAssignedAccountID>
		<cac:Party>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName><![CDATA[' . $datosGuia['destinatario']['nombreRazon'] . ']]></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:DeliveryCustomerParty>';

      if ($datosGuia['terceros']['tipoDoc'] != '') :
         $xml .= '<cac:SellerSupplierParty>
		<cbc:CustomerAssignedAccountID schemeID="' . $datosGuia['terceros']['tipoDoc'] . '">' . $datosGuia['terceros']['numDoc'] . '</cbc:CustomerAssignedAccountID>
		<cac:Party>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName><![CDATA[' . $datosGuia['terceros']['nombreRazon'] . ']]></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:SellerSupplierParty>';
      endif;


      $xml .= '<cac:Shipment>
		<cbc:ID>1</cbc:ID>
		<cbc:HandlingCode>' . $datosGuia['datosEnvio']['codTraslado'] . '</cbc:HandlingCode>';

      if ($datosGuia['datosEnvio']['descTraslado'] != '') :
         $xml .= '<cbc:Information>' . $datosGuia['datosEnvio']['descTraslado'] . '</cbc:Information>';
      endif;
      $xml .= '<cbc:GrossWeightMeasure unitCode="' . $datosGuia['datosEnvio']['uniPesoTotal'] . '">' . $datosGuia['datosEnvio']['pesoTotal'] . '</cbc:GrossWeightMeasure>';

      if ($datosGuia['datosEnvio']['numBultos'] > 0) :
         $xml .= '<cbc:TotalTransportHandlingUnitQuantity>' . $datosGuia['datosEnvio']['numBultos'] . '</cbc:TotalTransportHandlingUnitQuantity>';
      endif;

      $xml .= '<cbc:SplitConsignmentIndicator>false</cbc:SplitConsignmentIndicator>
		<cac:ShipmentStage>
			<cbc:TransportModeCode>' . $datosGuia['datosEnvio']['modTraslado'] . '</cbc:TransportModeCode>
			<cac:TransitPeriod>
				<cbc:StartDate>' . $datosGuia['datosEnvio']['fechaTraslado'] . '</cbc:StartDate>
			</cac:TransitPeriod>';

      if ($datosGuia['datosEnvio']['modTraslado'] == '01') :
         $xml .=   '<cac:CarrierParty>
				<cac:PartyIdentification>
					<cbc:ID schemeID="' . $datosGuia['transportista']['tipoDoc'] . '">' . $datosGuia['transportista']['numDoc'] . '</cbc:ID>
				</cac:PartyIdentification>
				<cac:PartyName>
					<cbc:Name><![CDATA[' . $datosGuia['transportista']['nombreRazon'] . ']]></cbc:Name>
				</cac:PartyName>
			</cac:CarrierParty>';
      endif;


      if ($datosGuia['datosEnvio']['modTraslado'] == '02') :

         $xml .= '<cac:TransportMeans>';
         $xml .= '<cac:RoadTransport>
					<cbc:LicensePlateID>' . $datosGuia['transportista']['placa'] . '</cbc:LicensePlateID>
				</cac:RoadTransport>';

         $xml .= '</cac:TransportMeans>
			<cac:DriverPerson>
				<cbc:ID schemeID="' . $datosGuia['transportista']['tipoDocChofer'] . '">' . $datosGuia['transportista']['numDocChofer'] . '</cbc:ID>
			</cac:DriverPerson>';
      endif;

      $xml .=   '</cac:ShipmentStage>
		<cac:Delivery>
			<cac:DeliveryAddress>
				<cbc:ID>' . $datosGuia['llegada']['ubigeo'] . '</cbc:ID>
				<cbc:StreetName>' . $datosGuia['llegada']['direccion'] . '</cbc:StreetName>
			</cac:DeliveryAddress>
		</cac:Delivery>';

      if ($datosGuia['contenedor']['numContenedor'] != '') :
         $xml .= '<cac:TransportHandlingUnit>
			<cbc:ID>' . $datosGuia['contenedor']['numContenedor'] . '</cbc:ID>
		   </cac:TransportHandlingUnit>';
      endif;
      $xml .= '<cac:OriginAddress>
			<cbc:ID>' . $datosGuia['partida']['ubigeo'] . '</cbc:ID>
			<cbc:StreetName>' . $datosGuia['partida']['direccion'] . '</cbc:StreetName>
		   </cac:OriginAddress>';

      if ($datosGuia['puerto']['codPuerto'] != '') :
         $xml .= '<cac:FirstArrivalPortLocation>
			<cbc:ID>' . $datosGuia['puerto']['codPuerto'] . '</cbc:ID>
		   </cac:FirstArrivalPortLocation>';
      endif;
      $xml .= '</cac:Shipment>';

      foreach ($detalle as $k => $v) :
         $xml .= '<cac:DespatchLine>
		<cbc:ID>' . $v['index'] . '</cbc:ID>
		<cbc:DeliveredQuantity unitCode="' . $v['unidad'] . '">' . $v['cantidad'] . '</cbc:DeliveredQuantity>
		<cac:OrderLineReference>
			<cbc:LineID>' . $v['index'] . '</cbc:LineID>
		</cac:OrderLineReference>
		<cac:Item>
			<cbc:Name><![CDATA[' . $v['descripcion'] . ']]></cbc:Name>
			<cac:SellersItemIdentification>
				<cbc:ID>' . $v['codigo'] . '</cbc:ID>
			</cac:SellersItemIdentification>';

         if ($v['codProdSunat'] != '') :
            $xml .= '<cac:CommodityClassification>
					<cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">' . $v['codProdSunat'] . '</cbc:ItemClassificationCode>
				</cac:CommodityClassification>';
         endif;
         $xml .= '</cac:Item>
	</cac:DespatchLine>';
      endforeach;

      $xml .= '</DespatchAdvice>';

      $doc->loadXML($xml);
      $doc->save($nombrexml . '.xml');
   }

   function CrearXMLGuiaRemision($nombrexml, $datosGuia, $detalle)
   {
      $doc = new \DOMDocument();
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
      <DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
         <ext:UBLExtensions>
		<ext:UBLExtension>
			<ext:ExtensionContent/>
		</ext:UBLExtension>
	</ext:UBLExtensions>
   <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
   <cbc:CustomizationID>2.0</cbc:CustomizationID>
	<cbc:ID>' . $datosGuia['guia']['serie'] . '-' . $datosGuia['guia']['correlativo'] . '</cbc:ID>
	<cbc:IssueDate>' . $datosGuia['guia']['fechaEmision'] . '</cbc:IssueDate>
	<cbc:IssueTime>' . $datosGuia['guia']['horaEmision'] . '</cbc:IssueTime>
   <cbc:DespatchAdviceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">' . $datosGuia['guia']['tipoDoc'] . '</cbc:DespatchAdviceTypeCode>';

      if ($datosGuia['guia']['observacion'] != '') :
         $xml .= '<cbc:Note><![CDATA[' . $datosGuia['guia']['observacion'] . ']]></cbc:Note>';
      endif;


      if ($datosGuia['relDoc']['nroDoc'] != '') :
         $xml .= '<cac:AdditionalDocumentReference>
		<cbc:ID>' . $datosGuia['relDoc']['nroDoc'] . '</cbc:ID>
      <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Documento relacionado al transporte" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo61">' . $datosGuia['relDoc']['tipoDoc'] . '</cbc:DocumentTypeCode>
      <cbc:DocumentType>Factura</cbc:DocumentType>
      <cac:IssuerParty>
            <cac:PartyIdentification>
            <cbc:ID schemeID="6" schemeAgencyName="PE:SUNAT" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
            </cac:PartyIdentification>
      </cac:IssuerParty>
	</cac:AdditionalDocumentReference>';
      endif;
      $xml .= '
<cac:Signature>
<cbc:ID>' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
<cac:SignatoryParty>
<cac:PartyIdentification>
<cbc:ID>' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
</cac:PartyIdentification>
<cac:PartyName>
<cbc:Name>' . $datosGuia['remitente']['razonsocial'] . '</cbc:Name>
</cac:PartyName>
</cac:SignatoryParty>
<cac:DigitalSignatureAttachment>
<cac:ExternalReference>
<cbc:URI>#GREENTER-SIGN</cbc:URI>
</cac:ExternalReference>
</cac:DigitalSignatureAttachment>
</cac:Signature>
';
      $xml .= '<cac:DespatchSupplierParty>
		
		<cac:Party>
      <cac:PartyIdentification>
         <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
         </cac:PartyIdentification>
         <cac:PartyLegalEntity>
         <cbc:RegistrationName>' . $datosGuia['remitente']['razonsocial'] . '</cbc:RegistrationName>
         </cac:PartyLegalEntity>
			
		</cac:Party>
	</cac:DespatchSupplierParty>
   
	<cac:DeliveryCustomerParty>
		
		<cac:Party>
      <cac:PartyIdentification>
<cbc:ID schemeID="' . $datosGuia['destinatario']['tipoDoc'] . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $datosGuia['destinatario']['numDoc'] . '</cbc:ID>
</cac:PartyIdentification>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName><![CDATA[' . $datosGuia['destinatario']['nombreRazon'] . ']]></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:DeliveryCustomerParty>';


      $xml .= '<cac:Shipment>
		<cbc:ID>SUNAT_Envio</cbc:ID>
		<cbc:HandlingCode>' . $datosGuia['datosEnvio']['codTraslado'] . '</cbc:HandlingCode>';

      if ($datosGuia['datosEnvio']['descTraslado'] != '') :

         $xml .= '<cbc:HandlingInstructions>' . $datosGuia['datosEnvio']['descTraslado'] . '</cbc:HandlingInstructions>';
      endif;

      $xml .= '<cbc:GrossWeightMeasure unitCode="' . $datosGuia['datosEnvio']['uniPesoTotal'] . '">' . $datosGuia['datosEnvio']['pesoTotal'] . '</cbc:GrossWeightMeasure>';

      if ($datosGuia['datosEnvio']['tipoVehiculo'] != 'otros') :

         $xml .= '<cbc:SpecialInstructions>SUNAT_Envio_IndicadorTrasladoVehiculoM1L</cbc:SpecialInstructions>';

      endif;
      // if ($datosGuia['datosEnvio']['numBultos'] > 0) :
      //    $xml .= '<cbc:TotalTransportHandlingUnitQuantity>' . $datosGuia['datosEnvio']['numBultos'] . '</cbc:TotalTransportHandlingUnitQuantity>';
      // endif;

      $xml .= '
		<cac:ShipmentStage>
			<cbc:TransportModeCode>' . $datosGuia['datosEnvio']['modTraslado'] . '</cbc:TransportModeCode>
			<cac:TransitPeriod>
				<cbc:StartDate>' . $datosGuia['datosEnvio']['fechaTraslado'] . '</cbc:StartDate>
			</cac:TransitPeriod>';

      if ($datosGuia['datosEnvio']['modTraslado'] == '01'  && $datosGuia['datosEnvio']['tipoVehiculo'] == '') :
         $xml .=   '<cac:CarrierParty>
				<cac:PartyIdentification>
					<cbc:ID schemeID="' . $datosGuia['transportista']['tipoDoc'] . '">' . $datosGuia['transportista']['numDoc'] . '</cbc:ID>
				</cac:PartyIdentification>
            <cac:PartyLegalEntity>
               <cbc:RegistrationName>
               <![CDATA[' . $datosGuia['transportista']['nombreRazon'] . ']]>
               </cbc:RegistrationName>
               <cbc:CompanyID>0001</cbc:CompanyID>
               </cac:PartyLegalEntity>
			</cac:CarrierParty>';
      endif;


      if ($datosGuia['datosEnvio']['modTraslado'] == '02' && $datosGuia['datosEnvio']['tipoVehiculo'] == 'otros') :

         $xml .= '<cac:DriverPerson>
				<cbc:ID schemeID="' . $datosGuia['transportista']['tipoDocChofer'] . '">' . $datosGuia['transportista']['numDocChofer'] . '</cbc:ID>
            <cbc:FirstName>' . $datosGuia['transportista']['nombreRazon'] . '</cbc:FirstName>
            <cbc:FamilyName>' . $datosGuia['transportista']['apellidosRazon'] . '</cbc:FamilyName>
            <cbc:JobTitle>Principal</cbc:JobTitle>
            <cac:IdentityDocumentReference>
            <cbc:ID>' . $datosGuia['transportista']['numBreveteChofer'] . '</cbc:ID>
            </cac:IdentityDocumentReference>
			</cac:DriverPerson>';
      endif;

      $xml .=   '</cac:ShipmentStage>
      <cac:Delivery>
			<cac:DeliveryAddress>
				<cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">' . $datosGuia['llegada']['ubigeo'] . '</cbc:ID>
				 <cac:AddressLine>
               <cbc:Line>' . $datosGuia['llegada']['direccion'] . '</cbc:Line>
               </cac:AddressLine>
			</cac:DeliveryAddress>
         <cac:Despatch>
            <cac:DespatchAddress>
            <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">' . $datosGuia['partida']['ubigeo'] . '</cbc:ID>
            <cac:AddressLine>
            <cbc:Line>' . $datosGuia['partida']['direccion'] . '</cbc:Line>
            </cac:AddressLine>
            </cac:DespatchAddress>
            </cac:Despatch>
		</cac:Delivery>';
      if ($datosGuia['datosEnvio']['modTraslado'] == '02' && $datosGuia['datosEnvio']['tipoVehiculo'] == 'otros') :
         $xml .= '
         <cac:TransportHandlingUnit>
            <cac:TransportEquipment>
            <cbc:ID>' . $datosGuia['transportista']['placa'] . '</cbc:ID>
            
            </cac:TransportEquipment>
         </cac:TransportHandlingUnit>';

      endif;
      // if ($datosGuia['contenedor']['numContenedor'] != '') :
      //    $xml .= '<cac:TransportHandlingUnit>
      // 	<cbc:ID>' . $datosGuia['contenedor']['numContenedor'] . '</cbc:ID>
      //    </cac:TransportHandlingUnit>';
      // endif;


      // if ($datosGuia['puerto']['codPuerto'] != '') :
      //    $xml .= '<cac:FirstArrivalPortLocation>
      // 	<cbc:ID>' . $datosGuia['puerto']['codPuerto'] . '</cbc:ID>
      //    </cac:FirstArrivalPortLocation>';
      // endif;
      $xml .= '</cac:Shipment>';

      foreach ($detalle as $k => $v) :
         $xml .= '<cac:DespatchLine>
		<cbc:ID>' . $v['index'] . '</cbc:ID>
		<cbc:DeliveredQuantity unitCode="' . $v['unidad'] . '">' . $v['cantidad'] . '</cbc:DeliveredQuantity>
		<cac:OrderLineReference>
			<cbc:LineID>' . $v['index'] . '</cbc:LineID>
		</cac:OrderLineReference>
		<cac:Item>
      <cbc:Description>' . $v['descripcion'] . '</cbc:Description>

			<cac:SellersItemIdentification>
				<cbc:ID>' . $v['codigo'] . '</cbc:ID>
			</cac:SellersItemIdentification>';

         if ($v['codProdSunat'] != '') :
            $xml .= '<cac:CommodityClassification>
					<cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">' . $v['codProdSunat'] . '</cbc:ItemClassificationCode>
				</cac:CommodityClassification>';
         endif;
         $xml .= '</cac:Item>
	</cac:DespatchLine>';
      endforeach;

      $xml .= '</DespatchAdvice>';

      $doc->loadXML($xml);
      $doc->save($nombrexml . '.xml');
   }
}
