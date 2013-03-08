Getting Started With OpenUserBundle
==================================

## Prerequisites

This version of the bundle requires Symfony 2.1. 

## Installation


### Step 1: Download OpenUserBundle using composer

Add OpenUserBundle in your composer.json:

```js
{
    "require": {
        "open/user-bundle":"*"
    }
}
```
If you haven't allready done so, get Composer.

    curl -s http://getcomposer.org/installer | php

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update 
```

Composer will install the bundle to your project's `open` directory.

### Step 2: Enable the bundle

Enable the bundles in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
       // ...
	   new Open\UserBundle\OpenUserBundle(),
       new FOS\UserBundle\FOSUserBundle(),
	   new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),

    );
}
```

### Step 2: Configure your application's security.yml

In order for Symfony's security component to use the FOSUserBundle, you must
tell it to do so in the `security.yml` file. The `security.yml` file is where the
basic configuration for the security for your application is contained.

Below is a minimal example of the configuration necessary to use the FOSUserBundle
in your application:

``` yaml
# app/config/security.yml
jms_security_extra:
    secure_all_services: false
    expressions: true

security:
    providers:
        fos_userbundle:
            id: fos_user.user_provider.username_email

    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    firewalls:
        secured_area:
            pattern:    ^/user/
            anonymous: true
            logout: 
                path: /user/logout
            anonymous:    true
            form_login:
                provider: fos_userbundle
                login_path: /user/login
                check_path: /user/login_check
                csrf_provider: form.csrf_provider
            oauth:
                resource_owners:
                    google:             "/user/login/check-google"
                    facebook:           "/user/login/check-facebook"
                login_path: /user/connect
                failure_path: /user/connect
                oauth_user_provider:
                    service: hwi_oauth.user.provider.fosub_bridge

    access_control:
        - { path: ^/user/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/admin/, role: ROLE_ADMIN }

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN
```

Under the `providers` section, you are making the bundle's packaged user provider
service available via the alias `fos_userbundle`. The id of the bundle's user
provider service is `fos_user.user_provider.username`.

Next, take a look at examine the `firewalls` section. Here we have declared a
firewall named `main`. By specifying `form_login`, you have told the Symfony2
framework that any time a request is made to this firewall that leads to the
user needing to authenticate himself, the user will be redirected to a form
where he will be able to enter his credentials. It should come as no surprise
then that you have specified the user provider we declared earlier as the
provider for the firewall to use as part of the authentication process.

**Note:**

> Although we have used the form login mechanism in this example, the FOSUserBundle
> user provider is compatible with many other authentication methods as well. Please
> read the Symfony2 Security component documention for more information on the
> other types of authentication methods.

The `access_control` section is where you specify the credentials necessary for
users trying to access specific parts of your application. The bundle requires
that the login form and all the routes used to create a user and reset the password
be available to unauthenticated users but use the same firewall as
the pages you want to secure with the bundle. This is why you have specified that
the any request matching the `/login` pattern or starting with `/register` or
`/resetting` have been made available to anonymous users. You have also specified
that any request beginning with `/admin` will require a user to have the
`ROLE_ADMIN` role.

For more information on configuring the `security.yml` file please read the Symfony2
security component [documentation](http://symfony.com/doc/current/book/security.html).

**Note:**

> Pay close attention to the name, `main`, that we have given to the firewall which
> the FOSUserBundle is configured in. You will use this in the next step when you
> configure the FOSUserBundle.

### Step 3: Configure the OpenUserBundle

Now that you have properly configured your application's `security.yml` to work
with the FOSUserBundle, the next step is to configure the bundle to work with
the specific needs of your application.

Add the following configuration to your `config.yml` file according to which type
of datastore you are using.

``` yaml
# app/config/config.yml
fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    user_class: Open\UserBundle\Entity\User
    #...
    service:
        mailer: fos_user.mailer.twig_swift
    resetting:
        email:
            from_email:
                address:        your_address@domain.com
                sender_name:    your_name
            template: OpenUserBundle:Resetting:resetting.email.twig
    registration:
        form:
            handler: Open_user.form.handler.registration
        confirmation:
            enabled:    true
            from_email:
                address:        your_address@domain.com
                sender_name:    your_name
            template: OpenUserBundle:Registration:registrationconfirm.email.twig

hwi_oauth:
    resource_owners:
        facebook:
            type: facebook
            client_id: <your_client_id>
            client_secret: <your_client_secret>
            scope: "email"
            user_response_class: 'Open\UserBundle\OAuth\Response\FacebookUserResponse'
            paths:
                email: email
                profilepicture: picture
        google:
            type: google
            client_id: <your_client_id>
            client_secret: <your_client_secret>
            scope: "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
            user_response_class: 'Open\UserBundle\OAuth\Response\GoogleUserResponse'
            paths:
                email: email
                profilepicture: picture

    firewall_name: secured_area
    fosub:
        username_iterations: 5
        properties:
            google: googleId
            facebook: facebookId
    connect: ~
```


### Step 4: Import OpenUserBundle routing files

Now that you have activated and configured the bundle, all that is left to do is
import the OpenUserBundle routing files.

By importing the routing files you will have ready made pages for things such as
logging in, creating users, etc.

In YAML:

``` yaml
# app/config/routing.yml
hwi_oauth_redirect:
    resource: "@HWIOAuthBundle/Resources/config/routing/redirect.xml"
    prefix:   /user/connect

OpenUserBundle_homepage:
    pattern:  /user/login/{service}
    defaults: { _controller: OpenUserBundle:Connect:redirectToService }

OpenUserBundle_loginpage:
    pattern:  /user/connect
    defaults: { _controller: OpenUserBundle:Connect:connect }

Open_oauth_connect_registration:
    pattern:  /user/registration/{key}
    defaults: { _controller: OpenUserBundle:Connect:registration }
    
connect:
    resource: "@HWIOAuthBundle/Resources/config/routing/connect.xml"
    prefix:   /user/connect

login:
    resource: "@HWIOAuthBundle/Resources/config/routing/login.xml"
    prefix:   /user/connect

fos_user_security:
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"
    prefix: /user

fos_user_profile:
    resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
    prefix: /user

fos_user_register:
    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
    prefix: /user/register

fos_user_resetting:
    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
    prefix: /user

change_password:
    prefix:  /user
    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"

facebook_login:
    pattern: /user/login/check-facebook

google_login:
    pattern: /user/login/check-google

open_app_urlfacebook:
    pattern:  /user/connect/facebook

open_app_urlgoogle:
    pattern:  /user/connect/google
```

### Step 5: Install Assets

For install assets run the following command.

``` bash
$ php app/console assets:install web
```


### Step 6: Update your database schema

For ORM run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```










