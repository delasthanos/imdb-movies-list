<?php 
class SearchImdbYear{

	private $year; // Start from year. Decrease in loop below
	//imdb link parameters
	//$type='tv_movie'; 
	private $type; 
	//$sort="alpha"; // sort by alpha because other orders might change // moviemeter 
	private $sort; 
	private $start; // increase by 50 
	//$start="0"; // increase by 50 
	private $pagesCounter; // increase by ++ 

	function __construct( $year, $type, $sort, $start, $pagesCounter ) {
		print ("\nInitalizing search parameters.");
		$this->year=$year;
		$this->type=$type;
		$this->sort=$sort;
		$this->start=$start;
		$this->pagesCounter=$pagesCounter;
		$this->createFolders();
	}

	/*
		MAIN FUNCTION to START SEARCH 
	*/
	public function searchImdb(){

		print ($this->htmlFilename());
		$this->getHtmlSearchResults($this->htmlFilename()); // accepts destination to save filename
		$this->parseSearchresults($this->htmlFilename());
	}

	private function parseSearchresults( $filename ){

			// class name .lister-item-content
			$collectMovies=[]; // final array to return
			$currentMovie=[]; // redelare everytime in foreach loop below

			$classname="lister-item-content";
			$getLocal = file_get_contents($filename);
			$dom = new DOMDocument();
			@$dom->loadHTML($getLocal);
			// run xpath for the dom
			$xPath = new DOMXPath($dom);
			//$elements = $xPath->query("//a/@href");
			$nodes = $xPath->query("//*[contains(@class, 'lister-item-content')]"); // get all movies nodes

			foreach ( $nodes as $node ){ //lister-item-content
				//var_dump($node->nodeValue);
				$currentMovie=[];
				$currentMovie['moviename']='';
				$currentMovie['imdb']='';
				$currentMovie['rating']='';

				$h3 = $xPath->query('h3[@class="lister-item-header"]', $node);
				foreach ($h3 as $h){
					// Moviename and imdb code below
					$a = $xPath->query('a', $h);
					foreach ($a as $k){

						$currentMovie['moviename']=$k->nodeValue;
						$getImdbCode = explode("/",$k->getAttribute("href"))[2];
						//print ('<pre>');//var_dump($getImdbCode);//print ('</pre>');
						$currentMovie['imdb']=$getImdbCode;
						//var_dump( $k->nodeValue );
						//var_dump( $k->getAttribute("href") );
					}
				}
				//$rating = $xPath->query( 'div[@class="ratings-imdb-rating"]', $node);
				$ratings = $xPath->query( 'div[@class="ratings-bar"]/div/strong', $node);
				foreach ( $ratings as $rating ){
					
					$currentMovie['rating']=$rating->nodeValue;
					//var_dump($rating->nodeValue);
				}

				array_push($collectMovies, $currentMovie );

			}

			print ('<pre>');
			var_dump($collectMovies);
			print ('</pre>');

	}

	private function getHtmlSearchResults($destination){

		if (!file_exists($destination)){
			if ( getHtmlFile( $this->imdbLink(), $destination )) {
				print ("Got file");
			}
		}
	}

	private function imdbLink(){
		return "http://www.imdb.com/search/title?sort=".$this->sort.",&start=".$this->start."&title_type=".$this->type."&year=".$this->year.",".$this->year;
	}

	private function htmlFilename(){

		return HTML_FILES_PATH.'/'.$this->year.'/'.$this->year.'-'.$this->pagesCounter; // html file to save 
	}

	private function createFolders(){

		// create folder for html files per year 
		if ( !file_exists(HTML_FILES_PATH.'/'.$this->year) ){ 
			print ("\nFile doest not exist."); 
			$mkdir = mkdir( HTML_FILES_PATH.'/'.$this->year, 0777, true); 
			if ( !$mkdir ) { 
				print ( "\nError creating folder\n" ); 
				exit();
			} else { 
				print ("Folder created ");
			}

		} else { 
			print ("\n\nFile Exist"); 
		}
	}

}
?>
