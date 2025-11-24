<?php
# LocalSettings.php for MediaWiki 1.44
# Minimal configuration based on your previous file

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
    exit;
}

error_reporting( E_ALL );

## Site settings
$wgSitename      = "VOC Wiki";
$wgMetaNamespace = "VOCWiki";

## URL settings
$wgServer        = getenv("MEDIAWIKI_URL");   # e.g., https://wiki.ubc-voc.com
$wgScriptPath    = "";
$wgScriptExtension = ".php";
$wgArticlePath   = "/$1";
$wgUsePathInfo   = true;

## Logo & favicon
$wgLogo    = "$wgScriptPath/images/vocwikilogo.png";
$wgFavicon = "$wgScriptPath/images/favicon.ico";

## Database settings
$wgDBtype   = "mysql";
$wgDBserver = getenv("MYSQL_HOST") ?: "db";  # docker service name
$wgDBname   = getenv("MYSQL_DATABASE");
$wgDBuser   = getenv("MYSQL_USER");
$wgDBpassword = getenv("MYSQL_PASSWORD");

$wgDBprefix = "";

## Table options
$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=utf8mb4";

## Secret key
$wgSecretKey = getenv("MEDIAWIKI_SECRETKEY") ?: 'replace-with-random-string';

## Permissions
$wgGroupPermissions['*']['edit'] = false;

## Email
$wgEnableEmail = true;
$wgEnableUserEmail = true;
$wgEmergencyContact = getenv("MEDIAWIKI_ADMIN_EMAIL");
$wgPasswordSender   = getenv("MEDIAWIKI_ADMIN_EMAIL");

## Uploads
$wgEnableUploads = true;
$wgUseImageMagick = false;

## Cache (minimal)
$wgMainCacheType = CACHE_NONE;
$wgMemCachedServers = [];

## Language & timezone
$wgLanguageCode = "en";
$wgLocaltimezone = "America/Vancouver";

## Skins
wfLoadSkin('Vector');
$wgDefaultSkin = "vector";

## Extensions (load only what you need)
wfLoadExtension('ConfirmEdit');
$wgCaptchaClass = 'QuestyCaptcha';
@include(__DIR__ . '/captcha.php');

wfLoadExtension('UserMerge');
$wgGroupPermissions['bureaucrat']['usermerge'] = true;
$wgGroupPermissions['sysop']['usermerge'] = true;

wfLoadExtension('CategoryTree');

wfLoadExtension('VisualEditor');
$wgDefaultUserOptions['visualeditor-enable'] = 1;

## Parsoid / REST API
$wgVirtualRestConfig['modules']['parsoid'] = [
    'url' => 'http://localhost:8142',
    'domain' => 'localhost',
    'prefix' => 'localhost',
];

## Debugging (optional)
$wgShowExceptionDetails = true;
$wgShowDBErrorBacktrace = true;
$wgShowSQLErrors = true;

## End of configuration
