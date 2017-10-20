<?php 
require_once('config.php');
print ("\n".__DIR__ ."\n");
require_once( __DIR__ .'/includes/functions.php');
require_once( __DIR__ .'/includes/Ansi.Color.class.php');
require_once( __DIR__ .'/includes/dbhandler.class.php');
require_once( __DIR__ .'/includes/SearchImdbYear.class.php');
$color = new Color();
// Find fields here: http://www.imdb.com/search/title
$year='2015'; // Start from year. Decrease in loop below
//imdb link parameters
$type='feature'; 
$sort="moviemeter,asc"; 
$start="-49"; // increase by 50
$pagesCounter="0"; // increase by ++ 
//$imdbLInk="http://www.imdb.com/search/title?sort=".$sort.",asc&start=".$start."&title_type=".$type."&year=".$year.",".$year; 
while ( --$year > 1990 ):
	$searchImdb = new SearchImdbYear( $year, $type, $sort, $start, $pagesCounter );
	$searchImdb->searchImdb();
	unset($searchImdb);
endwhile;
exit("\nEND");
?>  
