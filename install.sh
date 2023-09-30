#!/bin/bash
echo installerer git
sudo apt-get install -y git
mkdir -p ~/.n4s-install
cd ~/.n4s-install
echo opretter logfiler
sudo touch /var/log/olsen
sudo chmod 777 /var/log/olsen
echo opretter /svn
sudo mkdir /svn
echo åbner svn
echo laver gruppe acc
sudo addgroup acc
echo putter denne bruger i gruppe
sudo adduser $(whoami) acc
echo sætter ownership på /svn
sudo chgrp acc /svn/ 
echo sætter permission 777 på /svn - kode
sudo chmod 777 /svn/
echo sætter acl på /svn
sudo setfacl -dR -m g:acc:rwx /svn/
echo tilføjer $(whoami) til acc
echo kloner git
git --version > /dev/null||(echo har ikke git, installerer;sudo apt-get install git -y)
echo kloner nu
cd /svn
echo kloner...
#git clone git@github.com:jrgenolsen/n4s.git
wget https://www.dropbox.com/s/bhall6rcq5444m8/n4s-main.zip?dl=1
unzip "n4s-main.zip?dl=1"
echo klonet - godt - opretter symlink
ln -s n4s-main svnroot
echo installerer dependencies
sudo apt-get install -y $(cat /svn/svnroot/Libraries/Dependencies_debian.txt)
echo opretter /data/
sudo mkdir /data/ -p #datamappe global
echo opretter /data/screens
sudo mkdir /data/screens -p #screenshots
echo sætter grupps acc data
sudo chgrp acc /data/ -R
sudo setfacl -dR -m g:acc:rwx /data/
sudo chmod g+rwx /data/ -R
sudo chown acc /data/ -R

echo opretter privat regnskabsmappe for $(whoami)
mkdir ~/regnskaber
echo kontrollerer aliaser
if ! grep -q svnroot ~/.bashrc; then
	echo "source /svn/svnroot/aliases;alias n4s='tmux a||tmux'" >> ~/.bashrc
fi
cd /svn
sudo apt-get install make autotools-dev automake gcc pkg-config yacc libncurses5-dev # required to build tmux
#git clone https://github.com/tmux/tmux.git
wget "https://github.com/tmux/tmux/releases/download/3.3a/tmux-3.3a.tar.gz"
tar zxvf tmux-3.3a.tar.gz
cd tmux-3.3a
./configure&&make && sudo make install
if [ ! -f ~/.tmux.conf ]; then
	echo linker tmux config
	ln -s /svn/svnroot/Applications/tmux/tmux.conf ~/.tmux.conf
fi
if [ ! -f ~/.vimrc ]; then
	echo linker vim config
	ln -s /svn/svnroot/Libraries/vimrc ~/.vimrc
fi
echo "Du skal nu genstarte computeren"
echo -n "Ønsker du at genstarte (j/n): "
read jn
if [ "$jn" == "j" ]; then
	sudo reboot
fi
