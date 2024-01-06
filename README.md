# Blog
Projet 5 - Créez votre premier blog en PHP

<h3>INSTALLATION DU PROJET</h3>

1 - Git clone ou téléchargement du repository.<br>
2 - Lancer un compser install.<br>
3 - A la racine du projet créer un .htaccess.<br>

Contenu du HTACCESS <br>

RewriteEngine on<br>
RewriteCond %{REQUEST_FILENAME} !-f<br>
RewriteCond %{REQUEST_FILENAME} !-d<br>
RewriteRule ^(.*)$ /index.php?path=$1 [NC,L,QSA]<br>

4 - Créer une base de données vierge.<br>
5 - Importer le fichier script.sql dans la base de données.<br>
6 - Dans App/Model.php, changer les identifiants de la base de données.<br>

<h3>CREER UN UTILISATEUR</h3>
ATTENTION les mails utilisent la fonction mail() de php, il est possible que selon le serveur cela ne fonctionne pas.<br>
1 - Utiliser le formulaire d'inscription.<br>
2 - Si les mails ne fonctionne pas, aller dans la table user, passer le token en NULL et le FK_type_user_id sur 1 pour utilisateur ou 2 pour admin.<br>
3 - Connectez-vous.<br>

<h3>DESCRIPTION DU PROJET</h3>
Le projet est donc de développer votre blog professionnel. Ce site web se décompose en deux grands groupes de pages :

les pages utiles à tous les visiteurs
les pages permettant d’administrer votre blog.
Voici la liste des pages qui devront être accessibles depuis votre site web :

la page d'accueil
la page listant l’ensemble des blogs posts
la page affichant un blog post
la page permettant d’ajouter un blog post
la page permettant de modifier un blog post
les pages permettant de modifier/supprimer un blog post
les pages de connexion/enregistrement des utilisateurs
Vous développerez une partie administration qui devra être accessible uniquement aux utilisateurs inscrits et validés.

Les pages d’administration seront donc accessible sur conditions et vous veillerez à la sécurité de la partie administration.

Commençons par les pages utiles à tous les internautes.

Sur la page d’accueil il faudra présenter les informations suivantes :

Votre nom et prénom<br>
Une photo et/ou un logo<br>
Une phrase d’accroche qui vous ressemble ( exemple : “Martin Durand, le développeur qu’il vous faut !”)<br>
Un menu permettant de naviguer parmi l’ensemble des pages de votre site web<br>
Un formulaire de contact (à la soumission de ce formulaire, un email avec toutes ces informations vous serons envoyé) avec les champs suivants :<br>
nom/prénom<br>
email de contact<br>
message<br>
un lien vers votre CV au format pdf<br>
et l’ensemble des liens vers les réseaux sociaux où l’on peut vous suivre (Github, LinkedIn, Twitter…).<br>
Sur la page listant tous les blogs posts (du plus récent au plus ancien), il faut afficher les informations suivantes pour chaque blog post :<br>

le titre<br>
la date de dernière modification<br>
le châpo<br>
et un lien vers le blog post<br>
Sur la page présentant le détail d’un blog post, il faut afficher les informations suivantes :<br>

le titre<br>
le chapô<br>
le contenu<br>
l’auteur<br>
la date de dernière mise à jour<br>
le formulaire permettant d’ajouter un commentaire (soumis pour validation)<br>
les listes des commentaires validés et publiés<br>
Sur la page permettant de modifier un blog post, l’utilisateur a la possibilité de modifier les champs titre, chapô, auteur et contenu.<br>

Dans le footer menu, il doit figurer un lien pour accéder à l’administration du blog.<br>
