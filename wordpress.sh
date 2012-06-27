#!/bin/sh

# Locate wp-includes/version.php
for file in `locate wp-includes/version.php`
do 
	# Declare array
	declare -a output

	# Extract version from wp-includes/version.php
	output[1]=`cat $file | grep 'wp_version = ' | sed -e "s/.*= '//g" | sed -e "s/';//g"`

	# extract dir
	output[2]=`echo $file | sed -e "s/wp-includes\/version.php//g"`

        # extract domain
        #output[3]=`echo $file | sed -e "s/\/var\/www\/vhosts\///g" | sed -e "s/\/.*//g"`

	#output
	echo ${output[@]}
done
