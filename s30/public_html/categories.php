<div class="card">

  <div class="card-body">
              <?php
                if (isset($_POST['submit']) && isset($_POST['name'])) {
                        $name = $_POST['name'];
                        $stmt = $dbh->prepare("INSERT INTO categories(category) VALUES(:name)");
                        $stmt -> execute([':name' => $name]);
                        print '<p style="font-weight: bold; color: green;">Udało się</p>';

                };
            ?>
    <h5 class="card-title">Dodaj kategorie</h5>
	<form action="/categories" method="POST">
    <div class="form-group">
    <input type="text" name="name" placeholder="Nazwa" required class="form-control">
    </div>
    <input type="submit" name="submit"></button>
</form>
    
  </div>
</div>

<div class="card">

  <div class="card-body">
              <?php
				if (isset($_POST['delCat'])) {
                        $category = intval($_POST['delCat']);

                        $stmt = $dbh->prepare("DELETE FROM categories WHERE category = :category");
                        $stmt -> execute([':category' => $category]);
                
                        print '<p style="font-weight: bold; color: green;">Udało się</p>';
				};
            ?>
    <h5 class="card-title">Usuń kategorie</h5>
	<form action="/categories" method="POST">
    <div class="form-group">
        <select name="delCat" class="form-control">
		<?php 
		$stmt = $dbh->prepare("SELECT * FROM categories");
		$stmt -> execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			print '<option value="'. $row['category'] .'"> '. $row['category'] .' </option>';
		}
		?>
    </div>
    <input type="submit" name="submit"></button>
</form>
    
  </div>
</div>


