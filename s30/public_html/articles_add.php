<div class="card-body">
	
	<?php
		if (!defined('IN_INDEX')) { 
			exit("Nie można uruchomić tego pliku bezpośrednio."); 
		}
		if (isset($_POST['article'])) {
            $article = $_POST['article'];
            $title = $_POST['title'];
			
            if (mb_strlen($article) >= 5 && mb_strlen($title)>=3) {
				$stmt = $dbh->prepare("INSERT INTO articles (user_id, title, content, created) VALUES (:user_id, :title, :content, NOW())");
				$stmt->execute([':user_id' => $_SESSION['id'], ':title' => $title, ':content' => $article]);
				print '<p style="font-weight: bold; color: green;">Dane zostały dodane do bazy.</p>';
			} else {
                print '<p style="font-weight: bold; color: red;">Artykuł lub tytuł zbyt krótki.</p>';
            }
		}
	?>


	<form action="/articles_add" method="POST">
        <input type="text" name="title" placeholder="Tytuł">
		<textarea style="height: 300px;" name= "article" class="art-editor"></textarea>
        <input type="submit" value="Dodaj">
       </form>
</div>