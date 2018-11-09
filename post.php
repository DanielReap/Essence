<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('functions.php');

$x = getdate();
$date = $x['mon'] . '/' . $x['mday'] . '/' . $x['year'];

try{
    if(isset($_GET['id'])){
        $connect = new PDO('mysql:host=localhost;dbname=essence', 'user', 'pass');
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $connect->prepare('SELECT * FROM posts WHERE id = :id');
        $statement->execute(
            array(
                'id' => $_GET['id']
            )
        );
        $data = $statement->fetchAll();
        if(empty($data)){
            header('Location: 404.php');
        }
        foreach($data as $post){
            $username = $post['username'];
            $content = $post['content'];
        }
        function userId($username){
            $connect = new PDO('mysql:host=localhost;dbname=essence', 'dan', 'nYZFjd55');
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $statement = $connect->prepare('SELECT * FROM users WHERE username = :username');
            $statement->execute(
                array(
                    'username' => $username
                )
            );
            $data = $statement->fetchAll();
            foreach ($data as $user){
                return $user['id'];
            }
        }
        $statement = $connect->prepare('SELECT * FROM comments WHERE postid = :id');
        $statement->execute(
            array(
            'id' => $_GET['id']
            )
        );
        $comments = $statement->fetchAll();
        if(isset($_POST['submit'])){
            $statement = $connect->prepare('INSERT INTO comments (id, postid, date, user, content, likes, dislikes) VALUES (:id, :postid, :date, :user, :content, 0, 0)');
            $statement->execute(
                array(
                    'id' => null,
                    'postid' => strip_tags($_GET['id']),
                    'date' => $date,
                    'user' => strip_tags($_SESSION['loggedin']['username']),
                    'content' => strip_tags($_POST['comment'])
                )
            );
            header("Refresh:0");
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
        <div class="container">
            <div class="col-6 col-md-12">
                    <div class="card">
                        <div class="card-header forum-submit-header">
                            <p>
                                <?php echo $post['title']; ?>
                            </p>
                        </div>
                        <?php if(count($comments) > 0){?>
                        <div id="grey-background" class="card-block segment">
                        <?php }else{?>
                        <div id="grey-background" class="card-block">
                        <?php }?>
                            <img src="http://s3.amazonaws.com/37assets/svn/765-default-avatar.png" class="com-profile-pic">
                            <?php echo '<a class="com-username" href="profile.php?id='.userId($username).'"><span>'.$username.'</span></a>';?>
                            <p class="com-description">
                                <?php echo $content; ?>
                            </p>
                            <?php if(isset($_SESSION['loggedin'])){ ?>
                            <button class="btn btn-like">Like</button>
                            <button class="btn btn-dislike">Dislike</button>
                            <?php }?>
                        </div>
                            <?php
                            foreach ($comments as $comment)
                            {
                                if((count($comments) > 1) and ($comment != end($comments))){
                            ?>
                        <div id="grey-background" class="card-block segment">
                            <?php
                                }else{
                                    ?>
                            <div id="grey-background" class="card-block">
                            <?php
                                }
                                    ?>
                            <img src="http://s3.amazonaws.com/37assets/svn/765-default-avatar.png" class="com-profile-pic">
                            <?php
                                echo '<a class="com-username" href="profile.php?id='. userId($comment['user']) .'"><span>'.$comment['user'].'</span></a>';
                                echo '<p class="com-description">'.$comment['content'].'</p>';
                                if(isset($_SESSION['loggedin'])){
                                    ?>
                            <button class="btn btn-like">Like</button>
                            <button class="btn btn-dislike">Dislike</button>
                                    <?php
                                }
                                echo '</div>';
                            }
                            ?>
                        <script>
                            function openForm() {
                                document.getElementById("comment-form").style.display = "block";
                                document.getElementById("new-comment").style.display = "none";
                            }
                        </script>
                        <div id="comment-form" class="card-block comment-form" style="display: none;">
                            <form method="POST">
                                <textarea class="col-12 form-control" name='comment' rows='2' placeholder="New comment..."></textarea>
                                <input type="submit" name="submit" value="Submit" class="btn btn-submit btn-primary">
                            </form>
                        </div>
                    </div>
                    <?php
                    if(isset($_SESSION['loggedin'])){
                        echo '<button id="new-comment" class="new-comment btn btn-submit btn-primary" type="submit" onclick="openForm()">New Comment</button>';
                    }
                    ?>
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