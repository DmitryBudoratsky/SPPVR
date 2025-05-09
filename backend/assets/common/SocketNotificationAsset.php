<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\assets\common;


use backend\assets\AppAsset;

/**
 * Description of SocketNotificationAsset
 */
class SocketNotificationAsset extends AppAsset
{
    public $css = [];

    public $js = [
        'js/socket-notification.js'
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}