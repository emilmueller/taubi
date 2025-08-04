    let loadedTabs = {};
    let userPermissionList = {};

    const urlParams = new URLSearchParams(window.location.search);
    const desiredTabId = urlParams.get('tab'); // z. B. "users"
    let desiredTabFound = null;

    
    
    
   


    
    
    

    function activateTab(tabId) {
        console.log("ACTIVATE: "+tabId);
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show','active'));
        document.querySelector(`#${tabId}`).classList.add('show','active');
        document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
        document.querySelector(`#${tabId}`+'-tab').classList.add('active');
    }

    


    //Lade die Inhalte für die Tabs
    
       

    fetch('../api/get_permissions.php?type=permission_types&user_id='+user_id)
    .then(res => res.json())
    .then(userPermissions => {
        // console.log(userPermissions);
        let firstAllowedTab = null;
        
        userPermissionList=userPermissions.permissions;
        // console.log(userPermissionList);
        

        const tabs = document.querySelectorAll('#adminTabs [data-permission]');
        for(const tab of tabs){
            const required = tab.dataset.permission.split(" ");
            
            let hasPermission = false;
            //console.log(tab);
            for (const permission of required){
                if (userPermissions.permissions.includes(permission)) {                       
                    hasPermission = true;
                }

            }
            //console.log(tab.children[0].id+" -> "+ hasPermission);
            if (!hasPermission) {   
                                    
                tab.style.display = 'none';
            }else{
                firstAllowedTab ??= tab;
                // prüfen, ob dieser Tab der gewünschte ist
                const link = tab.querySelector('.nav-link');
                const tabTarget = link?.getAttribute('data-bs-target')?.replace('#', '');
                if (desiredTabId && tabTarget === desiredTabId) {
                    desiredTabFound = link;
                }
            }

        }

        
        // //console.log(firstAllowedTab);
        // if (firstAllowedTab) {
        //     const bsTab = new bootstrap.Tab(firstAllowedTab);
        //     bsTab.show();
        // }

        //console.log(loadedTabs);
        document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(btn => {
            btn.addEventListener('shown.bs.tab', async e => {
                const tabId = btn.getAttribute('data-bs-target').replace('#', '');
                const tabPane = document.getElementById(tabId);

                if (!loadedTabs[tabId]) {
                    try{
                       // console.log(`../api/admin_${tabId}.php`);
                        const res = await fetch(`../api/admin_${tabId}.php`);
                        const html = await res.text();
                        //console.log(html);
                        tabPane.innerHTML = html;
                        
                        const module = await import(`../js/admin_${tabId}.js`);
                        const initFn = module[`initTab`];
                        //console.log(initFn);
                        if(typeof initFn === 'function'){
                            //console.log(`../js/admin_${tabId}.js`);
                            initFn();

                        } 
                        loadedTabs[tabId] = true;

                    } catch(err) {
                            tabPane.innerHTML = '<div class="text-danger">Fehler beim Laden.'+err+'</div>';
                    }
                        
                }
            });
        });

        // Wenn kein Tab aktiv ist, einen erlaubten aktivieren
        const active = document.querySelector('.nav-link.active');

        if (!active) {
            
            //const tabLink = firstAllowedTab.querySelector('.nav-link');
            const tabToActivate = desiredTabFound ?? firstAllowedTab?.querySelector('.nav-link');
            if (tabToActivate) {
                const bsTab = new bootstrap.Tab(tabToActivate);
                bsTab.show();
            }
        }
            
    })
    .catch(err => {
        console.error("Berechtigungen konnten nicht geladen werden", err);
    });


    

    // Aktiven Tab sofort laden
    const active = document.querySelector('.nav-link.active');
    if (active) {
        console.log("SHOW: "+active);
        active.dispatchEvent(new Event('shown.bs.tab'));
    } 


    export function hasPermission(permission){
        return userPermissionList.includes(permission);

    }


    

    

















                   



  