<?php
// TODO 1: Обработка GET-запроса
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = urlencode($_GET['search']);

    $apiKey = 'AIzaSyDwVwJpAMur3QcEUqjY3_EOdwdsQGfl9aY';
    $cx = 'b4812edb56b424b19';

    $url = "https://www.googleapis.com/customsearch/v1?key={$apiKey}&cx={$cx}&q={$search}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    $items = $data['items'] ?? [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
</head>
<body>
<h2>My Browser</h2>
<form method="GET" action="">
    <label for="search">Search:</label>
    <input type="text" id="search" name="search" value=""><br><br>
    <input type="submit" value="Submit">
</form>

<?php
// TODO 2: Отображение результатов поиска с использованием foreach()
if (!empty($items)) {
    echo "<h3>Search Results:</h3>";
    echo "<ul>";
    foreach ($items as $item) {
        echo "<li>";
        echo "<a href='{$item['link']}' target='_blank'>{$item['title']}</a><br>";
        echo "<p>{$item['snippet']}</p>";
        echo "</li>";
    }
    echo "</ul>";
} elseif (isset($search)) {
    echo "<p>No results found.</p>";
}
?>
</body>
</html>
