<?php
function contact_widget_shortcode($atts) {
    global $post;
    extract(shortcode_atts(array(
        'title' => '',
        'id' => '',
    ), $atts));

    if (!$id) {
        return;
    }

    ob_start();
    $cfw = new Contact_widget_Class;
    if ($title) {
        echo '<h2>' . $title . '</h2>';
    }
    $cfw->contact_wid_body(array('wid_contact_form' => $id));
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}

function subscribe_widget_shortcode($atts) {
    global $post;
    extract(shortcode_atts(array(
        'title' => '',
        'id' => '',
    ), $atts));

    if (!$id) {
        return;
    }

    ob_start();
    $cfw = new Subscription_Widget_Class;
    if ($title) {
        echo '<h2>' . $title . '</h2>';
    }
    $cfw->subscribeWidBody(array('wid_subscribe_form' => $id));
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}

function newsletter_shortcode_function($atts) {
    global $post;
    extract(shortcode_atts(array(
        'cat' => '',
        'count' => 10,
        'order' => 'asc',
        'orderby' => 'date',
        'featuredimage' => 'yes',
        'readmore' => 'yes',
    ), $atts));

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $count,
        'order' => $order,
        'orderby' => $orderby,
    );

    if ($cat) {
        $cat = trim($cat, ",");
        $args['cat'] = $cat;
    }

    $return_data = array('query_args' => $args, 'extra' => array('featuredimage' => $featuredimage, 'readmore' => $readmore));
    $return_data = '@@' . serialize($return_data) . '@@';
    return $return_data;
}
