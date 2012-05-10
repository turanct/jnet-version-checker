for domain in `locate joomla/joomla.xml`
do
cat $domain  | grep -E "<version>" | sed -e "s:.*<version>::g" -e "s:</version>::g"
cat $domain  | grep -E "<name>" | sed -e "s:.*<name>::g" -e "s:</name>::g"
done

for file in `locate libraries/joomla/version.php`
do
version=`cat $file | grep '$RELEASE' | sed -e "s/.*= '//g" -e "s/';//g"`
versionSub=`cat $file | grep '$DEV_LEVEL' | sed -e "s/.*= '//g" -e "s/';//g"`
result=$version"."$versionSub
echo "Version: "$result
done


#Major: Welke domeinnaam en versie
#Minor:
