<?
	
	function con(){
		$con = mysqli_connect('localhost', 'root', 'root', 'post_and_comments');
		if (!$con) {
			printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error());
			exit;
		}
		return $con;
	}
	
	function select($sql,$query){
		$con = con();
		$stmt = mysqli_prepare($con,$sql);
		mysqli_stmt_bind_param($stmt, "s", $query);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		mysqli_stmt_close($stmt);
		if (!$result)
		   die();
		return $result;
	}
	
	
	function select_data($query){
		$sql = "SELECT posts.`title`, posts.`body` AS posts, comments.`body` AS comments FROM `posts`,`comments` WHERE comments.`body` LIKE ? OR comments.postId = posts.id"; 
		$query='%'.$query.'%';
		var_dump(select($sql,$query));	
	}
	
	function search ($query) 
	{ 
		$query = trim($query); 
		$query = htmlspecialchars($query);

		if (!empty($query)) 
		{ 
			if (strlen($query) < 3) {
				echo '<p>Слишком короткий поисковый запрос.</p>';
			} else { 
				$result = select_data($query);
				if($result){
					foreach($result as $key => $value){
						echo $value['title'].'<br>';
						echo $value['posts'].'<br>';
						echo ' - '.$value['comments'].'<br>';
					}
				}else{
					echo 'не удалось получить данные';
				}
			}
		}
	}    
	search ($_POST['search']);
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>

</head>
<body>
	
</body>
</html>
