# Sample Blog News Portal

Public news publishing portal where news can be published and disseminated

##  Installation

### Source Code

 * Copy **http** directory to in your apache server root directory.

 * Give write permission 777 to http/public/content folder.

 * Run composer in to the http folder 

```
cd /http
composer update
```

5 - Configure your apache http server. 

#### example httpd.conf File

```

<VirtualHost 127.0.0.1:80>

    <Directory "/blog/sample.blog.com/http/public">
        Options FollowSymLinks Indexes
        
        AllowOverride All
        
        Order deny,allow
        
        allow from All
        
    </Directory>
    
        ServerName dev.news.blog.com
        
        ServerAlias dev.news.blog.com
        
        DocumentRoot "/blog/sample.blog.com/http/public"
        
        ErrorLog "/blog/sample.blog.com/logs/local.blog.err"
        
        CustomLog "/blog/sample.blog.com/logs/local.blog.log" combined   
</VirtualHost>

```

6 - Configure your host file. 

#### Hosts File

```
127.0.0.0.1 dev.news.blog.com
```

### Database

1 - Create MySQL database name of : **blog**

2 - Run schema sql script : **/build/database/schema.sql**

3 - Set your database configuration in to the **/http/config/autoload/global.php** or **/http/config/autoload/local.php**

### Application Configuration

1 - convert al dist files to php files -> http\config\autoload\zenddevelopertools.local.php.dist to http\config\autoload\zenddevelopertools.local.php

### Mail 

Application working with PHP Mailer via sendmail