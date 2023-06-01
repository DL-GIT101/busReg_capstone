let xhr = new XMLHttpRequest();
xhr.open('POST', 'php/get_coordinates.php', true);
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

