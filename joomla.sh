#!/bin/sh

echo "\n********** Version 1 **********"
for file in `locate libraries/joomla/version.php`
do
    version=`cat $file | grep '$RELEASE' | sed -e "s/.*= '//g" -e "s/';//g"`
    versionSub=`cat $file | grep '$DEV_LEVEL' | sed -e "s/.*= '//g" -e "s/';//g"`
    result=$version"."$versionSub
    echo "Version: "$result @ $domain
done

echo "\n********** Version 2 **********"
for domain in `locate joomla.xml | grep -v "/plugins/" | grep -v "manifest"`
do
    cat $domain  | grep -E "<version>" | sed -e "s:.*<version>::g" -e "s:</version>::g"
    echo "Version: "$version @ domain
    #cat $domain  | grep -E "<name>" | sed -e "s:.*<name>::g" -e "s:</name>::g"
done

#Major: Welke domeinnaam en versie
#Minor:
