# Sample Blog News Portal

## Application & Data

### Language Framework and Libraries 

* PHP  >= 5.5
* Zend Framework 2([ Documentation ](http://framework.zend.com/manual/current/en/index.html))
* Composer - Dependency Manager for PHP ([ site ](https://getcomposer.org/))

#### Notes

* Zend\I18n\View\Helper component requires the intl PHP extension
* Composer may ask to Github Auth Token

#### Architecture

* HMVC Architecture ([ Wiki ](https://en.wikipedia.org/wiki/Hierarchical_model%E2%80%93view%E2%80%93controller))

#### Libraries

* Imagine       ([ GitHub ](https://github.com/avalanche123/Imagine)) - Image Resize and Manipulate
* PHP Mailer    ([ GitHub ](https://github.com/PHPMailer/PHPMailer))  - Send Email   

##### Self Write Zend Modules

* Welcome - List latest 10 news 
* Account - Verify registred users, do authenticated user operations ( create new post and delete ) 
* News    - read news, convert to pdf and subscribe RSS feeds. 

##### 3rd Party Zend Modules

* ZendDeveloperTools    ([ GitHub ](https://github.com/zendframework/ZendDeveloperTools)) - Debug tools for working with the Zend Framework 2
* DOMPDFModule          ([ GitHub ](https://github.com/raykolbe/DOMPDFModule))            - HTML to PDF 
* ZfcBase               ([ GitHub ](https://github.com/ZF-Commons/ZfcBase))               - Common classes used across several ZF2 modules
* ZfcUser               ([ GitHub ](https://github.com/ZF-Commons/ZfcUser))               - user registration and authentication module for Zend Framework 2

### Databases

* MySQL 5.6 +

| Database                 | charset       | collation            |
| ------------------------ |:-------------:| --------------------:|
| blog                     | utf8          | utf8_unicode_ci      |

## Theme

* Clean Blog ([ Download Original ](http://startbootstrap.com/template-overviews/clean-blog/))  

## Developers

* Zeki Unal <zekiunal@gmail.com>
