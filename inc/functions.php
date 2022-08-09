<?php

define( 'DB_NAME', 'C:/xampp/htdocs/CRUD/data/db.txt' );

// Set dummy Students data in db.txt
function seed() {
    $data = array(
        array(
            'id'    => 1,
            'fname' => 'Kamal',
            'lname' => 'Ahmed',
            'roll'  => '11',
        ),
        array(
            'id'    => 2,
            'fname' => 'Jamal',
            'lname' => 'Miah',
            'roll'  => '12',
        ),
        array(
            'id'    => 3,
            'fname' => 'Ripon',
            'lname' => 'Uddin',
            'roll'  => '9',
        ),
        array(
            'id'    => 4,
            'fname' => 'Sabrina',
            'lname' => 'Yeasmin',
            'roll'  => '15',
        ),
        array(
            'id'    => 5,
            'fname' => 'Shahin',
            'lname' => 'Ahmed',
            'roll'  => '18',
        ),
    );

    $serializedData = serialize( $data );
    file_put_contents( DB_NAME, $serializedData, LOCK_EX );
}

// Show All Student
function generateReport() {
    $serializedData = file_get_contents( DB_NAME );
    $students       = unserialize( $serializedData );
    ?>

    <table>
        <tr>
            <th>Name</th>
            <th width="25%">Roll</th>
            <th width="25%">Action</th>
        </tr>
        <?php

    foreach ( $students as $student ): ?>
        <tr>
            <td><?php printf( '%s %s', $student['fname'], $student['lname'] );?></td>
            <td><?php printf( '%s', $student['roll'] );?></td>
            <td><?php printf( '<a href="/CRUD/index.php?task=edit&id=%s">Edit</a> | <a class="delete" href="/CRUD/index.php?task=delete&id=%s">Delete</a>', $student['id'], $student['id'] );?></td>
        </tr>
    <?php endforeach;?>
    </table>
    <?php
}

// Add Student to db.txt and show
function addStudent( $fname, $lname, $roll ) {
    $found = false;

    $serializedData = file_get_contents( DB_NAME );
    $students       = unserialize( $serializedData );

    foreach ( $students as $_student ) {
        if ( $_student['roll'] == $roll ) {
            $found = true;
            break;
        }
    }

    if ( !$found ) {
        $newId   = getNewId( $students );
        $student = array(
            'id'    => $newId,
            'fname' => $fname,
            'lname' => $lname,
            'roll'  => $roll,
        );
        array_push( $students, $student );

        $serializedData = serialize( $students );
        file_put_contents( DB_NAME, $serializedData, LOCK_EX );
        return true;
    } else {
        return false;
    }

}

// Get student
function getStudent( $id ) {
    $serializedData = file_get_contents( DB_NAME );
    $students       = unserialize( $serializedData );

    foreach ( $students as $student ) {
        if ( $student['id'] == $id ) {
            return $student;
        }
    }
    return false;
}

function updateStudent( $id, $fname, $lname, $roll ) {
    $serializedData = file_get_contents( DB_NAME );
    $students       = unserialize( $serializedData );

    $found = false;
    // Check if any other student(ie. different id) have same roll number
    foreach ( $students as $_student ) {
        if ( $_student['roll'] == $roll && $_student['id'] != $id ) {
            $found = true;
            break;
        }
    }

    if ( !$found ) {
        $students[$id - 1]['fname'] = $fname;
        $students[$id - 1]['lname'] = $lname;
        $students[$id - 1]['roll']  = $roll;

        $serializedData = serialize( $students );
        file_put_contents( DB_NAME, $serializedData, LOCK_EX );

        return true;
    }

}

function deleteStudent( $id ) {
    $serializedData = file_get_contents( DB_NAME );
    $students       = unserialize( $serializedData );

    foreach($students as $offset=>$student) {
        if($student['id'] == $id) {
            unset($students[$offset]);
        }
    }

    $serializedData = serialize( $students );
    file_put_contents( DB_NAME, $serializedData, LOCK_EX );
}

function getNewId( $students ) {
    $maxId = max( array_column( $students, 'id' ) );
    return $maxId + 1;
}