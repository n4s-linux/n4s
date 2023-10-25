# n4s vitouch reload
2023-10-25T15:58 #ide reload fil for at få scrabbles med og committe changes til historik #vitouch

# N4S pandoc vs wkhtmltopdf
## Fordele WKHTML
2023-10-25T15:57 lynhurtigt ifht pandoc
## Ulemper WKHTML
2023-10-25T15:57 internal-links virker ikke - kan evt. lave en dokumenteret bug report - et gammelt problem


# n4s Ledger på ny server outputter ikke code i CSV felt
2023-10-25T00:29 #observation kan ikke lave kontokort med bilagsnumre da kode ikke kommer med på ny server
2023-10-25T00:29 workaround rapporter fra gammel server
2023-10-25T15:56 tror det var en ener, har ikke oplevet noget i dag
## Teori ledger version
2023-10-25T00:30 afkræftet da jeg har prøvet at kopiere gammel binary over, samme resultat
## IRC
2023-10-25T00:30 #spørgirc
2023-10-25T01:21 fik hjælp til at det var miljøet den er gal med
### LEDGER_DEPTH
2023-10-25T01:22 har opdaget at hvis jeg kører unset LEDGER_DEPTH kan jeg efterfølgende sætte den som jeg vil, og det kører


# Statuslinie afstemningskonti #n4s
2023-10-23T16:16 #ide statuskonti i tmux line
2023-10-23T16:16 implementeret så den tager alle Aktiver:Likvider - skal tilpasses så brugeren selv kan vælge hvilke konti der skal vises

# Styr på skat #n4s
2023-10-22T17:40 ide konto til styr på skat 
2023-10-22T19:27 #ide praksisændring: hver år omkonteres det indberettede resultat fra Egenkapital:Overført Resultat til Egenkapital:Indberettet resultat - således vil evt. differencer fremkomme som residualpost

# N4S automatisk konsolidering månedsvis
2023-10-22T13:37 #ide lave konsolidsering månedsvis i autoscripts, så der kommer historiske poster i moderselskab
2023-10-22T13:53 #observation det styres 999_Periodresult.bash og følger dens metode
2023-10-24T09:28 er fikset ved at udskifte 999_Periodresult med 999_periodresult som kører på dagsbasis fremfor periodevis.

# Tmux menuer - visning på flere klienter #n4s
2023-10-21T14:59 #observation tmux-menus bliver kun vist på den aktive klient, ikke på evt. andre attached klienter #spørgirc
2023-10-24T09:34 mulig løsning tmux-menus skifter ud med fzf-baserede menuer

# N4s noter i forskellige afdelinger kan ikke have samme navn
2023-10-21T01:36 #observation de bliver flettet sammen i html rapporteringen f.eks Aktiver:Kapitalandele & Indtægter:Kapitalandele

# N4s backup virker ikke på anden bruger
2023-10-21T00:00 #observation backup hænger når man åbner regnskab på en anden bruger, skal undersøges
2023-10-24T09:34 #teori permission based

# Rapportering n4s - naturlige fortegn
2023-10-18T11:26 #ide naturlige fortegn i rapportering - dvs indtægter er postive, udgifter er negative, egenkapital skal også vendes - aktiver står som de skal
2023-10-21T01:58 så er der naturlige fortegn på indtægter, udgifter, passiver, egenkapital - skal der også være på noter ?

# Pandoc erstatning #n4s #stuff
2023-10-16T13:45 #observation pandoc er meget langsomt
2023-10-16T13:45 #ide min egen markdown til html rapport - bør også lave vim style highlighting

# Frogmouth Markdown #n4s #stuff
2023-10-13T21:14 #observation frogmouth er rigtig god til at vise markdown filer i terminalen
2023-10-13T21:14 #baseret  @ #textualize

# N4S tmp outputmappe
2023-10-13T10:10 lav ny mappe til output fremfor tmp som har mange tmpfiler

# Borgbackup add remotes #n4s
2023-10-13T08:25 #ide #n4s #borgbackup sync() add remotes

# Wallacepos #n4s
2023-10-12T21:27 #ide pos til n4s
2023-10-12T21:27 forked #wallacepos https://github.com/micwallace/wallacepos
2023-10-22T19:32 kan det integreres i n4s ?

# Ny bogføringslov - og overholdelse af gl. bogføringslov #n4s
## Standardkontoplan
2023-10-11T09:55 nødvendig mapning til erhvervsstyrelsens standardkontoplan

## Bogføring implementeres der låser posteringerne
2023-10-11T09:53 #ide bogfør script der bogfører transaktioner til ledger-fil - skal være som en blockchain således at hver transaktion indeholder md5hash af de foregående transaktioner og man derved kan verificere hvornår der er bogført hvad

## Ugentlig backup
2023-10-11T09:51 #ide backup skal sættes op til at køres automatisk dagligt med #borgbackup i et cronjob
2023-10-11T12:46 sat #borgbackup op i funktionen sync koblet på sr() således at den tager en backup hver gang man åbner et regnskab

## XML fakturering og modtagelse
2023-10-04T10:43 #ide implementere fakturering #n4s
2023-10-10T21:55 lagt https://github.com/num-num/ubl-invoice/tree/master ind i svnroot - kan køres med phpunit og lave invoices, så skal jeg bare have en parser der kan læse også
2023-10-11T09:55 #observation erst har også lagt nogle pdf templates op man kan arbejde ud fra
2023-10-10T21:56 skal både kunne læse og afsender xml fakturaer i dette format - umiddelbart ved udgangen af 2024

## Anmeldelsespligt
2023-10-10T21:56 specialudviklede systemer skal ikke anmeldes, men virksomheden skal selv kunne dokumentere at det overholder kravene

## PSD2
2023-10-20T02:40 bankintegration skal genaktiveres

# Ingen årsafslutning regnskaber #n4s
2023-10-10T17:24 #ide #n4s ingen årsafslutning, men altid beregn primosaldo på konti af typen Aktiver Passiver Egenkapital Fejlkonto og sæt den ind - modkonto Egenkapital:Overført resultat
2023-10-11T09:52 fordel er vi ikke behøver tage forbehold
2023-10-17T22:37 implementeret funktion i aliases - testet på egne regnskaber - skal træde varsomt på kunderegnskaber i starten og skal have slettet deres manuelle årsafslutninger
2023-10-17T22:38 #ide årsafslutning harmuligvis stadig en berettigelse ifht resultatdisponering ? 2023-10-20T02:40 resultatdisponering kan blot være som en justering til den automatiske overførsel
2023-10-20T02:40 indtil videre kører det godt

# N4s periodisering
2023-10-08T20:09 observation p-start og p-end har ingen effekt i demoregnskab

# Touch screen interface #n4s
2023-10-05T11:38 #ide touchscreen interface n4s - touch virker godt på en stor terminal - behøver ikke være gui - i vim testet med mouse=a

# Autoupdate n4s
2023-10-08T11:05 tilføjet autoupdate (git pull ved hver gang aliases kører) - se hvordan det går
2023-10-11T09:54 disablet det, virkede ikke efter hensigten, har i stedet skrevet i README hvordan man opdaterer

# N4s - vim mus support
2023-10-05T11:42 #ide slå mus til og fra nemt (mouse= mouse=a)
2023-10-11T09:55 er slået til, jeg har slået det fra i min egen ~/.vim/custom.vim

# n4s - stuff - Diff fil forbedring
2023-04-22T00:41 i stedet for en diff-fil som nu, så kunne man have en diff.out og diff.in til nye linier og slettede linier. de kunne prefixes med hvilken markdown kategori de er i #outsourcing
2023-06-12T13:30 #ide #markdowndiff skal vise hvad der er rettet i hver sektion på en læselig måde
2023-10-04T10:25 #regex er for besværligt, manuel line by line parsing er #thewaytogo
2023-10-04T10:29 #ide outputte hver fil til en parsed markdown fil hvor sektioner prependes
2023-10-08T10:15 markdown parser til difffile lavet og implementeret
2023-10-08T10:15 #observation plusser og minuser kommer hver for sig, det ville være bedre hvis de kom historisk dvs fra top til bund og plusser og minuser blev blandet

# N4s bedre saldobalance og kontokort
2023-09-26T12:15 #ide bedre kontokort og saldobalance - evt. som simpelt tekstformta
2023-10-04T10:27 har opdateret html funktion med bedre htmlbaserede saldobalancer med noter, og bedre kontokort
2023-10-11T09:58 #ide lav også traditionel opstilling baseret på erst kontoplan - kræver der bliver mappet og rapporteringen sker udfra en flad nummerbaseret struktur

# Vitouch latex support #n4s
2023-06-20T18:17 #ide detekter om det er en markdown eller latex fil og skift pandoc kommando derefter

# N4s regnskabsår
2023-06-07T13:52 #ide for hver årsafslutning skal der oprettes et regnskabsår som man let kan skifte til - skal kunne navngives som man ønsker

# Hvidvask markdown section #n4s
2023-05-29T16:19 #ide en Hvidvask sektion på hver kunde som jeg løbende kan få opdateret
2023-07-21T21:09 har jeg fået gjort på mange
2023-09-14T16:03 #update har brugt det, men er mindre vigtigt nu hvor jeg er under nedlukning

# Mini n4s pc
2023-04-26T16:06 #ide lille bitte skærm og mulighed for bluetooth tastatur #raspberry pi til n4s klient/server
2023-04-26T16:05 #ide #minipc til #n4s https://dk.rs-online.com/web/p/display-udvikling/2306185?cm_mmc=DK-PLA-DS3A-_-google-_-PLA_DK_DK_Raspberry_Pi_%26_Arduino_%26_ROCK_og_udviklingsv%C3%A6rkt%C3%B8j_Whoop-_-(DK:Whoop!)+Display-udvikling-_-2306185&matchtype=&pla-307688689883&gclid=CjwKCAjwl6OiBhA2EiwAuUwWZdt7abCSHshXi8f2mO_tLnY75fg2m3sxc2Mrsq_dndEcFfDHuKWgQxoCnOUQAvD_BwE&gclsrc=aw.ds #raspberrypi
2023-04-26T16:06 også denne https://www.geekbuying.com/item/3-5-Inch-IPS-TYPE-C-Secondary-Screen-CPU-GPU-RAM-HDD-Monitoring-517026.html?Currency=DKK&source=googleads&utm_source=google&utm_medium=cpc&gclid=CjwKCAjwl6OiBhA2EiwAuUwWZf--uEpv4j3-ZSpAD4BTO9pkBjjE5AndG939gDL0BOW4kQVKxVtuCBoCV3MQAvD_BwE
2023-10-01T19:45 jeg har allerede minipcer på lager
2023-10-04T10:28 kan spare 500kr/md til #vultr hvis jeg får sat min egen server op, men har nok stadig brug for en #jumpserver
2023-10-11T09:59 #ide bedre at bruge en rigtig server

# Fremtidsregnskab #n4s
2023-04-16T17:09 #ide i stedet for budgetter, lav regnskab x år ud i fremtiden baseret på budgettal
2023-04-17T16:51 #udfordring det er svært at beregne budget på residualposter, det vil kræve man holder styr på alle tallene
2023-04-18T07:04 #ide kan man tage udgangspunkt i den nuværende ledger? jeg synes den bliver ved at lægge sig selv oveni - altså at resultatet bliver fordoblet for hver kørsel
2023-04-26T17:04 #ide hver markdown sektion bør tidsestimeres - spørg brugeren  undervejs i processen efter #vitouch
2023-05-02T12:30 #every.php hvis man skriver #every som comment bliver det automatisk budgetteret fremover
2023-07-21T21:19 #godide har et fungerende newbudget
2023-07-21T21:19 #ide simpel matrix editor til config filer - første config fil er bare et array budgetdata

# Tvivlsomme poster i n4s - hashtags udvælger til rapporteirng
        2023-02-28T21:00     #ide Hashtags medtages i specs fx ulovligt aktionærlån mangler bilag , forkert modtager ml58, tvivlsomt fradrag (#rt 10927)

# Csv til markdown #n4s
2023-03-22T16:00 #ide indtastning af CSV data konverteres automatisk til markdown så de kan vises nice i rapporter https://github.com/lzakharov/csv2md
2023-03-31T21:03 har fået gjort det her i nogle rapporter, så der er fremskridt
2023-07-21T21:38 #godide

# Eksport af data til andre regnskabssystemer #n4s
2023-03-22T08:44 #ide ved en simpel mapning til standardkontoplan burde man nemt kunne spytte alle poster ud for en periode til e-conomic, uniconta, dinero etc
2023-10-22T19:33 lige nu kan man eksportere og manuelt mappe konti

# Afstemning ved et ekstra lag poster #n4s
2023-03-18T15:13 #ide #n4s oprettelse af modpost til bank så den kan blive afstemt - modposter til alt, således at man skal kunne trække en balance på 0 når alt er udlignet
2023-07-21T21:41 det er en god ide med et skyggeregnskab som bliver lagt oveni

## Eksempel
RegnskabBank 31.12  197.000
SkyggeRegnskabKontoudtog-197.000
-----------------------------------------
Forskel       0
-----------------------------------------

# Min egen markdown parser #n4s
2023-03-12T11:24 #ide #stuff #n4s lave min egen markdown parser alt inklusive (også toc), drop pandoc
2023-07-21T21:43 #godide

# N4S database problem
2023-03-09T20:13 #ide kunne n4s være en database - postgresql som understøtter json - når den bro er bygget er vi decentrale nok #n4s-storage
2023-03-17T11:00 #ide et #quickfix kunne være #unison sync til en central server - eller #resilio

# Allerede åbne journaler n4s
2023-03-09T20:12 #ide #stuff #n4s allerede åbne journaler skal søge efter en fane ved det navn, om ikke andet i samme session, men evt. på alle sessioner, i stedet for at sige den allerede er åben - foreløbig af samme bruger


# Vim tabbing #n4s
        2023-03-01T19:00     #ide tabbe mellem felter i vim (#rt 10957)
2023-10-22T19:33 bedre løsning tui forms - evt. outsourcing med forms library

# Markdowngrep #n4s
2023-01-31T10:24 #ide #markdowngrep hvis man sammenkæder markdown headings  med underliggende poster kan man bedre greppe og finde relevante informationer 2023-02-01T12:07 #hjælpmigathuske
2023-02-01T12:50 har lavet det, men er i tvivl om hvordan funktionen skal implementeres - markdowngrepexpand.php
2023-02-01T13:50 skal implementeres i #diffile / #vitouch
2023-02-05T18:39 #ide kan deles på nettet, der må også være andre der kunne have glæde af dette

# Search - bulk update #n4s
2023-01-30T10:12 #ide #n4s search funtion - also add bulk update

# n4s - manuel årsafslutning - ville formentlig løse vores problem med balancer der ikke stemmer
2022-12-25T16:31 #ide #n4s manuel årsafslutning
2023-01-03T14:38 har umiddelbart løst problemet, og også hastigheden
2023-01-27T22:00 er ikke implementeret på alle kunder, men det er relativt nemt at gøre og har ikke oplevet nogle problemer med det endnu
2023-04-08T01:28 er implementeret på mange kunder nu

# n4s - entry after unaccounted aliases
2022-12-21T15:39 når man skriver efter et nyt unmapped aliases, så virker det ikke

# Csv import preview #n4s
2022-12-06T22:34 #ide kunne man lave et preview på csv import
2023-01-27T21:52 det er ikke helt ligetil

# n4s største udfordring er hurtig data storage

# Blockchain n4s
2022-11-20T08:09 #ide n4s på blockchain
2023-01-27T22:07 #ide hver fil / transaktion puttes ind i blokken og markeres som "brugt" med reference til punktet i blockchainen - hver ny blok har alle de tidligere blokkes checksum

# Tmux FORMATS section #n4s
2022-10-10T17:04 #ide tmux FORMATS sektionen i mansiden kan bruges til at styre pane titler individuelt

# Frappe #n4s
2022-09-20T15:32 #ide brug erpnext sammen med n4s samt https://github.com/frappe/books/tree/master/reports

# Ledger query kommando #n4s
2022-08-30T18:06#ide query kommando er ret kraftig, skal jeg gøre mere brug af
2023-10-22T19:35 kan bruges til at filtrere transaktioner

# GDPR bilag #n4s
2022-08-25T06:18#ide gdpr bilagsopbevaring - skal de være låst inde, eller kun personfølsomme oplysninger ?
2023-10-22T19:35 sletning efter 5 år kan automatiseres med find -mtime

# Undermapper / underregnskaber #n4s
2022-07-30T12:51#ide undermappe #n4s et helt tpath under et tpath - måske i mange niveauer ? eller er 2 nok ? #arbitrarylimits #aginstgnu #hardtoimplement på flere niveauer

# Ide n4s se kundedata altid
2022-06-27T08:32det skal være hurtigt at frembringe stamdata, herunder også cpr numre etc 2023-10-20T02:42 cpr-numre skal maskeres i vim samt andre følsomme data, skal kræve pw
2022-08-16T08:29#ide stamdata skal ligge i separat "database" fra sagerne

# n4s navngivning

# Raspberry pi n4s
2023-05-04T17:26 #raspberry pi med #n4s så folk kan købe en færdig maskine #scrabble

# N4s enheder
2023-05-04T17:31 #ide #n4s #produkt i form af stationær minipc eller opsætning af vps, - evt også hosting af vps #scrabble

# Arbejde i solen #n4s
2023-05-07T13:09 arbejde fra laptop i solen - finde ud af hvad for et terminal tema der er bedst #scrabble
2023-10-20T02:43 med det rette tema på en mørk terminal er det faktisk nemt at arbejde i solen

# N4s navn
2023-05-10T13:31 #navn #n4s #movemountains #scrabble
2023-08-07T22:53 #navn #n4s #fightthewind #ftw

# N4s - webinterface til telefon
2023-05-25T12:42 webinterface til stuff, så kan jeg benytte det fra en ipad eller tlf #scrabble
2023-05-30T18:53 #indtast #blokke direkte i #thenow eller tryk F12 #scrabble
2023-08-07T22:51 #mobiltelefon = skrald

# N4s - vidensdatabaser via git
2023-05-30T18:56 #deling af n4s-markdown vidensdatabaser via github - kan gøres så folk sender pull request ind #scrabble
2023-06-06T22:24 kunne også være en hidden git service
2023-08-07T22:51 #ulempe #centraliseret

# N4s - vidensdatabaser via sshfs
2023-05-31T10:41 #markdown wiki folder sharing over sshfs - del en mappe over ssh via tor, lad folk mounte den #scrabble
2023-06-06T22:25 man kan også lade folk synkronisere via unison
2023-08-07T22:50 #ulempe #centraliseret

# Zipfil n4s
2023-06-18T12:32 #ide n4s zip fil med alle regnskabsdata inbygget i PDF rapport - som nemt kan indlæses i en anden n4s #scrabble

# n4s - forking
2023-06-28T12:44 #n4s forke et vindue med samme miljø - skal spørge om nyt vindue eller vertikalt/horizontalt split #scrabble

# Distribution til windows #n4s
2023-07-24T10:54 #ide distribuere vm til windows, - men bør være en exe installer
2023-07-24T10:54 afventer #d4
2023-07-24T10:55 kontakt til mulig interesseret programmør @ #whatsapp afventer
2023-07-26T16:21 #n4s4windows
2023-10-20T02:43 er lavet som ova, afventer evt. respons fra brugere om problemer med kørsel
# Cygwin #n4s
2023-07-28T11:29 #cygwin kan n4s køre i det ? installeret på #kamatera #scrabble
2023-07-28T18:04 ser ud til at køre fint, undtagen x11 hvor keyboard ikke virker

# PDF regnskab #n4s
2023-07-11T15:41 #ide put krypteret tgz fil med hele regnskabet inde i pdf filen - evt. flere sæt med forskellige detaljenivauer
2023-10-22T19:36 hele regnskabet kan også krypteres med luks og indlejres i pdf, både en version med fulde detaljer, og en version hvor posteringer er anonymiserede

# Nye transaktioner - entry - kig efter dublikater #n4s
2023-05-07T13:29 #ide hvis man laver en ny transaktion bør den søge efter duplikater

# Regnskabsår som skyggeregnskab #n4s
2023-05-07T11:35 #ide forbedre regnskabsår ved at separere dem i hvert sit skyggeregnskab - så kan man heller ikke ændre ved en fejl i et eksiterende
2023-10-22T19:37 lige nu opereres der ikke med regnskabsår, men åbningsposter dannes efter behov

# Vim Menuer fremfor genvejstaster #n4s
2023-05-03T08:47 #ide menuer til vim da jeg er ved at løbe tør for taster og de er svære at huske for andre end mig selv
2023-05-03T17:40 #vim har nogle popup menuer som kunne være meget nytige - men bør lave lidt research på hvad er den bedste #vimpopup

# Ledger split-window og popup output med autoupdate #n4s
2023-04-28T08:49 #ide har brug for at når key.phps fork som opdaterer data i baggrunden er færdig og overskriver filen loades den bare uden videre #erdetfarligt ? hvad kan der ske #worstcase hvis jeg implementerer dette ?

 # Stuff - F6 virker ikke - opslag
2023-04-27T08:57 #observation kan ikke slå #månedsløn eller andre tags op med F6
2023-04-28T08:39 lige nu er det way over my head hvordan grepsearchtag fungerer

# Hurtig indtastning af gentagne linier #n4s
2023-04-27T07:30 #ide gentag en linie - søg i alle hashtags efter ofte / seneste brugte - erstat kun timestamp i ny post

# Vitouch opslag af tags - afbryd hvis ikke valg noget #n4s
2023-04-26T17:27 #ide hvis #ctrl-c er trykket skal den ikke fortsætte, men afbryde helt - når man trykker F2 for at vælge en journal med #vitouch

# Vim 256 color syntax #n4s
2023-04-26T09:59 #ide kan vim highlighte med 256 farver ? afventer #irc
2023-04-26T10:25 understøtter også truecolor, men er nok lidt overkill
2023-04-26T10:26 for 256 farver skal der blot henvises til hexkoder
2023-10-22T19:37 har 256 farver, men skal have specificeret farver nærmere, lige nu afhænger de af brugerens tema

# Kryptering af filer og historik #n4s
        2023-02-08T09:31     #ide få krypteret historik således at både journal og historik fil er krypteret og først åbnes op når man åbner den #n4s #sikkerhed #stuff                                                                                                                                                                    2023-02-13T09:09     evt. kunne hver journal være sit eget #luksfs filsystem der låses op, - men kan man bruge fingeraftryk eller andet ?
2023-10-20T02:46 indtil videre må det være nok hvis harddisken er krypteret med luks


# Regnskab - forbinde nutid med fremtid #n4s
        2023-04-25T06:38     #ide hvordan laver vi en bedre og mere præcis overgang mellem fortid/nutid og fremtid end at der er månedsvise poster som bliver skrevet i fremtiden, men aldrig bagud, da det antages at regnskabet er ajour
2023-04-25T07:23     lige nu er det måske et godt kompromis, som kan fungere til nogle typer regnskaber
2023-05-03T09:05 #ide kunne driften på den seneste måned / igangværende måned bliver justeret så den passer til budget , hvis man ikke er helt ajour ?
2023-07-11T15:23 ide sætte startdato for budget - fixed start date
2023-10-20T02:46 har et rullende budgetscript klar, men skal finde en smart måde at autodetecte hvorfra den skal starte, og køre med tidligere tal for fx moms, ikke budgettets tal


# Standard-stuff mappe pakkes ud #n4s
2023-04-24T06:50 ide ny regnskab skal man kunne vælge "stuff" - som loades som en kopi af en helt renset stuff-mappe, hvor der i header og footer er instruktioner til udskiftning af header og footer


# Hledger skift #n4s
2023-04-21T09:58 #ide skift til #hledger da #ledger virker som et dødt projekt, og der er flere fejl vi umiddelbart ikke kan få rettet
2023-04-21T09:59 #ulempe kunne være det er svært at finde programmører der kan rette noget i haskell
2023-05-04T08:09 fordel der er mere funktionalitet og formentlig færre fejl
2023-05-04T08:09 #alternativide skrive min egen ledger
2023-10-20T02:47 lige nu bruger vi begge dele, grundet c++ ledgers mange fejl 

# Man pages #n4s
2023-04-21T09:55 #ide lave manpages

## Man side hledger inspiration - fra markdown
18:02 < sm> hledger/hledger.m4.md, hledger-ui/hledger-ui.m4.md, hledger-web/hledger-web.m4.md, and also somethings in doc/common.m4
18:02 < sm> the other files are generated from those
18:03 < sm> by ./Shake manuals
18:03 < sm> also: hledger/Hledger/Cli/Commands/*.md
18:04 < sm> you asked about hledger specifically, let me try that again: the source files for hledger manual are:
18:04 < sm> hledger/hledger.m4.md, hledger/Hledger/Cli/Commands/*.md, doc/common.m4

# Tmux window name - symbols #n4s
2023-04-21T09:55 #observation man løber hurtigt tør for plads med lange tagnames
2023-04-21T09:55 #ide kunne man putte unicode symboler som vinduenavne så der er plads til mange åbne aktiviteter ?
2023-04-21T09:55 #ide en anden mere simpel løsning kunne være at croppe til 3-5 tegn max
2023-05-04T08:09 har croppet til 4 tegn, kunne være en god løsning kombineret med #findwindow

# TUI Bibliotek test #n4s
2023-04-19T11:46 https://github.com/VladimirMarkelov/clui bør teste dette tuilib - inspireret af #turbovision

# Ubalance ved ændring af transkationer - spørg bruger #n4s
2023-04-19T11:43 #ide opdag ubalance ved transaktion - hvis man ændrer det ene beløb på 2 benet post vil man typisk foretrække at det andet også ændres.
2023-04-19T11:43 hvis der er yderligere ubalance vis en guide
2023-07-11T15:42 #ide kan også være en kontrol i key.php

# Cronjobs #n4s
2023-04-19T11:34 skal have alle cronjobs ind i mit eget system, ikke noget direkte i cron, og ikke nogle scripts i hjemmemappe


# Mappestruktur n4s-export tmp data mv
2023-04-18T15:25 #ide har jeg brug for n4s-eksport og tmp - eller kan jeg nøjes med en af dem, eller ingen? måske bare ligge i data under brugerens mappe /data/users/$(whoami)
2023-07-11T15:42 #ide bør ligge under /data/users/$(whoami) - EVT med et symlink i hjemmemappe

# CSV Import og eksport #n4s
2023-04-18T07:36 #ide vælg CSV fil fra tmp mappe til import i stedet for at copypaste
2023-04-18T07:37 #ide lad os også kunne eksportere filen til et givent navn i tmp mappen
2023-04-25T09:06 #ide ved csv import lav en transaktion baseret på csv linie uid for hver transaktion - når den findes, så er den allerede lavet og skal ikke laves igen - for at uid er så præcist som muligt er det vigtigt med så mange kolonner som muligt - risiko kunne være hvis der er to poster samme dag med samme beløb og samme saldo og samme tekst, så vil det kun blive oprettet en enkelt gang - evt. kunne man tage uid på den foregående transaktion med for at forhindre dette


# Pakke / distribution af system #n4s
2023-04-07T09:36 debian pakke - eller docker eller andet
2023-04-10T19:43 #ide kunne godt være man bare henter en tgz fil som pakker ud i /
2023-04-13T08:51 #outsourcing pakke n4s .tgz/sh
2023-04-17T07:30 #ide jeg bør øve mig på manual installation og forberede en god guide til hvordan man gør det

# Journal print - når kun en overskrift #n4s
2023-04-18T07:00 #observation hvis der kun er en overskrift bliver indholdet printet tomt - #teori der er altid en tom kategori i alle rapporter

# Spørg om man vil køre pandoc #n4s
2023-04-13T10:17 #ide kun køre pandoc hvis bruger bekræfter at der ønskes rapport✔ @ 2023-04-18T07:48 (joo) 
2023-04-18T07:48 #ide til forbedring - spørg via read med et timeout, således at hvis man ikke aktivt beder om udskrift lukker vinduet når man er færdig

# Webinterface #n4s revival
2023-04-18T09:27 #ide genoplivning af webinterface - det er nyttigt
2023-04-06T18:00     #ide simpel webformular til mobil indtastning alternativ til at male mig selv skal gå i auto buffer (#rt 12087)
2023-04-13T08:52 man skal kunne kopiere de seneste linier med et nyt timestamp og evt rette tekst

# VIM TSV #n4s
2023-04-08T23:55 #ide bruge vim-tsv fremfor visidata så man ikke skal lære flere programmer
2023-04-13T08:52 #visidata har noget flere funktioner, vim er nok ikke p.t. et alternativ - men man burde kunne linke til et visidata sheet så det bliver konveret til markdown

# Vim - table mode #n4s
        2022-03-02 10:58     https://github.com/dhruvasagar/vim-table-mode
        2023-03-26T12:07     behovet er stadig aktuelt her et år efter
        2023-04-07T20:53     er begyndt at bruge det, - skal manuelt aktiveres pt - skal have bundet en knap
2023-04-13T08:54 #ide #vimrc start tablemode hvis funktion eksisterer #outsourcing
2023-10-20T02:49 konklusion smart, men nok for svært at bruge for alm. brugere

# Import buffer til journaler #n4s
2023-04-06T10:04 #ide importbuffer til hver fil - når man åbner filen skal den vise tydeligt hvis der er en importbuffer, så man kan indlæse den hvor man har lyst - evt. previewe den
2023-04-06T10:05 det burde kunne løse problemet med at en bruger låser filen, således at programmer ikke kan skrive i journaler. Programmer vil fortsat ikke kunne skrive direkte i journaler, men i en buffer fil som indsættes manuelt af operatøren
2023-04-13T08:55 buffer filer kan være en skjult fil med et uid
2023-05-03T20:50 lige nu bliver bufferfilerne kaldt $tagname.scrabble - de er ikke skjulte

# Markdown query tool #n4s
2023-04-01T15:58 #ide ved pandoc udskrift lad bruger vælge hvilke sektioner der skal med (fzf --multi) - lad gerne Logins, Stamdata etc være off by default
2023-04-05T09:32 #ide #markdownquery [fil] --include//--exclude [tags] eller --tui for visual selection via #fzf
2023-04-05T09:33 funktionalitet: tag markdown fil - vis kun de sektioner man ønsker at vælge - eller fravælg dem man vælger fra
2023-04-05T17:28 har lavet det, og har også publiceret den som gist
2023-04-05T17:31 ønsket opførsel marker alle sektioner som default, lad mig afmarkere dem jeg ikke ønsker med
2023-04-05T17:31 15:31 < > anyone knows fzf and how i can execute the select-all command on startup to select all the items in --multi?
2023-04-05T17:32 #ide default negativ query på Stamdata etc✔ @ 2023-04-05T17:37 (joo) virker faktisk fint nok
2023-04-05T18:18 #ide tilføj filtrering på subtabs også hvis muligt - kræver nok en del
2023-04-13T08:55 virker fint, skal have sat til select-all fra start #outsourcing
2023-10-20T02:49 #ide dele mkd.bash som en #gist

# Vim 9 Virtual text #n4s
2023-04-05T08:20 #ide bruge virtual text funktionen til at hente og vise data fra underliggende hashtags
2023-04-05T08:21 relevante showcase videoer i denne tråd https://github.com/vim/vim/issues/7553
2023-04-13T08:56 #outsourcing

# Journal kronologisk historik underligt output #n4s
2023-04-03T16:15 output er ihvettfald ikke fra dagen, men fra fortiden #n4s #vitouch #stuff
2023-04-03T19:32 nu er det lidt mindre underligt, har fikset parseren lidt
2023-04-13T08:56 er disablet indtil videre - skal skrives om til markdowntables

# There is no checkmark (U+2714) in font [lmroman10-regular]:mapping=tex-text;! #n4s
2023-04-03T10:53 #pandoc problem skal have en font der understøtter U+2714 #spørgirc✔ @ 2023-04-03T14:06 (joo) 
2023-04-03T14:06 <joo> is there a good font with good utf8 support ? that is available on most computers or if you have installed pandoc - not sure if pandoc  includes fonts ? - afventer respons @ #irc
2023-04-13T08:58 spurgt igen, tror ikke jeg fik noget respons sidst✔ @ 2023-04-13T09:01 (joo) det er der ikke
2023-10-20T02:49 har staidg ikke nogen checkmarks, men i stedet et @

# Søgning i journaler #n4s
2023-04-01T21:23 søgning i journaler - F1 - c - M - oprettet - virker - plads til forbedring
2023-04-13T08:58 også bundet til F6
2023-10-20T02:50 pt ude af drift #fixme

# Vitouch multi pdf rapporter overskrivning #n4s
2023-04-01T12:35 #observation en rapport overskrevet med indhold fra anden rapport, - kræver unikke tmp filnavne #vitouch #n4s
2023-04-13T08:59 tror det er fikset
2023-04-26T10:17 hver process bør bruge unikke filnavne

# Main screen #n4s
2023-04-03T16:32 #ide låst mainscreen #tmux skal være main menu på hver vindue nr. 1 #n4s #ncurses #mainmenu
2023-04-08T10:37 #ide er der en trigger når der kun er et vindue tilbage så man kan starte et nyt ?
2023-04-11T10:13 spurgt på irc om der er en trigger til tmux sidste vindue, afventer respons

# Vend fortegn #n4s
2023-04-14T08:50 #ide vend fortegn på poster - først og fremmest ved import af csv - men også vend fortegn funktion iøvrigt

# Ledger filer konverteres til json #n4s
2023-04-10T19:46 #ide alle ledger filer skal konverteres til json

# Kontrol af regnskabsår primoposter - om den har ændret sig #n4s
        2023-02-15T11:02     #ide #n4s automatisk kontrol af primoposter 
2023-10-20T02:50 umiddelbart irrelevant med den automatiske årsafslutningskørsel
2023-10-22T19:38 lige nu dannes primoposter automatisk så er ikke relevant 

# Hastighed n4s
        2023-02-16T16:06     #observation #n4s alvorlige hastighedsproblemer - kan først og fremmest testes på olsensrevision2023-03-06T08:06     både cas og  observerede særligt hastighedsproblemer på texas samt olsensrevision2023-03-15T17:49     lavede det så hver process laver en fork og arbejder med den tidligere fremsendte fil2023-03-16T09:37     ser ud til at have hjulpet, er ret hurtigt nu 
2023-05-03T20:50 #fork i key.php ledger fungerer ikke optimalt, men er vidst hurtig nok - hvordan kan vi genskrive noget i terminalen ?
2023-10-22T19:39 #ide få genimplementeret en fork process så den kan danne poster i baggrunden

# Reconcile alternativ n4s
        2023-03-19T16:57     #ide #n4s kunne man putte periode på hver post i en afstemning af a-skat f.eks. A-skat betaling for juli som betales i august sættes til Passiver:A-skat:Juli
2023-05-07T17:51 #confirmed det er vidst en god ide til hvordan man kan afstemme, denne ide bør også videreføres til moms som bør konteres i kvartaler f.eks. Passiver:Moms:2022H2:Salgsmoms - dette vil dog kræve at n4s kender brugerens momsperiode (måned, kvartal, halvår)

# Sætte LEDGER_WIDTH variabler baseret på hvad man har #n4s
        2023-03-18T19:45     #ide #n4s hvis man kun har en lille pane, så skal den ikke lave lang PAYEE & ACCOUNT, men det må den godt hvis der er god plads

# n4s Centralisering postgresql
        2023-03-16T23:00     #ide decentralisering ved at kører lokalt. forbindelse via postgresql server (#rt 11313) 2023-03-19T16:54    #ide kunne man få alexandr til at give sit perspektiv på løsningen om den er god ? Automatisk allokering Olsens Revision / 0lsen                      2023-03-21T13:10     #eftertanke tror jeg lige prøver #resilio og evaluerer min strategi

# Ledger output som split-windows #ledger #n4s
        2023-03-17T15:00     2023-03-17T15:00     Ide loutpit i split window frem for popup (#rt 11353)
        2023-03-20T08:40     er implementeret, man kan endda skifte mellem modes på alt-q

# Min egen ledger med serienumre på baggrund af ledgeren, kunne gerne være nummerisk #n4s
        2023-03-21T08:38     #ide #n4s min egen ledger med serienumre på baggrund af ledgeren, kunne gerne være nummerisk
        2023-03-21T09:23     man kunne trække alle ledger transaktioner ud og beregne deres md5sum som unik identifier, og give den et id i en blockchain eller lign
        2023-03-21T13:08     det mest pålidelige udtræk er i CSV
        2023-03-30T11:00     kunne man trække uid baseret på CSV og koble den på blockchain på md5 - hvis transaktionen bliver ændret vil dens hash ikke længere passe og så skal den igen uploades i blockchainen, evt erstatte den gamle

# Kontrol af store poster #ledger #n4s
        2023-03-22T07:54     #ide alle store poster udtages til kontrol - skal hakkes af, - kan gøres med en genvejstast der sætter et tjekmark - kunne evt. gælde på alle regnskaber så der scannes for ikke godkendte store poster
2023-10-20T02:51 har lavet review script, virker fint

# Eksport af data til andre regnskabssystemer #n4s
2023-03-22T08:44 #ide ved en simpel mapning til standardkontoplan burde man nemt kunne spytte alle poster ud for en periode til e-conomic, uniconta, dinero etc
2023-10-22T19:33 lige nu kan man eksportere og manuelt mappe konti

# Justering af balance - ncurses #n4s
        2023-03-22T15:13     #ide #n4s justering af balance - oprettes som ny json-transaktion - hver række i balancen har tre kolonner: Bogført værdi, Justering, Ny saldo. Man skal kunne taste i Justering samt ny saldo, som på #tastselverhverv ved momsreguleringer. Ny transaktion åbnes efterfølgende i vi
        2023-03-31T21:04     #ide jeg bør få styr på det med php & ncurses
2023-04-01T20:54 #igang med ncurses.php
2023-04-08T13:50 har rodet lidt med det - aktuel linie er nu fed 

# Årsafslutning uoverensstemmelser #n4s
2023-04-01T15:54 #problematik der ændres i tidligere årsregnskaber, men den nye åbning bliver ikke rettet #løsningsmulighed det skal ikke være mulige at ændre i regnskaber der er lukkede
2023-10-20T02:51 skulle være løst med det nye system hvor der ikke skal laves årsafslutninger

# Købsordrer #n4s
2023-04-13T09:04 udgifter skal kunne kobles på åbne købsordrer - købsordrer tages automatisk med i budget

# Automatisk kreditorafstemning #n4s
2023-03-29T12:29 #inprogress kreddeb.bash / kreddeb.php
2023-04-13T09:04 #procedure gennemgå alle poster på fejlkonto, se om der er nogle #åbneposter på debitor / kreditor
2023-05-01T18:12 #ide skrive på skærmen at nu hvor banken er importeret er det måske en ide at gennemgå kreditorer / debitorer - evt vis udskrift / saldo herefter

# Automatisk finding af dubletter #n4s
2023-04-05T19:44 #ide #find duplicate entries- when amount and date is the same
2023-04-13T09:05 #outsourcing on entry when amount and date exists already (or within approximation, ask for details), warn the user, and let the user instead edit the existing transaction - or add new

# Difftable markdown - journal historik #n4s
2023-04-05T19:47 difftable har jeg slået fra i #dagbog - ser ikke godt ud - kan vi få det til at fungere - en kronologisk visning af aktiviteter sat op i #markdowntabel

# Timer.bash virker ikke mere - tager ikke input #n4s
2023-04-03T18:54 timer.bash tager ikke input mere til nye timere, virker ikke
2023-04-03T18:55 #ide omskriv input del fra read fra stdin til tmux input, eksempel : tmux command-prompt -p "Indtast timer" "new-window vi '%1'"
2023-10-20T02:52 #fixme

# Transplant vim #stuff #n4s
2023-05-04T21:02 #outsourcing #vimscript select buffer with V, press hotkey, it should ask what file to transplant it into - put it into the markdown section # Transplants (if not exists, create in bottom) #scrabble
2023-10-20T02:52 har fået implementeret transplant, men den skal lige have historikken med #fixme

# Yearend #n4s
2023-07-05T10:19 yearend.bash skal nulstilles kapitalkonti samt momskonti - senere evt. anlægskonti #scrabble

# N4s Lokale ressourcer
2023-07-07T16:58 #ide køre kommandoer på klient fx browser , filmanager osv via #sshtunnel #scrabble

# Rentetilskrivning #n4s
2023-05-07T18:23 interest.bash og interest.php som udkast til manuel rentebregning

# Dokumentere funktioner i markdown #n4s
2023-05-07T17:41 #ide få flyttet de manual sider jeg har lavet allerede til en kasse ved navn n4s
2023-10-20T02:54 #ide dokumentationen til systemet skal være indbygget som #stuff wiki

# Ved registrering i journaler medtages nye filer #n4s
        2023-04-03T11:10     #ide tag nye filer fra kundens dropbox med i journalen
        2023-04-28T09:12     #ide man kunne synkronisere med resilio så serveren også har adgang til dropbox
        2023-07-21T21:34     #godide
2023-10-04T10:37 lige nu tages nye filer med i #drp #dagbog

# Ændring i 998 script #n4s
2023-07-19T19:06     hvis jeg får problemer #osb #vigtigt har udkommentere noend i 998_ script
2023-07-28T18:02 slettes om en måneds tid hvis jeg ikke har haf nogle problemer
2023-10-04T10:37 historie 997 tog alle posteringer og dannede årsafslutningsposter on the fly
2023-10-04T10:37 historie 998 afhænger af årsafslutning.bash og genererer enkeltposteringer
2023-10-04T10:37 historie 999 er som 998, bortset fra den kun laver en resultatposteringer pr. rapporteringsperiode
2023-10-04T10:37 ændret til 999_Periodresult

# Vim menu #n4s
2023-09-26T11:45 #ide gøre n4s mere brugervenligt med vim menuer

# Transplant vim historik #n4s
2023-09-26T11:44 tilføj historik til både source og destinationsfil når man transplanterer tekst

# Topic modelling #n4s
2023-09-19T21:00 Topic modelling (#rt 16395) #scrabble muligvis nyttigt begreb til samkøring af data

# Textual #n4s
2023-10-06T22:49 #ide pythonlib textual #tui
2023-10-10T13:01 har testet det, virker ikke alt for godt, lettere ustabilt, men virkelig flot

# Gum #n4s
2023-10-06T22:56 #ide textui #gum

# Stuff #n4s - tillad ikke sletning
2023-07-27T00:06 ide ikke tillade sletning men gerne transponering - standard slette knapper bindes til transponering
2023-10-20T02:55 kræver transponering får historik først
2023-10-20T02:56 eftersom markdown historikken er pæn nu og på sektionsniveau, er det måske ikke nødvendigt

# N4s - timelog entries
2023-07-25T11:04 #ide transaktioner oprettes baseret på timelog entries og deres værdier baseret på regler - når de først er oprettet med deres uid kan de ændres, de bliver ikke opdateret fra den linie igen #scrabble

# n4s - transaktions historik i separat fil
2023-07-18T12:00 Ide trans history separate file (#rt 15081) #scrabble

# Budget i n4s - gammel budgetsystem
Der kan oprettes automatiske periodiske transaktioner på kontoniveau
Periodiske transaktioner fremskrives automatisk (indtil videre 24 måneder frem fra nu, men kan ændres)
Derfor er det p.t. en forudsætning for maksimalt udbytte at der er tale om et rullende regnskab med forholdsvis stabil drift.

## Hvordan laves et budget
> budget

Herefter vil kontoplanen blive vist - og hver måneds budget, - der er også en kolonne til alle måneder
Vælg konto, vælg herefter periode og indtast værdi for at ændre. Afbryd med Ctrl-C

## Udvikling TODO
### Validering af tal
2023-05-04T21:14 #ide tal skal valideres som floatval for at sikre imod problemer i ledger-output

### Vis total i bunden
2023-05-04T21:12 #ide vis totaler i bunden

### Tillad modkonto
2023-05-04T21:12 #ide tillad modkonto

### Tillad specifikation på detaljegrad på kontoniveau der automatisk summer op
2023-05-04T21:12 #ide specifikation af budgettal kan være en TSV fil
2023-05-10T11:56 lavet udkast som er næsten funktionelt

### Filtre skjule/vise tomme konti
2023-05-04T21:13 #ide "Skjul/vis Tomme" skal skjule/vise de tomme rækker


### Manuelle transaktioner
2023-05-04T21:15 #ide optimalt vil manuelle transaktioner henvise til et underliggende #skyggeregnskab - men #skyggeregnskab er ikke implementeret endnu, og vurderes ret komplekst
2023-05-04T21:15 #ide manuel ledger-fil som kan redigeres via budget (Vælg: Manuel) - skal åbne i vi
2023-05-04T21:16 #ide oprettelse af transaktioner guidet af fzf, som så bliver appendet til ledger-fil
# N4S Windows version udkast
Subject: Software Distribution Project - Requirements and Collaboration

# Regarding Windows Version of n4s

I have developed an ERP/Accounting system for my local market, and it is currently running on GNU/Linux. 
To expand its reach to the Windows user base, I am looking for assistance in creating a standalone application with an exe-installer that incorporates the entire Linux environment. 
Below are the requirements for the project:

1. Virtual Machine Conversion:
   - Convert the Linux Virtual Machine (VM) into a standalone application that runs on Windows.
   - Ensure that the converted application includes all the necessary components for it to function seamlessly, such as working network, graphics, etc.

2. User-Friendly Installer:
   - Create an exe-installer that simplifies the installation process for Windows users.
   - The installer should automatically:
      a. Create a folder "C:\n4s" to house the necessary files.
      b. Set up "C:\n4s" as a shared folder accessible to the virtual appliance.
      c. Copy the VM, its configuration, and the required runtime files into the "C:\n4s" folder.
      d. Create a desktop shortcut to launch the virtual machine.

3. Application Features:
   - The application and shortcut must have a custom icon, making it visually distinct and easily recognizable.
   - Implement an autosave feature that regularly saves user data within the VM.
   - Include an idle suspension mechanism, which automatically suspends the application if left idle for more than an hour or upon system shutdown.

Optional Solutions:
   - Consider various deployment options, such as Docker, Windows Linux Subsystem, and others, to provide flexibility to users with different preferences.
Please feel free to reach out to me at your earliest convenience. You can contact me directly at +45 25864573 or add me on Whatsapp.

Thank you for considering this opportunity, and I look forward to the possibility of working together on this exciting project.

# n4s Transaktion historik
2023-07-18T12:00 Ide trans history separate file (#rt 15081) #scrabble

# Sletning af journallinier forbydes #n4s
2023-07-25T17:00     Ide fjern slet linje kun transponer til history eller evt devnull (#rt 15265)

