<?php 
require_once('config.php');
print ("\n\n ".__DIR__ ."\n\n");	
require_once( __DIR__ .'/includes/Ansi.Color.class.php');
require_once( __DIR__ .'/includes/dbhandler.class.php');
$color = new Color();







	// Save all files to KAT-MV-DATA 
	// Find fields here: http://www.imdb.com/search/title
	$year='2016'; // Decrease in loop below
	while( --$year > 1970 ): 

			//$type='tv_movie'; 
			$type='feature'; 
			//$sort="alpha"; // sort by alpha because other orders might change // moviemeter 
			$sort="moviemeter,asc"; 
			//$start="-49"; // increase by 50 
			$start="-49"; // increase by 50 
			$pagesCounter="0"; // increase by ++ 


			// create folder for html files per year 
			if ( !file_exists(HTML_FILES_PATH.'/'.$year) ){ 
				print ("\n\nFile Not  exists"); 
				$mkdir = mkdir( HTML_FILES_PATH.'/'.$year, 0777, true); 
				if ( !$mkdir ) { 
					print ( "\nError creating folder\n" ); 
					exit();
				} else { 
					print ("Folder created ");
				}

			} else { 
				print ("\n\nFile Exist"); 
			} 

			while( ( $start += 50 ) < 1200 ): 

					$imdbLInk="http://www.imdb.com/search/title?sort=".$sort.",asc&start=".$start."&title_type=".$type."&year=".$year.",".$year; 
					//$start += 50; 
					++$pagesCounter;
			
					print ( "\n Link : \n" ); 
					print ( $imdbLInk ); 
					print ( " \n " );

					$htmlFile = HTML_FILES_PATH.'/'.$year.'/'.$year.'-'.$pagesCounter; // html file to save 
					if ( getHtmlFile( $imdbLInk, $htmlFile )) { 

						// append test results to text file  
						// $imdbFIle = fopen("imdb", "a"); 
						$foundMovies=false;
						$handle = fopen( $htmlFile, "r"); 
						if ( !$handle ) { print ("Error opening file"); } 
						while ( ($line = fgets($handle) ) !== false) { 
							if ( 
								strpos( $line, 'title=' ) !== false && 
								strpos( $line, '('.$year.')' ) !== false 
							) { 
								$foundMovies=true;
								$badHTML= $line; 
								$doc = new DOMDocument(); 
								$doc->encoding = 'UTF-8'; 
								$doc->loadHTML($badHTML); 
								$goodHTML = simplexml_import_dom($doc)->asXML(); 
								try { 
									$xml = new SimpleXMLElement($goodHTML); 
									$result = $xml->xpath('/*'); 
									//var_dump( $result ); 
									foreach ( $result[0] as $k=>$v) { 
										foreach ($v as $k1=>$v1) { 
											$currentLine=""; 
											if ( $k1 == 'a' ){ 
												//var_dump( $v1->attributes() ); 
												print ( $v1->attributes()[0] ); 
												$movieName = $v1->attributes()[1]; 
												//$movieName = str_replace ( "(".$year." TV Movie)", "", $movieName ); 
												$movieName = str_replace ( "(".$year.")", "", $movieName ); 
												$movieName = trim($movieName); 
												$movieImdb= trim (explode ("/",$v1->attributes()[0])[2]); 
												//(2015 TV Movie) 
												//$currentLine .= $v1->attributes()[1]; $currentLine .= "_"; $currentLine .= $v1->attributes()[0]; $currentLine .= "\n"; 
											} 
											//fwrite ($imdbFIle, $currentLine ); 

											print ( "\n\n".$movieImdb."\n\n" ); 
											//insertIntoMoviesList( $movieName, $movieImdb, $year, $dbh ); 
											//exit();
										}
									}
									//break;
								} catch (Exception $e ) { 
									var_dump( $e ); 
									exit();
								}
							}// if strpos
							else { 
								//$foundMovies=false;
							}
						} // while in html=
						if ( !$foundMovies ) { 
							//exit("\n\n Did not found movies in html \n\n "); 
							print ("\n\n Did not found any more movies in html. \n\n "); 
							break; 
						} 
						print ("\n Ok "); 

					} else { 
						print ("Error. Failed to get ");
						exit();
					} 
					//} // html file exists
			endwhile; 
	endwhile; // year 













function insertIntoMoviesList ( $moviename, $imdb, $year, $dbh ) { 

	$insertMovie="INSERT INTO movies_list_short ( moviename, imdb, year ) VALUES ( :moviename, :imdb, :year ) "; 
	if ( !$stmt = $dbh->dbh->prepare($insertMovie) ) { 
		var_dump ( $dbh->dbh->errorInfo() ); 
	} else { 

			$stmt->bindParam(':moviename', $moviename ); 
			$stmt->bindParam(':imdb', $imdb ); 
			$stmt->bindParam(':year', $year ); 
	
			if (!$stmt->execute() ){
				if ( in_array( 1062, $stmt->errorInfo() ) ){
					//print ($n."[#] Already pesisted");
					printColor("#","yellow");
				} else { 
					printColor ($n."[!]error","red+bold");
					var_dump($stmt->errorInfo());
					sleep(2);
					exit();
				}
			} else {
					printColor ("_[#]ok","green+bold");
			}
	}
}

function getHtmlFile( $url, $destination ) { 

	global $n; 
	//print("never got here"); 
	$urlTorrent = $url; 
	$ch = curl_init( $urlTorrent ); 
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
	curl_setopt($ch, CURLOPT_ENCODING ,""); 
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'); 
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
