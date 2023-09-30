#!/bin/bash
cd /data/regnskaber/transactions_crm/.tags
#cat $runpath
grep $date $runpath|sort
#cat $runpath|grep $date|sort
#|sed -e 's/^[ \t]*//'|sort
