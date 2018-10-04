<?php

class FileIndexer {

	public $_path;
	public $_requestUri;
	
	/**
	* Constructor
	*/
	public function __construct() {
		$this->_path = [
			"root" => "C:",
			"project" => dirname($_SERVER["SCRIPT_NAME"])
		];

		$this->_requestUri = $this->getRequestUri();
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
	* list folders of current $path
	*/
	public function listTree() {
		$path = $this->_path["root"];

		foreach (scandir($path) as $file) {
			if (substr($file, 0, 1) == ".") 
				continue;

			$file = $path . ($path == "/" ? "" : "/") . $file;

			if (file_exists($file)) {
				if (is_dir($file)) {
					?><li>
						<?php echo $this->getUrlEnd($file); ?>
					</li><?php 
				}
			}
		}
	}

}

$FileIndexer = new FileIndexer();
?>