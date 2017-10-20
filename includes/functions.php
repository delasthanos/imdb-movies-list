<?php
function getHtmlFile( $url, $destination ) { 

	global $n; 
	//print("never got here"); 
	$urlTorrent = $url; 
	$ch = curl_init( $urlTorrent ); 
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
	curl_setopt($ch, CURLOPT_ENCODING ,""); 
	//curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 20); //timeout in seconds 
	//curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	//printColor( $n.$url, "white+bold" );
	$html = curl_exec($ch); 
	//var_dump($html);
	//$html = file_get_contents( $url ); 
	//$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"); 
	if ( !file_put_contents( $destination, $html ) ) { 
		return false;
	}
	else { 
		return true;
	}
} 
function printColor( $message, $c ) { 
	global $color;
	print ( $color->set($message,$c) );
}	
?>
