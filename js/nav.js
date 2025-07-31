const toggler = document.querySelector('.navbar-toggler');
const navbarCollapse = document.getElementById('mainNavbar');
const profilButton = document.getElementById('profilButton');

// Bootstrap collapse instance
const bsCollapse = new bootstrap.Collapse(navbarCollapse, { toggle: false });

toggler.addEventListener('click', () => {
let isOpen = navbarCollapse.classList.contains('show');
console.log(isOpen);
if (isOpen) {
    // Menü wird zugeklappt → Profil-Button zeigen
    profilButton.classList.remove('d-none');
} else {
    // Menü wird aufgeklappt → Profil-Button verstecken
    profilButton.classList.add('d-none');
}
});
navbarCollapse.addEventListener('shown.bs.collapse', () => {
    profilButton.classList.add('d-none');
});
navbarCollapse.addEventListener('hidden.bs.collapse', () => {
    profilButton.classList.remove('d-none');
});




fetch("../api/get_permissions.php?type=has_only_user_permission&user_id="+user_id)
    .then(response => response.json())
    .then(data => {
        
        if (data == false) {
            //console.log("✅ Zugriff erlaubt.");
            const nav = document.getElementById("navItems")
            const li = document.createElement('li');

            li.innerHTML = `
                <li class="nav-item" id="adminTab"><a class="nav-link" href="/app/admin.php">Admin</a></li>
            `;

            nav.appendChild(li);
            
        } 
    })
    .catch(error => {
    console.error("Fehler beim Abrufen von is_admin.php:", error);
    document.body.innerHTML = "<h1>Fehler beim Berechtigungscheck</h1>";
    });


