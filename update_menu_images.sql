-- Mise à jour des images des plats et boissons avec les images téléchargées
-- Ce fichier met à jour la colonne image_url de la table menu_items

-- Plats
UPDATE menu_items SET image_url = 'images/Etudiant/Poulet DG.jpg' WHERE name LIKE '%Poulet DG%';
UPDATE menu_items SET image_url = 'images/Etudiant/Riz Cantonais.jpeg' WHERE name LIKE '%Riz Cantonais%';
UPDATE menu_items SET image_url = 'images/Etudiant/Salade Composé.jpg' WHERE name LIKE '%Salade%';

-- Boissons
UPDATE menu_items SET image_url = 'images/Etudiant/Bissap.jpg' WHERE name LIKE '%Bissap%';
UPDATE menu_items SET image_url = 'images/Etudiant/Eau minérale.jpg' WHERE name LIKE '%Eau%';
UPDATE menu_items SET image_url = 'images/Etudiant/coca cola.jpg' WHERE name LIKE '%Coca%';
UPDATE menu_items SET image_url = 'images/Etudiant/hero.jpeg' WHERE name LIKE '%Hero%';
UPDATE menu_items SET image_url = 'images/Etudiant/jus de fruits.jpg' WHERE name LIKE '%Jus%';
