<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<?php
    
    $mainJson = file_get_contents("books.json");
    $users = json_decode($mainJson, true);
    $operator = $_GET['op'];
    if($operator== "Search")
    {
        $title  = $_GET['Title'];
        $author = $_GET['Author'];
        $isbn = $_GET['Isbn'];
        $flag = false;
        for($i = 0; $i < count($users); $i++){
            if((($users[$i]["title"] == $title && $users[$i]["author"] == $author)||$users[$i]["isbn"] == $isbn) && !$flag){
                echo "Available";
                $flag = true;
            }
        }
        if(!$flag){
            echo "Not available";
        }
    }
    else if($operator== "Save")
    {
		$array = array(
            'title' => $_GET['Title'],
            'author'=> $_GET['Author'],
            'available'=> $_GET['Available'],
            'pages'=> $_GET['Pages'],
            'isbn'=> $_GET['Isbn']
		);
        array_push($users,$array);
        $data_to_save = $users;
        if(!file_put_contents("books.json",json_encode($data_to_save,JSON_PRETTY_PRINT),LOCK_EX))
        {
            echo "ERROR to save";
        }
        else
        {
            echo "Successfully saved";
        }
    }
    else if($operator== "Delete")
    {
        $id=$_GET['Isbn'];
        $flag = false;
        for($i = 0; $i < count($users); $i++)
        {
            if($users[$i]["isbn"]==$id && !$flag)
            {
                unset($users[$i]);
                $flag=true;
            }
        }
        $data_to_delete = $users;
	    if(!file_put_contents("books.json",json_encode($data_to_delete,JSON_PRETTY_PRINT),LOCK_EX))
        {
            echo "ERROR to delete";
        }
        else if($flag==false)
        {
            echo "Not found";
        }
        else
        {
            echo "Successfully deleted";
        }
    }
    else if($operator== "Read")
    {
        ?>
        <table class="table table-bordered table-hover">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Available</th>
            <th>Pages</th>
            <th>Isbn</th>
        </tr>
        <?php
        if(count($users)!=0)
        {
            foreach($users as $info)
            {
                if($info['available']==1)
                    $info['available']="true";
                else
                    $info['available']="false";
               ?>
                <tr>
                <td><?php echo $info['title'] ?></td>
                <td><?php echo $info['author'] ?></td>
                <td><?php echo $info['available'] ?></td>
                <td><?php echo $info['pages'] ?></td>
                <td><?php echo $info['isbn'] ?></td>
                </tr>
                <?php
            }
        }
    }
?>
</html>