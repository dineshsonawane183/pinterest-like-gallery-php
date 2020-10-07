<?php
$feedDataArr = [];
$layoutString = "";
$layoutScript = "";

function make_curl_call() {
    $url = "https://cdn.pinkvilla.com/feed/fashion-section.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $result = json_decode($response);
    curl_close($ch); // Close the connection
    return $result;
}

function sortByOrder($a, $b) {
    return $a->viewCount - $b->viewCount;
}

function createLayoutScript() {
    global $layoutScript;
    $layoutScript = "
  <script>
    var colc = new Colcade('.grid', {
        columns: '.grid-col',
        items: '.grid-item'
    });
  </script>";
}

function createLayout($feedDataArr) {
    global $layoutString;
    foreach ($feedDataArr as $key) {
        $path = "//pinkvilla.com" . $key->path;
        $innerHtml1 = "<div class='grid-item'><div onclick=\"window.open('";
        $innerHtml2 = '\')">';
        $innerHtml3 = "<img src='" . $key->imageUrl . "'>";
        $innerHtml4 = "<div class='content'><h2>" . $key->title . "</h2></div></div></div>";
        $innerHtml = $innerHtml1 . $path . $innerHtml2 . $innerHtml3 . $innerHtml4;
        $layoutString = $layoutString . $innerHtml;
    }
}

function init() {
    $feedData = make_curl_call();
    //sort feed according to viewCount
    usort($feedData, 'sortByOrder');

    global $feedDataArr;
    $feedDataArr = $feedData;
    createLayout($feedDataArr);
    createLayoutScript();
}
//init call 
init();
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="./css/style.css">
        <script src="https://unpkg.com/colcade@0/colcade.js"></script>
    </head>

    <body>

        <div class="container">
            <div class="grid">
                <div class="grid-col grid-col--1">
                </div>
                <div class="grid-col grid-col--2">
                </div>
                <div class="grid-col grid-col--3">
                </div>
                <div class="grid-col grid-col--4">
                </div>
                <div class="grid-col grid-col--5">
                </div>

                <?php
                echo $layoutString;
                echo $layoutScript;
                ?>
            </div>
        </div>
    </body>
</html>
