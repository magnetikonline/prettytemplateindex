<?php
class PrettyTemplateIndex {

	const HTML_FILE_EXT = '*.html';


	public function __construct() {

		echo(
			$this->getPageHeader() .
			$this->getFileList() .
			'</table></body></html>'
		);
	}

	private function getPageHeader() {

		return <<<EOT
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />

	<title>Pretty Template Index</title>
	<style>
		body { background: #353c42;font: 62.5%/1 Georgia,Times,'Times New Roman',serif;margin: 60px 20px;padding: 0; }
		table { border-collapse: collapse;font-size: 1.6em;margin: 0 auto; }
		table th,table td { color: #000;padding: 8px;vertical-align: top; }
		table th { background: #8899a8;font-weight: bold;text-align: left; }
		table tr { background: #4a535b;-moz-transition: background 200ms linear;-o-transition: background 200ms linear;-webkit-transition: background 200ms linear;transition: background 200ms linear; }
		table tr:nth-child(odd) { background: #545f68; }
		table tr:hover { background: #bcd3e7; }
		table a { color: #000;text-decoration: none; }
		table a:hover { text-decoration: underline; }
		.notfound { text-align: center; }
	</style>
</head>

<body>

<table>
<tr>
	<th>Filename</th>
	<th>Title</th>
	<th>Size</th>
	<th>Last modified</th>
</tr>
EOT;
	}

	private function getFileList() {

		// check for files in same dir as script, otherwise try DOCUMENT_ROOT
		$fileList = glob(__DIR__ . '/' . self::HTML_FILE_EXT);
		$fileList = ($fileList)
			? $fileList
			: glob($_SERVER['DOCUMENT_ROOT'] . '/' . self::HTML_FILE_EXT);

		if (!$fileList) {
			// no templates found
			return '<tr><td class="notfound" colspan="4">No templates found</td></tr>';
		}

		// order alphabetically
		sort($fileList);

		$html = '';
		foreach ($fileList as $fileItem) {
			$fileItemHtml = htmlspecialchars(basename($fileItem));

			$html .=
				'<tr>' .
					'<td><a href="' . $fileItemHtml . '">' . $fileItemHtml . '</a></td>' .
					'<td>' . htmlspecialchars($this->getPageTitle($fileItem)) . '</td>' .
					'<td>' . filesize($fileItem) . '</td>' .
					'<td>' . date('Y-m-d H:i:s',filemtime($fileItem)) . '</td>' .
				'</tr>';
		}

		return $html;
	}

	private function getPageTitle($file) {

		return (preg_match('/<title>([^<]+)<\/title>/',file_get_contents($file),$matches))
			? trim($matches[1])
			: 'N/A';
	}
}


new PrettyTemplateIndex();