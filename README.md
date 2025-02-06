


<p align="center">
    <a href="https://github.com/EldoranDev/XGP-Rebirth" target="_blank">
        <img align="center" src="https://raw.githubusercontent.com/EldoranDev/XGP-Rebirth/refs/heads/main/assets/images/logo-rebirth-dark.webp" width="250px" title="XG Proyect" alt="xgp-logo">
    </a>
    <br>
    <strong>XGP</strong> Rebirth
    <br>
    <strong>Open-source OGame Clone</strong>
</p> 

About
====

XGP Rebirth is a modernised version of XG Proyect and allows to create clones of the original OGame. 

This is the attempt to migrate the original project to a more modern and updated version of PHP as well as moving it into a modern framework instead of basing it on a custom self build framework.

## Requirements

PHP 8.4 or greater  
MySQLi 8.0 or greater  

## How to get XGP Rebirth?

### Manually
This is the simplest and easiest way if you're not a technical person. Download and install XG Proyect will be easy! ;)

 1. Go to [releases](https://github.com/XGProyect/XG-Proyect-v3.x.x/releases)
 2. Look for the last version and then **assets** and finally look for the `.zip` file.
 3. Unzip the file.
 4. Browse the folder and search for the upload directory, there are hidden files in it, be sure that those are copied over also, specially the `.htaccess` file.
 5. Using docker, XAMPP or any local stack that you want set the copies files to your root.

### Composer
Composer is a package manager and also a quick way to setup your project.

1. Run
```
composer create-project xgproyect/xgproyect
```
2. Once composer has finishing installing all the dependencies you can use docker, see below.

## How to run XG Proyect?
### Kubernetes/Helm


### Docker
Easiest way to do it, is using Docker.

```
docker-compose up
```

You can also build with different PHP versions:
```
docker build -t xgproyect:7.4 --build-arg PHP_VERSION=7.4 .
```

Or build and run, altogether, specifying a **PHP version**:
```
docker-compose build --build-arg PHP_VERSION=8.2 && docker-compose up -d
```

Simple change the **PHP version** to any other **version** that you'd like to test.


## MailHog
XGP uses MailHog and PHPMailer as tools for better mailing support. MailHog allows you to intercept emails **locally** and receive them under a convenient panel.

Read our <a href="https://github.com/XGProyect/XG-Proyect-v3.x.x/wiki/MailHog-usage-and-setup" target="_blank">MailHog guide</a> to get started.

## License
The XG Proyect is open-sourced software licensed under the GPL-3.0 License.
