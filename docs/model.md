# Model

### Create a model

##### 1) Model file

To create a **model**, you need to create the file <code>BlogComponent/Model/ArtcilesModel.php</code> into your component directory.

##### 2) Model Class

Create your **model** class and extend the main Model class :

```
<?php

namespace BlogComponent\Model;

use \Odyssey\Model\Model;

class articlesModel extends Model
{
}
```

### Use a model

Into your controller files, you can use models like this :

```
<?php

use BlogComponent\Model\ArticlesModel.php
// ...

public function indexAction()
{
    // ...
    $articles = new ArticlesModel;
    // ...
}
```

<br>
<br>


# Main model methods

Odyssey main model have some default methods 



## <code>find();</code>

Retrieve a item by his ID.

#### Parameters

- **id** (integer) The ID of the item you want to retrieve.

#### Return

Array of fields of the item of query results.

#### Exemple

```
$articles->find(integer $id)
```

---

## <code>findNext();</code>

Retrieve the next item of the ID.

#### Parameters

- **id** (integer) The previous ID of the item you want to retrieve.

#### Return

Array of fields of the item of query results.

#### Exemple

```
$articles->findNext(integer $id)
```

---

## <code>findPrevious();</code>

Retrieve the previous item of the ID.

#### Parameters

- **id** (integer) The next ID of the item you want to retrieve.

#### Return

Array of fields of the item of query results.

#### Exemple

```
$articles->findPrevious(integer $id)
```

---

## <code>findAll();</code>

Retrieve all items of a table data.

#### Parameters

- **orderBy** (string) -

- **orderDir** (string) -

- **limit** (integer) -

- **offset** (integer) -

#### Return

Array of items.

#### Exemple

```
$articles->findAll([string $orderBy [, string $orderDir [, integer $limit [, integer $offset]]]])
```

---

## <code>findBy();</code>

Retrieve an item of a table data with colum contraints.

#### Parameters

- **column** (string) -

- **value** (string) -

#### Return

Array of items.

#### Exemple

```
$articles->findBy([string $column [, string $value]])
```

---

## <code>findAllby();</code>

Retrieve all items of a table data with colum containts.

#### Parameters

- **column** (string) -

- **value** (string) -

- **orderBy** (string) -

- **orderDir** (string) -

- **limit** (integer) -

- **offset** (integer) -

#### Return

#### Exemple

```
$articles->findAllBy([string $column [, string $value [, string $orderBy [, string $orderDir [, integer $limit [, integer $offset]]]]]])
```

---

## <code>search();</code>

#### Parameters

- **search** (array) -

- **operator** (string) -

- **stripTags** (boolean) -

#### Return

#### Exemple

```
$articles->search(array $search [, string $operator [, boolean $stripTags]])
```

---

## <code>delete();</code>

Delete an item.

#### Parameters

- **id** (integer) The ID of the item you want to delete.

#### Return

#### Exemple

```
$articles->delete(integer $id)
```

---

## <code>insert();</code>

Instert an item.

#### Parameters

- **data** (array) -

- **stripTags** (boolean) -

#### Return

#### Exemple

```
$articles->insert(array $data [, boolean $stripTags])
```

---

## <code>update();</code>

Update an item.

#### Parameters

- **search** (array) -

- **id** (integer) -

- **stripTags** (boolean) -

#### Return

#### Exemple

```
$articles->update(array $data, integer $id [, boolean $stripTags])
```