<?php

/*
SOCIAL NETWORKING APPLICATION
author: Gaetano Ceraulo
version: 1.0

Distributed under the terms of the MIT license below
Copyright 2018 Gaetano Ceraulo

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE
*/

// ####################################
// #### INCLUDE SOCIAL NETWORKING CLASS
// ####################################

// UNCOMMENT IF RUNNING ON WINDOWS PLATFORM
include_once(__DIR__ . '\class\social_networking_class.php');

/* UNCOMMENT IF RUNNING ON LINUX PLATFORM
include_once(__DIR__ . '/class/social_networking_class.php');
*/

// PRINT COMMANDS LIST TO CONSOLE

echo "\n\n";
echo "****************** SOCIAL NETWORKING APPLICATION ******************\n\n";
echo "COMMANDS LIST:\n\n";
echo "posting: <user name> -> <message>\n";
echo "reading: <user name>\n";
echo "following: <user name> follows <another user>\n";
echo "wall: <user name> wall\n\n";
echo "friendship*: <user name> friendship <another user>\n";
echo "*optional, 'Bob friendship Alice' row already in sample db\n";
echo "\nType 'quit' or 'exit' to close application.\n";

while( true ) {

	echo "\nType your command:";

	$command = trim( fgets(STDIN) );

	$socialnetworkclass = new social_networking_class();

	// GET USER COMMANDS
	
	switch (str_word_count($command)) {
			
	case 1:
		if((trim($command)=='quit')||(trim($command)=='exit')){echo "\nGoodbye!\n";exit();}else{
			
		// READ USER POSTS
			
		$socialnetworkclass->getClassIstance()->read_operation_function('read',trim($command));
		}
		break;
	case 2:
			
		// READ USER WALL
			
		$command_to_array = explode(' ',trim($command));
		$socialnetworkclass->getClassIstance()->read_operation_function('wall',trim($command_to_array[0]));
		break;
	default:
		$command_to_array = explode(' ',trim($command));
		if($command_to_array[1]=='follows'){
			
			// STORE USER FOLLOW
			
			$socialnetworkclass->getClassIstance()->write_operation_function("follows",trim($command_to_array[0]),"",trim($command_to_array[2]));
		}else if($command_to_array[1]=='->'){
			
			// STORE USER POST
			
			$socialnetworkclass->getClassIstance()->write_operation_function("post",trim($command_to_array[0]),substr($command, strpos($command, "-> ") + 3),"");
		}else if($command_to_array[1]=='friendship'){
			
			// STORE USER FRIENDSHIP
			
			$socialnetworkclass->getClassIstance()->write_operation_function("friendship",trim($command_to_array[0]),"",trim($command_to_array[2]));
		}
			
	}

}

?>