 // Call the function to load tags and then render them
 export function initTab(){
    //console.log("load Tag Tab");
    loadTags().then(loadedTags => {
      tags = loadedTags; // Store the loaded tags
      renderTags(tags);  // Render tags after loading
      addPlusButton(tags);
    });
 }
    
     // Function to load tags
    async function loadTags() {
      try {
        
        const taglist = await fetch('../api/get_tags.php');
        const data = await taglist.json();
        //console.log(data.tags);
        const tags = await Promise.all(data.tags.map(async tag => {
            return{
                id: tag.id,
                name: tag.name
            }
         }));

        //console.log(JSON.stringify(users, null, 2));
        return tags;


    
        
      } catch (error) {
        console.error('Error loading tags:', error);
      }
    }


   

    function addPlusButton(tags){
        const tagsTableDiv = document.getElementById('tagsTableDiv');
        const tbody = tagsTableDiv.querySelector('table').querySelector('tbody');
        const headers = Object.keys(tags[0]);
        
        const actionDiv = document.createElement("div");

        actionDiv.className ='d-flex';
        const addLineButton = document.createElement("button");
    
        addLineButton.title = "Füge eine Zeile hinzu";
        addLineButton.innerHTML = '<i class="bi bi-plus-square"></i>';
        addLineButton.className= "btn btn-sm btn-secondary";
        addLineButton.addEventListener("click", ()=> {
            addLine(tbody, {'id':'', 'name':''}, headers);
        });
        actionDiv.appendChild(addLineButton)
        tagsTableDiv.appendChild(actionDiv);
    }


     // Function to render tags
    function renderTags(tags) {
        
        const table = document.getElementById("tagsTable");

        table.innerHTML = "";
        const headers = Object.keys(tags[0]);
        const tableHeader = table.createTHead();
        const headerRow = tableHeader.insertRow();
        headers.forEach(key => {
            if(key!== 'id'){
                const th = document.createElement("th");
                th.textContent = key.charAt(0).toUpperCase()+key.slice(1);
                headerRow.appendChild(th);
          

            }
            

        });
        // th = document.createElement("th");
        // th.textContent = "Actions";
        // headerRow.appendChild(th);

        const tbody = table.createTBody();
        tags.forEach(tag => {
            addLine(tbody,tag,headers);
            
        });

        
    }

    function updateTag(id, input){
        console.log("ID: "+id+" -> Name: "+input.value);
        fetch('../api/update_tag.php', {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
              id: id,
              name: input.value
            })
        })
        .then(response => response.json())
        .then(result => {
            if (!result.success) {
                alert(result.message);
                fetch('../api/get_tags.php', {
                    method: "POST",
                    headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                    id: id
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        console.log("From get_tags.php: "+result.tag.id+" - "+result.tag.name);
                        input.value = result.tag.name;

                    }else{
                        console.log(result);
                    }
                });
                //console.log(result);
               
            } 
            //  loadTags().then(loadedTags => {
            //     tags = loadedTags; // Store the loaded tags
            //     renderTags(tags);  // Render tags after loading
            //     });
        })
        .catch(error => {
            
            // loadTags().then(loadedTags => {
            //     tags = loadedTags; // Store the loaded tags
            //     renderTags(tags);  // Render tags after loading
            // });
            
        });

    }

    function deleteTag(tag){
        fetch("../api/delete_tag.php?id="+tag.id) // delete
        .then(response => response.json())
        .then( result => {
            if(result.success){
                loadTags().then(loadedTags => {
                    renderTags(loadedTags);  // Render Tags after loading

                })
            }

        });
            
        


    }

    
    function addLine(tbody, tag, headers){
        
        
        const row = tbody.insertRow();
        headers.forEach(key => {
            const inputField = document.createElement('input');
            inputField.className= 'form-control';
            inputField.type = 'text';
            inputField.id = key+tag.id;
            inputField.name = key;
            inputField.value = tag[key];
            if(key == 'id'){
                inputField.type = 'hidden';
                row.appendChild(inputField);
            }else{
                inputField.addEventListener('change', function() {
                    updateTag(tag.id, this);
                });
                const cell = row.insertCell();
                cell.appendChild(inputField);
                row.appendChild(cell);
                
                
            }
            
            
            
            
        });
        

        const actionCell = row.insertCell();
        const actionDiv = document.createElement("div");

        actionDiv.className ='d-flex h-100 align-items-center';
        const deleteButton = document.createElement("button");
        deleteButton.title = "Tag löschen";
        
        deleteButton.innerHTML = '<i class="bi bi-trash"></i>';
        deleteButton.className= "btn btn-sm btn-danger";
        deleteButton.addEventListener("click", ()=> {
            deleteTag(tag);
        });

        actionDiv.append(deleteButton);
        actionCell.appendChild(actionDiv);

    }






