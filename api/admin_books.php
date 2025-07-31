<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("LOCATION:/login");
    exit();
}
?>


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
        <input type="text" id="searchInput" class="form-control" placeholder="Suche nach Titel, ISBN, Autor, Anbieter:in oder Beschreibung...">
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

  

  

  <div id="notification"></div>

  


</div>
</body>
</html>
