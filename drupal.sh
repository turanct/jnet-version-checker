#!/bin/sh

for domain in `locate sites/default/settings.php`
do
	domain=`echo $domain | awk -F "sites/default/settings.php" '{print $1}'`
	version=`drush st -r $domain | grep "Drupal version" | awk -F ":  " '{print $2}' | awk -F " " '{print $1}'`
	echo "$version $domain"
done

