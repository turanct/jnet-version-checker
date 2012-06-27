#!/bin/sh

# Search for Gallery installations and get their version

# Search for Gallery 3.x installations:
for domain in `locate modules/gallery/helpers/gallery.php`
do
	version=`cat $domain | grep -E "const VERSION" | awk -F " VERSION = \"" '{print $2}' | awk -F "\";"  '{print $1}'`
	domain=`echo $domain | awk -F "modules/gallery/helpers/gallery.php" '{print $1}'`
	echo "$version $domain"
done

# Search for Gallery 3.x installations:
for domain in `locate modules/core/AdminCore.inc`
do
	domain=`echo $domain | awk -F "modules/core/AdminCore.inc" '{print $1}'`
	for versionfile in `locate versions.dat | grep "$domain"`
	do
		if [ -f "$versionfile" ]; then
			# Versionfile still exists
			version=`tail -1 $versionfile`
			echo "$version $domain"
		fi
	done
done

