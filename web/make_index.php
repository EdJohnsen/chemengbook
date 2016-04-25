<!-- Before we get to any php code, we allow the client to read some straight forward html -->
<!doctype html>
<html lang="en-us">
<head>
<!-- Here's a little bit of css to layout some very basic and imperfect paged index entries -->
<style type="text/css">
body {text-align:center; line-height:1.4;}
div#content {width:86%; margin-left:auto; margin-right:auto; clear:both; height:auto; width:100%; float:none; text-align:left;}
div.nothing {width:0; height:0;}
div {overflow:auto; width:40%; float:left; margin-left:2%; padding:0 0 30px 0;}
div.page {overflow:auto; width:100%; clear:both; float:none; border-bottom:3px solid black; margin-bottom:30px;}
</style>
<!-- Now we configure and set the mathjax (pulled from a cdn source) -->
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

<!-- the php code starts here -->
<?php
// the $echo_this variable will get filled later and then sent to the client.
$echo_this = "";
// the $filename variable will be called upon when we open the outline.txt file (externally, I  changed the .idx file to a .txt file)
$filename = "output.txt";

// these pattern and replacement arrays are used to clean out unwanted text from the outline.txt file and to convert some latex commands into more friendly mathjax formats
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

// now we go ahead and open the output.txt file and start streaming out its content, line by line, and then parsing it into a 2-d array
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

// now we loop through the 2-d array, appending its contents into the $echo_this variable
	// we make new pages and new columns (every 60 and 30 entries, respectively)
	// we use the keys from the first dimensions as the text for each entry
	// we use the keys from the second dimensions as the numerals for the pdf links for each entry
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
// finalize and echo the $echo_this variable
$echo_this .= "</div></div></div>";
echo "<br>" . $echo_this;
?>
</body>
</html>
