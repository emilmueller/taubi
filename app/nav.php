
<?php
    session_start();
?>

<script>
    const user_id = <?php echo json_encode($_SESSION['id'] ?? null); ?>;
</script>
<!-- Ribbon at the top -->
<!-- <div class="ribbon d-flex justify-content-between align-items-center"> -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<div class="container-fluid" id="navbar">
    <a class="navbar-brand d-none d-sm-block" href="#">
        <img src="../Taubi_Logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
    </a>
    <!--  Hamburger-Toggler für Mobilansicht -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Navigation umschalten">
      <span class="navbar-toggler-icon"></span>
    </button>


    <!--  Navigationseinträge -->
    <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="navItems">
            <li class="nav-item"><a class="nav-link" href="/app/">Bibliothek</a></li>
            <li class="nav-item"><a class="nav-link" href="/account?my_books">Meine Bücher</a></li>
            
        </ul>
        
        
    </div>
    <!-- 🔹 Profil-Button (rechts) -->
    <div class="dropdown d-flex" id="profilButton">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i><span class="d-none d-md-inline ms-2"><?php echo $_SESSION['username'] ?><span>
        </button>

        <!-- Dropdown-Inhalt -->
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
        <li><a class="dropdown-item" href="/account/profile.php"><i class="bi bi-person-gear"></i> Profil</a></li>
        <li><a class="dropdown-item" href="/login/logout.php"><i class="bi bi-box-arrow-right"></i> Ausloggen</a></li>
        
        
        </ul>
    </div>
            
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="../js/taubi.js"></script>
<!-- <div class="d-flex align-items-center">
    <a href="/account" class="btn btn-link">
    
    </a>
</div> -->
</nav>
<script type='module' src="../js/nav.js"></script>