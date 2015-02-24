<?php

function generateURL($pages, $urlImage)
{
	$url = "./generate.php?";
	$url .= implode('&amp;', array_map(function($key, $val) {
			return 'pages[' . urlencode($key) . ']=' . urlencode($val);
		},
		array_keys($pages), $pages)
	);
	
	$index = array_search($urlImage, $pages);
	$next = -1;
	$prev = -1;
	
	if($index + 1 < count($pages))
	{
		$next = $index + 1;
	}
	
	if($index - 1 >= 0)
	{
		$prev = $index - 1;
	}
	
	$url .= "&amp;current=".$index;
	$url .= "&amp;n=".$next;
	$url .= "&amp;p=".$prev;
	
	return $url;
}

function printImage(&$urlImage, $pages)
{
	echo("<td><div class=\"imgHolder\">");
	$url = generateURL($pages, $urlImage);
	echo("<a href=\"".$url."\">");
	echo("<img src=\"./images/thumbnails/".$urlImage."\"><span>".$urlImage."</a></span></div></td>");
}

function generateThumbnail(&$url)
{
	$query = "c:\imgmagick\convert -resize 100x144 images/".$url. " images/thumbnails/".$url;
	exec($query, $string);
}

$dir = "./images/";
$files = scandir($dir);

$images = array_values(array_diff(scandir($dir, SCANDIR_SORT_NONE), array(".", "..", "thumbnails")));

sort($images, SORT_NUMERIC);


echo("<html><link rel=\"stylesheet\" href=\"./style.css\"/><body style=\"background-color : yellowgreen;\"><table><tr>");

foreach($images as $v)
{
	if(strpos($v, ".jpg") !== false)
	{
		if(!file_exists('./images/thumbnails/'.$v))
		{
			generateThumbnail($v);
		}
		printImage($v, $images);
	}
}

echo("</tr></table></body></html>");
?>
