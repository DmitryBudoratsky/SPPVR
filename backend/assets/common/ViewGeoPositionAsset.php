<?php

namespace backend\assets\common;

use backend\assets\AppAsset;

/**
 * View geo position asset bundle.
 */
class ViewGeoPositionAsset extends AppAsset
{
	public $css = [
		'css/geoPosition.css',
	];

	
	public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyAQPnocflT5SFVh1lQnlpWFSAhfdMUwxsM',
		'js/view-geo-position.js'
	];
}