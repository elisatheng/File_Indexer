<?php

class FileIndexer {

	public $_extensions;
	public $_path;
	public $_requestUri;
	
	/**
	* Constructor
	*/
	public function __construct() {
		$this->_extensions = [
			"audio" => ["m4a", "mid", "mp3", "mpa", "ogg", "wav", "wma"],
			"data" => ["pdf"],
			"image" => ["gif", "jpg", "jpeg", "png", "tif", "tiff"],
			"video" => ["avi", "flv", "m4v", "mov", "mp4", "mpg"],
			"web" => ["asp", "aspx", "cshtml", "css", "go", "htaccess", "htm", "html", "js", "json", "page", "php", "sass", "scss", "sh", "sql", "txt", "xhtm", "xhtml", "xml"]
		];
		
		$this->_path = [
			"root" => "C:",
			"project" => dirname($_SERVER["SCRIPT_NAME"])
		];

		$this->_requestUri = $this->getRequestUri();
	}

	/**
	* Get extension of file
	*
	* @param {string} $path
	* @return extension of $path, else false
	*/
	public function getExtension($path) {
		if (is_file($path)) {
			$pathInfo = pathinfo($path);
			return (isset($pathInfo["extension"])) ? $pathInfo["extension"] : false;
		}
		return false;
	}

	/**
	* Get query string from url
	* Return root path if no query string
	*
	* @return $queryString
	*/
	public function getRequestUri() {
		$requestUri = $_SERVER["REQUEST_URI"];
		$requestUri = str_replace($this->_path["project"], "", $requestUri);

		return ($requestUri !== "") 
			? str_replace("%20", " ", $requestUri)
			: $this->_path["root"];
	}

	/**
	* Get last part of url
	*
	* @param $path
	* @return end of $path
	*/
	public function getUrlEnd($path) {
		$path_array = explode("/", $path);
		return end($path_array);
	}

	/**
	* Display list of files into a table if $this->_requestUri is a directory
	* display content depending its type if is a file
	* else render error 404 image
	*/
	public function listContent() {
		$path = $this->_requestUri;

		if (file_exists($path)) {
			if (is_dir($path)) { ?>
				<table class="table table-hover tablesorter">
					<thead>
						<tr>
							<th>Name</th>
							<th>Size (bytes)</th>
							<th>Last modified date</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach (scandir($path) as $file) {
							if (substr($file, 0, 1) == ".") 
								continue;

							$file = ($path == "/") ? ($path . $file) : ($path . $file);
							$fileUrl = $file;
							if (is_dir($fileUrl))
								$fileUrl .= "/";

							if (file_exists($file)) { ?>
								<tr>
									<td>
										<?php $this->renderIcon($file); ?>
										<a href="<?php echo $this->_path["project"] . $fileUrl; ?>">
											<?php echo (is_file($file)) ? $this->getUrlEnd($file) : $this->getUrlEnd($file) . "/"; ?>
										</a>
									</td>
									<td>
										<?php echo (is_file($file)) ? filesize($file) : disk_total_space($file); ?>
									</td>
									<td>
										<?php echo date("d/m/Y H:i:s", filemtime($file)); ?>
									</td>
								</tr>
							<?php }
						} ?>
					</tbody>
				</table>
			<?php } else {
				$fileExtension = $this->getExtension($path);

				if (in_array($fileExtension, $this->_extensions["web"])) {
					?><pre style="height:100%;overflow-y:scroll;"><?php echo htmlentities(file_get_contents($path)); ?></pre><?php
				}
				else if (in_array($fileExtension, $this->_extensions["data"]) || in_array($fileExtension, $this->_extensions["audio"]) || in_array($fileExtension, $this->_extensions["image"]) || in_array($fileExtension, $this->_extensions["video"])) {
					?><iframe src="<?php echo 'file:///C:' . $path; ?>" height="100%" width="100%"></iframe><?php
				}
				else {
					echo "Sorry, this file is not supported by File_Indexer :/";
				}
			}
		}
		else {
			?><img src="<?php echo $this->_path["project"]; ?>/assets/img/404.png" class="error404"><?php
		}
	}

	/**
	* Define $path depending $subpath value
	* list folders of current $path if $subpath is null
	* list subfolders if the first part of $_requestUri matched with the current $file if $_requestUri exists
	*
	* @param {string} $subpath null
	*	for the subfolders of $_requestUri
	*/
	public function listTree($subpath=null) {
		$path = ($subpath == null)
			? (isset($_POST["path"]) && $_POST["path"] != null) 
				? $_POST["path"] 
				: $this->_path["root"]
			: $subpath;

		foreach (scandir($path) as $file) {
			if (substr($file, 0, 1) == ".") 
				continue;

			$file = $path . ($path == "/" ? "" : "/") . $file;

			if (file_exists($file)) {
				if (is_dir($file)) { ?>
					<li>
						<i class="fa fa-folder"></i>
						<span data-path="<?php echo str_replace("//", "/", $file) . "/"; ?>">
							<?php echo $this->getUrlEnd($file); ?>

							<a href="<?php echo $this->_path["project"] . "/" . str_replace("C:/", "", str_replace("//", "/", $file)); ?>">
								<i class="fa fa-arrow-left"></i>
							</a>
						</span>
						<ul>
							<?php
							if ($this->_requestUri !== "/") {
								$subpath = "C:" . $this->_requestUri;

								if (strpos($subpath, $file) !== false)
									$this->listTree($file);
							}
							else if (!isset($_POST["path"])) {
								if ($subpath == null) {
									if (strpos($this->_requestUri, $file) !== false)
										$this->listTree($file);
								}
								else if (file_exists($this->_requestUri)) {
									$subpath_array = explode("/", $subpath);
									$requestUri_array = explode("/", $this->_requestUri);

									foreach ($subpath_array as $subpath_key => $subpath_value) {
										if ($subpath_array[$subpath_key] == $requestUri_array[$subpath_key]) 
											unset($requestUri_array[$subpath_key]);
									}

									$requestUri_array = array_values($requestUri_array);

									if (isset($requestUri_array[0]) && $file == $subpath . "/" . $requestUri_array[0])
										$this->listTree($file);
								}
							}
							?>
						</ul>
					</li>
				<?php }
			}
		}
	}

	/**
	* Loop through array to display each part of $_requestUri with link
	*/
	public function renderBreadcrumb() {
		$breadcrumb = $this->_path["project"];
		$requestUri_array = explode("/", "C:" . $this->_requestUri);

		if ($this->_requestUri == "/") {
			?><a href="<?php echo $breadcrumb; ?>" class="btn">C:</a><?php
		}
		else {
			foreach ($requestUri_array as $index => $path) {
				$breadcrumb .= ($breadcrumb == "") ? $path : "/" . $path;
				$breadcrumb = str_replace("/C:", "", $breadcrumb);

				?><a href="<?php echo $breadcrumb; ?>" class="btn">
					<?php echo str_replace("%20", " ", $path); ?>
				</a><?php
			}
		}
	}

	/**
	* Display the icon matching with the filetype
	*
	* @param {string} $file
	*/
	public function renderIcon($file) {
		if (is_file($file)) {
			$fileExtension = $this->getExtension($file);

			if (in_array($fileExtension, $this->_extensions["audio"])) {
				?><i class="fa fa-file-audio-o"></i><?php
			}
			else if (in_array($fileExtension, $this->_extensions["data"])) {
				?><i class="fa fa-file-pdf-o"></i><?php
			}
			else if (in_array($fileExtension, $this->_extensions["image"])) {
				?><i class="fa fa-file-image-o"></i><?php
			}
			else if (in_array($fileExtension, $this->_extensions["video"])) {
				?><i class="fa fa-file-movie-o"></i><?php
			}
			else if (in_array($fileExtension, $this->_extensions["web"])) {
				?><i class="fa fa-file-code-o"></i><?php
			}
			else {
				?><i class="fa fa-file-text-o"></i><?php
			}
		}
		else {
			?><i class="fa fa-folder"></i><?php
		}
	}

	/**
	* Display $_requestUri without the last part
	*/
	public function renderPreviousPath() {
		$requestUri = "C:" . substr($this->_requestUri, 0, strlen($this->_requestUri) - 1);

		if ($lastSlash_pos = strrpos($requestUri, "/")) {
			if ($lastSlash_pos !== false) { 
				$path = str_replace("C:", "", substr($requestUri, 0, $lastSlash_pos));

				?><a href="<?php echo $this->_path["project"] . $path . "/"; ?>">
					<i class="fa fa-arrow-left"></i> Back
				</a><?php 
			}
		}

	}

}

$FileIndexer = new FileIndexer();
?>