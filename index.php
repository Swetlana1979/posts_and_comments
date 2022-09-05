<?php
$get_posts=file_get_contents('https://jsonplaceholder.typicode.com/posts');
$get_comments=file_get_contents('https://jsonplaceholder.typicode.com/comments');
$get_posts=json_decode($get_posts,true);
$get_comments=json_decode($get_comments,true);

$array_posts = array();
$array_comments = array();
	foreach($get_posts as $g){
		$arr=array();
		$arr['userId']=$g['userId'];
		$arr['id']=$g['id'];
		$arr['title']=$g['title'];
		$arr['body']=$g['body'];
		$array_posts[]=$arr;
    
}

	foreach($get_comments as $g){
		$arr=array();
		$arr['postId']=$g['postId'];
		$arr['id']=$g['id'];
		$arr['name']=$g['name'];
		$arr['email']=$g['email'];
		$arr['body']=$g['body'];
		$array_comments[]=$arr;
		
    }
	
	function con(){
		$con = mysqli_connect('localhost', 'root', 'root', 'post_and_comments');
		if (!$con) {
			printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error());
			exit;
		}
		return $con;
	}
	
	function insert_posts($sql,$userId,$title,$body){
		
		$con = con();
		$stmt = mysqli_prepare($con, $sql); 
		mysqli_stmt_bind_param($stmt, "iss", $userId,$title,$body);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
	}
	
	function insert_comments($sql,$postId,$name,$email,$body){
		
		$con = con();
		$stmt = mysqli_prepare($con, $sql); 
		mysqli_stmt_bind_param($stmt, "isss", $postId,$name,$email,$body);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
	}
	function add_posts($userId,$title,$body){
		$sql = "INSERT INTO posts(userId,title,body)VALUES(?,?,?)"; 
		insert_posts($sql,$userId,$title,$body);	
		
	}
	
	function add_comments($postId,$name,$email,$body){
		$sql = "INSERT INTO comments(postId,name,email,body)VALUES(?,?,?,?)"; 
		insert_comments($sql, $postId,$name,$email,$body);	
	 }
	 
	function add($array_posts,$array_comments){ 
		foreach($array_posts as $value){
			$userId = trim($value['userId']);
			$title = trim($value['title']);
			$body = trim($value['body']);
			add_posts($userId,$title,$body);
			
		}
		foreach($array_comments as $value){
			$postId = $value['postId'];
			$name = $value['name'];
			$email = $value['email'];
			$body = $value['body'];
			add_comments($postId,$name,$email,$body);
		}
		echo "Загружено ".count($array_posts)." записей и ".count($array_comments)." комментариев";
	}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

</head>
<body>
<? 
add($array_posts,$array_comments);?>
	<form id='' name='' action='search.php' method='post'>
		<input type='text' name='search' id='search'>
		<input type='submit' id='submit' name='submit' value='Поиск'>
	</form>
	
</body>
</html>