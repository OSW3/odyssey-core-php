# Controller

### Create a controller

##### 1) Controller file

To create a **controller**, you need to create the file <code>BlogComponent/Controller/ArtcilesController.php</code> into your component directory.

##### 2) Controller Class

Create your **controller** class and extend the main Controller class :

```
<?php

namespace BlogComponent\Controller;

use \Odyssey\Controller\Controller;
// ...

class ArticlesController extends Controller
{
    // ...
}
```

### Use a controller

##### Call your controller with a route

Define your route (at <code>app/config/routes.php</code>) and specify names of Component, Classe and Action -separated by (:) double dot- in the third route parameter

```
$routes = [
    ["blog-article", "/blog", "Blog:Articles:index", "GET"],
];
```

<br>
<br>


# Main controller methods

Odyssey main controller 


## Rendering

## <code>render();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->render(string $file [, array $data])
```

---

## <code>renderNotFound();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->renderNotFound()
```

---

## <code>renderForbidden();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->renderForbidden()
```

---

## <code>renderJson();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->renderJson(array $data)
```

---


## Routing

## <code>url();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->url(string $route [, array $params [, boolean $absolute]])
```

---

## <code>redirect();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->redirect(string $url)
```

---

## <code>redirectToRoute();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->redirectToRoute(string $route [, array $params])
```

---

## <code>getCurrentRoute();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->getCurrentRoute()
```

---


## Assets

## <code>asset();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->asset(string $file)
```

---

## <code>image();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->image(string $file)
```

---

## <code>css();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->css(string $file)
```

---

## <code>script();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->script(string $file)
```

---


## User

## <code>getUser();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->getUser()
```

---

## <code>getCurrentUser();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->getCurrentUser(string $field)
```

---


## Flashbag

## <code>setFlashbag();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->setFlashbag(string $message [, string $level])
```

---

## <code>xxxxx();</code>

xxx.

#### Parameters

- **xxx** (string) ---

#### Return

xxx.

#### Exemple

```
$this->getFlashbag()
```