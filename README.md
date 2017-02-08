# Drupal Console Extend

Composer template and instructions to extend Drupal Console adding commands globally.

### Create `~/.console/` directory
Execute the `init` command and select `/Users/USER_NAME/.console/` when asked for destination.
```
drupal init
```
NOTES: For instructions about installing the [Drupal Console Launcher](https://github.com/hechoendrupal/DrupalConsole/#update-drupalconsole-launcher).

### Install this project:
```
cd ~/.console/

composer create-project \
drupal/console-extend extend \
--no-interaction
```
NOTE: You can use `--keep-vcs` to prevent deletion of `.git` directory.

### Update this project
```
cd ~/.console/extend

composer update
```
