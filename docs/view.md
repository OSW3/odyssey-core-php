# View

## create a view

##### 1) View file

To create a **view**, you need to create the file <code>BlogComponent/Views/index.php</code> into your component directory.



# Hériter d'une vue parente
$this->layout(string $file [, array $data]);


# - Block de Section

# Définir une section
$this->start(string $sectionName)
$this->stop(string $sectionName)

# Appel d'une section
$this->section(string $sectionName)


# - Les Assets

$this->asset(string $file)
$this->image(string $file)
$this->css(string $file)
$this->script(string $file)

# - Les routes

$this->url(string $route [, array $params [, boolean $absolute]])
$this->getCurrentRoute()
$this->jsRoutes()

# - Flashbag

$this->getFlashbag()

# - Include

$this->include(string $file [, array $params [, boolean $once]])

# - User

$this->getCurrentUser([string $field])
$this->isGranted(array $roles)



# - Variable
# Nom du site 
-> config.php = 'site_name'	=> 'OSW3 Micro-Framework',
-> view = <?= $site_name ?>