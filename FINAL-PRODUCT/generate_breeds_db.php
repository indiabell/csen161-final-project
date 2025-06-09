<?php
// Load breed data from JSON
$json = file_get_contents('breeds.json');
$breeds = json_decode($json, true);

if (!$breeds) {
  exit("Error: Could not parse breeds.json");
}

// Connect to users.db
$pdo = new PDO('sqlite:users.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Clear old breeds data
$pdo->exec("DELETE FROM breeds");

// Insert new breed data
$stmt = $pdo->prepare("
  INSERT INTO breeds (id, name, traits, image, about, shelters)
  VALUES (:id, :name, :traits, :image, :about, :shelters)
");

foreach ($breeds as $breed) {
  $stmt->execute([
    ':id' => $breed['id'],
    ':name' => $breed['name'],
    ':traits' => json_encode($breed['traits']),
    ':image' => $breed['image'],
    ':about' => $breed['about'],
    ':shelters' => json_encode($breed['shelters'])
  ]);
}

echo "Breeds successfully imported into users.db";
?>