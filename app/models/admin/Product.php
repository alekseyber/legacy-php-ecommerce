<?php

namespace app\models\admin;

use app\models\AppModel;
use ishop\Cache;

class Product extends AppModel {

    public $attributes = [
        'title' => '',
        'category_id' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
        'price' => '',
        'old_price' => '',
        'content' => '',
        'product_model' => '1',
        'brand_id' => '',
        'sku' => '',
        'gender' => '',
        'sizesgroup_id' => '',
        'color_default' => '',
        'related' => '',
        'promo_id' => '',
        'update_at' => '',
        'status' => '2', //состояние выключено
        'hit' => '2', //состояние выключено
        'cart_on' => '1', //состояние выключено
    ];
    public $rules = [
        'required' => [
            ['title'],
            ['category_id'],
            ['sizesgroup_id'],
            ['product_model'],
            ['gender'],
        ],
        'integer' => [
            ['category_id'],
            ['related'],
            ['promo_id'],
        ],
    ];
    public $label = [
        'title' => 'Наименование',
        'status' => 'Включен',
        'category_id' => 'Родительская категория',
        'hit' => 'Хит',
        'cart_on' => 'В корзине предлагать',
        'related' => 'Связанный товар',
        'meta_title' => 'Meta title',
        'meta_keywords' => 'Meta_Keywords',
        'meta_description' => 'Meta description',
        'price' => 'Цена со скидкой',
        'old_price' => 'Цена без скидки',
        'content' => 'Контент',
        'brand_id' => 'Бренд',
        'cart_on' => 'Предлагать в к',
        'promo_id' => 'Promo',
        'sku' => 'SKU',
        'product_model' => 'Продукт модель',
        'gender' => 'Пол префикс',
        'color_default' => 'Цвет по умолчанию',
    ];
    public $form = [
        'action' => ADMIN . '/product/edit',
        'method' => 'post',
        'formclass' => 'post',
        'formclass' => 'js-form-edit-product',
        'dataattr' => ['toggle' => 'validator', 'posthangler' => 'false'],
    ];
    public $default = [
        'tagstart0' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box'],
        'tagstart000' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-header with-border'],
        'boxtitle0' => ['type' => 'html', 'default_v' => '<h3 class="box-title">Основные данные</h3>', 'label2' => ''],
        'tagstart001' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-tools pull-right'],
        'linkhref' => ['type' => 'href', 'label2' => 'Обновить страницу', 'default_v' => ADMIN . '/product/edit?id=', 'name2' => 'id', 'class' => 'btn btn-info'],
        'linkhref2' => ['type' => 'href', 'label2' => 'Копировать продукт', 'default_v' => ADMIN . '/product/copyproduct?id=', 'name2' => 'id', 'class' => 'btn btn-primary'],
        'submit' => ['type' => 'submit', 'default_v' => 'Сохранить', 'class' => 'btn btn-success btn-save-product'],
        'tagend001' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend000' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart00' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-body'],
        'tagstart1' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'row'],
        'tagstart2' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-4'],
        'title' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'tagend2' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart3' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'sku' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'tagend3' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart4' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'brand_id' => ['type' => 'select', 'default_v' => '', 'selectdata' => ['brand', 'title'], 'parentclass' => 'form-group'],
        'tagend4' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart9' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-1'],
        'status' => ['type' => 'checkbox', 'default_v' => '', 'parentclass' => 'form-group', 'swith' => '', 'selectedon' => '1'],
        'tagend9' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart10' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-1'],
        'hit' => ['type' => 'checkbox', 'default_v' => '', 'parentclass' => 'form-group', 'swith' => '', 'selectedon' => '1'],
        'tagend10' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart11' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'cart_on' => ['type' => 'checkbox', 'default_v' => '', 'parentclass' => 'form-group', 'swith' => '', 'selectedon' => '2'],
        'tagend11' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend1' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart6' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'row'],
        'tagstart7' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'price' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'tagend7' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart8' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'old_price' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'tagend8' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart5' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'promo_id' => ['type' => 'select', 'default_v' => '', 'selectdata' => ['promo', 'title'], 'selectzero' => '', 'parentclass' => 'form-group'],
        'tagend5' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart12' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-6'],
        'category_id' => ['type' => 'select', 'default_v' => 'Ничните ввод', 'parentclass' => 'form-group', 'selectdata' => ['category', 'title']],
        'tagend12' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend6' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart13' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'row'],
        'tagstart14' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-6'],
        'related' => ['type' => 'select', 'parentclass' => 'form-group', 'selectdata' => ['product', 'title'], 'selectzero' => ''],
        'tagend14' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart15' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-4'],
        'tagend15' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstartcolor_default' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'color_default' => ['type' => 'select', 'default_v' => '', 'parentclass' => 'form-group'],
        'tagendcolor_default' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend13' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart30' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'row'],
        'tagstart31' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'alias' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group', 'name3' => '', 'label2' => 'Alias не редактируется'],
        'tagend131' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart32' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-4'],
        'saveseolink' => ['type' => 'button', 'default_v' => 'Сохранить Seolink', 'class' => 'btn btn-primary edit-seolink'],
       // 'seolink' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group', 'name3' => '', 'label2' => 'Seolink', 'id' => 'input-seolink', 'data' => ['table' => 'product', 'action' => ADMIN.'/ajax/editseolink']],
        'tagend132' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstartproduct_model' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'product_model_v' => ['type' => 'select', 'default_v' => '', 'parentclass' => 'form-group', 'name2' => 'product_model', 'label2' => 'Продукт модель - не редакт',
            'select' => ['1' => 'цвет, размер', '2' => 'цвет', '3' => 'цвет один', '4' => 'цвет один с разм']],
        'tagendproduct_model' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstartpgender' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'gender_v' => ['type' => 'select', 'default_v' => '', 'parentclass' => 'form-group', 'name2' => 'gender', 'label2' => 'Пол префикс - не редакт',
            'select' => ['Женские' => 'Женские', 'Мужские' => 'Мужские', 'Детские' => 'Детские']],
        'tagendgender' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstartsizesgroup' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-2'],
        'sizesgroup' => ['type' => 'select', 'default_v' => '', 'parentclass' => 'form-group', 'name2' => 'sizesgroup_id',
            'selectdata' => ['sizesgroup', 'title'], 'label2' => 'Группа размеров - не редакт'],
        'tagendsizesgroup' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend130' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend00' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend0' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart16' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box box-widget collapsed-box'],
        'tagstart17' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-header with-border'],
        'boxtitle1' => ['type' => 'html', 'default_v' => '<h3 class="box-title">Мета теги</h3>', 'label2' => ''],
        'boxtitlebut1' => ['type' => 'button', 'default_v' => '<i class="fa fa-plus"></i>', 'data' => ['widget' => 'collapse'], 'parentclass' => 'box-tools', 'class' => 'btn btn-box-tool', 'name3' => ''],
        'tagstart19' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-body'],
        'meta_title' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'meta_keywords' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'meta_description' => ['type' => 'textarea', 'default_v' => '', 'parentclass' => 'form-group', 'rows' => '2'],
        'tagend19' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend17' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend16' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart20' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box box-widget collapsed-box'],
        'tagstart21' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-header with-border'],
        'boxtitle2' => ['type' => 'html', 'default_v' => '<h3 class="box-title">Контент</h3>', 'label2' => ''],
        'boxtitlebut2' => ['type' => 'button', 'default_v' => '<i class="fa fa-plus"></i>', 'data' => ['widget' => 'collapse'], 'parentclass' => 'box-tools', 'class' => 'btn btn-box-tool', 'name3' => ''],
        'tagstart22' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-body'],
        'content' => ['type' => 'textarea', 'default_v' => '', 'parentclass' => 'form-group', 'id' => 'editor2'],
        'tagend22' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend21' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend20' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart46' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box box-widget collapsed-box'],
        'tagstart47' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-header with-border'],
        'boxtitle4' => ['type' => 'html', 'default_v' => '<h3 class="box-title">Фильтр</h3>', 'label2' => ''],        
        'boxtitlebut4' => ['type' => 'button', 'default_v' => '<i class="fa fa-plus"></i>', 'data' => ['widget' => 'collapse'], 'parentclass' => 'box-tools', 'class' => 'btn btn-box-tool', 'name3' => ''],
        'tagend47' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagstart49' => ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'box-body'],
        'tagend49' => ['type' => 'tagend', 'default_v' => 'div'],
        'tagend46' => ['type' => 'tagend', 'default_v' => 'div'],
        'sizesgroup_id' => ['type' => 'hidden'],
        'product_model' => ['type' => 'hidden'],
        'gender' => ['type' => 'hidden'],
        'id' => ['type' => 'hidden'],
        'aliaschek' => ['type' => 'hidden', 'name2' => 'alias'],
//    'category_id' => ['type' => 'typeahead', 'default_v' => 'Ничните ввод', 'id' => 'category_id',  'remote' => 'category,title', 'parentclass' => 'form-group', 'changid' => '', 'selectdata' => ['category', 'title']],
    ];

//['1' => 'цвет, размер', '2' => 'цвет', '3' => 'цвет один', '4' => 'цвет один с разм']
    public function editDopCategoryProduct($id, $data) {
        $category = \R::getCol('SELECT category_id FROM categoryproduct WHERE product_id = ?', [$id]);
        // если изменились категории - удалим и запишем новые
        if (!empty($data['dopcategory'])) {
            $result = array_diff($category, $data['dopcategory']);
            if (!empty($result) || count($category) != count($data['dopcategory'])) {
                $category = \R::getAssoc('SELECT category_id, product_id FROM categoryproduct WHERE product_id = ?', [$id]);
                \R::exec("DELETE FROM categoryproduct WHERE product_id = ?", [$id]);
                $sql_part = '';
                foreach ($data['dopcategory'] as $v) {
                    $v = (int) $v;
                    $sql_part .= "($id, $v),";
                    if (!isset($category[$v])) {
                        $cat = \R::load('category', $v);
                        $cat->update_at = date('Y-m-d H:i:s');
                        \R::store($cat);
                    }
                }
                $sql_part = rtrim($sql_part, ',');
                \R::exec("INSERT INTO categoryproduct (product_id, category_id) VALUES $sql_part");
            }
        }
    }

    public function editRelatedProduct($id, $data) {
        $related_product = \R::getCol('SELECT related_id FROM related_product WHERE product_id = ?', [$id]);
        // если менеджер убрал связанные товары - удаляем их
        if (empty($data['related']) && !empty($related_product)) {
            \R::exec("DELETE FROM related_product WHERE product_id = ?", [$id]);
            return;
        }
        // если добавляются связанные товары
        if (empty($related_product) && !empty($data['related'])) {
            $sql_part = '';
            foreach ($data['related'] as $v) {
                $v = (int) $v;
                $sql_part .= "($id, $v),";
            }
            $sql_part = rtrim($sql_part, ',');
            \R::exec("INSERT INTO related_product (product_id, related_id) VALUES $sql_part");
            return;
        }
        // если изменились связанные товары - удалим и запишем новые
        if (!empty($data['related'])) {
            $result = array_diff($related_product, $data['related']);
            if (!empty($result) || count($related_product) != count($data['related'])) {
                \R::exec("DELETE FROM related_product WHERE product_id = ?", [$id]);
                $sql_part = '';
                foreach ($data['related'] as $v) {
                    $v = (int) $v;
                    $sql_part .= "($id, $v),";
                }
                $sql_part = rtrim($sql_part, ',');
                \R::exec("INSERT INTO related_product (product_id, related_id) VALUES $sql_part");
            }
        }
    }

    public function editFilter($id, $data) {
        $filter = \R::getAssoc('SELECT attrgr_id, bitmask FROM attribute_product WHERE product_id = ?', [$id]);
        $dataAttrs = [];
        $dataCompare = [];
        $dataTemp = $data['attrs'] ?? [];
        if ($dataTemp) {
            $bitvaluedata = $data['bitvaluedata'] ?? false;
            if ($bitvaluedata) {
                $bitvaluedata = unserialize($bitvaluedata);
            }
            $newdataTemp = [];
            foreach ($dataTemp as $k => $v) {
                if (is_array($v)) {
                    $temp = 0;
                    foreach ($v as $valitem) {
                        $item = $bitvaluedata[$k][$valitem] ?? '';
                        if ($item) {
                            $item = bindec($item);
                        }
                        $temp += $item;
                    }
                    if ($temp) {
                        $newdataTemp[$k] = $temp;
                    }
                } elseif ($v) {
                    $item = $bitvaluedata[$k][$v] ?? '';
                    if ($item) {
                        $item = bindec($item);
                    }
                    $newdataTemp[$k] = $item;
                }
            }

            foreach ($newdataTemp as $k => $v) {
                $dataAttrs[$k]['product_id'] = $id;
                $dataAttrs[$k]['attrgr_id'] = $k;
                $dataAttrs[$k]['bitmask'] = $v;
                $dataCompare[$k] = $v;
            }
        }

        // если менеджер убрал фильтры - удаляем их
        if (!$dataAttrs && !empty($filter)) {
            \R::exec("DELETE FROM attribute_product WHERE product_id = ?", [$id]);
            return;
        }
        // если фильтры добавляются
        if (empty($filter) && $dataAttrs) {

            foreach ($dataAttrs as $v) {
                $this->saveData('attribute_product', $v, false);
            }

            return;
        }

        // если изменились фильтры - удалим и запишем новые
        if ($dataAttrs) {
            //   debug ($dataAttrs);
            // die;
            foreach ($filter as $k => $v) {
                if (isset($dataCompare[$k]) && ($dataCompare[$k] == $filter[$k])) {
                    unset($dataCompare[$k]);
                    unset($filter[$k]);
                }
            }
            if (!$filter && !$dataCompare)
                return;

            \R::exec("DELETE FROM attribute_product WHERE product_id = ?", [$id]);
            foreach ($dataAttrs as $v) {
                $this->saveData('attribute_product', $v, false);
            }
        }
    }

    public static function cacheIdsClear() {
        $cache = Cache::instance();
        $cache->delete('product_ids');
    }

    public static function changeStatus($status, $id) {
        If ($status == '1') {
            $class = true;
        } elseif ($status == '2') {
            $class = false;
        } else {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'такого статуса не сещуствует'];
            return $res;
        }
        $product = \R::load('product', $id);
        if (!$product) {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'такого продукта не сещуствует'];
            return $res;
        }
        $product->status = $status;
        \R::store($product);
        self::cacheIdsClear();
        $res = ['succes' => true, 'title' => 'Статус продукта изменен', 'message' => "Статус продукта $id изменен", 'class' => $class];
        return $res;
    }

}
