let xhr = new XMLHttpRequest();
let link;
if(pathLevels === 1){
    link = 'php/get_coordinates.php';
}else if(pathLevels === 2){
    link = '../php/get_coordinates.php';
}
xhr.open('POST', link, true);
xhr.onload = function () {
    if (xhr.status === 200) {
    let coordinates = JSON.parse(xhr.responseText);

        for (let i = 0; i < coordinates.length; i++) {
            let latitude = coordinates[i].latitude;
            let longitude = coordinates[i].longitude;
            let name = coordinates[i].name;
            let location = L.latLng(latitude, longitude);
            let marker = L.marker(location).addTo(map);
            marker.bindPopup(name);
        }
    }  
};
xhr.send();