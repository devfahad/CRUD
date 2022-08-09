<?php

require_once "inc/functions.php";
$info  = '';
$task  = $_GET['task'] ?? 'report';
$error = $_GET['error'] ?? '0';

if ( 'delete' == $task ) {
    $id = filter_input( INPUT_GET, 'id' );
    if ( $id > 0 ) {
        deleteStudent( $id );
        header( 'location: /CRUD/index.php?task=report' );
    }
}

if ( 'seed' == $task ) {
    seed();
    $info = "Seeding is complete!";
}

$fname = '';
$lname = '';
$roll  = '';

if ( isset( $_POST['submit'] ) ) {
    $fname = filter_input( INPUT_POST, 'fname' );
    $lname = filter_input( INPUT_POST, 'lname' );
    $roll  = filter_input( INPUT_POST, 'roll' );
    $id    = filter_input( INPUT_POST, 'id' );

    if ( $id ) {
        // Update the existing student
        if ( $fname != '' && $lname != '' && $roll != '' ) {
            $result = updateStudent( $id, $fname, $lname, $roll );
            if ( $result ) {
                header( 'location: /CRUD/index.php?task=report' );
            } else {
                $error = 1;
            }
        }
    } else {
        // Add a new student
        if ( $fname != '' && $lname != '' && $roll != '' ) {
            $result = addStudent( $fname, $lname, $roll );

            if ( $result ) {
                header( 'location: /CRUD/index.php?task=report' );
            } else {
                $error = 1;
            }
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Project</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,700;1,300;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/necolas/normalize.css@master/normalize.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/milligram@1.4.1/dist/milligram.min.css">
    <style>
        body { margin-top: 30px; }
    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h2>CRUD Project using PHP</h2>
                <p>A simple project to perform CRUD operations using plain files and PHP</p>
                <?php include_once "inc/templates/nav.php";?>

                <hr />

                <?php if ( $info != '' ): ?>
                    <?="<p>{$info}</p>";?>
                <?php endif;?>

            </div>
        </div>

        <?php if ( '1' == $error ): ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <blockquote>Duplicate Roll Number found!</blockquote>
            </div>
        </div>
        <?php endif;?>

        <?php if ( 'report' == $task ): ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <?php generateReport();?>
            </div>
        </div>
        <?php endif;?>

        <?php if ( 'add' == $task ): ?>
            <div class="row">
                <div class="column column-60 column-offset-20">
                    <form action="/CRUD/index.php?task=add" method="POST">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>">
                        <label for="roll">Roll</label>
                        <input type="number" name="roll" id="roll" value="<?php echo $roll; ?>">
                        <button type="submit" class="button-primary" name="submit">Save</button>
                    </form>
                </div>
            </div>
        <?php endif;?>

        <?php

if ( 'edit' == $task ):
    $id      = filter_input( INPUT_GET, 'id' );
    $student = getStudent( $id );
    if ( $student ):
    ?>
	        <div class="row">
	            <div class="column column-60 column-offset-20">
	                <form action="/CRUD/index.php?task=edit&id=<?php echo $id; ?>" method="POST">
	                    <input type="hidden" name="id" value="<?php echo $id; ?>">
	                    <label for="fname">First Name</label>
	                    <input type="text" name="fname" id="fname" value="<?php echo $student['fname']; ?>">
	                    <label for="lname">Last Name</label>
	                    <input type="text" name="lname" id="lname" value="<?php echo $student['lname']; ?>">
	                    <label for="roll">Roll</label>
	                    <input type="number" name="roll" id="roll" value="<?php echo $student['roll']; ?>">
	                    <button type="submit" class="button-primary" name="submit">Update</button>
	                </form>
	            </div>
	        </div>
	    <?php endif;
endif;
?>

    </div>

<script type="text/javascript" src="./assets/js/script.js"></script>

</body>
</html>
