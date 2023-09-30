<?php
include(__DIR__."/common/SimpleTpl.php");
include(__DIR__."/common/core.class.php");
$stpl = new \Mikjaer\SimpleTpl\SimpleTpl();
$core = new Core($stpl);

if ($core->isAuthed())
{
	$core->addMenuItem((new MenuItem())
      ->setLabel("Hj&aelig;lp")
      ->setUrl("https://www.olsensrevision.dk/kontakt"));

	$core->addMenuItem((new MenuItem())
      ->setLabel("Grid")->setModule("grid"));

	$core->addMenuItem((new MenuItem())
      ->setLabel("Finans")
      ->addSubMenuItem( (new MenuItem) ->setLabel("Saldobalance")       ->setUrl("lw/key_html.php"))
      ->addSubMenuItem( (new MenuItem) ->setLabel("Ledger")     ->setUrl("lw/ledger_output.php"))
      ->addSubMenuItem( (new MenuItem) ->setLabel("Kontokort")        ->setModule("finans")->setMethod("kontokort"))
    );

	$core->addMenuItem((new MenuItem())
      ->setLabel("Debitor")
      ->addSubMenuItem( (new MenuItem) ->setLabel("Kartotek"))
      ->addSubMenuItem( (new MenuItem) ->setLabel("Fakturaer"))
	);

	$core->addMenuItem((new MenuItem())
      ->setLabel("Kreditor")
      ->addSubMenuItem( (new MenuItem) ->setLabel("Kartotek"))
      ->addSubMenuItem( (new MenuItem) ->setLabel("Fakturaer"))
	);

	$core->addMenuItem((new MenuItem())
      ->setLabel("Log ud")
      ->setUrl("/?logout=true")
	    );
}

?>
