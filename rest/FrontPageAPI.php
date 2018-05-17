<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/17/18
 * Time: 9:49 AM
 */

class FrontPageAPI {

    function get_data() {
        $opts = array(
            'http' => array(
                'user_agent' => 'PHP libxml agent',
            )
        );
        $context = stream_context_create($opts);
        libxml_set_streams_context($context);
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTMLFile("http://fondacijahastor.ba/novosti/"); // @ --> suppress errors

        $xpath = new DOMXpath($dom);
        $articles = $xpath->query('//div[@class="post-item"]');

        $article_list = array();
        $root_nodes = $dom->getElementsByTagName("div");
        foreach($root_nodes as $root_node) {
            $attr = $root_node->getAttribute("class");
            if (strpos($attr, "post-item") !== false) {
                /* create one article */
                $article_data = array(
                    "image" => "",
                    "title" => "",
                    "description" => "",
                    "about" => ""
                );
                /* get article images */
                $article_images = $root_node->getElementsByTagName("img");
                foreach ($article_images as $article_image) {
                    $article_data["image"] = $article_image->getAttribute("src");
                }
                /* get article titles */
                $article_titles = $root_node->getElementsByTagName("h2");
                foreach ($article_titles as $article_title) {
                    $article_data["title"] = $article_title->nodeValue;
                }
                /* get article descriptions */
                $article_descriptions = $root_node->getElementsByTagName("div");
                foreach ($article_descriptions as $article_description) {
                    if($article_description->getAttribute("class") == "post-excerpt") {
                        $article_data["description"] = $article_description->nodeValue;
                    }
                }
                /* get "more details" links */
                $article_details = $root_node->getElementsByTagName("a");
                foreach ($article_details as $article_detail) {
                    $article_data["about"] = $article_detail->getAttribute("href");
                }

                $article_list[] = $article_data;
            }

        }
        return $article_list;
    }
}


?>