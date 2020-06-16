<form action="/register" method="POST">
		<div class="g-recaptcha" data-sitekey="<?=$config['recaptcha_public'] ?>"></div>
        <input type="text" name="remail" placeholder="Adres e-mail">
        <input type="password" name="rpassword" placeholder="Hasło">
		<input type="submit" value="Załóż konto">
        </form>
		
		<?php
		if (!defined('IN_INDEX')) { 
			exit("Nie można uruchomić tego pliku bezpośrednio."); 
		}		
        if (isset($_POST['rpassword'])) {
			$rpassword = $_POST['rpassword'];
            $remail = $_POST['remail'];
            $ip = $_SERVER['REMOTE_ADDR'];
			
            if (preg_match('/^[a-zA-Z0-9\-\_\.]+\@[a-zA-Z0-9\-\_\.]+\.[a-zA-Z]{2,5}$/D', $remail)) {
				
				$secret=$config['recaptcha_private'];
				$response=$_POST['g-recaptcha-response'];
				
				$recaptcha = new \ReCaptcha\ReCaptcha($secret);
				$resp = $recaptcha ->verify($response, $ip);
				
				if ($resp->isSuccess()) {
					$rpassword = password_hash($rpassword, PASSWORD_DEFAULT);
					try {
						$stmt = $dbh->prepare('INSERT INTO users (id, email, password, created) VALUES (null, :remail, :rpassword, NOW())');
						$stmt->execute([':remail' => $remail, ':rpassword' => $rpassword]);
						print '<span style="color: green;">Konto zostało założone.</span>';
					} catch (PDOException $e) {
						print '<span style="color: red;">Podany adres email jest już zajęty.</span>';
					}
				}else {
					print '<p style="font-weight: bold; color: red;">wypelnij captcha</p>';
					$errors = $resp->getErrorCodes();
				}
			} else {
                print '<p style="font-weight: bold; color: red;">Nieprawidłowy email.</p>';
            }
		}
        ?>