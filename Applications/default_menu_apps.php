<?php
$svnroot = getenv("SVNROOT");
$apps = array(
        "Vælg kunde"=>array(
            'function'=>'select_customer',
            'application'=>null,
            'parameters'=>array(
                'kunderef'=>array('type'=>'string','optional'=>0,'argtype'=>null)
            )
        ),
        "Tidsregistrering"=>array(
            'application'=>"php $svnroot/Applications/tidsreg_add.php",
            'parameters'=>array(
                'kunderef' => array('type'=>'string','optional'=>0, 'argtype'=>'argv'),
                'op'=> array('type'=>'string','optional'=>1,'default'=>$op,'argtype'=>"env"
                )
            )
        ),
        "Historik"=>array(
            'application'=>"php $svnroot/Applications/tidsreg_add.php h",
            'parameters'=>array(
                'kunderef' => array('type'=>'string','optional'=>0, 'argtype'=>'argv')
                )
            ),
        "Info (kunde stamdatakort)"=>array(
            'application'=>"php $svnroot/Applications/tidsreg_add.php i",
            'parameters'=>array(
                'kunderef' => array('type'=>'string','optional'=>0, 'argtype'=>'argv')
                )
            ),
        "Todo"=>array(
            'application'=>"php $svnroot/Applications/todo.php",
            'parameters'=>array(
                'kunderef' => array('type'=>'string','optional'=>0,'argtype'=>'argv'),
                'op'=> array('type'=>'string','optional'=>1,'default'=>$op,'argtype'=>"env")
            )
        ),
        "Emails"=>array(
            'application'=>null,
            'function'=>'select_email',
            /*'parameters'=>array(
                'kunderef' => array('type'=>'string','optional'=>0,'argtype'=>'argv'),
                'op'=> array('type'=>'string','optional'=>1,'default'=>$op,'argtype'=>"env"),
            ), */
            'emaildata'=>array()
        ),
        "Email-arkiv"=>array(
            'application'=>null,
            'function'=>'select_email_archive'
            /*'parameters'=>array(
                'kunderef' => array('type'=>'string','optional'=>0,'argtype'=>'argv'),
                'op'=> array('type'=>'string','optional'=>1,'default'=>$op,'argtype'=>"env"),
            ), */
        )

    );
?>
