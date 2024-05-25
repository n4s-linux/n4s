# About this changelog
	This changelog is automatically generated on 2024-05-25 10:00.

## 2024-05-25

## 2024-05-24

## 2024-05-23
 * New alias for export accounts, and a shortcut... !
 * More date standard periods
 * Add sortinng option to fzf
 * Chose to report for just period or with carryover account values
 * Fault tolerance in mkentry
 * Some changelog stuff
 * Vim tooltips
 * Remove blank pages runs quietly
 * Fault tolerance in skat export
 * Improved transactions editor
 * Add errror account and tax account to the standard chart of accounts
 * Sort accounts in register account chooser

## 2024-05-22
 * 💡 New Feature: Easier transaction edit and add new transaction
 * Make it callable
 * Logging to own users logfiles
 * Print filename of corrupted file
 * Fixed dateformatting problem in history, added uid to new transactions
 * Small cosmetic change
 * Exact matches in fzf viewer
 * New Transactions gets a UID and is NOT locked
 * 🐛 Bugfix: Error in date format
 * Bugfix: Fix date format
 * Bugfix datetime format
 * Bugfix datetime format
 * Automatic questionarire improved with uids to match answers with
 * Add a header
 * Small change in changelog generator

## 2024-05-21
 * Gitlog markdown

## 2024-05-20
 * Opening calc more reserved rolling accounts
 * Fzf write to file, because some data is too big to pipe
 * Some html/pdf export improvements
 * 🐛 Fault tolerance some key numbers will be missing in some accounts
 * Small lookup account improvement and translation
 * 💡 New Feature: View all transactions when entering a transaction that is an opening
 * Fault tolerance
 * Support for some old transactions that doesnt have booking ids
 * A couple new accounts, and a dupliate account number fix
 * Fejlkonto export improvement and partial translation
 * Skat export improvements
 * A demo video
 * Duplicaate line fix
 * Linkfix
 * Readme text about video demo
 * Readme text about video demo

## 2024-05-19
 * Dont book anything outside the scope of the current period. Also check if no tpath
 * Some terminal cleanup and tax report with mapping included in standard export

## 2024-05-18
 * Take account as argument and add balance to the accounts displayed
 * Nicer balance, and display relevant shortcuts on sr
 * 💡New Feature: Nicer balance with integration to register
 * Typo in error message
 * Copyed and reersed transactions should not be locked or have a number
 * 💡 New Feature: Guide that allows to book specific or all transactions, and verify the book later on to see if any of the booked transactions has changed #blockchain
 * Improved booking function

## 2024-05-17
 * Adding metatdata to opening transactions
 * System should support dates before 1970-01-01 (unix time)
 * Disallow edit of locked files in vim
 * Improved fejlkonto email questionaire
 * Datepick supports dates before 1970
 * Add metadata to transactions that comes from a function like VAT
 * Remove nonprintable from fzf prompts
 * ⇒ symbol as delimiter for shortaccs
 * Color codes for search results - based on booked or not
 * Why not allow ancient transactions for ancient accounts?
 * Small improvements to moneyflow report
 * Changes to how locking transactions works, also implement the ledgerhack for correct csv export
 * Fault tolerance
 * Files that are locked should be opened as readonly, check using this func
 * Autospørg fejlkotno questionarire css file
 * 💡New Feature: More efficent account entry browser - with edit, copy, reverse function

## 2024-05-14
 * Color output when doing entries
 * Including credit and minor bugfix uninitialized variable
 * Two new accounts
 * Updated changelog

## 2024-05-13
 * Further shortening window names, for more windows on the screen
 * 🐛 Fault tolerance and reporting for invalid date formats in ledger files

## 2024-05-12
 * 💡 New Feature: Mindmap
 * Menu entry for mindmap
 * Mindmap start func
 * 💡 New Feature: Automated questionaire based on fejlkonto to customers
 * Prettier accounts in moneyflow report
 * New features and bugfixes in statusbar
 * Vim statusline fix - it was ugly
 * 💡 New feature: Scheduled transactions
 * Textbased UI draft
 * Symlink to mail script
 * New session manager stuff
 * automatic changelog stuff
 * changelof stuff
 * Readme stuff about changelog

## 2024-05-11
 * Make result transactions "unrelated"
 * 💡 New Feature: Moneyflow report
 * Balance adjustment on primo date
 * Account loop includes empty accounts
 * New question for fejlkonto available - Hvad er det
 * Simplification of readme
 * Translation - keeping this in danish
 * Reversing amounts for nautal amount "direction"
 * Changed my mind - empty accounts not to be included by default

## 2024-05-09
 * 💡 New Feature - Export to danish Accounting system Letregnskab that expects format like E-conomic
 * Datepicker being used in other scripts, with different parameters
 * Work in progress, small step in interpreting the markdown files from json
 * Some stats bar stuff, and translation of colorizer alias
 * Show shortcut for help menu
 * Translation of balance painter alias

## 2024-05-08
 * Help function for custom vim shortcutz
 * When searching, journals dont show rejected lines
 * Add account adjusment function to the bookkeeping menu

## 2024-05-07
 * 💡 New Feature: Adjust the balance of an account to amount
 * 💡 Minor improvement of formating and calculation of bank reconciliations
 * 💡 Bugfix: Calcopening properly now respects capital reserves
 * 🐛 Fixed Fault Tolerance in Date picker if none is selected, abort mission

## 2024-05-06
 * 💡 New Feature: Fast reconciliation of any accounts vs CSV
 * About bank reconciliation
 * Fedora based OVA

## 2024-05-05
 * 💡 New feature: Support import, search and display for currency amounts
 * 🐛 Bugfix: New private accounts symlink problem
 * 🐛 Bugfix Fault tolerance quick entry + unescaped unicode allows or searching non ascii based transactions like danish letters æ,ø,å, but also unicode in general
 * 🐛 Bugfix attempt related to vitouch on fedora, make sure running same version of echo on all systems
 * 💡Sort results by date column when displaying matching journals
 * Make vitouch more simple
 * About currency
 * Readme = README journal
 * 🐛 Bugfix Prettyname included on top to ensure always available - htmlreport
 * 🐛 Bugfix HTML report tmp file hardcode fix
 * Add info about export done
 * Bank fee column for easier revolut bankfile import
 * Webhosting added to chart of accounts
 * Cmatrix is displayed while generating reports, thus a requirement
 * Better opening respecting equity accounts that are ongoing
 * Skip invalid json files with only 1 trans

## 2024-05-01
 * Bugfix echo -e to escape newlines correctly on fedora
 * Translation to english
 * Sixel stuff, statusline improvements, faultsafe sr
 * Faulty logic rollback
 * statusline stuff
 * Show version in ps1 if no tpath
 * tmux stuff
 * stuff
 * Nonumber for n00bs
 * Translation
 * Ekstra file vitouch
 * pdf alias
 * upd8 function
 * Escapeing quotes
 * Improved logger
 * Improved logger
 * unescaping

## 2024-04-30
 * More datepicker options
 * Periodeafgrænsning - htmlreport for fejlkonto
 * Unicode bugfix
 * Quicktrans - no ask period
 * Enter time in new window

## 2024-04-27
 * Custom statuslinestuff + html generates fejlkonto list
 * Minor bugfix duplicate transaction program
 * Script to export question list to customers
 * Very simple progressbar for html report

## 2024-04-26
 * A little readme change
 * A little readme change

## 2024-04-25
 * Translation + bugfix tty instead of stdin to prevent pipe problems
 * Warning about omitted future postings - small clenaup to make the loop fit one page
 * Reset equity accounts on new accounting period, except reserves, capital and mellemregning
 * Pivots require colorless data input
 * Quick Transaction shortcut
 * Quick duplicate existing entry
 * Adding spark
 * Minor bugfix in transfer equity
 * Minor bugfix dateformat

## 2024-04-24
 * Symbols i names

## 2024-04-21
 * tmux2k
 * tmux-fzf
 * Journalviewer fix C-o
 * Lastsym mindre fix, - fjerner color escape codes til renteberegninger
 * Barsel tilføjes til kontoplan
 * Custom stats and symbols
 * tmux-fzf & tmux2k theme
 * About utf8
 * start of making it 256 colors
 * No colors in the account picker
 * Ledger hack to print reference
 * Trying to revive the webinterface - guess some people like to use the web....
 * Logic - dont mess with zero transactions
 * Typo
 * A little fault tolerance
 * Bugfix empty description handling + symbol stuff and colorizer disable support
 * Colorizer disabled for htmlreport and a small bugfix
 * Handling of last symbols
 * Getting little help trying to revive the webinterface for php8

## 2024-04-11
 * diverse unicode stuff + sr fejlrettelse aliases es fil mangler i nye regnskaber
 * Flytning af lastsym
 * Unicode stuff

## 2024-04-04
 * English readme
 * Minor adjustments readme
 * English translation
 * Linkfixes
 * Linkfixes
 * Linkfixes
 * Linkfixes
 * Translation fix
 * Translation to english
 * English translation
 * Translation
 * Fault safe stuff
 * Translation

## 2024-03-30
 * Simplificeret ctrl-o visining af tidligere poster med tekst
 * Video med kommentarer til reports
 * Ansi colors simplification and renaming
 * Inline notes
 * Logic changed files only show filename, not fullpath

## 2024-03-29
 * Allow users to open a temporary Dropbox Wormhole
 * Inline comments implementeret i colorizer

## 2024-03-28
 * Colorizer script
 * Mindre rettelse csv import, colorizer script references
 * Forbedret color picker
 * Videoguide til farvelægning
 * Balance and account sheet painter added to menu
 * Hooker newl up på colorizer
 * Mulighed for at undlade triming af fzf in/output
 * Lidt fault safe stuff hvis der ikke er tages throwes ej fejl
 * Lidt fejlhåndtering på massupdater
 * Lookup account skal også understøtte farver
 * Smårettelser til bankimport script

## 2024-03-26
 * Alias mapning fixet så den ikke spørger efter samme alias flere gange i samme kørsel

## 2024-03-25
 * kør logic efter csv import
 * Forbedret sessionsskifter
 * Tillad bruger at skrive mellemrum i regelnavne - erstattes med underscore
 * Disable popup menu i nye sessioner
 * Lidt error checking i html rapporter for at undgå fejl i output

## 2024-03-23
 * Indsæt uid på Alt-F1

## 2024-03-20
 * Unison sync script
 * Ignorearchives possible cause of deltion problem

## 2024-03-11
 * En lille vim guide i bunden eller i toppen af vim vinduet
 * Lad bruger vælge sti ved oprettelse af nye regnskaber
 * Comments tages også med til comments-report
 * Behøver ikke lyd på monitorering
 * Udkast til comments rapport

## 2024-03-06
 * Viser nu også forslag
 * Mindre oversættelse

## 2024-03-02
 * Forslag kobles automatisk på transaktionen

## 2024-02-29
 * Altid dags dato for tidsregistreringer internt #quickfix

## 2024-02-28
 * Askdesc gemmer forslag i hash, ikke kontinavne

## 2024-02-27
 * Kontohash rettet til at indeholde alle konti på posteringen

## 2024-02-26
 * Data entry - nyttige forslag til hyppige valg - og forøgelse af seneste bilagsnr som forslag

## 2024-02-23
 * Xauth fejl ignoreres

## 2024-02-20
 * Bugfix: aliases virker igen i den nye ledger wrapper

## 2024-02-14
 * fzf-baseret menu, ikke optimal
 * Mulighed for hurtig ompriortering af sessioner
 * Sessioner vises i navnerækkefølge for mere optimal stackbased arbejde
 * Script til hurtig op eller nedpriortering af sessioner

## 2024-02-12
 * Notenavne printes uden prefix
 * Ny konto virkede ikke #bugfix
 * fjernet debug info
 * Mindre fejlrettelse i søgefunktion
 * Tillad at man taster komma i beløb, omforfater selv til systemformat

## 2024-02-09
 * Åbningssymbol ændret
 * Påbegyndt newl omskrivning af søgefunktion med fra key.php med diverse forbedringer
 * Ved kontoopslag tages konti fra alle perioer med, ikke kun dem der fremkommer for perioden
 * Fjerne outdated funktion

## 2024-01-21
 * TODO: Fix this temporary hotfix - couldnt lookup the global var for some reason
 * Åbningsposter laves seperat for bedre sporbarhed af sammenhængende poster da de ellers bliver koblet sammen - kobles nu blot sammen med overført resultat
 * tla sortering udskilles i seperat funktion for genbrug - tla = top level account
 * 2 nye aliases: bookledger & nmenu
 * Brug af unicode symboler til at vise transaktionstyper - kladde eller bogført
 * Tilføjet ny regnskabslæser beta i menu
 * Script til hurtig tmux session
 * Udkast til ny fzf-baseret regnskabsmenu med indtil videre gode og nyttige views
 * Mindre Fejlrettelse
 * lookupaccount skal ikke vises aliases, kun rigtige konti indeholdende :

## 2024-01-20
 * Selvom der er blockchain hashtjek af transaktioner skal man stadig ikke kunne redigere bogførte transaktioner - låses nu
 * Anderledes visning af readonly filer
 * Forbedret bogføring visning af antallet af bogførte poster mv
 * Sortering genvejstast ændret, åbne poster rettet typo, bogfør kladdepostering ny entry

## 2024-01-16
 * Yacc bruges til at kompilere tmux

## 2024-01-10
 * Mindre fejlrettelse - CSV Import - forslagtil kontering
 * Kontomapning - vis hvilken konto det handler om i whiptail dialog
 * Mindre rettelse header i double quotes
 * Vis i rapportering om der er tale om budgettterede tal
 * Duplikat fil ændres til link
 * Forsøger at revive webinterface

## 2024-01-08
 * Mindre rettelse til lookup account
 * Default er i år - datepicker
 * Session skifter - opretter ny session hvis ikke eksisterer

## 2024-01-06
 * Periodens resultat i egenkapital skal også med i resultatposteringer

## 2024-01-05
 * Standard-export deaktivret midlertidigt da det kræver kontomapning - omskrevet fejl funktion
 * Errorhandling
 * Forsimplet lookup account

## 2024-01-01
 * Dependencies

## 2023-12-30
 * Der blev efterspurgt musesupport genaktivering #såladgåda
 * Forbedret csv indlæsningsflow
 * Header funktion udskilles til genbrug i andre scripts
 * print_array udskilles til separat fil
 * Forbedret csv indlæsning og standardisering af lookup-account
 * Der rundes ned til 2 decimaler i budget
 * filefilter udskilles til genbrug i andre scripts

## 2023-12-29
 * Om software frihed
 * Lidt om fri software
 * Mindre typo rettet
 * Formattering
 * Gøre readme mindre
 * Diverse ændringer i README
 * Diverse readme stuff

## 2023-12-28
 * Diverse forbedringer til det rullende budget
 * Om fremtidens regnskab
 * System update video

## 2023-12-27
 * Understøtter budgetfil ved automatiske rullende budgetter
 * Rullende budget alias
 * Nyt menupunkt: forecasting / rullende budget
 * Rullende budget med gennemsnitstal kan aktiveres fra regnskabsmenu
 * Om forecasting / rullende budget
 * Lidt formattering

## 2023-12-26
 * Funktioner udskilles til global kørsel
 * Forside + diverse andre ting til skat export
 * Standardkontoplan export
 * Tilføjet flere konti fra Erhvervsstyrelsens standardkontoplan
 * Om momskoder og mapning
 * Om bflov
 * Musesupport fjernes, er ikke hjælpsomt

## 2023-12-25
 * Mulighed for kørsel uden periodisering af driftsposter
 * Ja til alle forslag implementeret samt lidt failsafe stuff
 * Mulighed for eksport af saldobalance der følger erhvervsstyrelsens standardkontoplan - obs tilføjet 2 klasse a konti til kontoplanen mens vi venter på erhvervsstyrelsen
 * Nu med kontoudtog - trænger til lidt finpudsning
 * Forbedret skat export - numerisk kontoplan export 🎄
 * Gettags udskilles til global funktion
 * iy & iv erstattes med iy-eu, iy-abr samt iv-eu, iv-abr - udenlandske momskoder
 * Kun numeriske konti tages med, nulkontrol burde afsløre evt. differencer

## 2023-12-23
 * Embeddede asciinemavideoer for langsomgt - fjernes
 * Punktopstilling

## 2023-12-22
 * Aliases fil opdateres nu ved nye stillingstagen, kun nødvendigt 1 gang pr kørsel - samt whiptail warning når matchinb begynder
 * Tekster trimmes før sammenligning, samt der gives advis til stderr om poster der matcher over 50%

## 2023-12-21
 * Restsaldo vises for hver afskrivning i posteringstekst
 * Flere konti kan nu passes til pivot, før var det kun 1
 * CSV import tekst sanitizer forbedret
 * Automatisk forslag af konto udfra posteringstekst
 * Hjælpemenu til vim for nybegyndere
 * Periodresult fjernes fra nyt regnskab, da det er bygget ind i newl. CSV funktion spørger om man ønsker forslag efterfølgende
 * Menufarver vim fikset - samt tilføjet tmux menu til vim - hjælpemenu til nybegyndere
 * Efter csv import spørg både efter udligning af åbne poster samt efter forslag til kontering
 * Ved ingen matches, exit loop - mindre fejlrettelse
 * Updatealiases alias til opdatering af kontoaliases
 * guide bruger i oprettelse af manglen kontoaliases

## 2023-12-19
 * Skriver uddata til stderr fromfor stdout
 * Omskrivningsfunktion - anonymisering af konti mv til eksterne interessenter
 * Ændret konto for periodeafgrænsningsposter
 * Fikset problem med flere konti med samme navne i noter, flyttet rewrite funktion
 * Diverse fejldetektion i key.php (som er deprecated)
 * Rewrite funktion implementeret i newl
 * Første udkast til keytables til regnskaber
 * Xml OIOUBL faktura ind
 * fzf search size

## 2023-12-18
 * tags

## 2023-12-07
 * readme stuff
 * img size
 * img size
 * resize image
 * resize image
 * smaller image
 * smaller image
 * Centrering af billede
 * Centrering nyt forsøg github flavored md
 * centrering
 * Centrering
 * Centrering ikke mulig - back to normal
 * markdown syntaxfix
 * Fjerner logo helt
 * Eksempler
 * Vedr eksempler
 * Et linieskift gør det pænere
 * Mindre kosmetisk rettelse - linieskift
 * typo
 * Flyttet lidt rundt på sektionerne
 * Simplificering af readme
 * Mindre kosmetisk rettelse
 * 2 screenshots
 * Screenshots rapport
 * Flere funktioner
 * Vedr blockchain hashes
 * Deprecated
 * Automatisk logning af kommandoer inde i regnskaber på bruger niveau
 * Readme opdateret med logning mv
 * Farvetemaer
 * Om periodisering
 * Noget om renter
 * Tager den globale kontoplan med i opslag nu
 * Logging funktion
 * Udvidet standardkontoplan for virksomheder
 * Mere om hastighed
 * Rette afsnit

## 2023-12-06
 * Fjerner jpgraph virker ikke med php8.2 skifter til js version
 * Åbne poser alias
 * Html rapportering med javascriptbaserede chart.js graphs
 * Understøtter php scripts
 * Bogføringsmenu får en opdatering
 * Chart.js implementering til rapportering
 * New ledger modules
 * Piecharts er pæne, men kan ikke i sin natur vise negative værdier, hvorfor der skiftes til barchart
 * Barcharts data sorteres efter størrelse - tjek for tomme værdier i felter
 * Tillad ikke specialtegn i import, kan ødelægge csv export
 * 2 charts pr side, med indbygget titel
 * CSV import understøtter både dansk format og iso
 * Globalt kontoopslag
 * Rewrite support - nyttigt til anonymisering af visse regnskabskonti til offentlige regnskaber
 * test
 * demo video
 * Eksempler opdateret
 * Mindre rettelse
 * endnu en asciinema
 * Regel video
 * asciinema++
 * typo
 * vt100
 * flyttet tldr til bottom
 * mindre rettelse
 * fjerne debug stuff
 * Tjek om der er en rewrite fil
 * Fjerner git stuff bruges ikke mere

## 2023-12-05
 * Manglende bilagsapport medtager ej systemposteringer
 * Forbedret autoudligning af kreditorer - virker nu også med dublet udgifter/indtægter uden bilag
 * Forbedret autoudligning
 * sessionsnumre starter fra 1, dobbeltklik for at skifte mellem sessioner

## 2023-12-03
 * Kundemappe gemmes så man kan bruge den fra det åbne regnskab - med den begræning at man kun kan arbejde med bilag fra 1 mappe af gangen

## 2023-11-30
 * Tilføjet afskrivninger på anlægsaktiver
 * Gamle rente scripts ubrugelige
 * Kør bogen efter søgning
 * Periodeelementer tilføjes til nye transaktioner ved entry. Mulighed for bilagsupload
 * Bilagshåndtering
 * img2pdf bruges til at konvertere billeder til pdf
 * Renteberegning, og mindre fejlrettelse i perioder på ll/lll
 * Genvej til at skifte pane
 * Renteberegningsscript

## 2023-11-29
 * utf8decode er deprecated
 * Mindre fejlrettelse
 * Tillader tilknytning af fysiske bilag ved dragdrop - kræver at både klient og server har samme mappestruktur fx via dropbox
 * Næste nummer for bilag
 * Udkast til udligning af posteringer mærket med #cirka
 * Hstr gøres som default
 * Mindre fix til vi får føjet tjekmark til listings
 * Composer oprydning dependencies
 * Composer oprydning dependencies

## 2023-11-26
 * Relative numbers off
 * No reason to cache bash scripts now that ledger is fast

## 2023-11-25
 * Addresult = virtuelle transaktioner
 * Lidt oprydning og reverse tunnel kommandoer
 * Periodresult er en del af expand(), Periodisering = virtuelle transaktioner indtil videre
 * Mulighed for at vælge flere konti samtidig - openentries tager data fra csv så vi får alt med
 * Openentries omskrevet til at få data med fra hele bogen via csv export
 * Hvis man afbryder sortering, afbryd program
 * Ledger til array via csv output

## 2023-11-21
 * Mindre stavefejl
 * Tilføjet kald til automatisk udligning af debitor-kreditorposter, samt sørger for der kun bliver bogført hvis man bekræfter det
 * Automatisk udligning af debitor og kreditorposter - dog med manuel godkendelse
 * Hver transaktion i hovedbogen validerer med checksum de foregående transaktioner således at der ikke kan fifles med hovedbogen.
 * Kun unikke konti vises - der var dubletter
 * Mindre fejlrettelse og mulighed for ny konto ved entry
 * Hvis man ikke vælger en sag - exit pænt
 * Autoudlign aliases + diverse oprydning
 * Opslag i journaler kræver ej periode
 * Beregning og bøgføring af periodens resultat nu en del af n4s da ledger bash scripts var for langsomme
 * Automatisk beregning af overført resultat uden bash
 * Flere tips&tricks

## 2023-11-20
 * Nextnumber - næste transaktionsnummer og cashbook nr
 * Fjernet html output fra åbning
 * Åbningskørsel køres i et split-window for bedre hastighed
 * Der kan bogføres
 * Ghostcript er påkrævet af removeblankpages.bash
 * Table style striped samt naturlig retning på balance hvis man er regnskabskyndig
 * Forsøger at køre removeblankpages som backgroundjob
 * Forbedret søgefunktion - søg efter hashtags eller fulltextsearch
 * Søgning kører i nyt vindue
 * Rydder op i menu
 * fzf søgning gøres eksakt
 * Diverse relateret til at åbne regnskaber forbedret med splits
 * Nu bruger den hele den kontoplan den har til rådighed
 * Viser status til bruger
 * Vis status for åbning til bruger
 * Vinteroprydning gammel snask
 * fjernet debug info
 * Gammel debug snask
 * old debug stuff

## 2023-11-19
 * Mindre fejl i bookbash rettet
 * Arbejdet videre på ncurses

## 2023-11-18
 * ncurses
 * Forbedret ncurses interface
 * Koblet på ncurses ui, samt file metadata tages med i ledger export
 * Ved sr kør altid .init.bash
 * Build filer
 * Diverse e-conomic stuff

## 2023-11-14
 * Diverse depedencies
 * Ui alias
 * Ncurses php extension
 * Ncurses arbejde

## 2023-11-11
 * E-conomic integration

## 2023-11-10
 * Tilføjet timestamp - inspireret af oh my bash

## 2023-11-08
 * Fjerner vinduesfarver
 * Normal bg color så der understøttes gennemsigtig terminal
 * Disabler farvede vinduer
 * math inkluderes i top
 * Forbedret notevising
 * Visning af transaktioner før indtastning af metadata
 * vindues titler tmux
 * Htmlrapport trækker fra ny ledger-wrapper

## 2023-11-06
 * skal ikke køre dp efter sr
 * matematisk input tillades i newl
 * Newl entry forbedringer

## 2023-11-05
 * Lettere oprydning gamle scripts + newledger wrapper integration til shell
 * new-window frem for popup til ctrl-o
 * Ny ledger wrapper
 * Diverse
 * Pivot ny ledger-wrapper
 * fjerner borderes på tables

## 2023-11-04
 * Expand i særskilt fil så den kan bruges af newl
 * Oprydning i gl key funktion - lederwrapper
 * dato funktioner udskilles
 * Fejltjekning - ikke bruge tomme variabler mere i php
 * unused git stuff
 * Mindre justeringer
 * Ny kommando køres på lookup acc
 * 997 tilpasses med periode til at køre den givne periode
 * Udkast newl - ny php wrapper til ledger

## 2023-11-01
 * Mindre justeringer / oprydning af script
 * Tmux popup frem for newwindow ved ctrl-o søgning
 * Ledger hack nødvendig for korrekt csv export
 * Ændring af tmp mappe for at undgå konflikter
 * Forsøg at fikse ol til kørsel uden opdateret bog
 * 2 nye

## 2023-10-31
 * Mindre fejlrettelse

## 2023-10-30
 * Tager tmux i sin egen mappe
 * Speedups ledger kørsel

## 2023-10-29
 * versionering- samt invoice ting og sager
 * dl link
 * fpaste
 * composer stuff
 * OIOUBL udviklingssager
 * versionering
 * flytter til n4s-export - fremfor tmp
 * Bytter om på menu rækkefølge - regnskab først
 * velkomsthilsen
 * default periode
 * menu stuff
 * manual flyttes til bund i menu

## 2023-10-28
 * Old e-conomic stuff
 * Mailstuff fjernes
 * rt stuff fjernes
 * sanebox stuff fjernes

## 2023-10-27
 * Diverse composer stuff til oioubl
 * ubl invoice er et composer bibliotek
 * Get-func tages ud i separat fil til brug andre steder
 * Ubl template mv
 * Aliases oprydning
 * Composer stuff - til ubl invoicing
 * Scrapbook scrappes

## 2023-10-26
 * Markdown folding behøver ikke angivelse af filetype som cmdline argument, det er i cfg
 * Ændring af vidste du at unicode icon til noget der er i flere fonts
 * Markdown stuff vim
 * Bulk update, samt straks redigering med visning af fejlbesked af filer hvor der er momskode på likvider/debitorer/kreditorposter
 * Div
 * Automatisk versionering
 * Flyttet installation til separat dokument

## 2023-10-25
 * Nem indtastning i thenow
 * Manglende bilagsrapport samt google-chrome udskiftes med wkhtmltopdf
 * Opdaterer dependencies
 * Manglende bilag funktion tilføjet og et par andre smårettelser
 * Hvis tomt array til piechart, abort mission
 * Nye bindings til at sætte posteringer ind i thenow
 * readme stuff
 * readme stuff
 * Lidt oprydning gamle uaktuelle scripts - samt nyt fzf tema
 * readme stuff
 * Fjernet automatisk changelog, for meget støj henviser i stedet til activities på github
 * Fjerner kodeblokke indentering
 * Mindre justering i layout

## 2023-10-24
 * readme stuff
 * Oprydning af menuer og lidt ændring til farveskemaer herunder mere synligt vindueknapper
 * Fjerner nogle streger som ikke ser så godt ud
 * Forbedring af farveskema
 * Fjerner done-filter fra vitouch, ikke nødvendigt
 * Bugfix - fundet ved opgradering til php8- gør intet, fjernes
 * Script til at migrere server
 * Nyt dropbox script
 * Fjernet duplicate kørsel af pandoc

## 2023-10-23
 * Truecolors til journaler og vim
 * Status konti saldi opdateres ved l på tmux line
 * Readme key selling points
 * Readme
 * Farvekoder på statuskoder
 * Et par tips om kontokort
 * Eksempel video
 * Ny demo video
 * readme stuff
 * readme stuff
 * Manual fjernet som del af denne side
 * readme stuff
 * readme stuff
 * readme stuff

## 2023-10-22
 * Contributing
 * Update issue templates
 * ændring af readme
 * README
 * README
 * README
 * README
 * README
 * README
 * Udkast til code of conduct (credit chatgpt)
 * conduct
 * Nyt periodresult script - simplificeret
 * Diverse

## 2023-10-21
 * Naturlig retning på perioderegnskab
 * Forbedring - lad ikke program fejle hvis man taster et space ved input af tal
 * Disabler budgetscript midlertidigt
 * Diverse noter

## 2023-10-20
 * Calcurse - nok en joo ting
 * Datepicker har også altid nu  - tager fra 1970-01-01 og frem til dags dato
 * Ny changelog
 * Opdateret changelog
 * N4s-relaterede ideer publiceres løbende
 * Flere ideer taget med
 * joo bindings kalender

## 2023-10-19
 * Lagkagediagrammer til rapportering
 * Fjerner specialtegn som kan messe med csv export
 * Momsafregningsscript

## 2023-10-18
 * old stuff cleanup
 * old stuff cleanup
 * Dont use pager
 * How can i forget it? removed
 * Indtægter og udgifter = Overført resultat
 * Også resultatdisponering mv
 * Nye aliaser
 * Ny åbningsfunktion forbedret
 * Pie charts forbedret - nu af alle omkostninger

## 2023-10-17
 * Bedre historik på markdown filer - på sektionsbasis
 * error reporting shorties
 * Stats - rapportering forbedring - piechart udgifter
 * Mindre rettelse - script er disablet så nok ikke relevant
 * Nyt årsafslutningsscript - automatisk ved ændring af periode
 * Behøver ikke vise overført resultat ved periodeskift
 * Rewrite med CSV output for at omgå ledger-cli fejl der gav dublet output på nogle subaccounts
 * Fjerne noget debug info, samt åbne i pager
 * Oprydning gamle linier med git versionering

## 2023-10-16
 * Mere læsbart diff-output (korrekt rækkefølge)
 * Udvidet kontoplan
 * Søgt at udbedre mosh problematik med tmux
 * Short trackable links - measure amount of downloads

## 2023-10-15
 * Ny rapportering eksempel
 * linkfix
 * linkfix
 * Instruks rapportering

## 2023-10-14
 * Kontokort forbedringer
 * Bedre farve til tips og tricks
 * Flere tips & tricks
 * Advarsel ved momskoder på egenkapital
 * Rapporteringer forbedringer
 * Diverse styling kontokort
 * Win11 screenshots ssh session
 * Billeder
 * Readme stuff
 * Readme stuff
 * Backup forbedring verbose output fjernet
 * Arbejdet videre på 999
 * Forbedringer til HTML rapportering
 * Om Stuff - 2nd brain
 * Readme stuff
 * Rapportering forbedringer herunder nøgletalsrapport påbegyndt
 * Diverse html rapportering

## 2023-10-12
 * Lidt borgbackup rettelse , samt tilføjet tips og tricks
 * Tips og tricks til systemet

## 2023-10-11
 * Flyttet eksempel regnskaber op efter video
 * Readme stuff
 * UBL Faktura klasse
 * Automatisk backup hver gang man åbner regnskab
 * ændret kontaktoplysning
 * Unødvendige periode-aliases - erstattet af Alt-p (dp - periode vælger)
 * Diverse unødvendigt stuff fra UBL bibliotek fjernet - beholdt LICENS da det er under MIT licens - skulle angiveligt være kompatibelt med GPL Licens
 * editorconfig unødvendig
 * Mere venlig farve til windows pc

## 2023-10-10
 * Dvierse personlige stats fjernet fra dist-version
 * Mindre rettelse
 * Div forbedringer til README
 * Sales pitch
 * just
 * link
 * linkfix
 * spaces...
 * div dokumentation
 * markdown stuff
 * codefix
 * codefix
 * REadme stuff
 * Readme stuff
 * Readme stuff
 * Readme stuff
 * Readme stuff
 * Readme stuff
 * Readme stuff
 * Readme stuff
 * update instructions
 * Readme stuff
 * Fjernet genveje som er listet i menuen
 * Readme stufff
 * readme stuff
 * readme
 * more readme changes
 * Sort logo fremfor purple
 * toc
 * toc
 * readme
 * Readme stuff
 * Readme stuff
 * Readme stuff

## 2023-10-08
 * Diverse
 * Fjerner binary file ncurses stuff
 * Autoupdate fra git ved start af ny terminal
 * Datepick går 15 år tilbage fremfor 5
 * Kræv sat sato for at kunne søge
 * Viser fortunes fremfor dosha og omsætningsstatistikker
 * fortunes max 80 tegn samt gerne offensive
 * Kontoplan justeringer
 * Tilføjet nye konti - samt føjet 999 scrip til nye regnskaber som default
 * Keylogger
 * Demovideo tilføjet
 * Mindre rettelse video
 * Et linieskift
 * mindre rettelse

## 2023-10-06
 * Forkortelse - første omgang file get contents - snere andre landre funktioner
 * markdown to logfile format, vitouch difffile tager hensyn til markdown headings og subheadings
 * bashrc fjernes
 * Mindre fejlrettelse - nulstillede ikke subsection ved ny sektion
 * Nyt default tema
 * Arbejdet lidt på ncurses - virker stadig ikke
 * En række temaer

## 2023-10-03
 * Udkast til markdowndiff vitouch util
 * Mysql no longer required
 * Fjernett vendor - gamle dependencies
 * Composer stuff
 * Grafik
 * Tilføjet logo til readme
 * Installationsscript deaktiveres midlertidigt
 * Info om vbox extension pack

## 2023-10-02
 * Fjerner debug-info fra html rapportering cmd
 * Fjerner irrelevant produktsammenligning
 * Justering til budgetscript
 * Slår fejlkonto notifikation fra midlertidigt, for den kommer frem selvom saldo er nul
 * typo - pandoc blev ej kørt i rapportgenerator
 * chatgpt stuff removed

## 2023-09-30
 * Version 0.1b
 * TLDR skal også have virtualbox links
 * Tabel med produktsammenligning var for bred til at kunne vises
 * Mindre rettelse til README
 * Justering readme fil med intro booking
 * Readme - tilføjet hvad vi ikke kan
 * Eksempler tilføjet
 * Byttet rækkefølge på kontokort/balance
 * Vedr CSV
 * Readme minor stuff
