document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('addressInput');
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    if (input) {
        const options = {
            componentRestrictions: { country: 'NG' },
            fields: ['formatted_address', 'geometry']
        };

        const autocomplete = new google.maps.places.Autocomplete(input, options);

        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            
            if (place.geometry) {
                latInput.value = place.geometry.location.lat();
                lngInput.value = place.geometry.location.lng();
            }
        });
    }
}); 