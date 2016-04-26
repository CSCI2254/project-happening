<?php
include("dbconn.php");
//INSERT INTO events (ID, NAME, GROUP, LOCATION, DESCRIPTION, ) 
//VALUES ($id, $eventName, $group, $location, $description, )

// Get RSS URL
$URL = $_POST['url'];
$pages = $_POST['per_page'];
$upcoming = $_POST['upcoming'];
// Verify Application
$options = array(
    "http" => array(
        'method' => "GET",
        'header' => "Accept-language: en\r\n"."User-Agent: CSCI2252/v1.0"."(http://cscilab.bc.edu/; contact.happening.bc@gmail.com)"
    )
);
try {
    $context = stream_context_create($options);
    $rssinfo = file_get_contents($URL."&per_page=".$pages."&upcoming=".$upcoming, false, $context);
    $rssobject = new SimpleXMLElement($rssinfo);
} catch (Exception $e) {
   echo 'Caught exception: ',  $e->getMessage(), "\n";
}
//$dbc = connect_to_db("events"); // Connect to DB
$items = $rssobject->channel->item; // One or the other will work
if (!$items) $items = $rssobject->item;
foreach ($items as $item) {
    $name = $item->title;
    $group = $item->children("event", true)->organizer[0];
    $location = $item->children("event", true)->location[0];
    $description = $item->description;
    $startdate = $item->children("event", true)->startdate[0];
    $enddate = $item->children("event", true)->enddate[0];
    $type = $item->children("event", true)->type[0];
    $mediaurl = $item->children("media", true)->content->attributes()["url"];
    $eventlink = $item->link;
    // 1. Query to see if event already exits in database
    // 2. Check if each variable exists, else set to NULL
    // 3. Insert Event into DB
    // $result = perform_query( $dbc, $query )
    //print_r ($item); DEBUG
}
// disconnect_from_db($dbc, $result);
?>