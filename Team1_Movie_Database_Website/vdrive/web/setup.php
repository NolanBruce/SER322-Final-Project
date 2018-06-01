<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>SMDB - Database Setup</title>
	</head>

	<body>
		<?php // Start PHP Script
		
		set_time_limit(120);
		if (ob_get_level() == 0) ob_start();
		
			extract( $_POST );
			$iserror = false;
			$message = "";	
			$host = "localhost";
			$user = "root";
			$password = "";	
			$dbname = "smdb";
			
			// Show the form
			print("
			<form id='form1' name='form1' method='post' action='setup.php'>
				<center>
  				<h1>
    				<label>Database Setup for SQL Movie Database</label>
  				</h1>
  				<br />
  				<label>Click the button below to populate the blank movie database with data from the provided JSON files. Ensure this is only done once.					</label>
  				<br />
  				<br />
  					<input name='submit' type='submit' value='Import Data' />
					<br />
					<br />
					<input name='clear' type='submit' value='Clear Data' />
  				</center>
  				<br />
  				<br />
			</form>");
			
			// Handle the submit button
			if ( isset( $submit ) ) {
				
				print( "<center> Opening MySQL Connection... </center>" );
				// Setup MySQL Connection
				if ( !( $database = mysql_connect( $host, $user, $password ) ) )
					die( "Could not connect to database" );
					
				// Connected Create or Open Schema
				if ( !mysql_select_db( $dbname, $database ) ) {
					// Create the db as it doesn't exist
					$sql = "CREATE DATABASE $dbname";
					if ( mysql_query( $sql, $database ) ) {
						print( "<center> Database: $dbname created... </center>" );
						mysql_select_db( $dbname, $database );
					}
					else
						print( "<center> Error with DB creation: " . mysql_error( $database ) . " </center>" );
				}
				
				ob_flush();
        		flush();
				
				print( "<center> Creating tables if they do not exist... </center>" );
				
				ob_flush();
        		flush();
				
				// Now we create the tables if they do not exist
				// Films
				$table = "films";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				filmID INT(20) UNSIGNED PRIMARY KEY,
				title VARCHAR(100),
				runningTime INT(10),
				releaseDate VARCHAR(100),
				summary TEXT(500),
				popularity DOUBLE(10, 2)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				// Genres
				$table = "genres";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				genreID INT(10) UNSIGNED PRIMARY KEY,
				genreType VARCHAR(100)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				// Film Genres
				$table = "film_genres";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				genreID INT(10),
				filmID INT(10)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				// Actors
				$table = "actors";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				actorID INT(10) UNSIGNED PRIMARY KEY,
				birthDate VARCHAR(100),
				hometown VARCHAR(100),
				firstName VARCHAR(100),
				lastName VARCHAR(100),
				biography TEXT(500)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				// Actor Roles
				$table = "actor_roles";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				actorID INT(10),
				filmID INT(10),
				role VARCHAR(100)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				// Directors
				$table = "directors";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				directorID INT(10) UNSIGNED PRIMARY KEY,
				birthDate VARCHAR(100),
				hometown VARCHAR(100),
				firstName VARCHAR(100),
				lastName VARCHAR(100),
				biography TEXT(500)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				// Directed By
				$table = "directed_by";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				directorID INT(10),
				filmID INT(10)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				// Producers
				$table = "producers";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				producerID INT(10) UNSIGNED PRIMARY KEY,
				birthDate VARCHAR(100),
				hometown VARCHAR(100),
				firstName VARCHAR(100),
				lastName VARCHAR(100),
				biography TEXT(500)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				// Produced By
				$table = "produced_by";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				producerID INT(10),
				filmID INT(10)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation::" . mysql_error( $database ) . " </center>" );
				
				// Ratings
				$table = "ratings";
				$sql = "CREATE TABLE IF NOT EXISTS $table (
				ratingID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				filmID INT(10),
				rating INT(10)
				)";
				
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error with $table table creation:" . mysql_error( $database ) . " </center>" );
					
				ob_flush();
        		flush();
				
				// Now we populate those tables with data from the json files
				
				print( "<center> Populating tables with data from JSON files... </center>" );
				
				ob_flush();
        		flush();
				
				// Movies
				$jsonFile = "movies.json";
				$json = file_get_contents( $jsonFile );
				$json = json_decode( $json, true );
				$movieArray = $json["movies"];
				
				print( "<center> Populating films table... </center>" );
				
				ob_flush();
        		flush();
				
				// Create starting sql
				$sql = "INSERT INTO films (filmID, title, runningTime, releaseDate, summary, popularity) VALUES ";
				$sql_parts = array();
				
				// Loop through each movie object adding the data we want
				foreach ( $movieArray as $movie ) {
					$filmID = $movie["filmID"];
					$title = str_replace( "'", "''", $movie["title"] );
					$runningTime = $movie["runningTime"];
					$releaseDate = $movie["releaseDate"];
					$summary = str_replace( "'", "''", $movie["summary"] );
					$popularity = $movie["popularity"];
					
					$sql_parts[] = "($filmID, '$title', $runningTime, '$releaseDate', '$summary', $popularity)";						
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql		
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting movie: $title..." . mysql_error() . " </center>" );
				
				// Add random ratings to each movie
				print( "<center> Populating ratings table... </center>" );
				
				// Create starting sql
				$sql = "INSERT INTO ratings (filmID, rating) VALUES ";
				$sql_parts = array();
				
				// Loop through each movie object adding the data we want
				foreach ( $movieArray as $movie ) {
					$filmID = $movie["filmID"];
					
					// Create 10 ratings per movie
					for ( $i = 0; $i < 10; $i++ ) {
						$sql_parts[] = "($filmID, " . rand(1, 10) . ")";
					}
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql		
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting ratings:" . mysql_error() . " </center>" );
					
				// Film Genres					
				print( "<center> Populating film_genres table... </center>" );
				
				ob_flush();
        		flush();
				
				// Start sql
				$sql = "INSERT INTO film_genres (genreID, filmID) VALUES ";
				$sql_parts = array();
				
				// Loop through the movie objects again only grabbing genre data
				foreach ( $movieArray as $movie ) {
					$filmID = $movie["filmID"];
					
					// Add the genres for the movie into the film_genres table	
					foreach ( $movie["genres"] as $genre ) {
						$genreID = $genre["id"];
						
						$sql_parts[] = "($genreID, $filmID)";
					}
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting into film_genres, Error: " . mysql_error() . " </center>" );
					
				//Actor Roles
				print( "<center> Populating actor_roles table... </center>" );
				
				ob_flush();
        		flush();
				
				// Start sql
				$sql = "INSERT INTO actor_roles (actorID, filmID, role) VALUES ";
				$sql_parts = array();
				
				foreach ( $movieArray as $movie ) {
					$filmID = $movie["filmID"];
						
					// Add the actors for the movie into the actor_roles table
					foreach ( $movie["cast"] as $actor ) {
						$actorID = $actor["id"];
						$actor_role = str_replace( "'", "''", $actor["character"] );
						
						$sql_parts[] = "($actorID, $filmID, '$actor_role')";		
					}
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting into actor_roles, Error: " . mysql_error() . " </center>" );
					
				// Directed By
				print( "<center> Populating directed_by table... </center>" );
				
				ob_flush();
        		flush();
				
				// Start sql
				$sql = "INSERT INTO directed_by (directorID, filmID) VALUES ";
				$sql_parts = array(); 
				
				foreach ( $movieArray as $movie ) {
					$filmID = $movie["filmID"];
					
					// Add the directors for the movie into the directed_by table
					$directors = array();
					foreach ( $movie["crew"] as $director ) {
						if ( $director["job"] == "Director" ) {
							$directorID = $director["id"];
							
							$sql_parts[] = "($directorID, $filmID)";
						}
					}
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting into directed_by, Error: " . mysql_error() . " </center>" );
					
				// Produced By
				print( "<center> Populating produced_by table... </center>" );
				
				ob_flush();
        		flush();
				
				// Start sql
				$sql = "INSERT INTO produced_by (producerID, filmID) VALUES "; 
				$sql_parts = array();
			
				foreach ( $movieArray as $movie ) {
					$filmID = $movie["filmID"];
						
					// Add the producers for the movie to the produced_by table
					$producers = array();
					foreach ( $movie["crew"] as $producer ) {
						if ( $producer["job"] == "Producer" ){
							$producerID = $producer["id"];
							
							$sql_parts[] = "($producerID, $filmID)";							
						}
					}	
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting into produced_by, Error: " . mysql_error() . " </center>" );
				
				// Genres
				print( "<center> Populating genres table... </center>" );
				
				ob_flush();
        		flush();
				
				$jsonFile = "genres.json";
				$json = file_get_contents( $jsonFile );
				$json = json_decode( $json, true );
				$genreArray = $json["genres"];
				
				// Start sql
				$sql = "INSERT INTO genres (genreID, genreType) VALUES ";
				$sql_parts = array(); 
				
				// Loop through each movie object adding the data we want
				foreach ( $genreArray as $genre ) {
					$genreID = $genre["id"];
					$genre_name = $genre["name"];
					
					$sql_parts[] = "($genreID, '$genre_name')";					
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting into genres, Error: " . mysql_error() . " </center>" );
				
				// Actors
				$jsonFile = "actors.json";
				$json = file_get_contents( $jsonFile );
				$json = json_decode( $json, true );
				$actorArray = $json["actors"];
				
				print( "<center> Populating actors table... </center>" );
				
				ob_flush();
        		flush();
				
				// Start sql
				$sql = "INSERT INTO actors (actorID, birthDate, hometown, firstName, lastName, biography) VALUES ";
				$sql_parts = array(); 
				
				// Loop through each actor object adding the data we want
				foreach ( $actorArray as $actor ) {
					$actorID = $actor["id"];
					$birthDate = $actor["birthday"];
					$hometown = str_replace( "'", "''", $actor["place_of_birth"] );
					$name = explode( " ", str_replace( "'", "''", $actor["name"] ) );
					$biography = str_replace( "'", "''", $actor["biography"] );
					
					// Name may only have a first or lastname so check
					if ( count( $name ) > 1 ) {
						// Add the actor to the db
						$sql_parts[] = "($actorID, '$birthDate', '$hometown', '$name[0]', '$name[1]', '$biography')";
					}
					else {
						// Add the actor to the db
						$sql_parts[] = "($actorID, '$birthDate', '$hometown', '$name[0]', '', '$biography')";
					}
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting into actors, Error: " . mysql_error() . " </center>" );
				
				// Directors
				$jsonFile = "directors.json";
				$json = file_get_contents( $jsonFile );
				$json = json_decode( $json, true );
				$directorArray = $json["directors"];
				
				print( "<center> Populating directors table... </center>" );
				
				ob_flush();
        		flush();
				
				// Start sql
				$sql = "INSERT INTO directors (directorID, birthDate, hometown, firstName, lastName, biography) VALUES ";
				$sql_parts = array(); 
				
				// Loop through each director object adding the data we want
				foreach ( $directorArray as $director ) {
					$directorID = $director["id"];
					$birthDate = $director["birthday"];
					$hometown = str_replace( "'", "''", $director["place_of_birth"] );
					$name = explode( " ", str_replace( "'", "''", $director["name"] ) );
					$biography = str_replace( "'", "''", $director["biography"] );
					
					// Name may only have a first or lastname so check
					if ( count( $name ) > 1 ) {
						// Add the director to the db
						$sql_parts[] = "($directorID, '$birthDate', '$hometown', '$name[0]', '$name[1]', '$biography')";
					}
					else {
						// Add the director to the db
						$sql_parts[] = "($directorID, '$birthDate', '$hometown', '$name[0]', '', '$biography')";
					}
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting into directors, Error: " . mysql_error() . " </center>" );
				
				// Producers
				$jsonFile = "producers.json";
				$json = file_get_contents( $jsonFile );
				$json = json_decode( $json, true );
				$producerArray = $json["producers"];
				
				print( "<center> Populating producers table... </center>" );
				
				ob_flush();
        		flush();
				
				// Start sql
				$sql = "INSERT INTO producers (producerID, birthDate, hometown, firstName, lastName, biography) VALUES ";
				$sql_parts = array(); 
				
				// Loop through each producer object adding the data we want
				foreach ( $producerArray as $producer ) {
					$producerID = $producer["id"];
					$birthDate = $producer["birthday"];
					$hometown = str_replace( "'", "''", $producer["place_of_birth"] );
					$name = explode( " ", str_replace( "'", "''", $producer["name"] ) );
					$biography = str_replace( "'", "''", $producer["biography"] );
					
					// Name may only have a first or lastname so check
					if ( count( $name ) > 1 ) {
						// Add the producer to the db
						$sql_parts[] = "($producerID, '$birthDate', '$hometown', '$name[0]', '$name[1]', '$biography')";
					}
					else {
						// Add the producer to the db
						$sql_parts[] = "($producerID, '$birthDate', '$hometown', '$name[0]', '', '$biography')";
					}
				}
				
				// Final sql
				$sql .= implode(",", $sql_parts);
				
				// Run sql
				if ( !mysql_query( $sql, $database ) )
					print( "<center> Error inserting into producers, Error: " . mysql_error() . " </center>" );
				
				$message = "Finished!";
				
				// Close connection
				mysql_close( $database );
			}
			else if ( isset( $clear ) ) {
				// Clear tables
				print( "<center> Beginning to clear the database... </center>" );
				ob_flush();
        		flush();
				
				print( "<center> Opening MySQL Connection... </center>" );
				// Setup MySQL Connection
				if ( !( $database = mysql_connect( $host, $user, $password ) ) )
					die( "Could not connect to database" );
					
				// Create the db as it doesn't exist
				$sql = "DROP DATABASE $dbname";
				if ( mysql_query( $sql, $database ) ) {
					print( "<center> Database: $dbname deleted... </center>" );
					mysql_select_db( $dbname, $database );
				}
				else
					print( "<center> Error with DB deletion: " . mysql_error( $database ) . " </center>" );
				
				ob_flush();
        		flush();
				
				$message = "Finished!";
				
				// Close connection
				mysql_close( $database );
			}
			// Display Finished
			print( "<center> $message </center>" );
			ob_end_flush();
		?> <!-- end PHP Script -->		
	</body>
</html>