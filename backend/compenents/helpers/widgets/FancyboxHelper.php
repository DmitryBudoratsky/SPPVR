<?php

namespace backend\compenents\helpers\widgets;

use newerton\fancybox3\FancyBox;

/**
 * Description of FancyboxHelper
 *
 */
class FancyboxHelper
{
	static public function renderFancybox()
	{
		return FancyBox::widget();
	}
}
