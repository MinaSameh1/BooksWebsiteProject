<?php

include_once('database.php');

$sql = 'SELECT * FROM users ';
foreach ($pdo->query($sql) as $row) { ?>
<tr>
    <td><?php echo $row['userID']; ?></td>
    <td><?php echo $row['userName']; ?></td>
    <td><?php echo $row['Name']; ?></td>
    <td><?php echo $row['phoneNumber']; ?></td>
    <td><?php echo $row['age']; ?></td>
    <td><?php echo $row['DOB']; ?></td>
    <td><?php echo ($row['isAdmin']==0 ) ? "User" : "Admin" ?></td> 
    <td><?php echo $row['Email']; ?></td>
    <td><?php echo $row['Address']; ?></td>
    <td><?php echo $row['UserBlocked']; ?></td>
</tr>
<?php
}
?>