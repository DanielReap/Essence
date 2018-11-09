<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$message = null;

try
{
    if(isset($_GET['id'])){
        $connect = new PDO('mysql:host=localhost;dbname=essence', 'user', 'password');
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = 'SELECT * FROM users WHERE id = :id';
        $statement = $connect->prepare($query);
        $statement->execute(
        array(
            'id' => $_GET['id'],
            )
        );
        $data = $statement->fetchAll();
        if(empty($data)){
            header('Location: 404.php');
        }
        foreach ($data as $row){
            $username = $row['username'];
            $about = $row['about'];
        }
    }
    else
    {
        header('Location: 404.php');
    }
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
        <meta name="description" content="">
        <meta name="author" content="Daniel Reapsome">
        <link rel="icon" href="icon.png">
        <title>
            Essence | Profile
        </title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/signup.css" rel="stylesheet">
        <link href="css/essence.css" rel="stylesheet">
        <script src="js/"></script>
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
            <div class="profile-header col-6 col-md-12 col-centered">
                <img src="http://s3.amazonaws.com/37assets/svn/765-default-avatar.png" class="profile-pic">
                <span class="profile-usrname"><?php echo $username;?></span>
                <?php if(isset($_SESSION['loggedin'])){if($_GET['id'] == $_SESSION['loggedin']['id']){echo '<a href="edit.php" class="edit"><button type="button" id="edit" class="btn btn-outline-primary profile-btn">Edit</button></a>';}}?>
            </div>
            <div class="card about col-6 col-md-12 col-centered">
                <nav class="navbar navbar-light bg-faded profile-about">
                    <span class="navbar-text">
                        About
                    </span>
                </nav>
                <div class="card-block">
                    <p><?php echo $about;?></p>
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