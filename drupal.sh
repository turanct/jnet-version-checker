#!/bin/sh

# Search for Drupal installations and get their version
for domain in `locate sites/default/settings.php`
do
	domain=`echo $domain | awk -F "sites/default/settings.php" '{print $1}'`
	version=`drush st -r $domain | grep "Drupal version" | awk -F ":  " '{print $2}' | awk -F " " '{print $1}'`
	if [ -n "$version" ]; then
		# It's a real Drupal installation
		echo "$version $domain"
	fi
done

