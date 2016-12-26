# Drupal Console extend

Instructions to extend Drupal Console adding commands globally.

## Create `~/.console/` directory (if not created already)
Execute the `init` command and select `/Users/YOUR_USER_NAME/.console/` when asked for destination.
```
drupal init
```
NOTES:
* For instructions about installing the [Drupal Console Launcher](https://github.com/hechoendrupal/DrupalConsole/#update-drupalconsole-launcher)

## Install this project:
```
cd ~/.console/

composer create-project \
drupal/console-extend:dev-master extend \
--no-dev \
--no-interaction
```

## Update this project
```
cd ~/.console/extend

composer update --no-dev
```
