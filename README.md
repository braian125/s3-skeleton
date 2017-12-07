Welcome to S3 Skeleton!
===================

Install
-------------

**Using Composer**
>composer create-project gosoft/s3-skeleton=dev-master

**Github**
>git clone https://github.com/braian125/s3-skeleton.git

Run it
-------------

> **1**. run composer install<br />
> **2**. run npm i, on linux run with sudo (optional)<br />
> **3**. run npm install -g grunt, on linux run with sudo (optional)<br />
> **4**. Copy the .env.example file and rename it as .env, in the file configure your development environment variables<br />
> **5**. Serving your application using built-in PHP development server: **php -S localhost:8080 -t public**<br />

Direcotories
------------

>* `app`: Application code
>* `app/Controller`: All controllers files within the `App\Controller` namespace
>* `app/Models`: All models files within the `App\Models` namespace
>* `app/Middleware`: All middlewares files within the `App\Middleware` namespace
>* `resources/views`: Twig template files
>* `resources/assets`: The less work you have to do when performing repetitive tasks like minification, compilation, unit testing, linting, etc, the easier your job becomes.
>* `public`: Webserver root
>* `vendor`: Composer dependencies
>* `node_modules`: npm dependencies

How to use Grunt?
-------------

>**Devops**
>
> run grunt dev
>
>**Production**
>
> run grunt dist

Command Line
-------------

>**Create Controller**
>
>php skeleton create:controller yourcontrollername
>
>**Create Model**
>
>php skeleton create:model yourmodelname
>
>**Create Migration**
>
>php skeleton create:migration yourmigrationname
>
>php skeleton migrate
>
>php skeleton rollback



About us
--------
>**[@braian125](https://twitter.com/braian125)**
>
>**[@julianhm9612](https://twitter.com/julianhm9612)**