<?
require_once('config.php');
getDb();

//get_initial_job
$job = get_job();
if(!($job)){
  echo "no job";
  return false;
}

$id = $job['id'];
$query = $job['query'];
$since_id = $job['since_id'];

$data = tweet_search($query,$since_id);

$length = count($data->results);

echo $length . " tweets is taken";
if($length == 0){
  return false; 
}

for($i = 0;$i < $length;$i++){
  add_tweet_database($data->results[$i]);
}

$since_id = $data->results[0]->id_str;
$oldest_time = strtotime($data->results[$length-1]->created_at);

//calculate_scheduled_time
$velocity = ($length -1) / (time() - $oldest_time);
$scheduled_time = time() + (50 / $velocity);
$scheduled_time = date('Y-m-d H:i:s',$scheduled_time);


//job queue
add_job_queue($query,$since_id,$scheduled_time);

//done queue
update_job_queue($id);

function get_job(){
  try{
    global $db;
    $queue = $db->prepare("
      SELECT * from job WHERE scheduled_datetime < NOW() AND status = 0 LIMIT 1;
    ");
    $queue->execute();
    $data = $queue->fetchAll(PDO::FETCH_ASSOC);
    if($data[0]){
      return $data[0];
    }else{
      return false;
    }
  }catch (PDOException $e){
    die("接続エラー：{$e->getMessage()}");
  }
}

function add_job_queue($query,$since_id,$scheduled_time){
  try{
    global $db;
    $queue = $db->prepare("
      INSERT INTO job(query,since_id,scheduled_datetime) 
      VALUES(\"$query\",\"$since_id\",\"$scheduled_time\");
    ");
    $queue->execute();
  }catch (PDOException $e){
    die("接続エラー：{$e->getMessage()}");
  }

}

function update_job_queue($id){
  try{
    global $db;
    $queue = $db->prepare("
      UPDATE job SET status = 1 WHERE id = $id;
    ");
    $queue->execute();
  }catch (PDOException $e){
    die("接続エラー：{$e->getMessage()}");
  }

}

function getDb() {
  $dsn = 'mysql:dbname=' . C_DATABASE . '; host=' . C_HOST;
  $usr = C_USERNAME;
  $passwd = C_PASSWORD;

  try {
    global $db;
    $db = new PDO($dsn, $usr, $passwd);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db -> exec('SET NAMES utf8');
    $db -> exec("SET time_zone = '+0:00'");
  } catch (PDOException $e) {
    die("接続エラー：{$e->getMessage()}");
  }
  return $db;
}

function tweet_search($query,$since_id){

  $url = 'http://search.twitter.com/search.json?rpp=100&q=' . urlencode($query) . '&since_id=' . urlencode($since_id);
  $response = file_get_contents($url);
  $data = json_decode($response);
  return $data;

}

function add_tweet_database($tweet){
  try{
    global $db;

    $queue = $db->prepare('
      INSERT INTO tweets(created_at,from_user,from_user_id,from_user_name,geo,tweet_id,profile_image_url,profile_image_url_https,source,
        text,to_user,to_user_id,to_user_name,in_reply_to_status_id)
        VALUES("'.date('Y-m-d H:i:s',strtotime($tweet->created_at)).'",
          "'.$tweet->from_user.'",
          "'.$tweet->from_user_id.'",
          :from_user_name,
          "'.$tweet->geo.'",
          "'.$tweet->id_str.'",
          "'.$tweet->profile_image_url.'",
          "'.$tweet->profile_image_url_https.'",
          "'.$tweet->source.'",
          :text,
          "'.$tweet->to_user.'",
          "'.$tweet->to_user_id.'",
          :to_user_name,
          "'.$tweet->in_reply_to_status_id_str.'"); 
    ');
    $queue->bindParam(':text',$tweet->text,PDO::PARAM_STR);
    if($tweet->from_user_name == NULL){
      $tweet->from_user_name = " ";
    }
    if($tweet->to_user_name == NULL){
      $tweet->to_user_name = " ";
    }
    $queue->bindParam(':to_user_name',$tweet->to_user_name,PDO::PARAM_STR);
    $queue->bindParam(':from_user_name',$tweet->from_user_name,PDO::PARAM_STR);
    $queue->execute();
  }catch (PDOException $e){
    die("接続エラー：{$e->getMessage()}");
  }
}
