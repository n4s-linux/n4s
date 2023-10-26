# Krav til installation
En frisk installation af debian, ubuntu eller en anden debian-baseret distribution
Kan også installeres på andre distributioner, programafhængigheder kan findes i Libraries/Dependencies_Debian.txt
Den nemmeste måde at installere det på er ved at downloade være prækonfigurerede Virtualbox Appliance, instruktioner nedenfor

# Guide til Import og Kørsel af en OVA-fil i VirtualBox

I denne vejledning vil jeg guide dig gennem trinnene til at importere og køre en OVA-fil (Open Virtualization Appliance) i VirtualBox. En OVA-fil indeholder typisk et komplet operativsystem, og VirtualBox er en populær virtualiseringssoftware til at køre virtuelle maskiner.

## Forudsætninger

Inden du begynder, skal du sørge for at have følgende:

- **VirtualBox:** Hent og installer VirtualBox fra den officielle hjemmeside (https://www.virtualbox.org/wiki/Downloads).

*Vigtigt du skal både downloade virtualbox og installere Virtualbox VM Extension Pack for optimal ydelse*

## Trin

1. **Åbn VirtualBox:**
   - Start VirtualBox-applikationen på din computer.

2. **Importer OVA-fil:**
   - Klik på menuen "Filer" i VirtualBox.
   - Vælg "Importer apparat" fra menuen.

3. **Vælg OVA-fil:**
   - I dialogboksen "Apparat, der skal importeres", skal du klikke på mappeikonet for at browse og vælge den OVA-fil, du ønsker at importere.
   - Klik på "Næste" for at fortsætte.

4. **Indstillinger for apparatet:**
   - Gennemgå indstillingerne for den virtuelle maskine i skærmbilledet "Indstillinger for apparatet".
   - Du kan ændre indstillinger som navn, CPU og hukommelsesallokering, hvis det er nødvendigt.
   - Klik på "Importer" for at fortsætte.

5. **Importproces:**
   - VirtualBox begynder nu at importere OVA-filen.
   - Denne proces kan tage noget tid, afhængigt af OVA-filens størrelse og din computers ydeevne.

6. **Import fuldført:**
   - Når importprocessen er færdig, vises en bekræftelsesmeddelelse.
   - Klik på "Acceptér", hvis du bliver bedt om at acceptere licensvilkårene.

7. **Virtuel maskine tilføjet:**
   - Den virtuelle maskine, der svarer til den importerede OVA-fil, vises nu i VirtualBox-manageren.

8. **Start den virtuelle maskine:**
   - Vælg den virtuelle maskine fra listen.
   - Klik på knappen "Start" i VirtualBox-manageren.

## OVA solution koder
**Logins til den virtuelle maskine**
* Brugernavn: n4s
* Password bruger: n4s
* Diskkrypteringskode: n4s
* Root kode: n4s

## Opdatering af N4S
Opdater din lokale kopi af n4s ved at trække ændringer fra GitHub ved hjælp af følgende kommando:
```bash
cd /svn/svnroot/
git pull
```

Når opdateringen er færdig, skal du lukke din terminal.
Nu har du succesfuldt opdateret n4s til den nyeste bleeding edge-version fra GitHub.

Husk at have de nødvendige tilladelser og rettigheder til at opdatere n4s, og udvis forsigtighed, når du arbejder med kommandolinjeværktøjer.

# Kom hurtigt i gang
Du kan komme i gang med det samme ved at downloade vores OVA og starte den i virtualbox. For at lære hvordan du bruger systemet kan du se hvordan vi bogfører vores [eksempel regnskab](https://drive.google.com/file/d/1nwrxOqLnyxyygyskKH82jMwOPyTXERfQ/).

Hvis du vil hurtigt i gang og have en introduktion kan du booke et zoom-møde til favorabel intro-pris.
Det forventes at du har downloadet vores OVA samt Virtualbox med Extension Pack, og at du har Zoom installeret
Det forventes at du er nogenlunde teknisk kyndig.
Jeg vil vise dig hvordan du:
* Opretter regnskaber
* Indtaster transaktioner
* Importerer bank CSV filer
* Genererer balancer og kontokort

samt besvare evt. spørgsmål du måtte have.

Det anbefales at du har et konkret regnskab til til bogføring som vi kan arbejde med.


# N4s er ikke bare et regnskabsystem !!!
Vores motto ved udviklingen af *stuff* - din *2nd brain* / "anden hjerne" : "A place for everything and a thing for every place " - altså et sted til alle ting, og en ting til alle steder.
Lige meget hvad du har af oplysninger du ønsker din computer skal organisere skal der være en plads til den, uden at du skal have fat i en udvikler til at udvikle et nyt felt.

Selvom det måske virker som en udfordrende opgave at organisere og bevare al vores information og idéer, er der nu en løsning, der gør det let for alle at bygge deres eget "anden hjerne" af viden 🧠💡. Med vores innovative N4S-system kan du nemt skabe en digital platform, der fungerer som din sekundære hjerne og gør det muligt at organisere og navigere gennem dine tanker og informationer på en problemfri måde.

## Fordele ved at opbygge en "anden hjerne" i markdown-format:

- **Øget Produktivitet**: Uanset om du har ADHD eller ej, vil N4S hjælpe dig med at forbedre din produktivitet. Du kan nemt strukturere og finde dine data, så du kan fokusere på det, der virkelig betyder noget.

- **Bedre Organisering**: Med N4S kan du nemt organisere alle dine filer ved at bruge hashtags i det åbne og etablerede markdown-format. Dette gør det enkelt at kategorisere og finde de oplysninger, du har brug for.

- **Hurtig Adgang til Information**: Med N4S-systemet kan du hoppe fra en hashtag i en fil direkte til den relevante information, hvilket sparer tid og gør det nemt at fordybe dig i de emner, der interesserer dig.

- **Søgning på Tværs af Filer**: Du kan nemt søge i dine data på tværs af filer for at finde præcis, hvad du leder efter, hvilket gør informationssøgning til en leg.

- **Eksportér til PDF**: Du kan også eksportere dine oplysninger til et læsbart PDF-format, så du kan dele din viden med andre på en professionel måde.

Med N4S i det åbne og etablerede markdown-format er det enkelt for alle at opbygge deres egen "anden hjerne" af information og få glæde af en mere struktureret og organiseret tilgang til data.!!
Selvom det måske virker som en udfordrende opgave at organisere og bevare al vores information og idéer, er der nu en løsning, der gør det let for alle at bygge deres eget "anden hjerne" af viden 🧠💡. Med vores innovative N4S-system kan du nemt skabe en digital platform, der fungerer som din sekundære hjerne og gør det muligt at organisere og navigere gennem dine tanker og informationer på en problemfri måde.

Hvad kan man f.eks. bruge det til? (ikke udtømmende liste) :
* En password database (her anbefaler vi kraftigt du vælger at blowfish-kryptere dine markdown-filer)
* En kundedatabase 
* CRM-system - styr dine sager - dokumenter alle hændelser
* Indkøbsliste
* Medicin / patientjournal
* Dagbog
* Stamdata
