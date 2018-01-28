<?php
/**
 * CAPYBARA INDEXING SCRIPT
 * Index all of your folder with ease.
 */

 
 /**
  * Show the folder link, instead of using the viewer link
  * if the directory have index file in it. (is recursively checked)
  */
define('AUTODETECT_INDEX', true);


/**
 * The Index file direction, if you need to change the file
 * name.
 * Default: '/', (or change to index.php if you really worried).
 */
define('INDEX_NAME', '/');

/**
 * Index file list, will be checked for each folder.
 * If you have activated the AUTODETECT_INDEX, this setting
 * will be crucial to detect your index file.
 */
define('AUTODETECT_INDEX_FILELIST', [
    'index.html',
    'index.htm',
    'index.php',
    'index.py'
]);



/**
 * END OF SETTINGS, YOU CAN ENJOY YOUR DAY NOW.
 */


$_GET['q'] = isset($_GET['q'])?$_GET['q']:null;
$not_allowed_url = [
    "/" => "",
    "./"=> "",
    "../"=>"",
];
// Query Validation
if(strpos($_GET['q'], './') || strpos($_GET['q'], '../' || $_GET['q'] == '.')){
    $location = INDEX_NAME;
    if($_GET['q'] == '.')
        $_GET['q']='';
    $location .= ltrim(str_replace(
        key($not_allowed_url,
        array_values($not_allowed_url),
        $_SERVER['QUERY_STRING']), '/')
    );

    header('Location: ' . $location);
}
    
function precheck($path){
    if($path == '/' || $path == DIRECTORY_SEPARATOR)
        return true;
    if(is_file($path))
        return precheck(dirname($path));
    if(basename($path)[0] == "." || is_file($path . DIRECTORY_SEPARATOR . '.noindex'))
        return false;
    if(strpos($path, DIRECTORY_SEPARATOR))
        return true;
    return precheck(dirname($path));
}

function get_link($path) {
    if(!is_dir($path))
        return $path;
    
    if(AUTODETECT_INDEX)
        foreach(AUTODETECT_INDEX_FILELIST as $idx){
            if(is_file($path . DIRECTORY_SEPARATOR . $idx))
                return $path;   
        }
    
    return ltrim(INDEX_NAME . '?q=' . $_GET['q'] . $path);
}

// note: $path are always the full path.
$path = __DIR__;
if($_GET['q'])
    $path .= DIRECTORY_SEPARATOR . $_GET['q'];

$folders = [];
$folder = array_slice(scandir($path), 2);
foreach($folder as $paths) {
    if(!precheck($path . DIRECTORY_SEPARATOR . $paths))
        continue;
    $folders[] = $paths;
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index of /<?= htmlentities($_GET['q']?:'') ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.0.0/js/all.js"></script>
</head>
<body>
    <section class="hero is-dark">
        <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                    <a class="navbar-item">
                        Capdex
                    </a>
                    <span class="navbar-burger burger" data-target="navbarMenuHeroA">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    </div>
                    <div id="navbarMenuHeroA" class="navbar-menu">
                        <div class="navbar-end">
                            <a class="navbar-item" href="<?= htmlentities(INDEX_NAME) ?>"> Home </a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    <i>Fancy</i> Index For 
                </h1>
                <h2 class="subtitle">
                    /<?= htmlentities($_GET['q']?:'') ?>
                </h2>
            </div>
        </div>
    </section>

    <main>
        <section class="section">
            <div class="container">
                <div class="content">
                    <table class="table">
                        <thead>
                            <th width="30px"></th>
                            <th>Name</th>
                            <th>Last Modified</th>
                            <th>Size</th>
                        </thead>
                        <tbody>
                            <?php if($_GET['q'] && $_GET['q'] != '/' && $_GET['q'] != '.'): ?>
                            <tr>
                                <td><i class="fa fa-angle-up"></i></td>
                                <td colspan="3"><a href="<?= htmlentities(INDEX_NAME . '?q=' . 
                                    (dirname($_GET['q'])=="."?"":dirname($_GET['q']))) ?>"><i>Go Up</i></a></td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach($folders as $foldr): ?>
                                <?php if(is_dir($foldr)): ?>
                                    <tr>
                                        <td><i class="fa fa-folder"></i></td>
                                        <td><a href="<?= htmlentities(get_link($foldr)) ?>"><?= htmlentities($foldr) ?></a></td>
                                        <td><?= filemtime($foldr) ?></td>
                                        <td>-</td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td><i class="fa fa-file"></i></td>
                                        <td><a href="<?= htmlentities(get_link($foldr)) ?>"><?= htmlentities($foldr) ?></a></td>
                                        <td><?= filemtime($foldr) ?></td>
                                        <td><?= filesize($foldr)?:0 ?> bytes</td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="content">
                Indexed by <a href="https://github.com/chez14/capdex.php"><i class="fab fa-github"></i> Capdex.php</a>. Designed with <a href="https://bulma.io">Bulma CSS</a>.
            </div>
        </div>
    </footer>
</body>
</html>