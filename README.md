Timelinetool
=================================


Demo:
---------------------------------
There is no live demo yet.


Requirements
---------------------------------
- at least PHP 5.1 (PHP 5.3 recommended)
- HTML5 enabled browser (older browsers might work aswell, but are (and will remain) untested


Setup
---------------------------------
1. Download and install [PHP Composer](http://getcomposer.org): `curl -s http://getcomposer.org/installer | php`.
2. Install the vendor packages afterwards: `./composer.phar install`.
3. Configure your settings at `app/config/config.yml`, copy needed settings from `app/config/default.config.yml` as needed.
4. Adjust the translations and/or define new translations in `app/languages/`.
See `vendor/timelinetool/languages/xx.language.yml` for available translation keys.
5. Upload all files.
6. Make sure that the necessary folders are writable. 
These are at least `app/storage`, `app/cache` and `app/compile` plus subfolders.

>There will be a graphical Installer one day to automate these last steps for you, but for now you have to do this manually.


Update
---------------------------------
To upgrade, simply use the Composer (`./composer.phar update`):
"index.php", "composer.json" and "public" folders might need to be replaced aswell, check the Release Notes for this.


License
---------------------------------
CandyCMS is licensed under [GPLv3](http://www.gnu.org/licenses/gpl.html).