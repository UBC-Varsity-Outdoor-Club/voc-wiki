# VOC Wiki
## Instructions for setting up the Wiki on a new machine ##
1. Clone this repository
2. Create a file named `.env`, and add the settings included in `.env.sample`
3. Make a copy of `LocalSettings.php.sample` (remove the `.sample` suffix)
4. In LocalSettings.php, update `$wgSecretKey` from the placeholder value to a random string (you can generate one with `openssl rand -hex 64`)
5. In LocalSettings.php, update `$wgUpgradeKey` from the placeholder value to a random string