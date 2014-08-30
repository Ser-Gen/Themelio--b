<?php

function Field($param=array(''))
{
	$defaults = array(
		'mod' => '',
		'type' => 'text'
	);

	$options = array_merge($defaults, $param);

	$html = '<input class="Field %mod%" type="%type%">';

	foreach ($options as $key => $value) {
		if (gettype($value)==='string') {
			$html = str_replace('%'.$key.'%', $value, $html);
		}
	}

	return $html;
}

?>