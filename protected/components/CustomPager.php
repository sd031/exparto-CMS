<?php
class CustomPager extends CLinkPager {
	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		if($hidden || $selected)
			$class.=' '.($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
		return '<li class="'.$class.'">'.CHtml::link('<span>'.$label.'</span>',$this->createPageUrl($page)).'</li>';
	}
}