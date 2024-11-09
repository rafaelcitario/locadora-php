<?php

require 'bootstrap.php';

$seed = "
INSERT INTO movies (movie, category, price, amount) VALUES
('The Shawshank Redemption', 'Drama', 19.99, 50),
('The Dark Knight', 'Action', 24.99, 30),
('Inception', 'Sci-Fi', 22.50, 40),
('Pulp Fiction', 'Crime', 15.00, 60),
('Forrest Gump', 'Drama', 18.00, 55);


INSERT INTO authors (name) VALUES
('Frank Darabont'), -- Diretor de 'The Shawshank Redemption'
('Christopher Nolan'), -- Diretor de 'The Dark Knight' e 'Inception'
('Quentin Tarantino'), -- Diretor de 'Pulp Fiction'
('Robert Zemeckis'); -- Diretor de 'Forrest Gump'


INSERT INTO movies_authors (id_movie, id_author) VALUES
(1, 1), -- 'The Shawshank Redemption' - Frank Darabont
(2, 2), -- 'The Dark Knight' - Christopher Nolan
(3, 2), -- 'Inception' - Christopher Nolan
(4, 3), -- 'Pulp Fiction' - Quentin Tarantino
(5, 4); -- 'Forrest Gump' - Robert Zemeckis
";

try {
  $insertSeeds = $databaseConnection->exec($seed);
  echo "Success to seeding tables\n";
} catch (\PDOException $e) {
  exit("ERROR: " . $e->getMessage());
}
