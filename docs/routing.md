# Routing

To define a route, open <code>/app/config/routes.php</code> and add your route into the <code>$route</code> array.

Build a route like this :
```php
[name, path, controller, method]
```

## Parameters

<dl>
    <dt><strong>name</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the name of the route. Used to generate URL.</li>
        </ul>
    </dd>
    <dt><strong>path</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the path (directory and/or file) of the URL.</li>
        </ul>
    </dd>
    <dt><strong>controller</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the name of the component, the controller and the action separated by the hastag symbol (:).</li>
        </ul>
    </dd>
    <dt><strong>method</strong></dt>
    <dd>
        <ul>
            <li>Type : string</li>
            <li>Definition : Define the HTTP methods allowed to the request. Multiple methods are separated by a pipe symbole (|).</li>
        </ul>
    </dd>
</dl>

## Exemple

```php
$routes = [
    ["home", "/", "Default:Default:home", "GET"],
];
```
