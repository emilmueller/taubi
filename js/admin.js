    const loadedTabs = {};
    let userPermissionList = {};
    
    //console.log("load Admin "+user_id);
   




    // document.addEventListener("DOMContentLoaded", () => {
    // const params = new URLSearchParams(window.location.search);
    // const tab = params.get('tab');
    //     if(tab){
    //         activateTab(tab);
    //     }
    // });

    function activateTab(tabId) {
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
        for( const tab of tabs){
            const required = tab.dataset.permission.split(" ");
            
            let hasPermission = false;
            //console.log(required);
            for (const permission of required){
                if (userPermissions.permissions.includes(permission)) {                       
                    hasPermission = true;
                }

            }
            //console.log(tab.children[0].id+" -> "+ hasPermission);
            if (!hasPermission) {   
                                    
                tab.parentElement.style.display = 'none';
            }else{
                firstAllowedTab ??= tab;
            }

        }
        //console.log(firstAllowedTab);
        if (firstAllowedTab) {
            const bsTab = new bootstrap.Tab(firstAllowedTab);
            bsTab.show();
        }
        document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(btn => {
            btn.addEventListener('shown.bs.tab', async e => {
                const tabId = btn.getAttribute('data-bs-target').replace('#', '');
                const tabPane = document.getElementById(tabId);

                if (!loadedTabs[tabId]) {
                    try{
                       // console.log(`../api/admin_${tabId}.php`);
                        const res = await fetch(`../api/admin_${tabId}.php`);
                        const html = await res.text();
                        // console.log(html);
                        tabPane.innerHTML = html;
                        
                        const module = await import(`../js/admin_${tabId}.js`);
                        const initFn = module[`initTab`];
                        //console.log(initFn);
                        if(typeof initFn === 'function'){
                            console.log(`../js/admin_${tabId}.js`);
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

        if (!active && firstAllowedTab) {
            const tabLink = firstAllowedTab.querySelector('.nav-link');
            if (tabLink) {
                const bsTab = new bootstrap.Tab(tabLink);
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
        active.dispatchEvent(new Event('shown.bs.tab'));
    } 


    export function hasPermission(permission){
        return userPermissionList.includes(permission);

    }

    

















                   



  