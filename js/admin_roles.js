export function initTab(){
    window.togglePermission = togglePermission;
    
    
    document.querySelectorAll('button[data-role-id]').forEach(button => {
        button.addEventListener('click', () => {
            const roleID = JSON.parse(button.dataset.roleId);
            //console.log(roleID);
            buildRoleModal(roleID);

            
        });
    });

    document.getElementById('saveNewRoleBtn').addEventListener('click', () =>{
                saveNewRole('newRoleForm');
    });

     document.getElementById('deleteRoleBtn').addEventListener('click', () =>{
                deleteRole('editRoleForm');
    });
    
}


export function togglePermission(roleId, permissionId, btn) {
    fetch('../api/toggle_permission.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `role_id=${roleId}&permission_id=${permissionId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
        const assigned = data.assigned;
        btn.className = 'btn btn-sm ' + (assigned ? 'btn-success' : 'btn-danger');
        btn.innerHTML = assigned ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-x-lg"></i>';
        } else {
        alert('Fehler beim Aktualisieren');
        }
    }); 
}




function  buildRoleModal(roleID){   
        

        fetch('../api/get_roles.php?type=role&id='+roleID)
        .then(response => response.json())
        .then(res => {
            if(res.success){
          
                const role = res.role;
                
                const editRoleForm = document.getElementById("editRoleForm");
                const formHTML =`
                    <input type="hidden" name="id" value="${role['id']}">
                    
                    ${Object.keys(role).filter(key => key!=="id").map(key => {
                        const label = key.charAt(0).toUpperCase()+key.slice(1);
                        let inputField;   
                        if(key!= 'permissions'){
                            if(key == 'description'){
                                inputField = `<textarea rows="3" class="form-control" id="${key}" name="${key}">${role[key]}</textarea>`;

                            }else{
                                inputField = `<input type="text" class="form-control" id="${key}" name="${key}" value="${role[key]}" />`;
                            }
                                                    
                            
                            
                            //console.log(inputField);
                        }else{
                            return null;
                        }
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
            
                editRoleForm.innerHTML=formHTML;
            

                document.getElementById('saveRoleBtn').addEventListener('click', () =>{
                saveRole('editRoleForm');
                });

            }else{
                console.log("ERR"+res.message);

            }
        })
        

    }


    function saveRole(formID){
            const form = document.getElementById(formID);
            const formData = new FormData(form);
            // formData.set("role", roleString);
            // console.log(roleString);
            //console.log(formData.get('id'));

    
            fetch("../api/update_role.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    //alert("Rolle wurde erfolgreich gespeichert.");
                    
                    bootstrap.Modal.getInstance(document.getElementById('roleModal'))?.hide();
                    //initTab();
                } else {
                    //alert("Fehler beim Speichern: " + result.message);
                }
            })
            .catch(error => {
                console.error("Fehler beim Senden:", error);
                alert("Ein Fehler ist aufgetreten.");
                bootstrap.Modal.getInstance(document.getElementById(action+'Modal'))?.hide();
                //initTab();
                    
            });
        }

    function saveNewRole(formID){
        const form = document.getElementById(formID);
            const formData = new FormData(form);
            // formData.set("role", roleString);
            // console.log(roleString);
            //console.log(formData.get('id'));

    
            fetch("../api/update_role.php?new", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    //alert("Rolle wurde erfolgreich gespeichert.");
                    
                    bootstrap.Modal.getInstance(document.getElementById('newRoleModal'))?.hide();
                    location.href = '../app/admin.php?tab=roles';
                    //initTab();
                } else {
                    //alert("Fehler beim Speichern: " + result.message);
                }
            })
            .catch(error => {
                console.error("Fehler beim Senden:", error);
                alert("Ein Fehler ist aufgetreten.");
                bootstrap.Modal.getInstance(document.getElementById('newRoleModal'))?.hide();
                //initTab();
                    
            });


    }

    function deleteRole(formID){
        const form = document.getElementById(formID);
            const formData = new FormData(form);
            // formData.set("role", roleString);
            // console.log(roleString);
            

    
            fetch("../api/delete_role.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    //alert("Rolle wurde erfolgreich gespeichert.");
                    
                    bootstrap.Modal.getInstance(document.getElementById('editRoleModal'))?.hide();
                    location.href = '../app/admin.php?tab=roles';
                    //initTab();
                } else {
                    //alert("Fehler beim Speichern: " + result.message);
                }
            })
            .catch(error => {
                console.error("Fehler beim Senden:", error);
                alert("Ein Fehler ist aufgetreten.");
                bootstrap.Modal.getInstance(document.getElementById('editRoleModal'))?.hide();
                //initTab();
                    
            });


    }
    

    

