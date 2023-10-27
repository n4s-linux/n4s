<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************

		OIOUBL Instance Documentation	

		title= OrderResponseSimpleHTML.xsl
		replaces=
		publisher= "Digitaliseringsstyrelsen"
		Creator= Finn Christensen and Charlotte Dahl Skovhus
		created= 2006-12-01
		issued= 2008-01-22
		modified= $Date$
		$Revision$
		conformsTo= UBL-OrderResponseSimple-2.0.xsd
		description= "Stylesheet for displaying a OIOUBL-2.01 OrderResponseSimple"
		rights= "It can be used following the Common Creative Licence"
		
		all terms derived from http://dublincore.org/documents/dcmi-terms/

		For more information, see www.oioubl.dk	or email oioubl@itst.dk
		
******************************************************************************************************************
-->
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:n1="urn:oasis:names:specification:ubl:schema:xsd:OrderResponseSimple-2"
                xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                xmlns:ccts="urn:oasis:names:specification:ubl:schema:xsd:CoreComponentParameters-2"
                xmlns:sdt="urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2"
                xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
                exclude-result-prefixes="n1 cac cbc ccts sdt udt">

    <xsl:include href="OIOUBL_CommonTemplates.xsl"/>
    <xsl:output method="html" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN" doctype-system="http://www.w3.org/TR/html4/loose.dtd" indent="yes"/>
    <xsl:strip-space elements="*"/>
    <xsl:template match="/">
        <xsl:apply-templates/>
    </xsl:template>

    <xsl:template match="n1:OrderResponseSimple">

        <!-- Start HTML -->
        <html>
            <head>
                <link rel="Stylesheet" type="text/css" href="OIOUBL.css"/>
                <title><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Release']"/></title>
            </head>
            <body>
                <!-- Start på responsehovedet -->
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="4">
                            <!-- indsætter header -->
                            <h3>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OIOUBLOrdRSim']"/>
                                <xsl:if test="cbc:CopyIndicator ='true'">
                                    <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CopyIndicator']"/>
                                </xsl:if>
                            </h3>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!-- indsætter leverandøradressen -->
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='SellerParty']"/>
                            </b>
                            <br/>
                            <xsl:apply-templates select="cac:SellerSupplierParty"/>
                        </td>
                        <td>
                            <!-- indsætter kontaktoplysninger -->
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/>
                            </b>
                            <br/>
                            <xsl:apply-templates select="cac:SellerSupplierParty/cac:Party" mode="selsupcontact"/>
                        </td>
                        <td>
                            <!-- indsætter Kreditor -->
                            <xsl:if test="cac:AccountingSupplierParty !=''">
                                <xsl:if test="cac:AccountingSupplierParty/cac:Party/cac:PartyIdentification/cbc:ID != cac:SellerSupplierParty/cac:Party/cac:PartyIdentification/cbc:ID">
                                    <xsl:if test="cac:AccountingSupplierParty/cac:Party/cac:PartyName/cbc:Name != cac:SellerSupplierParty/cac:Party/cac:PartyName/cbc:Name">
                                        <b>
                                            <xsl:value-of
                                                    select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingSupplierOrd']"/>
                                        </b>
                                        <br/>
                                        <xsl:apply-templates select="cac:AccountingSupplierParty"/>
                                    </xsl:if>
                                </xsl:if>
                            </xsl:if>
                        </td>
                        <td valign="top">
                            <!-- indsætter kontaktoplysninger -->
                            <xsl:if test="cac:AccountingSupplierParty/cac:Party/cac:Contact !=''">
                                <xsl:if test="cac:AccountingSupplierParty/cac:Party/cac:Contact/cbc:ID != cac:SellerSupplierParty/cac:Party/cac:Contact/cbc:ID">
                                    <b>
                                        <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/>
                                    </b>
                                    <br/>
                                    <xsl:apply-templates select="cac:AccountingSupplierParty/cac:Party" mode="accsupcontact"/>
                                </xsl:if>
                            </xsl:if>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <hr/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <!-- indsætter køberadressen -->
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='BuyerParty']"/>
                            </b>
                            <br/>
                            <xsl:apply-templates select="cac:BuyerCustomerParty"/>
                        </td>
                        <td>
                            <!-- indsætter kontaktoplysninger -->
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/>
                            </b>
                            <br/>
                            <xsl:apply-templates select="cac:BuyerCustomerParty/cac:Party" mode="buycuscontact"/>
                        </td>
                        <td>
                            <!-- indsætter Debitor -->
                            <xsl:if test="cac:AccountingCustomerParty !=''">
                                <xsl:if test="cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID != cac:BuyerCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID">
                                    <xsl:if test="cac:AccountingCustomerParty/cac:Party/cac:PartyName/cbc:Name != cac:BuyerCustomerParty/cac:Party/cac:PartyName/cbc:Name">
                                        <b>
                                            <xsl:value-of
                                                    select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingCustomerOrd']"/>
                                        </b>
                                        <br/>
                                        <xsl:apply-templates select="cac:AccountingCustomerParty"/>
                                    </xsl:if>
                                </xsl:if>
                            </xsl:if>
                            <br/>
                        </td>
                        <td>
                            <!-- indsætter kontaktoplysninger -->
                            <xsl:if test="cac:AccountingCustomerParty/cac:Party/cac:Contact !=''">
                                <xsl:if test="cac:AccountingCustomerParty/cac:Party/cac:Contact/cbc:ID != cac:BuyerCustomerParty/cac:Party/cac:Contact/cbc:ID">
                                    <b>
                                        <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/>
                                    </b>
                                    <br/>
                                    <xsl:apply-templates select="cac:AccountingCustomerParty/cac:Party" mode="acccuscontact"/>
                                </xsl:if>
                            </xsl:if>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <hr/>
                        </td>
                    </tr>
                    <tr>
                        <td width="26%">
                            <b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderResponseID']"/>&#160;
                            </b>
                            <!-- indsætter Ordrerespons ID -->
                            <xsl:value-of select="cbc:ID"/>
                        </td>
                        <td width="27%">
                            <b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='IssueDate']"/>&#160;
                            </b>
                            <!-- indsætter ordrerespons dato -->
                            <xsl:value-of select="cbc:IssueDate"/>
                        </td>
                    </tr>
                </table>
                <hr/>
                <!-- Slut på ordrehovedet -->

                <!--Start ordrereference og respons-->
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="10%">
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderID']"/>
                            </b>
                        </td>
                        <td width="25%">
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderUUID']"/>
                            </b>
                        </td>
                        <td width="10%">
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderIssueDate']"/>
                            </b>
                        </td>
                        <td width="15%">
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AcceptedIndicator']"/>
                            </b>
                        </td>
                        <td width="40%">
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='RejectionNote']"/>
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <xsl:apply-templates select="cac:OrderReference" mode="header"/>
                        <td>
                            <xsl:choose>
                                <xsl:when test="cbc:AcceptedIndicator = 'true'">
                                    <xsl:value-of
                                            select="$moduleDoc/module/document-merge/g-funcs/g[@name='AcceptedIndicatorTrue']"/>
                                </xsl:when>
                                <xsl:when test="cbc:AcceptedIndicator = 'false'">
                                    <xsl:value-of
                                            select="$moduleDoc/module/document-merge/g-funcs/g[@name='AcceptedIndicatorFalse']"/>
                                </xsl:when>
                            </xsl:choose>
                        </td>
                        <td>
                            <xsl:apply-templates select="cbc:RejectionNote"/>
                        </td>
                    </tr>
                </table>
                <hr/>
                <!--Slut ordrereference og respons-->

                <!-- Start på fritekst og referencer-->
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td colspan="4">
                            <xsl:if test="cbc:Note[.!='']">
                                <b>
                                    <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Notes']"/>
                                </b>
                                &#160;
                                <xsl:apply-templates select="cbc:Note"/>
                                <br/>
                            </xsl:if>
                            <xsl:if test="cac:OrderReference/cac:DocumentReference !=''">
                                <b>
                                    <xsl:value-of
                                            select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderDocumentReference']"/>
                                </b>
                                &#160;
                                <xsl:apply-templates select="cac:OrderReference" mode="reference"/>
                                <br/>
                            </xsl:if>
                            <xsl:if test="cac:AdditionalDocumentReference/cbc:ID !=''">
                                <b>
                                    <xsl:value-of
                                            select="$moduleDoc/module/document-merge/g-funcs/g[@name='AdditionalDocumentReferenceID']"/>
                                </b>
                                &#160;
                                <xsl:apply-templates select="cac:AdditionalDocumentReference"/>
                            </xsl:if>
                        </td>
                    </tr>
                </table>
                <hr size="2"/>
                <!-- Slut på fritekst og referencer-->

                <!-- Start på OIOUBL footer -->
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>
                            <b>
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OIOUBLDoc']"/>
                            </b>
                            <br/>
                            <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='VersionID']"/>&#160;<xsl:value-of
                                select="cbc:UBLVersionID"/>
                            <br/>
                            <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CustomizationID']"/>&#160;<xsl:value-of
                                select="cbc:CustomizationID"/>
                            <br/>
                            <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ProfileID']"/>&#160;<xsl:value-of
                                select="cbc:ProfileID"/>
                            <br/>
                            <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ID']"/>&#160;<xsl:value-of
                                select="cbc:ID"/>
                            <br/>
                            <xsl:if test="cbc:UUID !=''">
                                <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='UUID']"/>&#160;<xsl:value-of
                                    select="cbc:UUID"/>
                            </xsl:if>
                        </td>
                        <xsl:if test="cac:Signature !=''">
                            <td>
                                <xsl:apply-templates select="cac:Signature"/>
                            </td>
                        </xsl:if>
                    </tr>
                </table>
                <!-- Slut på OIOUBL footer -->
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
