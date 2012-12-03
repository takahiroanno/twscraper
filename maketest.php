<?php

require_once 'db.php';

makemeeting();
makesaying();
makeparticipant();
makepolitician();
makeparty();
makecomment();
maketag();
maketagmap();

function makerandomtext($length){
	$query = "";
	for($i = 0;$i < $length;$i++){
		switch(rand(0,10)){
			case 0:
				$query = $query."あ";
				break;
			case 1:
				$query = $query."い";
				break;
			case 2:
				$query = $query."う";
				break;
			case 3:
				$query = $query."え";
				break;
			case 4:
				$query = $query."お";
				break;
			case 5:
				$query = $query."か";	
				break;
			case 6:
				$query = $query."き";
				break;
			case 7:
				$query = $query."く";
				break;
			case 8:
				$query = $query."け";
				break;
			case 9:
				$query = $query."こ";
				break;
			case 10:
				$query = $query."さ";
				break;
		}
	}
	return $query;
}

function makemeeting(){
	for($i = 0;$i < 100;$i++){
		$array['id'] = $i;
		$array['title'] = makerandomtext(rand(3,5));
		$array['type'] = rand(0,2);
		$array['go'] = rand(0,100);
		$array['starttime'] = date('Y-m-d G:i:s');
		$array['endtime'] = date('Y-m-d G:i:s');
		$array['no'] = rand(1,100);
		$array['other'] = makerandomtext(rand(10,15));
		$array['add_datatime'] = date('Y-m-d G:i:s');
		$array['mod_datatime'] = date('Y-m-d G:i:s');
		$array['deleted'] = rand(0,1);
		add($array,"meeting");
	}
	print "meeting_ok\n";
}

function makesaying(){
	for($i = 0;$i < 1000;$i++){
		$array['id'] = $i;
		$array['meeting_id'] = (int)rand(1,100);
		$array['politician_id'] = (int)rand(1,200);
		$array['sorder'] = (int)rand(1,100);
		$array['text'] = makerandomtext(rand(10,300));
		$array['add_datatime'] = date('Y-m-d G:i:s');
		$array['mod_datatime'] = date('Y-m-d G:i:s');
		$array['deleted'] = rand(0,1);
		add($array,"saying");
	}
	print "saying_ok\n";
}

function makeparticipant(){
	for($i = 0;$i < 1000;$i++){
		$array['id'] = $i;
		$array['meeting_id'] = (int)rand(1,100);
		$array['politician_id'] = (int)rand(1,300);
		$array['add_datatime'] = date('Y-m-d G:i:s');
		$array['mod_datatime'] = date('Y-m-d G:i:s');
		$array['deleted'] = rand(0,1);
		add($array,"participant");
	}
	print "participant_ok\n";
}

function makepolitician(){
	for($i = 0;$i < 300;$i++){
		$array['id'] = $i;
		$array['name'] = makerandomtext(rand(4,6));
		$array['party_id'] = (int)rand(1,6);
		$array['birthday'] = date('Y-m-d G:i:s');
		$array['district'] = makerandomtext(rand(10,300));
		$array['history'] = makerandomtext(rand(10,15));
		$array['pict_url'] = "img/testpict.png";
		$array['other'] = makerandomtext(rand(5,10));
		$array['add_datatime'] = date('Y-m-d G:i:s');
		$array['mod_datatime'] = date('Y-m-d G:i:s');
		$array['deleted'] = rand(0,1);
		add($array,"politician");
	}
	print "politician_ok\n";
}

function makeparty(){
	for($i = 0;$i < 5;$i++){
		$array['id'] = $i;
		$array['name'] = makerandomtext(rand(4,6))." 党";
		$array['discription'] = makerandomtext(rand(10,15));
		$array['add_datatime'] = date('Y-m-d G:i:s');
		$array['mod_datatime'] = date('Y-m-d G:i:s');
		$array['deleted'] = rand(0,1);
		add($array,"party");
	}
	print "party_ok\n";
}

function makecomment(){
	for($i = 0;$i < 1000;$i++){
		$array['id'] = $i;
		$array['saying_id'] = rand(1,1000);
		$array['text'] = makerandomtext(rand(10,15));
		$array['datetime'] = date('Y-m-d G:i:s');
		$array['name'] = makerandomtext(rand(4,6));
		$array['utype'] = makerandomtext(rand(4,6));
		$array['add_datatime'] = date('Y-m-d G:i:s');
		$array['mod_datatime'] = date('Y-m-d G:i:s');
		$array['deleted'] = rand(0,1);
		add($array,"comment");
	}
	print "comment_ok\n";
}

function maketag(){
	for($i = 0;$i < 500;$i++){
		$array['id'] = $i;
		$array['tag'] = makerandomtext(rand(4,6));
		$array['add_datatime'] = date('Y-m-d G:i:s');
		$array['mod_datatime'] = date('Y-m-d G:i:s');
		$array['deleted'] = rand(0,1);
		add($array,"tag");
	}
	print "tag_ok\n";
}

function maketagmap(){
	for($i = 0;$i < 500;$i++){
		$array['id'] = $i;
		$array['saying_id'] = rand(1,500);
		$array['tag_id'] = rand(1,500);
		$array['tag'] = makerandomtext(rand(4,6));
		$array['add_datatime'] = date('Y-m-d G:i:s');
		$array['mod_datatime'] = date('Y-m-d G:i:s');
		$array['deleted'] = rand(0,1);
		add($array,"tagmap");
	}
	print "tagmap_ok\n";
}