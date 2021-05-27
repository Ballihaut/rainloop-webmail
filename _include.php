<?php

// Name this file as "include.php" to enable it.

/**
 * @return string
 */
function __get_custom_data_full_path()
: string {
	return '';
	return '/var/external-rainloop-data-folder/'; // custom data folder path
}

/**
 * @return string
 */
function __get_additional_configuration_name()
: string {
	return '';
	return defined('APP_SITE') && 0 < strlen(APP_SITE) ? APP_SITE.'.ini' : ''; // additional configuration file name
}
