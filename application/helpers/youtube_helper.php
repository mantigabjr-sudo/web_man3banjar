<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('youtube_video_id')){
    function youtube_video_id($url){

        $url = trim((string)$url);

        if($url === ''){
            return '';
        }

        // Kalau yang disimpan hanya ID video
        if(preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)){
            return $url;
        }

        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
            '/youtube\.com\/live\/([a-zA-Z0-9_-]{11})/'
        ];

        foreach($patterns as $pattern){
            if(preg_match($pattern, $url, $match)){
                return $match[1];
            }
        }

        return '';
    }
}

if(!function_exists('youtube_embed_url')){
    function youtube_embed_url($url){

        $id = youtube_video_id($url);

        if($id === ''){
            return '';
        }

        return 'https://www.youtube.com/embed/'.$id.'?rel=0&modestbranding=1';
    }
}

if(!function_exists('youtube_watch_url')){
    function youtube_watch_url($url){

        $id = youtube_video_id($url);

        if($id === ''){
            return $url;
        }

        return 'https://www.youtube.com/watch?v='.$id;
    }
}