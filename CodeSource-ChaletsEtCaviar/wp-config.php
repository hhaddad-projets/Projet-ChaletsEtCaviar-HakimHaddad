<?php

/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'hhaddad_bddchaletsetcaviar');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'hhaddad_hhaddad');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'Sramfesses1');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ')#!tKl!GRI^6LP4N8UY~G,ltmgr(jS}9]:H2)@]*Z^h{PrPi8wz&|wtKRJqZ/Vy6');
define('SECURE_AUTH_KEY',  'c%dehi<4Y%(8T5UHBet]YGz+}sb[w<GzL[jk)ibrzpn. 3=Qz^sV$F?yccs+&U?>');
define('LOGGED_IN_KEY',    'J|v[x7!OpsV6Pg g%![yF{o(0, I,Ex:cDx5uo?u&Vk]t&tW=t-<t zCdxYc{-bD');
define('NONCE_KEY',        'IL:T>xt<}21>mjnS{xz8}99c; JnB{q~-GPUQb!;rKIjc ;fD45`1-k8Y*dkuoLP');
define('AUTH_SALT',        'Wwn^xG^6GVDP,YRV=UkdmXa_+>#OYtE GoF-#L/}y!T6C#@ehn|i,U&`FI65J4Ut');
define('SECURE_AUTH_SALT', '+-gP=UkEqOP;g_[Nh5C|^S-={9IZu`f9s]z1ha7F&+(73:v%F.YVD8d0k..h92.J');
define('LOGGED_IN_SALT',   '~^tAn7>xqZ,Wj=<MdltD$Q-AP^v`e=`vQ4~n<MH= qK!++QWaJvj6%a`Td?#KX4A');
define('NONCE_SALT',       's^{xi^CGaQ1v!}0]H4S M5+46sjm3h;dt0zrD3?0a{(OXpxxEjzES~oC:EV~wROx');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');