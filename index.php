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
	<link rel="stylesheet" href="<?php echo $FileIndexer->_path["project"]; ?>/assets/css/styles.css"/>
	<script src="<?php echo $FileIndexer->_path["project"]; ?>/assets/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo $FileIndexer->_path["project"]; ?>/assets/js/jquery.tablesorter.min.js"></script>
	<script src="<?php echo $FileIndexer->_path["project"]; ?>/assets/js/fileindexer.js"></script>
	<script src="<?php echo $FileIndexer->_path["project"]; ?>/assets/js/script.js"></script>

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Rammetto+One" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			
			<!-- SIDEBAR -->
			<aside class="col-md-3 col-md-push-9 sidebar">

				<!-- Header -->
				<header class="row sidebar-header">
					<span class="title">File_Indexer</span>
					<i class="pull-right fa fa-bars"></i>
				</header>

				<!-- Tree -->
				<ul class="row sidebar-tree">
					<li>
						<i class="fa fa-folder-open"></i>
						<span data-path="<?php echo $FileIndexer->_path["root"]; ?>">
							<?php echo $FileIndexer->_path["root"]; ?>

							<a href="<?php echo $FileIndexer->_path["project"] . "/" . $FileIndexer->_path["root"]; ?>">
								<i class="fa fa-arrow-left"></i>
							</a>
						</span>
						<ul>
							<?php $FileIndexer->listTree(); ?>
						</ul>
					</li>
				</ul>
			</aside>


			<!-- TOOLBAR -->
			<aside class="col-md-3 col-md-push-9 toolbar">

				<!-- Header -->
				<header class="row toolbar-header">
					<span class="title">Toolbar</span>
					<i class="pull-right fa fa-times"></i>
				</header>

				<!-- Section -->
				<section class="row toolbar-section">
					<div class="col-xs-12 search">
						<form>
							<input type="text" class="form-control" placeholder="Searching for...">
						</form>
					</div>
					<div class="col-xs-12 theme">
						<h2 class="title">Theme</h2>
						<div class="colors">
							<div data-color-light="#00cccc" data-color-dark="#009999"></div>
							<div data-color-light="#0086b3" data-color-dark="#007399"></div>
							<div data-color-light="#7575a3" data-color-dark="#666699"></div>
							<div data-color-light="#cc0099" data-color-dark="#b30086"></div>
							<div data-color-light="#e6004c" data-color-dark="#cc0044"></div>
							<div data-color-light="#ff471a" data-color-dark="#ff3300"></div>
							<div data-color-light="#fff0b3" data-color-dark="#ffeb99"></div>
						</div>
					</div>
				</section>
			</aside>


			<!-- BODY -->
			<section class="col-md-9 col-md-pull-3 body">

				<!-- Header -->
				<header class="row body-header">
					<div class="breadcrumb btn-group">
						<?php $FileIndexer->renderBreadcrumb(); ?>
					</div>
					<div class="pull-right back">
						<?php $FileIndexer->renderPreviousPath(); ?>
					</div>
				</header>

				<!-- Section -->
				<section class="row table-responsive body-section">
					<?php $FileIndexer->listContent(); ?>
				</section>
			</section>

		</div>
	</div>

	<!-- AJAX -->
	<?php if (isset($_POST["action"]) && $_POST["action"] == "list") { ?>
		<ul id="ajax" data-path="<?php echo $_POST["path"]; ?>">
			<?php $FileIndexer->listTree($_POST["path"]); ?>
		</ul>
	<?php } ?>
</body>
</html>