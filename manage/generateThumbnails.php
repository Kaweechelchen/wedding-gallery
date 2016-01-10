<?php

// sizes to create
$sizes = array(
    'large' => 1280,
    'thumb' => 250
);

// create folders
foreach ($sizes as $folder => $size)
{
    $dir = __DIR__.'/../photos/'.$folder;
    if (!is_dir($dir))
        mkdir($dir);
}

// scale images
$images = glob(__DIR__.'/../photos/*.jpg');
foreach ($images as $image)
{

    foreach ($sizes as $folder => $size)
    {

        $imagick = new \Imagick($image);
        $imagick->resizeImage($size, $size, \Imagick::FILTER_LANCZOS, 1, true);
        $imagick->writeImage(__DIR__.'/../photos/'.$folder.'/'.basename($image));
        $imagick->clear();
        $imagick->destroy();

    }

}
