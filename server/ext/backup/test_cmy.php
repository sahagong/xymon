#!/usr/local/php/bin/php -f
<?
/*

make file $XYMONHOME/etc/cmy_bb-hosts 
by coffeein <2003.11.20>


$XYMONHOME="/home/xymon/server";
*/
$fp = fopen("/home/xymon/server/www/exclude_list","r"); 
while( !feof($fp) ) 
	$doc_data .= fgets($fp); 

	fclose($fp); 

	echo $doc_data;
