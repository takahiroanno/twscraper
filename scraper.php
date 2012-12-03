<?

$query = 'ブロッコリー';
$since_id = '275460377167400960';
$data = tweet_search($query,$since_id);

$length = count($data->results);

echo $length . " tweets is taken";

for($i = 0;$i < $length;$i++){
  add_tweet_database($data->results[$i]);
}

//calculate_scheduled_time
//$velocity = $length / $time
//$scheduled_time = $current_time + (10 / $velocity);

//job queue
//add_job_queue($query,$since_id,$scheduled_time);

//done queue
//add_done_queue($id);


function tweet_search($query,$since_id){

  $url = 'http://search.twitter.com/search.json?q=' . urlencode($query) . '&since_id=' . urlencode($since_id);
  $response = file_get_contents($url);
  $data = json_decode($response);

  return $data;

}

function add_tweet_database($tweet){
  var_dump($tweet);
}
