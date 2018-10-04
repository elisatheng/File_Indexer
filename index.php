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


			<!-- BODY -->
			<section class="col-md-9 col-md-pull-3 body">

				<!-- Header -->
				<header class="row body-header">
					<div class="breadcrumb btn-group">
						<?php $FileIndexer->renderBreadcrumb(); ?>
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