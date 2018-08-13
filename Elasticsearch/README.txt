Ce dossier contient les différents scripts utilisés pour le moteur de recherche. 

 - app => contient le dossier de chargement d'Elasticsearch, appelé dans le header
 - composants => contient le script du header et du footer
 - css => style bootstrap et style perso de la page (main.css)
 - image => contient la favicon 
 - js => contient les bibliotèques js de bootstrap, jquery, scrpits perso (functions.js)
 - rab => scripts non utilisés conservés pour l'instant
 - vendor => dossier généré par composer
 - pageStats.php => future page de statistiques kibana 
 - result.php => page d'affichage d'un résultat de recherche Elasticsearch
 - searchSuggest.php => scrpit utilisé pour le fonctionnement de la recherche prédictive associée au 
   champ nom entreprise
 - .htaccess n'est pas utilisé mais peut l'être

/!\ A bien noter /!\

Il est possible qu'en copiant-collant le dossier Elasticsearch le moteur ne fonctionne pas pour 
plusieurs raisons :

 - port Elasticsearch : par défaut 127.0.0.1:9200
 - utilisation de composer pour créer le vendor et donc possible erreur de path (JAVA_HOME ou autre)
 - version d'Elasticsearch utilisée 6.2.4, les queries ne sont pas toutes valides avec les versions 
   antérieurs à cause d'un changement de syntaxe 