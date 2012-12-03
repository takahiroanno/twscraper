<?

//twitterのスクレイピングをするスクリプト

require_once('config.php');
require_once('db.php');

crawl();

function crawl(){
	//出力
	echo "-------------------------------------\n";
	echo "   これよりクロールします            \n";
	echo "-------------------------------------\n\n";
	//group数を数える
	for($i = 2;$i < get_group_number()+1;$i++){
	get_column($i);
	}
}

function get_column($group_id){

//$group_id、クエリを取得
$query = get_query_by_group_id($group_id);
echo "group_id = ".$group_id." query = ".$query."\n";
$query = urlencode($query);
//$query = urlencode("#ischool2012");

//$max_idを取得
$max_id = get_max_id($group_id);
echo "max_id は ".$max_id."\n";

//queryを送る
$contents = file_get_contents('http://search.twitter.com/search.json?q='.$query.'&rpp=10');
$contents = json_decode($contents);

//var_dump($contents);
echo "\n\n";
for($i = 0;$i < count($contents->results);$i++){

	echo "発言id:".$contents->results[$i]->id_str."を調査\n";
	
	
	if($contents->results[$i]->id_str <= $max_id){
		//すでにクロール済
		echo "これは既にクロールしました\n";
		break;
 	}
	//取得の処理
	$text = $contents->results[$i]->text;
	$name = $contents->results[$i]->from_user;
	$tw_user_id = $contents->results[$i]->from_user_id;
	$tw_name = $contents->results[$i]->from_user_name;
	$img_url = $contents->results[$i]->profile_image_url;
	$tw_id = $contents->results[$i]->id_str;
	$post_datetime = $contents->results[$i]->created_at;
	$post_datetime = strtotime($post_datetime);
	//var_dump($post_datetime);
	//user_id取得のための処理
	$user_id = is_registered($tw_user_id);
	if($user_id==0){
		echo "このユーザー(".$tw_name.")はまだ登録されていません。\n";
		$user_id = regist_user($tw_user_id,$name,$img_url,$tw_name);
		echo "登録しました。user_idは".$user_id."です.\n";
	}else{
		echo "このユーザーは登録されています。\n";
		echo "user_idは".$user_id."です。\n";
	}
	
	//Tweetの保存
	add_tweet($text,$user_id,$group_id,$tw_id,date("Y-m-d H:i:s",$post_datetime));
	echo "tweetを保存しました。tw_idは".$tw_id."\n";
}

echo "\n\n調べ終わりました\n\n";
}
