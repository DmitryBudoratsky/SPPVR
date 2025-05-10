<?php

namespace backend\compenents\helpers;

use newerton\fancybox3\FancyBox;

/**
 * Description of FancyboxHelper
 *
 */
class ancyboxHelper
{
	static public function renderFancybox()
	{
		return FancyBox::widget();
	}
}
