<?php include '../b/b.php'; ?>
<!doctype html>
<!--[if lte IE 7]> <html class="ielte9 ielte8 ielte7" lang="ru"> <![endif]-->
<!--[if IE 8]> <html class="ie8 ielte9 ielte8" lang="ru"> <![endif]-->
<!--[if IE 9]> <html class="ie9 ielte9" lang="ru"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="not-oldie" lang="ru"> <!--<![endif]-->

<?
include 'head.php';
?>

<?
if (isset($_GET['p'])) {
	include 'src/' . $_GET['p'] . '.php';
} else {
	include 'src/tmp.php';
}
?>

</html>