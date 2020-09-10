<?php

namespace app\models;

use ishop\App;
use ishop\Link;

class Breadcrumbs
{

    public static function getBreadcrumbs($category_id, $data = [])
    {
        $cats = App::$app->getProperty('cats');
        $breadcrumbs_array = self::getParts($cats, $category_id);
        $seo_link_on = App::$app->getProperty('seo_link_on');

        $breadcrumbs = "<li class='breadcrumb-item' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>" . PHP_EOL;
        $breadcrumbs .= "<a class='js-link-true2' itemprop='item' href='/'>" . PHP_EOL;
        $breadcrumbs .= "<span itemprop='name' class='material-icons'>home</span></a>" . PHP_EOL;
        $breadcrumbs .= "<meta itemprop='position' content='1' />" . PHP_EOL;
        $breadcrumbs .= "</li>" . PHP_EOL;
        $i = 2;

        if ($breadcrumbs_array) {
            $default_catalog_uri = App::$app->getProperty('filter_default_uri');
            $on_all_product = App::$app->getProperty('on_breadcrumbs_all_product');
            if ($on_all_product && !array_key_exists($default_catalog_uri, $breadcrumbs_array)) {
                $name_default_uri = App::$app->getProperty('name_default_uri');
                if (!$seo_link_on) {
                    $default_catalog_uri = 'category/' . $default_catalog_uri;
                }
                $default_catalog_uri = Link::getLink($default_catalog_uri);
                $breadcrumbs .= "<li class='breadcrumb-item' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>" . PHP_EOL;
                $breadcrumbs .= "<a class='js-link-true2' itemprop='item' href='{$default_catalog_uri}'>" . PHP_EOL;
                $breadcrumbs .= "<span itemprop='name'>{$name_default_uri}</span></a>" . PHP_EOL;
                $breadcrumbs .= "<meta itemprop='position' content='2' />" . PHP_EOL;
                $breadcrumbs .= "</li>" . PHP_EOL;
                $i++;
            }
            $count = count($breadcrumbs_array);
            foreach ($breadcrumbs_array as $alias => $title) {

                $class = '';
                $disabled = '';
                if ($count == 1 && !$data) {
                    $class = ' active';
                    $disabled = ' disabled';
                }
                $breadcrumbs .= "<li class='breadcrumb-item{$class}' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>" . PHP_EOL;
                if ($seo_link_on) {
                    $item_link = $alias;
                } else {
                    $item_link = 'category/{$alias}';
                }
                $item_link = Link::getLink($item_link);
                $breadcrumbs .= "<a class='js-link-true2{$disabled}' itemprop='item' href='{$item_link}'>" . PHP_EOL;
                $breadcrumbs .= "<span itemprop='name'>{$title}</span></a>" . PHP_EOL;
                $breadcrumbs .= "<meta itemprop='position' content='$i' />" . PHP_EOL;
                $breadcrumbs .= "</li>" . PHP_EOL;
                $i++;
                $count--;
            }
        }
        if ($data) {
            $breadcrumbs .= "<li class='breadcrumb-item active' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>" . PHP_EOL;

            if ($seo_link_on) {
                $item_link = $data['seolink'];
            } else {
                $item_link = $data['alias'];
            }
            $item_link = Link::getLink($item_link);
            $breadcrumbs .= "<a class='js-link-true2 disabled' itemprop='item' href='{$item_link}'>" . PHP_EOL;
            $breadcrumbs .= "<span itemprop='name'>{$data['title']}</span></a>" . PHP_EOL;
            $breadcrumbs .= "<meta itemprop='position' content='$i' />" . PHP_EOL;
            $breadcrumbs .= "</li>" . PHP_EOL;
        }
        return $breadcrumbs;
    }

    public static function getParts($cats, $id)
    {
        if (!$id)
            return false;
        $seo_link_on = App::$app->getProperty('seo_link_on');
        $breadcrumbs = [];
        $default_cat = '';
        foreach ($cats as $k => $v) {
            if (isset($cats[$id])) {
                if ($seo_link_on) {
                    $key = $cats[$id]['seolink'];
                } else {
                    $key = $cats[$id]['alias'];
                }

                if ($cats[$id]['cat_default'])
                    $default_cat = $key;

                $breadcrumbs[$key] = $cats[$id]['title'];

                $id = $cats[$id]['parent_id'];
            } else
                break;
        }

        if ($default_cat && count($breadcrumbs) > 1)
            unset($breadcrumbs[$default_cat]);

        return array_reverse($breadcrumbs, true);
    }
}
