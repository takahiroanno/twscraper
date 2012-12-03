<?php

function getDb() {
	$dsn = 'mysql:dbname=' . C_DATABASE . '; host=' . C_HOST;
	$usr = C_USERNAME;
	$passwd = C_PASSWORD;

	try {
		global $db;
		$db = new PDO($dsn, $usr, $passwd);
		$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db -> exec('SET NAMES utf8');

	} catch (PDOException $e) {
		die("接続エラー：{$e->getMessage()}");
	}
	return $db;
}
/*
function get_query_by_group_id($id){
	try {
		
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT name FROM groups WHERE id = :group_id  LIMIT 1;
		');
		$stt -> bindParam(':group_id',$id,PDO::PARAM_INT);
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		return $data[0]["name"];
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function get_max_id($group_id) {
	try {
		
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT tw_id FROM tweets WHERE group_id = :group_id ORDER BY tw_id DESC LIMIT 1;
		');
		$stt -> bindParam(':group_id',$group_id,PDO::PARAM_INT);
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		if(count($data) == 0){
			return 0;
		}

		return $data[0]["tw_id"];
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}


function get_group_number() {
	try {
		
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT id FROM groups ORDER BY id DESC LIMIT 1;
		');
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		return $data[0]["id"];
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}



function is_registered($id) {
	try {
		
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT id FROM users WHERE users.tw_id = :id LIMIT 1;
		');
		$stt -> bindParam(':id',$id,PDO::PARAM_INT);
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		if(count($data) == 0){
			return 0;
		}
		return $data[0]["id"];
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}


function regist_user($tw_id,$name,$img_url,$tw_name) {
	try {
		
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		INSERT INTO users(name,img_url,tw_name,tw_id,add_datetime,mod_datetime,deleted)
		VALUES("'.$name.'","'.$img_url.'","'.$tw_name.'",'.$tw_id.',NOW(),NOW(),0);
		');
		$stt -> execute();
		
		$la = $db -> lastInsertId();
		return (int)$la;


	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}


function add_tweet($text,$user_id,$group_id,$tw_id,$post_datetime) {
	try {


		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		INSERT INTO tweets(user_id,group_id,tw_id,txt,post_datetime,add_datetime,mod_datetime,deleted)
		VALUES("'.$user_id.'","'.$group_id.'","'.$tw_id.'", :text ,"'.$post_datetime.'",NOW(),NOW(),0);
		');
		$stt -> bindParam(':text',$text,PDO::PARAM_STR);
		$stt -> execute();
		
		$la = $db -> lastInsertId();
		return (int)$la;


	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}



function getpkgdata_by_id($id) {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT * FROM packages WHERE id = ' . $id . ' ;
		');
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		return $data[0];
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function getmondaisuu_by_id($id) {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT COUNT(id) FROM questions WHERE package_id = ' . $id . ' ;
		');
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		return $data[0]['COUNT(id)'];
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function getquestiondata_by_id($id) {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT * FROM questions WHERE package_id = ' . $id . ' ORDER BY qorder;
		');
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		return $data;
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function add_score($score, $id, $user_id) {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT id FROM questions WHERE package_id = ' . $id . ' ORDER BY qorder;
		');
		$stt -> execute();
		$question_id = $stt -> fetchAll(PDO::FETCH_ASSOC);
		//$question_id[0]["id"] = 1問目のquestionid;
		//var_dump($question_id);

		//重複を調査
		$stt2 = $db -> prepare('
		SELECT COUNT(id) FROM scores 
		WHERE question_id = ' . $question_id[0]["id"] . '
		AND user_id = ' . $user_id . ';
		');
		$stt2 -> execute();
		$number = $stt2 -> fetchAll(PDO::FETCH_ASSOC);

		if($number[0]['COUNT(id)'] > 0) {
			//2回目以降
			return 0;
		} else {

			for($i = 1; $i < count($score); $i++) {
				$columns["user_id"] = $user_id;
				$columns["question_id"] = $question_id[$i - 1]["id"];
				$columns["score"] = $score[$i];
				add($columns, "scores");
			}

			$columns["package_id"] = $id;
			$columns["score"] = $score[0];
			add($columns, "scoreboards");

			return 1;

		}

	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function get_ranking_by_id($id) {
	try {
		
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT * FROM scoreboards,user WHERE package_id =  ' . $id . '
		AND user.id = scoreboards.user_id ORDER BY score DESC;
		');

		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		
		return $data;
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function get_seitouritsu_by_id($id) {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT *
FROM scores, questions
WHERE questions.package_id =' . $id . '
AND scores.question_id = questions.id
		');
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);

		$seitousuu = 0;

		for($i = 0; $i < count($data); $i++) {
			if($data[$i]['score'] > 0) {
				$seitousuu++;
			}
		}
		if(count($data) == 0) {
			return 0;
		} else {
			return round($seitousuu / count($data) * 100);
		}
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function get_user_by_name($name) {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT COUNT(*) FROM user WHERE name LIKE  "' . $name . '";
		');
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		return $data[0]['COUNT(*)'];
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function get_userid_by_name($name) {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt = $db -> prepare('
		SELECT id FROM user WHERE name LIKE  "' . $name . '";
		');
		$stt -> execute();
		$data = $stt -> fetchAll(PDO::FETCH_ASSOC);
		return $data[0]['id'];
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function get_packages_by_id($id) {
	$package['participants'] = get_ranking_by_id($id);
	$package['participants_number'] = count($package['participants']);
	$package['seitouritsu'] = get_seitouritsu_by_id($id);
	if($package['seitouritsu'] > 80) {
		$package['difficulty'] = 1;
	}else if($package['seitouritsu'] > 60) {
		$package['difficulty'] = 2;
	}else if($package['seitouritsu'] > 40) {
		$package['difficulty'] = 3;
	}else if($package['seitouritsu'] > 20) {
		$package['difficulty'] = 4;
	} else {
		$package['difficulty'] = 5;
	}
	$a = getpkgdata_by_id($id);
	$package['title'] = $a['title'];
	$package['id'] = $id;
	return $package;
}

function get_packages() {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}

		$stt3 = $db -> prepare('
		SELECT id FROM packages LIMIT 100;";
		');
		$stt3 -> execute();
		$data = $stt3 -> fetchAll(PDO::FETCH_ASSOC);
		

		return $data;
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}" );
	}
}

/*
 function search_saying_by_query($query) {
 try {
 global $db;
 if ($db === NULL) {
 $db = getDb();
 }
 $stt = $db -> prepare('SELECT * FROM saying,politician WHERE saying.saying_text LIKE "%' . $query . '%" AND saying.politician_id = politician.politician_id');
 $stt -> execute();
 $searchsaying = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $searchsaying;
 } catch (PDOException $e) {
 die("エラーメッセージ:{e->getMessage()}");
 }
 }

 function search_comment_by_query($query) {
 try {
 global $db;
 if ($db === NULL) {
 $db = getDb();
 }
 $stt = $db -> prepare('SELECT * FROM comment WHERE comment_text LIKE "%' . $query . '%"; ');
 $stt -> execute();
 $searchcomment = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $searchcomment;
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 ////////////////////////////////////////////////////

 function get_comment_by_meeting_id($meeting_id) {
 try {
 global $db;
 $comment = array();
 if ($db === NULL) {
 $db = getDb();
 }
 $stt = $db -> prepare('SELECT saying_id FROM saying WHERE meeting_id =' . $meeting_id);
 $stt -> execute();
 $saying_ids = $stt -> fetchAll(PDO::FETCH_ASSOC);
 foreach ($saying_ids as $sayingids) {
 foreach ($sayingids as $sayings) {
 $stt = $db -> prepare('SELECT * FROM comment WHERE saying_id =' . $sayings);
 $stt -> execute();
 $comment = array_merge($comment, $stt -> fetchAll(PDO::FETCH_ASSOC));
 }
 }

 return $comment;
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_number_of_participants_by_meeting_id($meeting_id) {
 try {
 global $db;
 if ($meeting_id === 0) {
 $meeting_id = 'meeting_id';
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $stt = $db -> prepare('SELECT COUNT(*) FROM participant WHERE meeting_id =' . $meeting_id);
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["COUNT(*)"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_number_of_sayings_by_meeting_id($meeting_id) {
 try {
 global $db;
 if ($meeting_id === 0) {
 $meeting_id = 'meeting_id';
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $que = 'SELECT COUNT(*) FROM saying WHERE meeting_id =' . $meeting_id . ' AND deleted = 0';
 $stt = $db -> prepare($que);
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["COUNT(*)"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_number_of_sayings_by_politician_id($politician_id) {
 try {
 global $db;
 if ($politician_id === 0) {
 $politician_id = 'politician_id';
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $que = 'SELECT COUNT(*) FROM saying WHERE politician_id =' . $politician_id . ' AND deleted = 0';
 $stt = $db -> prepare($que);
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["COUNT(*)"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_number_of_sayings_by_tag_id($tag_id) {
 try {
 global $db;
 if ($tag_id === 0) {
 $tag_id = 'tag_id';
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $que = 'SELECT COUNT(*) FROM tagmap WHERE tag_id = ' . $tag_id . ' AND deleted = 0';
 //var_dump($query);
 $stt = $db -> prepare($que);
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["COUNT(*)"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_number_of_sayings_by_party_id($party_id) {
 try {
 global $db;
 if ($party_id === 0) {
 $party_id = 'politician.party_id';
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $que = 'SELECT COUNT(*) FROM saying, politician
 WHERE saying.politician_id = politician.id AND politician.party_id =' . $party_id . ' AND saying.deleted = 0 AND politician.deleted = 0';
 $stt = $db -> prepare($que);
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["COUNT(*)"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_number_of_comments_by_saying_id($saying_id) {
 try {
 global $db;
 if ($saying_id === 0) {
 $saying_id = 'saying_id';
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $stt = $db -> prepare('SELECT COUNT(*) FROM comment WHERE saying_id =' . $saying_id . ' AND deleted = 0');
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["COUNT(*)"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_number_of_tags_by_saying_id($saying_id) {
 try {
 global $db;
 if ($saying_id === 0) {
 $saying_id = 'saying_id';
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $stt = $db -> prepare('SELECT COUNT(*) FROM tag,tagmap WHERE tag.id = tagmap.tag_id AND tagmap.saying_id = ' . $saying_id);
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["COUNT(*)"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_number_of_politicians_by_party_id($party_id) {//バグ注意
 try {
 global $db;
 if ($party_id === 0) {
 $party_id = 'party_id';
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $que = 'SELECT COUNT(*) FROM politician WHERE politician.party_id = ' . $party_id . ' AND politician.deleted = 0';
 $stt = $db -> prepare($que);
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["COUNT(*)"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function insert_comment($comment) {
 try {
 global $db;
 if ($db === NULL) {
 $db = getDb();
 }
 $sql = $db -> prepare("SHOW TABLE STATUS LIKE 'comment';");
 $sql -> execute();
 $increment = $sql -> fetchAll(PDO::FETCH_ASSOC);
 $stt = $db -> prepare('INSERT INTO comment VALUES(' . $increment[0]["Auto_increment"] . ',' . $comment["s_id"] . ',"' . $comment["ctext"] . '","' . $comment["datetime"] . '","' . $comment["cname"] . '",' . $comment["ctype"] . ');');
 $stt -> execute();
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_tagmap_id($tag_id, $saying_id) {
 try {
 global $db;
 if ($saying_id === NULL || $tag_id === NULL) {
 throw new Exception("引数ありません");
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $que = 'SELECT id AS tagmap_id FROM tagmap WHERE saying_id = ' . $saying_id . ' AND tag_id = ' . $tag_id . ' AND tagmap.deleted = 0';
 $stt = $db -> prepare($que);
 $stt -> execute();
 $n = $stt -> fetchAll(PDO::FETCH_ASSOC);
 return $n[0]["tagmap_id"];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }
 */
/*
function get_fields($tablename) {
	try {
		global $db;
		if($db === NULL) {
			$db = getDb();
		}
		$que = 'DESC ' . $tablename;
		$stt = $db -> prepare($que);
		$stt -> execute();
		$temp_fields = $stt -> fetchAll(PDO::FETCH_ASSOC);
		$fields = array();
		for($i = 0; $i < count($temp_fields); $i++) {
			$fields[] = $temp_fields[$i]['Field'];
		}
		return $fields;
	} catch (PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

function add($columns, $tablename) {
	try {
		global $db;
		unset($columns['id']);
		$columns['add_datetime'] = 'NOW()';
		$columns['mod_datetime'] = 'NOW()';
		$columns['deleted'] = 0;
		$fields = get_fields($tablename);
		$key_array = array();
		$value_array = array();

		foreach($columns as $key => $value) {
			if(in_array($key, $fields)) {
				if($key !== 'add_datetime' && $key !== 'mod_datetime') {
					$value = '"' . $value . '"';
				}
				$key_array[] = $key;
				$value_array[] = $value;
			}
		}
		$key = implode(',', $key_array);
		$value = implode(',', $value_array);

		$que = 'INSERT INTO ' . $tablename . '(' . $key . ') VALUE(' . $value . ')';

		if($db === NULL) {
			$db = getDb();
		}
		$stt = $db -> prepare($que);
		$stt -> execute();
		$la = $db -> lastInsertId();
		return (int)$la;

	} catch(PDOException $e) {
		die("エラーメッセージ:{$e->getMessage()}");
	}
}

/*
 function update($columns, $tablename, $conds) {
 try {
 global $db;
 unset($columns['id']);
 unset($columns['add_datetime']);
 $columns['mod_datetime'] = 'NOW()';
 $columns['deleted'] = 0;
 $fields = get_fields($tablename);
 $set_array = array();
 $where_array = array();
 foreach ($columns as $key => $value) {
 if (in_array($key, $fields)) {
 if ($key !== 'add_datetime' && $key !== 'mod_datetime') {
 $value = '"' . $value . '"';
 }
 $set_array[] = $key . ' = ' . $value;

 }
 }
 $set = implode(',', $set_array);

 foreach ($conds as $key => $value) {
 if (in_array($key, $fields)) {
 if ($key !== 'add_datetime' && $key !== 'mod_datetime') {
 $value = '"' . $value . '"';
 }
 $where_array[] = $key . ' = ' . $value;

 }
 }
 $where = implode(',', $where_array);
 if (is_null($where)) {
 $where = 1;
 }
 $que = 'UPDATE ' . $tablename . ' SET ' . $set . ' WHERE ' . $where;

 if ($db === NULL) {
 $db = getDb();
 }
 $stt = $db -> prepare($que);
 $stt -> execute();

 } catch(PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function delete($column_id, $tablename) {
 try {
 global $db;
 $que = 'UPDATE ' . $tablename . ' SET mod_datetime = NOW(), deleted = 1 WHERE id = ' . $column_id;
 if ($db === NULL) {
 $db = getDb();
 }
 $stt = $db -> prepare($que);
 $stt -> execute();

 } catch(PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function get_series_of_saying_id($meeting_id, $sorder, $pointer) {
 try {
 global $db;
 if ($pointer === "next") {
 $pointer = 1;
 }else if ($pointer === "prev") {
 $pointer = -1;
 } else {
 throw new Exception("pointerの値が不正です。'next'または'prev'で指定してください。", 1);
 }
 if ($db === NULL) {
 $db = getDb();
 }
 $sorder = $sorder + $pointer;
 $stt = $db -> prepare('SELECT id FROM saying WHERE meeting_id=' . $meeting_id . ' AND sorder=' . $sorder);
 $stt -> execute();
 $pointer = $stt -> fetchAll(PDO::FETCH_ASSOC);
 //var_dump($pointer);
 return $pointer[0]['id'];
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function checkSayingLink($saying_link) {
 if ($saying_link === NULL) {
 throw new Exception("saying_linkが空です", 1);
 }else if (!is_numeric($saying_link)) {
 throw new Exception("saying_linkが数値形式ではありません", 1);
 } else {
 return $saying_link;
 }
 }

 //////////////////////////////////////////////////////
 //--------------------tag関連------------------------//
 //////////////////////////////////////////////////////
 function addTag($saying_id, $tag) {
 try {
 global $db;
 if ($tag === "") {
 throw new Exception("tagが入力されていません", 1);
 //return -1;
 }

 //DBにタグがあるかないか
 if ($db === NULL) {
 $db = getDb();
 }
 $que = 'SELECT tag.id AS tag_id FROM tag WHERE tag LIKE "' . $tag . '"';

 $stt = $db -> prepare($que);
 $stt -> execute();
 $num = $stt -> fetchAll(PDO::FETCH_ASSOC);
 $tag_id = $num[0]['tag_id'];

 if (empty($tag_id)) {
 //タグが無いので重複もない
 //addしてlast_insert_idが返ってくる
 $columns['tag'] = $tag;
 $tag_id = add($columns, 'tag');
 } else {
 //tagの更新を行う
 $conds = array('id' => $tag_id);
 $columns = array();
 update($columns, 'tag', $conds);
 }
 //tag_table更新終わり

 //同じsaying_idにおける重複確認
 $que = "SELECT id AS tagmap_id, deleted AS tagmap_deleted FROM tagmap
 WHERE saying_id = " . $saying_id . " AND tag_id = " . $tag_id;

 //var_dump($que);
 $stt = $db -> prepare($que);
 $stt -> execute();
 $num = $stt -> fetchAll(PDO::FETCH_ASSOC);
 $tagmap_id = $num[0]['tagmap_id'];
 $tagmap_deleted = $num[0]['tagmap_deleted'];

 if (isset($tagmap_id) && $tagmap_deleted === "0") {
 //同じ発言内に同じタグがあります。
 throw new Exception("すでに同じ発言内に同じタグがあります", 2);
 //return -2;
 } else if (isset($tagmap_id) && $tagmap_deleted === "1") {
 //同じタグが以前存在していた
 $conds['id'] = $tagmap_id;
 update($columns, 'tagmap', $conds);

 } else {
 //かぶりなし,新規に作成
 $columns['tag_id'] = $tag_id;
 $columns['saying_id'] = $saying_id;
 add($columns, 'tagmap');
 }

 return TRUE;
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function delTag($saying_id, $tag_id) {
 try {
 global $db;
 $tagmap_id = get_tagmap_id($tag_id, $saying_id);
 if ($tagmap_id === NULL) {
 throw new Exception("指定のタグが存在しません", 2);
 } else {
 delete($tagmap_id, 'tagmap');
 }
 return TRUE;
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function updateTag($saying_id, $tag_id, $tag) {
 try {
 global $db;
 $state = deletelTag($saying_id, $tag_id);

 if ($state === FALSE) {
 //削除に失敗
 return FALSE;
 } elseif ($state === TRUE) {
 //削除に成功
 $state = addTag($saying_id, $tag);
 }
 if ($state === TRUE) {
 return TRUE;
 } else {
 return FALSE;
 }
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 //////////////////////////////////////////////////////
 //--------------------comment関連--------------------//
 //////////////////////////////////////////////////////

 function addComment($saying_id, $comment_text, $user_id) {
 try {
 global $db;
 if ($db === NULL) {
 $db = getDb();
 }

 //引数チェック
 if ($saying_id === NULL) {
 throw new Exception("saying_idが入力されていません", 1);
 }
 if ($comment_text === NULL) {
 throw new Exception("textが入力されていません", 1);
 }
 if ($user_id === NULL) {
 throw new Exception("user_idが入力されていません", 1);
 }

 //コメント連投チェック
 //10分以内で同じコメントをしていたらはじく
 $que = 'SELECT saying_id FROM comment WHERE text LIKE "' . $comment_text . '" AND id = ' . $saying_id . ' AND NOW() > ADDTIME(mod_datetime, "0:10:0")';
 $stt = $db -> prepare($que);
 $stt -> execute();
 $test_id = $stt -> fetchAll(PDO::FETCH_ASSOC);
 $test_id = $test_id[0]['saying_id'];

 if ($test_id !== NULL) {
 //同じ投稿が最近なされていた
 throw new Exception("連続投稿はできません", 1);

 } elseif ($test_id === NULL) {
 //checkok

 $array['saying_id'] = $saying_id;
 $array['text'] = $comment_text;
 $array['user_id'] = $user_id;
 $array['mod_datetime'] = 'NOW()';
 //var_dump($array);
 add($array, 'comment');

 return TRUE;
 }
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 function deleteComment($saying_id, $comment_id, $user_id) {
 try {
 global $db;
 if ($db === NULL) {
 $db = getDb();
 }
 delete($comment_id, 'comment');
 return TRUE;
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 //////////////////////////////////////////////////////
 //-------------------user関連------------------------//
 //////////////////////////////////////////////////////
 function updateUser($array) {
 try {
 foreach ($array as $key => $value) {
 if ($value === NULL) {
 unset($array[$key]);
 }
 }
 $conds = array();
 $conds['id'] = $array['id'];
 update($array, 'user', $conds);
 } catch (PDOException $e) {
 die("エラーメッセージ:{$e->getMessage()}");
 }
 }

 * */
