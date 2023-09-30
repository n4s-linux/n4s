borg create /root/b::data-$(date +%Y-%m-%dT%H%M) /data -e none --compression lz4 -v
borg create /root/b::svnroot-$(date +%Y-%m-%dT%H%M) /svn/svnroot -e none --compression lz4 -v
borg create /root/b::home-$(date +%Y-%m-%dT%H%M) /home/ -e none --compression lz4 -v
