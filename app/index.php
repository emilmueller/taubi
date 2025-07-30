<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("LOCATION:/login");
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
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
  <link href="../css/taubi.css" rel="stylesheet">
  
</head>
<body id="body">

 <?php include 'nav.php'; ?>

  <!-- Books Section -->
  <div class="container mt-4">
    <div class="row">
      <div class="col-10">
        <h2>Alle Bücher</h2>
      </div>
      <div class="col-2">
        <button class="btn btn-secondary float-end" type="button" id="addBookButton">
        <i class="bi bi-book"></i>
        Buch hinzufügen
        </button>
      </div>
    </div>

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

    <div class="row row-cols-2 row-cols-md-4 g-4" id="bookCards">
      <!-- Cards will be added dynamically here -->
    </div>
  </div>

  

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
          pages: book.pages,
          book_condition: book.book_condition,
          price: book.price,
          date_published: book.date_published,
          language: book.language,
          isbn: book.isbn,
          seller: book.sold_by || '0', // fallback if no seller
          seller_name: book.seller_name, // if your API doesn't return seller name
          tags: book.tags
        }));

        //console.log(books);
        return books;
      } catch (error) {
        console.error('Error loading books:', error);
      }
    }

    function escapeForHtmlAttr(str) {
      //console.log(str.replace(/'/g, ""));
      return str.replace(/'/g, "");
    }

    // Function to render books
    function renderBooks(filteredBooks) {
      bookCardsContainer.innerHTML = '';
      filteredBooks.forEach(book => {
        const card = document.createElement('div');
        card.classList.add('col');
        card.innerHTML = `
          <div class="card h-100">
            <img src="${book.image_url}" class="card-img-top book_image" alt="Buchbild">
            <div class="card-body">
              <h5 class="card-title">${book.title}</h5>
              <p class="text-muted">Autor: ${book.author}</p>
              <p class="text-muted" style="display:none">ISBN: ${book.isbn}</p>
              <p class="text-muted">Zustand: ${book.book_condition}</p>
              <p class="text-muted">Preis: ${book.price}</p>
	            <p class="text-muted">Verkäufer: ${book.seller_name}</p>
              
            </div>
            <div class="card-footer">
              <button class="btn btn-secondary" onclick="show_message_modal('${escapeForHtmlAttr(book.title)}','${book.seller_name}','${book.seller}');">Kontakt</button>
	            <button class="btn btn-secondary" onclick="show_info_modal('${escapeForHtmlAttr(book.title)}','${book.seller_name}','${book.author}','${book.pages}','${book.language}','${book.date_published}','${book.book_condition}','${book.price}','${book.image_url}');">Mehr Infos</button>
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

    function show_info_modal(book_title, book_seller,book_author,book_pages,book_language, book_date_published,book_condition, book_price,book_image){
      document.getElementById("info_image").src=book_image;
      document.getElementById("info_seller").innerHTML=book_seller;
      document.getElementById("info_title").innerHTML=book_title;
      document.getElementById("info_author").innerHTML=book_author;
      document.getElementById("info_pages").innerHTML=book_pages;
      document.getElementById("info_language").innerHTML=book_language;
      document.getElementById("info_date_published").innerHTML=book_date_published;
      document.getElementById("info_book_condition").innerHTML=book_condition;
      document.getElementById("info_price").innerHTML=book_price;
      var message_modal = new bootstrap.Modal(document.getElementById('info_modal'));
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

  document.addEventListener('DOMContentLoaded', function () {
    const addBookButton = document.getElementById('addBookButton');

    if (addBookButton) {
      addBookButton.addEventListener('click', function () {
        window.location = 'scan_barcode.php';
      });
    }
  });
  



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

<!-- Modal to show info -->
<div class="modal fade" id="info_modal" tabindex="-1" aria-labelledby="send_message" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" style="max-width: 500px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buchinformationen</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-start">
        <img src="" id="info_image" class="img-fluid rounded mb-3 d-block mx-auto" alt="Buchbild" style="max-height: 300px;">
        <p class="card-text"><strong>Titel:</strong> <span id="info_title"></span></p>
        <p class="card-text"><strong>Autor:</strong> <span id="info_author"></span></p>
        <p class="card-text"><strong>Seiten:</strong> <span id="info_pages"></span></p>
        <p class="card-text"><strong>Sprache:</strong> <span id="info_language"></span></p>
        <p class="card-text"><strong>Veröffentlichungsdatum:</strong> <span id="info_date_published"></span></p>
        <p class="card-text"><strong>Verkäufer:</strong> <span id="info_seller"></span></p>
        <p class="card-text"><strong>Zustand:</strong> <span id="info_book_condition"></span></p>
        <p class="card-text"><strong>Preis:</strong> <span id="info_price"></span></p>
      </div>
      <div class="modal-footer justify-content-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schliessen</button>
      </div>
    </div>
  </div>
</div>


</div>
</body>
</html>
