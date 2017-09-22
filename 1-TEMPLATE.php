<?php



$string = file_get_contents($var);

$xml = simplexml_load_string($string);

$max_loop=20; 
$count = 0;

foreach ($xml->channel->item as $val) {

echo "<a href = ".$val->link.">$val->title</a><br />";
echo "$val->description<br />";
echo "$val->pubDate<br />";
echo "<br />";
// get db config
$config_database_username="DB_USER";
$config_database="DB_NAME";
$config_password="DB_PASSWORD";

//Connect to a database server which is on the same machine at port 3306
$dbcnx = mysql_connect("127.0.0.1:3306", $config_database_username, $config_password)
         or die("Unable to connect to the database. <br>  Please check your settings in config.php!");
         
//Find the desired database on the connected server.
mysql_select_db($config_database) or die("Unable to select the database schemata. <br>  Please check your settings in config.php!");//Check to see if we have this story.
	
			//Check the storytitle to make sure it doesn't already exists in the database.
			$val->title = addslashes($val->title);
			$val->link = addslashes($val->link);
			$val->description = addslashes($val->description);
			$val->link = strip_tags($val->link);
			$val->title = strip_tags($val->title);
			// $val->description = strip_tags($val->description);
			//Now do a search for that storytitle.
			$result = mysql_query("select * from swarmstories2 where `storytitle` ='$val->title'");
			if(mysql_num_rows($result)!=0)
			{
					echo "We have that story.<br />";
				 	$count++;
					if($count==$max_loop) break;
	
			}
		
		else
			{
				echo "Attempting to post that story... <br />";
				echo "Title: $val->title <br />";
				echo "Link: $val->link <br />";
			
				
$result = mysql_query("insert into swarmstories2 set storytitle ='$val->title', remarks = '$remarks', url = '$val->link', description = '$val->description'")  or die('failed because '.mysql_error());


						
			$count++;
			if($count==$max_loop) break;
				}



		}


?>
