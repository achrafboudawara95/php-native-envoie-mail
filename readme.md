# How to use
**Run Commands**

    $ composer install

**Configuration**
Edit `config.php` file

**Body :** 
 - address* : required receiver address
 - body* : required email body
 - subject : email subject
 - name : receiver name
**requirements**
 - headers :  token(equals to config.php token), Content-Type : application/json
 - method : post
 - request body : json
