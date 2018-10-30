<?php
//LOC:wp-content/themes/fotobuero15/includes/functional/functions-menus.php
function fotobuero15_menus()
{
    register_nav_menus(
        array(
            'main-menu' => __('Hauptnavigation', 'fotobuero15'),
            'box-menu' => __('Kachelnavigation', 'fotobuero15')
        )
    );
}

add_action('init', 'fotobuero15_menus');

function fotobuero15_kachelmenu($atts)
{
    $menu = wp_get_nav_menu_items(__('Kachelnavigation', 'fotobuero15'));

    $output = '<nav class="row box-menu">';

    if ($menu){
      foreach ($menu as $item) {
          $image = wp_get_attachment_image_src(get_post_thumbnail_id($item->object_id), 'large');
          $output .= '<div class="col-xs-6 col-sm-3 col-md-2"><div class="square-dummy"></div><div class="menu-container" style="background-image: url(\'' . $image[0] . '\')"><a href="' . $item->url . '" class="text-center">' . $item->title . '</a></div>' . '</div>';
      }
    }

    $output .= '</nav>';

    return $output;
}

add_shortcode('Kachelnavigation', 'fotobuero15_kachelmenu');
add_shortcode('kachelnavigation', 'fotobuero15_kachelmenu');

class Fotobuero_Menu_Walker extends Walker_Nav_Menu
{
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $class_names = '';
        $glyphicon_class = '';

        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if (array_search('menu-item-has-children', $classes)) {
            $link_class = 'dropdown-toggle';
            $link_data_toggle = 'dropdown';
            $ul_class = array('class' => 'dropdown-menu');
        }


        /**
         * Filter the CSS class(es) applied to a menu item's <li>.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param array $classes The CSS classes that are applied to the menu item's <li>.
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filter the ID applied to a menu item's <li>.
         *
         * @since 3.0.1
         *
         * @see wp_nav_menu()
         *
         * @param string $menu_id The ID that is applied to the menu item's <li>.
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['class'] = !empty($link_class) ? $link_class : '';
        $atts['data-toggle'] = !empty($link_data_toggle) ? $link_data_toggle : '';
        $atts['data-hover'] = !empty($link_data_toggle) ? 'dropdown' : '';
        $atts['data-delay'] = !empty($link_data_toggle) ? '1' : '';

        /**
         * Filter the HTML attributes applied to a menu item's <a>.
         *
         * @since 3.6.0
         *
         * @see wp_nav_menu()
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
         *
         * @type string $title Title attribute.
         * @type string $target Target attribute.
         * @type string $rel The rel attribute.
         * @type string $href The href attribute.
         * }
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $value = ('class' === $attr && stripos($value, '') !== false) ? 'right' : $value;
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= str_ireplace('%glyphicon%', $glyphicon_class, $args->link_before) . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= !empty($link_data_toggle) ? '<span class="caret">&nbsp;</span>' : '';
        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes $args->before, the opening <a>,
         * the menu item's title, the closing </a>, and $args->after. Currently, there is
         * no filter for modifying the opening and closing <li> for a menu item.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

?>
