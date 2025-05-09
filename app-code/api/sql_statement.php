<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	 <title>Jakach-Taubi install</title>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>We are creating the databases used in jakach-taubi, please stand by</h4>
                </div>
                <div class="card-body">
					<p>If the creation fails, please wait a minute and try again. The database server might still be starting at the time.</p>
					<br>
                </div>
			<?php
			$success=1;
			include "../config.php";

			
			// Execute SQL-Statement
			$sql="ALTER TABLE books RENAME COLUMN image to image_data;";


			if ($conn->query($sql) === TRUE) {
				echo '<br><div class="alert alert-success" role="alert">
						'.$sql.' successfully executed.
				</div>';
			} else {
				$success=0;
				echo '<br><div class="alert alert-danger" role="alert">
						Error: ' . $conn->error .'
				</div>';
			}

			

			


			$conn->close();
			?>
			</div>
		</div>
    </div>
</div>
</body>
</html>
