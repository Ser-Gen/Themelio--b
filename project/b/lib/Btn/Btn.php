<?php

function Btn($param=array(''))
{
	$defaults = array(
		'tag' => 'button',
		'mod' => '',
		'attr' => '',
		'content' => 'Текст кнопки'
	);

	$options = array_merge($defaults, $param);

	$html = '<%tag% class="Btn %mod%" %attr%>%content%</%tag%>';

	foreach ($options as $key => $value) {
		if (gettype($value)==='string') {
			$html = str_replace('%'.$key.'%', $value, $html);
		}
	}

	return $html;
}

?>