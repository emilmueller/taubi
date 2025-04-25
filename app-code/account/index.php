<!DOCTYPE html>
<html data-bs-theme="dark" lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Taubi - Book Exchange</title>
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      transition: background-color 0.3s, color 0.3s;
    }

    .ribbon {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
    }

    .ribbon a {
      color: white;
      margin-right: 20px;
      text-decoration: none;
    }

    .card-deck .card {
      margin-bottom: 20px;
    }

    .search-form {
      margin: 20px 0;
    }

    #notification {
      position: fixed;
      bottom: 20px;
      left: 20px;
      z-index: 9999;
      background-color: #333;
      color: #fff;
      padding: 12px 20px;
      border-radius: 6px;
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
    }

    #notification.show {
      opacity: 1;
    }

    #notification.success {
      background-color: #28a745;
    }

    #notification.error {
      background-color: #dc3545;
    }
  </style>
</head>
<body id="body">

  <!-- Ribbon at the top -->
  <div class="ribbon d-flex justify-content-between align-items-center">
    <div>
      <a href="/" class="btn btn-link">Bibliothekliost</a>
      <a href="/account?my_books" class="btn btn-link">Meine Bücher</a>
    </div>
    <div class="d-flex align-items-center">
      <a href="/account" class="btn btn-link">
        <i class="bi bi-person-circle"></i> Konto
      </a>
    </div>
  </div>

<div class="container my-5">
  <div class="row">
     <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <h3 id="user-name" class="card-title">Loading Username...</h3>
            <p id="user-email" class="text-muted">Loading Email...</p>
          </div>
        </div>
      </div>
    <div class="col-md-8">
    	<p>your books here</p>
        <!-- TODO: populate from backendapi-->
    </div>
  </div>
</div>
<script>
    //load user data
    document.addEventListener('DOMContentLoaded', () => {
      // Fetch user data from the backend API
      fetch('/api/account/get_user_data.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to fetch user data');
          }
          return response.json();
        })
        .then(user => {
          // Populate the fields with user data
          document.getElementById('user-name').textContent = user.name;
          document.getElementById('user-email').textContent = user.email;

        })
        .catch(error => {
          console.error('Error:', error);
		//TODO: show error
        });
	});
</script>
</body>
</html>
