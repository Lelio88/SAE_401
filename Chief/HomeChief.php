<?php require_once '../www/headerChief.inc.php'; ?>
<title>Home - Chief</title>
<body>
    <h1>Welcome to the Chief section.</h1>
    <div id="map"></div>
    
    <script>
        var map = L.map('map').setView([46.603354, 1.888334], 6); // Centrage sur la France
        var customIcon = L.icon({
            iconUrl: '../images/storept.png',  // L'URL de votre image personnalisée
            iconSize: [32, 32],                      // Taille de l'icône (largeur, hauteur)
            iconAnchor: [16, 32],                    // Point d'ancrage de l'icône (par rapport à l'icône)
            popupAnchor: [0, -32]                    // Position du popup par rapport à l'icône
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Fonction pour obtenir les coordonnées d'une adresse avec la ville
        function getCoordinates(street, state, callback) {
            let query = encodeURIComponent(`${street} ${state}`);
            let url = `https://nominatim.openstreetmap.org/search?format=json&q=${query}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let lat = parseFloat(data[0].lat);
                        let lon = parseFloat(data[0].lon);
                        callback(lat, lon);
                    } else {
                        console.error(`Adresse introuvable: ${street} ${state}`);
                    }
                })
                .catch(error => console.error('Erreur de géocodage:', error));
        }

        // Géolocalisation de l'utilisateur via son IP
        fetch('https://api.bigdatacloud.net/data/client-ip')
            .then(response => response.json())
            .then(data => {
                let ip = data.ipString;
                fetch(`https://api.apibundle.io/ip-lookup?apikey=613e7b4453d541f182c258d6c2676e5c&ip=${ip}`)
                    .then(response => response.json())
                    .then(location => {
                        let lat = location.latitude;
                        let lon = location.longitude;
                        L.marker([lat, lon]).addTo(map)
                            .bindPopup("You are here").openPopup();
                        map.setView([lat, lon], 12);
                    })
                    .catch(error => console.error('Erreur lors de la récupération des coordonnées:', error));
            })
            .catch(error => console.error("Erreur lors de la récupération de l'adresse IP:", error),
                map.setView([38.48, -100.46], 4)
                    
            );

        // Récupération et affichage des magasins depuis l'API
        fetch('https://dev-raulin231.users.info.unicaen.fr/A2/S4/SAE401/api.php?action=stores')
            .then(response => response.json())
            .then(magasins => {
                magasins.forEach(magasin => {
                    getCoordinates(magasin.street, magasin.state, (lat, lon) => {
                        L.marker([lat, lon], { icon: customIcon }).addTo(map)
                            .bindPopup(`<b>${magasin.store_name}</b><br>${magasin.state}<br>${magasin.street}`);
                    });
                });
            })
            .catch(error => console.error('Erreur lors de la récupération des magasins:', error));
    </script>
    </div>
    <?php require_once '../www/footerChief.inc.php'; ?>
</body>

</html>