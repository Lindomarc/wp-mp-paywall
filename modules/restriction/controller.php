<?php

if (!function_exists('pdi_paywall_verify_singular')) {
    function pdi_paywall_verify_singular($post_id = '')
    {
        $post_id = get_the_ID() ?? $post_id;
        $is_single = false;

        if (is_single($post_id)) {
            $is_single = true;
        }

        if (is_page($post_id)) {
            $is_single = true;
        }

        return $is_single;
    }
}

if (!function_exists('pdi_paywall_verify_singular_post')) {
    function pdi_paywall_verify_singular_post($post_id = '')
    {
        $post_id = get_the_ID() ?? $post_id;
        $is_single = false;

        if (is_single($post_id)) {
            $is_single = true;
        }

        return $is_single;
    }
}

if (!function_exists('pdi_paywall_verify_restrictions')) {
    function pdi_paywall_verify_restrictions($post_id = '')
    {
        $post_id = get_the_ID() ?? $post_id;
        $is_restriction = false;

        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $not_bypass_roles = ['reader', 'subscriber'];

            if (!array_intersect($not_bypass_roles, $user->roles)) {
                return $is_restriction;
            }
        }

        $restriction_content = pdi_paywall_get_restrictions();
        if (!empty($restriction_content)) {
            if (is_user_logged_in()) {
                $user = wp_get_current_user();
                $not_bypass_roles = ['reader'];

                if (!array_intersect($not_bypass_roles, $user->roles)) {
                    return $is_restriction;
                }
            } else {
                $post_categories = get_the_category($post_id);

                if (!empty($post_categories)) {
                    foreach ($post_categories as $post_category) {
                        if (in_array($post_category->term_id, $restriction_content)) {
                            $is_restriction = true;

                            return $is_restriction;
                        }
                    }
                }
            }
        }

        pdi_paywall_cookie_restriction();

        $visibility = get_post_meta($post_id, PDI_PAYWALL_META_KEY_VISIBILITY, true);
        if (!empty($visibility)) {
            $is_restriction = true;

            if (is_user_logged_in()) {
                $user = wp_get_current_user();

                if (array_intersect(['reader'], $user->roles) && $visibility === PDI_PAYWALL_VISIBILITY_REGISTERED) {
                    $is_restriction = false;
                }

                if (array_intersect(['subscriber'], $user->roles) && $visibility !== PDI_PAYWALL_VISIBILITY_EXCLUSIVE) {
                    $is_restriction = false;
                }
            }

            return $is_restriction;
        }

        if (is_user_logged_in()) {
            $config_registered = get_option('_pdi_paywall_read_limit_registered');
            if (!empty($config_registered)) {
                if (pdi_paywall_cookie_restriction_verify($config_registered)) {
                    $is_restriction = true;
                }
            }
        } else {
            $config_guest = get_option('_pdi_paywall_read_limit_guest');
            if (!empty($config_guest)) {
                if (pdi_paywall_cookie_restriction_verify($config_guest)) {
                    $is_restriction = true;
                }
            }
        }

        $no_restriction_content = pdi_paywall_get_free_restrictions();
        if (!empty($no_restriction_content)) {
            $post_categories = get_the_category($post_id);
            if (!empty($post_categories)) {
                foreach ($post_categories as $post_category) {
                    if (in_array($post_category->term_id, $no_restriction_content)) {
                        $is_restriction = false;

                        return $is_restriction;
                    }
                }
            }
        }

        return $is_restriction;
    }
}
if (!function_exists('pdi_paywall_content_restriction')) {
    function pdi_paywall_content_restriction($content)
    {
        if (pdi_paywall_verify_singular()) {
            if (pdi_paywall_verify_restrictions()) {
                $limit_content = get_option('_pdi_paywall_page_limit_content');
                if (!$limit_content){
                    $limit_content = 200;
                }
                $content = substr(strip_tags($content), 0, $limit_content); // verificar para colocar um valor custom ao invés de somente 100

                $login = get_page_link(get_option('_pdi_paywall_page_login'));
                $register = get_page_link(get_option('_pdi_paywall_page_register'));
                $plans = get_page_link(get_option('_pdi_paywall_page_plans'));
                if (is_user_logged_in()) {
                    require_once(PDI_PAYWALL_MODULES_PATH . 'restriction/views/registered.php');
                } else {
                    require_once(PDI_PAYWALL_MODULES_PATH . 'restriction/views/guest.php');
                }

            }
        }
        return  $content;

    }
    add_filter('the_content', 'pdi_paywall_content_restriction');
}


if (!function_exists('pdi_paywall_cookie_script')) {
    function pdi_paywall_cookie_script()
    {
        echo '<script src="' . plugins_url('/js/js.cookie.min.js', __FILE__) . '"></script>';
    }
    add_action('wp_head', 'pdi_paywall_cookie_script');
}

if (!function_exists('pdi_paywall_cookie_restriction')) {
    function pdi_paywall_cookie_restriction()
    {
        if (pdi_paywall_verify_singular_post()) {
            // verificar para mascarar o id do post
            $post_id = get_the_ID();
            $now = time();
            $one_day = $now + (24 * 60 * 60);

            if (!isset($_COOKIE[PDI_PAYWALL_COOKIE])) {
                $cookie_content = new stdClass;
                $cookie_content->$post_id = $one_day;
            } else {
                $cookie_content = json_decode(stripslashes($_COOKIE[PDI_PAYWALL_COOKIE]));

                if (property_exists($cookie_content, $post_id)) {
                    if ($cookie_content->$post_id < $now) {
                        unset($cookie_content->$post_id);
                    }
                } else {
                    $cookie_content->$post_id = $one_day;
                }
            }

            require_once(PDI_PAYWALL_MODULES_PATH . 'restriction/cookie.php');
        }
    }
}

if (!function_exists('pdi_paywall_cookie_restriction_verify')) {
    function pdi_paywall_cookie_restriction_verify($read_limit)
    {
        if (pdi_paywall_verify_singular_post()) {
            if (isset($_COOKIE[PDI_PAYWALL_COOKIE])) {
                $cookie_content = json_decode(stripslashes($_COOKIE[PDI_PAYWALL_COOKIE]));
                $total_read = count(get_object_vars($cookie_content));
                // verificar para mascarar o id do post
                $post_id = get_the_ID();

                if (property_exists($cookie_content, $post_id)) {
                    return false;
                }

                if ($total_read >= $read_limit) {
                    return true;
                }
            }
        }

        return false;
    }
}
