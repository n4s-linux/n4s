<?php
session_start();
include(__DIR__."/../modules/ledger_login/ledger_login.php");

if (!$auth->isAuthed())
	header("location: /svnroot/Applications/ledger_login.php");  
