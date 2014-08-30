<?php

// запоминаем, какие блоки подключены
$GLOBALS['cntxt'] = array('');

// местоположение файла с подключениеями стилей
$GLOBALS['importer'] = '../b/b_imports.styl';

function b($path='', $param=array(''), $return=false)
{

	// только если путь к блоку указан
	if ($path !== '')
	{

		// только один раз
		if (!isset($GLOBALS['cntxt'][$path]))
		{
			// подключаем функцию блока
			include 'lib/'.$path.'/'.$path.'.php';

			// подключаем стили блока
			// зависимости стилей должны разруливаться внутри стилей блока
			$f = file($GLOBALS['importer']);
			foreach ($f as $key => $value) {
				$f[$key] = trim($value);
			}
			$fs = '@require "lib/'.$path.'/'.$path.'"';
			$fp = fopen($GLOBALS['importer'], 'a');
			if (!in_array($fs, $f)){ 
				fputs($fp, $fs."\n");
				fclose($fp);
			}

			// запоминаем блок
			$GLOBALS['cntxt'][$path] = true;
		}
	}
	else
	{
		return false;
	}

	eval("\$html = \$path(\$param);");

	// в зависимости от параметров 
	if ($return) {

		// возвращать
		return $html;
	} else {

		// или выводить получившуюся вёрстку
		echo $html;
	}
}

?>
