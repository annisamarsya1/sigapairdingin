<?php
$lang = $_GET['lang'] ?? 'id';
$texts = [
    'id' => [
        'title' => 'Peta Risiko - Sigap Air Dingin',
        'subtitle' => 'Visualisasi titik-titik laporan di Kelurahan Air Dingin.',
        'footer' => '&copy; ' . date('Y') . ' Sigap Air Dingin. Dibangun untuk masyarakat Air Dingin.'
    ],
    'en' => [
        'title' => 'Risk Map - Sigap Air Dingin',
        'subtitle' => 'Visualization of report points in Kelurahan Air Dingin.',
        'footer' => '&copy; ' . date('Y') . ' Sigap Air Dingin. Built for the people of Air Dingin.'
    ]
];
?>
<!DOCTYPE html>
<html lang="<?= $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $texts[$lang]['title']; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-sA+z3L4lJY4t0cPHk4lZ8J2ZOV5pF31o7ztv6+gR0A4=" crossorigin=""/>

    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fafb;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            padding: 0.7rem 1rem;
        }

        .brand-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.2rem;
        }

        #map {
            flex: 1;
            width: 100%;
        }

        .footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 0;
            color: #64748b;
            font-size: 0.85rem;
            text-align: center;
        }

        /* Marker popup style */
        .leaflet-popup-content {
            font-size: 0.9rem;
            line-height: 1.4;
        }
    </style>
</head>
<body>

<?php include 'partials/navbar.php'; ?>


<!-- Map -->
<div id="map"></div>

<!-- Footer -->
<footer class="footer">
    <?= $texts[$lang]['footer']; ?>
</footer>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-o9N1jA6YciwXPDqp44GJrA/3x0kyH6fP0t3Z7rX0+QA=" crossorigin=""></script>
<script>
    // Koordinat pusat Kelurahan Air Dingin
    const pusat = [-0.6845, 100.7890]; // latitude, longitude

    // Inisialisasi peta
    const map = L.map('map').setView(pusat, 15);

    // Tile layer OSM
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Contoh data laporan risiko
    const laporanRisiko = [
        {lat: -0.6840, lng: 100.7895, title: 'Genangan Air', desc: 'Tingkat risiko sedang'},
        {lat: -0.6850, lng: 100.7880, title: 'Sampah Menumpuk', desc: 'Tingkat risiko rendah'},
        {lat: -0.6835, lng: 100.7900, title: 'Saluran Tersumbat', desc: 'Tingkat risiko tinggi'}
    ];

    // Tambahkan marker
    laporanRisiko.forEach(l => {
        L.marker([l.lat, l.lng]).addTo(map)
            .bindPopup(`<b>${l.title}</b><br>${l.desc}`);
    });
</script>

</body>
</html>
