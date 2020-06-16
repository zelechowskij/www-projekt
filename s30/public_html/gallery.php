<div class="dropdown">

		<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

			<?php if (isset($_GET['show'])) {
				$name = strval($_GET['show']);
				$stmt = $dbh->prepare("SELECT * FROM categories WHERE category = :name");
				$stmt -> execute([':name' => $name]);
				$category = $stmt->fetch();
				print $category['category'];
				
			}else { 
				print 'Kategoria';
			}?>
		</button>
		
		
		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

			<?php
				$stmt = $dbh->prepare("SELECT * FROM categories");
				$stmt -> execute();
				
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					print '<a class="dropdown-item" href="/gallery/show/'. $row['category'] .'">'. $row['category'] .'</a>';
				}  
			?>
		</div>
	</div>
</div>


<div class="row" id="gallery" data-toggle="modal" data-target="#modal">

	<?php
	    if (isset($_GET['show'])) {
            $category = strval($_GET['show']);
			$stmt = $dbh->prepare("SELECT * FROM images WHERE category = :category");
			$stmt -> execute([':category' => $category]);
		}else { 
			$stmt = $dbh->prepare("SELECT * FROM images"); 
			$stmt -> execute();
		}

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			print '
				<div class="col-12 col-sm-6 col-md-3 col-lg-3 crop">
					<a class="black-text" href="https://s30.labwww.pl/' . $row['name'] . '" data-target="#carousel" >
						<img class="w-100" src="https://s30.labwww.pl/' . $row['name'] . '">
						<h3 class="text-center">' . htmlspecialchars($row['title'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</h3>
					</a>
				</div>';

		}?>
</div>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-xl" role="document">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body">
		<div id="carousel" class="carousel slide" data-ride="carousel">


			<div class="carousel-inner">
				<?php
				$stmt -> execute();
				$flagFirst = true; 
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					if ($flagFirst){
						print '<div class="carousel-item active">';
						$flagFirst = false;
					} else{
						print '<div class="carousel-item">';
					}
					print '
						<img class="d-block w-100" src="https://s30.labwww.pl/' . $row['name'] . '" alt="' . $row['title'] . '">
						<br>
						<br>
						<div class="carousel-caption d-none d-md-block">
							<h6 class="tytul">' . htmlspecialchars($row['title'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</h5>
							<p class="opis">' . htmlspecialchars($row['description'], ENT_QUOTES | ENT_HTML401, 'UTF-8') . '</p>
						</div>
					</div>';
				}?>
			</div>


			<a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
</div>
</div>
</div>

