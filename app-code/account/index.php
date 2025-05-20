<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("LOCATION:/login/login.php");
    exit();
}
?>
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
      <a href="/" class="btn btn-link">Bibliothek</a>
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
	<div class="container mt-4">
    <h2>Meine Bücher</h2>

    	<div class="row row-cols-1 row-cols-md-3 g-4" id="bookCards">
      		<!-- Cards will be added dynamically here -->
	  </div>
	</div>

    </div>
  </div>
</div>
<script>
    //load user data
    document.addEventListener('DOMContentLoaded', () => {
      // Fetch user data from the backend API
      fetch('/api/get_user_data.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Failed to fetch user data');
          }
          return response.json();
        })
        .then(user => {
          // Populate the fields with user data
          document.getElementById('user-name').textContent = user.username;
          document.getElementById('user-email').textContent = user.email;

        })
        .catch(error => {
          console.error('Error:', error);
		//TODO: show error
        });
	});

	const bookCardsContainer = document.getElementById('bookCards');


    // Function to load books for the specified user
    async function loadBooks() {
      try {
        const response = await fetch(`/api/get_books.php`);
        const data = await response.json();

        // Transform API data to match your book object structure
        const books = data.map(book => ({
          title: book.title,
          author: book.author,
          description: `${book.publisher}, ${book.book_condition}, ${book.language}, ${book.pages} pages`,
          image_url: book.image_url,
          isbn: book.isbn,
          seller: book.sold_by || '0',
          seller_name: book.seller_name,
          tags: book.tags,
	        id: book.id
        }));

        return books;
      } catch (error) {
        console.error('Error loading books:', error);
      }
    }

    // Function to render books
    function renderBooks(filteredBooks) {
      bookCardsContainer.innerHTML = '';
      filteredBooks.forEach(book => {
	if(book.seller==1){ //TODO: add actuall user id here e.g. from $_SESSIOn
        const card = document.createElement('div');
        card.classList.add('col');
        card.innerHTML = `
          <div class="card h-100">
            <img src="${book.image_url}" class="card-img-top" alt="Buchbild">
            <div class="card-body">
              <h5 class="card-title">${book.title}</h5>
              <!--<p class="card-text">${book.description}</p>-->
              <p class="text-muted">Autor: ${book.author}</p>
              <p class="text-muted" style="display:none">ISBN: ${book.isbn}</p>
              <div class="position-absolute bottom-0 end-0 mb-1 mr-1">
                <button class="btn btn-success" onclick="edit_book(${book.id})"><span class="bi bi-pencil"></span></button> <!-- edit Button -->
                <button class="btn btn-danger" onclick="delete_book(${book.id})"><span class="bi bi-trash"></span></button> <!-- delete Button -->
              </div>
            </div>
          </div>
        `;
        bookCardsContainer.appendChild(card);
	}
      });
    }

    // Call the function to load books and then render them
    loadBooks().then(loadedBooks => {
      renderBooks(loadedBooks);  // Render books after loading
    });

    // Function to handle the "Delete" button 
    function delete_book(book_id) {
      fetch("/api/delete_book.php?id="+book_id); // delete
      loadBooks().then(loadedBooks => {
      		renderBooks(loadedBooks);  // Render books after loading
    	});
    }

    // Function to handle the "Delete" button 
    function edit_book(book_id) {
      window.open("/app/getbook.php?id="+book_id+"&action=db_search", "_self"); // edit 
      
    }
</script>
</body>
</html>
