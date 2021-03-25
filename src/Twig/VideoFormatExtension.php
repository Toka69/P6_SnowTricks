<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class VideoFormatExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('videoFormat', [$this, 'videoFormat'])
        ];
    }

    public function videoFormat($value){
        $videoFormat = null;

        // Youtube
        if(str_contains($value, "youtu")) {

            // https://youtu.be/D5MEXeItvoM
            if (str_contains($value, "youtu.be")) {
                $tag = str_replace("https://youtu.be/", "", $value);
            }

            // <iframe width="560" height="315" src="https://www.youtube.com/embed/D5MEXeItvoM" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            if (str_contains($value, "youtube.com/embed")) {
                $tag = str_replace("embed/", "", strstr($value, "embed/"));
                $tag = str_replace(strstr($tag, '"'), "", $tag);
            }


            // https://www.youtube.com/watch?v=D5MEXeItvoM
            if (str_contains($value, "youtube.com/watch")) {
                $tag = strstr($value, "v=");
                $tag = str_replace("v=", "", $tag);
            }

            $videoFormat = '<iframe src="https://www.youtube.com/embed/' . $tag . '" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }

        //Dailymotion
        if (str_contains($value, "dai")) {

            // https://dai.ly/x7zza2t
            if (str_contains($value, "dai.ly")) {
                $tag = str_replace("https://dai.ly/", "", $value);
            }

            // <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;"> <iframe style="width:100%;height:100%;position:absolute;left:0px;top:0px;overflow:hidden" frameborder="0" type="text/html" src="https://www.dailymotion.com/embed/video/x7zza2t?autoplay=1" width="100%" height="100%" allowfullscreen allow="autoplay"> </iframe> </div>
            if (str_contains($value, "dailymotion.com/embed/video/")) {
                $tag = str_replace("video/", "", strstr($value, "video/"));
                $tag = str_replace(strstr($tag, '"'), "", $tag);
                if(str_contains($tag, 'autoplay')){$tag = str_replace("?autoplay=1", "", $tag);}
            }

            // https://www.dailymotion.com/video/x7zza2t
            if (str_contains($value, "dailymotion.com/video/")) {
                $tag = str_replace("video/", "", strstr($value, "video/"));
            }

            $videoFormat = '<iframe src="https://www.dailymotion.com/embed/video/' . $tag . '" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }

        return $videoFormat;
    }
}