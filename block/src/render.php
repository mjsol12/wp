<?php
// Use the $attributes (array), $content (string), and $block (WP_Block) provided by WordPress.
$alignClass = !empty($attributes['align']) ? 'align' . $attributes['align'] : '';

// Fetch MCP posts (custom post type)
$args = array(
    'post_type'      => 'mcp',
    'posts_per_page' => -1,
);

$posts = get_posts($args);
// If there are no posts, return a message
if (empty($posts)) {
    echo '<div class="mcp-block">No MCP posts found.</div>';
}

// Start generating the HTML output
$output = '<div class="' . esc_attr($alignClass) . '">';
    $output .= '<div class="mcp-block">';

foreach ($posts as $post) {
    $title = get_the_title($post);
    $link = get_permalink($post);
    $excerpt = get_the_excerpt($post);
    $featured_image = get_the_post_thumbnail_url($post, 'full') ?: 'https://via.placeholder.com/300x200';

    // Generate the HTML for each post card
    $output .= '<div class="mcp-block-card">';
    $output .= '<img src="' . esc_url($featured_image) . '" alt="' . esc_attr($title) . '" />';
    $output .= '<div class="mcp-category"><span className="dashicons dashicons-format-image"></span> Object</div>';
    $output .= '<div class="mcp-title"><a href="' . esc_url($link) . '">' . esc_html($title) . '</a></div>';
    $output .= '<div class="mcp-description">' . esc_html($excerpt) . '</div>';
    $output .= '</div>';
}

    $output .= '</div>';
$output .= '</div>';

echo $output;
