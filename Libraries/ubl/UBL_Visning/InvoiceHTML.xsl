<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************
		OIOUBL Instance Documentation

		title       = InvoiceHTML.xsl
		publisher   = "Digitaliseringsstyrelsen"
		Creator     = Finn Christensen and Charlotte Dahl Skovhus
		created     = 2006-12-29
		modified    = $Date$
		$Revision$
		conformsTo= UBL-Invoice-2.0.xsd
		description= "Stylesheet for displaying a OIOUBL-2.01 Invoice"
		rights= "It can be used following the Common Creative Licence"
		
		all terms derived from http://dublincore.org/documents/dcmi-terms/

		For more information, see www.oioubl.dk	or email oioubl@itst.dk
		
******************************************************************************************************************
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:n1="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:oasis:names:specification:ubl:schema:xsd:CoreComponentParameters-2" xmlns:sdt="urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" exclude-result-prefixes="n1 cac cbc ccts sdt udt">
	<xsl:include href="OIOUBL_CommonTemplates2017.xsl"/>
	<xsl:include href="CSS_Template.xsl"/>
	<xsl:output method="html" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN" doctype-system="http://www.w3.org/TR/html4/loose.dtd" indent="yes"/>
	<xsl:strip-space elements="*"/>
	<xsl:template match="/">
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="n1:Invoice">
		<!-- Start HTML -->
		<html>
			<head>
				<!--<link rel="stylesheet" type="text/css" href="OIOUBL2017.css"/>-->
				<title>
					<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Release']"/>
				</title>
				<xsl:call-template name="CSSReference"/>
			</head>
			<body>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td class="UBLHeaderTd"/>
						<td class="UBLHeaderTd"/>
						<td class="UBLHeaderTd"/>
						<td class="DocumentHeader">
							<!-- Header -->
							<h3 class="DocumentType">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OIOUBLInv']"/>&#160;
                                <xsl:if test="cbc:InvoiceTypeCode = 'Proforma' or cbc:InvoiceTypeCode = 'Factored'">-&#160;<xsl:value-of select="cbc:InvoiceTypeCode"/>
								</xsl:if>
                                &#160;
                                <xsl:if test="cbc:CopyIndicator ='true'">
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CopyIndicator']"/>
								</xsl:if>
							</h3>
							<table border="0" width="100%" cellspacing="0" cellpadding="2">
								<tr>
									<td class="DocumentHeaderInfo">
										<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='InvoiceIDShort']"/></b>
									</td>
									<td class="DocumentHeaderInfo">
										<!-- Invoice number -->
										<xsl:value-of select="cbc:ID"/>
									</td>
								</tr>
								<tr>
									<td class="DocumentHeaderInfo">
										<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='IssueDate']"/></b>
									</td>
									<!-- Invoice date -->
									<td class="DocumentHeaderInfo">
										<xsl:value-of select="cbc:IssueDate"/>
									</td>
								</tr>
								<!-- Order number -->
								<xsl:choose>
									<xsl:when test="cac:OrderReference/cbc:ID !=''">
										<tr>
											<td class="DocumentHeaderInfo">
												<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderReferenceID']"/></b>
											</td>
											<td class="DocumentHeaderInfo">
												<xsl:value-of select="cac:OrderReference/cbc:ID"/>
											</td>
										</tr>
									</xsl:when>
								</xsl:choose>
								<!-- Supplier order number -->
								<xsl:choose>
									<xsl:when test="cac:OrderReference/cbc:SalesOrderID !=''">
										<tr>
											<td class="DocumentHeaderInfo">
												<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='SalesOrderID']"/></b>
											</td>
											<td class="DocumentHeaderInfo">
												<xsl:value-of select="cac:OrderReference/cbc:SalesOrderID"/>
											</td>
										</tr>
									</xsl:when>
								</xsl:choose>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<hr/>
						</td>
					</tr>
					<tr>
						<td>
							<!-- Debitor -->
							<b>
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingCustomerInv']"/>
							</b>
							<br/>
							<xsl:apply-templates select="cac:AccountingCustomerParty"/>
							<xsl:if test="cbc:AccountingCost !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingCost']"/>&#160;<xsl:value-of select="cbc:AccountingCost"/>
							</xsl:if>
						</td>
						<td>
							<xsl:if test="cac:AccountingCustomerParty/cac:Party/cac:Contact !=''">
								<!-- Contact information -->
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/>
								</b>
								<br/>
								<xsl:apply-templates select="cac:AccountingCustomerParty/cac:Party" mode="acccuscontact"/>
							</xsl:if>
						</td>
						<xsl:if test="cac:BuyerCustomerParty !=''">
							<td>
								<!-- Buyer -->
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='BuyerParty']"/>
								</b>
								<br/>
								<xsl:apply-templates select="cac:BuyerCustomerParty"/>
							</td>
						</xsl:if>
						<xsl:if test="cac:PayeeParty !=''">
							<xsl:if test="cac:PayeeParty/cac:PartyIdentification/cbc:ID != cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID">
								<xsl:if test="cac:PayeeParty/cac:PartyName/cbc:Name != cac:AccountingCustomerParty/cac:Party/cac:PartyName/cbc:Name">
									<td>
										<!-- Billing address -->
										<b>
											<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PayeeParty']"/>
										</b>
										<br/>
										<xsl:apply-templates select="cac:PayeeParty"/>
									</td>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</tr>
					<tr>
						<td colspan="4">
							<hr/>
						</td>
					</tr>
					<tr>
						<td>
							<!-- Creditor -->
							<b>
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingSupplierInv']"/>
							</b>
							<br/>
							<xsl:apply-templates select="cac:AccountingSupplierParty"/>
						</td>
						<td>
							<!-- Contact information -->
							<xsl:if test="cac:AccountingSupplierParty/cac:Party/cac:Contact !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/>
								</b>
								<br/>
								<xsl:apply-templates select="cac:AccountingSupplierParty/cac:Party" mode="accsupcontact"/>
							</xsl:if>
						</td>
						<xsl:if test="cac:SellerSupplierParty !=''">
							<td colspan="2">
								<!-- Seller -->
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='SellerParty']"/>
								</b>
								<br/>
								<xsl:apply-templates select="cac:SellerSupplierParty"/>
							</td>
						</xsl:if>
					</tr>
					<tr>
						<td colspan="4">
							<hr/>
						</td>
					</tr>
					<xsl:if test="cac:Delivery !=''">
						<!--<tr>
                            <td colspan="4">
                                -->
						<!-- Delivery information -->
						<!--
                                <b>
                                    <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Delivery']"/>
                                </b>
                            </td>
                        </tr>-->
						<xsl:apply-templates select="cac:Delivery" mode="header"/>
					</xsl:if>
				</table>
				<br/>
				<!-- End invoice head -->
				<!-- Start invoice line -->
				<table class="ItemsTable" border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr class="UBLInvoiceLineHeader">
						<!--Invoice line headings-->
						<th class="ItemLineHeader">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='LineID']"/>
						</th>
						<th class="ItemLineHeader">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='SellersItemIdentification']"/>
						</th>
						<th class="ItemLineHeader">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ItemName']"/>
						</th>
						<th class="ItemLineHeader">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Quantity']"/>
						</th>
						<th class="ItemLineHeader">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='QuantityUnitCode']"/>
						</th>
						<th class="ItemLineHeader">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PriceUnit']"/>
						</th>
						<th class="ItemLineHeader">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='TaxScheme']"/>
						</th>
						<th class="ItemLineHeader">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AllowanceChargePrice']"/>
						</th>
						<th class="ItemLineHeaderAmount">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='LineExtensionAmountLine']"/>
						</th>
						<th class="ItemLineHeaderAmount">
							<!--Amounttype - no heading-->
						</th>
					</tr>
					<!--Invoice lines-->
					<xsl:apply-templates select="cac:InvoiceLine"/>
					<!--Border between item lines and tax/total-->
					<tr>
						<td colspan="10">
							<hr class="HrTaxTotalBorder"/>
						</td>
					</tr>
					<!--Start tax and total-->
					<!-- Line total -->
					<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="LineTotal"/>
					<!-- Tax header -->
					<xsl:apply-templates select="cac:TaxTotal" mode="afgift"/>
					<!-- Discount and fee header -->
					<xsl:apply-templates select="cac:AllowanceCharge" mode="total"/>
					<!-- VAT -->
					<xsl:apply-templates select="cac:TaxTotal" mode="moms"/>
					<!-- Invoice total  -->
					<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="Total"/>
					<!-- End tax and total -->
				</table>
				<!-- End invoice line -->
				<hr/>

				<!-- Start payment information -->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td>
							<xsl:apply-templates select="cac:PaymentMeans"/>
						</td>
						<td>
							<xsl:apply-templates select="cac:PaymentTerms"/>
							<xsl:if test="cac:PrepaidPayment !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PrepaidPayment']"/>
								</b>
								<br/>
								<xsl:apply-templates select="cac:PrepaidPayment"/>
							</xsl:if>
							<xsl:if test="cac:LegalMonetaryTotal !=''">
								<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="supp"/>
							</xsl:if>
							<xsl:if test="cac:AllowanceCharge !=''">
								<br/>
								<xsl:apply-templates select="cac:AllowanceCharge" mode="supp"/>
							</xsl:if>
							<xsl:if test="cac:TaxTotal !=''">
								<br/>
								<xsl:apply-templates select="cac:TaxTotal" mode="supp"/>
								<br/>
							</xsl:if>
						</td>
						<xsl:if test="cac:TaxExchangeRate !='' or cac:PricingExchangeRate !='' or cac:PaymentExchangeRate !='' or cac:PaymentAlternativeExchangeRate !=''">
							<td>
								<xsl:if test="cac:TaxExchangeRate !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='TaxExchangeRate']"/>
									</b>
									<br/>
									<xsl:apply-templates select="cac:TaxExchangeRate"/>
								</xsl:if>
								<xsl:if test="cac:PricingExchangeRate !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PricingExchangeRate']"/>
									</b>
									<br/>
									<xsl:apply-templates select="cac:PricingExchangeRate"/>
								</xsl:if>
								<xsl:if test="cac:PaymentExchangeRate !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PaymentExchangeRate']"/>
									</b>
									<br/>
									<xsl:apply-templates select="cac:PaymentExchangeRate"/>
								</xsl:if>
								<xsl:if test="cac:PaymentAlternativeExchangeRate !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PaymentAlternativeExchangeRate']"/>
									</b>
									<br/>
									<xsl:apply-templates select="cac:PaymentAlternativeExchangeRate"/>
								</xsl:if>
							</td>
						</xsl:if>
					</tr>
				</table>
				<!-- End payment information -->
				<!-- Start free text and references -->
				<xsl:if test="cac:InvoicePeriod !='' or cac:Delivery/cac:RequestedDeliveryPeriod !='' or cbc:Note !='' or cac:OrderReference/cac:DocumentReference !='' or cac:ContractDocumentReference/cbc:ID !='' or cac:AdditionalDocumentReference/cbc:ID !=''">
					<hr/>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							<td>
								<xsl:if test="cac:InvoicePeriod !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='InvoicePeriod']"/>
									</b>
                                    &#160;
                                    <xsl:apply-templates select="cac:InvoicePeriod"/>
									<br/>
								</xsl:if>
								<xsl:if test="cac:Delivery/cac:RequestedDeliveryPeriod !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='RequestedDeliveryPeriod']"/>
									</b>
                                    &#160;
                                    <xsl:apply-templates select="cac:Delivery/cac:RequestedDeliveryPeriod"/>
									<br/>
								</xsl:if>
								<xsl:if test="cbc:Note[.!='']">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Notes']"/>
									</b>
                                    &#160;
                                    <xsl:apply-templates select="cbc:Note" mode="header"/>
									<br/>
									<br/>
								</xsl:if>
								<xsl:if test="cac:OrderReference/cac:DocumentReference !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderDocumentReference']"/>
									</b>
                                    &#160;
                                    <xsl:apply-templates select="cac:OrderReference" mode="reference"/>
									<br/>
								</xsl:if>
								<xsl:if test="cac:ContractDocumentReference/cbc:ID !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ContractDocumentReference']"/>
									</b>
                                    &#160;
                                    <xsl:apply-templates select="cac:ContractDocumentReference"/>
									<br/>
								</xsl:if>
								<xsl:if test="cac:AdditionalDocumentReference/cbc:ID !=''">
									<b>
										<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AdditionalDocumentReferenceID']"/>
									</b>
                                    &#160;
                                    <xsl:apply-templates select="cac:AdditionalDocumentReference"/>
									<br/>
								</xsl:if>
							</td>
						</tr>
					</table>
				</xsl:if>
				<!-- End free text and references -->
				<!-- Start OIOUBL footer -->
				<hr/>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td>
							<b>
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OIOUBLDoc']"/>
							</b>
							<br/>
							<!--<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='VersionID']"/>&#160;<xsl:value-of select="cbc:UBLVersionID"/>-->
							<!--<br/>-->
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CustomizationID']"/>&#160;<xsl:value-of select="cbc:CustomizationID"/>
							<br/>
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ProfileID']"/>&#160;<xsl:value-of select="cbc:ProfileID"/>
							<br/>
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ID']"/>&#160;<xsl:value-of select="cbc:ID"/>
							<br/>
							<xsl:if test="cbc:UUID !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='UUID']"/>&#160;<xsl:value-of select="cbc:UUID"/>
							</xsl:if>
							<br/>
							<xsl:if test="cbc:DocumentCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='DocumentCurrencyCode']"/>&#160;<xsl:value-of select="cbc:DocumentCurrencyCode"/>
								<br/>
							</xsl:if>
							<xsl:if test="cbc:TaxCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='TaxCurrencyCode']"/>&#160;<xsl:value-of select="cbc:TaxCurrencyCode"/>
								<br/>
							</xsl:if>
							<xsl:if test="cbc:PricingCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PricingCurrencyCode']"/>&#160;<xsl:value-of select="cbc:PricingCurrencyCode"/>
								<br/>
							</xsl:if>
							<xsl:if test="cbc:PaymentCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PaymentCurrencyCode']"/>&#160;<xsl:value-of select="cbc:PaymentCurrencyCode"/>
								<br/>
							</xsl:if>
							<xsl:if test="cbc:PaymentAlternativeCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PaymentAlternativeCurrencyCode']"/>&#160;<xsl:value-of select="cbc:PaymentAlternativeCurrencyCode"/>
								<br/>
							</xsl:if>
						</td>
						<xsl:if test="cac:Signature !=''">
							<td>
								<xsl:apply-templates select="cac:Signature"/>
							</td>
						</xsl:if>
					</tr>
				</table>
				<!-- End OIOUBL footer -->
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
