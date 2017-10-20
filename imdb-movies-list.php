<?php 
require_once('config.php');
print ("\n\n ".__DIR__ ."\n\n");
require_once( __DIR__ .'/includes/functions.php');
require_once( __DIR__ .'/includes/Ansi.Color.class.php');
require_once( __DIR__ .'/includes/dbhandler.class.php');
require_once( __DIR__ .'/includes/SearchImdbYear.class.php');
$color = new Color();
// Find fields here: http://www.imdb.com/search/title
$year='2015'; // Start from year. Decrease in loop below
//imdb link parameters
//$type='tv_movie'; 
$type='feature'; 
//$sort="alpha"; // sort by alpha because other orders might change // moviemeter 
$sort="moviemeter,asc"; 
$start="50"; // increase by 50 
//$start="0"; // increase by 50 
$pagesCounter="1"; // increase by ++ 
//$imdbLInk="http://www.imdb.com/search/title?sort=".$sort.",asc&start=".$start."&title_type=".$type."&year=".$year.",".$year; 
//print $imdbLInk;
//createFolders($year);
$searchImdb = new SearchImdbYear( $year, $type, $sort, $start, $pagesCounter );
$searchImdb->searchImdb();
// Currently for one year only
exit("\nEND");
?>  
