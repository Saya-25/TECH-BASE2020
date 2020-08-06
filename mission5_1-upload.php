<?php
// DB接続設定
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS mission5_1"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date TEXT,"
	. "pass TEXT"
	.");";
	$stmt = $pdo->query($sql);
/*
	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";
*/

if (isset($_POST["send"])) {
 /*送信ボタンが押された場合*/
		$name = $_POST["name"];
		$comment = $_POST["comment"];
		date_default_timezone_set('Asia/Tokyo');
		$date = date("Y/m/d H:i:s");
		/*タイムゾーンの設定・取得*/
		$option = $_POST["option"];
		/*編集のときの目印*/
		$pass = $_POST["pass"];
		/*新規投稿のパス*/
			if (isset($_POST["option"])) {
			/*オプションがあるかないかの確認、編集のとき*/
				if ($option !== "") {
				/*オプションがあるかないかの確認、空の時*/
  				$option = $_POST["option"];
  				$sql = 'UPDATE mission5_1 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
  				$stmt = $pdo->prepare($sql);
    			$stmt->bindParam(':id', $option, PDO::PARAM_INT);
    			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
    			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    			$stmt->bindParam(':date', $date, PDO::PARAM_STR);
    			$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
    			$stmt->execute();
					echo "編集書き込み完了<br>";
  			}else{
				/*オプションがない＝新規書き込みの時*/
        	$sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
          $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
          $sql -> bindParam(':date', $date, PDO::PARAM_STR);
          $sql -> bindparam(':pass', $pass, PDO::PARAM_STR);
          $sql -> execute();
					echo "新規書き込み完了<br>";
        	}
		}
	}elseif(isset($_POST["delete"])) {
		/*削除番号がフォームに入力されている場合*/
			$delete_id=$_POST["delete_num"];
			/*echo $_POST["delete_num"]."<br>";*/
			$delete_pass=$_POST["delete_pass"];
			/*echo $_POST["delete_pass"]."<br>";*/
			/*削除番号・パスワードのデータの受け取り*/
			/*echo $delete_id."<br>";
			echo $delete_pass."<br>";*/
			$sql = 'SELECT * FROM mission5_1 WHERE id=:id ';

      $stmt = $pdo->prepare($sql);
			/*差し替えるパラメータを含めて記述したSQLを準備*/
      $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
			/*その差し替えるパラメータの値を指定*/
      $stmt->execute();
			/*SQLを実行*/
      $results = $stmt->fetchAll();

			/*echo $results[0]."<br>";*/
			if ($results[0]['pass'] == $delete_pass){
      	$sql = 'delete from mission5_1 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();
				echo "削除しました<br>";
      }else{
            echo "パスワードが違います<br>";
            }
    }
		else if (isset($_POST["edit"])){
							$num = $_POST["edit_num"];
	            $edit_pass = $_POST["edit_pass"];
							/*echo $_POST["edit_num"]."<br>";
							echo $num."<br>";
							echo $edit_pass."<br>";*/
	            $sql = 'SELECT * FROM mission5_1 WHERE id=:id ';
	           	$stmt = $pdo->prepare($sql);                  //*差し替えるパラメータを含めて記述したSQLを準備*/
	            $stmt->bindParam(':id', $num, PDO::PARAM_INT); /*差し替えるパラメータの値を指定*/
	            $stmt->execute();                             //*SQLを実行*/
	           	$results = $stmt->fetchAll();
							/*echo $results[0]."<br>";*/
	            if ($results[0]['pass'] == $edit_pass){
	                /*echo "編集中";*/
	                $edit_id = $results[0]['id'];
	        				$edit_name = $results[0]['name'];
	        				$edit_comment = $results[0]['comment'];
	        				$edit_password = $results[0]['pass'];
	            }else {
	        	    		echo "パスワードが違います<br>";
	        					}
	    }

			/*echo "ok<br>";*/
			/*$sql = 'SELECT * FROM mission5_1';
    	$stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
    		echo $row['id'].' ';
    		echo $row['name'].' ';
    		echo $row['comment'].' ';
    		echo $row['date'].'<br>';
    		echo "<hr>";
				echo "完了";*/


$sql = 'SELECT * FROM mission5_1';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['date'].'<br>';
	echo "<hr>";
	echo "完了<br>";
}
 ?>
 <!DOCTYPE html>
 <html lang="jp">
 <head>
     <meta charset="utf-8">
     <title>mission5_1</title>
 </head>
 <body>

   <!--投稿フォームの作成-->
 【投稿フォーム】

  <form method="post">
  <input type="hidden" name="option" value="<?php echo $edit_id; ?>">
  名前：<input type="text" name="name" value="<?php echo $edit_name; ?>" placeholder="名前を入力してください"><br>
  コメント：<input type="text" name="comment" value="<?php echo $edit_comment; ?>" placeholder="コメントを入力してください"><br>
  パスワード：<input type='text' name='pass' value="<?php echo $edit_password; ?>" placeholder="パスワードを入力してください"><br>
  <input type="submit" name="send" value="送信">
  </form>

  【削除フォーム】
 　<form method="post">
   削除番号：<input type="number" name="delete_num" value="" placeholder="削除する番号を入力してください"><br>
   パスワード：<input type='text' name='delete_pass' value=''placeholder="パスワードを入力してください"><br>
   <input type="submit" name="delete" value="削除">
 </form>

 【編集番号指定用フォーム】
 <form method="post">
   編集番号：<input type="number" name="edit_num" value="" placeholder="編集する番号を入力してください"><br>
   パスワード：<input type='text' name='edit_pass' value=''placeholder="パスワードを入力してください"><br>
   <input type="submit" name="edit" value="編集">
 </form>

 </body>
 </html>
