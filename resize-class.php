<?php
        //error_reporting(0);
    
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', '1');    
    
        $api_key = "501e9de949f3e5b3090fab7f9a0eb48b"; 
        $url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key='.$api_key;
    
        if ($_SERVER['QUERY_STRING'] != NULL) {
    
            $logfile = "hits.txt";
            // Open log file for reading and writing
            if ($fp = @fopen($logfile, 'r+'))
            {
                // Lock log file from other scripts
                $locked = flock($fp, LOCK_EX);
    
                // Lock successful?
                if ($locked)
                {
                    // Let's read current count
                    $count = intval( trim( fread($fp, filesize($logfile) ) ) );
    
                    // Update count by 1 and write the new value to the log file
                    $count = $count + 1;
                    rewind($fp);
                    fwrite($fp, $count);    
                }
                else
                {
                    // Lock not successful. Better to ignore than to damage the log file
                    $count = 1;
                }
    
                // Release file lock and close file handle
                flock($fp, LOCK_UN);
                fclose($fp);
            }
    
    
            $qs = $_SERVER['QUERY_STRING'];    
    
            parse_str($qs, $d);    
    
            $q = $d[q];
            $w = $d[w];
            $h = $d[h];
            $c = $d[c];
            $u = $d[u];

            /* the referring url */
            $r = $d[r];


            /* ad unit */
            $a = $d[a];
    
            /* do analytics   
            $str = '/';
            if (!empty($q)) {
                $str .= $q.'/';
            }
    
            if (!empty($w) && !empty($h) && !empty($c)) {
                $str .= $w.'/'.$h.'/'.$c;
            }
            else if (!empty($w) && !empty($c)) {
                $str .= $w . '/' . $w . '/' . $c; 
            }
            else if (!empty($w)) {
                $str .= $w . '/' . $w; 
            }
            else if (!empty($a)) {
                $str .= $a;
            } */
    
            $aTitle = 'Image request';
            if (!empty($a)) {
                $aTitle = 'Ad request';
            }
    
            $data = array(
                'title' => $aTitle,
                'slug' => $r
            );
            gaBuildHit( 'pageview', $data);
            /* analytics done */
    
    
            $image_url;
            $license;
            $id;

            /* check for existing image */
            $fromCache = glob('cache/' . $r . '.{jpg,jpeg,png,gif}', GLOB_BRACE);            

            if (!empty($fromCache)) {
                $cachedImage = $fromCache[0];
                $im = imagecreatefrompng($cachedImage);
                header('Content-Type: image/png');
                imagepng($im);
                imagedestroy($im);
                die();
            }

    
            if (!empty($a)) {
    
                $ad_url = 'ads/'.$a.'.png';
    
                if (!empty($ad_url)) {
                    $im = imagecreatefrompng($ad_url);
                    header('Content-Type: image/png');
                    imagepng($im);
                    imagedestroy($im);
                    die();
                }
    
            } 
    
            else if (!empty($u)) {
                $peopleUrl = 'https://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key='.$api_key.'&username='.urlencode($u).'&format=json&nojsoncallback=1';
                $response = json_decode(file_get_contents($peopleUrl), true);
                $user = $response['user']['id'];
                
                $photosUrl =  'https://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key='.$api_key.'&user_id='.urlencode($user).'&format=json&nojsoncallback=1';
                $response = json_decode(file_get_contents($photosUrl), true);

                $i = rand(1,100);
                $photo = $response['photos']['photo'][$i];

                $image_url = 'http://farm'.$photo['farm'].'.staticflickr.com/'.$photo['server'].'/'.$photo['id'].'_'.$photo['secret'].'_b.jpg';

                if (!empty($image_url)) {
                    $i = new resize($image_url, $r);
                    $i -> resizeImage($w, $h);
                    $i -> sendImage($c, 'p-hold.com/c/'.$photo['id']);
                }
            }
    
            else {  
    
                if (strlen($c) == 3) {
                    $c.=$c;
                }
    
                if ($q == NULL) {  
                    $local_images = glob('store/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                    $image_url = $local_images[array_rand($local_images)];
                    $license = GetBetween('/','_',$image_url);
                    $id = GetBetween('_','_',$image_url);
                } 
                else {    
                    $per_page = 10;
                    $url.= '&license=4,5&tags='.$q.'&sort=interestingness-desc&content_type=1&media=photos&per_page='.$per_page.'&format=json&nojsoncallback=1';    
    
                    $response = json_decode(file_get_contents($url));
                    $photos = $response->photos->photo;
                    if (!empty($photos)) {
                        $photo = $photos[array_rand($photos)];
                        $id = $photo->id;
    
                        $url = 'https://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key='.$api_key.'&photo_id='.$id.'&format=json&nojsoncallback=1';
                        $response = json_decode(file_get_contents($url));
                        $license = $response->photo->license;
    
                        $image_url = 'http://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$id.'_'.$photo->secret.'_b.jpg';
                    } 
                    else {
                        $local_images = glob('store/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                        $image_url = $local_images[array_rand($local_images)];
                        $license = GetBetween('/','_',$image_url);
                        $id = GetBetween('_','_',$image_url);
                    }
                }
    
    
                if ($license == '4') {
                    $license = 'CC BY p-hold.com/c/'.$id;
                } else if ($license == '5') {
                    $license = 'CC BY-SA p-hold.com/c/'.$id;
                }
            }
    
            if (!empty($image_url)) {
                $i = new resize($image_url, $r);
                $i -> resizeImage($w, $h);
                $i -> sendImage($c, $license);
            }
        }
    
        function GetBetween($var1="",$var2="",$pool){
            $temp1 = strpos($pool,$var1)+strlen($var1);
            $result = substr($pool,$temp1,strlen($pool));
            $dd=strpos($result,$var2);
            if($dd == 0){
                $dd = strlen($result);
            }
            return substr($result,0,$dd);
        }
    
        // Handle the parsing of the _ga cookie or setting it to a unique identifier
        function gaParseCookie() {
          if (isset($_COOKIE['_ga'])) {
            list($version,$domainDepth, $cid1, $cid2) = split('[\.]', $_COOKIE["_ga"],4);
            $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1.'.'.$cid2);
            $cid = $contents['cid'];
          }
          else $cid = gaGenUUID();
          return $cid;
        }
    
        // Generate UUID v4 function - needed to generate a CID when one isn't available
        function gaGenUUID() {
          return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
    
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
    
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
          );
        }
    
    
        function gaBuildHit( $method = null, $info = null ) {
          if ( $method && $info) {
    
              // Standard params
              $v = 1;
              $tid = "UA-50288062-1"; // Put your own Analytics ID in here
              $cid = gaParseCookie();
    
              // Register a PAGEVIEW
              if ($method === 'pageview') {
    
                // Send PageView hit
                $data = array(
                  'v' => $v,
                  'tid' => $tid,
                  'cid' => $cid,
                  't' => 'pageview',
                  'dt' => $info['title'],
                  'dp' => $info['slug']
                );
    
                gaFireHit($data);
    
              } // end pageview method
          }
        }
    
        // See https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
        function gaFireHit( $data = null ) {
          if ( $data ) {
            $getString = 'https://ssl.google-analytics.com/collect';
            $getString .= '?payload_data&';
            $getString .= http_build_query($data);
            $result = file_get_contents( $getString );
    
            #$sendlog = error_log($getString, 1, "ME@EMAIL.COM"); // comment this in and change your email to get an log sent to your email
    
            return $result;
          }
          return false;
        }
    
    
           # ========================================================================#
           #
           #  Author:    Jarrod Oberto
           #  Version:	 1.0
           #  Date:      17-Jan-10
           #  Purpose:   Resizes and saves image
           #  Requires : Requires PHP5, GD library.
           #  Usage Example:
           #                     include("classes/resize_class.php");
           #                     $resizeObj = new resize('images/cars/large/input.jpg');
           #                     $resizeObj -> resizeImage(150, 100, 0);
           #                     $resizeObj -> saveImage('images/cars/large/output.jpg', 100);
           #
           #
           # ========================================================================#
    
                Class resize
                {
                    // *** Class variables
                    private $image;
                    private $width;
                    private $height;
                    private $cachePath;
                    public $imageResized;
                    function __construct($fileName, $f)
                    {
                        // *** Open up the file
                        $this->image = $this->openImage($fileName);
                        // *** Get width and height
                        $this->width  = imagesx($this->image);
                        $this->height = imagesy($this->image);
                        $this->cachePath = $f;
                    }
                    ## --------------------------------------------------------
    
                    private function openImage($file)
                    {
                        // *** Get extension
                        $extension = strtolower(strrchr($file, '.'));
                        switch($extension)
                        {
                            case '.jpg':
                            case '.jpeg':
                                $img = @imagecreatefromjpeg($file);
                                break;
                            case '.gif':
                                $img = @imagecreatefromgif($file);
                                break;
                            case '.png':
                                $img = @imagecreatefrompng($file);
                                break;
                            default:
                                $img = false;
                                break;
                        }
                        return $img;
                    }
                    ## --------------------------------------------------------
                    public function resizeImage($newWidth, $newHeight)
                    {
                        // *** Get optimal width and height - based on $option
                        $optionArray = $this->getDimensions($newWidth, $newHeight, $option);
                        $optimalWidth  = $optionArray['optimalWidth'];
                        $optimalHeight = $optionArray['optimalHeight'];
    
                        // *** Resample - create image canvas of x, y size
                        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
                        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
                        $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
    
                    }
                    ## --------------------------------------------------------
    
                    private function getDimensions($newWidth, $newHeight, $option)
                    {
                        $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                        $optimalWidth = $optionArray['optimalWidth'];
                        $optimalHeight = $optionArray['optimalHeight'];
                        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
                    }
                    ## --------------------------------------------------------
    
                    private function getOptimalCrop($newWidth, $newHeight)
                    {
                        $heightRatio = $this->height / $newHeight;
                        $widthRatio  = $this->width /  $newWidth;
                        if ($heightRatio < $widthRatio) {
                            $optimalRatio = $heightRatio;
                        } else {
                            $optimalRatio = $widthRatio;
                        }
                        $optimalHeight = $this->height / $optimalRatio;
                        $optimalWidth  = $this->width  / $optimalRatio;
                        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
                    }
                    ## --------------------------------------------------------
                    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
                    {
                        // *** Find center - this will be used for the crop
                        $cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
                        $cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );
                        $crop = $this->imageResized;
                        // *** Now crop from center to exact requested size
                        $this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
                        imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
                    }
    
                    ## --------------------------------------------------------
                    public function sendImage($c = NULL, $license = NULL)
                    {

                        if (imagetypes() && IMG_PNG && $license != NULL && $license != 'ad') {
    
                            $im = $this->imageResized; 
    
                            $black = imagecolorallocate($im, 50, 50, 50);
                            $grey = imagecolorallocate($im, 220, 220, 220);
    
                            $font = 'arial.ttf';
    
                            $h = imagesy($im);
                            $w = imagesx($im);
    
                            $bbox = imagettfbbox(7, 0, $font, $license);
    
                            imagefilledrectangle($im, $w - $bbox[2] - 5, $h - 11, $w, $h, $black);
    
                            $x = $w - $bbox[2] - 4;
                            $y = $h - 2;
    
                            imagettftext($im, 7, 0, $x, $y, $grey, $font, $license);
    
                            if($c != NULL && $im)
                            {
                                if (strlen($c) == 6) {
                                    list($r,$g,$b) = str_split($c,2);    
                                    if (imagefilter($im, IMG_FILTER_COLORIZE, hexdec($r), hexdec($g), hexdec($b))) {                            
                                        $this->placehold($im);
                                    }
                                }
                                else if ($c == 'blur') {
                                    if (imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR)) {                            
                                        $this->placehold($im);
                                    } 
                                }
                                else if ($c == 'grey' || $c == 'gray') {
                                    if (imagefilter($im, IMG_FILTER_GRAYSCALE)) {                            
                                        $this->placehold($im);
                                    } 
                                }
                            }
                            else if ($im) {
                                $this->placehold($im);
                            }
                        }
                        else if (imagetypes() && IMG_PNG && $license == 'ad') {                        
                            $im = $this->image; 
                            $this->placehold($im);
                        }
    
    
                    }  
    
                    public function placehold($im) 
                    {
                        imagepng($im, 'cache/' . $this->cachePath . '.png');
                        
                        header('Content-Type: image/png');
                        imagepng($im);
                        imagedestroy($im);
                        
                    }
                }
?>
