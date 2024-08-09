#!/bin/bash
# Locales
num=$(grep da_DK.UTF-8 /etc/locale.gen |grep -v "#"|wc -l)
if [ "$num" == "0" ]; then
	echo "da_DK.UTF-8 UTF-8" >> /etc/locale.gen
	locale-gen
fi
# installationsskript skal skrives om - manuel installation indtil videre
if [ "$1" == "" ]; then
	su -c install.sh install
fi
sudo apt-get install -y git fzf
sudo mkdir /svn
sudo addgroup acc
users=$(cut -d: -f1 /etc/passwd)

# Use fzf to allow the user to select multiple users
sudo chgrp acc /svn/ 
sudo chmod 777 /svn/
sudo setfacl -dR -m g:acc:rwx /svn/
cd /svn
git clone https://github.com/n4s-linux/n4s-your-second-accounting-brain.git
ln -s n4s-your-second-accounting-brain svnroot
mkdir /opt/google -p
chmod 777 /opt/google
ln -s /svn/svnroot/Librarires/chrome /opt/google/chrome
git config --global --add safe.directory /svn/n4s-your-second-accounting-brain
sudo apt-get install -y $(cat /svn/svnroot/Libraries/Dependencies_debian.txt)


echo "Select the users who should get home directory modifications (use TAB to select multiple users):"
echo "$users" | fzf --multi|while read curselect 
do
	sudo adduser $curselect acc
	mkdir /home/$curselect/regnskaber
	
	chown $curselect /home/$curselect/regnskaber
	if ! grep -q svnroot /home/$curselect/.bashrc; then
		echo "source /svn/svnroot/aliases;alias n4s='tmux a||tmux'" >> /home/$curselect/.bashrc
	fi
	if [ ! -f /home/$curselect/.tmux.conf ]; then
		echo linker tmux config
		ln -s /svn/svnroot/Applications/tmux/tmux.conf /home/$curselect/.tmux.conf
	fi
	if [ ! -f /home/$curselect/.vimrc ]; then
		ln -s /svn/svnroot/Libraries/vimrc /home/$curselect/.vimrc
	else
		jn=$(echo -e "Yes\nNo"|fzf --header="Replace vimrc")
		if [ "$jn" == "Yes" ]; then
			mv /home/$curselect/.vimrc /home/$curselect/.vimrc.old.$(date +%s)
			ln -s /svn/svnroot/Libraries/vimrc /home/$curselect/.vimrc
		fi
	fi
done


sudo mkdir /data/ -p #datamappe global
sudo mkdir /data/screens -p #screenshots
sudo mkdir /data/regnskaber -p
sudo chgrp acc /data/ -R
sudo setfacl -dR -m g:acc:rwx /data/
sudo chmod g+rwx /data/ -R
sudo chown acc /data/ -R

cd /svn
sudo apt-get install -y make autotools-dev automake gcc pkg-config yacc libncurses5-dev # required to build tmux
wget "https://github.com/tmux/tmux/releases/download/3.3a/tmux-3.3a.tar.gz"
tar zxvf tmux-3.3a.tar.gz
cd tmux-3.3a
./configure&&make && sudo make install
cd /svn
git clone https://github.com/ledger/ledger.git
cd ledger
./acprep opt
sudo apt-get install build-essential cmake autopoint texinfo python3-dev \
     zlib1g-dev libbz2-dev libgmp3-dev gettext libmpfr-dev \
     libboost-date-time-dev libboost-filesystem-dev \
     libboost-graph-dev libboost-iostreams-dev \
     libboost-python-dev libboost-regex-dev libboost-test-dev -y
# Use make with the number of CPU cores
make -j$(nproc) && make install || exit


echo "Du skal nu genstarte computeren"
echo -n "Ønsker du at genstarte (j/n): "
read jn
if [ "$jn" == "j" ]; then
	reboot
fi
