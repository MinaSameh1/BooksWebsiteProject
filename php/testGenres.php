        <?php 
			include_once('database.php');
			$stmt = $pdo->prepare("SELECT genreName FROM genres");
			$stmt->execute();
			$genres = $stmt->fetchAll();

			foreach( $genres as $genre ){ 
			?>
		<a href="#" class="filter1"> <?php echo $genre[0]; ?> </a> <br>
		<?php } ?>
		