<div class="card-body">

	<?php
	if (!defined('IN_INDEX')) { 
		exit("Nie można uruchomić tego pliku bezpośrednio."); 
	}

	if (isset($_GET['show']) && intval($_GET['show']) > 0) {
		print'
		<br><a href="/articles_list"><button>Wróc do poprzedniej strony</button></a></br>
		';
		$id = intval($_GET['show']);
		try {
			$stmt = $dbh->prepare("SELECT * FROM articles WHERE id = :id");
			$stmt->execute([':id' => $id]);
			$article = $stmt->fetch(PDO::FETCH_ASSOC);
			print'
		
		<br>' . $article['title'] . '</br>
		<br>' . $article['content'] . '</br>
		
		';
		} catch (PDOException $e) {
			print '<span style="color: red;">Nie znaleziono artykułu</span>';
		}
		

    // podstrona /articles_list/show/<id>,
    // tutaj wyswietlamy tytul i tresc artykulu, ktorego ID mamy w zmiennej $id

	} elseif (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
		$id = intval($_GET['edit']);
		

		if (isset($_POST['title']) && isset($_POST['article'])) {

        $article = $_POST['article'];
        $title = $_POST['title'];
			
            if (mb_strlen($article) >= 5 && mb_strlen($title)>=3) {
				
				$stmt = $dbh->prepare("UPDATE articles SET title = :title, content = :content WHERE id = :id AND user_id = :user_id");
				$stmt->execute([':user_id' => (isset($_SESSION['id']) ? $_SESSION['id'] : 0), ':title' => $title, ':content' => $article, ':id' => $id]);
				print '<p style="font-weight: bold; color: green;">Dane zostały dodane do bazy.</p>';
			} else {
                print '<p style="font-weight: bold; color: red;">Artykuł lub tytuł zbyt krótki.</p>';
            }

		}
		
		print'
		<br><a href="/articles_list"><button>Wróc do poprzedniej strony</button></a></br>
		';
		try {
			$stmt = $dbh->prepare("SELECT * FROM articles WHERE id = :id");
			$stmt->execute([':id' => $id]);
			$article = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
		<form action="/articles_list/edit/<?=$id ?>" method="POST">
        <input type="text" name="title" placeholder="Tytuł" value="<?=htmlspecialchars($article['title'], ENT_QUOTES | ENT_HTML401, 'UTF-8') ?>">
		<textarea style="height: 300px;" name= "article" class="art-editor"> <?=htmlspecialchars($article['content'], ENT_QUOTES | ENT_HTML401, 'UTF-8') ?></textarea>
        <input type="submit" value="Zapisz">
       </form>
	   <?php
		} catch (PDOException $e) {
			print '<span style="color: red;">Nie znaleziono artykułu</span>';
		}
		
		
		
    // podstrona /articles_list/edit/<id>,
    // tutaj wyswietlamy formularz edycji artykulu, ktorego ID mamy w zmiennej $id

	} else {

		if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {

			$id = intval($_GET['delete']);
            $stmt = $dbh->prepare("DELETE FROM articles WHERE id = :id AND user_id = :user_id");
            $stmt->execute([':id' => $id, ':user_id' => (isset($_SESSION['id']) ? $_SESSION['id'] : 0)]);

        // tutaj usuwamy artykul, ktorego ID mamy w zmiennej $id,
        // przed usunieciem nalezy upewnic sie, ze zalogowany uzytkownik jest autorem artykulu

		}

    // podstrona /articles_list,
    // tutaj wyswietlamy listę wszystkich artykulow
	?>

	<table class="table table-striped">
          <thead>
            <tr id="wiersz-naglowka">
              <th scope="col">Tytuł</th>
			  <th scope="col">Edytuj</th>
			  <th scope="col">Usuń</th>
            </tr>
          </thead>
          <tbody> 
            <?php
			$stmt = $dbh->prepare("SELECT * FROM articles ORDER BY id DESC");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

				
				if(isset($_SESSION['id']) && $row['user_id'] == $_SESSION['id']){
					print '
					<tr>
                  <td><a href="/articles_list/show/' . $row['id'] . '">' . htmlspecialchars($row['title'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</a></td>	
					<td><a href="/articles_list/edit/' . $row['id'] . '"><button>Edytuj</button></a></td>				  
					<td><a href="/articles_list/delete/' . $row['id'] . '"><button>Usuń</button></a></td>
					</tr>';
				}else{
					print '
				<tr>
                  <td><a href="/articles_list/show/' . $row['id'] . '">' . htmlspecialchars($row['title'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</a></td>				  
                </tr>';
				}
            }
			
			?>
            
          </tbody>
        </table>
	
	<?php } ?>
</div>
