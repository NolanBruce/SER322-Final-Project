<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style -->
    <link href="bootstrap/css/style.css" rel="stylesheet" >
    
	<title>SQL Movie Browser</title>
    
    <style type = "text/css">
		.btn-link{
			border:none;
  			outline:none;
			background:none;
			cursor:pointer;
			color:#0000EE;
			padding:0;
			text-decoration:underline;
			font-family:inherit;
			font-size:inherit;
		}
	</style>
</head>

<body>

	<?php // Start PHP Script
	
	// Extract the key and values from the post function
	extract( $_POST );
	if ( !isset( $searchRadio ) )
		$searchRadio = 'title';
	$actorSearch = ( isset( $searchRadio ) && $searchRadio == 'actor' );
	$directorSearch = ( isset( $searchRadio ) && $searchRadio == 'director' );
	$producerSearch = ( isset( $searchRadio ) && $searchRadio == 'producer' );
	$genreSearch = ( isset( $searchRadio ) && $searchRadio == 'genre' );
	$titleSearch = ( isset( $searchRadio ) && $searchRadio == 'title' );
	
	?>
    <div class='container'>
        <form method='post' action='index.php'>
          <div class='page-header text-center'>
            <h1>SQL Movie Browser</h1>
          </div>
          <div class='row'>
            <div class='12'>
              <div class='checkbox text-center'>
                <h3>Search by ...</h3>
                <label class='checkbox-inline'><input type='radio' name='searchRadio' value='actor' <?php if ( $actorSearch == true ) echo "checked='checked'"; ?> > Actor</label>
                <label class='checkbox-inline'><input type='radio' name='searchRadio' value='director' <?php if ( $directorSearch == true ) echo "checked='checked'"; ?> > Director</label>
                <label class='checkbox-inline'><input type='radio' name='searchRadio' value='producer' <?php if ( $producerSearch == true ) echo "checked='checked'"; ?> > Producer</label>
                <label class='checkbox-inline'><input type='radio' name='searchRadio' value='genre' <?php if ( $genreSearch == true ) echo "checked='checked'"; ?> > Genre</label>
                <label class='checkbox-inline'><input type='radio' name='searchRadio' value='title' <?php if ( $titleSearch == true ) echo "checked='checked'"; ?> > Movie Title</label>
              </div>
            </div>
          </div>
          <div class='row'>
              <div class='form-group text-center'>
               
                <input type='text' class='form-control' name='search_input' placeholder='Jim Carrey'> <br></br>
                 <button type='submit' class='btn btn-primary btn-md' name='search_by_query'>Search</button>
              </div>
          </div>
          <div class='row'>
            <h4 class='text-center'>Or browse category by letter</h4>
            <div class='text-center'>
             <button type='submit' name='search_by_letter' value='#' class='btn-link'>#</button>
             <button type='submit' name='search_by_letter' value='A' class='btn-link'>A</button>
             <button type='submit' name='search_by_letter' value='B' class='btn-link'>B</button>
             <button type='submit' name='search_by_letter' value='C' class='btn-link'>C</button>
             <button type='submit' name='search_by_letter' value='D' class='btn-link'>D</button>
             <button type='submit' name='search_by_letter' value='E' class='btn-link'>E</button>
             <button type='submit' name='search_by_letter' value='F' class='btn-link'>F</button>
             <button type='submit' name='search_by_letter' value='G' class='btn-link'>G</button>
             <button type='submit' name='search_by_letter' value='H' class='btn-link'>H</button>
             <button type='submit' name='search_by_letter' value='I' class='btn-link'>I</button>
             <button type='submit' name='search_by_letter' value='J' class='btn-link'>J</button>
             <button type='submit' name='search_by_letter' value='K' class='btn-link'>K</button>
             <button type='submit' name='search_by_letter' value='L' class='btn-link'>L</button>
             <button type='submit' name='search_by_letter' value='M' class='btn-link'>M</button>
             <button type='submit' name='search_by_letter' value='N' class='btn-link'>N</button>
             <button type='submit' name='search_by_letter' value='O' class='btn-link'>O</button>
             <button type='submit' name='search_by_letter' value='P' class='btn-link'>P</button>
             <button type='submit' name='search_by_letter' value='Q' class='btn-link'>Q</button>
             <button type='submit' name='search_by_letter' value='R' class='btn-link'>R</button>
             <button type='submit' name='search_by_letter' value='S' class='btn-link'>S</button>
             <button type='submit' name='search_by_letter' value='T' class='btn-link'>T</button>
             <button type='submit' name='search_by_letter' value='U' class='btn-link'>U</button>
             <button type='submit' name='search_by_letter' value='V' class='btn-link'>V</button>
             <button type='submit' name='search_by_letter' value='W' class='btn-link'>W</button>
             <button type='submit' name='search_by_letter' value='X' class='btn-link'>X</button>
             <button type='submit' name='search_by_letter' value='Y' class='btn-link'>Y</button>
             <button type='submit' name='search_by_letter' value='Z' class='btn-link'>Z</button>
            </div>
          </div>
        </form>
      <div class='table-responsive'>
          <table class='table table-striped'>
            <thead>
              <tr>
                <th>Rating</th>
                <th>Movie Title</th>
                <th>Genre(s)</th>
                <th>Summary</th>
                <th>Actor(s)</th>
                <th>Director(s)</th>
                <th>Producer(s)</th>
                <th>Released</th>
              </tr>
            </thead>
            <tbody> 
                
	<?php			
	// The real work Begins here!!
	$iserror = false;
	$host = "localhost";
	$user = "root";
	$password = "";	
	$dbname = "smdb";
	
	// Grab the MSQL Query from passed SQL string
	function createTableFromFilmResults( $filmResults ) {
		
		// Loop through the film results grabbing the info
		while ( $film = mysql_fetch_assoc( $filmResults ) ) {
				
			// Print the starting row
			print( "<tr>" );
			
			// Print the rating column
			$rating_sum = 0;
			$rating_count = 0;
			$sql = "SELECT rating 
					FROM ratings 
					WHERE filmID = " . $film['filmID'] ;
			$rating_result = mysql_query( $sql );
			if ( $rating_result ) {
				while ( $rating = mysql_fetch_assoc( $rating_result ) ) {
					$rating_sum += $rating['rating'];
					$rating_count += 1;
				}					
				// Print rating
				if ( $rating_count > 0 )
					print( "<td>". round(($rating_sum / $rating_count), 1) . "</td>" );
				else
					print( "<td>UKN</td>" );
			}
			else
				print( "<td>UKN</td>" );				
			
			// Print the Title
			print( "<td>" . $film['title'] . "</td>" );
			
			// Print the genres
			$sql = "SELECT genres.genreType 
					FROM film_genres 
					JOIN genres
					ON film_genres.genreID = genres.genreID 
					WHERE film_genres.filmID = " . $film['filmID'] ;
			$genres_result = mysql_query( $sql );
			if ( $genres_result ) {
				$genre_array = array();
				while ( $genre = mysql_fetch_assoc( $genres_result ) ) {
					$genre_array[] = $genre['genreType'];
				}
				print( "<td>" . implode(", ", $genre_array) . "</td>" ); 
			}
			else
				print( "<td>UKN</td>" );
			
			// Print the synopsis
			print( "<td>" . $film['summary'] . "</td>" );
			
			// Print the Actors
			$sql = "SELECT actors.firstName, actors.lastName 
					FROM actor_roles 
					JOIN actors
					ON actor_roles.actorID = actors.actorID 
					WHERE actor_roles.filmID = " . $film['filmID'] ;
			$actors_result = mysql_query( $sql );
			if ( $actors_result ) {
				$actors_array = array();
				while ( $actor = mysql_fetch_assoc( $actors_result ) ) {
					$actors_array[] = $actor['firstName'] . " " . $actor['lastName'];
				}
				print( "<td>" . implode(", ", $actors_array) . "</td>" ); 
			}
			else
				print( "<td>UKN</td>" );
				
			// Print the Directors
			$sql = "SELECT directors.firstName, directors.lastName 
					FROM directed_by 
					JOIN directors
					ON directed_by.directorID = directors.directorID 
					WHERE directed_by.filmID = " . $film['filmID'] ;
			$directors_result = mysql_query( $sql );
			if ( $directors_result ) {
				$directors_array = array();
				while ( $director = mysql_fetch_assoc( $directors_result ) ) {
					$directors_array[] = $director['firstName'] . " " . $director['lastName'];
				}
				print( "<td>" . implode(", ", $directors_array) . "</td>" ); 
			}
			else
				print( "<td>UKN</td>" );
				
			// Print the Producers
			$sql = "SELECT producers.firstName, producers.lastName 
					FROM produced_by 
					JOIN producers
					ON produced_by.producerID = producers.producerID 
					WHERE produced_by.filmID = " . $film['filmID'] ;
			$producers_result = mysql_query( $sql );
			if ( $producers_result ) {
				$producers_array = array();
				while ( $producer = mysql_fetch_assoc( $producers_result ) ) {
					$producers_array[] = $producer['firstName'] . " " . $producer['lastName'];
				}
				print( "<td>" . implode(", ", $producers_array) . "</td>" ); 
			}
			else
				print( "<td>UKN</td>" );
				
			// Print the date released
			print( "<td>" . substr($film['releaseDate'], 0, 4) . "</td>" ); 
			
			// Print the end row
			print( "</tr>" );
				
		}
	}
	
	// How did we post; by letter or by search function
	if ( isset( $search_by_letter ) ) {
		
		// Setup MySQL Connection
		if ( !( $database = mysql_connect( $host, $user, $password ) ) )
			print( "Error connecting to the Database..." . mysql_error( $database ) );
					
		// Connected Create or Open Schema
		if ( !mysql_select_db( $dbname, $database ) ) 
			print( "Error connecting to the Database..." . mysql_error( $database ) );
		
		$search_letter = $search_by_letter;
		$sql = '';
				
		// No we need to create the search based on the radio buttons
		switch ( $searchRadio ) {
			case "actor":
				if ( $search_letter == '#' )
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT actor_roles.filmID FROM actor_roles WHERE actor_roles.actorID IN 
						( SELECT actors.actorID FROM actors WHERE actors.lastName REGEXP '^[0-9]+' )
						) ORDER BY films.title";
				else
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT actor_roles.filmID FROM actor_roles WHERE actor_roles.actorID IN 
						( SELECT actors.actorID FROM actors WHERE actors.lastName LIKE '" . $search_letter . "%' )
						) ORDER BY films.title";				
				break;
			case "director":
				if ( $search_letter == '#' )
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT directed_by.filmID FROM directed_by WHERE directed_by.directorID IN 
						( SELECT directors.directorID FROM directors WHERE directors.lastName REGEXP '^[0-9]+' )
						) ORDER BY films.title";
				else
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT directed_by.filmID FROM directed_by WHERE directed_by.directorID IN 
						( SELECT directors.directorID FROM directors WHERE directors.lastName LIKE '" . $search_letter . "%' )
						) ORDER BY films.title";
				break;
			case "producer":
				if ( $search_letter == '#' )
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT produced_by.filmID FROM produced_by WHERE produced_by.producerID IN 
						( SELECT producers.producerID FROM producers WHERE producers.lastName REGEXP '^[0-9]+' )
						) ORDER BY films.title";
				else
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT produced_by.filmID FROM produced_by WHERE produced_by.producerID IN 
						( SELECT producers.producerID FROM producers WHERE producers.lastName LIKE '" . $search_letter . "%' )
						) ORDER BY films.title";
				break;
			case "genre":
				if ( $search_letter == '#' )
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT film_genres.filmID FROM film_genres WHERE film_genres.genreID IN 
						( SELECT genres.genreID FROM genres WHERE genres.genreType REGEXP '^[0-9]+' )
						) ORDER BY films.title";
				else
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT film_genres.filmID FROM film_genres WHERE film_genres.genreID IN 
						( SELECT genres.genreID FROM genres WHERE genres.genreType LIKE '" . $search_letter . "%' )
						) ORDER BY films.title";
				break;
			case "title":
			default:
				if ( $search_letter == '#' )
					$sql = "SELECT * FROM films WHERE title REGEXP '^[0-9]+' ORDER BY films.title";
				else
					$sql = "SELECT * FROM films WHERE title LIKE '" . $search_letter . "%' ORDER BY films.title";
				break;
		}
		
		// Make the query
		$result = mysql_query( $sql );
		
		// Did we get something
		if ( mysql_num_rows($result) <= 0 ) {
			print( "<tr><td>Nothing found using those search paramaters...</td></tr>" );
		}
		else {
			// We have data now loop through the movies grabbing the different stuff we want
			createTableFromFilmResults( $result );
		}
		
	}
	else if ( isset( $search_by_query ) ) {
		// Setup MySQL Connection
		if ( !( $database = mysql_connect( $host, $user, $password ) ) )
			print( "Error connecting to the Database..." . mysql_error( $database ) );
					
		// Connected Create or Open Schema
		if ( !mysql_select_db( $dbname, $database ) ) 
			print( "Error connecting to the Database..." . mysql_error( $database ) );
		
		$search_query = $search_input;
		$search_exploded = explode( " ", $search_query );
		$searchParameters = count( $search_exploded );
		$sql = '';
				
		// No we need to create the search based on the radio buttons
		switch ( $searchRadio ) {
			case "actor":
				if ( $searchParameters > 1 )
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT actor_roles.filmID FROM actor_roles WHERE actor_roles.actorID IN 
						( SELECT actors.actorID FROM actors WHERE actors.firstName LIKE '" . $search_exploded[0] . "%' AND actors.lastName LIKE '" . $search_exploded[1] . "%' )
						) ORDER BY films.title";		
				else
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT actor_roles.filmID FROM actor_roles WHERE actor_roles.actorID IN 
						( SELECT actors.actorID FROM actors WHERE actors.firstName LIKE '" . $search_exploded[0] . "%' OR actors.lastName LIKE '" . $search_exploded[0] . "%' )
						) ORDER BY films.title";	
				break;
			case "director":
				if ( $searchParameters > 1 )
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT directed_by.filmID FROM directed_by WHERE directed_by.directorID IN 
						( SELECT directors.directorID FROM directors WHERE directors.firstName LIKE '" . $search_exploded[0] . "%' AND directors.lastName LIKE '" . $search_exploded[1] . "%' )
						) ORDER BY films.title";		
				else
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT directed_by.filmID FROM directed_by WHERE directed_by.directorID IN 
						( SELECT directors.directorID FROM directors WHERE directors.firstName LIKE '" . $search_exploded[0] . "%' OR directors.lastName LIKE '" . $search_exploded[0] . "%' )
						) ORDER BY films.title";	
				break;
			case "producer":
				if ( $searchParameters > 1 )
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT produced_by.filmID FROM produced_by WHERE produced_by.producerID IN 
						( SELECT producers.producerID FROM producers WHERE producers.firstName LIKE '" . $search_exploded[0] . "%' AND producers.lastName LIKE '" . $search_exploded[1] . "%' )
						) ORDER BY films.title";
				else
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT produced_by.filmID FROM produced_by WHERE produced_by.producerID IN 
						( SELECT producers.producerID FROM producers WHERE producers.firstName LIKE '" . $search_exploded[0] . "%' OR producers.lastName LIKE '" . $search_exploded[0] . "%' )
						) ORDER BY films.title";
				break;
			case "genre":
					$sql = "SELECT * FROM films WHERE filmID IN 
						( SELECT film_genres.filmID FROM film_genres WHERE film_genres.genreID IN 
						( SELECT genres.genreID FROM genres WHERE genres.genreType LIKE '" . $search_query . "%' )
						) ORDER BY films.title";
				break;
			case "title":
			default:
					$sql = "SELECT * FROM films WHERE title LIKE '" . $search_query . "%'  ORDER BY films.title";
				break;
		}
		
		// Make the query
		$result = mysql_query( $sql );
		
		// Did we get something
		if ( mysql_num_rows($result) <= 0 ) {
			print( "<tr><td>Nothing found using those search paramaters...</td></tr>" );
		}
		else {
			// We have data now loop through the movies grabbing the different stuff we want
			createTableFromFilmResults( $result );
		}
	}
	else if ( isset( $submit_rating ) ) {  // This is the code for the rating input

		// Setup MySQL Connection
		if ( !( $database = mysql_connect( $host, $user, $password ) ) )
			print( "Error connecting to the Database..." . mysql_error( $database ) );
		else
		//print( "Logged in.");
					
		// Connected Create or Open Schema
		if ( !mysql_select_db( $dbname, $database ) ) 
			print( "Error connecting to the Database..." . mysql_error( $database ) );
		else
		//print( "Connected." );

		//search for film and save result
		$sql = "SELECT * FROM films WHERE title = '" . $title . "'";
		$valid = mysql_query( $sql );

		//check if any results were returned
		if ( mysql_num_rows($valid) < 1 ) {
			//if not, inform the user
			print( "<tr><td>Nothing found using those search paramaters...</td></tr>" );
		}
		else if ( ($rating > 10) OR ($rating < 0) ) {
			//if the rating is not within the acceptable parameters, inform the user
			print( "<tr><td>Enter a rating between 0 and 10...</td></tr>" );
		}
		else {
			//if both prior checks are passed, enter rating into database
			$sql = "SELECT filmID FROM films WHERE title = '" . $title . "'";
			$result = mysql_query( $sql );
			while($row = mysql_fetch_assoc( $result )) {
        		$filmID = $row['filmID'];
   			}
   			$sql = "INSERT INTO ratings (filmID,rating) VALUES ($filmID, $rating)";
			mysql_query( $sql );
			createTableFromFilmResults( $valid );
		}		
	}
	else if(isset($StaticQuery)){
		// Setup MySQL Connection
		if ( !( $database = mysql_connect( $host, $user, $password ) ) )
			print( "Error connecting to the Database..." . mysql_error( $database ) );
					
		// Connected Create or Open Schema
		if ( !mysql_select_db( $dbname, $database ) ) 
			print( "Error connecting to the Database..." . mysql_error( $database ) );
		
		switch ( $StaticQuery ) {
			case "Query1":				
				$sql = "SELECT DISTINCT films.*
						FROM actor_roles
						JOIN actors
						on actor_roles.actorID = actors.actorID
						JOIN films
						ON actor_roles.filmID = films.filmID
						Join film_genres
						ON films.filmID = film_genres.filmID
						JOIN genres
						ON film_genres.genreID = genres.genreID
						JOIN directed_by
						on directed_by.filmID = films.filmID
						JOIN directors
						on directors.directorID = directed_by.directorID
						where actors.firstName = 'Tom' AND actors.lastName = 'Cruise' AND 
						genres.genreType = 'Science Fiction' ORDER BY films.title";
				break;
			case "Query2":
				$sql = "SELECT DISTINCT films.filmID, films.title, films.runningTime, films.releaseDate, films.summary, films.popularity
						FROM films, genres, film_genres, directed_by, directors
						WHERE film_genres.genreID=genres.genreID
							AND film_genres.filmID=films.filmID
							AND directed_by.directorID=directors.directorID
							AND directed_by.filmID=films.filmID
							AND genreType='Drama'
							AND releaseDate LIKE '%2016%' ORDER BY films.title";
				break;
			case "Query3":
				$sql = "SELECT DISTINCT films.filmID, films.title, films.runningTime, films.releaseDate, films.summary, films.popularity
						FROM films, actors, actor_roles, directed_by, directors
						WHERE actors.actorID=actor_roles.actorID
							AND actor_roles.filmID=films.filmID
							AND directed_by.directorID=directors.directorID
							AND directed_by.filmID=films.filmID
							AND directors.firstName='Steven'
							AND directors.lastName='Spielberg'
							AND title NOT IN (SELECT title
												FROM films, actors, actor_roles
												WHERE actor_roles.filmID=films.filmID
												AND actors.actorID=actor_roles.actorID
												AND firstName='Harrison'
												AND lastName='Ford') 
							ORDER BY films.title";
				break;
			case "Query4":
				$sql = "SELECT DISTINCT films.filmID, films.title, films.runningTime, films.releaseDate, films.summary, films.popularity
						FROM actor_roles
						JOIN actors
						on actor_roles.actorID = actors.actorID
						JOIN films
						ON actor_roles.filmID = films.filmID
						Join film_genres
						ON films.filmID = film_genres.filmID
						JOIN genres
						ON film_genres.genreID = genres.genreID
						JOIN directed_by
						on directed_by.filmID = films.filmID
						JOIN directors
						on directors.directorID = directed_by.directorID
						where actors.firstName = 'Christian' AND actors.lastName = 'Bale' AND 
						genres.genreType = 'Drama' ORDER BY films.title";
				break;
			case "Query5":
				$sql = "SELECT DISTINCT films.filmID, films.title, films.runningTime, films.releaseDate, films.summary, films.popularity
						FROM actor_roles
						JOIN actors
						on actor_roles.actorID = actors.actorID
						JOIN films
						ON actor_roles.filmID = films.filmID
						Join film_genres
						ON films.filmID = film_genres.filmID
						JOIN genres
						ON film_genres.genreID = genres.genreID
						JOIN directed_by
						ON directed_by.filmID = films.filmID
						JOIN directors
						ON directors.directorID = directed_by.directorID
						WHERE directors.firstName = 'Chris' AND genres.genreType = 	'Comedy'
						AND actors.firstName = 'Channing'
						AND actor_roles.role LIKE '%Superman%' ORDER BY films.title";
				break;
			case "Query6":
			$sql="SELECT DISTINCT films.filmID, films.title, films.runningTime, films.releaseDate, films.summary, films.popularity FROM actor_roles
					JOIN actors
					on actor_roles.actorID = actors.actorID
					JOIN films
					ON actor_roles.filmID = films.filmID
					Join film_genres
					ON films.filmID = film_genres.filmID
					JOIN genres
					ON film_genres.genreID = genres.genreID
					JOIN directed_by
					on directed_by.filmID = films.filmID
					JOIN directors
					on directors.directorID = directed_by.directorID
					WHERE actors.hometown LIKE '%Illinois%'
					AND films.releaseDate LIKE '%201%'
					AND genres.genreType = 'Drama' ORDER BY films.title";
		break;
		}
		
		// Make the query. 
		$result = mysql_query( $sql );
		
		//Display the query
		createTableFromFilmResults($result);

	}
				
	// Print the rest of the Forms
	?>  
            </tbody>
          </table>
      </div>
      <div class='row'>
        <h4 class='text-center'>Have a different opinion? Enter the title and your rating!</h4>
        <form method='post' action='index.php'>
          <div class='form-group text-center'>
            <label>Title:<input type='text' name='title' id='opinion-title' placeholder='Dumb and Dumber To'></label>
            <label>Rating:<input type='text' name='rating' id='opinion-rating' placeholder='1.0'></label><br>
            <button type='submit' name='submit_rating' value='submit_rating' class='btn btn-primary btn-md' href='#'>Submit Rating</button>
          </div>
        </form>
      </div>
      
      <div class='form-group text-center'>
      	<form method='post' action='index.php'>
      		<div class='page-header text-center'>
				<h2>Static Queries</h2>
			</div>
        <div align="center">
          <table width='988' style='border:2px solid black;'>
            <tr>
              <th style='border:1px solid black;'><div align="center">'English' Search</div></th>
              <th style='border:1px solid black;'><div align="center">SQL Query</div></th> 
              <th style='border:1px solid black;'><div align="center">Run Query</div></th>
            </tr>
            <tr>
              <td style='border:1px solid black;'><div align="center">Science Fiction Movies that star Tom Cruise</div></td>
              <td style='border:1px solid black;'><div align="center">SELECT DISTINCT films.* FROM actor_roles JOIN actors ON actor_roles.actorID = actors.actorID JOIN films ON actor_roles.filmID = films.filmID JOIN film_genres ON films.filmID = film_genres.filmID JOIN genres ON film_genres.genreID = genres.genreID JOIN directed_by ON directed_by.filmID = films.filmID JOIN directors ON directors.directorID = directed_by.directorID WHERE actors.firstName = 'Tom' AND actors.lastName = 'Cruise' AND genres.genreType = 'Science Fiction' ORDER BY films.title</div></td> 
              <td style='border:1px solid black;'><div align="center">
                <button type='sumbit' name='StaticQuery' id='Query1' value='Query1'>Execute</button>
              </div></td>
            </tr>
            <tr>
              <td style='border:1px solid black;'><div align="center">Movies with directors who directed a drama that was released in 2016</div></td>
              <td style='border:1px solid black;'><div align="center">SELECT DISTINCT films.filmID, films.title, films.runningTime, films.releaseDate, films.summary, films.popularity
FROM films, genres, film_genres, directed_by, directors
WHERE film_genres.genreID=genres.genreID
AND film_genres.filmID=films.filmID
AND directed_by.directorID=directors.directorID
AND directed_by.filmID=films.filmID
AND genreType='Drama'
AND releaseDate LIKE '%2016%'</div></td> 
              <td style='border:1px solid black;'><div align="center">
                <button type='sumbit' name='StaticQuery' id='Query2' value='Query2'>Execute</button>
              </div></td>
            </tr>
            <tr>
              <td style='border:1px solid black;'><div align="center">All movies that were directed by Steven Spielberg but don’t star Harrison Ford</div></td>
              <td style='border:1px solid black;'><div align="center">SELECT DISTINCT films.filmID, films.title, films.runningTime, films.releaseDate, films.summary, films.popularity
                FROM films, actors, actor_roles, directed_by, directors
                WHERE actors.actorID=actor_roles.actorID
                AND actor_roles.filmID=films.filmID
                AND directed_by.directorID=directors.directorID
                AND directed_by.filmID=films.filmID
                AND directors.firstName='Steven'
                AND directors.lastName='Spielberg'
                AND title NOT IN (SELECT title
                FROM films, actors, actor_roles
                WHERE actor_roles.filmID=films.filmID
                AND actors.actorID=actor_roles.actorID
                AND firstName='Harrison'
              AND lastName='Ford');</div></td> 
              <td style='border:1px solid black;'><div align="center">
                <button type='sumbit' name='StaticQuery' id='Query3' value='Query3'>Execute</button>
              </div></td>
            </tr>
            <tr>
                        <tr>
              <td style='border:1px solid black;'><div align="center">Movies with actors from Illinois that came out in the 2010’s</div></td>
              <td style='border:1px solid black;'><div align="center">SELECT DISTINCT films.filmID, films.title, films.runningTime, films.releaseDate, films.summary, films.popularity
		FROM actor_roles
		JOIN actors
		on actor_roles.actorID = actors.actorID
		JOIN films
		ON actor_roles.filmID = films.filmID
		Join film_genres
		ON films.filmID = film_genres.filmID
		JOIN genres
		ON film_genres.genreID = genres.genreID
		JOIN directed_by
		on directed_by.filmID = films.filmID
		JOIN directors
		on directors.directorID = directed_by.directorID
		WHERE actors.hometown LIKE '%Illinois%'
		AND films.releaseDate LIKE '%201%'
		AND genres.genreType = 'Drama';
</div></td> 
              <td style='border:1px solid black;'><div align="center">
                <button type='sumbit' name='StaticQuery' id='Query6' value='Query6'>Execute</button>
              </div></td>
            </tr>
            <tr>
              <td style='border:1px solid black;'><div align="center">Movies with Christian Bale that are Dramas</div></td>
              <td style='border:1px solid black;'><div align="center">SELECT DISTINCT films.title, genres.genreType,
 		actors.firstName, actors.lastName, directors.firstName,
 		directors.lastName
		FROM actor_roles
		JOIN actors
		on actor_roles.actorID = actors.actorID
		JOIN films
		ON actor_roles.filmID = films.filmID
		Join film_genres
		ON films.filmID = film_genres.filmID
		JOIN genres
		ON film_genres.genreID = genres.genreID
		JOIN directed_by
		on directed_by.filmID = films.filmID
		JOIN directors
		on directors.directorID = directed_by.directorID
		where actors.firstName = 'Christian' AND actors.lastName = 'Bale' AND 
		genres.genreType = 'Drama'</div></td> 
              <td style='border:1px solid black;'><div align="center">
                <button type='sumbit' name='StaticQuery' id='Query4' value='Query4'>Execute</button>
              </div></td>
            </tr>
            <tr>
              <td style='border:1px solid black;'><div align="center">Movies with a director with the first name of chris that is a comedy with an actors first name of Channing and actor role of superman</div></td>
              <td style='border:1px solid black;'><div align="center">SELECT DISTINCT films.title, genres.genreType,
 		actors.firstName, actors.lastName, directors.firstName,
 		directors.lastName
		FROM actor_roles
		JOIN actors
		on actor_roles.actorID = actors.actorID
		JOIN films
		ON actor_roles.filmID = films.filmID
		Join film_genres
		ON films.filmID = film_genres.filmID
		JOIN genres
		ON film_genres.genreID = genres.genreID
		JOIN directed_by
		on directed_by.filmID = films.filmID
		JOIN directors
		on directors.directorID = directed_by.directorID
		WHERE directors.firstName = 'Chris' AND genres.genreType = 	'Comedy'
		AND actors.firstName = 'Channing'
  	        AND actor_roles.role LIKE '%Superman%'</div></td> 
              <td style='border:1px solid black;'><div align="center">
                <button type='sumbit' name='StaticQuery' id='Query5' value='Query5'>Execute</button>
              </div></td>
            </tr>
          </table>
        </div>
       </form>
      </div>
      
</div>
  
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src='js/bootstrap.min.js'></script>

</body>
</html>