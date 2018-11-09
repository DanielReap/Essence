<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

try
{
    $connect = new PDO('mysql:host=localhost;dbname=essence', 'user', 'pass');
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $statement = $connect->query('SELECT * FROM forums');
    $forums = $statement->fetchAll();
    $statement = $connect->query('SELECT * FROM posts ORDER BY id DESC');
    $posts = $statement->fetchAll();
}
catch(PDOException $error)
{
    $message = $error->getMessage();
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="A Forum created for programmers and gamers to collab and create together.">
        <meta name="author" content="Daniel Reapsome">
        <link rel="icon" href="icon.png">
        
        <title>Essence | Home</title>
        
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/essence.css" rel="stylesheet">
    </head>
    
    <body>
        
        <nav class="navbar navbar-toggleable-md fixed-top navbar-inverse bg-inverse">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <a class="navbar-brand raleway color-white" href="/">Essence</a>
            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
<?php
if(isset($_SESSION['loggedin'])){
?>
                    <li class='nav-item'>
                        <a class='nav-link' href='index.php'>
                            Home
                            <span class='sr-only'>
                                (current)
                            </span>
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='forums.php'>
                            Forums
                        </a>
                    </li>
                    <li class='nav-item'>
<?php
    echo "<a class='nav-link' href='profile.php?id=". $_SESSION['loggedin']['id'] ."'>";
?>
                            Profile
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='logout.php'>
                            Logout
                        </a>
                    </li>
<?php
}else{
?>
                    <li class='nav-item'>
                        <a class='nav-link' href='index.php'>
                            Home
                            <span class='sr-only'>
                                (current)
                            </span>
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='forums.php'>
                            Forums
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='login.php'>
                            Login
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a class='nav-link' href='register.php'>
                            Register
                        </a>
                    </li>
<?php
}
?>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search">
                    <button class="btn btn-grey my-2 my-sm-0 " type="submit">
                        Search
                    </button>
                </form>
            </div>
        </nav>
        
        <div class="container">
            <div class="row row-offcanvas row-offcanvas-right">
                <div class="col-12 col-md-9">
                    <p class="float-right hidden-md-up">
                        <button type="button" class="btn btn-most-recent btn-sm" data-toggle="offcanvas">
                            Most Recent
                        </button>
                    </p>
                    <div class="jumbotron">
                        <h1>
                            Welcome to Essence
                        </h1>
                        <p>
                            Essence is a forum designed to help programmers, gamers, and artists to come together and collaborate in order to create a friendly and welcoming community!
                        </p>
                    </div>
                </div>
                
                <div class="col-6 col-md-3 sidebar-offcanvas" id="sidebar">
                    <div class="list-group">
                        <a href="#" class="list-group-item active">
                            Most Recent
                        </a>
                        <a href="post.php?id=<?php echo current($posts)['id']; ?>" class="list-group-item">
                            <?php
                                echo current($posts)['title'];
                            ?>
                        </a>
                        <a href="post.php?id=<?php echo next($posts)['id']; ?>" class="list-group-item">
                            <?php
                                echo current($posts)['title'];
                            ?>
                        </a>
                        <a href="post.php?id=<?php echo next($posts)['id']; ?>" class="list-group-item">
                            <?php
                                echo current($posts)['title'];
                            ?>
                        </a>
                        <a href="post.php?id=<?php echo next($posts)['id']; ?>" class="list-group-item">
                            <?php
                                echo current($posts)['title'];
                            ?>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-9">
                    <div class="list-group forums">
                        <a href="forums.php" class="list-group-item active">
                            Forums
                        </a>
                        <?php
                        foreach ($forums as $forum){    
                            echo '<a href="forum.php?id='. $forum['id'] .'" class="list-group-item forum-item">'. $forum['title'] .'</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <footer>
                <hr>
                <p>
                    &copy; Essence Network 2017
                </p>
            </footer>
        </div>
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous">
        </script>
        <script>
            window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous">
        </script>
        <script src="js/bootstrap.min.js">
        </script>
        <script src="js/ie10-viewport-bug-workaround.js">
        </script>
        <script src="js/essence.js">
        </script>
    </body>
</html>