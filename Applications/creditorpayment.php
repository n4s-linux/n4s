<?php
  $amount = explode(" ",$argv[1])[0] ;
  $account = substr($argv[1],strlen($amount)+1);
$amount *= -1;
$account2 = str_replace("Aktiver:Debitorer:","",$account);
$account2 = str_replace("Passiver:Kreditorer:","",$account2);
echo gettransdate() . " Betaling via bank:  ($account2)\n\t$account  $amount\n\tBank\n\n";
  
  function gettransdate() {
  if (getenv("transdate") == FALSE)
    return date("Y-m-d");
    else {
      fwrite(STDERR, "Indtast transaktionsdato: " . PHP_EOL);
      $handle = @fopen("/dev/stdin", "r");
        $retval = trim(fgets($handle));
      fclose($handle);
    return $retval;
    }
}
?>