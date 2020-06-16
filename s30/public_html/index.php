<?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
	define("IN_INDEX", 1);
	require __DIR__ . '/vendor/autoload.php';
    include("config.inc.php");
	include("functions.inc.php");
	

    if (isset($config) && is_array($config)) {

        try {
            $dbh = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4', $config['db_user'], $config['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Nie mozna polaczyc sie z baza danych: " . $e->getMessage();
            exit();
        }

    } else {
        exit("Nie znaleziono konfiguracji bazy danych.");
    }

  

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    <meta charset="utf-8">
    <title>Galeria zdjęć</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css" />
    <link rel="stylesheet"href="https://s30.labwww.pl/style.css">
    <script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script>
    <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>

		<style>
        html {
            position: relative;
            min-height: 100%;
        }
        body {
            margin-bottom: 60px;
        }
        .footer {
          position: absolute;
          bottom: 0;
          width: 100%;
          height: 60px;
          line-height: 60px;
          background-color: #f5f5f5;
        }
        </style>
    </head>
    <body>

	<!--nagłówek-->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	  <a class="navbar-brand" href="/">Moja galeria</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	  </button>
	  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
		<div class="navbar-nav">
		  <a class="nav-item nav-link" href="/add_pic">Dodaj zdjęcie</a>
		  <a class="nav-item nav-link" href="/categories">Kategorie</a>
		  <a class="nav-item nav-link" href="/instruction">Instrukcja</a>

		</div>
	  </div>
	</nav>


				<div class="container mb-5">
                <?php
                    $allowed_pages = ['gallery', 'add_pic', 'index','categories', 'instruction'];
					$protected_pages = ['articles_add'];
                    if (isset($_GET['page']) && $_GET['page'] && (!in_array($_GET['page'], $protected_pages) || isset($_SESSION['id']))) {
                        if (file_exists($_GET['page'] . '.php')) {
                            include($_GET['page'] . '.php');
                        } else {
                            print 'Plik ' . $_GET['page'] . '.php nie istnieje.';
                        }
                    } else {
                        include('gallery.php');
                    }
                ?>
				</div>

	<footer class="footer navbar-fixed-bottom" style="background-color: #f5f5f5;">
	  <div class="container">
		<span class="text-muted">Aktualna data: <?php print date('Y-m-d'); ?></span>
		<span class="text-muted">Żelechowski Jarosław</span>
	  </div>
	</footer>


    </body>
</html>
<script>

  window.onload = function() {
    document.addEventListener("contextmenu", function(e){
      e.preventDefault();
    }, false);

    document.addEventListener("keydown", function(e) {
      // "I" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
        disabledEvent(e);
      }
      // "J" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
        disabledEvent(e);
      }
      // "S" key + macOS
      if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        disabledEvent(e);
      }
      // "U" key
      if (e.ctrlKey && e.keyCode == 85) {
        disabledEvent(e);
      }
      // "F12" key
      if (event.keyCode == 123) {
        disabledEvent(e);
      }
    }, false);

    function disabledEvent(e){
      if (e.stopPropagation){
        e.stopPropagation();
      } else if (window.event){
        window.event.cancelBubble = true;
      }
      e.preventDefault();
      return false;
    }
  };
</script>
