Menuen er defineret i application.php

Strukturen hvori menuen defineres er et såkaldt fluent api, det består af en række kædede metoder med det formål at køre
koden lettere at, selv for folk der ikke er vandt til at kode php.

$core->addMenuItem() opretter et menuitem, og tager klassen MenuItem som paramenter, og returnerer samtidig denne klasse.

Det vil sige at:

$core->addMenuItem(new MenuItem())

Opretter en ny MenuItem klasse og bruger den til at oprette et punktpunkt, hvoreftr metoden addMenuItem returner denne 
klasse, derfor kan du med det samme kalde metoder i MenuItem klassen, således:

$core->addMenuItem((new MenuItem())
     ->setLabel("Grid")
     ->setModule("grid"));

Dette kunne også skrives således:

$menuItem = new MenuItem();
$menuItem   ->setLabel("grid");
$menuItem   ->setModule("grid");
$core->addMenuItem(new MenuItem());


### setUrl("kasseklade.php") / setUrl("https://www.olsensrevision.dk");
Menupunktet vil linke til den givende URL og blander sig på ingen måde i hvad der linkes til, dog kan du inkludere header og footer i 
et eksternt script som eksemplificeret i kasseklade.php:

<?php 
include ("application.php");

$core->setTitle("Kassekladen i det gamle design");
print $core->header();

?>

Her indsættes det script som generere kassekladen.

<?php
print $core->footer();
?>

### SetModule("finans");
Menupunktet vil linke til modulet ved navn finans, modulet ligger i modules mappen "moduler/finans" som indeholder en fil ved navn
finans.class.php som skal indeholde en klasse ved navn finans.php, med en metode ved navn main.php som forventes at generere den
dynamiske side.

Ekstra funktioner kan lægges i klassen, ligesom der kan ligge ekstra filer i mappen.

### SetMethod("metode");
Menupunkter der allerede peger på et modul kan tillige pege på en metode, dette fungere præcis som med moduler bortset fra at den
ikke vil kalde main metoden, men den metode du specificerer.

### SimpleTemplate

Er dokumenteret her: https://github.com/Mikjaer/SimpleTpl/blob/master/getting-started.txt 
