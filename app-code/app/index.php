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

  <!-- Books Section -->
  <div class="container mt-4">
    <h2>Alle Bücher</h2>

    <!-- Search + Fach Dropdown -->
    <div class="search-form row">
      <div class="col-md-9 mb-2">
        <input type="text" id="searchInput" class="form-control" placeholder="Suche nach Titel, ISBN, Autor oder Beschreibung...">
      </div>
      <div class="col-md-3 mb-2">
	<select id="fachFilter" class="form-select">
	  <option value="">Alle Fächer</option>
	</select>

      </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4" id="bookCards">
      <!-- Cards will be added dynamically here -->
    </div>
  </div>

  <!-- Bootstrap 5.3 JS and required Popper -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

  <script>
    const bookCardsContainer = document.getElementById('bookCards');
    const searchInput = document.getElementById('searchInput');
    const fachFilter = document.getElementById('fachFilter');

    // Function to load books
    async function loadBooks() {
      try {
        const response = await fetch('/api/get_books.php');
        const data = await response.json();

        // Transform API data to match your book object structure
        const books = data.map(book => ({
          title: book.title,
          author: book.author,
          description: `${book.publisher}, ${book.book_condition}, ${book.language}, ${book.pages} pages`, // or customize this
          image_url: book.image_url,
          isbn: book.isbn,
          seller: book.sold_by || '0', // fallback if no seller
          seller_name: book.seller_name, // if your API doesn't return seller name
          tags: book.tags
        }));

        console.log(books);
        return books;
      } catch (error) {
        console.error('Error loading books:', error);
      }
    }

    // Function to render books
    function renderBooks(filteredBooks) {
      bookCardsContainer.innerHTML = '';
      filteredBooks.forEach(book => {
        const card = document.createElement('div');
        card.classList.add('col');
        card.innerHTML = `
          <div class="card h-100">
            <img src="${book.image_url}" class="card-img-top" alt="Buchbild">
            <div class="card-body">
              <h5 class="card-title">${book.title}</h5>
              <p class="card-text">${book.description}</p>
              <p class="text-muted">Autor: ${book.author}</p>
              <p class="text-muted" style="display:none">ISBN: ${book.isbn}</p>
              <button class="btn btn-secondary" onclick="show_message_modal('${book.title}','${book.seller_name}','${book.seller}');">Kontakt</button>
            </div>
          </div>
        `;
        bookCardsContainer.appendChild(card);
      });
    }

    // Function to filter books based on search input and selected Fach
    function filterBooks() {
      const query = searchInput.value.toLowerCase();
      const selectedFach = fachFilter.value;

      const filtered = books.filter(book => {
        const matchesQuery = book.title.toLowerCase().includes(query)
          || book.author.toLowerCase().includes(query)
          || book.isbn.toLowerCase().includes(query)
          || book.description.toLowerCase().includes(query);

        const matchesFach = !selectedFach || (book.tags && book.tags.includes(selectedFach));
        return matchesQuery && matchesFach;
      });

      renderBooks(filtered);
    }

    // Call the function to load books and then render them
    loadBooks().then(loadedBooks => {
      books = loadedBooks; // Store the loaded books
      renderBooks(books);  // Render books after loading
    });

    // Filter on input or dropdown change
    searchInput.addEventListener('input', filterBooks);
    fachFilter.addEventListener('change', filterBooks);

    // Function to show the message modal
    function show_message_modal(book_title, book_seller_name,book_seller_id){
      document.getElementById("message_text").value="Hallo "+book_seller_name+" Ich würde gerne "+book_title+" haben.";
      document.getElementById("message_seller_id").value=book_seller_id;
      var message_modal = new bootstrap.Modal(document.getElementById('send_message'));
      message_modal.show();
    }

    // Function to send a message
    function send_message(){
      const message = document.getElementById("message_text").value;
      const seller_id = document.getElementById("message_seller_id").value;
      fetch('/api/send_message.php?message=' + encodeURIComponent(message) +"&seller_id="+encodeURIComponent(seller_id))
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            show_notification("Nachricht wurde erfolgreich gesendet.", 'success');
          } else {
            show_notification("Nachricht konnte nicht gesendet werden.", 'error');
          }
        })
        .catch(error => {
          show_notification("Nachricht konnte nicht gesendet werden.", 'error');
        });
    }

    // Function to show notifications
    function show_notification(message, type = 'success', duration = 3000) {
      const notif = document.getElementById('notification');
      notif.textContent = message;
      notif.classList.remove('success', 'error');
      notif.classList.add(type, 'show');
      setTimeout(() => {
        notif.classList.remove('show');
      }, duration);
    }

   async function loadTags() {
    try {
      const response = await fetch('/api/get_tags.php');
      const data = await response.json();

      const fachFilter = document.getElementById('fachFilter');

      data.tags.forEach(tag => {
        const option = document.createElement('option');
        option.value = tag;
        option.textContent = tag;
        fachFilter.appendChild(option);
      });
    } catch (error) {
      console.error('Fehler beim Laden der Tags:', error);
    }
  }

  // Call this when your page loads
  document.addEventListener('DOMContentLoaded', loadTags);
  </script>

  <div id="notification"></div>

  <!-- Modal to send message -->
  <div class="modal fade" id="send_message" tabindex="-1" aria-labelledby="send_message" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nachricht senden</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Gib eine Kurze Nachricht für den/die Verkäufer*in des Buches an.
          <textarea class="form-control mt-3" id="message_text" rows="4" placeholder="Deine Nachricht..."></textarea>
          <input type="text" style="display:none" id="message_seller_id">
	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="send_message()">Absenden</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schliessen</button>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
