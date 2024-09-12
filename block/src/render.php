<?php
// Use the $attributes (array), $content (string), and $block (WP_Block) provided by WordPress.

$fullHeightClass = !empty($attributes['fullHeight']) && $attributes['fullHeight'] ? 'full-height' : '';

// Fetch MCP posts (custom post type)
$args = array(
    'post_type'      => 'mcp',
    'posts_per_page' => -1,
);

$posts = get_posts($args);

// If there are no posts, return a message
if (empty($posts)) {
    return '<div class="mcp-block">No MCP posts found.</div>';
}

// Start generating the HTML output
$output = '<div class="mcp-block ' . esc_attr($fullHeightClass) . '">';

foreach ($posts as $post) {
    $title = get_the_title($post);
    $link = get_permalink($post);
    $excerpt = get_the_excerpt($post);
    $featured_image = get_the_post_thumbnail_url($post, 'full') ?: 'https://via.placeholder.com/300x200';

    // Generate the HTML for each post card
    $output .= '<div class="mcp-block-card">';
    $output .= '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr($title) . '" />';
    $output .= '<div class="mcp-category"><span class="dashicons dashicons-admin-media"></span> Object</div>';
    $output .= '<div class="mcp-title"><a href="' . esc_url($link) . '">' . esc_html($title) . '</a></div>';
    $output .= '<div class="mcp-description">' . esc_html($excerpt) . '</div>';
    $output .= '</div>';
}

$output .= '</div>';

return $output;
