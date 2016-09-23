<?php

function create_tasks_table($conn)
{
    // sql to create table
    $sql = "CREATE TABLE IF NOT EXISTS tasks (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        user_id INT UNSIGNED NOT NULL, 
        task VARCHAR(256) NOT NULL,
        next_step VARCHAR(256),
        percent_completed TINYINT UNSIGNED,
        is_private BOOL,
        type ENUM('normal', 'repeat', 'asap'),
        duration DATETIME,
        start_date DATE,
        start_time TIME,
        finish_date DATE,
        finish_time TIME,
        repeat_interval DATETIME
    )";

    if ($conn->query($sql) === TRUE) {
        //echo "Table tasks created successfully<br>";
    } else {
        echo "Error creating table: $conn->error <br>";
    }
}

function tasks($conn,$lastUser)
{
    $id = filter_input(INPUT_POST, 'id');
    $user_id = filter_input(INPUT_POST, 'user_id');
    $task = filter_input(INPUT_POST, 'task');
    $next_step = filter_input(INPUT_POST, 'next_step');
    $percent_completed = filter_input(INPUT_POST, 'percent_completed');
    $is_private = filter_input(INPUT_POST, 'is_private');
    $type = filter_input(INPUT_POST, 'type');
    $duration = filter_input(INPUT_POST, 'duration');
    $start_date = filter_input(INPUT_POST, 'start_date');
    $start_time = filter_input(INPUT_POST, 'start_time');
    $finish_date = filter_input(INPUT_POST, 'finish_date');
    $finish_time = filter_input(INPUT_POST, 'finish_time');
    $repeat_interval = filter_input(INPUT_POST, 'repeat_interval');

    $sql = "SELECT id, user_id, task, next_step, percent_completed, is_private, type, duration, start_date, start_time, finish_date, finish_time, repeat_interval FROM tasks";
    $result = $conn->query($sql);

    echo '<input type="submit" name="input_task" value="Add new task"/><br>';
    echo '<br>';

    $taskRowIdx = filter_input(INPUT_POST, 'taskRowIdx');
    
    if($taskRowIdx != null)
    {
        echo '<input type="submit" name="delete_task" value="Delete selected task"/><br>';
        echo '<br>';
        echo '<input type="submit" name="update_task" value="Update selected task"/><br>';
        echo '<br>';
    }

    echo "<table>";

    if ($result->num_rows > 0) {
        echo "<tr> <th>ID</th> <th>User ID</th> <th>Task</th> <th>Next step</th> <th>Completed %</th> <th>is private</th> <th>Type</th> ".
                "<th>Duration</th> <th>Start</th> <th>Time</th> <th>Finish</th> <th>Time</th> <th>Repeat</th> </tr>";
        // output data of each row
        $count = 0;
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $user_id = $row["user_id"];
            $task = $row["task"];
            $next_step = $row["next_step"];
            $percent_completed = $row["percent_completed"];
            $is_private = $row["is_private"];
            $type = $row["type"];
            $duration = $row["duration"];
            $start_date = $row["start_date"];
            $start_time = $row["start_time"];
            $finish_date = $row["finish_date"];
            $finish_time = $row["finish_time"];
            $repeat_interval = $row["repeat_interval"];

            ++$count;
            $style = "";
            if($taskRowIdx==$count){
                $style = "style='background:red;'";
            }
            if($taskRowIdx==$count && filter_has_var(INPUT_POST, 'update_task')){
                echo "<tr> <td>$id</td>".
                "<td> <input type='text' name='user_id' value='$user_id'> </td>".
                "<td> <input type='text' name='task' value='$task'> </td>".
                "<td> <input type='text' name='next_step' value='$next_step'> </td>".
                "<td> <input type='text' name='percent_completed' value='$percent_completed'> </td>".
                "<td> <input type='checkbox' name='is_private' value='$is_private'> </td>".
                //"<td> <input type='text' name='type' value='$type'> </td>".
                "<td> <select name='type'>".
                "<option value='normal'>Normal</option>".
                "<option value='repeat'>Repeat</option>".
                "<option value='asap'>ASAP</option>".
                "</select> </td>".
                "<td> <input type='datetime-local' name='duration' value='$duration'> </td>".
                "<td> <input type='date' name='start_date' value='$start_date'> </td>".
                "<td> <input type='time' name='start_time' value='$start_time'> </td>".
                "<td> <input type='date' name='finish_date' value='$finish_date'> </td>".
                "<td> <input type='time' name='finish_time' value='$finish_time'> </td>".
                "<td> <input type='datetime-local' name='repeat_interval' value='$repeat_interval'> </td> </tr>";
            } else {
                echo "<tr onclick='RowClick(\"taskRowIdx\", this);' $style> <td>$id</td>".
                "<td> $user_id </td>".
                "<td> $task </td>".
                "<td> $next_step </td>".
                "<td> $percent_completed </td>".
                "<td> $is_private </td>".
                "<td> $type </td>".
                "<td> $duration </td>".
                "<td> $start_date </td>".
                "<td> $start_time </td>".
                "<td> $finish_date </td>".
                "<td> $finish_time </td>".
                "<td> $repeat_interval </td> </tr>";
            }
        }
    } else {
        echo "0 results<br>";
    }

    if(filter_has_var(INPUT_POST, 'input_task'))
    {
        echo "<tr> <td>$id</td>".
                "<td> <input type='text' name='user_id' value='$user_id'> </td>".
                "<td> <input type='text' name='task' value='$task'> </td>".
                "<td> <input type='text' name='next_step' value='$next_step'> </td>".
                "<td> <input type='text' name='percent_completed' value='$percent_completed'> </td>".
                "<td> <input type='checkbox' name='is_private' value='$is_private'> </td>".
                //"<td> <input type='text' name='type' value='$type'> </td>".
                "<td> <select name='type'>".
                "<option value='normal'>Normal</option>".
                "<option value='repeat'>Repeat</option>".
                "<option value='asap'>ASAP</option>".
                "</select> </td>".
                "<td> <input type='datetime-local' name='duration' value='$duration'> </td>".
                "<td> <input type='date' name='start_date' value='$start_date'> </td>".
                "<td> <input type='time' name='start_time' value='$start_time'> </td>".
                "<td> <input type='date' name='finish_date' value='$finish_date'> </td>".
                "<td> <input type='time' name='finish_time' value='$finish_time'> </td>".
                "<td> <input type='datetime-local' name='repeat_interval' value='$repeat_interval'> </td> </tr>";
    }

    echo "</table>";

    if(filter_has_var(INPUT_POST, 'input_task'))
    {
        echo '<br>';
        echo '<input type="submit" name="insert_task" value="Submit new task"/><br>';
        echo '<br>';
    }

    if(filter_has_var(INPUT_POST, 'update_task'))
    {
        echo '<br>';
        echo '<input type="submit" name="save_task" value="Save changes"/><br>';
        echo '<br>';
    }

    $lastTask = -1;

    if(filter_has_var(INPUT_POST, 'insert_task'))
    {    
        $sql = "INSERT INTO tasks (user_id, task, next_step, percent_completed, is_private, type, duration, start_date, start_time, finish_date, finish_time, repeat_interval) ".
                "VALUES ('$lastUser', '$task', '$next_step', '$percent_completed', '$is_private', '$type', '$duration', '$start_date', '$start_time', '$finish_date', '$finish_time', '$repeat_interval')";

        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
            echo "New record created successfully. Last inserted ID is: $last_id <br>";
            $lastTask = $last_id;

            postRedirect();
        } else {
            echo "Error: $sql <br> $conn->error <br>";
        }
    }

    if(filter_has_var(INPUT_POST, 'save_task'))
    {
        $sql = "UPDATE tasks SET user_id='$user_id', task='$task', next_step='$next_step', percent_completed='$percent_completed', ".
                "is_private='$is_private', type='$type', duration='$duration', start_date='$start_date', ".
                "start_time='$start_time', finish_date='$finish_date', finish_time='$finish_time', repeat_interval='$repeat_interval' ".
                "WHERE id=$taskRowIdx";

        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    if(filter_has_var(INPUT_POST, 'delete_task'))
    {
        // sql to delete a record
        $sql = "DELETE FROM tasks WHERE id=$taskRowIdx";

        if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully";

            postRedirect();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }

    echo "<input id='taskRowIdx' type='hidden' name='taskRowIdx' value='$taskRowIdx'>";
    
    return $lastTask;
}