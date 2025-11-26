# VOC Wiki
The VOC Wiki is a Mediawiki application running inside a Docker container. The version of Mediawiki that the Docker container runs is defined in the `image` field of `docker-compose.yml`. If you decide to upgrade the version of Mediawiki, it is safest to build a separate container in case something goes wrong 
## Instructions for building the wiki ##
1. Clone this repository, and update `docker-compose.yml` to the desired version of Mediawiki
2. Create a file named `.env`, and add the settings included in `.env.sample`
3. Create a file named `captcha.php` and add the answers to the questions in `captcha.php.sample` (feel free to also change the questions, or add new ones)
4. Build the Docker container `docker compose build`
5. Run the Docker container `docker compose up -d`
6. Visit the application in the browser (assuming you've already set up DNS, and configured nginx to proxy wiki traffic to port 8080 where the container is listening). You should see the Mediawiki web installer. Follow the instructions to install Mediawiki
   1. (Make note of the admin username and password!)
7. Copy the wiki backup into the Docker container
   ```sh
    docker cp backup.xml mediawiki:/backup.xml
   ```
8.  Restore the backup and rebuild the changes. The first step is expected to take a long time if you are using a backup including the full revisions history. You may want to run it as a background docker process (`-d`) so that it doesn't stop when your SSH session terminates
   ```sh
    docker exec -it -d mediawiki php maintenance/run.php importDump /backup.xml > /var/www/html/import.log 2>&1
    docker exec -it mediawiki php maintenance/run.php rebuildrecentchanges
    docker exec -it mediawiki php maintenance/run.php initSiteStats
   ```
9.  Restore uploaded files (copy the `images` directory from the current wiki)
    ```sh
    docker cp images mediawiki:/var/www/html/images
    docker exec -it mediawiki php maintenance/run.php importImages --conf /var/www/html/LocalSettings.php /var/www/html/images
    ```

10. The Main_Page doesn't come in with the import for some reason. To get around this, you can manually import it using the interface of the old wiki, import it into the new wiki under a different name, and then move it to the "Main Page" name. The same trickery can be applied to MediaWiki:Sidebar

## Creating backups
The backup used by the migration process above can be obtained by running:
```sh
docker compose exec -it mediawiki php maintenance/run.php dumpBackup --current > backup.xml
```
For a backup that includes *all* revision history (and takes a wildly long time to import), use:
```sh
docker compose exec -it mediawiki php maintenance/run.php dumpBackup --full > backup.xml
```

## Extensions
### ConfirmEdit (and QuestyCaptcha)
ConfirmEdit requires users to complete a CAPTCHA before submitting an edit to a page (or creating a new page). QuestyCaptcha allows the CAPTCHA to be a custom, VOC-related trivia question, which is allegedly more successful against wiki spam than typical CAPTCHAs. It is recommended by MediaWiki as an anti-spam measure

To upgrade ConfirmEdit, download the latest release from [Mediawiki](https://www.mediawiki.org/wiki/Extension:ConfirmEdit) and replace the current `extensions/ConfirmEdit` directory with the new version. Ensure that the new version of ConfirmEdit is compatible with the version of Mediawiki used by the Docker container (it is likely best to trythis on localhost first)

### UserMerge
UserMerge allows admins to combine accounts A and B by merging all of A's history into B and then deleting A. This is useful when moving the wiki to a new machine, because it enables contributors to simply make new accounts on the new machine without losing their old contributions.

### CategoryTree
Allows a view of all categorized pages on the "Content" tab

### VisualEditor
Enables a WYSIWYG editor for pages

### ParserFunctions
Used on several template pages
