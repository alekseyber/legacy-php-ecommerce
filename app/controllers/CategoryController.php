<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use app\widgets\filter\Filter;
//use app\widgets\menu\Menu;
use app\models\Product;
use app\models\Sort;
use ishop\App;
use ishop\libs\Pagination;
use ishop\Link;
use ishop\libs\URL;
use app\models\Cart;

class CategoryController extends AppController {

    public function viewAction() {
        $alias = $this->route['alias'];

        //добавление префикса к таблице
        //$table = self::getTablePref('name_table');

        $category = \R::findOne('category', 'alias = ? AND status = 1', [$alias]);

        if (!$category) {
            throw new \Exception('Страница не найдена', 404);
        }
		$code = !empty($_GET['cupon']) ? $_GET['cupon'] : '';			
		if ($code) {
			Cart::promocod($code);
		} 
        if (App::$app->getProperty('last-modified_header_on') && !$this->json) {
            self::setLastmodified($category->update_at);
        }

        // $menuCatalog = new Menu([
        //     'tpl' => WWW . '/menu/menu.php',
        //     'class' => 'nav-left nav-pills refinements-item-catproduct nav-stacked',
        //     'seolink' => Link::getLink($category->seolink),
        // ]);
        self::uriChek();

        $linkCanonical = PATH . '/' . App::$app->getProperty('controlleraction');
        $utm_content = $_GET['utm_content'] ?? 0;
        $utm = [];
        if ($utm_content) {
            $pattern = "#[^\d]+?#";
            $utm_content = preg_replace($pattern, '', $utm_content);
            $utm_content = trim($utm_content);
            if ($utm_content) {
                $utm = \R::getRow('SELECT `id`,`key_item`,`title`,`promo` FROM `utm` WHERE `key_item` = ? AND status = 1 LIMIT 1', [$utm_content]);
                if ($utm) {
                    $_SESSION['utm_id'] = $utm['id'];
                }
            }
        }
        $breadcrumbs = Breadcrumbs::getBreadcrumbs($category->id);

        $cat_model = new Category();
        $ids = $cat_model->getIds($category->id);
        $ids = !$ids ? $category->id : $ids . $category->id;

        $getArr = App::$app->getProperty('chek_get');
        $tags_status = isset($getArr['tags']);

        //$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $page = isset($getArr['page']) ? $getArr['page'] : 1;


        $perpage = App::$app->getProperty('pagination');
        $postfix_meta = "";

        $product_ids = App::$app->getProperty('product_ids');
        $sql_part = "SELECT DISTINCT product_id FROM categoryproduct WHERE product_id IN ($product_ids) AND category_id IN ($ids)";
        $product_ids = \R::getCol($sql_part);

        $product_ids = implode(',', $product_ids);
        //        $filter = new Filter($product_ids, '', '', true, false, false, true, false);

        $filter = new Filter([
            'ids' => $product_ids,
            'cache' => false,
        ]);


        $product_ids = $filter->ids;
        $color = $filter->color;
        $colorSql = $filter->colorSql;
        $count_modif = $filter->valFactTitle['count']['modif'] ?? 0;
        $count_product = $filter->valFactTitle['count']['product'] ?? 0;
        $total = $color ? $count_modif : $count_product;
        $pagination = new Pagination($page, $perpage, $total);
        if ($perpage >= $total)
            $paginationstr = '';
        else
            $paginationstr = $pagination;
        $start = $pagination->getStart();
        $sort = '';
        $paginContent = '';
        if ($total) {
            //   $post = self::postChek();

            $sort = new Sort(Link::getLink($filter->uri), 'sortproduct', $getArr);

            $sortSqlPart = $sort->sortSqlPart;
            $products = Product::getAllProducts($product_ids, false, true, $start, $perpage, $color, $colorSql, $sortSqlPart);

            $p_model = new Product();
            $products = $p_model->setProducts($products);

            //  $valTagsHtml = '';
            // if ($category['parent_id'] == 0) {
            //     $valTagsHtml = $filter->valTagsHtml;
            // }

            $paginContent = $this->loadViewContent('view_pagin', compact('products', 'paginationstr'));

            if (
                    $this->json &&
                    (isset($_POST['pagin']) ||
                    (isset($getArr['sortproduct']) &&
                    (int) $getArr['sortproduct']))
            ) {
                exit(json_encode(['content' => $paginContent]));
                //   $this->loadView('view_pagin', compact('products', 'paginationstr'));
            }

//            if ($this->isAjax() && isset($getArr['sortproduct']) && (int) $getArr['sortproduct']) {
//                $this->loadView('view_pagin', compact('products', 'paginationstr'));
//            }

            if ($tags_status) {
                $currenturi = App::$app->getProperty('chek_uri');
                $currenturi = URL::deletePage($currenturi);
                if (isset($filter->valTagsIndex[$currenturi])) {
                    $linkCanonical = PATH . '/' . ltrim($filter->valTagsIndex[$currenturi]['uri'], '/');
                    $postfix_meta = ' - Тег: ' . $filter->valTagsIndex[$currenturi]['title'];
                }
            }

            if ($page > 1) {
                $linkCanonical = URL::addGET($linkCanonical, 'page', $page);
                $postfix_meta .= " - Страница " . $page;
            }

            $contData['meta_title'] = $category->meta_title . $postfix_meta;
            $contData['meta_description'] = $category->meta_description . $postfix_meta;
            $contData['meta_keywords'] = $category->meta_keywords;
            $contData['htitle'] = $category->htitle . $postfix_meta;
            $contData['promo'] = $category->promo;
            $contData['content'] = ($page > 1) ? '' : $category->content;

            $patternData = Category::getPattern($filter->valFactTitle);
            $patternData['category_name'] = $category->title;
            $patternData['category_title'] = $category->htitle;
            if ($utm) {
                if ($utm['title']) {
                    $contData['htitle'] = $utm['title'];
                }
                if ($utm['promo']) {
                    $contData['promo'] = $utm['promo'];
                }
            }
            $contData = prRep($contData, $patternData);

            //  $contData['valTagsHtml'] = $valTagsHtml;
            //            if ($category['parent_id'] == 0) {
            //                $contData['valTagsHtml'] = $filter->valTagsHtml;
            //            }
        } else {
            $products = [];
            $contData['meta_title'] = "С учетом текущих условий фильтрации - товаров не найдено";
            $contData['meta_description'] = "С учетом текущих условий фильтрации - товаров не найдено, попробуйте изменить критерии фильтрации.";
            $contData['meta_keywords'] = "";
            $contData['htitle'] = 'Товаров не найдено';
            $contData['promo'] = 'С учетом текущих условий фильтрации - товаров не найдено, попробуйте изменить критерии фильтрации.';
            $contData['content'] = '';
            //  $contData['valTagsHtml'] = '';
        }
        if ($this->json) {

            $res = [
                'content' => $this->loadViewContent('view', compact('paginContent', 'breadcrumbs', 'total', 'sort', 'contData')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $filter->toString(),
            ];

            exit(json_encode($res));
        }
        $opengraph = [];

        $linkCanonical = preg_replace('/&amp;/', '&', $linkCanonical);

        if (App::$app->getProperty('on_opengraph')) {
            $opengraph['og:title'] = $contData['htitle'];
            $opengraph['og:type'] = 'website';
            $opengraph['og:description'] = $contData['meta_description'];
            $opengraph['og:url'] = $linkCanonical;
            $opengraph['og:locale'] = 'ru_RU';
            $img = $category->img;
            if ($img && $img != "no_image.jpg") {
                $img_category_path = trim(App::$app->getProperty('img_category_path'), '/');
                $opengraph['og:image'] = PATH . '/' . $img_category_path . '/' . $img;
            }
        }

        App::$app->setProperty('filter_content', $filter);

        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical, $opengraph);
        $this->set(compact('paginContent', 'breadcrumbs', 'total', 'sort', 'contData'));
    }

}
