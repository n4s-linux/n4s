# Rapportering n4s - naturlige fortegn
	2023-10-18T11:26 joo	#ide naturlige fortegn i rapportering - dvs indtægter er postive, udgifter er negative, egenkapital skal også vendes - aktiver står som de skal

# N4S tmp outputmappe
	2023-10-13T10:10 joo	lav ny mappe til output fremfor tmp som har mange tmpfiler

# N4s periodisering
	2023-10-08T20:09 joo	observation p-start og p-end har ingen effekt i demoregnskab

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

# N4S database problem
	2023-03-09T20:13 joo	#ide kunne n4s være en database - postgresql som understøtter json - når den bro er bygget er vi decentrale nok #n4s-storage
	2023-03-17T11:00 joo	#ide et #quickfix kunne være #unison sync til en central server - eller #resilio

# Allerede åbne journaler n4s
	2023-03-09T20:12 joo	#ide #stuff #n4s allerede åbne journaler skal søge efter en fane ved det navn, om ikke andet i samme session, men evt. på alle sessioner, i stedet for at sige den allerede er åben - foreløbig af samme bruger
	

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

# Ide n4s se kundedata altid
	2022-06-27T08:32	det skal være hurtigt at frembringe stamdata, herunder også cpr numre etc
	2022-08-16T08:29	#ide stamdata skal ligge i separat "database" fra sagerne

# n4s navn Its Just A Collection of Scripts IJACOS - bedre navn end n4s ? eller en kombination - n4s er for provo - så vil folk bare sige we aint buyin anyway, bør være mere neutralt - der er ihvertfald brug for et mere positivt navn - noget playfulcleverness

# Raspberry pi n4s
	2023-05-04T17:26 joo	#raspberry pi med #n4s så folk kan købe en færdig maskine #scrabble

# N4s enheder
	2023-05-04T17:31 joo	#ide #n4s #produkt i form af stationær minipc eller opsætning af vps, - evt også hosting af vps #scrabble

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

# Mappestruktur n4s-export tmp data mv
	2023-04-18T15:25 joo	#ide har jeg brug for n4s-eksport og tmp - eller kan jeg nøjes med en af dem, eller ingen? måske bare ligge i data under brugerens mappe /data/users/$(whoami)
	2023-07-11T15:42 joo	#ide bør ligge under /data/users/$(whoami) - EVT med et symlink i hjemmemappe

# Webinterface #n4s revival
	2023-04-18T09:27 joo	#ide genoplivning af webinterface - det er nyttigt

# Main screen #n4s
	2023-04-03T16:32 joo	#ide låst mainscreen #tmux skal være main menu på hver vindue nr. 1 #n4s #ncurses #mainmenu
	2023-04-08T10:37 joo	#ide er der en trigger når der kun er et vindue tilbage så man kan starte et nyt ?
	2023-04-11T10:13 joo	spurgt på irc om der er en trigger til tmux sidste vindue, afventer respons

# Hastighed n4s
        2023-02-16T16:06 joo    #observation #n4s alvorlige hastighedsproblemer - kan først og fremmest testes på olsensrevision	2023-03-06T08:06 joo    både cas og joo observerede særligt hastighedsproblemer på texas samt olsensrevision	2023-03-15T17:49 joo    lavede det så hver process laver en fork og arbejder med den tidligere fremsendte fil	2023-03-16T09:37 joo    ser ud til at have hjulpet, er ret hurtigt nu 
	2023-05-03T20:50 joo	#fork i key.php ledger fungerer ikke optimalt, men er vidst hurtig nok - hvordan kan vi genskrive noget i terminalen ?

# Reconcile alternativ n4s
        2023-03-19T16:57 joo    #ide #n4s kunne man putte periode på hver post i en afstemning af a-skat f.eks. A-skat betaling for juli som betales i august sættes til Passiver:A-skat:Juli
	2023-05-07T17:51 joo	#confirmed det er vidst en god ide til hvordan man kan afstemme, denne ide bør også videreføres til moms som bør konteres i kvartaler f.eks. Passiver:Moms:2022H2:Salgsmoms - dette vil dog kræve at n4s kender brugerens momsperiode (måned, kvartal, halvår)

# n4s Centralisering postgresql
        2023-03-16T23:00 joo    #ide decentralisering ved at kører lokalt. forbindelse via postgresql server (#rt 11313) 2023-03-19T16:54 joo   #ide kunne man få alexandr til at give sit perspektiv på løsningen om den er god ? Automatisk allokering Olsens Revision / 0lsen                      2023-03-21T13:10 joo    #eftertanke tror jeg lige prøver #resilio og evaluerer min strategi

# n4s Automatisk allokering på vores regnskaber
        2023-03-20T08:48 joo    #ide #n4s automatisk allokering af Olsens Revision ApS / 0lsen ApS omkostninger - herunder Andrea
        2023-03-20T08:54 joo    #ide kunne det laves som et modul

