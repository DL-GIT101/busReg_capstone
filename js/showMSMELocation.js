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

        coordinates.forEach(business => {
            let latitude = business.latitude;
            let longitude = business.longitude;
            let name = business.name;
            let activity = business.activity;
            let contact = business.contact;
            let location = L.latLng(latitude, longitude);
            let marker = L.marker(location).addTo(map);
            marker.bindPopup(name + '</br>' + activity + '</br>' + contact);
        });
    }  
};
xhr.send();