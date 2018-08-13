<?php
    require_once 'vendor/autoload.php';

    use Elasticsearch\ClientBuilder;

    $client = ClientBuilder::create()->build();

    /*$es = new Elasticsearch\Client([
        'hosts'=>['127.0.0.1:9200']
    ])*/
?>