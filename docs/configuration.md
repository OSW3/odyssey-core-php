# Configuration

## Create de configuration file

1. Duplicate the template config file <code>/app/config/config.php.dist</code>
2. Rename the new file to <code>/app/config/config.php</code> 
3. Open <code>/app/config/config.php</code> change parameters

## Default parameters

All parameters are stored in the array <code>$config</code>.

### General parameters

<dl>
    <dt><strong>site_name</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the name of the website.</li>
            <li>Default : null</li>
        </ul>
    </dd>
    <dt><strong>env</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the execution environnement.</li>
            <li>Values : Value can be '<strong>dev</strong>' or '<strong>prod</strong>'.</li>
            <li>Default : dev</li>
        </ul>
    </dd>
</dl>

### Database parameters

<dl>
    <dt><strong>db_host</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the host name.</li>
            <li>Default : 127.0.0.1.</li>
        </ul>
    </dd>
    <dt><strong>db_user</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the user name.</li>
            <li>Default : root</li>
        </ul>
    </dd>
    <dt><strong>db_pass</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the password of the user.</li>
            <li>Default : null</li>
        </ul>
    </dd>
    <dt><strong>db_name</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the name of the schema.</li>
            <li>Default : null</li>
        </ul>
    </dd>
    <dt><strong>db_table_prefix</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the table prefix.</li>
            <li>Default : null</li>
        </ul>
    </dd>
</dl>

### Security parameters

<dl>
    <dt><strong>security_user_table</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the name of the table (without the db_table_prefix) of users data.</li>
            <li>Default : users</li>
        </ul>
    </dd>
    <dt><strong>security_id_property</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the primary key field name of users table.</li>
            <li>Default : id</li>
        </ul>
    </dd>
    <dt><strong>security_username_property</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the username field name of users table.</li>
            <li>Default : username</li>
        </ul>
    </dd>
    <dt><strong>security_email_property</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the email field name of users table.</li>
            <li>Default : email</li>
        </ul>
    </dd>
    <dt><strong>security_password_property</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the password field name of users table.</li>
            <li>Default : password</li>
        </ul>
    </dd>
    <dt><strong>security_roles_property</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the roles field name of users table.</li>
            <li>Default : roles</li>
        </ul>
    </dd>
    <dt><strong>security_login_route_name</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the name of the login route.</li>
            <li>Default : signin</li>
        </ul>
    </dd>
</dl>


## Add your parameters

You can add your own parameter anywhere on the <code>$config</code> array.

## Get parameters

<small>/app/config/config.php</small>
```php
<?php
$config = [
    "site_name" => "Odyssey Discovery",
    // ...
];
```

### Get a parameter in a controller

<small>/src/MyComponent/Controller/myController.php</small>
```php
$this->getConfig('site_name'); // Odyssey Discovery
```

### Get a parameter in a view

<small>/src/MyComponent/Views/myView.php</small>
```php
<h1><?= $this->e('site_name') ?></h1>
```