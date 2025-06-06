<?php
  // Load JSON data
  $json = file_get_contents('breeds.json');
  $breeds = json_decode($json, true);

  if (!$breeds) {
    exit("Error: Could not parse breeds.json");
  }

  // Connect to SQLite
  $pdo = new PDO('sqlite:breeds.db');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Create breeds table
  $pdo->exec("DROP TABLE IF EXISTS breeds");
  $pdo->exec("
    CREATE TABLE breeds (
      id INTEGER PRIMARY KEY,
      name TEXT,
      traits TEXT,
      image TEXT,
      shelters TEXT
    )
  ");

  // Insert each breed
  $stmt = $pdo->prepare("
    INSERT INTO breeds (id, name, traits, image, shelters)
    VALUES (:id, :name, :traits, :image, :shelters)
  ");

  foreach ($breeds as $breed) {
    $stmt->execute([
      ':id' => $breed['id'],
      ':name' => $breed['name'],
      ':traits' => json_encode($breed['traits']),
      ':image' => $breed['image'],
      ':shelters' => json_encode($breed['shelters'])
    ]);
  }

  echo "breeds.db created successfully.";
?>
