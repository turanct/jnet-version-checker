#!/bin/sh
if [[ -n $1 ]]; then
        latestRelease=$1
else
        latestRelease="2.5.4"
fi

left=`echo $latestRelease | awk -F '.' '{ print $1 }'`
middle=`echo $latestRelease | awk -F '.' '{ print $2 }'`
right=`echo $latestRelease | awk -F '.' '{ print $3 }'`

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
cat $domain | grep -E "<version>" | sed -e "s:.*<version>::g" -e "s:</version>::g"
    #echo "Version: "$version @ $domain

    left2=`echo $version | awk -F '.' '{ print $1 }'`
    middle2=`echo $version | awk -F '.' '{ print $2 }'`
    right2=`echo $version | awk -F '.' '{ print $3 }'`

    if [[ $left -gt $left2 ]]; then
        echo "MajorVersion update! @ "$domain" ( "$latestRelease" > "$version" )"
    elif [[ -n $middle2 ]] && [[ $middle -gt $middle2 ]]; then
        echo "MediumVersion update!  @ "$domain" ( "$latestRelease" > "$version" )"
    elif [[ -n $right2 ]] && [[ $right -gt $right2 ]]; then
        echo "SmallVersion update!  @ "$domain" ( "$latestRelease" > "$version" )"
    fi

    #cat $domain | grep -E "<name>" | sed -e "s:.*<name>::g" -e "s:</name>::g"
done

#Major: Welke domeinnaam en versie
#Minor:
