<?php
// Kod do wstawienia po tagu <head>
$insertHead = <<<EOD
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-4EFBDJN68Z"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-4EFBDJN68Z');
</script>

<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="8f672f76-6936-4fe8-90b6-7de5b3cd276d" type="text/javascript" async></script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TN8LRGW7');</script>
<!-- End Google Tag Manager -->
EOD;

// Kod do wstawienia po tagu <body>
$insertBody = <<<EOD
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TN8LRGW7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
EOD;

// Pobierz wszystkie pliki HTML w bieżącym katalogu
$htmlFiles = glob("*.html");

foreach ($htmlFiles as $file) {
    $content = file_get_contents($file);
    if ($content === false) {
        echo "Nie udało się odczytać pliku: $file\n";
        continue;
    }

    $modified = false;

    // Wstawienie kodu do <head>
    $patternHead = '/(<head\b[^>]*>)/i';
    if (preg_match($patternHead, $content, $matches)) {
        $replacement = $matches[1] . "\n" . $insertHead;
        $content = preg_replace($patternHead, $replacement, $content, 1);
        $modified = true;
    } else {
        echo "Nie znaleziono tagu <head> w pliku: $file\n";
    }

    // Wstawienie kodu do <body>
    $patternBody = '/(<body\b[^>]*>)/i';
    if (preg_match($patternBody, $content, $matches)) {
        $replacement = $matches[1] . "\n" . $insertBody;
        $content = preg_replace($patternBody, $replacement, $content, 1);
        $modified = true;
    } else {
        echo "Nie znaleziono tagu <body> w pliku: $file\n";
    }

    // Zapisanie zmian do pliku
    if ($modified) {
        if (file_put_contents($file, $content) !== false) {
            echo "Zaktualizowano plik: $file\n";
        } else {
            echo "Nie udało się zapisać zmian w pliku: $file\n";
        }
    }
}
?>
