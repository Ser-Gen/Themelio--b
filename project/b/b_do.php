<?php

@include 'b.php';



function rmFromImports($name)
{
	$fs = '@require "lib/'.$name.'/'.$name.'"';
	$f = file($GLOBALS['importer']);
	foreach ($f as $key => $value) {
		if ($fs === trim($value)) {
			unset($f[$key]);
		}
	}
	$fp = fopen($GLOBALS['importer'], 'a');
	$fr = '';
	foreach ($f as $key => $value) {
		$fr = $fr.$value;
	}
	file_put_contents($GLOBALS['importer'], $fr);
	fclose($fp);
}

function rmFolder($dir)
{
	$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it,
							 RecursiveIteratorIterator::CHILD_FIRST);
	foreach($files as $file) {
		if ($file->getFilename() === '.' || $file->getFilename() === '..') {
			continue;
		}
		if ($file->isDir()){
			rmdir($file->getRealPath());
		} else {
			unlink($file->getRealPath());
		}
	}
	rmdir($dir);
}


// местоположение файла с подключениеями стилей
$GLOBALS['importer'] = 'b_imports.styl';
$GLOBALS['lib'] = 'lib/';

// шаблон файла с функцией блока
$tmp_php = <<<T
<?php

function %name%(\$param=array(''))
{
	\$defaults = array(
		'mod' => '',
		'attr' => '',
		'tag' => 'span',
		'content' => 'Текст блока «%name%»'
	);

	\$options = array_merge(\$defaults, \$param);

	\$html = '<%tag% class="%name% %mod%" %attr%>%content%</%tag%>';

	foreach (\$options as \$key => \$value) {
		if (gettype(\$value)==='string') {
			\$html = str_replace('%'.\$key.'%', \$value, \$html);
		}
	}

	return \$html;
}

?>
T;

// шаблон файла со стилями блока
$tmp_css = <<<T
.%name% {

}
T;

// шаблон файла со скриптами блока
$tmp_js = <<<T
if ($('.%name%').length) {

}
T;

// шаблон файла с описанием блока
$tmp_doc = <<<T
Сюда можно добавить описание блока
T;


// если отправлена переменная добавления
if (isset($_POST['add-name'])) {
	$na = $_POST['add-name'];

	// если папки нет
	if (!file_exists('lib/'.$na)) {

		// создаём папку и все файлы блока внутри
		mkdir('lib/'.$na, 0777, true);

		if (isset($_POST['add-tech-tmpl'])) {
			$tmp_php = str_replace('%name%', $na, $tmp_php);
			$fp = fopen("lib/".$na."/".$na.".php","wb");
			fwrite($fp,$tmp_php);
			fclose($fp);
		}

		if (isset($_POST['add-tech-css'])) {
			$tmp_css = str_replace('%name%', $na, $tmp_css);
			$fp = fopen("lib/".$na."/".$na.".styl","wb");
			fwrite($fp,$tmp_css);
			fclose($fp);

			// если шаблон не будет использоваться
			if (!isset($_POST['add-tech-tmpl'])) {

				// подключаем стили блока сразу
				$f = file($GLOBALS['importer']);
				foreach ($f as $key => $value) {
					$f[$key] = trim($value);
				}
				$fs = '@require "lib/'.$na.'/'.$na.'"';
				$fp = fopen($GLOBALS['importer'], 'a');
				if (!in_array($fs, $f)){ 
					fputs($fp, $fs."\n");
					fclose($fp);
				}
			}
		}

		if (isset($_POST['add-tech-js'])) {
			$tmp_js = str_replace('%name%', $na, $tmp_js);
			$fp = fopen("lib/".$na."/".$na.".js","wb");
			fwrite($fp,$tmp_js);
			fclose($fp);
		}

		if (isset($_POST['add-tech-doc'])) {
			$fp = fopen("lib/".$na."/".$na.".md","wb");
			fwrite($fp,$tmp_doc);
			fclose($fp);
		}
	}
}

// если отправлена переменная удаления
if (isset($_POST['rm-name'])) {
	$nr = $_POST['rm-name'];

	// если папка есть
	if (file_exists('lib/'.$nr)) {
		if (isset($_POST['rm-css'])) {

			// удалить из стилей
			rmFromImports($nr);
		} else {

			// удалить из стилей
			rmFromImports($nr);

			// удалить файлы
			rmFolder('lib/'.$nr);
		}
	}
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Создатель</title>
	<link rel="stylesheet" href="/assets/css/main.css">
	<link rel="stylesheet" href="doc-styles.css">

	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.1/styles/tomorrow.min.css">
</head>
<body class="Page Page--doc">

	<div class="doc-Forms">
		<form method="post" class="doc-Form">
			<input type="text" name="add-name" autofocus placeholder="Имя блока" required="required">
			<label class="doc-Label"><input type="checkbox" name="add-tech-tmpl" checked> Разметка</label>
			<label class="doc-Label"><input type="checkbox" name="add-tech-css" checked> Стили</label>
			<label class="doc-Label"><input type="checkbox" name="add-tech-js" checked> Скрипты</label>
			<label class="doc-Label"><input type="checkbox" name="add-tech-doc" checked> Документация</label>
			<label class="doc-Label"><button class="doc-Submit">Создать</button></label>
		</form><!--
		
		--><form method="post" class="doc-Form doc-Form--rm">
			<input type="text" name="rm-name" placeholder="Имя блока" required="required">
			<label class="doc-Label"><input type="checkbox" name="rm-css" checked> только из стилей</label>
			<label class="doc-Label"><button class="doc-Submit">Удалить</button></label>
		</form>
	</div>

	<ul class="doc-Blocks">
<?php

// вывести список всех блоков
$files1 = scandir($GLOBALS['lib']);

foreach ($files1 as $key => $value) {
	if ((trim($value) !== '.') && (trim($value) !== '..')) {
		echo '<li class="doc-Block">';
		echo '<div class="doc-Block__name">'.$value.'</div>';
		if (file_exists($GLOBALS['lib'].$value.'/'.$value.'.md')) {
			echo '<div class="doc-Block__doc">'.file_get_contents($GLOBALS['lib'].$value.'/'.$value.'.md').'</div>';
		}
		if (file_exists($GLOBALS['lib'].$value.'/'.$value.'.php')) {
			echo '<div class="doc-Block-demo">';
			echo '<a href="#" class="doc-Block-demo__trigger">Пример</a>';
			echo '<div class="doc-Block-demo__content">'.b($value, array(), true).'</div>';
			echo '</div>';
		}
		echo '</li>';
	}
}

?>
	</ul>

	<script src="/assets/js/jquery.js"></script>
	<script src="/b/b.js"></script>
	<script src="/b/marked.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/highlight.js/8.1/highlight.min.js"></script>
	<script>

		$(document).ready(function() {
			$('pre code').each(function(i, block) {
				hljs.highlightBlock(block);
			});
		});

		// подготавливаем вывод документатора
		if ($('.doc-Block__doc').length) {
			$('.doc-Block__doc').each(function () {
				$(this).html(marked($(this).html()));
			})
		}

		// показываем скрытые примеры
		if ($('.doc-Block-demo__trigger').length) {
			$('.doc-Block-demo__trigger').click(function (e) {
				e.preventDefault();
				$(this).parents('.doc-Block-demo').find('.doc-Block-demo__content').slideToggle(200);
			})
		}

	</script>
</body>
</html>