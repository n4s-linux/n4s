<?php
// this is just an example how to get the hash
print json_encode(	array(	"donaldjtrump"	=> array(
							"password" => password_hash("hereisapass",PASSWORD_DEFAULT)
						),
				"forkalimdor" => array(
							"password" => password_hash("hereiapass2",PASSWORD_DEFAULT)
						),
				"putin" => array(
							"password" => password_hash("verysecretpass3",PASSWORD_DEFAULT)
						)
					));

