# Se déplacer à la racine du projet
cd ./

# Ajouter la date dans les fichiers de log
date >> logs/auto/deploy.log
date >> logs/auto/error.log

# Exécuter les commandes git et rediriger les sorties vers les fichiers de log
git pull origin main >> logs/auto/deploy.log 2>> logs/auto/error.log

# Retour au répertoire initial si nécessaire
cd -