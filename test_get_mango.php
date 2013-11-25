<?php
ini_set('display_error', 'On');
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        try {
            $intShow = 5;
            $connMaongo = new MongoClient();

            $db = $connMaongo->fandf;
            $collection = $db->backup_from_amazon_server;

//            
//            $document = $collection->findOne();
//            var_dump($document);

            $cursor = $collection->find();
            $i = 1;
            foreach ($cursor as $document) {
                if ($i > $intShow)
                    break;
                echo '<br><br>Main Array================================================<br>';
                foreach ($document as $key => $value) {
                    echo $key, '====>', $value, '<br>';
                }

                echo '<br><br>Data Array================================================<br>';
                foreach ($document['data'] as $key => $value) {
                    echo $key, '====>', $value, '<br>';
                }

                echo '<br><br>Entities Array================================================<br>';
                foreach ($document['entities'] as $key => $value) {
                    echo $key, '====>', $value, '<br>';
                }

                echo '<br>Metadata Array================================================<br>';
                foreach ($document['data']['metadata'] as $key => $value) {
                    echo $key, '====>', $value, '<br>';
                }
                $i++;
            }


//            
//            var_dump($document['data']);
            echo '<br><br>Collection Count: ', $collection->count();
//            $list = $db -> listCollections();
//            foreach ($list as $value) {
//                echo $value;
//            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
        ?>
    </body>
</html>
