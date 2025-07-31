
function initTab(){
    loadUsers().then(loadedUsers => {
      users = loadedUsers; // Store the loaded users
      renderUsers(users);  // Render users after loading
    });

}
    
     // Function to load users
    async function loadUsers() {
      try {
        
        const userlist = await fetch('../api/get_users.php');
        const data = await userlist.json();
        const users = await Promise.all(data.map(async user => {
            const roles = await getRoles(user);
            //const permissions = await getPermissions(user);
            return{
                id: user.id,
                username: user.username,
                email: user.email,
                role: roles,  
                banned: user.banned,
                ban_message: user.ban_message,
                last_login: user.last_login,
                //permissions: permissions

            }
        
        
        }));

        //console.log(JSON.stringify(users, null, 2));
        return users;


    
        
      } catch (error) {
        console.error('Error loading users:', error);
      }
    }

    async function getRoles(user){
        //console.log(user);
        const response = await fetch('/api/get_roles.php?type=roles&user_id='+encodeURIComponent(user.id));
        const answer = await response.json();
            
        return answer.roles; 
    }

    

     // Function to render users
    function renderUsers(filteredUsers) {
        
        const table = document.getElementById("userTable");
        table.innerHTML = "";
        const headers = Object.keys(filteredUsers[0]);
        const tableHeader = table.createTHead();
        const headerRow = tableHeader.insertRow();
        headers.forEach(key => {
          
            const th = document.createElement("th");
            th.textContent = key.charAt(0).toUpperCase()+key.slice(1);
            headerRow.appendChild(th);
          

        });
        th = document.createElement("th");
        th.textContent = "Actions";
        headerRow.appendChild(th);

        const tbody = table.createTBody();
        filteredUsers.forEach(user => {
            const row = tbody.insertRow();
            headers.forEach(key => {
              
                const cell = row.insertCell();
                cell.textContent = user[key];
              
            });
            const actionCell = row.insertCell();
            const actionDiv = document.createElement("div");

            actionDiv.className ='d-flex gap-3 h-100 align-items-center';
            const editButton = document.createElement("button");
            editButton.title = "Nutzer:in bearbeiten!";
            
            editButton.innerHTML = '<i class="bi bi-pencil"></i>';
            editButton.className= "btn btn-sm btn-success";
            editButton.addEventListener("click", ()=> {
                editUser(user);
            });
            if(!hasPermission("edit_users")){
              editButton.disabled = true;
            } 

            const deleteButton = document.createElement("button");
            deleteButton.title = "Nutzer:in löschen!";
            deleteButton.innerHTML = '<i class="bi bi-trash"></i>';
            deleteButton.className= "btn btn-sm btn-danger";
            deleteButton.addEventListener("click", ()=> {
                deleteUser(user);
            });

            if(!hasPermission("delete_user")){
              deleteButton.disabled = true;
            }

            const banButton = document.createElement("button");
            if (user.banned === '0'){
              banButton.title = "Nutzer:in sperren!";
              banButton.innerHTML = '<i class="bi bi-ban"></i>';
              banButton.className= "btn btn-sm btn-danger";

            }else{
              banButton.title = "Nutzer:in wieder freigeben!";
              banButton.innerHTML = '<i class="bi bi-ban"></i>';
              banButton.className= "btn btn-sm btn-success";

            }
            
            banButton.addEventListener("click", ()=> {
                handleBan(user);
            });

            if(!hasPermission("ban_users")){
              banButton.disabled = true;
            }
            
            
            actionDiv.append(editButton, deleteButton, banButton);
            actionCell.appendChild(actionDiv);
            
            // actionCell.innerHTML = `
            //     <button class="btn btn-sm btn-success me-1" onclick="editUser(${user})"><i class="bi bi-pencil"></i></button>
            //     <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})"><i class="bi bi-trash"></i></button>
            //     `;
            
        });
    }


    

    

    function  buildUserModal(user, roles){   

        
        const editUserForm = document.getElementById("editUserForm");
        const formHTML =`
            <div class="row mb-3 align-items-center">
                <div class="col-4 text-end fw-semibold">
                    <label class="form-label" for="id">ID</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control input-readonly" id="id" name="id" value="${user.id}" readonly>
                </div>
            </div>
            ${Object.keys(user).filter(key => key!=="id").map(key => {
                const label = key.charAt(0).toUpperCase()+key.slice(1);
                let inputField;                              
                if(key==='role'){
                  inputField = `
                    <select multiple id="${key}" name="${key}[]">
                      ${roles.map(role => `
                        <option value="${role.id}" ${user[key].includes(role.name)||role.name=="User" ? "selected" : ""}>${role.name}</option>
                      `).join("")}
                    </select>
                  `;
                  
                  

                }else if (key === 'banned'){
                    
                    if(hasPermission("ban_users")){
                      inputField =  `<input type="text" class="form-control" id="${key}" name="${key}" value="${user[key]}">`;
                    }else{
                      inputField =  `<input type="text" class="form-control input-readonly" id="${key}" name="${key}" value="${user[key]}" readonly>`;
                    }                   
                }else if(key === 'ban_message'){
                  if(hasPermission("ban_users")){
                      inputField =  `<input type="text" class="form-control" id="${key}" name="${key}" value="${user[key]}">`;
                    }else{
                      inputField =  `<input type="text" class="form-control input-readonly" id="${key}" name="${key}" value="${user[key]}" readonly>`;
                    }   
                  
                  
                }else{
                  inputField = `<input type="text" class="form-control" id="${key}" name="${key}" value="${user[key]}">`;
                }
                //console.log(inputField);
                return `
                  <div class="row mb-3 align-items-center">
                    <div class="col-4 text-end fw-semibold">
                      <label for="${key}" class="form-label">${label}:</label>
                    </div>
                    <div class="col-8">
                      ${inputField}
                    </div>
                  </div>
                  `;
            }).join("")}
        `;
        editUserForm.innerHTML=formHTML;
        const choices = new Choices('#role' ,{
          removeItemButton: true,
          searchEnabled: true

        });

        // Zugriff auf das geöffnete Dropdown-Element
        const dropdown = document.querySelector('.choices__list--dropdown');

        // Eventlistener: Wenn Maus das Dropdown verlässt → Dropdown schließen
        dropdown.addEventListener('mouseleave', () => {
          choices.hideDropdown(); // API von Choices.js
        });

        
        // Modal anzeigen
        const modal = new bootstrap.Modal(document.getElementById("editUserModal"));
        modal.show();
    }

    function buildBanUserModal(user){
      const banUserForm = document.getElementById("banUserForm");
      const formHTML =`
        <div class="row mb-3 align-items-center">
            <div class="col-4 text-end fw-semibold">
                <label class="form-label" for="id">ID</label>
            </div>
            <div class="col-8">
                <input type="text" class="form-control input-readonly" id="id" name="id" value="${user.id}" readonly>
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <div class="col-4 text-end fw-semibold">
                <label class="form-label" for="username">Benutzername</label>
            </div>
            <div class="col-8">
                <input type="text" class="form-control input-readonly" id="username" name="username" value="${user.username}" readonly>
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <div class="col-4 text-end fw-semibold">
                <label class="form-label" for="ban_message">Grund</label>
            </div>
            <div class="col-8">
                <input type="text" class="form-control" id="ban_message" name="ban_message" value="${user.ban_message}">
                <input type="hidden" name="banned" value=1/>
            </div>
        </div>


      `;

      banUserForm.innerHTML = formHTML;
      // Modal anzeigen
      const modal = new bootstrap.Modal(document.getElementById("banUserModal"));
      modal.show();

    }

    function editUser(user){
        
        fetch('../api/get_roles.php?type=rolelist')
            .then(response => response.json())
            .then(data => {
                //console.log(data);
                const roles = data;
                buildUserModal(user, roles);
            })
            .catch(error => {
                console.error("Fehler: ", error);
            })

        
    }

    function handleBan(user){
      if(user.banned=='1'){
        unbanUser(user);
        
      }else{
        buildBanUserModal(user);
      }
      

    }

    
    function deleteUser(user){
        alert("Delete"+user.id);
    }

    function unbanUser(user){
      fetch("../api/update_user.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
              id: user.id,
              banned: 0,
              ban_message: ""
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                //alert("Benutzer wurde erfolgreich entsperrt.");
                loadUsers().then(loadedUsers => {
                users = loadedUsers; // Store the loaded users
                renderUsers(users);  // Render users after loading
                });
            } else {
            //alert("Fehler beim Entsperren: " + result.message);
            }
        })
        .catch(error => {
            console.error("Fehler beim Senden:", error);
            alert("Ein Fehler ist aufgetreten.");loadUsers().then(loadedUsers => {
                users = loadedUsers; // Store the loaded users
                renderUsers(users);  // Render users after loading
                 });
        });


    }

    function saveUser(action){
        const form = document.getElementById(action+'Form');
        const formData = new FormData(form);
        // formData.set("role", roleString);
        // console.log(roleString);

        fetch("../api/update_user.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                //alert("Benutzer wurde erfolgreich gespeichert.");
                // ggf. Modal schließen, Tabelle neu laden etc.
                // Modal anzeigen
                bootstrap.Modal.getInstance(document.getElementById(action+'Modal'))?.hide();
                

                loadUsers().then(loadedUsers => {
                users = loadedUsers; // Store the loaded users
                renderUsers(users);  // Render users after loading
                });
            } else {
            //alert("Fehler beim Speichern: " + result.message);
            }
        })
        .catch(error => {
            console.error("Fehler beim Senden:", error);
            alert("Ein Fehler ist aufgetreten.");
            bootstrap.Modal.getInstance(document.getElementById(action+'Modal'))?.hide();
                

                loadUsers().then(loadedUsers => {
                users = loadedUsers; // Store the loaded users
                renderUsers(users);  // Render users after loading
                 });
        });
    }

   
        
        
    
    
    


