<?php
require_once("vendor/autoload.php");
use Kunnu\Dropbox\DropboxApp;

$app = new DropboxApp("okq3n0esj3k8p4y", "231n635tsyuaibp");
use Kunnu\Dropbox\Dropbox;

$dropbox = new Dropbox($app);

$listFolderContents = $dropbox->listFolder("/");
print_r($listFolderContents);
?>
