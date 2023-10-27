<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************
		OIOUBL Instance Documentation

		title		= CreditNoteHTML.xsl
		publisher	= "Digitaliseringsstyrelsen"
		Creator		= Finn Christensen and Charlotte Dahl Skovhus
		created		= 2006-12-29
		modified	= $Date$
		$Revision$
		conformsTo= UBL-CreditNote-2.0.xsd
		description= "Stylesheet for displaying a OIOUBL-2.01 CreditNote"
		rights= "It can be used following the Common Creative Licence"
		
		all terms derived from http://dublincore.org/documents/dcmi-terms/

		For more information, see www.oioubl.dk	or email oioubl@itst.dk
		
******************************************************************************************************************
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:n1="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:oasis:names:specification:ubl:schema:xsd:CoreComponentParameters-2" xmlns:sdt="urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" exclude-result-prefixes="n1 cac cbc ccts sdt udt">
	<xsl:include href="OIOUBL_CommonTemplates2017.xsl"/>
	<xsl:include href="CSS_Template.xsl"/>
	<xsl:output method="html" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN" doctype-system="http://www.w3.org/TR/html4/loose.dtd" indent="yes"/>
	<xsl:strip-space elements="*"/>
	<xsl:template match="/">
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="n1:CreditNote">
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
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OIOUBLCN']"/>&#160;
								<xsl:if test="cbc:CopyIndicator ='true'">
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CopyIndicator']"/>
								</xsl:if>
							</h3>
							<table class="DocumentHeaderInfo" border="0" width="100%" cellspacing="0" cellpadding="2">
								<tr>
									<td class="DocumentHeaderInfo">
										<b>
											<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CreditNoteID']"/>&#160;
											</b>
									</td>
									<td class="DocumentHeaderInfo">
										<!-- Creditnote number -->
										<xsl:apply-templates select="cbc:ID"/>
									</td>
								</tr>
								<tr>
									<td class="DocumentHeaderInfo">
										<b>
											<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='IssueDate']"/>&#160;
											</b>
									</td>
									<td class="DocumentHeaderInfo">
										<!-- Creditnote date -->
										<xsl:apply-templates select="cbc:IssueDate"/>
									</td>
								</tr>
								<xsl:choose>
									<xsl:when test="cac:BillingReference/cac:InvoiceDocumentReference/cbc:ID !=''">
										<tr>
											<td class="DocumentHeaderInfo">
												<b>
													<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='InvoiceID']"/>&#160;
													</b>
											</td>
											<td class="DocumentHeaderInfo">
												<!-- Supplier invoice number  -->
												<xsl:apply-templates select="cac:BillingReference/cac:InvoiceDocumentReference/cbc:ID"/>
											</td>
										</tr>
									</xsl:when>
									<xsl:otherwise>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="cac:BillingReference/cac:InvoiceDocumentReference/cbc:IssueDate !=''">
										<tr>
											<td class="DocumentHeaderInfo">
												<b>
													<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='InvoiceIssueDate']"/>&#160;
													</b>
											</td>
											<td class="DocumentHeaderInfo">
												<!-- Supplier invoice date -->
												<xsl:apply-templates select="cac:BillingReference/cac:InvoiceDocumentReference/cbc:IssueDate"/>
											</td>
										</tr>
									</xsl:when>
									<xsl:otherwise>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="cac:OrderReference/cbc:ID !=''">
										<tr>
											<td class="DocumentHeaderInfo">
												<b>
													<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderReferenceID']"/>&#160;
													</b>
											</td>
											<td class="DocumentHeaderInfo">
												<!-- Order number  -->
												<xsl:apply-templates select="cac:OrderReference/cbc:ID"/>
											</td>
										</tr>
									</xsl:when>
									<xsl:otherwise>
									</xsl:otherwise>
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
						<td width="30%">
							<!-- Debitor -->
							<b>
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingCustomerCrn']"/>
							</b>
							<br/>
							<xsl:apply-templates select="cac:AccountingCustomerParty"/>
							<xsl:if test="cbc:AccountingCost !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingCost']"/>&#160;<xsl:value-of select="cbc:AccountingCost"/>
							</xsl:if>
							<br/>
						</td>
						<td width="30%">
							<!-- Contact information -->
							<xsl:if test="cac:AccountingCustomerParty/cac:Party/cac:Contact !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/>
								</b>
								<br/>
								<xsl:apply-templates select="cac:AccountingCustomerParty/cac:Party" mode="acccuscontact"/>
							</xsl:if>
						</td>
						<!-- Optional billing address -->
						<xsl:if test="cac:PayeeParty !=''">
							<xsl:if test="cac:PayeeParty/cac:PartyIdentification/cbc:ID != cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID">
								<xsl:if test="cac:PayeeParty/cac:PartyName/cbc:Name != cac:AccountingCustomerParty/cac:Party/cac:PartyName/cbc:Name">
									<td width="30%">
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
						<td width="30%">
							<!-- Creditor -->
							<b>
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingSupplierCrn']"/>
							</b>
							<br/>
							<xsl:apply-templates select="cac:AccountingSupplierParty"/>
						</td>
						<td width="30%">
							<!-- Contact information -->
							<xsl:if test="cac:AccountingSupplierParty/cac:Party/cac:Contact !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/>
								</b>
								<br/>
								<xsl:apply-templates select="cac:AccountingSupplierParty/cac:Party" mode="accsupcontact"/>
							</xsl:if>
						</td>
					</tr>
				</table>
				<br/>
				<!-- End creditnote head -->
				<!-- Start creditnote line-->
				<table class="ItemsTable" border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr class="UBLCreditnoteLineHeader">
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
						</th>
						<th class="ItemLineHeaderAmount">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='LineExtensionAmountLine']"/>
						</th>
						<th class="ItemLineHeaderAmount">
							<!--Amounttype - no heading-->
						</th>
					</tr>
					<xsl:apply-templates select="cac:CreditNoteLine"/>
					<!--Border between item lines and tax/total-->
					<tr>
						<td colspan="10">
							<hr class="HrTaxTotalBorder"/>
						</td>
					</tr>
					<!-- Start tax and total -->
					<!-- <hr/> -->
					<!-- Line total -->
					<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="LineTotal"/>
					<!-- Fee header -->
					<xsl:apply-templates select="cac:TaxTotal" mode="afgift"/>
					<!-- Discount and fee header -->
					<xsl:apply-templates select="cac:AllowanceCharge" mode="total"/>
					<!-- Moms  -->
					<xsl:apply-templates select="cac:TaxTotal" mode="moms"/>
					<!-- Creditnote total  -->
					<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="TotalKreditNota"/>
					<!-- End tax and total -->
				</table>
				<!-- End creditnote line-->
				<hr/>
				<!-- Start free text and references -->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td>
							<xsl:if test="cac:InvoicePeriod !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='InvoicePeriod']"/>
								</b>&#160;<xsl:apply-templates select="cac:InvoicePeriod"/>
								<br/>
							</xsl:if>
							<xsl:if test="cac:DiscrepancyResponse/cbc:ReferenceID !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ReferenceID']"/>
								</b>&#160;
							<xsl:apply-templates select="cac:DiscrepancyResponse/cbc:ReferenceID"/>&#160;-&#160;
							<xsl:apply-templates select="cac:DiscrepancyResponse/cbc:Description"/>
								<br/>
							</xsl:if>
							<xsl:if test="cac:BillingReference !=''">
								<xsl:apply-templates select="cac:BillingReference"/>
							</xsl:if>
							<xsl:if test="cac:OrderReference/cac:DocumentReference !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderDocumentReference']"/>
								</b>&#160;<xsl:apply-templates select="cac:OrderReference" mode="reference"/>
								<br/>
							</xsl:if>
							<xsl:if test="cbc:Note[.!='']">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Notes']"/>
								</b>&#160;<xsl:apply-templates select="cbc:Note" mode="header"/>
								<br/>
							</xsl:if>
							<xsl:if test="cac:ContractDocumentReference/cbc:ID !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ContractDocumentReference']"/>
								</b>&#160;<xsl:apply-templates select="cac:ContractDocumentReference"/>
								<br/>
							</xsl:if>
							<xsl:if test="cac:AdditionalDocumentReference/cbc:ID !=''">
								<b>
									<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AdditionalDocumentReferenceID']"/>
								</b>&#160;<xsl:apply-templates select="cac:AdditionalDocumentReference"/>
							</xsl:if>
							<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="supp"/>
						</td>
					</tr>
				</table>
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
