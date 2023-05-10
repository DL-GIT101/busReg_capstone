let latitudeInput = document.getElementById('latitude');
let longitudeInput = document.getElementById('longitude');
let marker = null;

if(latitudeInput.value && longitudeInput.value) {
    marker = L.latLng(latitudeInput.value, longitudeInput.value);
}
const onMapClick = (e) => {
    if(tarlacCity.getBounds().contains(e.latlng)){
        if (marker) {
            marker.setLatLng(e.latlng);
          } else {
            marker = L.marker(e.latlng).addTo(map);
          }
    }
    let latitude = e.latlng.lat;
    let longitude = e.latlng.lng;
    latitudeInput.value = latitude;
    longitudeInput.value = longitude;
}
        
map.on('click', onMapClick); 