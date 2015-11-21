#!/bin/bash

echo "/**************************************"
echo "= Starting sync command from SVN"
echo "**************************************/";
/usr/bin/rsync -av --exclude .svn root@192.168.1.202:/var/www/bot/postdata/* /var/www/rsync_postdata
/bin/chown -R bot:bot /var/www/rsync_postdata/
/bin/find /var/www/rsync_postdata/ -type d -exec chmod 777 {} \;

echo "/**************************************"
echo "= Sync from SVN Completed"
echo "**************************************/";

/bin/rm -Rf /var/www/rsync_postdata/*/cookies/*
/bin/rm -Rf /var/www/rsync_postdata/*/login/*
/bin/rm -Rf /var/www/rsync_postdata/*/logs/*
/bin/rm -Rf /var/www/rsync_postdata/*/search/*
/bin/rm -Rf /var/www/rsync_postdata/*/sending/*
/bin/rm -Rf /var/www/rsync_postdata/*/xml/*

echo "/**************************************"
echo "= Deleted Logs and Sync to All VM's postdata"
echo "**************************************/";
# /usr/bin/rsync -av /var/www/rsync_postdata/* /var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.102"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.102:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.103"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.103:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.104"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.104:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.105"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.105:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.106"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.106:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.107"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.107:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.108"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.108:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.109"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.109:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.110"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.110:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.111"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.111:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.112"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.112:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.113"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.113:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.114"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.114:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.115"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.115:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.116"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.116:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.117"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.117:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.118"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.118:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.119"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.119:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.120"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.120:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.121"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.121:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.122"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.122:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.123"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.123:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.124"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.124:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.125"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.125:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.126"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.126:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.127"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.127:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.128"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.128:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.129"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.129:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.130"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.130:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.131"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.131:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.132"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.132:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.133"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.133:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.134"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.134:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.135"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.135:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.136"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.136:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.137"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.137:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.138"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.138:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.139"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.139:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.140"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.140:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.141"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.141:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.142"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.142:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.143"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.143:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.144"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.144:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.145"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.145:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.146"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.146:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.147"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.147:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.148"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.148:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.149"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.149:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.150"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.150:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.151"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.151:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.152"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.152:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.153"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.153:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.154"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.154:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.155"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.155:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.156"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.156:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.157"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.157:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.158"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.158:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.159"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.159:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.160"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.160:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.161"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.161:/var/www/html/postdata



echo "/**************************************"
echo "="
echo "="
echo "= Sync Data to Production Completed"
echo "="
echo "="
echo "**************************************/";


echo "/**************************************"
echo "= Sync to 192.168.1.181"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.181:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.182"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.182:/var/www/html/postdata

echo "/**************************************"
echo "= Sync to 192.168.1.183"
echo "**************************************/";
/usr/bin/rsync -av /var/www/rsync_postdata/* root@192.168.1.183:/var/www/html/postdata

echo "/**************************************"
echo "="
echo "="
echo "= Sync All Data Completed"
echo "="
echo "="
echo "**************************************/";

