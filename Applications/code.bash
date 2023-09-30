#!/bin/bash
function c() {                                                                                                                                                                                                                                       
        start=$(date +%s)
        bash /svn/svnroot/Applications/start.bash code
        end=$(date +%s)
        min=$(echo "scale=2;($end-$start)/60/60*2"|bc -l) #*2 = fordobling af minutter brugt, til tænketid og research 2023-03-21T17:02 joo     tænker det er et fornuftigt skøn
        ts=$(date "+%Y-%m-%dT%H:%M")
        mkdir -p /data/regnskaber
        fn=$(cat ~/tmp/.editcode)
        rm -rf ~/tmp/.editcode
        fn=$(echo "$fn"|sed 's/\/svn\/svnroot\///g')
        echo -e "$ts\t#n4s:#code:$fn\t$min" >> /data/regnskaber/stats/$(whoami).stats
}

