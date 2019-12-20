![Alt text](app/code/Brajola/ForceLogin/resources/magento.png?raw=true "Title")

## Magento 2 ForceLogin Module
This module enables the option to block the viewing of URLs by unregistered users.

Tested in Magento Community Version 2.3.3

### Module Installation
##### Installing via Composer
```#composer require brajola/ForceLogin```

##### Manual Installation
  - Download the package at https://github.com/brajola/ForceLogin
  - Unzip to project root folder

##### Commands
```php bin/magento setup:upgrade```

```php bin/magento setup:di:compile```

```php bin/magento cache:clean```

###Use
The initial idea of this module is to block access to all store URLs for unlogged customers but it is possible to add URLs to a whitelist where they will be ignored.
This module creates a new section on the Magento admin page (Stores> Settings> Configuration) where the following actions are possible:
 - Enable and disable the module

 - Insert, Edit and Delete Whitelist Items
