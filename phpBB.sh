#!/bin/sh

# Zoeken naar phpBB instalaties:

mysql -ukevin -Djnet1771_phpBB -poget8A7Bb --skip-column-names -e"select config_value from phpbb_config where config_name='version';" | grep '[\d\.]*'

