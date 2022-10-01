<?php
/**
 * Bstone Widget - Social Icons
 *
 * @package Bstone Light
 * 
 * @since   Bstone Light 1.0.2
 */

if ( ! class_exists( 'bstone_light_widget_social_icons' ) ) {

    /**
     * Social Icons Widget
     */
    class bstone_light_widget_social_icons extends WP_Widget {

        /**
         * @var array $icons
         * Collection of fontawesome icons
         */
        protected $icons_data = array(
            array(
                'name' => '500px',
                'icon' => 'fab fa-500px'
            ),
            array(
                'name' => 'accessible',
                'icon' => 'fab fa-accessible-icon'
            ),
            array(
                'name' => 'accusoft',
                'icon' => 'fab fa-accusoft'
            ),
            array(
                'name' => 'adn',
                'icon' => 'fab fa-adn'
            ),
            array(
                'name' => 'adversal',
                'icon' => 'fab fa-adversal'
            ),
            array(
                'name' => 'affiliatetheme',
                'icon' => 'fab fa-affiliatetheme'
            ),
            array(
                'name' => 'algolia',
                'icon' => 'fab fa-algolia'
            ),
            array(
                'name' => 'amazon',
                'icon' => 'fab fa-amazon'
            ),
            array(
                'name' => 'amazon-pay',
                'icon' => 'fab fa-amazon-pay'
            ),
            array(
                'name' => 'amilia',
                'icon' => 'fab fa-amilia'
            ),
            array(
                'name' => 'android',
                'icon' => 'fab fa-android'
            ),
            array(
                'name' => 'angellist',
                'icon' => 'fab fa-angellist'
            ),
            array(
                'name' => 'angrycreative',
                'icon' => 'fab fa-angrycreative'
            ),
            array(
                'name' => 'angular',
                'icon' => 'fab fa-angular'
            ),
            array(
                'name' => 'app-store',
                'icon' => 'fab fa-app-store'
            ),
            array(
                'name' => 'app-store-ios',
                'icon' => 'fab fa-app-store-ios'
            ),
            array(
                'name' => 'apper',
                'icon' => 'fab fa-apper'
            ),
            array(
                'name' => 'apple',
                'icon' => 'fab fa-apple'
            ),
            array(
                'name' => 'apple-pay',
                'icon' => 'fab fa-apple-pay'
            ),
            array(
                'name' => 'asymmetrik',
                'icon' => 'fab fa-asymmetrik'
            ),
            array(
                'name' => 'audible',
                'icon' => 'fab fa-audible'
            ),
            array(
                'name' => 'autoprefixer',
                'icon' => 'fab fa-autoprefixer'
            ),
            array(
                'name' => 'avianex',
                'icon' => 'fab fa-avianex'
            ),
            array(
                'name' => 'aviato',
                'icon' => 'fab fa-aviato'
            ),
            array(
                'name' => 'aws',
                'icon' => 'fab fa-aws'
            ),
            array(
                'name' => 'bandcamp',
                'icon' => 'fab fa-bandcamp'
            ),
            array(
                'name' => 'behance',
                'icon' => 'fab fa-behance'
            ),
            array(
                'name' => 'behance-square',
                'icon' => 'fab fa-behance-square'
            ),
            array(
                'name' => 'bimobject',
                'icon' => 'fab fa-bimobject'
            ),
            array(
                'name' => 'bitbucket',
                'icon' => 'fab fa-bitbucket'
            ),
            array(
                'name' => 'bitcoin',
                'icon' => 'fab fa-bitcoin'
            ),
            array(
                'name' => 'bity',
                'icon' => 'fab fa-bity'
            ),
            array(
                'name' => 'black-tie',
                'icon' => 'fab fa-black-tie'
            ),
            array(
                'name' => 'blackberry',
                'icon' => 'fab fa-blackberry'
            ),
            array(
                'name' => 'blogger',
                'icon' => 'fab fa-blogger'
            ),
            array(
                'name' => 'blogger-b',
                'icon' => 'fab fa-blogger-b'
            ),
            array(
                'name' => 'bluetooth',
                'icon' => 'fab fa-bluetooth'
            ),
            array(
                'name' => 'bluetooth-b',
                'icon' => 'fab fa-bluetooth-b'
            ),
            array(
                'name' => 'btc',
                'icon' => 'fab fa-btc'
            ),
            array(
                'name' => 'buromobelexperte',
                'icon' => 'fab fa-buromobelexperte'
            ),
            array(
                'name' => 'buysellads',
                'icon' => 'fab fa-buysellads'
            ),
            array(
                'name' => 'cc-amazon-pay',
                'icon' => 'fab fa-cc-amazon-pay'
            ),
            array(
                'name' => 'cc-amex',
                'icon' => 'fab fa-cc-amex'
            ),
            array(
                'name' => 'cc-apple-pay',
                'icon' => 'fab fa-cc-apple-pay'
            ),
            array(
                'name' => 'cc-diners-club',
                'icon' => 'fab fa-cc-diners-club'
            ),
            array(
                'name' => 'cc-discover',
                'icon' => 'fab fa-cc-discover'
            ),
            array(
                'name' => 'cc-jcb',
                'icon' => 'fab fa-cc-jcb'
            ),
            array(
                'name' => 'cc-mastercard',
                'icon' => 'fab fa-cc-mastercard'
            ),
            array(
                'name' => 'cc-paypal',
                'icon' => 'fab fa-cc-paypal'
            ),
            array(
                'name' => 'cc-stripe',
                'icon' => 'fab fa-cc-stripe'
            ),
            array(
                'name' => 'cc-visa',
                'icon' => 'fab fa-cc-visa'
            ),
            array(
                'name' => 'centercode',
                'icon' => 'fab fa-centercode'
            ),
            array(
                'name' => 'chrome',
                'icon' => 'fab fa-chrome'
            ),
            array(
                'name' => 'cloudscale',
                'icon' => 'fab fa-cloudscale'
            ),
            array(
                'name' => 'cloudsmith',
                'icon' => 'fab fa-cloudsmith'
            ),
            array(
                'name' => 'cloudversify',
                'icon' => 'fab fa-cloudversify'
            ),
            array(
                'name' => 'codepen',
                'icon' => 'fab fa-codepen'
            ),
            array(
                'name' => 'codiepie',
                'icon' => 'fab fa-codiepie'
            ),
            array(
                'name' => 'connectdevelop',
                'icon' => 'fab fa-connectdevelop'
            ),
            array(
                'name' => 'contao',
                'icon' => 'fab fa-contao'
            ),
            array(
                'name' => 'cpanel',
                'icon' => 'fab fa-cpanel'
            ),
            array(
                'name' => 'creative-commons',
                'icon' => 'fab fa-creative-commons'
            ),
            array(
                'name' => 'creative-commons-by',
                'icon' => 'fab fa-creative-commons-by'
            ),
            array(
                'name' => 'creative-commons-nc',
                'icon' => 'fab fa-creative-commons-nc'
            ),
            array(
                'name' => 'creative-commons-nc-eu',
                'icon' => 'fab fa-creative-commons-nc-eu'
            ),
            array(
                'name' => 'creative-commons-nc-jp',
                'icon' => 'fab fa-creative-commons-nc-jp'
            ),
            array(
                'name' => 'creative-commons-nd',
                'icon' => 'fab fa-creative-commons-nd'
            ),
            array(
                'name' => 'creative-commons-pd',
                'icon' => 'fab fa-creative-commons-pd'
            ),
            array(
                'name' => 'creative-commons-pd-alt',
                'icon' => 'fab fa-creative-commons-pd-alt'
            ),
            array(
                'name' => 'creative-commons-remix',
                'icon' => 'fab fa-creative-commons-remix'
            ),
            array(
                'name' => 'creative-commons-sa',
                'icon' => 'fab fa-creative-commons-sa'
            ),
            array(
                'name' => 'creative-commons-sampling',
                'icon' => 'fab fa-creative-commons-sampling'
            ),
            array(
                'name' => 'creative-commons-sampling-plus',
                'icon' => 'fab fa-creative-commons-sampling-plus'
            ),
            array(
                'name' => 'creative-commons-share',
                'icon' => 'fab fa-creative-commons-share'
            ),
            array(
                'name' => 'css3',
                'icon' => 'fab fa-css3'
            ),
            array(
                'name' => 'css3-alt',
                'icon' => 'fab fa-css3-alt'
            ),
            array(
                'name' => 'cuttlefish',
                'icon' => 'fab fa-cuttlefish'
            ),
            array(
                'name' => 'd-and-d',
                'icon' => 'fab fa-d-and-d'
            ),
            array(
                'name' => 'dashcube',
                'icon' => 'fab fa-dashcube'
            ),
            array(
                'name' => 'delicious',
                'icon' => 'fab fa-delicious'
            ),
            array(
                'name' => 'deploydog',
                'icon' => 'fab fa-deploydog'
            ),
            array(
                'name' => 'deskpro',
                'icon' => 'fab fa-deskpro'
            ),
            array(
                'name' => 'deviantart',
                'icon' => 'fab fa-deviantart'
            ),
            array(
                'name' => 'digg',
                'icon' => 'fab fa-digg'
            ),
            array(
                'name' => 'digital-ocean',
                'icon' => 'fab fa-digital-ocean'
            ),
            array(
                'name' => 'discord',
                'icon' => 'fab fa-discord'
            ),
            array(
                'name' => 'discourse',
                'icon' => 'fab fa-discourse'
            ),
            array(
                'name' => 'dochub',
                'icon' => 'fab fa-dochub'
            ),
            array(
                'name' => 'docker',
                'icon' => 'fab fa-docker'
            ),
            array(
                'name' => 'draft2digital',
                'icon' => 'fab fa-draft2digital'
            ),
            array(
                'name' => 'dribbble',
                'icon' => 'fab fa-dribbble'
            ),
            array(
                'name' => 'dribbble-square',
                'icon' => 'fab fa-dribbble-square'
            ),
            array(
                'name' => 'dropbox',
                'icon' => 'fab fa-dropbox'
            ),
            array(
                'name' => 'drupal',
                'icon' => 'fab fa-drupal'
            ),
            array(
                'name' => 'dyalog',
                'icon' => 'fab fa-dyalog'
            ),
            array(
                'name' => 'earlybirds',
                'icon' => 'fab fa-earlybirds'
            ),
            array(
                'name' => 'ebay',
                'icon' => 'fab fa-ebay'
            ),
            array(
                'name' => 'edge',
                'icon' => 'fab fa-edge'
            ),
            array(
                'name' => 'elementor',
                'icon' => 'fab fa-elementor'
            ),
            array(
                'name' => 'ello',
                'icon' => 'fab fa-ello'
            ),
            array(
                'name' => 'ember',
                'icon' => 'fab fa-ember'
            ),
            array(
                'name' => 'empire',
                'icon' => 'fab fa-empire'
            ),
            array(
                'name' => 'envira',
                'icon' => 'fab fa-envira'
            ),
            array(
                'name' => 'envelope-open',
                'icon' => 'fas fa-envelope-open'
            ),
            array(
                'name' => 'envelope-open-line',
                'icon' => 'far fa-envelope-open'
            ),
            array(
                'name' => 'envelope',
                'icon' => 'fas fa-envelope'
            ),
            array(
                'name' => 'envelope-line',
                'icon' => 'far fa-envelope'
            ),
            array(
                'name' => 'erlang',
                'icon' => 'fab fa-erlang'
            ),
            array(
                'name' => 'ethereum',
                'icon' => 'fab fa-ethereum'
            ),
            array(
                'name' => 'etsy',
                'icon' => 'fab fa-etsy'
            ),
            array(
                'name' => 'expeditedssl',
                'icon' => 'fab fa-expeditedssl'
            ),
            array(
                'name' => 'facebook',
                'icon' => 'fab fa-facebook'
            ),
            array(
                'name' => 'facebook-f',
                'icon' => 'fab fa-facebook-f'
            ),
            array(
                'name' => 'facebook-messenger',
                'icon' => 'fab fa-facebook-messenger'
            ),
            array(
                'name' => 'facebook-square',
                'icon' => 'fab fa-facebook-square'
            ),
            array(
                'name' => 'firefox',
                'icon' => 'fab fa-firefox'
            ),
            array(
                'name' => 'first-order',
                'icon' => 'fab fa-first-order'
            ),
            array(
                'name' => 'first-order-alt',
                'icon' => 'fab fa-first-order-alt'
            ),
            array(
                'name' => 'firstdraft',
                'icon' => 'fab fa-firstdraft'
            ),
            array(
                'name' => 'flickr',
                'icon' => 'fab fa-flickr'
            ),
            array(
                'name' => 'flipboard',
                'icon' => 'fab fa-flipboard'
            ),
            array(
                'name' => 'fly',
                'icon' => 'fab fa-fly'
            ),
            array(
                'name' => 'font-awesome',
                'icon' => 'fab fa-font-awesome'
            ),
            array(
                'name' => 'font-awesome-alt',
                'icon' => 'fab fa-font-awesome-alt'
            ),
            array(
                'name' => 'font-awesome-flag',
                'icon' => 'fab fa-font-awesome-flag'
            ),
            array(
                'name' => 'fonticons',
                'icon' => 'fab fa-fonticons'
            ),
            array(
                'name' => 'fonticons-fi',
                'icon' => 'fab fa-fonticons-fi'
            ),
            array(
                'name' => 'fort-awesome',
                'icon' => 'fab fa-fort-awesome'
            ),
            array(
                'name' => 'fort-awesome-alt',
                'icon' => 'fab fa-fort-awesome-alt'
            ),
            array(
                'name' => 'forumbee',
                'icon' => 'fab fa-forumbee'
            ),
            array(
                'name' => 'foursquare',
                'icon' => 'fab fa-foursquare'
            ),
            array(
                'name' => 'free-code-camp',
                'icon' => 'fab fa-free-code-camp'
            ),
            array(
                'name' => 'freebsd',
                'icon' => 'fab fa-freebsd'
            ),
            array(
                'name' => 'fulcrum',
                'icon' => 'fab fa-fulcrum'
            ),
            array(
                'name' => 'galactic-republic',
                'icon' => 'fab fa-galactic-republic'
            ),
            array(
                'name' => 'galactic-senate',
                'icon' => 'fab fa-galactic-senate'
            ),
            array(
                'name' => 'get-pocket',
                'icon' => 'fab fa-get-pocket'
            ),
            array(
                'name' => 'gg',
                'icon' => 'fab fa-gg'
            ),
            array(
                'name' => 'gg-circle',
                'icon' => 'fab fa-gg-circle'
            ),
            array(
                'name' => 'git',
                'icon' => 'fab fa-git'
            ),
            array(
                'name' => 'git-square',
                'icon' => 'fab fa-git-square'
            ),
            array(
                'name' => 'github',
                'icon' => 'fab fa-github'
            ),
            array(
                'name' => 'github-alt',
                'icon' => 'fab fa-github-alt'
            ),
            array(
                'name' => 'github-square',
                'icon' => 'fab fa-github-square'
            ),
            array(
                'name' => 'gitkraken',
                'icon' => 'fab fa-gitkraken'
            ),
            array(
                'name' => 'gitlab',
                'icon' => 'fab fa-gitlab'
            ),
            array(
                'name' => 'gitter',
                'icon' => 'fab fa-gitter'
            ),
            array(
                'name' => 'glide',
                'icon' => 'fab fa-glide'
            ),
            array(
                'name' => 'glide-g',
                'icon' => 'fab fa-glide-g'
            ),
            array(
                'name' => 'gofore',
                'icon' => 'fab fa-gofore'
            ),
            array(
                'name' => 'goodreads',
                'icon' => 'fab fa-goodreads'
            ),
            array(
                'name' => 'goodreads-g',
                'icon' => 'fab fa-goodreads-g'
            ),
            array(
                'name' => 'google',
                'icon' => 'fab fa-google'
            ),
            array(
                'name' => 'google-drive',
                'icon' => 'fab fa-google-drive'
            ),
            array(
                'name' => 'google-play',
                'icon' => 'fab fa-google-play'
            ),
            array(
                'name' => 'google-plus',
                'icon' => 'fab fa-google-plus'
            ),
            array(
                'name' => 'google-plus-g',
                'icon' => 'fab fa-google-plus-g'
            ),
            array(
                'name' => 'google-plus-square',
                'icon' => 'fab fa-google-plus-square'
            ),
            array(
                'name' => 'google-wallet',
                'icon' => 'fab fa-google-wallet'
            ),
            array(
                'name' => 'gratipay',
                'icon' => 'fab fa-gratipay'
            ),
            array(
                'name' => 'grav',
                'icon' => 'fab fa-grav'
            ),
            array(
                'name' => 'gripfire',
                'icon' => 'fab fa-gripfire'
            ),
            array(
                'name' => 'grunt',
                'icon' => 'fab fa-grunt'
            ),
            array(
                'name' => 'gulp',
                'icon' => 'fab fa-gulp'
            ),
            array(
                'name' => 'hacker-news',
                'icon' => 'fab fa-hacker-news'
            ),
            array(
                'name' => 'hacker-news-square',
                'icon' => 'fab fa-hacker-news-square'
            ),
            array(
                'name' => 'hackerrank',
                'icon' => 'fab fa-hackerrank'
            ),
            array(
                'name' => 'hips',
                'icon' => 'fab fa-hips'
            ),
            array(
                'name' => 'hire-a-helper',
                'icon' => 'fab fa-hire-a-helper'
            ),
            array(
                'name' => 'hooli',
                'icon' => 'fab fa-hooli'
            ),
            array(
                'name' => 'hornbill',
                'icon' => 'fab fa-hornbill'
            ),
            array(
                'name' => 'hotjar',
                'icon' => 'fab fa-hotjar'
            ),
            array(
                'name' => 'houzz',
                'icon' => 'fab fa-houzz'
            ),
            array(
                'name' => 'html5',
                'icon' => 'fab fa-html5'
            ),
            array(
                'name' => 'hubspot',
                'icon' => 'fab fa-hubspot'
            ),
            array(
                'name' => 'imdb',
                'icon' => 'fab fa-imdb'
            ),
            array(
                'name' => 'instagram',
                'icon' => 'fab fa-instagram'
            ),
            array(
                'name' => 'internet-explorer',
                'icon' => 'fab fa-internet-explorer'
            ),
            array(
                'name' => 'ioxhost',
                'icon' => 'fab fa-ioxhost'
            ),
            array(
                'name' => 'itunes',
                'icon' => 'fab fa-itunes'
            ),
            array(
                'name' => 'itunes-note',
                'icon' => 'fab fa-itunes-note'
            ),
            array(
                'name' => 'java',
                'icon' => 'fab fa-java'
            ),
            array(
                'name' => 'jedi-order',
                'icon' => 'fab fa-jedi-order'
            ),
            array(
                'name' => 'jenkins',
                'icon' => 'fab fa-jenkins'
            ),
            array(
                'name' => 'joget',
                'icon' => 'fab fa-joget'
            ),
            array(
                'name' => 'joomla',
                'icon' => 'fab fa-joomla'
            ),
            array(
                'name' => 'js',
                'icon' => 'fab fa-js'
            ),
            array(
                'name' => 'js-square',
                'icon' => 'fab fa-js-square'
            ),
            array(
                'name' => 'jsfiddle',
                'icon' => 'fab fa-jsfiddle'
            ),
            array(
                'name' => 'kaggle',
                'icon' => 'fab fa-kaggle'
            ),
            array(
                'name' => 'keybase',
                'icon' => 'fab fa-keybase'
            ),
            array(
                'name' => 'keycdn',
                'icon' => 'fab fa-keycdn'
            ),
            array(
                'name' => 'kickstarter',
                'icon' => 'fab fa-kickstarter'
            ),
            array(
                'name' => 'kickstarter-k',
                'icon' => 'fab fa-kickstarter-k'
            ),
            array(
                'name' => 'korvue',
                'icon' => 'fab fa-korvue'
            ),
            array(
                'name' => 'laravel',
                'icon' => 'fab fa-laravel'
            ),
            array(
                'name' => 'lastfm',
                'icon' => 'fab fa-lastfm'
            ),
            array(
                'name' => 'lastfm-square',
                'icon' => 'fab fa-lastfm-square'
            ),
            array(
                'name' => 'leanpub',
                'icon' => 'fab fa-leanpub'
            ),
            array(
                'name' => 'less',
                'icon' => 'fab fa-less'
            ),
            array(
                'name' => 'line',
                'icon' => 'fab fa-line'
            ),
            array(
                'name' => 'linkedin',
                'icon' => 'fab fa-linkedin'
            ),
            array(
                'name' => 'linkedin-in',
                'icon' => 'fab fa-linkedin-in'
            ),
            array(
                'name' => 'linode',
                'icon' => 'fab fa-linode'
            ),
            array(
                'name' => 'linux',
                'icon' => 'fab fa-linux'
            ),
            array(
                'name' => 'lyft',
                'icon' => 'fab fa-lyft'
            ),
            array(
                'name' => 'magento',
                'icon' => 'fab fa-magento'
            ),
            array(
                'name' => 'mailchimp',
                'icon' => 'fab fa-mailchimp'
            ),
            array(
                'name' => 'mandalorian',
                'icon' => 'fab fa-mandalorian'
            ),
            array(
                'name' => 'markdown',
                'icon' => 'fab fa-markdown'
            ),
            array(
                'name' => 'mastodon',
                'icon' => 'fab fa-mastodon'
            ),
            array(
                'name' => 'maxcdn',
                'icon' => 'fab fa-maxcdn'
            ),
            array(
                'name' => 'medapps',
                'icon' => 'fab fa-medapps'
            ),
            array(
                'name' => 'medium',
                'icon' => 'fab fa-medium'
            ),
            array(
                'name' => 'medium-m',
                'icon' => 'fab fa-medium-m'
            ),
            array(
                'name' => 'medrt',
                'icon' => 'fab fa-medrt'
            ),
            array(
                'name' => 'meetup',
                'icon' => 'fab fa-meetup'
            ),
            array(
                'name' => 'megaport',
                'icon' => 'fab fa-megaport'
            ),
            array(
                'name' => 'microsoft',
                'icon' => 'fab fa-microsoft'
            ),
            array(
                'name' => 'mix',
                'icon' => 'fab fa-mix'
            ),
            array(
                'name' => 'mixcloud',
                'icon' => 'fab fa-mixcloud'
            ),
            array(
                'name' => 'mizuni',
                'icon' => 'fab fa-mizuni'
            ),
            array(
                'name' => 'modx',
                'icon' => 'fab fa-modx'
            ),
            array(
                'name' => 'monero',
                'icon' => 'fab fa-monero'
            ),
            array(
                'name' => 'napster',
                'icon' => 'fab fa-napster'
            ),
            array(
                'name' => 'neos',
                'icon' => 'fab fa-neos'
            ),
            array(
                'name' => 'nimblr',
                'icon' => 'fab fa-nimblr'
            ),
            array(
                'name' => 'nintendo-switch',
                'icon' => 'fab fa-nintendo-switch'
            ),
            array(
                'name' => 'node',
                'icon' => 'fab fa-node'
            ),
            array(
                'name' => 'node-js',
                'icon' => 'fab fa-node-js'
            ),
            array(
                'name' => 'npm',
                'icon' => 'fab fa-npm'
            ),
            array(
                'name' => 'ns8',
                'icon' => 'fab fa-ns8'
            ),
            array(
                'name' => 'nutritionix',
                'icon' => 'fab fa-nutritionix'
            ),
            array(
                'name' => 'odnoklassniki',
                'icon' => 'fab fa-odnoklassniki'
            ),
            array(
                'name' => 'odnoklassniki-square',
                'icon' => 'fab fa-odnoklassniki-square'
            ),
            array(
                'name' => 'old-republic',
                'icon' => 'fab fa-old-republic'
            ),
            array(
                'name' => 'opencart',
                'icon' => 'fab fa-opencart'
            ),
            array(
                'name' => 'openid',
                'icon' => 'fab fa-openid'
            ),
            array(
                'name' => 'opera',
                'icon' => 'fab fa-opera'
            ),
            array(
                'name' => 'optin-monster',
                'icon' => 'fab fa-optin-monster'
            ),
            array(
                'name' => 'osi',
                'icon' => 'fab fa-osi'
            ),
            array(
                'name' => 'page4',
                'icon' => 'fab fa-page4'
            ),
            array(
                'name' => 'pagelines',
                'icon' => 'fab fa-pagelines'
            ),
            array(
                'name' => 'palfed',
                'icon' => 'fab fa-palfed'
            ),
            array(
                'name' => 'patreon',
                'icon' => 'fab fa-patreon'
            ),
            array(
                'name' => 'paypal',
                'icon' => 'fab fa-paypal'
            ),
            array(
                'name' => 'periscope',
                'icon' => 'fab fa-periscope'
            ),
            array(
                'name' => 'phabricator',
                'icon' => 'fab fa-phabricator'
            ),
            array(
                'name' => 'phoenix-framework',
                'icon' => 'fab fa-phoenix-framework'
            ),
            array(
                'name' => 'phoenix-squadron',
                'icon' => 'fab fa-phoenix-squadron'
            ),
            array(
                'name' => 'php',
                'icon' => 'fab fa-php'
            ),
            array(
                'name' => 'pied-piper',
                'icon' => 'fab fa-pied-piper'
            ),
            array(
                'name' => 'pied-piper-alt',
                'icon' => 'fab fa-pied-piper-alt'
            ),
            array(
                'name' => 'pied-piper-hat',
                'icon' => 'fab fa-pied-piper-hat'
            ),
            array(
                'name' => 'pied-piper-pp',
                'icon' => 'fab fa-pied-piper-pp'
            ),
            array(
                'name' => 'pinterest',
                'icon' => 'fab fa-pinterest'
            ),
            array(
                'name' => 'pinterest-p',
                'icon' => 'fab fa-pinterest-p'
            ),
            array(
                'name' => 'pinterest-square',
                'icon' => 'fab fa-pinterest-square'
            ),
            array(
                'name' => 'playstation',
                'icon' => 'fab fa-playstation'
            ),
            array(
                'name' => 'product-hunt',
                'icon' => 'fab fa-product-hunt'
            ),
            array(
                'name' => 'pushed',
                'icon' => 'fab fa-pushed'
            ),
            array(
                'name' => 'python',
                'icon' => 'fab fa-python'
            ),
            array(
                'name' => 'qq',
                'icon' => 'fab fa-qq'
            ),
            array(
                'name' => 'quinscape',
                'icon' => 'fab fa-quinscape'
            ),
            array(
                'name' => 'quora',
                'icon' => 'fab fa-quora'
            ),
            array(
                'name' => 'r-project',
                'icon' => 'fab fa-r-project'
            ),
            array(
                'name' => 'ravelry',
                'icon' => 'fab fa-ravelry'
            ),
            array(
                'name' => 'react',
                'icon' => 'fab fa-react'
            ),
            array(
                'name' => 'readme',
                'icon' => 'fab fa-readme'
            ),
            array(
                'name' => 'rebel',
                'icon' => 'fab fa-rebel'
            ),
            array(
                'name' => 'red-river',
                'icon' => 'fab fa-red-river'
            ),
            array(
                'name' => 'reddit',
                'icon' => 'fab fa-reddit'
            ),
            array(
                'name' => 'reddit-alien',
                'icon' => 'fab fa-reddit-alien'
            ),
            array(
                'name' => 'reddit-square',
                'icon' => 'fab fa-reddit-square'
            ),
            array(
                'name' => 'rendact',
                'icon' => 'fab fa-rendact'
            ),
            array(
                'name' => 'renren',
                'icon' => 'fab fa-renren'
            ),
            array(
                'name' => 'replyd',
                'icon' => 'fab fa-replyd'
            ),
            array(
                'name' => 'researchgate',
                'icon' => 'fab fa-researchgate'
            ),
            array(
                'name' => 'resolving',
                'icon' => 'fab fa-resolving'
            ),
            array(
                'name' => 'rev',
                'icon' => 'fab fa-rev'
            ),
            array(
                'name' => 'rocketchat',
                'icon' => 'fab fa-rocketchat'
            ),
            array(
                'name' => 'rockrms',
                'icon' => 'fab fa-rockrms'
            ),
            array(
                'name' => 'safari',
                'icon' => 'fab fa-safari'
            ),
            array(
                'name' => 'sass',
                'icon' => 'fab fa-sass'
            ),
            array(
                'name' => 'schlix',
                'icon' => 'fab fa-schlix'
            ),
            array(
                'name' => 'scribd',
                'icon' => 'fab fa-scribd'
            ),
            array(
                'name' => 'searchengin',
                'icon' => 'fab fa-searchengin'
            ),
            array(
                'name' => 'sellcast',
                'icon' => 'fab fa-sellcast'
            ),
            array(
                'name' => 'sellsy',
                'icon' => 'fab fa-sellsy'
            ),
            array(
                'name' => 'servicestack',
                'icon' => 'fab fa-servicestack'
            ),
            array(
                'name' => 'shirtsinbulk',
                'icon' => 'fab fa-shirtsinbulk'
            ),
            array(
                'name' => 'shopware',
                'icon' => 'fab fa-shopware'
            ),
            array(
                'name' => 'simplybuilt',
                'icon' => 'fab fa-simplybuilt'
            ),
            array(
                'name' => 'sistrix',
                'icon' => 'fab fa-sistrix'
            ),
            array(
                'name' => 'sith',
                'icon' => 'fab fa-sith'
            ),
            array(
                'name' => 'skyatlas',
                'icon' => 'fab fa-skyatlas'
            ),
            array(
                'name' => 'skype',
                'icon' => 'fab fa-skype'
            ),
            array(
                'name' => 'slack',
                'icon' => 'fab fa-slack'
            ),
            array(
                'name' => 'slack-hash',
                'icon' => 'fab fa-slack-hash'
            ),
            array(
                'name' => 'slideshare',
                'icon' => 'fab fa-slideshare'
            ),
            array(
                'name' => 'snapchat',
                'icon' => 'fab fa-snapchat'
            ),
            array(
                'name' => 'snapchat-ghost',
                'icon' => 'fab fa-snapchat-ghost'
            ),
            array(
                'name' => 'snapchat-square',
                'icon' => 'fab fa-snapchat-square'
            ),
            array(
                'name' => 'soundcloud',
                'icon' => 'fab fa-soundcloud'
            ),
            array(
                'name' => 'speakap',
                'icon' => 'fab fa-speakap'
            ),
            array(
                'name' => 'spotify',
                'icon' => 'fab fa-spotify'
            ),
            array(
                'name' => 'squarespace',
                'icon' => 'fab fa-squarespace'
            ),
            array(
                'name' => 'stack-exchange',
                'icon' => 'fab fa-stack-exchange'
            ),
            array(
                'name' => 'stack-overflow',
                'icon' => 'fab fa-stack-overflow'
            ),
            array(
                'name' => 'staylinked',
                'icon' => 'fab fa-staylinked'
            ),
            array(
                'name' => 'steam',
                'icon' => 'fab fa-steam'
            ),
            array(
                'name' => 'steam-square',
                'icon' => 'fab fa-steam-square'
            ),
            array(
                'name' => 'steam-symbol',
                'icon' => 'fab fa-steam-symbol'
            ),
            array(
                'name' => 'sticker-mule',
                'icon' => 'fab fa-sticker-mule'
            ),
            array(
                'name' => 'strava',
                'icon' => 'fab fa-strava'
            ),
            array(
                'name' => 'stripe',
                'icon' => 'fab fa-stripe'
            ),
            array(
                'name' => 'stripe-s',
                'icon' => 'fab fa-stripe-s'
            ),
            array(
                'name' => 'studiovinari',
                'icon' => 'fab fa-studiovinari'
            ),
            array(
                'name' => 'stumbleupon',
                'icon' => 'fab fa-stumbleupon'
            ),
            array(
                'name' => 'stumbleupon-circle',
                'icon' => 'fab fa-stumbleupon-circle'
            ),
            array(
                'name' => 'superpowers',
                'icon' => 'fab fa-superpowers'
            ),
            array(
                'name' => 'supple',
                'icon' => 'fab fa-supple'
            ),
            array(
                'name' => 'teamspeak',
                'icon' => 'fab fa-teamspeak'
            ),
            array(
                'name' => 'telegram',
                'icon' => 'fab fa-telegram'
            ),
            array(
                'name' => 'telegram-plane',
                'icon' => 'fab fa-telegram-plane'
            ),
            array(
                'name' => 'tencent-weibo',
                'icon' => 'fab fa-tencent-weibo'
            ),
            array(
                'name' => 'themeco',
                'icon' => 'fab fa-themeco'
            ),
            array(
                'name' => 'themeisle',
                'icon' => 'fab fa-themeisle'
            ),
            array(
                'name' => 'trade-federation',
                'icon' => 'fab fa-trade-federation'
            ),
            array(
                'name' => 'trello',
                'icon' => 'fab fa-trello'
            ),
            array(
                'name' => 'tripadvisor',
                'icon' => 'fab fa-tripadvisor'
            ),
            array(
                'name' => 'tumblr',
                'icon' => 'fab fa-tumblr'
            ),
            array(
                'name' => 'tumblr-square',
                'icon' => 'fab fa-tumblr-square'
            ),
            array(
                'name' => 'twitch',
                'icon' => 'fab fa-twitch'
            ),
            array(
                'name' => 'twitter',
                'icon' => 'fab fa-twitter'
            ),
            array(
                'name' => 'twitter-square',
                'icon' => 'fab fa-twitter-square'
            ),
            array(
                'name' => 'typo3',
                'icon' => 'fab fa-typo3'
            ),
            array(
                'name' => 'uber',
                'icon' => 'fab fa-uber'
            ),
            array(
                'name' => 'uikit',
                'icon' => 'fab fa-uikit'
            ),
            array(
                'name' => 'uniregistry',
                'icon' => 'fab fa-uniregistry'
            ),
            array(
                'name' => 'untappd',
                'icon' => 'fab fa-untappd'
            ),
            array(
                'name' => 'usb',
                'icon' => 'fab fa-usb'
            ),
            array(
                'name' => 'ussunnah',
                'icon' => 'fab fa-ussunnah'
            ),
            array(
                'name' => 'vaadin',
                'icon' => 'fab fa-vaadin'
            ),
            array(
                'name' => 'viacoin',
                'icon' => 'fab fa-viacoin'
            ),
            array(
                'name' => 'viadeo',
                'icon' => 'fab fa-viadeo'
            ),
            array(
                'name' => 'viadeo-square',
                'icon' => 'fab fa-viadeo-square'
            ),
            array(
                'name' => 'viber',
                'icon' => 'fab fa-viber'
            ),
            array(
                'name' => 'vimeo',
                'icon' => 'fab fa-vimeo'
            ),
            array(
                'name' => 'vimeo-square',
                'icon' => 'fab fa-vimeo-square'
            ),
            array(
                'name' => 'vimeo-v',
                'icon' => 'fab fa-vimeo-v'
            ),
            array(
                'name' => 'vine',
                'icon' => 'fab fa-vine'
            ),
            array(
                'name' => 'vk',
                'icon' => 'fab fa-vk'
            ),
            array(
                'name' => 'vnv',
                'icon' => 'fab fa-vnv'
            ),
            array(
                'name' => 'vuejs',
                'icon' => 'fab fa-vuejs'
            ),
            array(
                'name' => 'weebly',
                'icon' => 'fab fa-weebly'
            ),
            array(
                'name' => 'weibo',
                'icon' => 'fab fa-weibo'
            ),
            array(
                'name' => 'weixin',
                'icon' => 'fab fa-weixin'
            ),
            array(
                'name' => 'whatsapp',
                'icon' => 'fab fa-whatsapp'
            ),
            array(
                'name' => 'whatsapp-square',
                'icon' => 'fab fa-whatsapp-square'
            ),
            array(
                'name' => 'whmcs',
                'icon' => 'fab fa-whmcs'
            ),
            array(
                'name' => 'wikipedia-w',
                'icon' => 'fab fa-wikipedia-w'
            ),
            array(
                'name' => 'windows',
                'icon' => 'fab fa-windows'
            ),
            array(
                'name' => 'wix',
                'icon' => 'fab fa-wix'
            ),
            array(
                'name' => 'wolf-pack-battalion',
                'icon' => 'fab fa-wolf-pack-battalion'
            ),
            array(
                'name' => 'wordpress',
                'icon' => 'fab fa-wordpress'
            ),
            array(
                'name' => 'wordpress-simple',
                'icon' => 'fab fa-wordpress-simple'
            ),
            array(
                'name' => 'wpbeginner',
                'icon' => 'fab fa-wpbeginner'
            ),
            array(
                'name' => 'wpexplorer',
                'icon' => 'fab fa-wpexplorer'
            ),
            array(
                'name' => 'wpforms',
                'icon' => 'fab fa-wpforms'
            ),
            array(
                'name' => 'xbox',
                'icon' => 'fab fa-xbox'
            ),
            array(
                'name' => 'xing',
                'icon' => 'fab fa-xing'
            ),
            array(
                'name' => 'xing-square',
                'icon' => 'fab fa-xing-square'
            ),
            array(
                'name' => 'y-combinator',
                'icon' => 'fab fa-y-combinator'
            ),
            array(
                'name' => 'yahoo',
                'icon' => 'fab fa-yahoo'
            ),
            array(
                'name' => 'yandex',
                'icon' => 'fab fa-yandex'
            ),
            array(
                'name' => 'yandex-international',
                'icon' => 'fab fa-yandex-international'
            ),
            array(
                'name' => 'yelp',
                'icon' => 'fab fa-yelp'
            ),
            array(
                'name' => 'yoast',
                'icon' => 'fab fa-yoast'
            ),
            array(
                'name' => 'youtube',
                'icon' => 'fab fa-youtube'
            ),
            array(
                'name' => 'youtube-square',
                'icon' => 'fab fa-youtube-square'
            ),
            array(
                'name' => 'zhihu',
                'icon' => 'fab fa-zhihu'
            ),
        );

        function __construct() {
            parent::__construct(
                
                // Base ID of widget
                'bstone_light_widget_social_icons',

                // Widget name will appear in UI
                __('Bstone Social Icons', 'bstone-light'),

                // Widget description
                array( 'description' => __( 'Display social icons.', 'bstone-light' ) )
            );

            add_action('current_screen', array($this, 'check_current_screen'));

            //Hooks to enqueue javascript file in SiteOrigin builder.
		    add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'admin_scripts' ) );

            // Enqueue the color picker script and style
            add_action( 'load-widgets.php', array( $this, 'load_wp_colorpicker' ) );
        }

        /**
         * Enqueue admin javascript only on widgets and customizer pages.
         */
        public function check_current_screen() {
            $current_screen = get_current_screen();
    
            if ( ! empty( $current_screen->id ) && ( $current_screen->id === 'widgets' || $current_screen->id === 'customize' ) ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
            }
        }

        /**
         * Included styles and js files in the backend part.
         */
        public function admin_scripts() {
            wp_enqueue_style( 'socicon', BSTONE_LIGHT_URI . 'inc/assets/css/choosen.css', array(), '20180625' );

            wp_enqueue_script(
                'bstone-light-widget-social-icons-js',
                BSTONE_LIGHT_URI . 'inc/assets/js/choosen.js',
                array(),
                '20170209',
                false
            );

            wp_enqueue_script(
                'bstone-light-widget-script-js',
                BSTONE_LIGHT_URI . 'inc/assets/js/widgets-script.js',
                array(),
                '20170209',
                true
            );
        }

        // Enqueue the color picker script and style
        function load_wp_colorpicker() {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
        }

        // Widget Frontend
        public function widget( $args, $instance ) {
            $title = apply_filters( 'widget_title', $instance['title'] );
            $description             = empty( $instance['description'] )              ? ''   : $instance['description'];
            $open_in_new_tab         = isset( $instance['open_in_new_tab'] )          ? $instance['open_in_new_tab']              : true;
            $nofollow                = isset( $instance['nofollow'] )                 ? $instance['nofollow']                     : false;
            $icon_padding            = ( ! empty( $instance['icon_padding'] ) )       ? absint( $instance['icon_padding'] )       : 0;
            $icon_margin             = ( ! empty( $instance['icon_margin'] ) )        ? absint( $instance['icon_margin'] )        : 0;
            $icon_size               = ( ! empty( $instance['icon_size'] ) )          ? absint( $instance['icon_size'] )          : 0;
            $icon_border_width       = ( ! empty( $instance['icon_border_width'] ) )  ? absint( $instance['icon_border_width'] )  : 0;
            $icon_border_radius      = ( ! empty( $instance['icon_border_radius'] ) ) ? absint( $instance['icon_border_radius'] ) : 0;
            $icon_color              = empty( $instance['icon_color'] )               ? ''    : $instance['icon_color'];
            $icon_bg_color           = empty( $instance['icon_bg_color'] )            ? ''    : $instance['icon_bg_color'];
            $icon_border_color       = empty( $instance['icon_border_color'] )        ? ''    : $instance['icon_border_color'];
            $icon_color_hover        = empty( $instance['icon_color_hover'] )         ? ''    : $instance['icon_color_hover'];
            $icon_bg_color_hover     = empty( $instance['icon_bg_color_hover'] )      ? ''    : $instance['icon_bg_color_hover'];
            $icon_border_color_hover = empty( $instance['icon_border_color_hover'] )  ? ''    : $instance['icon_border_color_hover'];

            if ( ! $icon_padding )       { $icon_padding       = 0; }
            if ( ! $icon_margin )        { $icon_margin        = 0; }
            if ( ! $icon_size )          { $icon_size          = 0; }
            if ( ! $icon_border_width )  { $icon_border_width  = 0; }
            if ( ! $icon_border_radius ) { $icon_border_radius = 0; }

            $icons = $instance['icons'];

            // Widget ID
            $bst_widget_id = 'bst-'.uniqid();

            ?>
            <aside class="widget bst-search-widget" id="<?php echo $bst_widget_id; ?>">
                <?php
                
                // before and after widget arguments are defined by themes
                echo ( isset( $before_widget ) ? $before_widget : '' );

                if ( ! empty( $title ) ) {
                    echo $args['before_title'] . $title . $args['after_title'];
                }
                
                // This is where you run the code and display the output

                if ( ! empty( $description ) ) {
                    echo '<p>'.$description.'</p>';
                }

                $new_tab_attr  = '';
                $nofollow_attr = '';

                if( $open_in_new_tab ) { $new_tab_attr  = ' target="_blank"'; }
                if( $nofollow )        { $nofollow_attr = ' rel="nofollow"'; }
                ?>
                
                <?php if( isset( $icons ) ) { ?>
                <div class="bst-social-icons-widget-cnt">
                    <ul>
                        <?php
                            if( is_array( $icons ) ) {
                                foreach( $icons as $fi_name => $fi_value ) {
                        ?>
                        <li>
                            <?php 
                            if( '' != $fi_value['url'] ) { echo '<a href="'.$fi_value['url'].'"'.$new_tab_attr.$nofollow_attr.'>'; }
                            echo '<i class="'.$fi_value['icon'].'"></i>';
                            if( '' != $fi_value['url'] ) { echo '</a>'; }
                            ?>
                        </li>
                        <?php } } ?>
                    </ul>
                </div>
                <?php } ?>

                <?php
                echo ( isset( $after_widget ) ? $after_widget : '' );
                ?>

                <style type="text/css">
                    #<?php echo $bst_widget_id; ?> ul li {
                        margin-left: <?php echo $icon_margin; ?>px;
                        margin-right: <?php echo $icon_margin; ?>px;
                    }
                    #<?php echo $bst_widget_id; ?> ul li:first-child {
                        margin-left: 0px;
                    }
                    #<?php echo $bst_widget_id; ?> ul li:last-child {
                        margin-right: 0px;
                    }
                    #<?php echo $bst_widget_id; ?> ul li a {
                        color: <?php echo $icon_color; ?>;
                        background: <?php echo $icon_bg_color; ?>;
                        width: <?php echo $icon_size; ?>px;
                        height: <?php echo $icon_size; ?>px;
                        font-size: <?php echo $icon_size; ?>px;
                        padding: <?php echo $icon_padding; ?>px;
                        border: solid <?php echo $icon_border_width; ?>px <?php echo $icon_border_color; ?>;
                        border-radius: <?php echo $icon_border_radius; ?>px;
                    }
                    #<?php echo $bst_widget_id; ?> ul li a:hover {
                        color: <?php echo $icon_color_hover; ?>;
                        background: <?php echo $icon_bg_color_hover; ?>;
                        border: solid <?php echo $icon_border_width; ?>px <?php echo $icon_border_color_hover; ?>;
                    }
                    #<?php echo $bst_widget_id; ?> ul li i {
                        font-size: <?php echo $icon_size; ?>px;
                    }
                </style>
            </aside>
            <?php
        }

        // Widget Backend
        public function form( $instance ) {
            $defaults = array(
                'title'                   => 'Follow us',
                'description'             => '',
                'open_in_new_tab'         => true,
                'nofollow'                => false,
                'icon_padding'            => 0,
                'icon_margin'             => 5,
                'icon_size'               => 18,
                'icon_border_width'       => 0,
                'icon_border_radius'      => 0,
                'icon_color'              => '#ffffff',
                'icon_bg_color'           => '#199EDA',
                'icon_border_color'       => '#199EDA',
                'icon_color_hover'        => '#ffffff',
                'icon_bg_color_hover'     => '#393A3B',
                'icon_border_color_hover' => '#393A3B',
                'icon_repeat_count'       => '',
                'icons'                   => array( array( 'icon' => '', 'url' => '' ) ),
            );

            $instance = wp_parse_args( ( array ) $instance, $defaults );

            $title                   = esc_attr( $instance['title'] );
            $description             = esc_attr( $instance['description'] );
            $open_in_new_tab         = esc_attr( $instance['open_in_new_tab'] );
            $nofollow                = esc_attr( $instance['nofollow'] );
            $icon_padding            = esc_attr( $instance['icon_padding'] );
            $icon_margin             = esc_attr( $instance['icon_margin'] );
            $icon_size               = esc_attr( $instance['icon_size'] );
            $icon_border_width       = esc_attr( $instance['icon_border_width'] );
            $icon_border_radius      = esc_attr( $instance['icon_border_radius'] );
            $icon_color              = esc_attr( $instance['icon_color'] );
            $icon_bg_color           = esc_attr( $instance['icon_bg_color'] );
            $icon_border_color       = esc_attr( $instance['icon_border_color'] );
            $icon_color_hover        = esc_attr( $instance['icon_color_hover'] );
            $icon_bg_color_hover     = esc_attr( $instance['icon_bg_color_hover'] );
            $icon_border_color_hover = esc_attr( $instance['icon_border_color_hover'] );
            $icon_repeat_count       = esc_attr( $instance['icon_repeat_count'] );
            $icons                   = $instance['icons'];
            
            // Widget ID
            $bst_widget_id = 'bst-admin-'.uniqid();

            // Widget admin form
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

            <p>
				<label
					for="<?php echo $this->get_field_id( 'description' ); ?>"><?php esc_html_e( 'Description:', 'bstone-light' ); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>"
                      name="<?php echo $this->get_field_name( 'description' ); ?>" cols="20" v-model="description"
                      rows="3"></textarea>
				<small><?php _e( 'Short description to be displayed right above the icons. Basic HTML allowed.', 'bstone-light' ); ?></small>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox"<?php checked( $open_in_new_tab ); ?> id="<?php echo $this->get_field_id( 'open_in_new_tab' ); ?>" name="<?php echo $this->get_field_name( 'open_in_new_tab' ); ?>" />
                <label for="<?php echo $this->get_field_id( 'open_in_new_tab' ); ?>"><?php _e( 'Open links in new tab?', 'bstone-light' ); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox"<?php checked( $nofollow ); ?> id="<?php echo $this->get_field_id( 'nofollow' ); ?>" name="<?php echo $this->get_field_name( 'nofollow' ); ?>" />
                <label for="<?php echo $this->get_field_id( 'nofollow' ); ?>"><?php _e( 'Add <code>rel="nofollow"</code> to links', 'bstone-light' ); ?></label>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_padding' ); ?>"><?php _e( 'Icon Padding:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'icon_padding' ); ?>" name="<?php echo $this->get_field_name( 'icon_padding' ); ?>" type="number" value="<?php echo esc_attr( $icon_padding ); ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_margin' ); ?>"><?php _e( 'Icon Spacing:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'icon_margin' ); ?>" name="<?php echo $this->get_field_name( 'icon_margin' ); ?>" type="number" value="<?php echo esc_attr( $icon_margin ); ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php _e( 'Icon Size:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'icon_size' ); ?>" name="<?php echo $this->get_field_name( 'icon_size' ); ?>" type="number" value="<?php echo esc_attr( $icon_size ); ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_border_width' ); ?>"><?php _e( 'Icon Border Width:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'icon_border_width' ); ?>" name="<?php echo $this->get_field_name( 'icon_border_width' ); ?>" type="number" value="<?php echo esc_attr( $icon_border_width ); ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_border_radius' ); ?>"><?php _e( 'Icon Border Radius:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'icon_border_radius' ); ?>" name="<?php echo $this->get_field_name( 'icon_border_radius' ); ?>" type="number" value="<?php echo esc_attr( $icon_border_radius ); ?>" />
            </p>

            <script type='text/javascript'>
                jQuery(document).ready(function($) {
                    $('.bst-color-picker').wpColorPicker();
                });
            </script>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_color' ); ?>"><?php _e( 'Icon Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" value="<?php echo $icon_color; ?>" />                            
            </p>
            
            <p>
                <label for="<?php echo $this->get_field_id( 'icon_color_hover' ); ?>"><?php _e( 'Icon Hover Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'icon_color_hover' ); ?>" value="<?php echo $icon_color_hover; ?>" />                            
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_bg_color' ); ?>"><?php _e( 'Icon Background Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_bg_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_bg_color' ); ?>" value="<?php echo $icon_bg_color; ?>" />                            
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_bg_color_hover' ); ?>"><?php _e( 'Icon Hover Background Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_bg_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'icon_bg_color_hover' ); ?>" value="<?php echo $icon_bg_color_hover; ?>" />                            
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_border_color' ); ?>"><?php _e( 'Icon Border Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_border_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_border_color' ); ?>" value="<?php echo $icon_border_color; ?>" />                            
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icon_border_color_hover' ); ?>"><?php _e( 'Icon Hover Border Color', 'bstone-light' ); ?></label>
                <input class="bst-color-picker" type="text" id="<?php echo $this->get_field_id( 'icon_border_color_hover' ); ?>" name="<?php echo $this->get_field_name( 'icon_border_color_hover' ); ?>" value="<?php echo $icon_border_color_hover; ?>" />                            
            </p>

            <?php
                $bst_icon_count = 0;
                if( is_array( $icons ) ) {
                foreach( $icons as $fi_name => $fi_value ) {
                    $bst_icon_count++;
            ?>

            <div class="bst-social-icons-repeat">
            
            <p id="<?php echo $bst_widget_id; ?>-select-icons-<?php echo $bst_icon_count; ?>">
                <label for="<?php echo $this->get_field_id( 'icons' ).'-'.$bst_icon_count; ?>"><?php _e( 'Icon:', 'bstone-light' ); ?></label>
                <select class="chosen" style="width:150px;" name="<?php echo $this->get_field_name( 'icons' ); ?>[<?php echo $bst_icon_count; ?>][icon]" id="<?php echo $this->get_field_id( 'icons' ).'-'.$bst_icon_count; ?>">
                    <option value=""><?php _e( 'Select An Icon', 'bstone-light' ); ?></option>
                    <?php
                        foreach( $this->icons_data as $icon ) {
                            echo '<option'; ?> <?php if( $fi_value['icon'] == $icon['icon'] ) { echo ' selected'; } ?> <?php echo ' value="'.$icon['icon'].'">' . str_replace( "-"," ", $icon['name'] ) . '</option>';
                        }
                    ?>
                </select>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $("#<?php echo $bst_widget_id; ?>-select-icons-<?php echo $bst_icon_count; ?> .chosen").chosen();
                    });
                </script>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'icons' ).'-url-'.$bst_icon_count; ?>"><?php echo __( 'URL:', 'bstone-light' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'icons' ).'-url-'.$bst_icon_count; ?>" name="<?php echo $this->get_field_name( 'icons' ); ?>[<?php echo $bst_icon_count; ?>][url]" type="text" value="<?php echo $fi_value['url']; ?>" />
            </p>

            <?php if( $bst_icon_count > 1 ) { echo '<a href="#" class="bst-icon-remove" onClick="jQuery(this).parent().remove(); return false;">'.__( 'Remove', 'bstone-light' ).'</a>'; } ?>

            </div>

            <?php } } ?>

            <div id="<?php echo $bst_widget_id; ?>-cnt" class="bst-social-icons-cnt"></div>

            <p>
                <button type="button" data-container="<?php echo $bst_widget_id; ?>-cnt" data-icons-count="<?php echo $bst_icon_count; ?>" data-icons-id="<?php echo $this->get_field_id( 'icons' ); ?>" data-icons-name="<?php echo $this->get_field_name( 'icons' ); ?>" data-icon-cnt-id="<?php echo $bst_widget_id; ?>" data-icons-icon-label="<?php _e( 'Icon:', 'bstone-light' ); ?>" data-icons-icon-empty="<?php _e( 'Select An Icon', 'bstone-light' ); ?>" data-icons-url-label="<?php echo __( 'URL:', 'bstone-light' ); ?>" data-icon-remove="<?php echo __( 'Remove', 'bstone-light' ); ?>" class="button bst-add-social-icons"><?php echo __( 'Add More', 'bstone-light' ); ?></button>
            </p>

            <?php
        }
            
        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['title']                   = ( ! empty( $new_instance['title'] ) )                   ? strip_tags( $new_instance['title'] )                   : '';
            $instance['description']             = ( ! empty( $new_instance['description'] ) )             ? strip_tags( $new_instance['description'] )             : '';
            $instance['open_in_new_tab']         = isset( $new_instance['open_in_new_tab'] )               ? (bool) $new_instance['open_in_new_tab']                : true;
            $instance['nofollow']                = isset( $new_instance['nofollow'] )                      ? (bool) $new_instance['nofollow']                       : false;
            $instance['icon_padding']            = ( ! empty( $new_instance['icon_padding'] ) )            ? strip_tags( $new_instance['icon_padding'] )            : 0;
            $instance['icon_margin']             = ( ! empty( $new_instance['icon_margin'] ) )             ? strip_tags( $new_instance['icon_margin'] )             : 5;
            $instance['icon_size']               = ( ! empty( $new_instance['icon_size'] ) )               ? strip_tags( $new_instance['icon_size'] )               : 0;
            $instance['icon_border_width']       = ( ! empty( $new_instance['icon_border_width'] ) )       ? strip_tags( $new_instance['icon_border_width'] )       : 0;
            $instance['icon_border_radius']      = ( ! empty( $new_instance['icon_border_radius'] ) )      ? strip_tags( $new_instance['icon_border_radius'] )      : 0;
            $instance['icon_color']              = ( ! empty( $new_instance['icon_color'] ) )              ? strip_tags( $new_instance['icon_color'] )              : '';
            $instance['icon_bg_color']           = ( ! empty( $new_instance['icon_bg_color'] ) )           ? strip_tags( $new_instance['icon_bg_color'] )           : '';
            $instance['icon_border_color']       = ( ! empty( $new_instance['icon_border_color'] ) )       ? strip_tags( $new_instance['icon_border_color'] )       : '';     
            $instance['icon_color_hover']        = ( ! empty( $new_instance['icon_color_hover'] ) )        ? strip_tags( $new_instance['icon_color_hover'] )        : '';
            $instance['icon_bg_color_hover']     = ( ! empty( $new_instance['icon_bg_color_hover'] ) )     ? strip_tags( $new_instance['icon_bg_color_hover'] )     : '';
            $instance['icon_border_color_hover'] = ( ! empty( $new_instance['icon_border_color_hover'] ) ) ? strip_tags( $new_instance['icon_border_color_hover'] ) : '';

            $instance['icons'] = array();

            if ( isset ( $new_instance['icons'] ) ) {

                foreach ( $new_instance['icons'] as $value ) {
                    if( '' != $value['icon'] ) {
                        $instance['icons'][] = array( 'icon' => $value['icon'], 'url' => $value['url'] );
                    }
                }

            } else {
                $instance['icons'] = array( array( 'icon' => '', 'url' => '' ) );
            }

            return $instance;
        }
    }
}