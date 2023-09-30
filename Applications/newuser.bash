#!/bin/bash
echo '
Velkommen til n4s demoserver.

Din demo køres uden nogen former for garanti eller support.

For at acceptere og oprette en ny bruger, tryk ENTER
'
read

echo -n "Indtast brugernavn (skriv dette ned, ingen specialtegn, ingen mellemrum): "
read bruger
echo Husk også at skrive din kode ned
sudo adduser $bruger --quiet

echo klar til at logge på ?

su $bruger -
exit
