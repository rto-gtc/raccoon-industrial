<?php
// Ustawienia URL kanałów RSS w zależności od wybranego portalu
$rss_url = "https://www.energetykacieplna.pl/rss.xml";
if (isset($_GET['portal']) && $_GET['portal'] == 'srodowisko') {
    $rss_url = "https://www.srodowisko.pl/rss.xml";
}

// Ładowanie kanału RSS
$rss = @simplexml_load_file($rss_url);

// Jeśli nie udało się załadować RSS
if (!$rss) {
    echo "<h2>Nie udało się załadować kanału RSS.</h2>";
    exit;
}

// Jeśli formularz wyszukiwania został wysłany
$searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Raccoon Services Odnawialne źródła energii</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="montaż instalacji, elektryka, hydraulika, ogrzewanie, wycena, Raccoon Services">
    <meta name="description" content="Instalacje na najwyższym poziomie. Zapraszamy użytkowników domów pasywnych i energooszczędnych po instalacje dobrane właśnie do takich budynków. ">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://biznes-dom.pl/audyt-raccoon/Raccoon-Rs/index.html">
    <meta property="og:title" content="Raccoon Services – Profesjonalny Montaż Instalacji">
    <meta property="og:description" content="Skorzystaj z naszego narzędzia do wyceny i zamów montaż instalacji w Twoim domu!">
    <meta property="og:image" content="https://static.wixstatic.com/media/e0bdea_837a5324844d4a2dbcb3461cfeafb497~mv2.webp">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #fafafa;
            color: #333;
        }
        header {
            background-color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        header img {
            width: 380px;
            margin-bottom: 10px;
        }
        .rss-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .rss-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .rss-item:hover {
            transform: translateY(-5px);
        }
        .rss-item h3 {
            font-size: 1.2rem;
            color: #333;
        }
        .rss-item p {
            font-size: 1rem;
            color: #555;
        }
        .rss-item a {
            color: #007BFF;
            text-decoration: none;
        }
        .rss-item a:hover {
            text-decoration: underline;
        }
/* Transparentny pasek przewijania */
::-webkit-scrollbar {
    width: 8px;
    background: transparent; /* Tło paska przewijania */
}

::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.3); /* Kolor uchwytu przewijania */
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.5); /* Widoczniejszy przy najechaniu */
}

::-webkit-scrollbar-track {
    background: transparent; /* Przezroczyste tło toru */
}

/* Dla przeglądarek obsługujących scrollbar-gutter */
body {
    scrollbar-gutter: stable;
}

    </style>
</head>
<body>

    <!-- Nagłówek z logo -->
    <header>
        <img src="https://static.wixstatic.com/media/e0bdea_90b77c02101b42dab3e95049ecca876e~mv2.png" alt="Energetyka Cieplna Logo">
    </header>

    <!-- Kontener z dropdown i wyszukiwarką -->
    <div class="container my-4">
        <div class="row justify-content-center">
            <!-- Dropdown do wyboru portalu -->
            <div class="col-12 col-md-4 mb-3">
                <form action="" method="get">
                    <select name="portal" onchange="this.form.submit()" class="form-select">
                        <option value="energetyka" <?php echo (!isset($_GET['portal']) || $_GET['portal'] == 'energetyka') ? 'selected' : ''; ?>>Energetyka Cieplna</option>
                        <option value="srodowisko" <?php echo (isset($_GET['portal']) && $_GET['portal'] == 'srodowisko') ? 'selected' : ''; ?>>Środowisko</option>
                    </select>
                </form>
            </div>

            <!-- Wyszukiwarka -->
            <div class="col-12 col-md-8 mb-3">
                <form class="d-flex" action="" method="get">
                    <input type="text" name="search" placeholder="Szukaj artykułów..." value="<?php echo $searchTerm; ?>" class="form-control me-2">
                    <button type="submit" class="btn btn-primary">Szukaj</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Wyświetlanie artykułów z RSS -->
    <div class="container">
        <div class="rss-grid">
            <?php
            // Filtracja artykułów na podstawie wyszukiwanego terminu
            foreach ($rss->channel->item as $item) {
                $title = (string)$item->title;
                $link = (string)$item->link;
                $description = (string)$item->description;
                $pubDate = (string)$item->pubDate;

                // Jeśli wyszukiwany termin jest pusty lub znajduje się w tytule
                if (empty($searchTerm) || stripos($title, $searchTerm) !== false) {
                    echo '<div class="rss-item">';
                    echo '<h3><a href="' . htmlspecialchars($link) . '" target="_blank">' . htmlspecialchars($title) . '</a></h3>';
                    echo '<p><strong>Data publikacji:</strong> ' . date('d-m-Y H:i', strtotime($pubDate)) . '</p>';
                    echo '<p>' . htmlspecialchars($description) . '</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
>