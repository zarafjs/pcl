# CakePHP pcl Plugin

A plugin built on ACL for managing user group permissions in CakePHP applications.

## Synopsis

CakePHP 3 Permission Control Plugin (pcl) is built on CakePHP acl plugin. It providses a user-friendly visual interface to set permissions for each individual groups of users in the application.

## Requirements

pcl assumes that cakephp/acl has already been successfully implemented in the application.


## Installing via composer

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require mainuljs/pcl
```

Then make sure in your `config\bootstrap.php`:
```php
Plugin::load('Pcl', ['bootstrap' => false]);
```
