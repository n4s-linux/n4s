<?php
	//Amazon Market place vouchers
	$input = system("ls \$HOME/Dropbox/SaneBox/marketplace-message*/*/*.pdf >/svn/svnroot/tmp/lsoutput ");
	$input = file_get_contents("/svn/svnroot/tmp/lsoutput");
	unlink ("/svn/svnroot/tmp/lsoutput");
	$files = explode("\n",$input);
	foreach ($files as $file) {
		$mtime = filemtime($file);
		system("(cp -p \"$file\" \"\$HOME/Dropbox/Olsens IT ApS/Bilag/Incoming/Amazon_$mtime.pdf\";rm \"$file\") >/dev/null 2>&1");
	}
	$input = system("ls \$HOME/Dropbox/SaneBox/Google\\ Billing/*/*.pdf  >/svn/svnroot/tmp/lsoutput ");
	$input = file_get_contents("/svn/svnroot/tmp/lsoutput");
	$files = explode("\n",$input);
	foreach ($files as $file) {
		$mtime = filemtime($file);
		system("(cp -p \"$file\" \"\$HOME/Dropbox/Olsens IT ApS/Bilag/Incoming/Google_$mtime.pdf\";rm \"$file\") >/dev/null 2>&1");
	}
?>
