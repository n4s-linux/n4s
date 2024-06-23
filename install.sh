#!/bin/bash
# installationsskript skal skrives om - manuel installation indtil videre
if [ "$1" == "" ]; then
	su -c install.sh install
fi
sudo apt-get install -y git fzf
sudo mkdir /svn
sudo addgroup acc
users=$(cut -d: -f1 /etc/passwd)

# Use fzf to allow the user to select multiple users
echo "Select the users who should get home directory modifications (use TAB to select multiple users):"
selected_users=$(echo "$users" | fzf --multi)
foreach selected_users as curselect
do
	sudo adduser $curselect acc
done
sudo chgrp acc /svn/ 
sudo chmod 777 /svn/
sudo setfacl -dR -m g:acc:rwx /svn/
cd /svn
git clone git@github.com:jrgenolsen/n4s.git
ln -s n4s svnroot
sudo apt-get install -y $(cat /svn/svnroot/Libraries/Dependencies_debian.txt)
sudo mkdir /data/ -p #datamappe global
sudo mkdir /data/screens -p #screenshots
sudo chgrp acc /data/ -R
sudo setfacl -dR -m g:acc:rwx /data/
sudo chmod g+rwx /data/ -R
sudo chown acc /data/ -R

foreach selected_users as curselect
do
mkdir /home/$curselect/regnskaber
chown $curselect /home/$curselect/regnskaber
if ! grep -q svnroot /home/$curselect/.bashrc; then
	echo "source /svn/svnroot/aliases;alias n4s='tmux a||tmux'" >> /home/$curselect/.bashrc
fi
done
cd /svn
sudo apt-get install make autotools-dev automake gcc pkg-config yacc libncurses5-dev # required to build tmux
wget "https://github.com/tmux/tmux/releases/download/3.3a/tmux-3.3a.tar.gz"
tar zxvf tmux-3.3a.tar.gz
cd tmux-3.3a
./configure&&make && sudo make install

foreach selected_users as curselect
do
	if [ ! -f /home/$curselect/.tmux.conf ]; then
		echo linker tmux config
		ln -s /svn/svnroot/Applications/tmux/tmux.conf /home/$curselect/.tmux.conf
	fi
	if [ ! -f /home/$curselect/.vimrc ]; then
		echo linker vim config
		ln -s /svn/svnroot/Libraries/vimrc ~/.vimrc
	fi
done
echo "Du skal nu genstarte computeren"
echo -n "Ønsker du at genstarte (j/n): "
read jn
if [ "$jn" == "j" ]; then
	reboot
fi
