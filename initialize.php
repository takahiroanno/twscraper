<?php
require_once ('db.php');
require_once ('config.php');
db_initialize();
function db_initialize() {
	//$a = $db->exec('DROP TABLE meeting ;');
	//var_dump($a);
	//mysql_query('DROP TABLE tf_user ;');
	//mysql_query('DROP TABLE tf_comment');
	//var_dump($db->errorInfo());
	$db = getDb();
	/*
	$db -> exec("DROP TABLE IF EXISTS packages");
	$db -> exec("DROP TABLE IF EXISTS users");
	$db -> exec("DROP TABLE IF EXISTS scores");
	$db -> exec("DROP TABLE IF EXISTS questions");
	$db -> exec("DROP TABLE IF EXISTS types");
	 * 
	 */
	$db -> exec("DROP TABLE IF EXISTS users");
	$db -> exec("DROP TABLE IF EXISTS classes");
	$db -> exec("DROP TABLE IF EXISTS groups");
	$db -> exec("DROP TABLE IF EXISTS groupmaps");
	$db -> exec("DROP TABLE IF EXISTS logs");
	$db -> exec("DROP TABLE IF EXISTS tweets");
	$db -> exec("DROP TABLE IF EXISTS goods");
	$db -> exec("DROP TABLE IF EXISTS bads");
	
	$db -> exec("CREATE TABLE IF NOT EXISTS users (
          id 			bigint 		NOT NULL AUTO_INCREMENT
        , name		 	varchar(20)	NOT NULL
        , bio	 		text		NOT NULL
        , img_url		varchar(1024) NOT NULL
        , tw_id		 	varchar(15)	NOT NULL
        , tw_token 		varchar(100)	NOT NULL
        , tw_token_secret varchar(100)  NOT NULL
		, add_datetime 	datetime	NOT NULL
 		, mod_datetime 	datetime 	NOT NULL
 		, deleted 		int(1) 		NOT NULL
		, PRIMARY KEY(id)
	) ENGINE=MyISAM DEFAULT CHARSET= utf8;");
	echo "users...ok\n";

	$db -> exec("CREATE TABLE IF NOT EXISTS groups(
        id 				bigint 		NOT NULL AUTO_INCREMENT
        ,name			varchar(100)	NOT NULL
        ,class_id		bigint		NOT NULL
        ,add_datetime 	datetime 	NOT NULL
        ,mod_datetime 	datetime 	NOT NULL
        ,deleted 		int(1) 	NOT NULL
        , PRIMARY KEY(id)
	) ENGINE=MyISAM DEFAULT CHARSET= utf8;");
	echo "groups...ok\n";
	
	$db -> exec("CREATE TABLE IF NOT EXISTS groupmaps(
        id 				bigint 		NOT NULL AUTO_INCREMENT
        ,group_id		bigint 		NOT NULL
        ,user_id		bigint 		NOT NULL
        ,add_datetime 	datetime 	NOT NULL
        ,mod_datetime 	datetime 	NOT NULL
        ,deleted 		int(1) 	NOT NULL
        , PRIMARY KEY(id)
	) ENGINE=MyISAM DEFAULT CHARSET= utf8;");
	echo "groupmaps...ok\n";
	

	$db -> exec("CREATE TABLE IF NOT EXISTS logs(
       	  id 			bigint 		NOT NULL AUTO_INCREMENT
		, group_id 		bigint 		NOT NULL
      	, txt 			text		NOT NULL
		, add_datetime 	datetime 	NOT NULL
 		, mod_datetime 	datetime 	NOT NULL
	 	, deleted 		int(1) 		NOT NULL
		, PRIMARY KEY(id) 
       ) ENGINE=MYISAM DEFAULT CHARSET= UTF8;");
	echo "logs...ok\n";

	$db -> exec("CREATE TABLE IF NOT EXISTS tweets(
          id 			bigint 		NOT NULL AUTO_INCREMENT
        , url 			varchar(1024) NOT NULL
        , user_id		bigint 		NOT NULL
        , group_id		bigint		NOT NULL
        , txt			varchar(200) NOT NULL
        , fav			int(1) 		NOT NULL
        , introduced	int(1)		NOT NULL
        , post_datetime	datetime	NOT NULL
        , add_datetime 	datetime 	NOT NULL
 		, mod_datetime 	datetime 	NOT NULL
	 	, deleted 		int(1) 		NOT NULL
        , PRIMARY KEY(id)
	) ENGINE=MyISAM DEFAULT CHARSET= utf8;");
	echo "tweets...ok\n";
	
	$db -> exec("CREATE TABLE IF NOT EXISTS goods(
          id 			bigint 		NOT NULL AUTO_INCREMENT
        , user_id		bigint 		NOT NULL
        , class_id		bigint		NOT NULL
        , add_datetime 	datetime 	NOT NULL
 		, mod_datetime 	datetime 	NOT NULL
	 	, deleted 		int(1) 		NOT NULL
        , PRIMARY KEY(id)
	) ENGINE=MyISAM DEFAULT CHARSET= utf8;");
	echo "goods...ok\n";
	
	$db -> exec("CREATE TABLE IF NOT EXISTS bads(
          id 			bigint 		NOT NULL AUTO_INCREMENT
        , user_id		bigint 		NOT NULL
        , class_id		bigint		NOT NULL
        , add_datetime 	datetime 	NOT NULL
 		, mod_datetime 	datetime 	NOT NULL
	 	, deleted 		int(1) 		NOT NULL
        , PRIMARY KEY(id)
	) ENGINE=MyISAM DEFAULT CHARSET= utf8;");
	echo "bads...ok\n";
	
	$db -> exec("CREATE TABLE IF NOT EXISTS classes(
          id 			bigint 		NOT NULL AUTO_INCREMENT
        , name		varchar(30) 	NOT NULL
        , description 	text		NOT NULL
        , add_datetime 	datetime 	NOT NULL
 		, mod_datetime 	datetime 	NOT NULL
	 	, deleted 		int(1) 		NOT NULL
        , PRIMARY KEY(id)
	) ENGINE=MyISAM DEFAULT CHARSET= utf8;");
	echo "classes...ok\n";
	
}
