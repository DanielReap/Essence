<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$x = getdate();
$date = $x['mon'] . '/' . $x['mday'] . '/' . $x['year'];

if(isset($_SESSION['loggedin']) == False){
    header('Location: 404.php');
}

try
{
    if(empty($_GET['id'])){
        header('Location: 404.php');
    }
    $connect = new PDO('mysql:host=localhost;dbname=essence', 'user', 'password');
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = 'SELECT * FROM forums WHERE id = :id';
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
    if(isset($_POST['submit']))
    {
        $statement = $connect->prepare('INSERT INTO posts (id, date, section, title, username, likes, dislikes, content) VALUES (:id, :date, :sectionid, :title, :username, 0, 0, :content)');
        $statement->execute(
            array(
                'id' => null,
                'date' => $date,
                'sectionid' => strip_tags($_GET['id']),
                'title' => strip_tags($_POST['title']),
                'username' => strip_tags($_SESSION['loggedin']['username']),
                'content' => strip_tags($_POST['content'])
            )
        );
        $statement = $connect->query('SELECT * FROM posts WHERE id = (SELECT MAX(id) FROM posts)');
        $data = $statement->fetchAll();
        foreach ($data as $id){
            $id = $id['id'];
        }
        header('Location: post.php?id=' . $id);
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
            Essence | Forums
        </title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/signup.css" rel="stylesheet">
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
        <?php if(isset($message)){echo $message;}?>
        <div class="container">
            <div class="col-6 col-md-12">
                <form method="POST">
                    <div class="card">
                        <div class="card-header forum-submit-header">
                            <p>Submit Post</p>
                        </div>
                        <div id="grey-background" class="card-block">
                            <input type="input" id="forum-title" name='title' class="col-6 col-md-3 form-control" placeholder="Title" required>
                            <textarea id="forum-description" name='content' class="form-control" rows="6" placeholder="Post description..." required></textarea>
                            <button type="submit" name='submit' class="btn btn-submit btn-primary right">Submit</button>
                            <button type="upload" class="btn btn-submit btn-primary left">Upload</button>
                        </div>
                    </div>
                </form>
                <footer>
                <hr>
                <p>
                    &copy; Essence Network 2017
                </p>
            </footer>
            </div>
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