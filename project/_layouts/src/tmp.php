<body class="Page">

	<?
	include 'header.php';
	?>

	<div class="Page__content">
		<div class="Page__wrapper">

			<p>
				<?=b('Field')?>
			</p>

			<?=b('Btn', array(
				'tag' => 'a',
				'attr' => 'href="#"',
				'content' => b('Btn', array('content' => 'Ok'),true)
			))?>
			<?=b('Btn', array(
				'mod' => 'Btn--main'
			))?>

			<?=b('Menu', array(
				'mod' => 'Menu--horiz Menu--tab',
				'content' => array(
					'active' => '<a href="#" class="Menu__text">Первый пункт</a>',
					            '<a href="#" class="Menu__text">Второй пункт</a>',
					            '<a href="#" class="Menu__text">Третий пункт</a>'
				))
			)?>

		</div>
	</div>

	<?
	include 'footer.php';
	?>

	<?
	include 'foot.php';
	?>

</body>
