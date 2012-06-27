#!/bin/sh

# Locate wp-includes/version.php
for file in `locate viewforum.php | grep -v "language"`
do
	# Get phpBB config file path
	config=`echo $file | sed -e "s/viewforum\.php/config.php/g"`

	# Get connection details
	host=`cat $config | grep "dbhost" | awk -F " = '" '{print $2}' | sed -e "s/';//g"`
	dbname=`cat $config | grep "\\$dbname" | awk -F " = '" '{print $2}' | sed -e "s/';//g"`
	dbuser=`cat $config | grep "\\$dbuser" | awk -F " = '" '{print $2}' | sed -e "s/';//g"`
	dbpass=`cat $config | grep "\\$dbpasswd" | awk -F " = '" '{print $2}' | sed -e "s/';//g"`
	dbprefix=`cat $config | grep "\\$table_prefix" | awk -F " = '" '{print $2}' | sed -e "s/';//g"`

	# Get version number
	InstalledVersion=`mysql -u $dbuser -D $dbname -p$dbpass -h $host --skip-column-names -e "select config_value from ${dbprefix}config config where config_name='version';" | grep '[\d\.]*'`

	# Output
	echo "$InstalledVersion $config"
done
