Ce dossier contient les diff�rents scripts utilis�s pour le moteur de recherche. 

 - app => contient le dossier de chargement d'Elasticsearch, appel� dans le header
 - composants => contient le script du header et du footer
 - css => style bootstrap et style perso de la page (main.css)
 - image => contient la favicon 
 - js => contient les bibliot�ques js de bootstrap, jquery, scrpits perso (functions.js)
 - rab => scripts non utilis�s conserv�s pour l'instant
 - vendor => dossier g�n�r� par composer
 - pageStats.php => future page de statistiques kibana 
 - result.php => page d'affichage d'un r�sultat de recherche Elasticsearch
 - searchSuggest.php => scrpit utilis� pour le fonctionnement de la recherche pr�dictive associ�e au 
   champ nom entreprise
 - .htaccess n'est pas utilis� mais peut l'�tre

/!\ A bien noter /!\

Il est possible qu'en copiant-collant le dossier Elasticsearch le moteur ne fonctionne pas pour 
plusieurs raisons :

 - port Elasticsearch : par d�faut 127.0.0.1:9200
 - utilisation de composer pour cr�er le vendor et donc possible erreur de path (JAVA_HOME ou autre)
 - version d'Elasticsearch utilis�e 6.2.4, les queries ne sont pas toutes valides avec les versions 
   ant�rieurs � cause d'un changement de syntaxe 