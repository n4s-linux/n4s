dest=n4spub #er i hosts
if [ "$1" == "add" ]; then
	find /svn/svnroot/Applications /svn/svnroot/Libraries/ /svn/svnroot/Robot /svn/svnroot/Documents|grep -v deprecated|grep -v vendor|while read i
	do
		matchcount=$(grep "$i" /svn/svnroot/Applications/public.filelist|wc -l)
		if [ "$matchcount" == 0 ]; then
			echo "$i"
		fi
	done|fzf --multi >> /svn/svnroot/Applications/public.filelist
elif [ "$1" == "pack" ]; then
	mkdir -p ~/pack/Applications ~/pack/tmp/ ~/pack/Robot ~/pack/Libraries
	tar -cvf ~/pack.tar --files-from="/svn/svnroot/Applications/public.filelist"
	scp ~/pack.tar root@$dest:/root/
	ssh root@$dest tar xvf pack.tar
fi
