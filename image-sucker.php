<?php
        error_reporting(0);
        // *** Include the class
        include("resize-class.php");

        set_time_limit(360);

        $api_key = "501e9de949f3e5b3090fab7f9a0eb48b";   
        $perPage = 50;
    
        $url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search';
        $url.= '&api_key='.$api_key;
        $url.= '&license=4,5';
        $url.= '&sort=interestingness-desc';
        $url.= '&content_type=1';
        $url.= '&media=photos';
        $url.= '&per_page='.$perPage;
        $url.= '&format=json';
        $url.= '&nojsoncallback=1';    
    
        $response = json_decode(file_get_contents($url));
        $photo_array = $response->photos->photo;

        if (count($photo_array) > 0) {
            
            $files = glob('store/*'); // get all file names
            foreach($files as $file){ // iterate files
              if(is_file($file)) {
                unlink($file); // delete file
              }
            }
            $files = glob('thumbs/*'); // get all file names
            foreach($files as $file){ // iterate files
              if(is_file($file)) {
                unlink($file); // delete file
              }
            }         

            $fp = fopen('store/images.json', 'w');
            fwrite($fp, json_encode($photo_array));
            fclose($fp);
            
            $owners_str = '[';

            foreach($photo_array as $single_photo){
    
                $farm_id = $single_photo->farm;
                $server_id = $single_photo->server;
                $photo_id = $single_photo->id;
                $secret_id = $single_photo->secret;
                $size = 'b';
    
                $title = $single_photo->title;  

                $url = 'https://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key='.$api_key.'&photo_id='.$photo_id.'&format=json&nojsoncallback=1';
                $response = json_decode(file_get_contents($url));
                $license = $response->photo->license;


                $photo_url = 'http://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';
                $store_url = 'store/'.$license.'_'.$photo_id.'_'.$secret_id.'.jpg';

                copy($photo_url, $store_url);
                
                $i = new resize($store_url);
                $i -> resizeImage(140, 140);
                imagepng($i->imageResized, 'thumbs/'.$photo_id.'_'.$secret_id.'_thumb.png');

                $url = 'https://api.flickr.com/services/rest/?method=flickr.people.getInfo&api_key='.$api_key.'&user_id='.$single_photo->owner.'&format=json&nojsoncallback=1';
                $response = json_decode(file_get_contents($url));
                $owners_str .= json_encode($response->person).',';
           
            }

            $owners_str = substr($owners_str, 0, -1).']';

            $fp = fopen('store/owners.json', 'w');
            fwrite($fp, $owners_str);
            fclose($fp);
        }
?>