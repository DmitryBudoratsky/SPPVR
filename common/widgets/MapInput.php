<?php

namespace common\widgets;

use Yii;
use common\models\db\Settings;

class MapInput extends \yii\base\Widget {

    const MAP_TYPE_ROADMAP = 'ROADMAP';
    const MAP_TYPE_HYBRID = 'HYBRID';
    const MAP_TYPE_SATELLITE = 'SATELLITE';
    const MAP_TYPE_TERRAIN = 'TERRAIN';

    public $sensor = false;
    public $mapCanvas = 'map_canvas';
    public $language = 'en';
    public $width = '100%';
    public $height = '100%';
    public $center = 'Riia 184, Tartu, Tartu linn, Estonia';
    public $zoom = 12;
    public $mapType = 'ROADMAP';
    public $mapOptions = [];
    public $markerOptions = [];
    public $apiKey = null;

    public $countryInput = null;
    public $addressInput = 'address-input';
    public $latInput = 'lat-input';
    public $lngInput = 'lng-input';


    public function init() {
        if ($this->apiKey === null) {
            $this->apiKey = Yii::$app->params['googleApiKey'];
        }
        $this->sensor = $this->sensor ? 'true' : 'false';
        parent::init();
    }

    public function run() {

        return $this->render('mapInput');
    }
}
