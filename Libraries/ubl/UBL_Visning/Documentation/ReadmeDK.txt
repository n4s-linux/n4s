## ***** SVN Version info *****
## File           ReadmeDK.txt
## Last modified  $Date$
## Modified by    $Author$
## SVN version    $Revision$



OIOUBL HTML Visningsstylesheets
-------------------------------


1.0 Anvendelse
--------------
Anvendes til at præsentere OIOUBL dokumenter i HTML format.

Dokumentstylesheets:

ApplicationResponseHTML.xsl
CreditNoteHTML.xsl
InvoiceHTML.xsl
OrderHTML.xsl
OrderCancellationHTML.xsl
OrderChangeHTML.xsl
OrderResponseHTML.xsl
OrderResponseSimpleHTML.xsl
ReminderHTML.xsl
StatementHTML.xsl
UtilityStatementHTML.xsl

For hvert OIOUBL dokument er der oprettet et HTML visningsstylesheet.
Invoice og CreditNote refererer til OIOUBL_CommonTemplate2017.xsl, og andre dokumentstylesheets refererer til OIOUBL_CommonTemplate.xsl

Tværgående stylesheet:
- OIOUBL_CommonTemplates.xsl
- OIOUBL_CommonTemplates2017.xsl

Overskrifter / fælles-værdier:
- OIOUBL_Headlines.xml
- OIOUBL_Headlines2017.xml

Styling:
- OIOUBL.css
- OIOUBL2017.css

Eksempel på brug:
- msxsl.exe Examples/OIOUBL_Invoice.xml InvoiceHTML.xsl -o output.html



2.0 Forudsætninger og installation
----------------------------------
Det forudsættes at OIOUBL instanserne er valideret korrekt (både xsd og schematron).


3.0 Release Notes
-----------------
15.09.2017: Layout for faktura og kreditnota har fået et kraftigt løft.
- Moms procent aflæses nu i dokumentet.
- Beløb er nu korrekt højrestillet.
- Serienummer/SerialID vises.
- Fejl i visning af fakturaperiode rettet.
- Fakturanr. vises i øverste højre hjørne.
- Diverse fejlrettelser og forbedringer.

30.09.2022: Mindre opdatering
- Eksempelfiler opdateret til UBL 2.1
- UtilityStatementHTML.xsl tilpasset til UBL 2.1


4.0 Revisionslog
-----------------
12.05.2011: Version 1.0 frigivet.
15.03.2013: Version 1.5 frigivet.
15.09.2017: Version 2.0 frigivet.
30.09.2022: Version 2.1 frigivet.


5.0 Rapportering af fejl og mangler etc.
----------------------------------------
Information om fejl, mangler, og andet relevant, modtages meget gerne på følgende mailadresse:
    support@nemhandel.dk

På forhånd tak!
