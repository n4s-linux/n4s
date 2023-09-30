<?php
require_once("key.php");
$ledger_accounts = null;

function listaccounts() {
$cmd = "LEDGER_DEPTH=99 noend=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger accounts > /tmp/accounts;chmod 777 /tmp/accounts";
system($cmd);
//echo $cmd;
$accounts = explode("\n",file_get_contents("/tmp/accounts"));
return $accounts;
}
function has_subacc($account){
	global $ledger_accounts;
	if ($ledger_accounts == null) {
		$cmd = "ledger_depth=99 noend=1 ledger_begin=1970/1/1 ledger_end=2099/12/31 php /svn/svnroot/Applications/key.php ledger accounts > /tmp/subacc";
		system($cmd);
		$ledger_accounts = explode("\n",file_get_contents("/tmp/subacc"));
	}
	$a = has_subs($account,$ledger_accounts);
	return $a;
}
function has_subs($acc,$data) {
	foreach ($data as $d) {
		if (stristr($d,$acc.":"))
			return true;
	}
	return false;
}

function check_accounts($file) {
        global $undefined_subacc;
        global $accounts;
        global $path;
        global $aliases;
        if (!isset($file['Transactions'])) return $file;
        foreach ($file['Transactions'] as &$curtrans) {
                if (account_exists($curtrans['Account'])) continue;
                if (!isset($aliases[$curtrans['Account']])) {
                        $aname = $curtrans['Account'];
			if (stream_isatty(STDERR)) {
				$curtrans['Account'] = lookup_acc($accounts,0,$curtrans['Account']);
				$aliases[$aname] = $curtrans['Account'];
				file_put_contents("$path/aliases",json_encode($aliases,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");
				file_put_contents("$path/$file[Filename]",json_encode($file,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");
	//                      system("cd \"$path\";git commit aliases -m \"New Alias via command line\";git commit \"$file[Filename]\" -m 'Changed accounts via new alias from commandline'");
			}
			else
				$curtrans["Account"] = "New aliases:$curtrans[Account]";
                }
                else {
                        $curtrans['Account'] = $aliases[$curtrans['Account']];
                        file_put_contents("$path/$file[Filename]",json_encode($file,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");
//                      system("cd \"$path\";git commit \"$file[Filename]\" -m 'Changed accounts via alias from commandline'");

                }
                if (has_subacc($curtrans['Account']))
                        $curtrans['Account'] .= ":$undefined_subacc";
        }
        return $file;
}

?>
