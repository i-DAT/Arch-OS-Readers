<?php
  // This file contains 2 methods of parsing ARCH-OS feed using PHP.
  // The field names are:
  //					  description_link
  //					  source
  //					  timestamp
  //					  value
  
//-----------------------------------------------------------------------------------------------------------//

  // Method 1 uses the PHP DOM
  // DOMElement->getElementsByTagName() -- Gets elements by tagname
  // nodeValue : The value of this node, depending on its type.

  $objDOM = new DOMDocument();
  $objDOM->load("http://http://www.arch-os.com/livefeed/"); //make sure path is correct


  $sensors = $objDOM->getElementsByTagName("sensor");
  // for each sensor tag, parse the document and get values for
  // link, source, timestamp and value tag.

  foreach( $sensors as $sensor )
  {
    $links = $sensor->getElementsByTagName("description_link");
    $link  = $links->item(0)->nodeValue;

    $details = $sensor->getElementsByTagName("source");
    $detail  = $details->item(0)->nodeValue;

    $times = $sensor->getElementsByTagName("timestamp");
    $time  = $times->item(0)->nodeValue;

    $values = $sensor->getElementsByTagName("value");
    $value  = $values->item(0)->nodeValue;

    echo "$link :: $detail :: $time :: $value<br>";
  }

//-----------------------------------------------------------------------------------------------------------//

  // Method 2 uses SimpleXML in a class
  // fetchNodes($nodeName) -- Gets specific nodes according to node name
  // fetchNodesAsObjects() -- Gets all nodes as array of objects
  // countNodes($nodeName) -- Count nodes of type $nodeName
  //
  // NOTE: First returned value is always empty

class archos{
    private $xml;
    public function __construct($xmlFile="http://www.arch-os.com/livefeed/"){
        // read XML file
        if(!$this->xml=simplexml_load_file($xmlFile)){
            throw new Exception('Error reading XML file.');
        }
    }

    public function fetchNodes($nodeName){
        if(!$nodeName){
            throw new Exception('Invalid node name.');
        }
        $nodes=array();
        foreach($this->xml as $node){
            $nodes[]=$node->$nodeName;
        }
        return $nodes;
    }

    public function fetchNodesAsObjects(){
        $nodes=array();
        foreach($this->xml as $node){
            $nodes[]=$node;
        }
        return $nodes;
    }

    public function countNodes($nodeName){
        if(!$nodeName){
            throw new Exception('Invalid node name.');
        }
        $nodeCounter=0;
        foreach($this->xml as $node){
            $nodeCounter++;
        }
        return $nodeCounter;
    }
}



try{
	$archos=new archos();
    // fetch <source> nodes
    $nameNodes=$archos->fetchNodes('source');
    // display <source> nodes
    foreach($nameNodes as $name){
        echo 'source: '.$name.'<br />';
    }
    // fetch nodes as array of objects
    $nodes=$archos->fetchNodesAsObjects();
    // display nodes
	$i = 0;
    foreach($nodes as $node){
        echo $i.' description_link: '.$node->description_link.'source: '.$node->source.'timestamp: '.$node->timestamp.' value: '.$node->value.'<br />';
		$i++;
    }
    // display number of nodes
    echo 'Found '.$archos->countNodes('values').' values';
}
catch(Exception $e){
    echo $e->getMessage();
    exit();
}

?>