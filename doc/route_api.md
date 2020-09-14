# Documentation de l'API

| Endpoint | Méthode HTTP | Donnée(s) à transmettre | Description |
|--|--|--|--|
| `/user/[id]` | GET | Aucune | Récupération des données d'un utilisateur |
| `/user/[id]/product/[barcode]` | POST |  | Création d'un produit en bdd commune: s'il existe déjà = true => ajoute à la bdd perso / s'il n'existe pas = false, appel à une autre route api |
| `/user/[id]/product/manual` | POST | - | Crée un produit dans la table perso |
| `/user/[id]` | PUT | name, status | Mise à jour complète d'une catégorie |

