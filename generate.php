<?php

function printImage(&$url)
{
	echo("<div class=\"viewport\"><img src=\"./images/".$url."\"/></div>");
}

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

function printJS(&$images, &$next, &$prev)
{
	echo generateURL($images, $images[$next]);
	$string = "<script type=\"text/javascript\">";
	$string .= "
	document.onkeydown = checkKey;

	function checkKey(e)
	{
		e = e || window.event;
		
		if(e.keyCode == '39')
		{
			window.location = \"".generateURL($images, $images[$next])."\";
		}
		else if(e.keyCode == '37')
		{
			window.location = \"".generateURL($images, $images[$prev])."\";
		}
	}
	";
	echo($string);
	echo("</script>");
}

parse_str($_SERVER["QUERY_STRING"], $output);
//print_r($_SERVER);

echo("<html><link rel=\"stylesheet\" href=\"./view.css\"/>");
echo("<body style=\"background-color : yellowgreen;\">");

$images = $output['pages'];
$current = $output['current'];
$next = $output['n'];
$prev = $output['p'];
$index = array_search($output['current'], $output['pages']);

echo $next;
echo $prev;
echo $index;

if($next != -1 and $prev != -1)
{
	printJs($images, $next, $prev);
}
else if($next == -1)
{
	printJs($images, $current, $prev);
}
else
{
	printJs($images, $next, $current);
}

printImage($images[$current]);

echo("</body></html>");
?>