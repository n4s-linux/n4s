#((if [ $(screen -ls|grep $STY|grep Attached|wc -l|awk '{print $1}') != "1" ]; then screen -d $STY; else sleep 5;fi)&)&
