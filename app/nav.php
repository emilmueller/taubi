

<!-- Ribbon at the top -->
<div class="ribbon d-flex justify-content-between align-items-center">
<div id="navbar">
    <a href="/" class="btn btn-link">Bibliothek</a>
    <a href="/account?my_books" class="btn btn-link">Meine Bücher</a>
</div>
<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
     <i class="bi bi-person-circle"></i> Konto
    </button>

    <!-- Dropdown-Inhalt -->
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <li><a class="dropdown-item" href="/account/profile.php"><i class="bi bi-person-gear"></i> Profil</a></li>
      <li><a class="dropdown-item" href="/login/logout.php"><i class="bi bi-box-arrow-right"></i> Ausloggen</a></li>
      
    </ul>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<!-- <div class="d-flex align-items-center">
    <a href="/account" class="btn btn-link">
    
    </a>
</div> -->
</div>
<script>
    
    document.addEventListener("DOMContentLoaded", function () {
    fetch("../api/is_admin.php")
        .then(response => response.json())
        .then(data => {
            if (data.success === true) {
                //console.log("✅ Zugriff erlaubt.");
                const nav = document.getElementById("navbar")
                const link = document.createElement("a");
                link.href = "/app/user_admin.php";
                link.textContent = "Admin";
                link.className = "btn btn-link"; // z. B. für Bootstrap-Styling

                // Link anhängen
                nav.appendChild(link);
                
            } 
        })
        .catch(error => {
        console.error("Fehler beim Abrufen von is_admin.php:", error);
        document.body.innerHTML = "<h1>Fehler beim Berechtigungscheck</h1>";
        });
    });


</script>