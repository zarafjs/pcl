# CakePHP pcl Plugin
A plugin built on cakephp/acl for managing user group permissions in CakePHP applications.

## Synopsis
CakePHP 3 Permission Control Plugin (pcl) is built on cakephp/acl plugin. It providses a user-friendly visual interface to set permissions for each individual group of users in the application.

## Requirements
pcl assumes that cakephp/acl has already been successfully implemented in the application.

## Installing via composer
You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require muinjs/pcl
```

Then make sure in your `config\bootstrap.php`:
```php
Plugin::load('Pcl', ['bootstrap' => false]);
```

##Usage
1. After successful installation navigate to /pcl/access/sync.
2. On the redirected page set which prefixes, controllers or actions to display on the main permissions page.
3. Finally navigate to /pcl/access/groups to set the required global or specific permissions.


##Thank you
Please feel free to suggest any updates to the repo. Also let me know if you feel any difficulties or find any bugs.
