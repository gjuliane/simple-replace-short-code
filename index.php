<?php

/**
 *  Complete regexps
 *  \[vc_empty_space*[\]]       // empty space replace with <br>
 *  <h2.*\/h2>                  // all h2
 *  \[embed*[\]]                // start embed
 *  \[\/embed*[\]]              // end embed
 *  \[embed.*\](.*)\[\/embed\]  // complete embed
 *  \[.*[\]]                    // all shortcodes
 */

// getting the content
$node = do_get_content();

// replacing empty spaces
$node = preg_replace("/\[vc_empty_space*[\]]/m", "<br>", $node);
$node = preg_replace("/<h2.*\/h2>/m", "<br>", $node);

// serching and replacing youtube embeds
$node = preg_replace_callback("/\[embed.*\](.*)\[\/embed\]/m", 'do_replace_yt_embed', $node);

// cleaning all shotcodes
$node = preg_replace("/\[.*[\]]/m", "<br>", $node);

// saving
#code... 

echo $node;

/**
 * Get the content
 *
 * @return void
 */
function do_get_content() {
    $path = getcwd() . '/post.txt';
    #echo $path;
    return file_get_contents($path);
}

/**
 * extract id of youtube
 *
 * @param [type] $url
 * @return void
 */
function do_get_youtubeid($url) {
    $match =  '@';
    $match .=  '(?:https?\:)?';                           #opcional
    $match .=   '(//www\.youtube\.com/)';                 #forzoso
    $match .=    '(?:';                                   #agrupador inicio
    $match .=      '(embed/|watch\?v=|watch\?.+&v=|v/)';  #variación forzoso
    $match .=    ')';                                     #agrupador fin
    $match .=    '([\w-]{11})';                           #forzoso
    $match .=  '@i';
    $matches = array();
    preg_match($match, $url, $matches);
    return isset($matches[3])?$matches[3]:false;
}

/**
 * do replacing the match to yt frame
 *
 * @param [type] $matches
 * @return void
 */
function do_replace_yt_embed($matches) {
    if (isset($matches[1])) {
        $yt_id = do_get_youtubeid($matches[1]);
        if ($yt_id) {
            return '<iframe src="https://www.youtube.com/embed/'.$yt_id.'" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';

        }else{
            return '';
        }
    }
    return '';
}