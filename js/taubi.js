function hasUserPermission(id, permission_type){
    //console.log("CHECK: "+id+" for "+permission_type);
    return fetch('../api/get_permissions.php?type=has_permission&user_id='+id+'&permission_type='+permission_type)
        .then(res => res.json())
        .then(userPermission => {
            
            if(userPermission){
                return true;
            }else{
                return false;
            }
            
        })
        .catch(err => {
            console.error('Fehler beim Laden der Berechtigungen:', err);
        });
        


}


