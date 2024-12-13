<?php

require_once "config.php";

class Controller {
    public function SearchMovies($query, $page){
        global $config;
        $response = file_get_contents($config['endpoint'] . 'search/movie?api_key=' . $config['api_key'] . '&query=' . $query . '&page=' . $page . '&include_adult=false&language=en-US');
        $results = json_decode($response, true);
        return [
            'total_pages' => $results['total_pages'],
            'data' => $results['results']
        ];
    }
    public function GetMovieByID($id){
        global $config;
        $response = file_get_contents($config['endpoint'] .'movie/'. $id . '?api_key=' . $config['api_key'] . '&language=en-US');
        $movie = json_decode($response, true);
        return $movie;

    }

}

function DateNow($date) {
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return 'Invalid date'; // Jika tanggal tidak valid
    }
    return date('M d, Y', $timestamp);
}

