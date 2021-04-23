<?php
    require_once("FileIndexer.class.php");
    $fileIndexer = new FileIndexer();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>File_Indexer</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Elisa Theng"/>
	
	<link rel="stylesheet" href="<?php echo $fileIndexer->path["project"]; ?>/assets/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo $fileIndexer->path["project"]; ?>/assets/css/styles.css"/>
	<script src="<?php echo $fileIndexer->path["project"]; ?>/assets/js/jquery-3.3.1.min.js"></script>
	<script src="<?php echo $fileIndexer->path["project"]; ?>/assets/js/jquery.tablesorter.min.js"></script>
	<script src="<?php echo $fileIndexer->path["project"]; ?>/assets/js/fileindexer.js"></script>
	<script src="<?php echo $fileIndexer->path["project"]; ?>/assets/js/script.js"></script>

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Rammetto+One" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
	<div class="container-fluid">
		<div class="row">

            <!-- SIDEBAR -->
            <aside class="col-md-3 col-md-push-9 fi-sidebar">
                <header class="row fi-sidebar__header">
                    <span class="title">My File Indexer</span>
                    <i class="pull-right fa fa-bars"></i>
                </header>
                <ul class="row fi-sidebar__tree">
                    <li class="tree-item__home">
                        <i class="fa fa-folder-open"></i>
                        <a href="<?php echo $fileIndexer->path["project"] . "/" . $fileIndexer->path["root"]; ?>">
                            <?php echo $fileIndexer->path["root"]; ?>
                        </a>
                        <ul><?php echo $fileIndexer->listTreeItems(); ?></ul>
                    </li>
                </ul>
            </aside>

            <!-- TOOLBAR -->
            <aside class="col-md-3 col-md-push-9 fi-toolbar">
                <header class="row fi-toolbar__header">
                    <span class="title">Toolbar</span>
                    <i class="pull-right fa fa-times"></i>
                </header>
                <section class="row fi-toolbar__section">
                    <div class="col-xs-12 fi-toolbar__section-search">
                        <form>
                            <input type="text" class="form-control" placeholder="Searching for...">
                        </form>
                    </div>
                    <div class="col-xs-12 fi-toolbar__section-theme">
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
            <div class="col-md-9 col-md-pull-3 fi-body">
				<header class="row fi-body__header">
					<div class="fi-body__header-breadcrumb btn-group">
                        <a href="<?php echo $fileIndexer->path['project']; ?>" class="btn"><i class="fa fa-home"></i></a>
						<?php foreach ($fileIndexer->listBreadcrumb() as $part) { ?>
                            <a href="<?php echo $part['href']; ?>" class="btn"><?php echo str_replace("%20", " ", $part['content']); ?></a>
                        <?php } ?>
					</div>
				</header>
				<section class="row table-responsive fi-body__section">
                    <?php if (file_exists($fileIndexer->requestUri) && is_dir($fileIndexer->requestUri)) { ?>
                        <table class="table table-hover tablesorter fi-body__section-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Last modified date</th>
                                    <th>Size (bytes)</th>
                                </tr>
                            </thead>
                            <tbody>
                    <?php } ?>

					<?php foreach ($fileIndexer->listItems() as $item) {
                        if (array_key_exists('dir', $item)) { ?>
                            <tr>
                                <td>
                                    <i class="<?php echo $item['dir']['icon']; ?>"></i>
                                    <a href="<?php echo $item['dir']['href']; ?>"><?php echo $item['dir']['content']; ?></a>
                                </td>
                                <td><?php echo $item['dir']['date']; ?></td>
                                <td><?php echo $item['dir']['size']; ?></td>
                            </tr>
                        <?php } else {
                            echo html_entity_decode($item['file']['html']);
                        }
                    } ?>

                    <?php if (file_exists($fileIndexer->requestUri) && is_dir($fileIndexer->requestUri)) { ?>
                            </tbody>
                        </table>
                    <?php } ?>
				</section>
			</div>
		</div>
	</div>
</body>
</html>