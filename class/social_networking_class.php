<?php

/*
SOCIAL NETWORKING APPLICATION CLASS
author: Gaetano Ceraulo
version: 1.0

Distributed under the terms of the MIT license below
Copyright 2018 Gaetano Ceraulo

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE
*/

// ########################################################################
// #### INCLUDE MYSQL DATABASE CONNECTION PARAMETERS AND GLOBAL PARAMETERS
// ########################################################################

// UNCOMMENT IF RUNNING ON WINDOWS PLATFORM
include_once(__DIR__ . '\include\mysql_config.inc.php');
include_once(__DIR__ . '\include\config.inc.php');

/* UNCOMMENT IF RUNNING ON LINUX PLATFORM
include_once(__DIR__ . '/include/mysql_config.inc.php');
include_once(__DIR__ . '/include/config.inc.php');
*/

// #############################################################################
// #### FUNCTION FOR CONVERT DATETIME IN SECONDS/MINUTES/HOURS/DAYS/MONTHS/YEARS
// #############################################################################

function timeago($date) {

	$timestamp = strtotime($date);	

	$strTime = array("second", "minute", "hour", "day", "month", "year");
	$length = array("60","60","24","30","12","10");

	$currentTime = time();
	if($currentTime >= $timestamp) {
		$diff     = time()- $timestamp;
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
		$diff = $diff / $length[$i];
		}

		$diff = round($diff);
		$suffix = "";
		if ($diff >= 2){$suffix="s";}

		return "(".$diff . " " . $strTime[$i] . $suffix . " ago)";
	}
	
}

// #############################################################
// #### CLASS FOR RETRIEVE AND STORE DATA FROM/TO MYSQL DATABASE
// #############################################################

class social_networking_class {
	
    public function getClassIstance(){ return $this; }
	
	// ########################################################
	// #### PUBLIC FUNCTION FOR RETRIEVING USERS POSTS AND WALL
	// ########################################################
	
    public function read_operation_function($type,$username) {
	
	// CONNECTING TO MYSQL DATABASE
		
	$mysqli = new mysqli($GLOBALS['MYSQL_HOST'],$GLOBALS['MYSQL_USERNAME'],$GLOBALS['MYSQL_PASSWORD'],$GLOBALS['MYSQL_DBNAME']);
	$mysqli->set_charset("utf8");
	
	if ($mysqli->connect_errno) {
		printf("DB Connection Failed: %s\n", $mysqli->connect_error);
		exit();
	}
		
	if($type=='read'){
			
			// GET USER FRIENDS POSTS AND PRINT RESULTS
		
			$query = "SELECT * FROM friendships WHERE username='$username'";
			$result = $mysqli->query($query);
			$rowcount = mysqli_num_rows($result);

			if ($rowcount >= 1){
				while($row = $result->fetch_array())
				{
					
					$friend_username = $row['friend_username'];
					$friendship_query = "SELECT * FROM posts WHERE username='$friend_username' ORDER BY datetime DESC";
					$friendship_result = $mysqli->query($friendship_query);
					$friendship_rowcount = mysqli_num_rows($friendship_result);

					if ($friendship_rowcount >= 1){
						echo "\n".ucfirst($friend_username)."\n";
						while($friendship_row = $friendship_result->fetch_array())
						{
							$output_string = $friendship_row['message']." ".timeago($friendship_row['datetime']);
							echo preg_replace('~[.[:cntrl:]]~', '', $output_string)."\n";
						}
					}

					$friendship_result->free();
					
				}
			}

			$result->free();
		
			// GET USER POSTS AND PRINT RESULTS
		
			$query = "SELECT * FROM posts WHERE username='$username' ORDER BY datetime DESC";
			$result = $mysqli->query($query);
			$rowcount = mysqli_num_rows($result);

			if ($rowcount >= 1){
				echo "\n".ucfirst($username)."\n";
				while($row = $result->fetch_array())
				{
					$output_string = $row['message']." ".timeago($row['datetime']);
					echo preg_replace('~[.[:cntrl:]]~', '', $output_string)."\n";
				}
			}

			$result->free();
		
	} else if ($type=='wall'){
		
		// GET USER WALL 
		
		echo "\n";
		$all_outputTemporaryArray = array();
		$counter_all_outputArray = array();
		$all_outputDefinitiveArray = array();
		
		// ADD USER POSTS IN ARRAYS FOR DATETIME SORTING
		
		$query = "SELECT * FROM posts WHERE username='$username'";
		$result = $mysqli->query($query);
		$rowcount = mysqli_num_rows($result);

		if ($rowcount >= 1){
			while($row = $result->fetch_array())
			{
				$output_string = ucfirst($username)." - ".$row['message']." ".timeago($row['datetime']);
				$output_string = preg_replace('~[.[:cntrl:]]~', '', $output_string)."\n";
				array_push($all_outputTemporaryArray,$output_string); array_push($counter_all_outputArray,$row['datetime']);
			}
		}

		$result->free();
		
		// ADD USER FOLLOWED FRIENDS POSTS IN ARRAYS FOR DATETIME SORTING
		
		$query = "SELECT * FROM follows WHERE username='$username'";
		$result = $mysqli->query($query);
		$rowcount = mysqli_num_rows($result);

		if ($rowcount >= 1){
			while($row = $result->fetch_array())
			{
				
				$friend_username = $row['followed_username'];
				$friendship_query = "SELECT * FROM posts WHERE username='$friend_username'";
				$friendship_result = $mysqli->query($friendship_query);
				$friendship_rowcount = mysqli_num_rows($friendship_result);

				if ($friendship_rowcount >= 1){
					while($friendship_row = $friendship_result->fetch_array())
					{
						$output_string = ucfirst($friend_username)." - ".$friendship_row['message']." ".timeago($friendship_row['datetime']);
						$output_string =  preg_replace('~[.[:cntrl:]]~', '', $output_string)."\n";
						array_push($all_outputTemporaryArray,$output_string); array_push($counter_all_outputArray,$friendship_row['datetime']);
					}
				}

				$friendship_result->free();
				
			}
		}
		
		$result->free();
		
		// SORT POSTS IN ARRAYS BY DATETIME IN REVERSE ORDER AND PRINT RESULTS
		
		arsort($counter_all_outputArray);

		foreach ($counter_all_outputArray as $key => $val) {
		   array_push($all_outputDefinitiveArray,$all_outputTemporaryArray[$key]);
		}

		foreach ($all_outputDefinitiveArray as $key => $val) {
			echo $val;
		}
		
	}
		
		// CLOSE MYSQL DATABASE CONNECTION
		
		$mysqli->close();
		
	}
	
	// ##############################################################################################
	// #### PUBLIC FUNCTION FOR WRITE USER OPERATIONS TO MYSQL DATABASE (POSTS, FOLLOWS, FRIENDSHIPS)
	// ##############################################################################################
	
	public function write_operation_function($type,$username,$message,$otherusername) {
	
	$current_datetime =  date("Y-m-d H:i:s");	
	
	// CONNECTING TO MYSQL DATABASE
		
	$mysqli = new mysqli($GLOBALS['MYSQL_HOST'],$GLOBALS['MYSQL_USERNAME'],$GLOBALS['MYSQL_PASSWORD'],$GLOBALS['MYSQL_DBNAME']);
	$mysqli->set_charset("utf8");
	$message = mysqli_real_escape_string($mysqli, $message);
		
	if ($mysqli->connect_errno) {
		printf("DB Connection Failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	if($type=='post'){
	
	// STORE USER POSTS IN MYSQL DATABASE
		
	$sql=("INSERT INTO posts (username,message,datetime) VALUES ('$username','$message','$current_datetime')");	
	if ($mysqli->query($sql) === TRUE) {} else {echo "DB Query Error: " . $mysqli->error;exit();}
		
	} else if($type=='follows'){
		
		// STORE USER FOLLOWED FRIENDS IN MYSQL DATABASE
		
		$sql=("INSERT INTO follows (username,followed_username,datetime) VALUES ('$username','$otherusername','$current_datetime')");	
		if ($mysqli->query($sql) === TRUE) {} else {echo "DB Query Error: " . $mysqli->error;exit();}
		
	} else if($type=='friendship'){
		
		// STORE USER FRIENDS IN MYSQL DATABASE
		
		$sql=("INSERT INTO friendships (username,friend_username,datetime) VALUES ('$username','$otherusername','$current_datetime')");	
		if ($mysqli->query($sql) === TRUE) {} else {echo "DB Query Error: " . $mysqli->error;exit();}
		
	}
	
	// CLOSE MYSQL DATABASE CONNECTION
		
	$mysqli->close();
		
	}
	
}
