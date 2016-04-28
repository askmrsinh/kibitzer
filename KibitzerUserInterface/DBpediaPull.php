<?php
  
  /* ARC2 static class inclusion */ 
  include_once("vendor/semsol/arc2/ARC2.php");
  
  /* configuration */ 
  $config = array(
    /* remote endpoint */
    'remote_store_endpoint' => 'http://dbpedia.org/sparql',
  );

  /* instantiation */
  $store = ARC2::getRemoteStore($config);

  $q = '
   PREFIX dbo: <http://dbpedia.org/ontology/>
   PREFIX dbp: <http://dbpedia.org/property/>
   PREFIX res: <http://dbpedia.org/resource/>
   PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
   PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
   SELECT ?label
   WHERE { <http://dbpedia.org/resource/Harry_Potter>
             dbo:abstract ?label . FILTER (lang(?label) = \'en\')}
  ';
  
  $rows = $store->query($q, 'rows');
  
  echo "<pre>";
  print_r(array_values($rows));
  echo "</pre?";
  
   $q = '
    PREFIX dbo: <http://dbpedia.org/ontology/>
    PREFIX dbp: <http://dbpedia.org/property/>
    PREFIX res: <http://dbpedia.org/resource/>
    PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    SELECT ?label
    WHERE { <http://dbpedia.org/resource/Harry_Potter>
              dbp:author ?label}
  ';
  
  $rows = $store->query($q, 'rows');
  
  echo "<pre>";
  print_r(array_values($rows));
  echo "</pre?";
  
    $q = '
      PREFIX dbo: <http://dbpedia.org/ontology/>
      PREFIX dbp: <http://dbpedia.org/property/>
      PREFIX res: <http://dbpedia.org/resource/>
      PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
      PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
      SELECT ?label
      WHERE { <http://dbpedia.org/resource/Harry_Potter>
                dbp:genre ?label}
    ';
    
    $rows = $store->query($q, 'rows');
    
    echo "<pre>";
    print_r(array_values($rows));
    echo "</pre?";
    
    $q = '
      PREFIX dbo: <http://dbpedia.org/ontology/>
      PREFIX dbp: <http://dbpedia.org/property/>
      PREFIX res: <http://dbpedia.org/resource/>
      PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
      PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
      SELECT ?label
      WHERE { <http://dbpedia.org/resource/Harry_Potter>
                dbo:thumbnail ?label}
    ';
    
    $rows = $store->query($q, 'rows');
    
    echo "<pre>";
    print_r(array_values($rows));
    echo "</pre>";
    echo '<img src="' . '"/>';
?>
  
