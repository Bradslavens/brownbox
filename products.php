<?php
    // block access by outside servers
    if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) 
    {
        header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

        /* choose the appropriate page to redirect users */
        die( header( 'location: /error.php' ) );
    }
    require __DIR__ . '/vendor/autoload.php';
    
    $dotenv = Dotenv\Dotenv::create(__DIR__);
    $dotenv->load();
    
    $product = $_GET["product"];
    
    $product = filter_var($product, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW );
    
    // connect to database
    $db = getenv("DB");
    $db_host = getenv("DB_HOST");
    $db_name = "products";
    $user = getenv("DB_USER");
    $pass = getenv("DB_PASS");
    
    try {
        $dbh = new PDO($db.':host='.$db_host .';dbname='. $db_name, $user, $pass);
    } catch (PDOException $e) {
        print "error " . $e->getMessage(). "<br>";
        die();
    }
    
    $statement = $dbh->prepare("SELECT * FROM products WHERE name=?");
    
    if($statement->execute(array($product))) {
            $results = $statement->fetchAll();
        }
        else {
            die();
        }
    
    var_dump($results);
    $dbh = null;
    $statement = null;