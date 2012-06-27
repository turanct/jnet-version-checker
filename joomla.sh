#!/bin/sh

# Search for Joomla! installations and get their version

# Search for Joomla! 1.x installations:
for file in `locate libraries/joomla/version.php`
do
	masterversion=`cat $file | grep '$RELEASE' | sed -e "s/.*= '//g" -e "s/';//g"`
	subversion=`cat $file | grep '$DEV_LEVEL' | sed -e "s/.*= '//g" -e "s/';//g"`
	version=$masterversion"."$subversion
	domain=`echo $file | awk -F "libraries/joomla/version.php" '{print $1}'`
	echo "$version $domain"
done

# Search for Joomla! 2.x installations:
for domain in `locate joomla.xml | grep -v "/plugins/" | grep -v "manifest"`
do
	version=`cat $domain | grep -E "<version>" | sed -e "s:.*<version>::g" -e "s:</version>::g"`
	domain=`echo $domain | awk -F "joomla/joomla.xml" '{print $1}'`
	echo "$version $domain"
done
