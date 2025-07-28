<!-- <?php

    
            ob_start();
            include '../api/get_users.php';
            $res = json_decode(ob_get_clean(),true);

            print_r($res[0]['id']);


    
?> -->


<script>



     // Function to load books
    async function loadUsers() {
      try {
        const userlist = await fetch('../api/get_users.php');
        const data = await userlist.json();
        const users = await Promise.all(data.map(async user => {
            const roles = await getRoles(user);
            return{
                id: user.id,
                username: user.username,
                email: user.email,
                role: roles,  
                banned: user.banned,
                ban_message: user.ban_message,
                last_login: user.last_login

            }
        
        
        }));

        console.log(JSON.stringify(users, null, 2));
        return users;


    
        
      } catch (error) {
        console.error('Error loading users:', error);
      }
    }

    async function getRoles(user){
        //console.log(user);
        const response = await fetch('/api/get_roles.php?roles='+encodeURIComponent(user.role));
        const answer = await response.json();
            
        return answer.roles; 
    }

     // Function to render books
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
            const editButton = document.createElement("button");
            editButton.innerHTML = '<i class="bi bi-pencil"></i>';
            editButton.className= "btn btn-sm btn-success me-1";
            editButton.addEventListener("click", ()=> {
                editUser(user);
            })
            actionCell.appendChild(editButton);

            const deleteButton = document.createElement("button");
            deleteButton.innerHTML = '<i class="bi bi-trash"></i>';
            deleteButton.className= "btn btn-sm btn-danger me-1";
            deleteButton.addEventListener("click", ()=> {
                deleteUser(user);
            })
            actionCell.appendChild(deleteButton);
            
            // actionCell.innerHTML = `
            //     <button class="btn btn-sm btn-success me-1" onclick="editUser(${user})"><i class="bi bi-pencil"></i></button>
            //     <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})"><i class="bi bi-trash"></i></button>
            //     `;
            
        });
    }


    loadUsers().then(loadedUsers => {
      users = loadedUsers; // Store the loaded users
      renderUsers(users);  // Render users after loading
    });

    function editUser(user){
        
        fetch('../api/get_roles.php?type=rolelist')
            .then(response => {
                if(!response.ok){
                    throw new Error("Fehler beim Laden der Rollen.");
                }
                return response.json();
            })
            .then(data => {
                const roles = data.roles;
                buildUserModal(user, roles);
            })
            .catch(error => {
                console.error("Fehler: ", error);
            })

        
    }

    function  buildUserModal(user, roles){   

        // console.log(roles);

        const editUserForm = document.getElementById("editUserForm");
        const formHTML =`
            <div class="row mb-3 align-items-center">
                <div class="col-4 text-end fw-semibold">
                    <label class="form-label" for="id">ID</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" id="id" name="id" value="${user.id}" readonly>
                </div>
            </div>
            ${Object.keys(user).filter(key => key!=="id").map(key => {
                const label = key.charAt(0).toUpperCase()+key.slice(1);
                
                const inputField = key === "role"
                  ? `
                    <select class="form-select" multiple size=8 id="${key}" name="${key}[]">
                      ${roles.map(role => `
                        <option value="${role}" ${user[key].includes(role) ? "selected" : ""}>${role}</option>
                      `).join("")}
                    </select>
                  `
                  : `
                    <input type="text" class="form-control" id="${key}" name="${key}" value="${user[key]}">
                  `;

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
        // Modal anzeigen
        const modal = new bootstrap.Modal(document.getElementById("editUserModal"));
        modal.show();
    }

    function deleteUser(user){
        alert("Delete"+user.id);
    }

    function saveUser(){
        const form = document.getElementById("editUserForm");
        let roleString = "";
        const formRoles = document.getElementById("role");
        Array.from(formRoles.options).forEach(option => {
            if(option.selected){
                roleString+="1";
            }else{
                roleString+="0";
            }

        });

        

        const formData = new FormData(form);
        formData.set("role", roleString);
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
                bootstrap.Modal.getInstance(document.getElementById('editUserModal'))?.hide();
                

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
            bootstrap.Modal.getInstance(document.getElementById('editUserModal'))?.hide();
                

                loadUsers().then(loadedUsers => {
                users = loadedUsers; // Store the loaded users
                renderUsers(users);  // Render users after loading
                 });
        });






        // fetch('../api/get_roles.php?type=rolelist')
        //     .then(response => {
        //         if(!response.ok){
        //             throw new Error("Fehler beim Laden der Rollen.");
        //         }
        //         return response.json();
        //     })
        //     .then(data => {
        //         const roles = data.roles;
        //         saveUser_with_Roles(roles);
        //     })
        //     .catch(error => {
        //         console.error("Fehler: ", error);
        //     });
    }

   
        
        
    
    
    


</script>

<div class="container mt-4">
  <table class="table table-striped" id="userTable"></table>
</div>

<!-- Modal User Edit-->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Benutzer bearbeiten</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm">

        
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
        <button type="button" class="btn btn-primary" onclick="saveUser()">Speichern</button>
      </div>
      
    </div>
  </div>
</div>