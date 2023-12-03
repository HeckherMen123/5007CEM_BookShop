<?php
    // required header
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/book_genre.php';
    
    // instantiate database and genre object
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize object
    $genre = new Genre($db);
    
    // query genres
    $stmt = $genre->read();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){
    
        // products array
        $genre_arr=array();
        $genre_arr["records"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
    
            $genre_item=array(
                "genreID" => $genreID,
                "genreName" => $genreName,
                "genreDesc" => html_entity_decode($genreDesc)
            );
    
            array_push($genre_arr["records"], $genre_item);
        }
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show genre data in json format
        echo json_encode($genre_arr);
    }
    
    else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no genre found
        echo json_encode(
            array("message" => "No genre found.")
        );
    }
?>