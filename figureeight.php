<?

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$lines = array();
$figures = array();
$sources = "";

$lines = array_merge($lines,getLines(35646,"The science and art of cooking may be divided","and are consequently the heaviest."));
$lines = array_merge($lines,getLines(71855,"The foot rule is used as a unit of measurement","the outlines of the object will “stand out.”"));
$lines = array_merge($lines,getLines(60598,"It sounds like a fairy-tale","which should be broiled until very well done."));
$lines = array_merge($lines,getLines(36513,"Three tumblers, a jug of water","the fault will not be yours."));
$lines = array_merge($lines,getLines(60687,"This trick must be frequently practiced","will appear to have changed their places."));
$lines = array_merge($lines,getLines(41669,"No mechanical toy is more interesting","closer to the lens than the center is."));
$lines = array_merge($lines,getLines(69989,"Did you ever think about","the used steam passes into the open air."));
$lines = array_merge($lines,getLines(42278,"In every household there are countless things","any ornamentation you may care to add."));
$lines = array_merge($lines,getLines(12655,"The magic hand made of wax","the tackle and drum below the floor."));
$lines = array_merge($lines,getLines(67768,"Normal air contains 79 per cent of nitrogen","by an infusion of tea or coffee."));
$lines = array_merge($lines,getLines(41839,"In the present state of our knowledge","shall be launched for their benefit."));
$lines = array_merge($lines,getLines(73645,"A convenient kitchen table","in the list given in the back of this book."));
$lines = array_merge($lines,getLines(26184,"The purpose of this paper is to characterize simple sabotage","Do not cooperate in salvage schemes."));

$dropped=0;
foreach ($lines as $key => $line)
{
	if (substr($line, 0, 4) == "<div")
	{
		array_push($figures,$line);
		array_splice($lines,$key-$dropped,1);
		$dropped++;
	}
}

shuffle($figures);

//$pointers = array_rand($lines, 3);
//$livepointer=rand(0,2);
$book = "";
$shownfigure = 0;
$saidfigure = 0;
$float="right";

$countdown = 3;
$wordcount = 0;
$chaptercount = 0;
$usedheadings = array();

while ($wordcount<50000)
{
	$chapter = rand(3,6);
	$chaptertext = "";
	$pointers = array_rand($lines, 3);
	$livepointer=rand(0,2);
	for ($j=0; $j<$chapter; $j++)
	{
		$chaptertext .= "<p>";
		$paragraph = rand(3,6);
		for ($k=0; $k<$paragraph; $k++)
		{
			$newline = $lines[$pointers[$livepointer]];
			
			$countdown--;
			if ($countdown<1) // add a figure
			{
				$countdown = 5+rand(1,6);
				$shownfigure++;
				$newline = $figures[$shownfigure]."".$newline." <i>(Fig.&nbsp;$shownfigure)</i>";
				$newline = preg_replace("/Figure XX/", "Figure ".$shownfigure, $newline);

				$newline = preg_replace("/float:somewhere/","float:$float",$newline);
				if ($float=="left") { $float="right"; } else { $float="left"; }

			}

			// chance of switching to another strand
			if (rand(1,2)==1) { $livepointer=rand(0,2); }
		
			// chance of changing the current strand to a different part of the corpus entirely
			if (rand(1,20)==1) { $pointers[$livepointer] = array_rand($lines); }

			$chaptertext .= $newline."\n";
			$pointers[$livepointer]++;
			
			if ($pointers[$livepointer] >= sizeof($lines)) { $pointers[$livepointer] = array_rand($lines); }
		
		}
		$chaptertext .= "</p>";
	}
	
	$heading = "";
	// find a chapter heading, longer words better
	
	for ($w=15; $w>3; $w--)
	{
		if (badTitle($heading,$usedheadings) && preg_match("/\b((a|an|the) \w{".$w.",20} \w{".$w.",20}) (with|on|under|into|inside|in|from) /",$chaptertext,$matches))
		{ $heading = $matches[1]; }
		if (badTitle($heading,$usedheadings) && preg_match("/\b(\w+ing (a|an|the) \w{".$w.",20}) (with|on|under|into|inside|in|from) /",$chaptertext,$matches))
		{ $heading = $matches[1]; }
		if (badTitle($heading,$usedheadings) && preg_match("/\b(\w+ing (a|an|the) \w{".$w.",20})[\.,;:]/",$chaptertext,$matches))
		{ $heading = $matches[1]; }
		if (badTitle($heading,$usedheadings) && preg_match("/\b((a|an|the) \w{".$w.",20} \w{".$w.",20})[\.,;:]/",$chaptertext,$matches))
		{ $heading = $matches[1]; }
		if (badTitle($heading,$usedheadings) && preg_match("/\b((a|an|the) \w{".$w.",20} (of|in|at) \w{".$w.",20})[\.,;:]/",$chaptertext,$matches))
		{ $heading = $matches[1]; }
	}
	if (badTitle($heading,$usedheadings) && preg_match("/\b((a|an|the) \w{9,20}) (with|on|under|into|inside|in|from) /",$chaptertext,$matches))
	{ $heading = $matches[1]; }
	
	if ($heading != "")
	{
		array_push($usedheadings,$heading);
		$heading = ucwords($heading);
		$heading = preg_replace("/ The /"," the ",$heading);
		$heading = preg_replace("/ A /"," a ",$heading);
		$heading = preg_replace("/ An /"," an ",$heading);
		$heading = preg_replace("/ Of /"," of ",$heading);
		$heading = preg_replace("/ In /"," in ",$heading);
		$heading = preg_replace("/ At /"," at ",$heading);
		$chaptercount++;
		$book .= "<h2>$chaptercount. $heading</h2>\n";
	}
	$book .= $chaptertext;
	$wordcount += str_word_count("$heading $chaptertext");
}

?>
<html>
<head><title>Figure Eight</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,400..900;1,400..900&family=IM+Fell+English:ital@0;1&display=swap" rel="stylesheet">
<style>
body {
	background-color: white;
	font-family: "Alegreya", serif;
	font-size: 1.2em;
	padding: 0em 25%;
}

.fig {
	max-width: 20%;
	min-width: 200px;
	margin: 0.5em 2em;
	padding: 1em;
	clear:both;
	border:1px solid #000;
	text-align:center;
	font-size:0.9em;
	font-weight:bold;
	font-family: "IM Fell English";
}

img { max-width:100%; }
	
h1 { font-family: "IM Fell English"; text-align: center; font-size: 4.0em; padding: 0.5em;}
h2 { padding: 2em 0em 0em 0em; clear:both; font-family: "IM Fell English"; }

.credits {
	font-size: 0.6em;
	margin: 8em 4em 2em 4em;
	border-top: 1px solid #000;
	padding: 2em;
	clear:both;
}

@media screen and (max-width: 700px) {
	body { padding:0em; }
	.fig { margin: 1em auto; float:none !important; }
}
  
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<h1>Figure Eight</h1>
<div>A <?=number_format($wordcount)?>-word book
generated by <a href="https://github.com/kevandotorg/nanogenmo-2024">a script</a>
written by <a href="https://kevan.org">Kevan Davis</a>
for <a href="https://github.com/NaNoGenMo/2024/">NaNoGenMo 2024</a>, using
text and images of public domain instructional books from <a href="https://www.gutenberg.org/">Project Gutenberg</a>.
</div>

<?=$book?>

<div class="credits">Source texts and figures:<ol><?=$sources?></ol></div>
</body>
</html>
<?

function badTitle($t,$past)
{
//	if (preg_match("/ (and|or|the|in|on|an|a|with)\b/",$t)) { return true; }
	if ($t == "") { return true; }
	if (in_array($t,$past)) { return true; }
	return false;
}

function getLines($gutid,$start,$end)
{
	global $sources;

	$goodlines = array();

	$text = file_get_contents("https://www.gutenberg.org/files/".$gutid."/".$gutid."-h/".$gutid."-h.htm");
	
	$text = preg_replace("/[\r\n]+/"," ",$text);
	
	// grab the title for the credits later
	if (preg_match("/<title>(.+)<\/title>/",substr($text, 0, 500),$matches)) { $sources .= "<li><a href=\"https://www.gutenberg.org/ebooks/".$gutid."\">".$matches[1]."</a>"; }
	
	// catch potential headings which lack punctuation (specifically in 12655, 36513)
	$text = preg_replace("/([A-Za-z]) *<\/(span|div|p|h1|h2|h3)>/","$1.</$2>",$text);
	
	$text = preg_replace("/<img[^>]+src=\"images\/([a-z0-9_\-]+\.(png|gif|jpg|jpeg))\"[^>]+>/","[IMAGE:$1]",$text);
	$text = strip_tags($text);
	$text = preg_replace("/\[IMAGE:([a-z0-9\._\-]+)\]/","<div style=\"float:somewhere;\" class=\"fig\"><img src=\"https://www.gutenberg.org/files/".$gutid."/".$gutid."-h/images/$1\"/><div class=\"caption\">Figure XX</div></div>.",$text);

	$text = str_replace("’", "'", $text);
	$text = str_replace("’", "'", $text);
	$text = preg_replace("/\.&mdash;/",". ",$text); // remove ugly cookbook quirk
	
	// trim down to the specified intro and outro
	//$text = preg_replace("/^.+".$start."/",$start,$text);
	//$text = preg_replace("/".$end.".+$/",$end,$text);
	//$text = preg_replace("/^.+(".$start.".+".$end.").+$/","$1",$text);
	$text = strstr($text,$start);
	$text = strstr($text,$end,true).$end;

	if ($text=="") { print "$gutid is null"; return array(); }

	// break into sentences by full stops, avoiding mid-sentence abbreviations
	$lines = preg_split("/(?<!mr.|mrs.|dr.|u.s.|u.k.| fig.| figs.| approx.| no.| hr.| m.| do.| i.| ii.| iii.| iv.| v.| vi.| vii.| viii.)(?<=[.?!])\s+/i", $text, -1);
		
	foreach($lines as $line)
	{
		// skip dull/shouty/caps sentences that may just be headings
		if (preg_match("/^[^ ]+ ?[^ ]+$/",$line)) { $line = ""; }
		if (preg_match("/^[^ ]+ [^ ]+ [^ ]+$/",$line)) { $line = ""; }
		if (preg_match("/\b[A-Z0-9,]+ [A-Z0-9,]+ [A-Z0-9,]+\b/",$line)) { $line = ""; }
		if (preg_match("/\b[A-Z]+\b.$/",$line)) { $line = ""; }
		if ($line == strtoupper($line)) { $line = ""; }
		if (preg_match("/^([A-Z][a-z,]+ )+(of |for |a |an |the |and |in |with |to |or )?([A-Z][a-z,]+ )*[A-Z][a-z,]+\.$/",$line)) { $line = ""; }
		
		// skip lists of numbers that are probably broken tables
		if (preg_match("/\d+ .+\d+ .+\d+/",$line)) { $line = ""; }
		
		// anything starting with a space or lowercase letter is likely useless
		if (preg_match("/^(&nbsp;| )/",$line)) { $line = ""; }
		if (preg_match("/^[a-z]/",$line)) { $line = ""; }

		// skip pre-existing references to figures and numbers
		if (preg_match("/\b(figure|fig|number|no)s?\.? \d+/i",$line)) { $line = ""; }
		
		// drop punctuation from figure lines
		$line = preg_replace("/>\./",">",$line);
		
		// drop inline footnotes and list headings
		$line = preg_replace("/\[\d+\]/","",$line);
		$line = preg_replace("/\(\d+\)/","",$line);

		if (preg_match("/^<div.+>$/",$line)) // images always good
		{ array_push($goodlines,$line); }
		elseif (preg_match("/^[A-Za-z0-9,;:=<>.'\"& \/\\\-]+[.?!]$/",$line) && count($goodlines) < 2000) // clean lines good, but stop at 2000 per book
		{ array_push($goodlines,$line); }
		//else 
		//{ print $line."\n"; }
	}
		
	return $goodlines;
}
	

?>
