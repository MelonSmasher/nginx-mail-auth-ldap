# PHP MAIL Auth Server LDAP

A very simple authentication server for NGINX mail authentication that relies on modern libraries to authenticate with LDAP.

## Setup

#### Install Libraries

```bash
cd /usr/share/nginx/html;
git clone https://github.com/MelonSmasher/nginx-mail-auth-ldap.git;
cd nginx-mail-auth-ldap;
composer install;
cp .env.example .env;
# edit the new .env file
```

#### Auth Server Setup

```nginx
server {
    listen                127.0.0.1:9500;
    root                  /usr/share/nginx/html/nginx-mail-auth-ldap;
    index                 index.php index.html;

    location ~ \.php$ {
        include           fastcgi_params;
        fastcgi_param     SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param     PATH_INFO $fastcgi_path_info;
        fastcgi_pass      unix:/run/php/php7.1-fpm.sock;
     }
}
```

#### Mail Setup

SMTP example:

```nginx
mail {
    server_name            mail.example.com;
    auth_http              127.0.0.1:9500/auth.php;
    
    ssl_certificate       /path/to/cert.pem;
    ssl_certificate_key   /path/to/private/key.key;
    ssl_protocols         TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers           HIGH:!aNULL:!MD5;
    ssl_session_cache     shared:MAIL:10m;
    ssl_session_timeout   10m;
    starttls              on;
    smtp_auth             plain login;
    xclient               off;
    
    server {
        listen 465 ssl;
        protocol smtp;
    }
    
    server {
        listen 587;
        protocol smtp;
    }
}
```
