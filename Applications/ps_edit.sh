#!/bin/sh
# Prestashop product edition using webservice.
# V: 1.0
# D: 7 Oct, 2011
# Author: Jorge Solla (jorgesolla (A T) gmail (D O T) com)
# WARNING!!!!!!: Before using this script, you *MUST* create a config.inc file with your shop parameters containing the following vars:
WS_KEY=ZQCRAFT1IRWEIV1GQQOX8YA9KIJZFB7S
BASEURL=http://www.olsens-it.dk/prestashop/api

# Get configuration params
#source ./config.inc
if [ $# -lt 2 ]
then
echo "Usage: $0 <PSHOP_ID> [ACTIVE=1/0] [QUANTITY=value] [PRICE=value] [WPRICE=value] [REFERENCE=value] [DESC=\"value\" NOTE: double quotes!!] ";
echo "Example: $0 1234 ACTIVE=0"
echo "Example: $0 1234 STOCK=500"
exit 1;
fi
# Evaluate all parameters
PCOUNT=0
for param in $*
do
# Param 0 doesn't need evaluation -> Product ID in PS
if [ $PCOUNT -gt 0 ]
then
  eval $param
fi
# Inc param count
((PCOUNT=PCOUNT+1))
done;
# Get product ID from first param
ID=$1
# Retrieve current product on PRESTASHOP
CURL_REPLY=$( curl -s -u "$WS_KEY:"   $BASEURL/products/$ID )
if [ $? -gt 0 ]
then
echo "Error: Curl was unable to connect to PS webservice"
exit 1
fi
# Get reply length: When we request a non-existent product, prestashop returns OK but NO data
LENGTH=${#CURL_REPLY}
if [ $LENGTH -eq 0 ]
then
echo "Error: Product ID [$ID] does not exist"
exit 1
fi
# Search for errors on the reply
RESULT=$(echo "$CURL_REPLY" | grep "error")
LENGTH=${#RESULT}
if [ $LENGTH -gt 0 ]
then
echo "PS WEBSERVICE: Error detected fetching product ID [$ID]. Unable to continue.";
exit 1
fi

# Prestashop requires to delete some keys from the product XML to use it as an update XML
XML=$CURL_REPLY
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/position_in_category')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/id_default_combination')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/id_default_image')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/manufacturer_name')
echo -n "Setting new values: "
# Insert new values into fields
if [ -n "${ACTIVE+x}" ]
then
echo -n "ACTIVE=$ACTIVE | "
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/active" -v "$ACTIVE")
fi
if [ -n "${QUANTITY+x}" ]
then
echo -n "QUANTITY=$QUANTITY |"
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/quantity" -v "$QUANTITY")
fi
if [ -n "${PRICE+x}" ]
then
echo -n "PRICE=$PRICE |" 
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/price" -v "$PRICE")
fi
if [ -n "${WPRICE+x}" ]
then
echo -n "WHOLESALE_PRICE=$WPRICE |"
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/wholesale_price" -v "$WPRICE")
fi
# Create a tmp file to update the article (Create a PUT file for CURL)
ARTICLE_DATA_FILE=$(mktemp)
echo -e "$XML" > $ARTICLE_DATA_FILE
# Update product
CURL_REPLY=$( curl -s -u "$WS_KEY:" -i -H "Content-Type:application/x-www-form-urlencoded" -X PUT -T "$ARTICLE_DATA_FILE"  $BASEURL/products/$ID )
# Check CURL return status
if [ $? -gt 0 ]
then
echo "Error: Curl was unable to PUT the modified product"
# Delete TMP file before quitting
rm $ARTICLE_DATA_FILE
exit 1
fi
# Delete TMP file
rm $ARTICLE_DATA_FILE

# Search for errors on the reply
RESULT=$(echo "$CURL_REPLY" | grep "error")
LENGTH=${#RESULT}
if [ $LENGTH -eq 0 ]
then
echo " -> OK";
exit 0
else
echo ""
echo "Error: PS Returns error while updating ID [$ID]";
echo "$CURL_REPLY"
exit 1
fi
!/bin/sh
# Prestashop product edition using webservice.
# V: 1.0
# D: 7 Oct, 2011
# Author: Jorge Solla (jorgesolla (A T) gmail (D O T) com)
# WARNING!!!!!!: Before using this script, you *MUST* create a config.inc file with your shop parameters containing the following vars:
# WS_KEY=<YOUR PSHOP WEBSERVICE KEY>
# BASEURL=http://MYSHOP.COM/prestashop/api

# Get configuration params
source ./config.inc
if [ $# -lt 2 ]
then
echo "Usage: $0 <PSHOP_ID> [ACTIVE=1/0] [QUANTITY=value] [PRICE=value] [WPRICE=value] [REFERENCE=value] [DESC=\"value\" NOTE: double quotes!!] ";
echo "Example: $0 1234 ACTIVE=0"
echo "Example: $0 1234 STOCK=500"
exit 1;
fi
# Evaluate all parameters
PCOUNT=0
for param in $*
do
# Param 0 doesn't need evaluation -> Product ID in PS
if [ $PCOUNT -gt 0 ]
then
  eval $param
fi
# Inc param count
((PCOUNT++))
done;
# Get product ID from first param
ID=$1
# Retrieve current product on PRESTASHOP
CURL_REPLY=$( curl -s -u "$WS_KEY:"   $BASEURL/products/$ID )
if [ $? -gt 0 ]
then
echo "Error: Curl was unable to connect to PS webservice"
exit 1
fi
# Get reply length: When we request a non-existent product, prestashop returns OK but NO data
LENGTH=${#CURL_REPLY}
if [ $LENGTH -eq 0 ]
then
echo "Error: Product ID [$ID] does not exist"
exit 1
fi
# Search for errors on the reply
RESULT=$(echo "$CURL_REPLY" | grep "error")
LENGTH=${#RESULT}
if [ $LENGTH -gt 0 ]
then
echo "PS WEBSERVICE: Error detected fetching product ID [$ID]. Unable to continue.";
exit 1
fi

# Prestashop requires to delete some keys from the product XML to use it as an update XML
XML=$CURL_REPLY
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/position_in_category')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/id_default_combination')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/id_default_image')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/manufacturer_name')
echo -n "Setting new values: "
# Insert new values into fields
if [ -n "${ACTIVE+x}" ]
then
echo -n "ACTIVE=$ACTIVE | "
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/active" -v "$ACTIVE")
fi
if [ -n "${QUANTITY+x}" ]
then
echo -n "QUANTITY=$QUANTITY |"
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/quantity" -v "$QUANTITY")
fi
if [ -n "${PRICE+x}" ]
then
echo -n "PRICE=$PRICE |" 
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/price" -v "$PRICE")
fi
if [ -n "${WPRICE+x}" ]
then
echo -n "WHOLESALE_PRICE=$WPRICE |"
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/wholesale_price" -v "$WPRICE")
fi
# Create a tmp file to update the article (Create a PUT file for CURL)
ARTICLE_DATA_FILE=$(mktemp)
echo -e "$XML" > $ARTICLE_DATA_FILE
# Update product
CURL_REPLY=$( curl -s -u "$WS_KEY:" -i -H "Content-Type:application/x-www-form-urlencoded" -X PUT -T "$ARTICLE_DATA_FILE"  $BASEURL/products/$ID )
# Check CURL return status
if [ $? -gt 0 ]
then
echo "Error: Curl was unable to PUT the modified product"
# Delete TMP file before quitting
rm $ARTICLE_DATA_FILE
exit 1
fi
# Delete TMP file
rm $ARTICLE_DATA_FILE

# Search for errors on the reply
RESULT=$(echo "$CURL_REPLY" | grep "error")
LENGTH=${#RESULT}
if [ $LENGTH -eq 0 ]
then
echo " -> OK";
exit 0
else
echo ""
echo "Error: PS Returns error while updating ID [$ID]";
echo "$CURL_REPLY"
exit 1
fi
!/bin/sh
# Prestashop product edition using webservice.
# V: 1.0
# D: 7 Oct, 2011
# Author: Jorge Solla (jorgesolla (A T) gmail (D O T) com)
# WARNING!!!!!!: Before using this script, you *MUST* create a config.inc file with your shop parameters containing the following vars:
# WS_KEY=ZQCRAFT1IRWEIV1GQQOX8YA9KIJZFB7S
# BASEURL=http://www.olsens-it.dk/api

# Get configuration params
#source ./config.inc
if [ $# -lt 2 ]
then
echo "Usage: $0 <PSHOP_ID> [ACTIVE=1/0] [QUANTITY=value] [PRICE=value] [WPRICE=value] [REFERENCE=value] [DESC=\"value\" NOTE: double quotes!!] ";
echo "Example: $0 1234 ACTIVE=0"
echo "Example: $0 1234 STOCK=500"
exit 1;
fi
# Evaluate all parameters
PCOUNT=0
for param in $*
do
# Param 0 doesn't need evaluation -> Product ID in PS
if [ $PCOUNT -gt 0 ]
then
  eval $param
fi
# Inc param count
((PCOUNT++))
done;
# Get product ID from first param
ID=$1
# Retrieve current product on PRESTASHOP
CURL_REPLY=$( curl -s -u "$WS_KEY:"   $BASEURL/products/$ID )
if [ $? -gt 0 ]
then
echo "Error: Curl was unable to connect to PS webservice"
exit 1
fi
# Get reply length: When we request a non-existent product, prestashop returns OK but NO data
LENGTH=${#CURL_REPLY}
if [ $LENGTH -eq 0 ]
then
echo "Error: Product ID [$ID] does not exist"
exit 1
fi
# Search for errors on the reply
RESULT=$(echo "$CURL_REPLY" | grep "error")
LENGTH=${#RESULT}
if [ $LENGTH -gt 0 ]
then
echo "PS WEBSERVICE: Error detected fetching product ID [$ID]. Unable to continue.";
exit 1
fi

# Prestashop requires to delete some keys from the product XML to use it as an update XML
XML=$CURL_REPLY
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/position_in_category')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/id_default_combination')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/id_default_image')
XML=$(echo "$XML" |  xmlstarlet ed -d '/prestashop/product/manufacturer_name')
echo -n "Setting new values: "
# Insert new values into fields
if [ -n "${ACTIVE+x}" ]
then
echo -n "ACTIVE=$ACTIVE | "
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/active" -v "$ACTIVE")
fi
if [ -n "${QUANTITY+x}" ]
then
echo -n "QUANTITY=$QUANTITY |"
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/quantity" -v "$QUANTITY")
fi
if [ -n "${PRICE+x}" ]
then
echo -n "PRICE=$PRICE |" 
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/price" -v "$PRICE")
fi
if [ -n "${WPRICE+x}" ]
then
echo -n "WHOLESALE_PRICE=$WPRICE |"
XML=$(echo "$XML" |  xmlstarlet ed -u "/prestashop/product/wholesale_price" -v "$WPRICE")
fi
# Create a tmp file to update the article (Create a PUT file for CURL)
ARTICLE_DATA_FILE=$(mktemp)
echo -e "$XML" > $ARTICLE_DATA_FILE
# Update product
CURL_REPLY=$( curl -s -u "$WS_KEY:" -i -H "Content-Type:application/x-www-form-urlencoded" -X PUT -T "$ARTICLE_DATA_FILE"  $BASEURL/products/$ID )
# Check CURL return status
if [ $? -gt 0 ]
then
echo "Error: Curl was unable to PUT the modified product"
# Delete TMP file before quitting
rm $ARTICLE_DATA_FILE
exit 1
fi
# Delete TMP file
rm $ARTICLE_DATA_FILE

# Search for errors on the reply
RESULT=$(echo "$CURL_REPLY" | grep "error")
LENGTH=${#RESULT}
if [ $LENGTH -eq 0 ]
then
echo " -> OK";
exit 0
else
echo ""
echo "Error: PS Returns error while updating ID [$ID]";
echo "$CURL_REPLY"
exit 1
fi

