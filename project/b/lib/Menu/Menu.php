<?php

function Menu($param=array(''))
{
  $defaults = array(
  	'content' => array('Элемент 1', 'Элемент 2')
  );

  $options = array_merge($defaults, $param);

	$html = '<ul class="Menu %mod%">';

  foreach ($options['content'] as $key => $value) {
    if ($key === 'active') {
      $html = $html.'<li class="Menu__item active">'.$value.'</li>';
    } else {
      $html = $html.'<li class="Menu__item">'.$value.'</li>';
    }
  }

  $html = $html.'</ul>';


  foreach ($options as $key => $value) {
    if (gettype($value)==='string') {
      $html = str_replace('%'.$key.'%', $value, $html);
    }
  }

  return $html;
}

?>