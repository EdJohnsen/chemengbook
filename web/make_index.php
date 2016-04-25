<!doctype html>
<html lang="en-us">
<head>
<style type="text/css">
body {text-align:center; line-height:1.4;}
div#content {width:86%; margin-left:auto; margin-right:auto; clear:both; height:auto; width:100%; float:none; text-align:left;}
div.nothing {width:0; height:0;}
div {overflow:auto; width:40%; float:left; margin-left:2%; padding:0 0 30px 0;}
div.page {overflow:auto; width:100%; clear:both; float:none; border-bottom:3px solid black; margin-bottom:30px;}
</style>
<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}
});
</script>
<script type="text/javascript" async
  src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML">
</script>
</head>
<body>

<?php

$echo_this = "";

// $start_time = microtime(true);

$filename = "output.txt";

// $echo_this .= "Last update unix time:" . filemtime($filename) . "<br>";

//$string = 'The quick brown fox jumps over the lazy dog.';
$patterns = array();
$patterns[0] = '/uuline/';
$patterns[1] = '/uline/';
$patterns[2] = '/\\\\indexentry{/';
$patterns[3] = '/mathaccentV {tilde}07E/';
$patterns[4] = '/mathaccentV {hat}05E/';
$patterns[5] = '/mathaccentV {dot}05F/';
$replacements = array();
$replacements[0] = 'underline{\\\\underline';
$replacements[1] = 'underline';
$replacements[2] = '';
$replacements[3] = 'tilde';
$replacements[4] = 'hat';
$replacements[5] = 'dot';


//echo preg_replace($patterns, $replacements, $string);

if ($stream = fopen('output.txt', 'r')) {
	while (!feof($stream)) {
		$line = fgets($stream);
		if ($line !== false) {
			//$valArray = explode(",",$line);
			$line = preg_replace($patterns, $replacements, $line);
			$line_end = substr($line,strripos($line, "}{")+2,-2);
			$line = substr($line, 0, strripos($line, "}{"));
			if(substr_count($line, "underline")<2){}
			else{$line = str_replace("}","}}",$line);}
			$arrays[$line][$line_end] = 1;
			//$echo_this .= $line . " " . $line_end . "<br>";
		}
	}
	fclose($stream);
}
//$end_time = microtime(true);
//$time_taken = ($end_time - $start_time);

//echo ceil($time_taken) . " seconds";
$counter = 0;
$echo_this .= "<div id='content'><div class='nothing'><div class='nothing'>";
foreach($arrays as $key=>$value){
	if($counter%60==0){$echo_this .= "</div></div><div class='page'><div class='nothing'>";}
	if($counter%30==0){$echo_this .= "</div><div>";}
	$echo_this .= $key . "( ";
	$comma = "";
	foreach($value as $kv=>$val){
		$echo_this .= $comma . "<a href='Chemical_Engineering_Book___MIT_Qualifying_Exam.pdf#page=" . $kv . "' target='_blank'>" . $kv . "</a>";
		$comma = ", ";
	}
	$echo_this .= " )<br>";
	$counter++;
}
$echo_this .= "</div></div></div>";
echo "<br>" . $echo_this;
?>
</body>
</html>