<div class="card-body">

		<?php
		if (!defined('IN_INDEX')) { 
			exit("Nie można uruchomić tego pliku bezpośrednio."); 
		}		
        if (isset($_POST['opinia'])) {
            $opinia = $_POST['opinia'];
            $ip = $_SERVER['REMOTE_ADDR'];
			
            if (mb_strlen($opinia) >= 5 && mb_strlen($opinia) <= 200) {
				
				$secret=$config['recaptcha_private'];
				$response=$_POST['g-recaptcha-response'];
				
				$recaptcha = new \ReCaptcha\ReCaptcha($secret);
				$resp = $recaptcha ->verify($response, $ip);
				
				if ($resp->isSuccess()) {
					$stmt = $dbh->prepare("INSERT INTO guest_book (opinion, ip, created) VALUES (:opinion, :ip, NOW())");
					$stmt->execute([':opinion' => $opinia, ':ip' => $ip]);
					print '<p style="font-weight: bold; color: green;">Dane zostały dodane do bazy.</p>';
				}else {
					print '<p style="font-weight: bold; color: red;">wypełnij captcha</p>';
					$errors = $resp->getErrorCodes();
				}

                
            
			} else {
                print '<p style="font-weight: bold; color: red;">Podane dane są nieprawidłowe.</p>';
            }
		}
        ?>
		
		<form action="/guest_book" method="POST">
		<div class="g-recaptcha" data-sitekey="<?=$config['recaptcha_public'] ?>"></div>
        <input type="text" name="opinia" placeholder="Opinia">
        <input type="submit" value="Dodaj">
        </form>
		

        <table class="table table-striped">
          <thead>
            <tr id="wiersz-naglowka">
              <th scope="col">ID</th>
              <th scope="col">Opinia</th>
              <th scope="col">Ip</th>
              <th scope="col">Data</th>
            </tr>
          </thead>
          <tbody>
            <?php
			if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
			$ip = $_SERVER['REMOTE_ADDR'];
            $stmt = $dbh->prepare("DELETE FROM guest_book WHERE id = :id AND ip = :ip");
            $stmt->execute([':id' => $id, ':ip' => $ip]);
        }	
			
			
            $stmt = $dbh->prepare("SELECT id, opinion, ip, created FROM guest_book");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

				
				if($row['ip']==$_SERVER['REMOTE_ADDR']){
					print '
					<tr>
                  <td>' . intval($row['id']) . '</td>
                  <td>' . htmlspecialchars($row['opinion'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</td>
                  <td>' . htmlspecialchars($row['ip'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</td>
				  <td>' . date('D, d M Y H:i:s', strtotime($row['created'])) . '</td>				  
                
					<td><a href="/guest_book/delete/' . $row['id'] . '"><button>Usuń</button></a></td>
					
					
					</tr>';
				}else{
					print '
				<tr>
                  <td>' . intval($row['id']) . '</td>
                  <td>' . htmlspecialchars($row['opinion'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</td>
                  <td>' . htmlspecialchars($row['ip'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</td>
				  <td>' . date('D, d M Y H:i:s', strtotime($row['created'])) . '</td>				  
                </tr>';
				}
            }
			
			
            ?>
          </tbody>
        </table>
    </div>