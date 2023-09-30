echo -n "Indtast søgning: "
read search
wget -O - "https://cvrapi.dk/api?search=$search&country=dk"|jq|fzf --ansi
