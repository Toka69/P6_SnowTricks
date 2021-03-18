<?php


namespace App\EntityListener;

use App\Entity\Video;

class VideoListener
{
    public function postLoad(Video $video){
        $this->videoUrlFormat($video);
    }

    public function videoUrlFormat(Video $video){

        if($video){
        // Youtube
            dump(str_contains($video->getLocation(), "youtu"));
//            if(str_contains($video->getLocation(), "youtu") == false){
//                dump("test");
//            }

            // https://youtu.be/D5MEXeItvoM
            if(str_contains($video->getLocation(), "youtu.be")) {
                $tag = str_replace("https://youtu.be/", "", $video->getLocation());
                return $video->setLocation($tag);
            }

            // <iframe width="560" height="315" src="https://www.youtube.com/embed/D5MEXeItvoM" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            if(str_contains($video->getLocation(), "youtube.com/embed")){
                $tag = strstr($video->getLocation(), "embed/");
                $tag = str_replace("embed/","", $tag);
                return $video->setLocation($tag);
            }


            // https://www.youtube.com/watch?v=D5MEXeItvoM
            if(str_contains($video->getLocation(), "youtube.com/watch")) {
                $tag = strstr($video->getLocation(), "v=");
                $tag = str_replace("v=","", $tag);
                return $video->setLocation($tag);
            }
        }
    }
}
