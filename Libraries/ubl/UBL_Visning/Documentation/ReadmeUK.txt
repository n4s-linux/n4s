## ***** SVN Version info *****
## File           ReadmeUK.txt
## Last modified  $Date$
## Modified by    $Author$
## SVN version    $Revision$



OIOUBL presentation stylesheet (HTML)
-------------------------------------


1.0 Purpose and usage
---------------------
Presentation of OIOUBL-2.02 documents as HTML.

Document stylesheets:

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

There is a presentation stylesheet available for each OIOUBL document.
Invoice and CreditNote refer to OIOUBL_CommonTemplate2017.xsl, and other document stylesheets refer to OIOUBL_CommonTemplate.xsl

Common stylesheet:
- OIOUBL_CommonTemplates.xsl
- OIOUBL_CommonTemplates2017.xsl

Headlines / common values:
- OIOUBL_Headlines.xml
- OIOUBL_Headlines2017.xml

Styling:
- OIOUBL.css
- OIOUBL2017.css

Example of use:
- msxsl.exe Examples/OIOUBL_Invoice.xml InvoiceHTML.xsl -o output.html



2.0 Prerequisites and installation
----------------------------------
It is required that the OIOUBL document is valid (both xsd and schematron).


3.0 Release Notes
-----------------
15.09.2017: Layout for Invoice and CreditNote face-lifted.
- VAT percentage is now taken from document.
- Amounts are now right aligned.
- SerialID is now shown.
- Error in display of invoice period is fixed.
- Invoice number is now shown in upper right corner.
- Misc. fixed and improvements.

30.09.2022: Minor updates
- Example files updated to UBL 2.1
- UtilityStatementHTML.xsl updated to UBL 2.1

4.0 Revisionslog
-----------------
12.05.2011: Version 1.0 released.
15.03.2013: Version 1.5 released.
15.09.2017: Version 2.0 released.
30.09.2022: Version 2.1 released.


5.0 Your feedback
-----------------
Please post your comments and feedback to the following email address:
    support@nemhandel.dk

Thanks!
