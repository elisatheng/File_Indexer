<?php

class FileIndexer
{
    private const EXTENSIONS = [
        'audio' => ['m4a', 'mid', 'mp3', 'mpa', 'ogg', 'wav', 'wma'],
        'data'  => ['pdf'],
        'image' => ['gif', 'ico', 'jpg', 'jpeg', 'png', 'tif', 'tiff'],
        'video' => ['avi', 'flv', 'm4v', 'mov', 'mp4', 'mpg'],
        'web'   => [
            'asp', 'aspx', 'cshtml', 'css', 'go', 'htaccess', 'htm', 'html', 'js', 'json', 'md', 'page',
            'php', 'sass', 'scss', 'sh', 'sql', 'txt', 'xhtm', 'xhtml', 'xml',
        ],
    ];

    public array $path;
    public string $requestUri;

    public function __construct()
    {
        $this->path = [
            'root' => (substr(strtolower(PHP_OS), 0, 3) === 'win' ? 'C:' : "/"),
            'project' => dirname($_SERVER['SCRIPT_NAME'])
        ];

        $this->requestUri = $this->getRequestUri();
    }

    public function listBreadcrumb(): iterable
    {
        if ($this->requestUri === "/") {
            return;
        }

        $breadcrumb = $this->path["project"];
        $requestUriParts = explode("/", $this->path['root'] . $this->requestUri);

        foreach (array_filter($requestUriParts) as $requestUriPart) {
            $breadcrumb .= ($breadcrumb === "") ? $requestUriPart : "/" . $requestUriPart;
            $breadcrumb = str_replace("/C:", '', $breadcrumb);

            yield [
                'href' => $this->convertUrlSpaces($breadcrumb),
                'content' => htmlentities($this->convertUrlSpaces($requestUriPart)),
            ];
        }
    }

    public function listTreeItems(string $targetPath=null, int $offset=0): string
    {
        $path = ($targetPath == null)
            ? $this->path["root"]
            : $targetPath;

        $requestUriParts = array_filter(explode('/', $this->requestUri));
        array_unshift($requestUriParts, $path);

        $html = '';

        foreach (scandir($path) as $file) {
            if (substr($file, 0, 1) == ".") {
                continue;
            }

            $file = $path . ($path === "/" ? "" : "/") . $file;

            if (!file_exists($file) || !is_dir($file)) {
                continue;
            }

            $dirName = $this->getUrlEnd($file);

            $html .= '<li>';
            $html .= '<i class="fa fa-folder"></i> ';
            $html .= '<a href="'.$this->path['project'].$file.'">'.$dirName.'</a>';

            if (isset($requestUriParts[$offset+1]) && $dirName === $requestUriParts[$offset+1]) {
                $html .= '<ul>' . $this->listTreeItems($file, $offset+1) . '</ul>';
            }

            $html .= '</li>';
        }

        return $html;
    }

    public function listItems(): iterable
    {
        $path = $this->requestUri;

        if (!file_exists($path)) {
            yield [
                'file' => [
                    'html' => htmlentities('<img src="' . $this->path["project"] . '/assets/img/404.png" class="error404">'),
                ]
            ];
            return;
        }

        if (is_dir($path)) {
            yield from $this->listItemsDir($path);
            return;
        }

        yield from $this->renderFile($path);
    }

    private function listItemsDir(string $path): iterable
    {
        foreach (scandir($path) as $file) {
            if (substr($file, 0, 1) == ".") {
                continue;
            }

            $filePath = $path . $file;

            if (!file_exists($filePath)) {
                return;
            }

            yield [
                'dir' => [
                    'icon' => $this->getIconClasses($filePath),
                    'href' => $this->convertUrlSpaces($this->path["project"] . $filePath . (!is_file($filePath) ? "/" : null)),
                    'content' => $this->getUrlEnd($filePath) . (!is_file($filePath) ? "/" : null),
                    'size' => (is_file($filePath)) ? filesize($filePath) : disk_total_space($filePath),
                    'date' => date("d/m/Y H:i:s", filemtime($filePath)),
                ]
            ];
        }
    }

    private function renderFile(string $path): iterable
    {
        $fileExtension = $this->getExtension($path);

        if (in_array($fileExtension, self::EXTENSIONS['web'])) {
            yield [
                'file' => [
                    'html' => htmlentities('<pre style="height:100%;overflow-y:scroll;">' . htmlentities(file_get_contents($path)) . '</pre>'),
                ]
            ];
            return;
        }

        if (
            in_array($fileExtension, self::EXTENSIONS['data'])
            || in_array($fileExtension, self::EXTENSIONS['audio'])
            || in_array($fileExtension, self::EXTENSIONS['image'])
            || in_array($fileExtension, self::EXTENSIONS['video'])
        ) {
            yield [
                'file' => [
                    'html' => htmlentities('<iframe src="file://' . $path .'" height="100%" width="100%"></iframe>'),
                ]
            ];
            return;
        }

        yield [
            'file' => [
                'html' => htmlentities('<p>Sorry, this file is not supported by File Indexer</p>'),
            ]
        ];
    }

    private function convertUrlSpaces(string $path): string
    {
        return str_replace(" ", "%20", $path);
    }

    private function getExtension(string $path): ?string
    {
        if (!is_file($path)) {
            return null;
        }

        $pathInfo = pathinfo($path);
        return $pathInfo['extension'] ?? null;
    }

    private function getIconClasses(string $file): string
    {
        if (!is_file($file)) {
            return 'fa fa-folder';
        }

        $fileExtension = $this->getExtension($file);

        if (in_array($fileExtension, self::EXTENSIONS["audio"])) {
            return 'fa fa-file-audio-o';
        }

        if (in_array($fileExtension, self::EXTENSIONS["data"])) {
            return 'fa fa-file-pdf-o';
        }

        if (in_array($fileExtension, self::EXTENSIONS["image"])) {
            return 'fa fa-file-image-o';
        }

        if (in_array($fileExtension, self::EXTENSIONS["video"])) {
            return 'fa fa-file-movie-o';
        }

        if (in_array($fileExtension, self::EXTENSIONS["web"])) {
            return 'fa fa-file-code-o';
        }

        return 'fa fa-file-text-o';
    }

    private function getRequestUri(): string
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestUri = str_replace($this->path['project'], '', $requestUri);

        return ($requestUri !== '')
            ? str_replace("%20", ' ', $requestUri)
            : $this->path['root'];
    }

    private function getUrlEnd(string $path)
    {
        $path_array = explode("/", $path);
        return end($path_array);
    }
}
