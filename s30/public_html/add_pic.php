
<?php
			if (!defined('IN_INDEX')) { exit("Nie można uruchomić tego pliku bezpośrednio."); }

			if (isset($_POST['submit'])) {

				if (isset($_POST['title']) && mb_strlen($_POST['title']) > 1) {
					$file = $_FILES['file'];
					$fileName = $file['name'];
					$fileTmp = $file['tmp_name'];
					$fileSize = $file['size'];
					$fileError = $file['error'];

					$tmp = explode('.', $fileName);
					$fileExt = strtolower(end($tmp)); 
					$allowedExt = array('jpg', 'jpeg', 'png');
					
					
					
					if ($fileError === 0) {
						if (in_array($fileExt, $allowedExt)) {
								$fileNameNew = uniqid('', true) . "." . $fileExt;
								$fileDest = 'images/' . $fileNameNew;
								move_uploaded_file($fileTmp, $fileDest);		
		
								$title = $_POST['title'];
								$id=session_id();
								$category = $_POST['category'];
								$description = $_POST['description'];
								$stmt = $dbh->prepare("INSERT INTO images (id, title, name, category, description, date) VALUES (:id, :title, :name, :category, :description, NOW())");
								$stmt->execute([':id' => $id, ':title' => $title, ':name' => $fileDest, ':category' => $category, ':description' => $description]);
								print '<p style="font-weight: bold; color: green;">Udało się</p>';

						} else {
							
							print '<p style="font-weight: bold; color: red;">Akceptujemy tylko pliki z następującymi rozszerzeniami: .jpg, .jpeg, .png</p>';
						}
					} else {
						
						print '<p style="font-weight: bold; color: red;">Nie udało się</p>';
					}
				} else {
					
					print '<p style="font-weight: bold; color: red;">Tytuł musi mieć co najmniej 2 znaki</p>';
				}
			}
?>

<div class="container content" id="form-box">
    <form action="/add_pic" method="post" enctype="multipart/form-data" class="with-border">
		<div class="form-group">
		
				<label for="inputTitle">Tytuł zdjęcia</label>
				<input type="text" name="title" id="inputTitle" placeholder="Tytuł" class="form-control">
			</div>
			<div class="form-group">
			
				<label for="inputDes">Opis zdjęcia</label>
				<input type="text" name="description" id="inputDes" placeholder="Opis" class="form-control">
			</div>
        <div class="form-group">
		
            <label for="category">Wybierz kategorię:</label>
            <select class="form-control" name="category" required>
                <option>Brak</option>
                <?php
					$stmt = $dbh->prepare("SELECT * FROM categories");
					$stmt->execute();				
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						print '<option> '. $row['category'] .' </option>';
					}

				?>				
				</select>
        </div>
        <div class="form-group">
		
            <label for="img">Wybierz lub przeciągnij zdjęcie</label><br>
            <input class = "dropzone" name="file" type="file" accept="image/*" required> 
        </div>
        <input type="submit" name="submit"></button>
    </form>
<script>
    Dropzone.autoDiscover = false;
</script>
