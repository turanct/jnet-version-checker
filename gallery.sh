#!/bin/sh

# Zoeken naar Gallery 3.x instalaties:

for domain in `locate modules/gallery/helpers/gallery.php`
do
	#echo $domain
	domain2=`echo $domain | awk -F "modules/gallery/helpers/gallery.php" '{print $1}'`
	echo $domain2
	cat $domain  | grep -E "const VERSION" | awk -F " VERSION = \"" '{print $2}' | awk -F "\";"  '{print $1}'
done


# Zoeken naar Gallery 2.x instalaties:

for domain in `locate modules/core/AdminCore.inc`
do
	# /var/www/vhosts/os.jnet.be/httpdocs/gallery2/modules/core/AdminCore.inc
	domain=`echo $domain | awk -F "modules/core/AdminCore.inc" '{print $1}'`
	echo $domain
	#cat $domain  | grep -E "const VERSION" | awk -F " VERSION = \"" '{print $2}' | awk -F "\";"  '{print $1}'
done

