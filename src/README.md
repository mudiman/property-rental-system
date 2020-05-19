<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About RentalSytem

## Configuration

    Setup ubuntu server 16.04 VM machine

    Install packages

    Please follow laravel setup to install missing package. https://laravel.com/docs/5.4.  NOTE we are using nginx not apache
    Install below packages using command: sudo apt-get install "package_name"'
    Install php composer
    php7, php7-fpm, php7-bcmath,php7-mcrypt, php7-mysql (Note once you run composer install for install all depencies any missing pacakges will be mention which needs to be installed)
    nginx
    mysql-server
    supervisor
    redis
    
    Deploy code in machine to this path inorder for configuration file to match. /home/ubuntu/smoor/

    Configure nginx by creating new site with configuration file smoor-nginx.conf in server_config_files folder. This file will go to /etc/nginx/sites-available/smoor-nginx.conf
    And make symobolic link to enable site
    ln -s /etc/nginx/sites-available/smoor-nginx.conf /etc/nginx/sites-enabled/


    Configure supervisor with configuration file smoor-supervisor.conf in server_config_files folder. This file will go to /etc/supervisor/conf.d/smoor-supervisor.conf
    
    Install and configure nodjs server in nodejs folder using package.json

## Deployment

    For deployment we are using code deploy

    Dev enviromnet is auto deployment when code is push in "dev" branch. I have made dev branch default branch on github as i want to deploy dev commits imediately to dev server.
    For stage and live. Please open two urls github commit page and aws code deploy application

    https://github.com/SmoorApp/smoor/commits/dev
    https://eu-west-1.console.aws.amazon.com/codedeploy/home?region=eu-west-1

    Choose application(environment) for which you want to deploy. Then press the radio button to select deployment group and from action button menu press "Deply new revision"
    In this new page use repository type Github
    And pase commit id and github app name which is "SmoorApp/smoor"
    Remember to always check "Dont fail the deployment to an instance if" and "Overwrite the content"


Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb combination of simplicity, elegance, and innovation give you tools you need to build any application with which you are tasked.

## Learning Smoor

Laravel has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Laravel documentation](https://laravel.com/docs) is thorough, complete, and makes it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 900 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](http://patreon.com/taylorotwell):

- **[Vehikl](http://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Styde](https://styde.net)**
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
