<?php

namespace backend\assets\common;

use backend\assets\AppAsset;

/**
 * Update geo position asset bundle.
 */
class UpdateGeoPositionAsset extends AppAsset
{
	public $css = [
		'css/geoPosition.css',
	];

	public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyAQPnocflT5SFVh1lQnlpWFSAhfdMUwxsM',
		'js/update-geo-position.js'
	];
}