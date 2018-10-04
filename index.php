<?php
	require_once("fileindexer.class.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>File_Indexer</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Elisa Theng"/>
	
	<link rel="stylesheet" href="<?php echo $FileIndexer->_path["project"]; ?>/assets/css/bootstrap.min.css"/>
	<script src="<?php echo $FileIndexer->_path["project"]; ?>/assets/js/jquery-3.3.1.min.js"></script>

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Rammetto+One" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
	<div>
		<h2>Render breadcrumb</h2>
		<?php $FileIndexer->renderBreadcrumb(); ?>
	</div>
	<div>
		<h2>List content</h2>
		<?php $FileIndexer->listContent(); ?>
	</div>
	<div>
		<h2>List tree</h2>
		<ul><?php $FileIndexer->listTree(); ?></ul>
	</div>
</body>
</html>