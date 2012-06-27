#!/bin/sh
if [[ -n $1 ]]; then
latestRelease=$1
else
latestRelease="2.6.4"
fi

if [[ -n $2 ]]; then
latestOldRelease=$2
else
latestOldRelease="1.5.27"
fi

left=`echo $latestRelease | awk -F '.' '{ print $1 }'`
middle=`echo $latestRelease | awk -F '.' '{ print $2 }'`
right=`echo $latestRelease | awk -F '.' '{ print $3 }'`

leftOld=`echo $latestOldRelease | awk -F '.' '{ print $1 }'`
middleOld=`echo $latestOldRelease | awk -F '.' '{ print $2 }'`
rightOld=`echo $latestOldRelease | awk -F '.' '{ print $3 }'`


for file in `locate libraries/joomla/version.php`
do

    masterversion=`cat $file | grep '$RELEASE' | sed -e "s/.*= '//g" -e "s/';//g"`
    subversion=`cat $file | grep '$DEV_LEVEL' | sed -e "s/.*= '//g" -e "s/';//g"`
    version=$masterversion"."$subversion
    domain=`echo $file | awk -F "libraries/joomla/version.php" '{print $1}'`
    echo "$version $domain"

done

for domain in `locate joomla.xml | grep -v "/plugins/" | grep -v "manifest"`
do
    version=`cat $domain | grep -E "<version>" | sed -e "s:.*<version>::g" -e "s:</version>::g"`

    domain=`echo $domain | awk -F "joomla/joomla.xml" '{print $1}'`
    echo "$version $domain"

done
