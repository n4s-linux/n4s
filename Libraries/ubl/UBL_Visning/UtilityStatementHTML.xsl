<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************

		OIOUBL Stylesheet	

		title= UtilityStatementHTML.xsl
		replaces= UTS_2_HTML.xsl	
		publisher= "Digitaliseringsstyrelsen"
		creator= "Finn Christensen"
		created= 2010-01-28
		issued= 2010-01-28
		modified= $Date: 2013-02-13 14:37:50 +0100 (on, 13 feb 2013) $
		$Revision: 2850 $
		conformsTo= OIOUBL stylesheet package
		description= "This document is produced as part of the OIOUBL stylesheet package"
		rights= "It can be used following the Common Creative Licence"
		
		all terms derived from http://dublincore.org/documents/dcmi-terms/

		For more information, see www.oioubl.dk	or email oioubl@itst.dk
		
******************************************************************************************************************
-->
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:n1="urn:oasis:names:specification:ubl:schema:xsd:UtilityStatement-2"
                xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                xmlns:ccts="urn:oasis:names:specification:ubl:schema:xsd:CoreComponentParameters-2"
                xmlns:sdt="urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2"
                xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
                exclude-result-prefixes="n1 cac cbc ccts sdt udt">

    <xsl:include href="OIOUBL_CommonTemplates.xsl"/>
    <xsl:output method="html" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN"
                doctype-system="http://www.w3.org/TR/html4/loose.dtd" indent="yes"/>
    <xsl:strip-space elements="*"/>

    <xsl:template match="/">
        <xsl:apply-templates/>
    </xsl:template>

    <xsl:template match="n1:UtilityStatement">

        <!-- Faste parametre (disse kan/skal tilpasses inden brug af stylesheet) -->
        <!-- Slut faste parametre -->

        <!-- Betingede parametre -->

        <!-- Globale variable -->
        <xsl:variable name="CurrencyCode" select="cbc:DocumentCurrencyCode"/>
        <xsl:variable name="UtsType" select="cbc:UtilityStatementTypeCode"/>


        <!-- Start HTML -->
        <html>
            <head>
                <title><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Release']"/></title>
            </head>


            <!-- opretter HTML med tabeller -->
            <body>

                <!-- Start på UtilityStatement header -->
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td valign="top" colspan="4">
                            <font face="Arial">
                                <b>OIOUBL Utility Statement -
                                    <xsl:value-of select="cbc:UtilityStatementTypeCode"/>
                                </b>
                            </font>
                        </td>
                        <td valign="top"></td>
                        <td valign="top"></td>
                        <td valign="top"></td>
                    </tr>
                    <tr>
                        <td width="100%" valign="top" colspan="4" bgcolor="#FFFFFF" height="2">
                            <hr style="color:#77B321; background-color: #77B321; height: 5px; border: 0"/>
                        </td>
                    </tr>

                    <tr>
                        <td valign="top" colspan="2" bgcolor="#FFFFFF">
                            <font face="Arial" size="2">
                                <!-- indsætter køberadressen -->
                                <b>Køber</b>
                                <br/>
                                <xsl:for-each select="cac:ReceiverParty">
                                    <xsl:value-of select="cac:PartyName/cbc:Name"/>
                                    <br/>
                                    <xsl:value-of select="cac:PostalAddress/cbc:StreetName"/><xsl:text> </xsl:text><xsl:value-of
                                        select="cac:PostalAddress/cbc:BuildingNumber"/>
                                    <br/>
                                    <xsl:value-of select="cac:PostalAddress/cbc:PostalZone"/><xsl:text> </xsl:text><xsl:value-of
                                        select="cac:PostalAddress/cbc:CityName"/>
                                    <br/>
                                    <br/>
                                    EndpointID:
                                    <xsl:value-of select="cbc:EndpointID"/>
                                    <br/>
                                    <xsl:value-of select="cac:PartyIdentification/cbc:ID/@schemeID"/>:
                                    <xsl:value-of select="cac:PartyIdentification/cbc:ID"/>
                                    <br/>
                                </xsl:for-each>
                                Kundenr:
                                <xsl:value-of select="cac:ReceiverParty/cac:Contact/cbc:ID"/>
                                <br/>
                                <xsl:if test="string(cac:SubscriberConsumption/cac:UtilityConsumptionPoint/cac:WebSiteAccess/cbc:Password)">Adgangskode:
                                    <xsl:value-of
                                            select="cac:SubscriberConsumption/cac:UtilityConsumptionPoint/cac:WebSiteAccess/cbc:Password"/>
                                    <br/>
                                </xsl:if>
                            </font>
                        </td>
                        <td valign="top" bgcolor="#FFFFFF">
                            <font face="Arial" size="2">
                                <!-- indsætter juridisk adresse -->
                                <b>Juridisk</b>
                                <br/>

                                <xsl:for-each select="cac:BuyerCustomerParty/cac:Party">
                                    <xsl:value-of select="cac:PartyName/cbc:Name"/>
                                    <br/>
                                    <xsl:value-of select="cac:PostalAddress/cbc:StreetName"/><xsl:text> </xsl:text><xsl:value-of
                                        select="cac:PostalAddress/cbc:BuildingNumber"/>
                                    <br/>
                                    <xsl:value-of select="cac:PostalAddress/cbc:PostalZone"/><xsl:text> </xsl:text><xsl:value-of
                                        select="cac:PostalAddress/cbc:CityName"/>
                                    <br/>
                                    <br/>
                                    EndpointID:
                                    <xsl:value-of select="cbc:EndpointID"/>
                                    <br/>
                                    <xsl:value-of select="cac:PartyIdentification/cbc:ID/@schemeID"/>:
                                    <xsl:value-of select="cac:PartyIdentification/cbc:ID"/>
                                    <br/>
                                </xsl:for-each>

                            </font>
                        </td>
                        <td valign="top" bgcolor="#FFFFFF">
                            <font face="Arial" size="2">
                                <!-- indsætter Subscriber -->
                                <b>Forbruger</b>
                                <br/>

                                <xsl:for-each select="cac:SubscriberParty">
                                    <xsl:value-of select="cac:PartyName/cbc:Name"/>
                                    <br/>
                                    <xsl:value-of select="cac:PostalAddress/cbc:StreetName"/><xsl:text> </xsl:text><xsl:value-of
                                        select="cac:PostalAddress/cbc:BuildingNumber"/>
                                    <br/>
                                    <xsl:value-of select="cac:PostalAddress/cbc:PostalZone"/><xsl:text> </xsl:text><xsl:value-of
                                        select="cac:PostalAddress/cbc:CityName"/>
                                    <br/>
                                    <br/>
                                    EndpointID:
                                    <xsl:value-of select="cbc:EndpointID"/>
                                    <br/>
                                    <xsl:value-of select="cac:PartyIdentification/cbc:ID/@schemeID"/>:
                                    <xsl:value-of select="cac:PartyIdentification/cbc:ID"/>
                                    <br/>
                                </xsl:for-each>

                            </font>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" valign="top" colspan="4" bgcolor="#FFFFFF" height="1">
                            <hr style="color:#77B321; background-color: #77B321; height: 2px; border: 0"/>
                        </td>
                    </tr>


                    <tr>
                        <td valign="top" colspan="2" bgcolor="#FFFFFF">
                            <font face="Arial" size="2">
                                <!-- indsætter leverandøradressen -->
                                <b>Leverandør</b>
                                <br/>
                                <xsl:for-each select="cac:SenderParty">
                                    <xsl:value-of select="cac:PartyName/cbc:Name"/>
                                    <br/>
                                    <xsl:value-of select="cac:PostalAddress/cbc:StreetName"/><xsl:text> </xsl:text><xsl:value-of
                                        select="cac:PostalAddress/cbc:BuildingNumber"/>
                                    <br/>
                                    <xsl:value-of select="cac:PostalAddress/cbc:PostalZone"/><xsl:text> </xsl:text><xsl:value-of
                                        select="cac:PostalAddress/cbc:CityName"/>
                                    <br/>
                                    <br/>
                                    EndpointID:
                                    <xsl:value-of select="cbc:EndpointID"/>
                                    <br/>
                                    <xsl:value-of select="cac:PartyIdentification/cbc:ID/@schemeID"/>:
                                    <xsl:value-of select="cac:PartyIdentification/cbc:ID"/>
                                    <br/>
                                </xsl:for-each>
                            </font>
                        </td>
                        <td valign="top" colspan="2" bgcolor="#FFFFFF">
                            <font face="Arial" size="2">
                                <!-- indsætter kontaktoplysninger -->
                                <b>Kontaktoplysninger</b>
                                <br/>
                                Tlf.:
                                <xsl:value-of select="cac:SenderParty/cac:Contact/cbc:Telephone"/>
                                <br/>
                                Email.:
                                <xsl:value-of select="cac:SenderParty/cac:Contact/cbc:ElectronicMail"/>
                                <br/>
                            </font>
                        </td>
                    </tr>

                    <tr>
                        <td width="100%" valign="top" colspan="4" bgcolor="#FFFFFF" height="1">
                            <hr style="color:#77B321; background-color: #77B321; height: 2px; border: 0"/>
                        </td>
                    </tr>
                    <tr>
                        <td width="26%" valign="top" bgcolor="#FFFFFF">
                            <font face="Arial" size="2">
                                <b>Specifikationsnr:</b>
                                <!-- indsætter Specifikationsnummer -->
                                <xsl:value-of select="cbc:ID"/>
                            </font>
                        </td>
                        <td width="26%" valign="top" bgcolor="#FFFFFF">
                            <font face="Arial" size="2">
                                <b>Fakturanr:</b>
                                <!-- indsætter Fakturanummer  -->
                                <xsl:value-of select="cac:ParentDocumentReference/cbc:ID"/>
                            </font>
                        </td>
                        <td width="23%" valign="top" bgcolor="#FFFFFF">
                        </td>
                        <td width="27%" valign="top" bgcolor="#FFFFFF">
                            <font face="Arial" size="2">
                                <b>Dato:</b>
                                <!-- indsætter faktura dato -->
                                <xsl:value-of select="cbc:IssueDate"/>
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" valign="top" colspan="4" bgcolor="#FFFFFF" height="1">
                            <hr style="color:#77B321; background-color: #77B321; height: 2px; border: 0"/>
                        </td>
                    </tr>
                </table>
                <br/>
                <!-- Slut på UtilityStatement header -->


                <!-- Start på Utility invoice (forbrugssted) -->

                <xsl:for-each select="cac:SubscriberConsumption">


                    <!-- Bestem 1: Normal, 2. Tele og 3: Udvidet samtalespec -->
                    <xsl:variable name="t1">
                        <xsl:choose>
                            <xsl:when test="string(cac:Consumption/cbc:UtilityStatementTypeCode)">
                                <xsl:value-of select="cac:Consumption/cbc:UtilityStatementTypeCode"/>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:value-of select="$UtsType"/>
                            </xsl:otherwise>
                        </xsl:choose>
                    </xsl:variable>
                    <xsl:variable name="FormatType">
                        <xsl:choose>
                            <xsl:when test="$t1 = 'Tele'">2</xsl:when>
                            <xsl:when test="$t1 = 'TeleExtended'">3</xsl:when>
                            <xsl:otherwise>1</xsl:otherwise>
                        </xsl:choose>
                    </xsl:variable>

                    <!-- Betingede parametre -->
                    <xsl:variable name="Tele">
                        <xsl:choose>
                            <xsl:when test="$t1 = 'Tele'">true</xsl:when>
                            <xsl:otherwise>false</xsl:otherwise>
                        </xsl:choose>
                    </xsl:variable>


                    <!-- Normal + Tele  -->
                    <xsl:if test="$FormatType = 1 or $FormatType = 2">


                        <table border="0" width="100%" cellspacing="0" cellpadding="2">


                            <tr>
                                <td valign="top" colspan="4">
                                    <font face="Arial">
                                        <b><xsl:value-of select="$t1"/>,&#160;<xsl:value-of
                                                select="cbc:SpecificationTypeCode"/>,&#160;specifikation for aftagenr:
                                            <xsl:value-of select="cac:UtilityConsumptionPoint/cbc:ID"/>
                                        </b>
                                    </font>
                                </td>
                                <td valign="top"></td>
                                <td valign="top"></td>
                                <td valign="top"></td>
                            </tr>

                            <tr>
                                <td width="100%" valign="top" colspan="4" bgcolor="#FFFFFF" height="1">
                                    <hr style="color:#77B321; background-color: #77B321; height: 2px; border: 0"/>
                                </td>
                            </tr>

                            <tr>
                                <td width="30%" valign="top" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        <!-- indsætter fakturainformation labels -->
                                        <b>Afregningsinformation</b>
                                        <br/>
                                        Kontonummer:
                                        <br/>
                                        Kategori:
                                        <br/>
                                        Leveringsperiode:
                                        <br/>
                                        Beskrivelse:
                                        <br/>
                                    </font>
                                </td>
                                <td width="70%" valign="top" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        <!-- indsætter fakturainformation værdier -->
                                        <br/>
                                        <xsl:value-of select="cbc:ConsumptionID"/>
                                        <br/>
                                        <xsl:value-of select="cbc:SpecificationTypeCode"/>
                                        <br/>
                                        <xsl:value-of select="cac:Consumption/cac:MainPeriod/cbc:StartDate"/><xsl:text> til </xsl:text><xsl:value-of
                                            select="cac:Consumption/cac:MainPeriod/cbc:EndDate"/>
                                        <br/>
                                        <xsl:value-of select="cbc:Note"/>
                                        <br/>
                                    </font>
                                </td>
                            </tr>


                            <!-- indsætter SubscriberParty -->
                            <xsl:if test="cac:SubscriberParty != ''">
                                <tr>
                                    <td width="20%" valign="top" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <!-- indsætter data labels -->
                                            <br/>
                                            <b>Forbruger</b>
                                            <br/>
                                            Navn:
                                            <br/>
                                            ID:
                                            <br/>
                                        </font>
                                    </td>
                                    <td width="80%" valign="top" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <!-- indsætter data værdier -->
                                            <br/>
                                            <br/>
                                            <xsl:value-of select="cac:SubscriberParty/cac:PartyName/cbc:Name"/>
                                            <br/>
                                            <xsl:value-of select="cac:SubscriberParty/cac:PartyIdentification/cbc:ID"/>
                                            <br/>
                                        </font>
                                    </td>
                                </tr>
                            </xsl:if>

                            <tr>
                                <td width="20%" valign="top" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        <!-- indsætter forbrugssted data labels -->
                                        <br/>
                                        <b>Forbrugssted</b>
                                        <br/>
                                        Aftagenr:
                                        <br/>
                                        <xsl:if test="count(cac:UtilityConsumptionPoint/cac:Address) &gt; 0">Adresse:
                                            <br/>
                                            <br/>
                                        </xsl:if>
                                        <xsl:if test="count(cac:UtilityConsumptionPoint/cac:UtilityMeter/cac:MeterReading) = 0 and string(cac:UtilityConsumptionPoint/cac:UtilityMeter/cbc:MeterNumber)">Målernr:
                                            <br/>
                                        </xsl:if>
                                        <xsl:if test="cac:UtilityConsumptionPoint/cbc:TotalDeliveredQuantity[.!='']">Totalt forbrug:
                                            <br/>
                                        </xsl:if>
                                    </font>
                                </td>
                                <td width="80%" valign="top" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        <!-- indsætter forbrugssted data værdier -->
                                        <br/>
                                        <br/>
                                        <xsl:value-of select="cac:UtilityConsumptionPoint/cbc:ID"/>
                                        <br/>
                                        <xsl:if test="count(cac:UtilityConsumptionPoint/cac:Address) &gt; 0">
                                            <xsl:value-of select="cac:UtilityConsumptionPoint/cac:Address/cbc:StreetName"/><xsl:text> </xsl:text><xsl:value-of
                                                select="cac:UtilityConsumptionPoint/cac:Address/cbc:BuildingNumber"/>
                                            <br/>
                                            <xsl:value-of select="cac:UtilityConsumptionPoint/cac:Address/cbc:PostalZone"/><xsl:text> </xsl:text><xsl:value-of
                                                select="cac:UtilityConsumptionPoint/cac:Address/cbc:CityName"/>
                                            <br/>
                                        </xsl:if>
                                        <xsl:if test="count(cac:UtilityConsumptionPoint/cac:UtilityMeter/cac:MeterReading) = 0">
                                            <xsl:value-of select="cac:UtilityConsumptionPoint/cac:UtilityMeter/cbc:MeterNumber"/>
                                            <br/>
                                        </xsl:if>
                                        <xsl:if test="cac:UtilityConsumptionPoint/cbc:TotalDeliveredQuantity[.!='']"><xsl:value-of
                                                select="cac:UtilityConsumptionPoint/cbc:TotalDeliveredQuantity"/>&#160;<xsl:value-of
                                                select="cac:UtilityConsumptionPoint/cbc:TotalDeliveredQuantity/@unitCode"/>
                                            <br/>
                                        </xsl:if>
                                    </font>
                                </td>
                            </tr>
                        </table>


                        <!-- indsætter måler(e) og måledata -->
                        <xsl:if test="count(cac:UtilityConsumptionPoint/cac:UtilityMeter) != 0 and count(cac:UtilityConsumptionPoint/cac:UtilityMeter/cac:MeterReading) != 0">

                            <!-- indsætter streg + overskrift -->
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td width="100%" valign="top" bgcolor="#FFFFFF" height="1">
                                        <hr style="color:#77B321; background-color: #77B321; height: 2px; border: 0"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Måler(e) og måledata</b>
                                        </font>
                                    </td>
                                </tr>
                            </table>

                            <!-- indsætter tabel til målinger -->
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Målernr</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Forbrugstype</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Metode</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Gl. visning</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Dato</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Ny visning</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Dato</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Aflæst forbrug</b>
                                        </font>
                                    </td>
                                    <td valign="top" align="right">
                                        <font face="Arial" size="2">
                                            <b>Forbrug</b>
                                        </font>
                                    </td>
                                </tr>

                                <!-- Start på målinger -->
                                <xsl:for-each select="cac:UtilityConsumptionPoint/cac:UtilityMeter">

                                    <xsl:variable name="maalernr" select="cbc:MeterNumber"/>
                                    <xsl:variable name="maalernavn" select="cbc:MeterName"/>
                                    <xsl:variable name="bkonstant" select="cbc:MeterConstant"/>
                                    <xsl:variable name="actualbrv" select="cbc:ActualHeatingQuantity"/>
                                    <xsl:variable name="actualbrvunit" select="cbc:ActualHeatingQuantity/@unitCode"/>
                                    <xsl:variable name="estimatedbrv" select="cbc:EstimatedHeatingQuantity"/>
                                    <xsl:variable name="estimatedbrvunit" select="cbc:EstimatedHeatingQuantity/@unitCode"/>
                                    <xsl:variable name="faktorbrv" select="cbc:HeatingCorrection"/>
                                    <xsl:variable name="measuretotal" select="cbc:TotalMeasuredQuantity"/>
                                    <xsl:variable name="measuretotalunit" select="cbc:TotalMeasuredQuantity/@unitCode"/>
                                    <xsl:variable name="maalertotal" select="cbc:TotalDeliveredQuantity"/>
                                    <xsl:variable name="maalertotalunit" select="cbc:TotalDeliveredQuantity/@unitCode"/>
                                    <xsl:variable name="metode" select="cbc:MeterReadingMethodCode"/>
                                    <xsl:variable name="kommentar" select="cbc:MeterReadingComments"/>

                                    <xsl:for-each select="cac:MeterReading">

                                        <xsl:variable name="total">
                                            <xsl:choose>
                                                <xsl:when test="not (string($maalertotal))">
                                                    <xsl:value-of select="cbc:DeliveredQuantity"/>
                                                </xsl:when>
                                                <xsl:when test="string($maalertotal) and (count(../cac:MeterReading) > 1)">
                                                    <xsl:value-of select="cbc:DeliveredQuantity"/>
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <xsl:value-of select="$maalertotal"/>
                                                </xsl:otherwise>
                                            </xsl:choose>
                                        </xsl:variable>

                                        <xsl:variable name="totalunit">
                                            <xsl:choose>
                                                <xsl:when test="not (string($maalertotal))">
                                                    <xsl:value-of select="cbc:DeliveredQuantity/@unitCode"/>
                                                </xsl:when>
                                                <xsl:when test="string($maalertotal) and (count(../cac:MeterReading) > 1)">
                                                    <xsl:value-of select="cbc:DeliveredQuantity/@unitCode"/>
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <xsl:value-of select="$maalertotalunit"/>
                                                </xsl:otherwise>
                                            </xsl:choose>
                                        </xsl:variable>

                                        <tr>
                                            <td width="8%" valign="top">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Målernr -->
                                                    <xsl:value-of select="$maalernr"/>
                                                </font>
                                            </td>

                                            <td width="8%" valign="top">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Forbrugstype -->
                                                    <xsl:value-of select="cbc:MeterReadingTypeCode"/>
                                                </font>
                                            </td>


                                            <td width="15%" valign="top">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Metode -->
                                                    <xsl:value-of select="$metode"/>
                                                </font>
                                            </td>

                                            <td width="10%" valign="top">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Gl. visning -->
                                                    <xsl:value-of select="cbc:PreviousMeterQuantity"/>&#160;<xsl:value-of
                                                        select="cbc:PreviousMeterQuantity/@unitCode"/>
                                                </font>
                                            </td>

                                            <td width="15%" valign="top">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Dato -->
                                                    <xsl:value-of select="cbc:PreviousMeterReadingDate"/>
                                                </font>
                                            </td>

                                            <td width="10%" valign="top">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Ny visning -->
                                                    <xsl:value-of select="cbc:LatestMeterQuantity"/>&#160;<xsl:value-of
                                                        select="cbc:LatestMeterQuantity/@unitCode"/>
                                                </font>
                                            </td>

                                            <td width="10%" valign="top">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Dato -->
                                                    <xsl:value-of select="cbc:LatestMeterReadingDate"/>
                                                </font>
                                            </td>

                                            <td width="10%" valign="top">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Aflæst forbrug -->
                                                    <xsl:value-of select="cbc:DeliveredQuantity"/>&#160;<xsl:value-of
                                                        select="cbc:DeliveredQuantity/@unitCode"/>
                                                </font>
                                            </td>


                                            <td width="15%" valign="top" align="right">
                                                <font face="Arial" size="2">
                                                    <!-- indsætter Forbrug -->
                                                    <xsl:value-of select="$total"/>&#160;<xsl:value-of select="$totalunit"/>
                                                </font>
                                            </td>
                                        </tr>

                                        <!-- indsætter yderligere oplysninger -->
                                        <tr>
                                            <td valign="top">
                                                <font face="Arial" size="2"></font>
                                            </td>

                                            <td colspan="8" valign="top">
                                                <font face="Arial" size="1">

                                                    <xsl:if test="string($maalernavn)">
                                                        <b>Målernavn:</b>&#160;<xsl:value-of select="$maalernavn"/>
                                                        <br/>
                                                    </xsl:if>
                                                    <xsl:if test="string($kommentar)">
                                                        <b>Kommentar:</b>&#160;<xsl:value-of select="$kommentar"/>
                                                        <br/>
                                                    </xsl:if>
                                                    <xsl:if test="$bkonstant != '1.000'">
                                                        <b>Konstant:</b>&#160;<xsl:value-of select="$bkonstant"/>
                                                        <br/>
                                                    </xsl:if>

                                                    <xsl:if test="string($actualbrv)">
                                                        <b>Korrektion:</b>&#160;Anmeldt brændværdi:&#160;<xsl:value-of
                                                            select="$estimatedbrv"/>&#160;<xsl:value-of
                                                            select="$estimatedbrvunit"/>,&#160;Aktuel brændværdi:&#160;<xsl:value-of
                                                            select="$actualbrv"/>&#160;<xsl:value-of select="$actualbrvunit"/>,&#160;Korrektionsfaktor:&#160;<xsl:value-of
                                                            select="$faktorbrv"/>,&#160;Aflæst forbrug:&#160;<xsl:value-of
                                                            select="$measuretotal"/>&#160;<xsl:value-of
                                                            select="$measuretotalunit"/>,&#160;Korrigeret forbrug:&#160;<xsl:value-of
                                                            select="$maalertotal"/>&#160;<xsl:value-of select="$maalertotalunit"/>
                                                        <br/>
                                                    </xsl:if>

                                                </font>
                                            </td>
                                        </tr>

                                    </xsl:for-each>
                                </xsl:for-each>

                            </table>
                            <br/>
                        </xsl:if>


                        <!-- Start på subniveau (leverandør) -->

                        <xsl:for-each select="cac:SupplierConsumption">
                            <hr style="color:#77B321; background-color: #77B321; height: 1px; border: 0"/>
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td width="100%" valign="top" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <!-- indsætter leverandør information labels -->
                                            <xsl:choose>
                                                <xsl:when test="string(cac:Consumption/cbc:UtilityStatementTypeCode)">
                                                    <b><xsl:value-of select="cac:Consumption/cbc:UtilityStatementTypeCode"/>,&#160;<xsl:value-of
                                                            select="cbc:Description"/>
                                                    </b>
                                                    &#160;
                                                </xsl:when>
                                                <xsl:otherwise>
                                                    <b>
                                                        <xsl:value-of select="cbc:Description"/>
                                                    </b>
                                                    &#160;
                                                </xsl:otherwise>
                                            </xsl:choose>

                                            <xsl:if test="cac:UtilitySupplierParty/cac:PartyName/cbc:Name[.!='']">
                                                <xsl:value-of select="cac:UtilitySupplierParty/cac:PartyName/cbc:Name"/>&#160;
                                                CVR:&#160;<xsl:value-of
                                                    select="cac:UtilitySupplierParty/cac:PartyIdentification/cbc:ID"/>&#160;
                                            </xsl:if>

                                        </font>
                                    </td>
                                </tr>
                            </table>

                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Linje nr</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Beskrivelse</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Antal</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Enhed</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Enhedspris</b>
                                        </font>
                                    </td>
                                    <xsl:choose>
                                        <xsl:when test="$Tele = 'true'">
                                            <td valign="top">
                                                <font face="Arial" size="2">
                                                    <b>Tidsforbrug</b>
                                                </font>
                                            </td>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <td valign="top">
                                                <font face="Arial" size="2"></font>
                                            </td>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                    <td valign="top" align="right">
                                        <font face="Arial" size="2">
                                            <b>Pris</b>
                                        </font>
                                        <br/>
                                    </td>
                                </tr>


                                <!-- Start på linjeniveau -->

                                <xsl:for-each select="cac:ConsumptionLine">
                                    <tr>
                                        <td width="5%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter LineID -->
                                                <xsl:value-of select="cbc:ID"/>
                                            </font>
                                        </td>
                                        <td width="50%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter ItemDescription -->
                                                <xsl:value-of select="cac:UtilityItem/cbc:Description"/>
                                            </font>
                                        </td>
                                        <td width="9%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter antal -->
                                                <xsl:variable name="antal" select="cbc:InvoicedQuantity"/>
                                                <xsl:value-of select="format-number($antal, '##0.00##')"/>
                                            </font>
                                        </td>
                                        <td width="9%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter enhed -->
                                                <xsl:value-of select="cbc:InvoicedQuantity/@unitCode"/>
                                            </font>
                                        </td>
                                        <td width="9%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter enhedspris (Price eller UnstructuredPrice) -->
                                                <xsl:choose>
                                                    <xsl:when test="cac:UnstructuredPrice">
                                                        <xsl:value-of select="cac:UnstructuredPrice/cbc:PriceAmount"/>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <xsl:variable name="enhedspris" select="cac:Price/cbc:PriceAmount"/>
                                                        <xsl:value-of select="format-number($enhedspris, '##0.00##')"/>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </font>
                                        </td>
                                        <td width="4%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter tidsforbrug (UnstructuredPrice) -->
                                                <xsl:choose>
                                                    <xsl:when test="cac:UnstructuredPrice">
                                                        <xsl:value-of select="cac:UnstructuredPrice/cbc:TimeAmount"/>
                                                    </xsl:when>
                                                </xsl:choose>
                                            </font>
                                        </td>
                                        <td width="14%" valign="top" align="right">
                                            <font face="Arial" size="2">
                                                <!-- indsætter linietotal -->
                                                <xsl:variable name="linietotal" select="cbc:LineExtensionAmount"/>
                                                <xsl:value-of select="format-number($linietotal, '##0.00')"/>
                                            </font>
                                        </td>
                                    </tr>

                                    <!-- indsætter flere linjeoplysniger -->
                                    <tr>

                                        <td valign="top">
                                            <font face="Arial" size="2"></font>
                                        </td>

                                        <td colspan="1" valign="top">
                                            <font face="Arial" size="1">

                                                <xsl:if test="cac:Delivery/cac:DeliveryLocation/cac:Address != ''">
                                                    <b>Adresse.:</b>
                                                    <br/>
                                                    <xsl:for-each select="cac:Delivery/cac:DeliveryLocation/cac:Address">
                                                        <xsl:if test="cbc:Name != ''">
                                                            <xsl:value-of select="cbc:Name"/>
                                                            <br/>
                                                        </xsl:if>
                                                        <xsl:if test="cbc:StreetName != ''">
                                                            <xsl:value-of select="cbc:StreetName"/><xsl:text> </xsl:text><xsl:value-of
                                                                select="cbc:BuildingNumber"/>
                                                            <br/>
                                                        </xsl:if>
                                                        <xsl:if test="cbc:PostalZone != ''">
                                                            <xsl:value-of select="cbc:PostalZone"/><xsl:text> </xsl:text><xsl:value-of
                                                                select="cbc:CityName"/>
                                                            <br/>
                                                        </xsl:if>
                                                    </xsl:for-each>
                                                </xsl:if>

                                                <xsl:if test="cac:Period != ''">
                                                    <b>Periode:&#160;</b>
                                                    <xsl:value-of select="cac:Period/cbc:StartDate"/>&#160;til&#160;<xsl:value-of
                                                        select="cac:Period/cbc:EndDate"/>
                                                    <br/>
                                                </xsl:if>

                                                <xsl:if test="$Tele = 'true'">
                                                    <b><xsl:value-of select="cac:UtilityItem/cbc:SubscriberIDTypeCode"/>:&#160;
                                                    </b>
                                                    <xsl:value-of select="cac:UtilityItem/cbc:SubscriberID"/>,&#160;<xsl:value-of
                                                        select="cac:UtilityItem/cbc:SubscriberIDType"/>,&#160;<xsl:value-of
                                                        select="cac:UtilityItem/cbc:ConsumptionTypeCode"/>,&#160;<xsl:value-of
                                                        select="cac:UtilityItem/cbc:ConsumptionType"/>,&#160;<xsl:value-of
                                                        select="cac:UtilityItem/cbc:CurrentChargeTypeCode"/>,&#160;<xsl:value-of
                                                        select="cac:UtilityItem/cbc:CurrentChargeType"/>,&#160;<xsl:value-of
                                                        select="cac:UtilityItem/cbc:OneTimeChargeTypeCode"/>,&#160;<xsl:value-of
                                                        select="cac:UtilityItem/cbc:OneTimeChargeType"/>
                                                    <br/>
                                                </xsl:if>

                                                <xsl:if test="cac:UtilityItem/cac:Contract != ''">
                                                    <b>Kontrakt:&#160;</b>
                                                    <xsl:value-of
                                                            select="cac:UtilityItem/cac:Contract/cbc:ID"/>&#160;(<xsl:value-of
                                                        select="cac:UtilityItem/cac:Contract/cbc:ContractType"/>)
                                                    <br/>
                                                </xsl:if>

                                            </font>
                                        </td>

                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>
                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>
                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>
                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>
                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>

                                    </tr>


                                    <!-- Slut på linjeniveau -->
                                </xsl:for-each>
                            </table>


                            <!-- indsætter totalbeløb pr. subniveau (kun hvis mere end én instans) -->
                            <xsl:variable name="MultiSub">
                                <xsl:choose>
                                    <xsl:when test="count(../cac:SupplierConsumption) &gt; 1">true</xsl:when>
                                    <xsl:otherwise>false</xsl:otherwise>
                                </xsl:choose>
                            </xsl:variable>

                            <xsl:if test="$MultiSub = 'true'">
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">

                                    <!-- Overskrift -->
                                    <tr>
                                        <td width="70%" valign="top" bgcolor="#FFFFFF">
                                            <font face="Arial" size="2">
                                                <b>Totaler</b>
                                                <br/>
                                            </font>
                                        </td>
                                        <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                            <font face="Arial" size="2">
                                            </font>
                                        </td>
                                    </tr>

                                    <!-- Linjesum -->
                                    <tr>
                                        <td width="70%" valign="top" bgcolor="#FFFFFF">
                                            <font face="Arial" size="2">
                                                Total excl moms og afgifter
                                            </font>
                                        </td>
                                        <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                            <font face="Arial" size="2">
                                                <xsl:value-of
                                                        select="format-number(cac:Consumption/cac:LegalMonetaryTotal/cbc:LineExtensionAmount, '##0.00')"/>&#160;
                                            </font>
                                        </td>
                                    </tr>

                                    <!-- Afgifter -->
                                    <xsl:for-each select="cac:Consumption/cac:TaxTotal">

                                        <xsl:for-each select="cac:TaxSubtotal">
                                            <xsl:variable name="momspct" select="cac:TaxCategory/cbc:Percent"/>
                                            <!-- Div. afgifter-->
                                            <xsl:if test="cac:TaxCategory/cac:TaxScheme/cbc:TaxTypeCode">
                                                <tr>
                                                    <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                        <font face="Arial" size="2">

                                                            Afgift&#160; - &#160;
                                                            Kategori:&#160; <xsl:value-of select="cac:TaxCategory/cbc:ID"/>,&#160;
                                                            Pligtkode:&#160; <xsl:value-of
                                                                select="cac:TaxCategory/cac:TaxScheme/cbc:ID"/>,&#160;
                                                            <xsl:value-of select="cac:TaxCategory/cac:TaxScheme/cbc:Name"/>&#160;
                                                        </font>
                                                    </td>
                                                    <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                        <font face="Arial" size="2">

                                                            <xsl:variable name="tot4" select="cbc:TaxAmount"/>
                                                            <xsl:value-of select="format-number($tot4, '##0.00')"/>&#160;
                                                        </font>
                                                    </td>
                                                </tr>
                                            </xsl:if>

                                        </xsl:for-each>
                                    </xsl:for-each>

                                    <!-- Rabat og gebyr -->
                                    <xsl:for-each select="cac:Consumption/cac:AllowanceCharge">
                                        <tr>
                                            <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">
                                                    <xsl:choose>
                                                        <xsl:when test="cbc:ChargeIndicator ='true'">
                                                            Gebyr&#160; (<xsl:apply-templates
                                                                select="cbc:AllowanceChargeReason"/>)&#160;
                                                        </xsl:when>
                                                        <xsl:when test="cbc:ChargeIndicator ='false'">
                                                            Rabat&#160; (<xsl:apply-templates
                                                                select="cbc:AllowanceChargeReason"/>)&#160;
                                                        </xsl:when>
                                                    </xsl:choose>
                                                    <xsl:choose>
                                                        <xsl:when test="cac:TaxCategory/cbc:ID ='StandardRated'">
                                                            Momspligtig
                                                        </xsl:when>
                                                        <xsl:when test="cac:TaxCategory/cbc:ID ='ZeroRated'">
                                                            Ikke-momspligtig
                                                        </xsl:when>
                                                    </xsl:choose>
                                                </font>
                                            </td>
                                            <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">
                                                    <xsl:value-of select="format-number(cbc:Amount, '##0.00')"/>&#160;
                                                </font>
                                            </td>
                                        </tr>
                                    </xsl:for-each>

                                    <!-- Momsfri  -->
                                    <xsl:for-each select="cac:Consumption/cac:TaxTotal">
                                        <xsl:for-each select="cac:TaxSubtotal">
                                            <!-- Momsfri andel -->
                                            <xsl:if test="cac:TaxCategory/cbc:ID = 'ZeroRated' and cac:TaxCategory/cac:TaxScheme/cbc:ID ='63'">
                                                <tr>
                                                    <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                        <font face="Arial" size="2">
                                                            Momsfri andel
                                                        </font>
                                                    </td>
                                                    <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                        <font face="Arial" size="2">
                                                            <xsl:variable name="tot3" select="cbc:TaxableAmount"/>
                                                            <xsl:value-of select="format-number($tot3, '##0.00')"/>&#160;
                                                        </font>
                                                    </td>
                                                </tr>
                                            </xsl:if>
                                            <!-- Reverse Charge -->
                                            <xsl:if test="cac:TaxCategory/cbc:ID = 'ReverseCharge' and cac:TaxCategory/cac:TaxScheme/cbc:ID ='63'">
                                                <tr>
                                                    <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                        <font face="Arial" size="2">
                                                            Momsfri andel (REVERSECHARGE)
                                                        </font>
                                                    </td>
                                                    <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                        <font face="Arial" size="2">
                                                            <xsl:variable name="tot3" select="cbc:TaxableAmount"/>
                                                            <xsl:value-of select="format-number($tot3, '##0.00')"/>&#160;
                                                        </font>
                                                    </td>
                                                </tr>
                                            </xsl:if>
                                        </xsl:for-each>
                                    </xsl:for-each>

                                    <!-- Momspligtig  -->
                                    <xsl:for-each select="cac:Consumption/cac:TaxTotal">
                                        <xsl:for-each select="cac:TaxSubtotal">
                                            <xsl:if test="cac:TaxCategory/cbc:ID = 'StandardRated' and cac:TaxCategory/cac:TaxScheme/cbc:ID ='63'">
                                                <tr>
                                                    <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                        <font face="Arial" size="2">
                                                            Momsgrundlag
                                                        </font>
                                                    </td>
                                                    <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                        <font face="Arial" size="2">
                                                            <xsl:variable name="tot6" select="cbc:TaxableAmount"/>
                                                            <xsl:value-of select="format-number($tot6, '##0.00')"/>&#160;
                                                        </font>
                                                    </td>
                                                </tr>
                                            </xsl:if>
                                        </xsl:for-each>
                                    </xsl:for-each>

                                    <!-- Momstotal  -->
                                    <xsl:for-each select="cac:Consumption/cac:TaxTotal">
                                        <xsl:for-each select="cac:TaxSubtotal">
                                            <xsl:variable name="nuller">
                                                <xsl:choose>
                                                    <xsl:when test="cbc:TaxAmount = ''">nul</xsl:when>
                                                    <xsl:when test="cbc:TaxAmount = '0'">nul</xsl:when>
                                                    <xsl:when test="cbc:TaxAmount = '0.0'">nul</xsl:when>
                                                    <xsl:when test="cbc:TaxAmount = '0.00'">nul</xsl:when>
                                                    <xsl:when test="cbc:TaxAmount = '0.000'">nul</xsl:when>
                                                    <xsl:otherwise>ejnul</xsl:otherwise>
                                                </xsl:choose>
                                            </xsl:variable>
                                            <xsl:variable name="momspct" select="cac:TaxCategory/cbc:Percent"/>
                                            <xsl:if test="cac:TaxCategory/cac:TaxScheme/cbc:ID ='63'">
                                                <xsl:if test="$nuller = 'ejnul'">
                                                    <tr>
                                                        <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                            <font face="Arial" size="2">
                                                                <!-- Totalmoms -->
                                                                Total momsbeløb&#160; (<xsl:value-of
                                                                    select="format-number($momspct, '##0.00')"/>%)
                                                            </font>
                                                        </td>
                                                        <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                            <font face="Arial" size="2">
                                                                <xsl:variable name="tot4" select="cbc:TaxAmount"/>
                                                                <xsl:value-of select="format-number($tot4, '##0.00')"/>&#160;
                                                            </font>
                                                        </td>
                                                    </tr>
                                                </xsl:if>
                                            </xsl:if>
                                        </xsl:for-each>
                                    </xsl:for-each>

                                    <!-- Fakturatotal  -->
                                    <tr>
                                        <td width="70%" valign="top" bgcolor="#FFFFFF">
                                            <font face="Arial" size="2">
                                                Total incl moms og afgifter
                                            </font>
                                        </td>
                                        <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                            <font face="Arial" size="2">
                                                <xsl:value-of
                                                        select="format-number(cac:Consumption/cac:LegalMonetaryTotal/cbc:PayableAmount, '##0.00')"/>&#160;
                                            </font>
                                        </td>
                                    </tr>

                                    <!-- slut på totalbeløb pr. leverandør -->
                                </table>
                            </xsl:if>


                            <!-- indsætter Specifikation af korrektion for afkøling (subniveau) -->
                            <xsl:if test="count(cac:Consumption/cac:EnergyWaterSupply/cac:EnergyWaterCorrection) != 0">
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">

                                    <tr></tr>
                                    <tr>
                                        <td width="100%" valign="top" colspan="3" bgcolor="#FFFFFF">
                                            <font face="Arial" size="2">
                                                <br/>
                                                <b>Specifikation af korrektion for afkøling</b>
                                                <br/>
                                                <xsl:for-each
                                                        select="cac:Consumption/cac:EnergyWaterSupply/cac:EnergyWaterCorrection">

                                                    Måler nr:&#160;<xsl:value-of select="cbc:MeterNumber"/>
                                                    <br/>

                                                    Din afkøling er:&#160;<xsl:value-of
                                                        select="cbc:ActualTemperatureReductionQuantity"/>
                                                    <br/>

                                                    Normtal:&#160;<xsl:value-of select="cbc:NormalTemperatureReductionQuantity"/>
                                                    <br/>

                                                    Afvigelse fra normtal:&#160;<xsl:value-of
                                                        select="cbc:DifferenceTemperatureReductionQuantity"/>
                                                    <br/>

                                                    <xsl:if test="cbc:CorrectionUnitAmount != ''">Regulering pr. MWh pr. grad C:&#160;<xsl:value-of
                                                            select="cbc:CorrectionUnitAmount"/>
                                                        <br/>
                                                    </xsl:if>

                                                    <xsl:if test="cbc:ConsumptionEnergyQuantity != ''">Dit forbrug af fjernvarmeenergi:&#160;<xsl:value-of
                                                            select="cbc:ConsumptionEnergyQuantity"/>
                                                        <br/>
                                                    </xsl:if>

                                                    <xsl:if test="cbc:ConsumptionWaterQuantity != ''">Dit forbrug af fjernvarmevand:&#160;<xsl:value-of
                                                            select="cbc:ConsumptionWaterQuantity"/>
                                                        <br/>
                                                    </xsl:if>

                                                    <xsl:if test="cbc:CorrectionAmount != ''">Din korrektion for afkøling:&#160;<xsl:value-of
                                                            select="cbc:CorrectionAmount"/>
                                                        <br/>
                                                    </xsl:if>

                                                    <xsl:value-of select="cbc:Description"/>

                                                    <br/>
                                                </xsl:for-each>
                                            </font>
                                        </td>
                                    </tr>

                                </table>
                            </xsl:if>


                            <!-- indsætter udvikling i forbrug (subniveau) -->
                            <xsl:if test="count(cac:Consumption/cac:EnergyWaterSupply/cac:ConsumptionReport) != 0">
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">

                                    <tr></tr>
                                    <tr>
                                        <td width="100%" valign="top" colspan="3" bgcolor="#FFFFFF">
                                            <font face="Arial" size="2">
                                                <br/>
                                                <b>Udvikling i forbrug</b>
                                                <br/>
                                                <xsl:for-each
                                                        select="cac:Consumption/cac:EnergyWaterSupply/cac:ConsumptionReport/cac:ConsumptionHistory">

                                                    <xsl:if test="string(cbc:MeterNumber)">
                                                        Målernr:<xsl:text> </xsl:text><xsl:value-of select="cbc:MeterNumber"/><xsl:text> - </xsl:text>
                                                    </xsl:if>
                                                    Forbrug:<xsl:text> </xsl:text><xsl:value-of select="cbc:Quantity"/><xsl:text> </xsl:text>
                                                    <xsl:value-of select="cbc:Quantity/@unitCode"/><xsl:text> - </xsl:text>
                                                    <xsl:if test="string(cbc:Amount)">
                                                        Beløb:<xsl:text> </xsl:text><xsl:value-of select="cbc:Amount"/><xsl:text> - </xsl:text>
                                                    </xsl:if>
                                                    Periode:<xsl:text> </xsl:text><xsl:value-of
                                                        select="cac:Period/cbc:StartDate"/><xsl:text> til </xsl:text>
                                                    <xsl:value-of select="cac:Period/cbc:EndDate"/><xsl:text> - </xsl:text>
                                                    Note:<xsl:text> </xsl:text><xsl:value-of select="cbc:Description"/>
                                                    <br/>

                                                </xsl:for-each>
                                            </font>
                                        </td>
                                    </tr>
                                </table>
                            </xsl:if>


                            <!-- loop pr Utility Sub Invoice -->
                        </xsl:for-each>


                        <!-- indsætter streg -->
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <td width="100%" valign="top" bgcolor="#FFFFFF" height="1">
                                    <hr style="color:#77B321; background-color: #77B321; height: 1px; border: 0"/>
                                </td>
                            </tr>
                        </table>


                        <!-- indsætter totalbeløb (topniveau) -->
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">

                            <!-- Overskrift -->
                            <tr>
                                <td width="70%" valign="top" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        <b>Afregningstotaler</b>
                                        <br/>
                                    </font>
                                </td>
                                <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                    </font>
                                </td>
                            </tr>

                            <!-- Linjesum -->
                            <tr>
                                <td width="70%" valign="top" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        Sum af deltotaler excl moms og afgifter
                                    </font>
                                </td>
                                <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        <xsl:value-of
                                                select="format-number(cac:Consumption/cac:LegalMonetaryTotal/cbc:LineExtensionAmount, '##0.00')"/>&#160;
                                    </font>
                                </td>
                            </tr>

                            <!-- Afgifter -->
                            <xsl:for-each select="cac:Consumption/cac:TaxTotal">

                                <xsl:for-each select="cac:TaxSubtotal">
                                    <xsl:variable name="momspct" select="cac:TaxCategory/cbc:Percent"/>
                                    <!-- Div. afgifter-->
                                    <xsl:if test="cac:TaxCategory/cac:TaxScheme/cbc:TaxTypeCode">
                                        <tr>
                                            <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">

                                                    Afgift&#160; - &#160;
                                                    Kategori:&#160; <xsl:value-of select="cac:TaxCategory/cbc:ID"/>,&#160;
                                                    Pligtkode:&#160; <xsl:value-of select="cac:TaxCategory/cac:TaxScheme/cbc:ID"/>,&#160;
                                                    <xsl:value-of select="cac:TaxCategory/cac:TaxScheme/cbc:Name"/>&#160;
                                                </font>
                                            </td>
                                            <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">

                                                    <xsl:variable name="tot4" select="cbc:TaxAmount"/>
                                                    <xsl:value-of select="format-number($tot4, '##0.00')"/>&#160;
                                                </font>
                                            </td>
                                        </tr>
                                    </xsl:if>

                                </xsl:for-each>
                            </xsl:for-each>

                            <!-- Rabat og gebyr -->
                            <xsl:for-each select="cac:Consumption/cac:AllowanceCharge">
                                <tr>
                                    <td width="70%" valign="top" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <xsl:choose>
                                                <xsl:when test="cbc:ChargeIndicator ='true'">
                                                    Gebyr&#160; (<xsl:apply-templates select="cbc:AllowanceChargeReason"/>)&#160;
                                                </xsl:when>
                                                <xsl:when test="cbc:ChargeIndicator ='false'">
                                                    Rabat&#160; (<xsl:apply-templates select="cbc:AllowanceChargeReason"/>)&#160;
                                                </xsl:when>
                                            </xsl:choose>
                                            <xsl:choose>
                                                <xsl:when test="cac:TaxCategory/cbc:ID ='StandardRated'">
                                                    Momspligtig
                                                </xsl:when>
                                                <xsl:when test="cac:TaxCategory/cbc:ID ='ZeroRated'">
                                                    Ikke-momspligtig
                                                </xsl:when>
                                            </xsl:choose>
                                        </font>
                                    </td>
                                    <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <xsl:value-of select="format-number(cbc:Amount, '##0.00')"/>&#160;
                                        </font>
                                    </td>
                                </tr>
                            </xsl:for-each>

                            <!-- Momsfri  -->
                            <xsl:for-each select="cac:Consumption/cac:TaxTotal">
                                <xsl:for-each select="cac:TaxSubtotal">
                                    <!-- Momsfri andel -->
                                    <xsl:if test="cac:TaxCategory/cbc:ID = 'ZeroRated' and cac:TaxCategory/cac:TaxScheme/cbc:ID ='63'">
                                        <tr>
                                            <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">
                                                    Momsfri andel
                                                </font>
                                            </td>
                                            <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">
                                                    <xsl:variable name="tot3" select="cbc:TaxableAmount"/>
                                                    <xsl:value-of select="format-number($tot3, '##0.00')"/>&#160;
                                                </font>
                                            </td>
                                        </tr>
                                    </xsl:if>
                                    <!-- Reverse Charge -->
                                    <xsl:if test="cac:TaxCategory/cbc:ID = 'ReverseCharge' and cac:TaxCategory/cac:TaxScheme/cbc:ID ='63'">
                                        <tr>
                                            <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">
                                                    Momsfri andel (REVERSECHARGE)
                                                </font>
                                            </td>
                                            <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">
                                                    <xsl:variable name="tot3" select="cbc:TaxableAmount"/>
                                                    <xsl:value-of select="format-number($tot3, '##0.00')"/>&#160;
                                                </font>
                                            </td>
                                        </tr>
                                    </xsl:if>
                                </xsl:for-each>
                            </xsl:for-each>

                            <!-- Momspligtig  -->
                            <xsl:for-each select="cac:Consumption/cac:TaxTotal">
                                <xsl:for-each select="cac:TaxSubtotal">
                                    <xsl:if test="cac:TaxCategory/cbc:ID = 'StandardRated' and cac:TaxCategory/cac:TaxScheme/cbc:ID ='63'">
                                        <tr>
                                            <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">
                                                    Momsgrundlag
                                                </font>
                                            </td>
                                            <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                <font face="Arial" size="2">
                                                    <xsl:variable name="tot6" select="cbc:TaxableAmount"/>
                                                    <xsl:value-of select="format-number($tot6, '##0.00')"/>&#160;
                                                </font>
                                            </td>
                                        </tr>
                                    </xsl:if>
                                </xsl:for-each>
                            </xsl:for-each>

                            <!-- Momstotal  -->
                            <xsl:for-each select="cac:Consumption/cac:TaxTotal">
                                <xsl:for-each select="cac:TaxSubtotal">
                                    <xsl:variable name="nuller">
                                        <xsl:choose>
                                            <xsl:when test="cbc:TaxAmount = ''">nul</xsl:when>
                                            <xsl:when test="cbc:TaxAmount = '0'">nul</xsl:when>
                                            <xsl:when test="cbc:TaxAmount = '0.0'">nul</xsl:when>
                                            <xsl:when test="cbc:TaxAmount = '0.00'">nul</xsl:when>
                                            <xsl:when test="cbc:TaxAmount = '0.000'">nul</xsl:when>
                                            <xsl:otherwise>ejnul</xsl:otherwise>
                                        </xsl:choose>
                                    </xsl:variable>
                                    <xsl:variable name="momspct" select="cac:TaxCategory/cbc:Percent"/>
                                    <xsl:if test="cac:TaxCategory/cac:TaxScheme/cbc:ID ='63'">
                                        <xsl:if test="$nuller = 'ejnul'">
                                            <tr>
                                                <td width="70%" valign="top" bgcolor="#FFFFFF">
                                                    <font face="Arial" size="2">
                                                        <!-- Totalmoms -->
                                                        Total momsbeløb&#160; (<xsl:value-of
                                                            select="format-number($momspct, '##0.00')"/>%)
                                                    </font>
                                                </td>
                                                <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                                    <font face="Arial" size="2">
                                                        <xsl:variable name="tot4" select="cbc:TaxAmount"/>
                                                        <xsl:value-of select="format-number($tot4, '##0.00')"/>&#160;
                                                    </font>
                                                </td>
                                            </tr>
                                        </xsl:if>
                                    </xsl:if>
                                </xsl:for-each>
                            </xsl:for-each>

                            <!-- Fakturatotal  -->
                            <tr>
                                <td width="70%" valign="top" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        Afregningstotal incl moms og afgifter
                                    </font>
                                </td>
                                <td width="30%" valign="top" align="right" bgcolor="#FFFFFF">
                                    <font face="Arial" size="2">
                                        <xsl:value-of
                                                select="format-number(cac:Consumption/cac:LegalMonetaryTotal/cbc:PayableAmount, '##0.00')"/>&#160;
                                    </font>
                                </td>
                            </tr>

                            <!-- slut totalbeløb (topniveau) -->
                        </table>


                        <!-- indsætter udvikling i forbrug (topniveau) -->
                        <xsl:if test="count(cac:Consumption/cac:EnergyWaterSupply/cac:ConsumptionReport) != 0">
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr></tr>
                                <tr>
                                    <td width="100%" valign="top" colspan="7" bgcolor="#FFFFFF" height="1">
                                        <hr style="color:#77B321; background-color: #77B321; height: 1px; border: 0"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" valign="top" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <br/>
                                            <b>Udvikling i forbrug</b>
                                            <br/>
                                            <xsl:for-each
                                                    select="cac:Consumption/cac:EnergyWaterSupply/cac:ConsumptionReport/cac:ConsumptionHistory">

                                                <xsl:if test="string(cbc:MeterNumber)">
                                                    Målernr:<xsl:text> </xsl:text><xsl:value-of select="cbc:MeterNumber"/><xsl:text> - </xsl:text>
                                                </xsl:if>
                                                Forbrug:<xsl:text> </xsl:text><xsl:value-of select="cbc:Quantity"/><xsl:text> </xsl:text>
                                                <xsl:value-of select="cbc:Quantity/@unitCode"/><xsl:text> - </xsl:text>
                                                <xsl:if test="string(cbc:Amount)">
                                                    Beløb:<xsl:text> </xsl:text><xsl:value-of select="cbc:Amount"/><xsl:text> - </xsl:text>
                                                </xsl:if>
                                                Periode:<xsl:text> </xsl:text><xsl:value-of select="cac:Period/cbc:StartDate"/><xsl:text> til </xsl:text>
                                                <xsl:value-of select="cac:Period/cbc:EndDate"/><xsl:text> - </xsl:text>
                                                Note:<xsl:text> </xsl:text><xsl:value-of select="cbc:Description"/>
                                                <br/>

                                            </xsl:for-each>
                                        </font>
                                    </td>
                                </tr>
                                <!-- slut på udvikling i forbrug (topniveau) -->
                            </table>
                        </xsl:if>


                        <!-- indsætter a conto plan -->
                        <xsl:if test="count(cac:OnAccountPayment) != 0">
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr></tr>
                                <tr>
                                    <td width="100%" valign="top" colspan="7" bgcolor="#FFFFFF" height="1">
                                        <hr style="color:#77B321; background-color: #77B321; height: 1px; border: 0"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" valign="top" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <br/>
                                            <b>A conto plan</b>
                                            <br/>
                                            <xsl:for-each select="cac:OnAccountPayment">

                                                Forventet forbrug:<xsl:text> </xsl:text><xsl:value-of
                                                    select="cbc:EstimatedConsumedQuantity"/><xsl:text> </xsl:text>
                                                <xsl:value-of select="cbc:EstimatedConsumedQuantity/@unitCode"/>
                                                <br/>
                                                Periode:<xsl:text> </xsl:text><xsl:value-of select="cac:Period/cbc:StartDate"/><xsl:text> til </xsl:text>
                                                <xsl:value-of select="cac:Period/cbc:EndDate"/>
                                                <br/>
                                                Note:<xsl:text> </xsl:text><xsl:value-of select="cbc:Note"/>
                                                <br/>

                                                <xsl:for-each select="cac:PlannedSettlement">

                                                    Betalingsdato:<xsl:text> </xsl:text><xsl:value-of select="cbc:DueDate"/><xsl:text> - </xsl:text>
                                                    Beløb inkl. moms og afgifter:<xsl:text> </xsl:text><xsl:value-of
                                                        select="cbc:Amount"/>
                                                    <br/>


                                                </xsl:for-each>
                                            </xsl:for-each>
                                        </font>
                                    </td>
                                </tr>
                                <!-- slut på a conto plan -->
                            </table>
                        </xsl:if>


                        <!-- indsætter gns. energipris -->
                        <xsl:if test="count(cac:Consumption/cac:EnergyWaterSupply/cac:ConsumptionAverage) != 0">
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr></tr>
                                <tr>
                                    <td width="100%" valign="top" colspan="7" bgcolor="#FFFFFF" height="1">
                                        <hr style="color:#77B321; background-color: #77B321; height: 1px; border: 0"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" valign="top" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <b>Gennemsnitlige energipriser</b>
                                            <br/>
                                            <xsl:for-each select="cac:Consumption/cac:EnergyWaterSupply/cac:ConsumptionAverage">

                                                Gennemsnitspris:<xsl:text> </xsl:text><xsl:value-of select="cbc:AverageAmount"/><xsl:text> - </xsl:text>
                                                Note:<xsl:text> </xsl:text><xsl:value-of select="cbc:Description"/>
                                                <br/>

                                            </xsl:for-each>
                                        </font>
                                    </td>
                                </tr>
                                <!-- slut på gns. energipris -->
                            </table>
                        </xsl:if>


                        <!-- indsætter Specifikation af korrektion for afkøling. -->
                        <xsl:if test="count(cac:Consumption/cac:EnergyWaterSupply/cac:EnergyWaterCorrection) != 0">
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr></tr>
                                <tr>
                                    <td width="100%" valign="top" colspan="7" bgcolor="#FFFFFF" height="1">
                                        <hr style="color:#77B321; background-color: #77B321; height: 1px; border: 0"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" valign="top" bgcolor="#FFFFFF">
                                        <font face="Arial" size="2">
                                            <br/>
                                            <b>Specifikation af korrektion for afkøling</b>
                                            <br/>
                                            <xsl:for-each
                                                    select="cac:Consumption/cac:EnergyWaterSupply/cac:EnergyWaterCorrection">

                                                Måler nr:&#160;<xsl:value-of select="cbc:MeterNumber"/>
                                                <br/>

                                                Din afkøling er:&#160;<xsl:value-of
                                                    select="cbc:ActualTemperatureReductionQuantity"/>
                                                <br/>

                                                Normtal:&#160;<xsl:value-of select="cbc:NormalTemperatureReductionQuantity"/>
                                                <br/>

                                                Afvigelse fra normtal:&#160;<xsl:value-of
                                                    select="cbc:DifferenceTemperatureReductionQuantity"/>
                                                <br/>

                                                Regulering pr. MWh pr. grad C:&#160;<xsl:value-of
                                                    select="cbc:CorrectionUnitAmount"/>
                                                <br/>

                                                Dit forbrug af fjernvarmeenergi:&#160;<xsl:value-of
                                                    select="cbc:ConsumptionEnergyQuantity"/>
                                                <br/>

                                                Dit forbrug af fjernvarmevand:&#160;<xsl:value-of
                                                    select="cbc:ConsumptionWaterQuantity"/>
                                                <br/>

                                                Din korrektion for afkøling:&#160;<xsl:value-of select="cbc:CorrectionAmount"/>
                                                <br/>

                                                <br/>
                                            </xsl:for-each>
                                        </font>
                                    </td>
                                </tr>
                                <!-- slut på Specifikation af korrektion for afkøling. -->
                            </table>
                        </xsl:if>


                        <!-- indsætter energiafgifter -->
                        <xsl:if test="count(cac:Consumption/cac:EnergyWaterSupply/cac:EnergyTaxReport) != 0">

                            <!-- indsætter streg + overskrift -->
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td width="100%" valign="top" bgcolor="#FFFFFF" height="1">
                                        <hr style="color:#77B321; background-color: #77B321; height: 1px; border: 0"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Afgiftsspecifikation</b>
                                        </font>
                                    </td>
                                </tr>
                            </table>

                            <!-- indsætter tabel til linjerne -->
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Pligtkode</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Beskrivelse</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Beløb</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Aconto</b>
                                        </font>
                                    </td>
                                    <td valign="top" align="right">
                                        <font face="Arial" size="2">
                                            <b>Balance</b>
                                        </font>
                                    </td>
                                </tr>

                                <!-- Start på linjer -->
                                <xsl:for-each select="cac:Consumption/cac:EnergyWaterSupply/cac:EnergyTaxReport">
                                    <tr>
                                        <td width="10%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Kode -->
                                                <xsl:value-of select="cac:TaxScheme/cbc:ID"/>
                                            </font>
                                        </td>

                                        <td width="60%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Beskrivelse -->
                                                <xsl:value-of select="cac:TaxScheme/cbc:Name"/>
                                            </font>
                                        </td>


                                        <td width="10%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Beløb -->
                                                <xsl:value-of select="cbc:TaxEnergyAmount"/>
                                            </font>
                                        </td>


                                        <td width="10%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Aconto -->
                                                <xsl:value-of select="cbc:TaxEnergyOnAccountAmount"/>
                                            </font>
                                        </td>

                                        <td width="10%" valign="top" align="right">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Balance -->
                                                <xsl:value-of select="cbc:TaxEnergyBalanceAmount"/>
                                            </font>
                                        </td>
                                    </tr>
                                </xsl:for-each>

                            </table>

                            <!-- slut på energiafgifter -->
                        </xsl:if>


                        <!-- indsætter streg -->
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <td width="100%" valign="top" bgcolor="#FFFFFF" height="1">
                                    <hr style="color:#77B321; background-color: #77B321; height: 2px; border: 0"/>
                                </td>
                            </tr>
                        </table>


                        <br/>

                        <!-- Slut Normal + Tele  -->
                    </xsl:if>


                    <!-- Udvidet samtalespec  -->
                    <xsl:if test="$FormatType = 3">


                        <!-- Start på udvidet samtalespecifikation -->

                        <!-- Header -->
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">

                            <tr>
                                <td valign="top">
                                    <font face="Arial">
                                        <b>Udvidet samtalespecifikation</b>
                                    </font>
                                </td>
                            </tr>

                            <tr>
                                <td width="100%" valign="top" colspan="4" bgcolor="#FFFFFF" height="1">
                                    <hr style="color:#77B321; background-color: #77B321; height: 2px; border: 0"/>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <font face="Arial" size="2">
                                        Brugeroplysning: <xsl:value-of
                                            select="cac:SubscriberParty/cac:PartyName/cbc:Name"/>&#160; (ID:&#160;<xsl:value-of
                                            select="cac:SubscriberParty/cac:PartyIdentification/cbc:ID"/>)
                                        <br/>
                                        Fortrolighedskode:
                                        <xsl:value-of select="cac:Consumption/cac:TelecommunicationsSupply/cbc:PrivacyCode"/>
                                        <br/>
                                        Note:
                                        <xsl:value-of select="cbc:Note"/>
                                        <br/>
                                        Totalt forbrug:
                                        <xsl:value-of select="cac:Consumption/cac:TelecommunicationsSupply/cbc:TotalAmount"/>
                                    </font>
                                </td>
                            </tr>
                        </table>


                        <!-- Start på TeleSpecificationLine -->
                        <xsl:for-each select="cac:Consumption/cac:TelecommunicationsSupply/cac:TelecommunicationsSupplyLine">


                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td width="100%" valign="top" colspan="4" bgcolor="#FFFFFF" height="2">
                                        <hr style="color:#77B321; background-color: #77B321; height: 1px; border: 0"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <font face="Arial" size="2">
                                            <b><xsl:value-of select="cbc:ID"/>:
                                            </b>
                                            &#160;

                                            Telefonnummer:&#160;<xsl:value-of select="cbc:PhoneNumber"/>,&#160;
                                            <xsl:if test="cbc:Description != ''">Note:&#160;<xsl:value-of
                                                    select="cbc:Description"/>,&#160;
                                            </xsl:if>

                                            Linjetotal:&#160;<xsl:value-of select="cbc:LineExtensionAmount"/>&#160;

                                            (heraf moms:&#160;<xsl:value-of select="cac:TaxTotal/cbc:TaxAmount"/>),&#160;

                                            <xsl:if test="count(cac:TeleExchangeRate) != 0">Valutakurs:&#160;<xsl:value-of
                                                    select="cac:TeleExchangeRate"/>,&#160;
                                            </xsl:if>

                                            <xsl:if test="count(cac:AllowanceCharge) != 0">Rabat/gebyr:&#160;<xsl:value-of
                                                    select="cac:AllowanceCharge"/>
                                            </xsl:if>
                                        </font>
                                    </td>
                                </tr>

                            </table>

                            <br/>

                            <!-- indsætter tabel til opkaldsdata -->
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Tidspunkt</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Kaldt nr</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Kode</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Beløb1</b>
                                        </font>
                                    </td>
                                    <td valign="top">
                                        <font face="Arial" size="2">
                                            <b>Beløb2</b>
                                        </font>
                                    </td>
                                    <td valign="top" align="right">
                                        <font face="Arial" size="2">
                                            <b>Sum</b>
                                        </font>
                                    </td>
                                </tr>

                                <!-- Start på Call -->
                                <xsl:for-each select="cac:TelecommunicationsService">

                                    <tr>
                                        <td width="10%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Tidspunkt -->
                                                <xsl:value-of select="cbc:CallDate"/>&#160;<xsl:value-of
                                                    select="substring(cbc:CallTime, 1, 5)"/>
                                            </font>
                                        </td>

                                        <td width="10%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Kaldt tlf. nr. -->
                                                <xsl:value-of select="cbc:ServiceNumberCalled"/>
                                            </font>
                                        </td>

                                        <td width="10%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Kode -->
                                                <xsl:value-of select="cbc:TelecommunicationsServiceCallCode"/>
                                            </font>
                                        </td>

                                        <td width="30%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Beløb1 -->
                                                <xsl:value-of select="cac:TimeDuty[1]/cbc:Amount"/>
                                            </font>
                                        </td>


                                        <td width="30%" valign="top">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Beløb2 -->
                                                <xsl:value-of select="cac:CallDuty[1]/cbc:Amount"/>
                                            </font>
                                        </td>


                                        <td width="10%" valign="top" align="right">
                                            <font face="Arial" size="2">
                                                <!-- indsætter Sum -->
                                                <xsl:value-of select="cbc:CallExtensionAmount"/>
                                            </font>
                                        </td>
                                    </tr>


                                    <!-- indsætter Afgiftsdetaljer -->
                                    <tr>

                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>
                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>
                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>

                                        <td colspan="1" valign="top">
                                            <font face="Arial" size="1">

                                                <!-- Start på TimeDuty -->
                                                <xsl:for-each select="cac:TimeDuty">

                                                    <b><xsl:value-of select="cbc:DutyCode"/>:&#160;
                                                    </b>
                                                    &#160;<xsl:value-of select="cbc:Duty"/>

                                                    <!-- Slut på  TimeDuty -->
                                                    <br/>
                                                </xsl:for-each>

                                                <xsl:if test="string(cac:Country/cbc:IdentificationCode)">Landekode:&#160;<xsl:value-of
                                                        select="cac:Country/cbc:IdentificationCode"/>
                                                    <br/>
                                                </xsl:if>

                                                <br/>
                                            </font>
                                        </td>

                                        <td colspan="1" valign="top">
                                            <font face="Arial" size="1">

                                                <!-- Start på CallDuty -->
                                                <xsl:for-each select="cac:CallDuty">

                                                    <b><xsl:value-of select="cbc:DutyCode"/>:&#160;
                                                    </b>
                                                    &#160;<xsl:value-of select="cbc:Duty"/>

                                                    <!-- Slut på  CallDuty -->
                                                    <br/>
                                                </xsl:for-each>

                                                <xsl:if test="string(cac:Country/cbc:IdentificationCode)">Landekode:&#160;<xsl:value-of
                                                        select="cac:Country/cbc:IdentificationCode"/>
                                                    <br/>
                                                </xsl:if>

                                                <br/>
                                            </font>
                                        </td>

                                        <td valign="top">
                                            <font face="Arial"></font>
                                        </td>

                                    </tr>

                                </xsl:for-each>

                            </table>

                            <!-- Slut på  TeleSpecificationLine -->
                        </xsl:for-each>


                        <!-- Slut på udvidet samtalespecifikation -->
                        <!-- indsætter streg -->
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <td width="100%" valign="top" bgcolor="#FFFFFF" height="1">
                                    <hr style="color:#77B321; background-color: #77B321; height: 2px; border: 0"/>
                                </td>
                            </tr>
                        </table>
                        <br/>


                        <!-- Slut Udvidet samtalespec  -->
                    </xsl:if>


                    <!-- Slut på SubscriberConsumption -->
                </xsl:for-each>


                <!-- Start på fritekst og referencer-->
                <xsl:if test="cbc:Note !='' or cac:AdditionalDocumentReference/cbc:ID !=''">
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                        </tr>
                        <tr>
                            <td>
                                <font face="Arial" size="2">
                                    <xsl:if test="cbc:Note[.!='']">
                                        <b>Yderligere oplysninger</b>
                                        <br/>
                                        <xsl:value-of select="cbc:Note"/>
                                        <br/>
                                    </xsl:if>
                                    <xsl:if test="cac:AdditionalDocumentReference/cbc:ID[.!='']">
                                        <b>SupplerendeDokumentReference ID:</b>
                                        <br/>
                                        <xsl:value-of select="cac:AdditionalDocumentReference/cbc:ID"/>
                                        <br/>
                                    </xsl:if>
                                </font>
                            </td>
                        </tr>
                    </table>
                    <br/>
                </xsl:if>
                <!-- Slut på fritekst og referencer-->


                <!-- Start på OIOUBL footer -->
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td width="100%" valign="top" colspan="4" bgcolor="#FFFFFF" height="2">
                            <hr style="color:#77B321; background-color: #77B321; height: 5px; border: 0"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <font face="Arial" size="2">
                                <b>OIOUBL dokumentparametre</b>
                                <br/>
                                UBLVersionID:
                                <xsl:value-of select="cbc:UBLVersionID"/>
                                <br/>
                                CustomizationID:
                                <xsl:value-of select="cbc:CustomizationID"/>
                                <br/>
                                Profil ID:
                                <xsl:value-of select="cbc:ProfileID"/>
                                <br/>
                                ID:
                                <xsl:value-of select="cbc:ID"/>
                                <br/>
                                <xsl:if test="cbc:UUID !=''">
                                    UUID:
                                    <xsl:value-of select="cbc:UUID"/>
                                </xsl:if>
                                <br/>
                                Dokument valuta:
                                <xsl:value-of select="cbc:DocumentCurrencyCode"/>
                                <br/>
                                OIOUBL faktura reference: ID: <xsl:value-of select="cac:ParentDocumentReference/cbc:ID"/>, Dato:
                                <xsl:value-of select="cac:ParentDocumentReference/cbc:IssueDate"/>
                                <br/>
                            </font>
                        </td>
                    </tr>
                </table>
                <!-- Slut på OIOUBL footer -->


            </body>
        </html>

    </xsl:template>

    <!--  ...................................................  -->
    <!--                                                       -->
    <!--  Fælles templates                                     -->
    <!--                                                       -->
    <!--  ...................................................  -->

</xsl:stylesheet>
