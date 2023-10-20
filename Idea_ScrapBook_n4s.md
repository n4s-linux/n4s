# Rapportering n4s - naturlige fortegn
	2023-10-18T11:26 joo	#ide naturlige fortegn i rapportering - dvs indtægter er postive, udgifter er negative, egenkapital skal også vendes - aktiver står som de skal

# Pandoc erstatning #n4s #stuff
	2023-10-16T13:45 joo	#observation pandoc er meget langsomt
	2023-10-16T13:45 joo	#ide min egen markdown til html rapport - bør også lave vim style highlighting

# N4S tmp outputmappe
	2023-10-13T10:10 joo	lav ny mappe til output fremfor tmp som har mange tmpfiler

# Borgbackup add remotes #n4s
	2023-10-13T08:25 joo	#ide #n4s #borgbackup sync() add remotes

# Ny bogføringslov - og overholdelse af gl. bogføringslov #n4s
## Standardkontoplan
	2023-10-11T09:55 joo	nødvendig mapning til erhvervsstyrelsens standardkontoplan

## Bogføring implementeres der låser posteringerne
	2023-10-11T09:53 joo	#ide bogfør script der bogfører transaktioner til ledger-fil - skal være som en blockchain således at hver transaktion indeholder md5hash af de foregående transaktioner og man derved kan verificere hvornår der er bogført hvad

## Ugentlig backup
	2023-10-11T09:51 joo	#ide backup skal sættes op til at køres automatisk dagligt med #borgbackup i et cronjob
	2023-10-11T12:46 joo	sat #borgbackup op i funktionen sync koblet på sr() således at den tager en backup hver gang man åbner et regnskab

## XML fakturering og modtagelse
	2023-10-04T10:43 joo	#ide implementere fakturering #n4s
	2023-10-10T21:55 joo	lagt https://github.com/num-num/ubl-invoice/tree/master ind i svnroot - kan køres med phpunit og lave invoices, så skal jeg bare have en parser der kan læse også
	2023-10-11T09:55 joo	#observation erst har også lagt nogle pdf templates op man kan arbejde ud fra
	2023-10-10T21:56 joo	skal både kunne læse og afsender xml fakturaer i dette format - umiddelbart ved udgangen af 2024

## Anmeldelsespligt
	2023-10-10T21:56 joo	specialudviklede systemer skal ikke anmeldes, men virksomheden skal selv kunne dokumentere at det overholder kravene

## PSD2
	2023-10-20T02:40 joo	bankintegration skal genaktiveres

# Ingen årsafslutning regnskaber #n4s
	2023-10-10T17:24 joo	#ide #n4s ingen årsafslutning, men altid beregn primosaldo på konti af typen Aktiver Passiver Egenkapital Fejlkonto og sæt den ind - modkonto Egenkapital:Overført resultat
	2023-10-11T09:52 joo	fordel er vi ikke behøver tage forbehold
	2023-10-17T22:37 joo	implementeret funktion i aliases - testet på egne regnskaber - skal træde varsomt på kunderegnskaber i starten og skal have slettet deres manuelle årsafslutninger
	2023-10-17T22:38 joo	#ide årsafslutning harmuligvis stadig en berettigelse ifht resultatdisponering ? 2023-10-20T02:40 joo	resultatdisponering kan blot være som en justering til den automatiske overførsel
	2023-10-20T02:40 joo	indtil videre kører det godt

# N4s periodisering
	2023-10-08T20:09 joo	observation p-start og p-end har ingen effekt i demoregnskab

# Touch screen interface #n4s
	2023-10-05T11:38 joo	#ide touchscreen interface n4s - touch virker godt på en stor terminal - behøver ikke være gui - i vim testet med mouse=a

# Autoupdate n4s
	2023-10-08T11:05 joo	tilføjet autoupdate (git pull ved hver gang aliases kører) - se hvordan det går
	2023-10-11T09:54 joo	disablet det, virkede ikke efter hensigten, har i stedet skrevet i README hvordan man opdaterer

# N4s - vim mus support
	2023-10-05T11:42 joo	#ide slå mus til og fra nemt (mouse= mouse=a)
	2023-10-11T09:55 joo	er slået til, jeg har slået det fra i min egen ~/.vim/custom.vim

# n4s - stuff - Diff fil forbedring
	2023-04-22T00:41 joo	i stedet for en diff-fil som nu, så kunne man have en diff.out og diff.in til nye linier og slettede linier. de kunne prefixes med hvilken markdown kategori de er i #outsourcing
	2023-06-12T13:30 joo	#ide #markdowndiff skal vise hvad der er rettet i hver sektion på en læselig måde
	2023-10-04T10:25 joo	#regex er for besværligt, manuel line by line parsing er #thewaytogo
	2023-10-04T10:29 joo	#ide outputte hver fil til en parsed markdown fil hvor sektioner prependes
	2023-10-08T10:15 joo	markdown parser til difffile lavet og implementeret
	2023-10-08T10:15 joo	#observation plusser og minuser kommer hver for sig, det ville være bedre hvis de kom historisk dvs fra top til bund og plusser og minuser blev blandet

# N4s bedre saldobalance og kontokort
	2023-09-26T12:15 joo	#ide bedre kontokort og saldobalance - evt. som simpelt tekstformta
	2023-10-04T10:27 joo	har opdateret html funktion med bedre htmlbaserede saldobalancer med noter, og bedre kontokort
	2023-10-11T09:58 joo	#ide lav også traditionel opstilling baseret på erst kontoplan - kræver der bliver mappet og rapporteringen sker udfra en flad nummerbaseret struktur

# N4s regnskabsår
	2023-06-07T13:52 joo	#ide for hver årsafslutning skal der oprettes et regnskabsår som man let kan skifte til - skal kunne navngives som man ønsker

# Mini n4s pc
	2023-04-26T16:06 joo	#ide lille bitte skærm og mulighed for bluetooth tastatur #raspberry pi til n4s klient/server
	2023-04-26T16:05 joo	#ide #minipc til #n4s https://dk.rs-online.com/web/p/display-udvikling/2306185?cm_mmc=DK-PLA-DS3A-_-google-_-PLA_DK_DK_Raspberry_Pi_%26_Arduino_%26_ROCK_og_udviklingsv%C3%A6rkt%C3%B8j_Whoop-_-(DK:Whoop!)+Display-udvikling-_-2306185&matchtype=&pla-307688689883&gclid=CjwKCAjwl6OiBhA2EiwAuUwWZdt7abCSHshXi8f2mO_tLnY75fg2m3sxc2Mrsq_dndEcFfDHuKWgQxoCnOUQAvD_BwE&gclsrc=aw.ds #raspberrypi
	2023-04-26T16:06 joo	også denne https://www.geekbuying.com/item/3-5-Inch-IPS-TYPE-C-Secondary-Screen-CPU-GPU-RAM-HDD-Monitoring-517026.html?Currency=DKK&source=googleads&utm_source=google&utm_medium=cpc&gclid=CjwKCAjwl6OiBhA2EiwAuUwWZf--uEpv4j3-ZSpAD4BTO9pkBjjE5AndG939gDL0BOW4kQVKxVtuCBoCV3MQAvD_BwE
	2023-10-01T19:45 joo	jeg har allerede minipcer på lager
	2023-10-04T10:28 joo	kan spare 500kr/md til #vultr hvis jeg får sat min egen server op, men har nok stadig brug for en #jumpserver
	2023-10-11T09:59 joo	#ide bedre at bruge en rigtig server

# Tvivlsomme poster i n4s - hashtags udvælger til rapporteirng
        2023-02-28T21:00 joo    #ide Hashtags medtages i specs fx ulovligt aktionærlån mangler bilag , forkert modtager ml58, tvivlsomt fradrag (#rt 10927)

# Afstemning ved et ekstra lag poster #n4s
	2023-03-18T15:13 joo	#ide #n4s oprettelse af modpost til bank så den kan blive afstemt - modposter til alt, således at man skal kunne trække en balance på 0 når alt er udlignet
	2023-07-21T21:41 joo	det er en god ide med et skyggeregnskab som bliver lagt oveni

## Eksempel
	Regnskab	Bank 31.12 	 197.000
	SkyggeRegnskab	Kontoudtog	-197.000
	-----------------------------------------
	Forskel				       0
	-----------------------------------------
	
# Min egen markdown parser #n4s
	2023-03-12T11:24 joo	#ide #stuff #n4s lave min egen markdown parser alt inklusive (også toc), drop pandoc
	2023-07-21T21:43 joo	#godide

# N4S database problem
	2023-03-09T20:13 joo	#ide kunne n4s være en database - postgresql som understøtter json - når den bro er bygget er vi decentrale nok #n4s-storage
	2023-03-17T11:00 joo	#ide et #quickfix kunne være #unison sync til en central server - eller #resilio

# Allerede åbne journaler n4s
	2023-03-09T20:12 joo	#ide #stuff #n4s allerede åbne journaler skal søge efter en fane ved det navn, om ikke andet i samme session, men evt. på alle sessioner, i stedet for at sige den allerede er åben - foreløbig af samme bruger
	

# Search - bulk update #n4s
	2023-01-30T10:12 joo	#ide #n4s search funtion - also add bulk update

# n4s - manuel årsafslutning - ville formentlig løse vores problem med balancer der ikke stemmer
	2022-12-25T16:31 joo	#ide #n4s manuel årsafslutning
	2023-01-03T14:38 joo	har umiddelbart løst problemet, og også hastigheden
	2023-01-27T22:00 joo	er ikke implementeret på alle kunder, men det er relativt nemt at gøre og har ikke oplevet nogle problemer med det endnu
	2023-04-08T01:28 joo	er implementeret på mange kunder nu

# n4s - entry after unaccounted aliases
	2022-12-21T15:39 joo	når man skriver efter et nyt unmapped aliases, så virker det ikke

# n4s største udfordring er hurtig data storage

# Blockchain n4s
	2022-11-20T08:09 joo	#ide n4s på blockchain
	2023-01-27T22:07 joo	#ide hver fil / transaktion puttes ind i blokken og markeres som "brugt" med reference til punktet i blockchainen - hver ny blok har alle de tidligere blokkes checksum

# Frappe #n4s
	2022-09-20T15:32 joo	#ide brug erpnext sammen med n4s samt https://github.com/frappe/books/tree/master/reports

# Undermapper / underregnskaber #n4s
	2022-07-30T12:51	#ide undermappe #n4s et helt tpath under et tpath - måske i mange niveauer ? eller er 2 nok ? #arbitrarylimits #aginstgnu #hardtoimplement på flere niveauer

# Ide n4s se kundedata altid
	2022-06-27T08:32	det skal være hurtigt at frembringe stamdata, herunder også cpr numre etc 2023-10-20T02:42 joo	cpr-numre skal maskeres i vim samt andre følsomme data, skal kræve pw
	2022-08-16T08:29	#ide stamdata skal ligge i separat "database" fra sagerne

# n4s navngivning

# Raspberry pi n4s
	2023-05-04T17:26 joo	#raspberry pi med #n4s så folk kan købe en færdig maskine #scrabble

# N4s enheder
	2023-05-04T17:31 joo	#ide #n4s #produkt i form af stationær minipc eller opsætning af vps, - evt også hosting af vps #scrabble

# Arbejde i solen #n4s
	2023-05-07T13:09 joo	arbejde fra laptop i solen - finde ud af hvad for et terminal tema der er bedst #scrabble
	2023-10-20T02:43 joo	med det rette tema på en mørk terminal er det faktisk nemt at arbejde i solen

# N4s navn
	2023-05-10T13:31 joo	#navn #n4s #movemountains #scrabble
	2023-08-07T22:53 joo	#navn #n4s #fightthewind #ftw

# N4s - webinterface til telefon
	2023-05-25T12:42 joo	webinterface til stuff, så kan jeg benytte det fra en ipad eller tlf #scrabble
	2023-05-30T18:53 joo	#indtast #blokke direkte i #thenow eller tryk F12 #scrabble
	2023-08-07T22:51 joo	#mobiltelefon = skrald

# N4s - vidensdatabaser via git
	2023-05-30T18:56 joo	#deling af n4s-markdown vidensdatabaser via github - kan gøres så folk sender pull request ind #scrabble
	2023-06-06T22:24 joo	kunne også være en hidden git service
	2023-08-07T22:51 joo	#ulempe #centraliseret

# N4s - vidensdatabaser via sshfs
	2023-05-31T10:41 joo	#markdown wiki folder sharing over sshfs - del en mappe over ssh via tor, lad folk mounte den #scrabble
	2023-06-06T22:25 joo	man kan også lade folk synkronisere via unison
	2023-08-07T22:50 joo	#ulempe #centraliseret

# Zipfil n4s
	2023-06-18T12:32 joo	#ide n4s zip fil med alle regnskabsdata inbygget i PDF rapport - som nemt kan indlæses i en anden n4s #scrabble

# n4s - forking
	2023-06-28T12:44 joo	#n4s forke et vindue med samme miljø - skal spørge om nyt vindue eller vertikalt/horizontalt split #scrabble

# Distribution til windows #n4s
	2023-07-24T10:54 joo	#ide distribuere vm til windows, - men bør være en exe installer
	2023-07-24T10:54 joo	afventer #d4
	2023-07-24T10:55 joo	kontakt til mulig interesseret programmør @ #whatsapp afventer
	2023-07-26T16:21 joo	#n4s4windows
	2023-10-20T02:43 joo	er lavet som ova, afventer evt. respons fra brugere om problemer med kørsel
# Cygwin #n4s
	2023-07-28T11:29 joo	#cygwin kan n4s køre i det ? installeret på #kamatera #scrabble
	2023-07-28T18:04 joo	ser ud til at køre fint, undtagen x11 hvor keyboard ikke virker

# Kryptering af filer og historik #n4s
        2023-02-08T09:31 joo    #ide få krypteret historik således at både journal og historik fil er krypteret og først åbnes op når man åbner den #n4s #sikkerhed #stuff                                                                                                                                                                    2023-02-13T09:09 joo    evt. kunne hver journal være sit eget #luksfs filsystem der låses op, - men kan man bruge fingeraftryk eller andet ?
	2023-10-20T02:46 joo	indtil videre må det være nok hvis harddisken er krypteret med luks


# Regnskab - forbinde nutid med fremtid #n4s
        2023-04-25T06:38 joo    #ide hvordan laver vi en bedre og mere præcis overgang mellem fortid/nutid og fremtid end at der er månedsvise poster som bliver skrevet i fremtiden, men aldrig bagud, da det antages at regnskabet er ajour
	2023-04-25T07:23 joo    lige nu er det måske et godt kompromis, som kan fungere til nogle typer regnskaber
	2023-05-03T09:05 joo	#ide kunne driften på den seneste måned / igangværende måned bliver justeret så den passer til budget , hvis man ikke er helt ajour ?
	2023-07-11T15:23 joo	ide sætte startdato for budget - fixed start date
	2023-10-20T02:46 joo	har et rullende budgetscript klar, men skal finde en smart måde at autodetecte hvorfra den skal starte, og køre med tidligere tal for fx moms, ikke budgettets tal


# Standard-stuff mappe pakkes ud #n4s
	2023-04-24T06:50 joo	ide ny regnskab skal man kunne vælge "stuff" - som loades som en kopi af en helt renset stuff-mappe, hvor der i header og footer er instruktioner til udskiftning af header og footer


# Hledger skift #n4s
	2023-04-21T09:58 joo	#ide skift til #hledger da #ledger virker som et dødt projekt, og der er flere fejl vi umiddelbart ikke kan få rettet
	2023-04-21T09:59 joo	#ulempe kunne være det er svært at finde programmører der kan rette noget i haskell
	2023-05-04T08:09 joo	fordel der er mere funktionalitet og formentlig færre fejl
	2023-05-04T08:09 joo	#alternativide skrive min egen ledger
	2023-10-20T02:47 joo	lige nu bruger vi begge dele, grundet c++ ledgers mange fejl 

# Man pages #n4s
	2023-04-21T09:55 joo	#ide lave manpages

## Man side hledger inspiration - fra markdown
	18:02 < sm> hledger/hledger.m4.md, hledger-ui/hledger-ui.m4.md, hledger-web/hledger-web.m4.md, and also somethings in doc/common.m4
	18:02 < sm> the other files are generated from those
	18:03 < sm> by ./Shake manuals
	18:03 < sm> also: hledger/Hledger/Cli/Commands/*.md
	18:04 < sm> you asked about hledger specifically, let me try that again: the source files for hledger manual are:
	18:04 < sm> hledger/hledger.m4.md, hledger/Hledger/Cli/Commands/*.md, doc/common.m4

# Tmux window name - symbols #n4s
	2023-04-21T09:55 joo	#observation man løber hurtigt tør for plads med lange tagnames
	2023-04-21T09:55 joo	#ide kunne man putte unicode symboler som vinduenavne så der er plads til mange åbne aktiviteter ?
	2023-04-21T09:55 joo	#ide en anden mere simpel løsning kunne være at croppe til 3-5 tegn max
	2023-05-04T08:09 joo	har croppet til 4 tegn, kunne være en god løsning kombineret med #findwindow

# TUI Bibliotek test #n4s
	2023-04-19T11:46 joo	https://github.com/VladimirMarkelov/clui bør teste dette tuilib - inspireret af #turbovision

# Ubalance ved ændring af transkationer - spørg bruger #n4s
	2023-04-19T11:43 joo	#ide opdag ubalance ved transaktion - hvis man ændrer det ene beløb på 2 benet post vil man typisk foretrække at det andet også ændres.
	2023-04-19T11:43 joo	hvis der er yderligere ubalance vis en guide
	2023-07-11T15:42 joo	#ide kan også være en kontrol i key.php

# Cronjobs #n4s
	2023-04-19T11:34 joo	skal have alle cronjobs ind i mit eget system, ikke noget direkte i cron, og ikke nogle scripts i hjemmemappe


# Mappestruktur n4s-export tmp data mv
	2023-04-18T15:25 joo	#ide har jeg brug for n4s-eksport og tmp - eller kan jeg nøjes med en af dem, eller ingen? måske bare ligge i data under brugerens mappe /data/users/$(whoami)
	2023-07-11T15:42 joo	#ide bør ligge under /data/users/$(whoami) - EVT med et symlink i hjemmemappe

# CSV Import og eksport #n4s
	2023-04-18T07:36 joo	#ide vælg CSV fil fra tmp mappe til import i stedet for at copypaste
	2023-04-18T07:37 joo	#ide lad os også kunne eksportere filen til et givent navn i tmp mappen
	2023-04-25T09:06 joo	#ide ved csv import lav en transaktion baseret på csv linie uid for hver transaktion - når den findes, så er den allerede lavet og skal ikke laves igen - for at uid er så præcist som muligt er det vigtigt med så mange kolonner som muligt - risiko kunne være hvis der er to poster samme dag med samme beløb og samme saldo og samme tekst, så vil det kun blive oprettet en enkelt gang - evt. kunne man tage uid på den foregående transaktion med for at forhindre dette


# Pakke / distribution af system #n4s
	2023-04-07T09:36 joo	debian pakke - eller docker eller andet
	2023-04-10T19:43 joo	#ide kunne godt være man bare henter en tgz fil som pakker ud i /
	2023-04-13T08:51 joo	#outsourcing pakke n4s .tgz/sh
	2023-04-17T07:30 joo	#ide jeg bør øve mig på manual installation og forberede en god guide til hvordan man gør det

# Journal print - når kun en overskrift #n4s
	2023-04-18T07:00 joo	#observation hvis der kun er en overskrift bliver indholdet printet tomt - #teori der er altid en tom kategori i alle rapporter

# Spørg om man vil køre pandoc #n4s
	2023-04-13T10:17 joo	#ide kun køre pandoc hvis bruger bekræfter at der ønskes rapport✔ @ 2023-04-18T07:48 (joo) 
	2023-04-18T07:48 joo	#ide til forbedring - spørg via read med et timeout, således at hvis man ikke aktivt beder om udskrift lukker vinduet når man er færdig

# Webinterface #n4s revival
	2023-04-18T09:27 joo	#ide genoplivning af webinterface - det er nyttigt
	2023-04-06T18:00 joo    #ide simpel webformular til mobil indtastning alternativ til at male mig selv skal gå i auto buffer (#rt 12087)
	2023-04-13T08:52 joo	man skal kunne kopiere de seneste linier med et nyt timestamp og evt rette tekst

# VIM TSV #n4s
	2023-04-08T23:55 joo	#ide bruge vim-tsv fremfor visidata så man ikke skal lære flere programmer
	2023-04-13T08:52 joo	#visidata har noget flere funktioner, vim er nok ikke p.t. et alternativ - men man burde kunne linke til et visidata sheet så det bliver konveret til markdown

# Vim - table mode #n4s
        2022-03-02 10:58 joo    https://github.com/dhruvasagar/vim-table-mode
        2023-03-26T12:07 joo    behovet er stadig aktuelt her et år efter
        2023-04-07T20:53 joo    er begyndt at bruge det, - skal manuelt aktiveres pt - skal have bundet en knap
	2023-04-13T08:54 joo	#ide #vimrc start tablemode hvis funktion eksisterer #outsourcing
	2023-10-20T02:49 joo	konklusion smart, men nok for svært at bruge for alm. brugere

# Import buffer til journaler #n4s
	2023-04-06T10:04 joo	#ide importbuffer til hver fil - når man åbner filen skal den vise tydeligt hvis der er en importbuffer, så man kan indlæse den hvor man har lyst - evt. previewe den
	2023-04-06T10:05 joo	det burde kunne løse problemet med at en bruger låser filen, således at programmer ikke kan skrive i journaler. Programmer vil fortsat ikke kunne skrive direkte i journaler, men i en buffer fil som indsættes manuelt af operatøren
	2023-04-13T08:55 joo	buffer filer kan være en skjult fil med et uid
	2023-05-03T20:50 joo	lige nu bliver bufferfilerne kaldt $tagname.scrabble - de er ikke skjulte

# Markdown query tool #n4s
	2023-04-01T15:58 joo	#ide ved pandoc udskrift lad bruger vælge hvilke sektioner der skal med (fzf --multi) - lad gerne Logins, Stamdata etc være off by default
	2023-04-05T09:32 joo	#ide #markdownquery [fil] --include//--exclude [tags] eller --tui for visual selection via #fzf
	2023-04-05T09:33 joo	funktionalitet: tag markdown fil - vis kun de sektioner man ønsker at vælge - eller fravælg dem man vælger fra
	2023-04-05T17:28 joo	har lavet det, og har også publiceret den som gist
	2023-04-05T17:31 joo	ønsket opførsel marker alle sektioner som default, lad mig afmarkere dem jeg ikke ønsker med
	2023-04-05T17:31 joo	15:31 < joo> anyone knows fzf and how i can execute the select-all command on startup to select all the items in --multi?
	2023-04-05T17:32 joo	#ide default negativ query på Stamdata etc✔ @ 2023-04-05T17:37 (joo) virker faktisk fint nok
	2023-04-05T18:18 joo	#ide tilføj filtrering på subtabs også hvis muligt - kræver nok en del
	2023-04-13T08:55 joo	virker fint, skal have sat til select-all fra start #outsourcing
	2023-10-20T02:49 joo	#ide dele mkd.bash som en #gist

# Vim 9 Virtual text #n4s
	2023-04-05T08:20 joo	#ide bruge virtual text funktionen til at hente og vise data fra underliggende hashtags
	2023-04-05T08:21 joo	relevante showcase videoer i denne tråd https://github.com/vim/vim/issues/7553
	2023-04-13T08:56 joo	#outsourcing

# Journal kronologisk historik underligt output #n4s
	2023-04-03T16:15 joo	output er ihvettfald ikke fra dagen, men fra fortiden #n4s #vitouch #stuff
	2023-04-03T19:32 joo	nu er det lidt mindre underligt, har fikset parseren lidt
	2023-04-13T08:56 joo	er disablet indtil videre - skal skrives om til markdowntables

# There is no checkmark (U+2714) in font [lmroman10-regular]:mapping=tex-text;! #n4s
	2023-04-03T10:53 joo	#pandoc problem skal have en font der understøtter U+2714 #spørgirc✔ @ 2023-04-03T14:06 (joo) 
	2023-04-03T14:06 joo	<joo> is there a good font with good utf8 support ? that is available on most computers or if you have installed pandoc - not sure if pandoc  includes fonts ? - afventer respons @ #irc
	2023-04-13T08:58 joo	spurgt igen, tror ikke jeg fik noget respons sidst✔ @ 2023-04-13T09:01 (joo) det er der ikke
	2023-10-20T02:49 joo	har staidg ikke nogen checkmarks, men i stedet et @

# Søgning i journaler #n4s
	2023-04-01T21:23 joo	søgning i journaler - F1 - c - M - oprettet - virker - plads til forbedring
	2023-04-13T08:58 joo	også bundet til F6
	2023-10-20T02:50 joo	pt ude af drift #fixme

# Vitouch multi pdf rapporter overskrivning #n4s
	2023-04-01T12:35 joo	#observation en rapport overskrevet med indhold fra anden rapport, - kræver unikke tmp filnavne #vitouch #n4s
	2023-04-13T08:59 joo	tror det er fikset
	2023-04-26T10:17 joo	hver process bør bruge unikke filnavne

# Main screen #n4s
	2023-04-03T16:32 joo	#ide låst mainscreen #tmux skal være main menu på hver vindue nr. 1 #n4s #ncurses #mainmenu
	2023-04-08T10:37 joo	#ide er der en trigger når der kun er et vindue tilbage så man kan starte et nyt ?
	2023-04-11T10:13 joo	spurgt på irc om der er en trigger til tmux sidste vindue, afventer respons

# Ledger filer konverteres til json #n4s
	2023-04-10T19:46 joo	#ide alle ledger filer skal konverteres til json

# Hastighed n4s
        2023-02-16T16:06 joo    #observation #n4s alvorlige hastighedsproblemer - kan først og fremmest testes på olsensrevision	2023-03-06T08:06 joo    både cas og joo observerede særligt hastighedsproblemer på texas samt olsensrevision	2023-03-15T17:49 joo    lavede det så hver process laver en fork og arbejder med den tidligere fremsendte fil	2023-03-16T09:37 joo    ser ud til at have hjulpet, er ret hurtigt nu 
	2023-05-03T20:50 joo	#fork i key.php ledger fungerer ikke optimalt, men er vidst hurtig nok - hvordan kan vi genskrive noget i terminalen ?

# Reconcile alternativ n4s
        2023-03-19T16:57 joo    #ide #n4s kunne man putte periode på hver post i en afstemning af a-skat f.eks. A-skat betaling for juli som betales i august sættes til Passiver:A-skat:Juli
	2023-05-07T17:51 joo	#confirmed det er vidst en god ide til hvordan man kan afstemme, denne ide bør også videreføres til moms som bør konteres i kvartaler f.eks. Passiver:Moms:2022H2:Salgsmoms - dette vil dog kræve at n4s kender brugerens momsperiode (måned, kvartal, halvår)

# Sætte LEDGER_WIDTH variabler baseret på hvad man har #n4s
        2023-03-18T19:45 joo    #ide #n4s hvis man kun har en lille pane, så skal den ikke lave lang PAYEE & ACCOUNT, men det må den godt hvis der er god plads

# n4s Centralisering postgresql
        2023-03-16T23:00 joo    #ide decentralisering ved at kører lokalt. forbindelse via postgresql server (#rt 11313) 2023-03-19T16:54 joo   #ide kunne man få alexandr til at give sit perspektiv på løsningen om den er god ? Automatisk allokering Olsens Revision / 0lsen                      2023-03-21T13:10 joo    #eftertanke tror jeg lige prøver #resilio og evaluerer min strategi

# Eksport af data til andre regnskabssystemer #n4s
        2023-03-22T08:44 joo    #ide ved en simpel mapning til standardkontoplan burde man nemt kunne spytte alle poster ud for en periode til e-conomic, uniconta, dinero etc
	2023-10-20T02:51 joo	lige nu kan 'l csv' fint klare ærterne, men kræver manuel mapning



# Justering af balance - ncurses #n4s
        2023-03-22T15:13 joo    #ide #n4s justering af balance - oprettes som ny json-transaktion - hver række i balancen har tre kolonner: Bogført værdi, Justering, Ny saldo. Man skal kunne taste i Justering samt ny saldo, som på #tastselverhverv ved momsreguleringer. Ny transaktion åbnes efterfølgende i vi
        2023-03-31T21:04 joo    #ide jeg bør få styr på det med php & ncurses
	2023-04-01T20:54 joo	#igang med ncurses.php
	2023-04-08T13:50 joo	har rodet lidt med det - aktuel linie er nu fed 

# Årsafslutning uoverensstemmelser #n4s
	2023-04-01T15:54 joo	#problematik der ændres i tidligere årsregnskaber, men den nye åbning bliver ikke rettet #løsningsmulighed det skal ikke være mulige at ændre i regnskaber der er lukkede
	2023-10-20T02:51 joo	skulle være løst med det nye system hvor der ikke skal laves årsafslutninger

# Købsordrer #n4s
	2023-04-13T09:04 joo	udgifter skal kunne kobles på åbne købsordrer - købsordrer tages automatisk med i budget

# Automatisk kreditorafstemning #n4s
	2023-03-29T12:29 joo	#inprogress kreddeb.bash / kreddeb.php
	2023-04-13T09:04 joo	#procedure gennemgå alle poster på fejlkonto, se om der er nogle #åbneposter på debitor / kreditor
	2023-05-01T18:12 joo	#ide skrive på skærmen at nu hvor banken er importeret er det måske en ide at gennemgå kreditorer / debitorer - evt vis udskrift / saldo herefter

# Automatisk finding af dubletter #n4s
	2023-04-05T19:44 joo	#ide #find duplicate entries- when amount and date is the same
	2023-04-13T09:05 joo	#outsourcing on entry when amount and date exists already (or within approximation, ask for details), warn the user, and let the user instead edit the existing transaction - or add new

# Difftable markdown - journal historik #n4s
	2023-04-05T19:47 joo	difftable har jeg slået fra i #dagbog - ser ikke godt ud - kan vi få det til at fungere - en kronologisk visning af aktiviteter sat op i #markdowntabel

# Timer.bash virker ikke mere - tager ikke input #n4s
	2023-04-03T18:54 joo	timer.bash tager ikke input mere til nye timere, virker ikke
	2023-04-03T18:55 joo	#ide omskriv input del fra read fra stdin til tmux input, eksempel : tmux command-prompt -p "Indtast timer" "new-window vi '%1'"
	2023-10-20T02:52 joo	#fixme

# Transplant vim #stuff #n4s
	2023-05-04T21:02 joo	#outsourcing #vimscript select buffer with V, press hotkey, it should ask what file to transplant it into - put it into the markdown section # Transplants (if not exists, create in bottom) #scrabble
	2023-10-20T02:52 joo	har fået implementeret transplant, men den skal lige have historikken med #fixme

# Yearend #n4s
	2023-07-05T10:19 joo	yearend.bash skal nulstilles kapitalkonti samt momskonti - senere evt. anlægskonti #scrabble

# N4s Lokale ressourcer
	2023-07-07T16:58 joo	#ide køre kommandoer på klient fx browser , filmanager osv via #sshtunnel #scrabble

# Rentetilskrivning #n4s
	2023-05-07T18:23 joo	interest.bash og interest.php som udkast til manuel rentebregning

# Dokumentere funktioner i markdown #n4s
	2023-05-07T17:41 joo	#ide få flyttet de manual sider jeg har lavet allerede til en kasse ved navn n4s
	2023-10-20T02:54 joo	#ide dokumentationen til systemet skal være indbygget som #stuff wiki

# Ved registrering i journaler medtages nye filer #n4s
        2023-04-03T11:10 joo    #ide tag nye filer fra kundens dropbox med i journalen
        2023-04-28T09:12 joo    #ide man kunne synkronisere med resilio så serveren også har adgang til dropbox
        2023-07-21T21:34 joo    #godide
	2023-10-04T10:37 joo	lige nu tages nye filer med i #drp #dagbog

# Ændring i 998 script #n4s
	2023-07-19T19:06 joo    hvis jeg får problemer #osb #vigtigt har udkommentere noend i 998_ script
	2023-07-28T18:02 joo	slettes om en måneds tid hvis jeg ikke har haf nogle problemer
	2023-10-04T10:37 joo	historie 997 tog alle posteringer og dannede årsafslutningsposter on the fly
	2023-10-04T10:37 joo	historie 998 afhænger af årsafslutning.bash og genererer enkeltposteringer
	2023-10-04T10:37 joo	historie 999 er som 998, bortset fra den kun laver en resultatposteringer pr. rapporteringsperiode
	2023-10-04T10:37 joo	ændret til 999_Periodresult

# Vim menu #n4s
	2023-09-26T11:45 joo	#ide gøre n4s mere brugervenligt med vim menuer

# Transplant vim historik #n4s
	2023-09-26T11:44 joo	tilføj historik til både source og destinationsfil når man transplanterer tekst

# Topic modelling #n4s
	2023-09-19T21:00 joo	Topic modelling (#rt 16395) #scrabble muligvis nyttigt begreb til samkøring af data

# Textual #n4s
	2023-10-06T22:49 joo	#ide pythonlib textual #tui
	2023-10-10T13:01 joo	har testet det, virker ikke alt for godt, lettere ustabilt, men virkelig flot

# Gum #n4s
	2023-10-06T22:56 joo	#ide textui #gum

# Stuff #n4s - tillad ikke sletning
	2023-07-27T00:06 joo	ide ikke tillade sletning men gerne transponering - standard slette knapper bindes til transponering
	2023-10-20T02:55 joo	kræver transponering får historik først
	2023-10-20T02:56 joo	eftersom markdown historikken er pæn nu og på sektionsniveau, er det måske ikke nødvendigt

# N4s - timelog entries
	2023-07-25T11:04 joo	#ide transaktioner oprettes baseret på timelog entries og deres værdier baseret på regler - når de først er oprettet med deres uid kan de ændres, de bliver ikke opdateret fra den linie igen #scrabble

# n4s - transaktions historik i separat fil
	2023-07-18T12:00 joo	Ide trans history separate file (#rt 15081) #scrabble

