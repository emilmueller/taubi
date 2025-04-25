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
      <a href="#" class="btn btn-link">Bibliothek</a>
      <a href="#" class="btn btn-link">Meine Bücher</a>
    </div>
    <div class="d-flex align-items-center">
      <a href="#" class="btn btn-link">
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
          <option value="Mathe">Mathe</option>
          <option value="Deutsch">Deutsch</option>
          <option value="Englisch">Englisch</option>
		  <!-- TODO: dynamisch von api endpoint laden -->
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


	<!-- TODO: dynamisch von api endpoint laden -->
    const books = [
      { title: 'Mathebuch 1', author: 'Autor A', description: 'Mathe Basics', image_url: 'https://picsum.photos/200/300', isbn: '123', seller: '1', seller_name: 'ueli', tags: ['Mathe'] },
      { title: 'Deutschbuch 1', author: 'Autor B', description: 'Grammatik Grundlagen', image_url: 'https://picsum.photos/200/300', isbn: '456', seller: '2', seller_name: 'anna', tags: ['Deutsch'] },
      { title: 'Englischbuch 1', author: 'Autor C', description: 'English for Beginners', image_url: 'https://picsum.photos/200/300', isbn: '789', seller: '3', seller_name: 'ben', tags: ['Englisch'] },
      { title: 'Mixbuch 1', author: 'Autor D', description: 'Für Mathe und Deutsch geeignet', image_url: 'https://picsum.photos/200/300', isbn: '999', seller: '4', seller_name: 'mia', tags: ['Mathe', 'Deutsch'] },
    ];

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
              <button class="btn btn-secondary" onclick="show_message_modal('${book.title}','${book.seller_name}');">Kontakt</button>
            </div>
          </div>
        `;
        bookCardsContainer.appendChild(card);
      });
    }

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

    // Initial render
    renderBooks(books);

    // Filter on input or dropdown change
    searchInput.addEventListener('input', filterBooks);
    fachFilter.addEventListener('change', filterBooks);

    function show_message_modal(book_title, book_seller_name){
      document.getElementById("message_text").value="Hallo "+book_seller_name+" Ich würde gerne "+book_title+" haben.";
      var message_modal = new bootstrap.Modal(document.getElementById('send_message'));
      message_modal.show();
    }

    function send_message(){
      const message = document.getElementById("message_text").value;
      fetch('https://www.jakach.ch/api/something?message=' + encodeURIComponent(message))
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

    function show_notification(message, type = 'success', duration = 3000) {
      const notif = document.getElementById('notification');
      notif.textContent = message;
      notif.classList.remove('success', 'error');
      notif.classList.add(type, 'show');
      setTimeout(() => {
        notif.classList.remove('show');
      }, duration);
    }
  </script>

  <div id="notification"></div>

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
