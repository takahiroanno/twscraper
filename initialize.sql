USE twscraper;
SET NAMES utf8;
DROP TABLE IF EXISTS job;
DROP TABLE IF EXISTS tweets;

CREATE TABLE IF NOT EXISTS job(
  id  bigint NOT NULL AUTO_INCREMENT
, query varchar(100) NOT NULL
, since_id varchar(60) NOT NULL
, scheduled_datetime datetime NOT NULL
, status int(1) NOT NULL
, PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS tweets(
  id  bigint NOT NULL AUTO_INCREMENT
, created_at datetime NOT NULL
, from_user varchar(50) NOT NULL
, from_user_id varchar(20) NOT NULL
, from_user_name varchar(50) NOT NULL
, geo varchar(100) NOT NULL 
, tweet_id varchar(50) NOT NULL
, profile_image_url varchar(200) NOT NULL
, profile_image_url_https varchar(200) NOT NULL
, source varchar(200) NOT NULL
, text varchar(300) NOT NULL
, to_user varchar(50) NOT NULL
, to_user_id bigint NOT NULL
, to_user_name varchar(50) NOT NULL
, in_reply_to_status_id varchar(50) NOT NULL
, PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

