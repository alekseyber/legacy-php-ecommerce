<?php

namespace app\widgets\filter;

use ishop\Cache;
use ishop\App;
use ishop\libs\URL;
use ishop\Link;

class Filter {

    public $uri = '';
    public $ids = '';
    public $color = false;
    public $colorSql = '';
    public $valGetTitle = []; //Титлы принятых GET параметров
    public $valFactTitle = []; //Титлы найденых товаров
    public $valTagsAttr = []; //Теги найденных атрибутов фильтров
    public $valTagsHtml = ''; //Теги найденных атрибутов фильтров в виде html
    public $getPrice = ['pricefr' => 0, 'priceto' => 0];
    public $js = '';
    public $count = 0;
    public $countGr = 0;
    protected $tpl = __DIR__ . '/filter_tpl.php';
    protected $check = true;
    protected $status = true;
    protected $oneproduct = false;
    protected $cartproduct = false;
    protected $cartattron = false;
    protected $count_col = 1;
    protected $col_auto = false;
    protected $pclasspole = [];
    protected $delen = 0.2;
    protected $cache = true;
    protected $formprocessor = true;
    protected $groups = [];
    protected $attrs = [];
    protected $colorAttrs = [];
    protected $sizesAttrs = [];
    protected $getAttrs = [];
    protected $clearLink;
    protected $filterGet = [];
    public $filterIds = [];
    public $filter = '';
    protected $sizesSql = '';
    protected $idsSql = '';
    protected $idsNoBynarySql = '';
    protected $idsSqlcountGr = 0;
    protected $currency = [];
    protected $pricejs = [];
    protected $priceLimit = [];
    protected $tablenobinary = [];
    public $inputs = [];
    protected $selected = [];
    protected $bitvalue = [];

//public $valGetTitle = ['color' => '', 'filter' => [], 'price' => ['pricefr' => 0, 'priceto' => 0]]; //Титлы принятых GET параметров
//public $valFactTitle = ['color' => '', 'filter' => [], 'price' => ['pricefr' => 0, 'priceto' => 0], 'count' => ['product' => 0, 'modif' => 0]]; //Титлы найденых товаров


    public function __construct($params = []) {

        if ($params && is_array($params)) {

            foreach ($params as $key => $param) {
                switch ($key) {
                    case 'ids':
                        if ($param) {
                            $this->ids = $param;
                        }
                        break;
                    case 'uri':
                        if ($param) {
                            $this->uri = $param;
                        }
                        break;
                    case 'tpl':
                        if ($param) {
                            $this->tpl = $param;
                        }
                        break;
                    case 'check':
                        if (!$param) {
                            $this->check = false;
                        }
                        break;
                    case 'oneproduct':
                        if ($param) {
                            $this->oneproduct = true;
                        }
                        break;
                    case 'cartproduct':
                        if ($param) {
                            $this->cartproduct = true;
                        }
                        break;
                    case 'status':
                        if (!$param) {
                            $this->status = false;
                        }
                        break;
                    case 'cartattron':
                        if ($param) {
                            $this->cartattron = true;
                        }
                        break;
                    case 'count_col':
                        if ((int) $param) {
                            $this->count_col = (int) $param;
                        }
                        break;
                    case 'col_auto':
                        if ($param) {
                            $this->col_auto = true;
                        }
                        break;
                    case 'delen':
                        if ((float) $param) {
                            $this->delen = (float) $param;
                        }
                        break;
                    case 'cache':
                        if (!$param) {
                            $this->cache = false;
                        }
                        break;
                    case 'formprocessor':
                        if (!$param) {
                            $this->formprocessor = false;
                        }
                        break;
                }
            }
        }

// $ids = '', $uri = '', $tpl = '', $check = true, $oneproduct = false, $cartproduct = false, $status = true, 
// $cartattron = false, $count_col = 0, $col_auto = false, $delen = 0, $cache = true
        $this->currency = App::$app->getProperty('currency');
        $this->run();
    }

    protected function run() {
        $cache = Cache::instance();
        $cachetime = App::$app->getProperty('cachetime_filter');
        $set_cache = false;
        $cache_key = '';

        if (!$this->oneproduct && $this->cache) {
            $set_cache = true;
            $count_col = ($this->count_col - 1) * 1000;
            $col_auto = $this->col_auto ? 'true' : 'false';
            $cache_key = 'filterendrezult' . $count_col . $col_auto . $this->tpl;

            $data_filter = $cache->get($cache_key, 'filter');
            if ($data_filter) {
                $this->filter = $data_filter['filter'];
                $this->js = $data_filter['js'];
                $this->tpl = $data_filter['tpl'];
                $this->count_col = $data_filter['count_col'];
                $this->pricejs = $data_filter['pricejs'];
                $this->valFactTitle = $data_filter['valFactTitle'];
                return true;
            }
        }

        $oneproduct = $this->oneproduct ? 'oneproduct' : '';
        $cartproduct = $this->cartproduct ? 'cartproduct' : '';
        $cartattron = $this->cartproduct ? 'cartattron' : '';
        $status = $this->status ? 'statustrure' : '';
        $check = $this->check ? 'checktrure' : '';
        $cache_key_in = $oneproduct . $cartproduct . $cartattron . $status . $check;

        $tablearr = ['tablenobinary', 'pclasspole'];
        foreach ($tablearr as $table) {
            $this->$table = $cache->get('filter_group' . $table, 'filter');
            if (!$this->$table) {
                $count = \R::exec("SHOW TABLES LIKE '$table'");
                if ($count) {
                    $this->$table = \R::getAssoc("SELECT id, name FROM $table");
                } else {
                    $this->$table = ['id' => 0, 'name' => 'id'];
                }
                $cache->set('filter_group' . $table, $this->$table, $cachetime, 'filter');
            }
        }
        $this->groups = $cache->get('filter_group' . $cache_key_in, 'filter');
//$this->groups = [];
        if (!$this->groups) {
            $this->groups = $this->getGroups();
            $this->getAttrs();
            $cache->set('filter_group' . $cache_key_in, $this->groups, $cachetime, 'filter');
        }

        if ($this->check && !$this->oneproduct) {

            if (!$this->uri) {
                $this->getParams();
            }
            if ($this->ids) {
                $this->getIds();
            }
        }

        if ($this->ids) {
            $this->getGroupsChek();
        }

        $this->getFilterEnd();

        if ($this->uri && $this->ids) {
            $this->getFilterGrEnd();
            $this->getFilterLink();
        }

        if (!$this->oneproduct && $this->ids) {
            $this->js = $this->getPriceJs();

            if ($set_cache) {
                $data_filter['filter'] = $this->filter;
                $data_filter['js'] = $this->js;
                $data_filter['tpl'] = $this->tpl;
                $data_filter['count_col'] = $this->count_col;
                $data_filter['pricejs'] = $this->pricejs;
                $data_filter['valFactTitle'] = $this->valFactTitle;
                $cache->set($cache_key, $data_filter, $cachetime, 'filter');
            }
        }


        if ($this->valTagsAttr) {
            $this->valTagsHtml = $this->getTagsHtml();
        }
    }

    protected function getTagsHtml() {
        $tpl = __DIR__ . '/filtertags_tpl.php';
        ob_start();
        $valTagsAttrs = $this->valTagsAttr;
        require $tpl;
        return ob_get_clean();
    }

    public function getHtml() {
        if (!$this->tpl) {
            return false;
        }
        ob_start();
        if ($this->count_col > 1) {
            $filter = $this->getFilterCols();
        } else {
            $filter = $this->filter;
        }
        extract($this->pricejs);
        $valFactTitle = $this->valFactTitle;
        $valGetTitle = $this->valFactTitle;
        require $this->tpl;
        return ob_get_clean();
    }

    public function __toString() {
        return $this->getHtml();
    }

    protected function getGroups() {
        $sql_part = '';
        if ($this->oneproduct) {
            $status = $this->status ? ' AND status = 1' : '';

            $cartproduct = $this->cartproduct ? ' AND cartproduct = 1' : '';
            $sql_part = 'WHERE color = 0 AND sizes = 0' . $status . $cartproduct;
        } else {
            $sql_part = $this->check ? 'WHERE filter = 1 AND status = 1' : '';
        }
        // $data = \R::getAssoc("SELECT id, alias, title, color, sizes, filter, status, cartproduct, tablegrupp, tablenpbinary FROM attribute_group $sql_part ORDER BY filter, sortvalue");
        $data = \R::getAssoc("SELECT * FROM attribute_group $sql_part ORDER BY sortvalue");
        if (!$this->check) {
            foreach ($data as &$val) {
                $polenpbinary_id = $val['polenpbinary_id'] ?? '';
                if ($polenpbinary_id) {
                    $pclasspole = $this->pclasspole[$polenpbinary_id] ?? 'empty';
                    $val['pclasspole'] = $pclasspole;
                }
            }
        }

        return $data;
    }

    protected function getAttrs() {
        $where = '';
        $where = $this->status ? 'status = 1 AND ' : '';
        $where .= $this->cartattron ? 'carton = 1' : '';
        $where = rtrim($where, ' AND ');
        if ($where) {
            $where = " WHERE $where ";
        }
        if ($this->cartproduct) {
            $select = 'SELECT *, BIN(1 << `sdvig`) AS bitvalue';
        } else {
            $select = 'SELECT `id`, `value`, `attr_group_id`, `sdvig`, BIN(1 << `sdvig`) AS bitvalue, `sortvalue`, `status`, `tablegr_id`, `tags`';
        }
        $attrs = [];
        $attribute_value = \R::getAssoc("$select FROM attribute_value" . $where . ' ORDER BY sortvalue');

        if ($attribute_value) {
            foreach ($attribute_value as $k => $v) {
                $new_attribute_value[$v['attr_group_id']][$k] = $v;
            }
        }
        if ($this->check) {
            $where = $where ? $where . 'AND nullvalue = 0 ' : ' WHERE nullvalue = 0 ';
        }
        foreach ($this->groups as $k => &$v) {
            if ($v['color'] || $v['sizes']) {
                if (!$this->check && $this->oneproduct) {
                    unset($this->groups[$k]);
                    continue;
                }
                $v['attrs'] = $this->getColorAttrs($v['color']);
                continue;
            }
            if ($v['tablenpbinary']) {
                $tablenobinary = $this->tablenobinary[$v['tablenpbinary']] ?? '';
                $pclasspole = $this->pclasspole[$v['polenpbinary_id']] ?? 'id';
                if (!$tablenobinary || !$pclasspole) {
                    continue;
                }

                $data = \R::getAssoc("SELECT * FROM $tablenobinary" . $where . ' ORDER BY sortvalue');

                $attrs[$k] = $data ?? [];
                $filter_rustitle = App::$app->getProperty('filter_rustitle');
                foreach ($attrs[$k] as &$item) {
                    if ($filter_rustitle) {
                        $item['value'] = $item['title_rus'] ?? $item['title'];
                        if (!$item['value']) {
                            $item['value'] = $item['title'];
                        }
                    } else {
                        $item['value'] = $item['title'];
                    }
                    if (!$this->check) {
                        $title_rus = (isset($item['title_rus']) && $item['title_rus']) ? ' | ' . $item['title_rus'] : '';
                        $item['value'] = $item['title'] . $title_rus;
                        if ($v['tablegrupp']) {
                            $tablegr_id_val = (isset($item['tablegr_id'])) ? ' | GR: ' . $item['tablegr_id'] : '';
                            $item['value'] .= $tablegr_id_val;
                            $tablegr_id = $item['tablegr_id'] ?? 0;
                            if ($tablegr_id) {
                                $item['value'] .= ' | ' . $this->getNameAttrKod($v['tablegrupp'], $k, $v['alias'], $v['tablenpbinary'], $tablegr_id);
                            }
                        }
                    }
                }
            } else {
                if (!empty($new_attribute_value[$k])) {
                    $attrs[$k] = $new_attribute_value[$k] ?? [];
                }
            }

            $v['attrs'] = $attrs[$k] ?? [];
        }

        return true;
    }

    protected function getColorAttrs($tip = false) {
        $tip = !$tip ? 'sizes' : 'color';
        $tipparams = $tip . 'Attrs';

        if ($this->$tipparams)
            return $this->$tipparams;

        $cache = Cache::instance();
        $cachetime = \ishop\App::$app->getProperty('cachetime_filter');

        $attrs = $cache->get("filter_{$tip}attrs", 'filter');
        if (!$attrs) {
            $title = 'title';
            $filter_colorrustitle = \ishop\App::$app->getProperty('filter_colorrustitle');
            if ($filter_colorrustitle && $tip == 'color')
                $title = 'rustitle AS title';

            $data = \R::getAssoc("SELECT id, $title, alias, tablegr_id, tags FROM $tip ORDER BY sortvalue");

            $attrs = [];
            foreach ($data as $k => $v) {
                $attrs['key'][$k] = ['title' => $v['title'], 'alias' => $v['alias'], 'tablegr_id' => $v['tablegr_id'], 'tags' => $v['tags']];
                $attrs['alias'][$v['alias']] = ['title' => $v['title'], 'key' => $k, 'tablegr_id' => $v['tablegr_id'], 'tags' => $v['tags']];
            }

            $cache->set("filter_{$tip}attrs", $attrs, $cachetime, 'filter');
        }

        $this->$tipparams = $attrs;

        return $attrs;
    }

    protected function getIds() {
        $ids = $this->ids;
        $idsNoBynarySql = $this->idsNoBynarySql;
        $idsNoPrice = $this->ids;
        $wherePrice = '';
        $this->priceLimit['pricefr'] = 0;
        $this->priceLimit['priceto'] = 0;

        if ($ids && $idsNoBynarySql) {
            $sql = "SELECT `id` FROM `productclass` WHERE (id IN ($ids)) AND ($idsNoBynarySql)";
            // echo $ids;
            $ids = \R::getCol($sql);
            $ids = array_unique($ids);
            $ids = $ids ? implode(',', $ids) : '';
        }

        if ($ids) {
            $whereIds = " AND (product_id IN ($ids))";
            $idsSql = $this->idsSql ? "({$this->idsSql})" : '';

            $pricefr = $this->getPrice['pricefr'] / $this->currency['value'];
            $priceto = $this->getPrice['priceto'] / $this->currency['value'];

            $wherePrice = ($pricefr > 0) ? "price >= $pricefr AND " : '';
            $wherePrice .= ($priceto > 0) ? "price <= $priceto" : '';

            $wherePrice = trim($wherePrice, ' AND ');
            $wherePrice = $wherePrice ? "($wherePrice)" : '';

            if ($wherePrice || $idsSql) {
                $where = $idsSql . ($idsSql ? ' AND ' : '');
                if (!$wherePrice)
                    $where = rtrim($where, ' AND ');


                $wherePr = trim($where, ' AND ') . $whereIds;
                $wherePr = ' WHERE ' . trim($wherePr, ' AND ');

                $where = ' WHERE ' . $where . $wherePrice . $whereIds;

                if ($idsSql) {
                    $sql_part = 'SELECT product_id';
                    $sql_part .= ' FROM attribute_product';
                    $sql_partNoPr = $sql_part;
                    $sql_part .= $wherePrice ? ' JOIN product ON attribute_product.product_id = product.id' : '';
                    $sql_part .= $where;
                    $sql_partNoPr = $sql_partNoPr . $wherePr;

                    if ($this->idsSqlcountGr) {
                        $sql_part .= ' GROUP BY product_id HAVING COUNT(product_id) = ' . $this->idsSqlcountGr;
                        $sql_partNoPr .= ' GROUP BY product_id HAVING COUNT(product_id) = ' . $this->idsSqlcountGr;
                    }
                } else {
                    $sql_part = 'SELECT id';
                    $sql_part .= ' FROM product';
                    $sql_partNoPr = $sql_part;
                    $sql_part .= $where;
                    $sql_part = preg_replace('#product_id#', 'id', $sql_part);
                    $sql_partNoPr = $sql_partNoPr . $wherePr;
                    $sql_partNoPr = preg_replace('#product_id#', 'id', $sql_partNoPr);
                }


                $ids = \R::getCol($sql_part);
                $ids = array_unique($ids);
                $ids = $ids ? implode(',', $ids) : '';


                if ($wherePrice) {

                    $idsNoPrice = \R::getCol($sql_partNoPr);
                    $idsNoPrice = array_unique($idsNoPrice);
                    $idsNoPrice = $idsNoPrice ? implode(',', $idsNoPrice) : '';
                }
            }

            $this->valFactTitle['count']['product'] = $ids ? count(explode(',', $ids)) : 0;

            if ($ids && $this->sizesSql) {
                $sizesSqlArr = explode(',', $this->sizesSql);
                $sizesSqlArr = count($sizesSqlArr);

                $sql_part = 'SELECT product.id';
                $sql_part .= ' FROM product';
                $sql_part .= ' JOIN modification ON product.id = modification.product_id';
                $sql_part .= ' JOIN modificationregistr ON modification.id = modificationregistr.modification_id';
                $sql_part .= ' WHERE modification.status = 1 AND';
                $sql_part .= " (modificationregistr.sizes_id IN ({$this->sizesSql}))";
                if ($wherePrice) {
                    $sql_partNoPrice = $sql_part . " AND modificationregistr.id IN (SELECT modificationregistr_id FROM `registr` WHERE product_id IN ($idsNoPrice) AND amount > 0)";
                    $sql_partNoPrice .= " AND (product.id IN ($idsNoPrice))";
                }
                $sql_part .= " AND modificationregistr.id IN (SELECT modificationregistr_id FROM `registr` WHERE product_id IN ($ids) AND amount > 0)";
                $sql_part .= " AND (product.id IN ($ids))";

//$sql_part .= " GROUP BY product_id HAVING COUNT(modification.id) = 1";
//echo $sql_part . '========<br>';
                $ids = \R::getCol($sql_part);
                $this->valFactTitle['count']['sizes'] = count($ids);
                $temp_arr = array_unique($ids);
                $this->valFactTitle['count']['product'] = count($temp_arr);

                $ids = array_unique($ids);
                $ids = $ids ? implode(',', $ids) : '';
                if ($wherePrice) {
                    $idsNoPrice = \R::getCol($sql_partNoPrice);
                    $idsNoPrice = array_unique($idsNoPrice);
                    $idsNoPrice = $idsNoPrice ? implode(',', $idsNoPrice) : '';
                }
            }

            if ($ids) {
                $colorSqlArr = $this->colorSql ? explode(',', $this->colorSql) : [];
                $colorSqlArr = $colorSqlArr ? count($colorSqlArr) : '';

                $sql_part = 'SELECT product.id';
                $sql_part .= ' FROM product';
                $sql_part .= ' JOIN modification ON product.id = modification.product_id';
                $sql_part .= $this->colorSql ? ' JOIN color ON modification.color_id = color.id' : '';
                $sql_part .= ' WHERE ';
                $sql_part .= $this->colorSql ? "(color.id IN ({$this->colorSql})) AND " : '';

                if ($wherePrice) {
                    $sql_partNoPrice = $sql_part . "modification.status = 1 AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id IN ($idsNoPrice) AND amount > 0) AND ";
                    $sql_partNoPrice .= "(product.id IN ($idsNoPrice))";
                }
                $sql_part = $sql_part . "modification.status = 1 AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id IN ($ids) AND amount > 0) AND ";
                $sql_part .= "(product.id IN ($ids))";

//$sql_part .= $this->colorSql ? " GROUP BY product_id HAVING COUNT(product.id) = $colorSqlArr" : '';

                $ids = \R::getCol($sql_part);
                $this->valFactTitle['count']['modif'] = count($ids);
                $this->valFactTitle['count']['product'] = $this->colorSql ? count($ids) : $this->valFactTitle['count']['product'];

                $ids = array_unique($ids);
                $ids = $ids ? implode(',', $ids) : '';

                if ($wherePrice) {
                    $idsNoPrice = \R::getCol($sql_partNoPrice);
                    $idsNoPrice = array_unique($idsNoPrice);
                    $idsNoPrice = $idsNoPrice ? implode(',', $idsNoPrice) : '';
                }
            } else {
                $this->valFactTitle['count']['modif'] = 0;
                $this->valFactTitle['count']['product'] = 0;
            }

            if ($ids) {
                $sql_part = 'SELECT MIN(price) AS pricefr, MAX(price) AS priceto';
                $sql_part .= ' FROM product';
                if ($wherePrice) {
                    $sql_partNoPrice = $sql_part . " WHERE (id IN ($idsNoPrice)) LIMIT 1";
                }
                $sql_part .= " WHERE (id IN ($ids)) LIMIT 1";

                $price = \R::getRow($sql_part);
                if ($price) {
                    $this->valFactTitle['price'] = $price;
                    $this->valFactTitle['price']['pricefr'] = $this->valFactTitle['price']['pricefr'] * $this->currency['value'];
                    $this->valFactTitle['price']['priceto'] = $this->valFactTitle['price']['priceto'] * $this->currency['value'];
                    if ($wherePrice) {
                        $price = \R::getRow($sql_partNoPrice);
                        $this->priceLimit['pricefr'] = $this->priceLimit['pricefr'] * $this->currency['value'];
                        $this->priceLimit['priceto'] = $this->priceLimit['priceto'] * $this->currency['value'];
                    }
                    $this->priceLimit = $price;
                }
            }
        } else {
            $this->valFactTitle['count']['product'] = 0;
        }
        $this->ids = $ids;
        return true;
    }

    protected function getGroupsChek() {
        $status = $this->status ? 'AND attribute_group.status = 1' : '';
        $filter = !$this->oneproduct ? 'AND attribute_group.filter = 1' : '';

        $sql_part = 'SELECT attrgr_id, alias, BIT_OR(bitmask) AS bitmask';
        $sql_part .= ' FROM attribute_product JOIN attribute_group ON attribute_group.id = attribute_product.attrgr_id';
        $sql_part .= " WHERE product_id IN ($this->ids) $filter $status";
        $sql_part .= " GROUP BY attrgr_id";
        $data = \R::getAssoc($sql_part);

        foreach ($this->groups as $k => &$v) {

            if (isset($data[$k])) {
                $v['bitmask'] = $data[$k]['bitmask'];
                $bitmask = decbin($v['bitmask']);

                foreach ($v['attrs'] as $key => &$val) {
                    $val['title'] = $val['value'];
                    $bitvalue = $val['bitvalue'];
                    $tempsdvig = iconv_strlen($bitmask) - iconv_strlen($bitvalue);
                    if ($tempsdvig >= 0) {
                        $temp = '';
                        for ($i = 0; $i < $tempsdvig; $i++)
                            $temp .= '0';
                        $bitvalue = $temp . $bitvalue;
                    } else {
                        continue;
                    }
                    if (bindec($bitmask & $bitvalue)) {
                        $val['on'] = true;
                        $v['select'] = true;
                        $this->selected[$k][$key] = $key;
                    }
                }
                continue;
            }

            if ($this->check && $v['color']) {
                $sql_part = 'SELECT DISTINCT color.id FROM product';
                $sql_part .= ' JOIN modification ON product.id = modification.product_id';
                $sql_part .= ' JOIN color ON modification.color_id = color.id';
                $sql_part .= " WHERE product.id IN ({$this->ids}) AND modification.status = 1";
                $sql_part .= " AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id IN ({$this->ids}) AND amount > 0)";
                $colorSelect = \R::getCol($sql_part);
                $v['bitmask'] = $colorSelect;
                if ($v['bitmask']) {
                    foreach ($v['bitmask'] as $valcolor) {
                        if (isset($v['attrs']['key'][$valcolor])) {
                            $v['attrs']['key'][$valcolor]['on'] = true;
                            $v['select'] = true;
                        }
                    }
                }
                continue;
            }

            if ($this->check && $v['sizes']) {
                $sql_part = 'SELECT DISTINCT modificationregistr.sizes_id FROM product';
                $sql_part .= ' JOIN modification ON product.id = modification.product_id';
                $sql_part .= ' JOIN modificationregistr ON modification.id = modificationregistr.modification_id';
                $sql_part .= " WHERE product.id IN ({$this->ids})";
                $sql_part .= " AND modificationregistr.id IN (SELECT modificationregistr_id FROM `registr` WHERE product_id IN ({$this->ids}) AND amount > 0)";
                $sizesSelect = \R::getCol($sql_part);
                $v['bitmask'] = $sizesSelect;
                if ($v['bitmask']) {
                    foreach ($v['bitmask'] as $valsizes) {
                        if (isset($v['attrs']['key'][$valsizes])) {
                            $v['attrs']['key'][$valsizes]['on'] = true;
                            $v['select'] = true;
                        }
                    }
                }
                continue;
            }

            if (!$v['tablenpbinary'] || !$v['polenpbinary_id']) {
                unset($this->groups['$k']);
                continue;
            }
            $pclasspole = $this->pclasspole[$v['polenpbinary_id']] ?? 'id';
            if (!$pclasspole) {
                unset($this->groups['$k']);
                continue;
            }

            $where = "id IN ($this->ids)";
            if ($this->check) {
                $where .= " AND $pclasspole > 1";
            }
            $count = \R::count('productclass', $where);
            if ($count) {
                $sql = "SELECT DISTINCT $pclasspole FROM productclass WHERE " . $where;
                $v['attr_ids'] = \R::getCol($sql);
                foreach ($v['attr_ids'] as $valueitem) {
                    if (isset($v['attrs'][$valueitem])) {
                        $v['attrs'][$valueitem]['on'] = true;
                        $v['select'] = true;
                        $this->selected[$k][$valueitem] = $valueitem;
                    }
                }
            }
        }
        $this->filterIds = $this->groups;
        return true;
    }

    protected function getParams() {

        $getArr = App::$app->getProperty('chek_get');
        $uri = App::$app->getProperty('chek_uri');
        $base_uri = Link::getLink(App::$app->getProperty('filter_default_uri'));
        $start_uri = $base_uri;
        $start_ankor = '';

        $uri = URL::deletePage($uri);
        $clearLink = Link::getLink($uri);
        $idsSql = '';
        $idsNoBynarySql = [];
        $valGetTitle = [];
        $arr_get_selected = [];
        foreach ($this->groups as $k => &$v) {

            if (isset($getArr[$v['alias']])) {
                $val = $getArr[$v['alias']];

                //  $attrs = ($v['color'] || $v['sizes']) ? $this->getColorAttrs($color) : $v['attrs'];

                $uri = URL::deleteGET($uri, $v['alias']);
                $clearLink = URL::deleteGET($clearLink, $v['alias']);

                $dataGet = [];
                $dataGet = explode(',', $getArr[$v['alias']]);

                if ($v['tablegrupp']) {
                    $arr_get_no_tablegrupp = $dataGet;
                    $alias_on = false;
                    if ($v['color'] || $v['sizes']) {
                        $alias_on = true;
                    }

                    $dataGet = $this->getGetNoGr($v['tablegrupp'], $dataGet, $v['attrs'], $k, $v['alias'], $v['tablenpbinary'], $alias_on);
                    foreach ($arr_get_no_tablegrupp as $arr_get_no_tablegrupp_item) {

                        $itemtag = $this->getGetAttrTag($v['tablegrupp'], $v['tablenpbinary'], $k, $alias_on, $arr_get_no_tablegrupp_item);
                        if ($itemtag) {
                            $start_uri = URL::addGET($start_uri, $v['alias'], $arr_get_no_tablegrupp_item);
                            $start_ankor .= $itemtag . ' ';
                            $arr_get_selected[] = [
                                'ankor' => $itemtag,
                                'link' => urldecode(URL::addGET($base_uri, $v['alias'], $arr_get_no_tablegrupp_item))
                            ];
                        }
                    }
                    $val = implode(',', $dataGet);
                }

                $get = '';
                $bitsumm = 0;
                $selectAttrs = false;
                $valGetTitle['filter'][$v['title']] = '';


                foreach ($dataGet as $value) {
                    $tipparams = '';
                    $tip = '';
                    if ($v['color'] || $v['sizes']) {
                        $tip = $v['sizes'] ? 'sizes' : 'color';
                        $tipparams = $tip . 'Sql';
                        if (!isset($valGetTitle[$tip]))
                            $valGetTitle[$tip] = '';
                        if (isset($v['attrs']['alias'][$value])) {
                            $selectAttrs = true;
                            $kattrscolor = $v['attrs']['alias'][$value]['key'];
                            $v['attrs']['key'][$kattrscolor]['active'] = true;
                            $v['select'] = true;
                            $get .= $value . ',';
                            $this->$tipparams .= $v['attrs']['alias'][$value]['key'] . ',';
                            $valGetTitle[$tip] .= $v['attrs']['key'][$v['attrs']['alias'][$value]['key']]['title'] . ', ';
                            if (!$v['tablegrupp']) {
                                if ($v['attrs']['key'][$v['attrs']['alias'][$value]['key']]['tags']) {
                                    $start_uri = URL::addGET($start_uri, $v['alias'], $v['attrs']['key'][$v['attrs']['alias'][$value]['key']]['alias']);
                                    $start_ankor .= $v['attrs']['key'][$v['attrs']['alias'][$value]['key']]['tags'] . ' ';
                                    $arr_get_selected[] = [
                                        'ankor' => $v['attrs']['key'][$v['attrs']['alias'][$value]['key']]['tags'],
                                        'link' => urldecode(URL::addGET($base_uri, $v['alias'], $v['attrs']['key'][$v['attrs']['alias'][$value]['key']]['alias']))
                                    ];
                                }
                            }
                        }
                    } else {
                        if (isset($v['attrs'][$value]) && is_array($v['attrs'][$value])) {
                            $v['attrs'][$value]['active'] = true;
                            $v['select'] = true;
                            $selectAttrs = true;
                            $get .= $value . ',';
                            if ($v['tablenpbinary']) {
                                $pclasspole = $this->pclasspole[$v['polenpbinary_id']] ?? '';
                                if ($pclasspole) {
                                    $idsNoBynarySql[$pclasspole] = $idsNoBynarySql[$pclasspole] ?? '';
                                    $idsNoBynarySql[$pclasspole] .= $value . ',';
                                }
                            } else {
                                $bitsumm += (1 << $v['attrs'][$value]['sdvig']);
                            }

                            $valGetTitle['filter'][$v['title']] .= $v['attrs'][$value]['value'] . ', ';
                            if ($v['attrs'][$value]['tags']) {
                                $start_uri = URL::addGET($start_uri, $v['alias'], $value);                                  
                                $start_ankor .= $v['attrs'][$value]['tags'] . ' ';
                                $arr_get_selected[] = [
                                    'ankor' => $v['attrs'][$value]['tags'],
                                    'link' => urldecode(URL::addGET($base_uri, $v['alias'], $value))
                                ];
                            }
                        }
                    }
                }
                $v['get'] = '';

                if ($selectAttrs) {
                    $get = $get ? rtrim($get, ',') : '';
                    if ($tipparams)
                        $this->$tipparams = $this->$tipparams ? rtrim($this->$tipparams, ',') : '';
                    if ($tip)
                        $valGetTitle[$tip] = $valGetTitle[$tip] ? rtrim($valGetTitle[$tip], ', ') : '';

                    $valGetTitle['filter'][$v['title']] = $valGetTitle['filter'][$v['title']] ? rtrim($valGetTitle['filter'][$v['title']], ', ') : '';
                    if (!$valGetTitle['filter'][$v['title']])
                        unset($valGetTitle['filter'][$v['title']]);

                    if ($bitsumm) {
                        $idsSql .= "(attrgr_id = $k AND bitmask & $bitsumm) OR ";
                        $this->idsSqlcountGr++;
                    }
                    $v['get'] = $get;

                    if ($v['tablegrupp'] && $get) {
                        $alias_on = false;
                        if ($v['color'] || $v['sizes']) {
                            $alias_on = true;
                        }
                        $get = $this->getGetGrupp($v, $k, $alias_on);
                        $v['get'] = $get;
                    }
                    $uri = URL::addGET($uri, $v['alias'], $get);
                }
            }
        }

        $this->color = $this->colorSql ? true : false;
        $idsSql = $idsSql ? trim($idsSql, ' OR ') : '';

        if ($idsNoBynarySql) {
            $rezultNoByn = '';
            foreach ($idsNoBynarySql as $keys => $item) {
                $rezultNoByn .= "$keys IN (" . rtrim($item, ',') . ') AND ';
            }
            $idsNoBynarySql = rtrim($rezultNoByn, ' AND ');
        } else {
            $idsNoBynarySql = '';
        }

        foreach ($this->getPrice as $keys => $item) {
            $get = 0;
            if (!empty($getArr[$keys])) {
                $get = $getArr[$keys];
                $uri = URL::deleteGET($uri, $keys);
                $clearLink = URL::deleteGET($clearLink, $keys);
                if ($get > 0) {
                    $this->getPrice[$keys] = $get;
                    $uri = URL::addGET($uri, $keys, $get);
                    $valGetTitle['price'][$keys] = $get;
                }
            }
        }

        $this->idsSql = $idsSql;
        $this->idsNoBynarySql = $idsNoBynarySql;
        $this->valGetTitle = $valGetTitle;
        $this->filterGet = $this->groups;
        $this->clearLink = urldecode($clearLink);
        $this->uri = urldecode($uri);
        if ($arr_get_selected) {
            $prefix_tags = App::$app->getProperty('prefix_tags');
            $start_ankor = trim($start_ankor);
            if (count($arr_get_selected) == 1) {
                $arr_get_selected[0]['ankor'] = $prefix_tags . $arr_get_selected[0]['ankor'];
            } else {
                $arr_get_selected[] = [
                    'ankor' => $prefix_tags . $start_ankor,
                    'link' => urldecode($start_uri)
                ];
            }


            $this->valTagsAttr = $arr_get_selected;
        }
        return true;
    }

    protected function getFilterEnd() {

        $count = 0;
        if (!$this->check && $this->formprocessor && $this->oneproduct) {
            $this->inputs['tagstart_filter'] = ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'filter-adm-wrap'];
        }
        foreach ($this->groups as $k => &$v) {
            if (!isset($v['attrs']) || !$v['attrs']) {
                unset($this->groups[$k]);
                continue;
            }
            if (!$this->check && $this->oneproduct) {
                if ($v['color'] || $v['sizes']) {
                    unset($this->groups[$k]);
                    continue;
                }
            }
            if ($this->check && !isset($v['select'])) {
                unset($this->groups[$k]);
                continue;
            }

            $tip = '';
            if ($v['color'] || $v['sizes']) {
                $v['attrs'] = $v['attrs']['key'];
                $tip = $v['sizes'] ? 'sizes' : 'color';
            }
            if (!$this->check && $this->formprocessor && $this->oneproduct && !$v['color'] && !$v['sizes']) {
                $this->inputs['tagstart_' . $v['alias']] = ['type' => 'tagstart', 'default_v' => 'div', 'class' => 'col-md-6 item-filter'];
                $nameattr = '';
                if ($v['tablenpbinary']) {
                    $pclasspole = $v['pclasspole'] ?? '';
                    $nameattr = "pclass[$pclasspole]";
                    $valnoselected = 1;
                } else {
                    $nameattr = "attrs[$k]";
                    $valnoselected = 0;
                }
                if ($v['radio']) {
                    $this->inputs[$nameattr]['type'] = 'select';
                } else {
                    $this->inputs[$nameattr]['type'] = 'selectmultiple';
                }
                $this->inputs[$nameattr]['name'] = $nameattr;
                $this->inputs[$nameattr]['default_v'] = '';
                $this->inputs[$nameattr]['value'] = '';
                $st = $v['status'] ? 'Вкл.' : 'Выкл.';
                $f = $v['filter'] ? 'Ф-ДА' : 'Ф-Нет';
                $cart = $v['cartproduct'] ? 'CP-ДА' : 'CP-Нет';
                $label = $v['title'] . " | $st | $f | $cart | " . $v['alias'];
                $this->inputs[$nameattr]['label'] = $label;
                $this->inputs[$nameattr]['parentclass'] = 'form-group';
                if (!$valnoselected && $v['radio']) {
                    $this->inputs[$nameattr]['select'][0] = 'Не выбрано';
                    if (!isset($this->selected[$k])) {
                        $this->selected[$k][0] = 0;
                    }
                }
                if (isset($this->selected[$k])) {
                    if ($v['radio']) {
                        $this->inputs[$nameattr]['value'] = current($this->selected[$k]);
                    } else {
                        $this->inputs[$nameattr]['selected'] = $this->selected[$k];
                    }
                }
                $this->inputs['tagend_' . $v['alias']] = ['type' => 'tagend', 'default_v' => 'div'];
            }
            foreach ($v['attrs'] as $key => &$val) {
                if ($this->check) {
                    if (!isset($val['on']) && !isset($val['active'])) {
                        unset($this->groups[$k]['attrs'][$key]);
                        continue;
                    }
                    if (!isset($val['on']) && isset($val['active'])) {
                        $val['block'] = true;
                    }
                    if ($v['color'] || $v['sizes']) {
                        $this->valFactTitle[$tip] = $this->valFactTitle[$tip] ?? '';
                        $this->valFactTitle[$tip] .= $val['title'] . ', ';
                    } else {
                        $this->valFactTitle['filter'][$v['title']] = $this->valFactTitle['filter'][$v['title']] ?? '';
                        $this->valFactTitle['filter'][$v['title']] .= $val['title'] . ', ';
                    }
                    $val['active'] = $val['active'] ?? '';
                    $val['block'] = $val['block'] ?? '';
                    $val['on'] = $val['on'] ?? '';
                } elseif ($this->formprocessor && $this->oneproduct && !$v['color'] && !$v['sizes']) {
                    $tit = $val['title'] ?? $val['value'];
//                    if ($v['ropr']) {
//                        $shirochkov = $val['shirochkov'];
//                        $shrper = $val['shrper'];
//                        $vislinz = $val['vislinz'];
//                        $shirlinz = $val['shirlinz'];
//                        $shirdugi = $val['shirdugi'];
//                        $tit .= " | Ш-О $shirochkov | Ш-П $shrper | В-Л $vislinz | Ш-Л $shirlinz | Ш-Д $shirdugi |";
//                    }
                    $this->inputs[$nameattr]['select'][$key] = $tit;
                    if (!$v['tablenpbinary']) {
                        $this->bitvalue[$k][$key] = $val['bitvalue'];
                    }
                }
            }
            $count += count($this->groups[$k]['attrs']);
            $v['count'] = count($this->groups[$k]['attrs']);

            if ($this->check) {
                if (isset($this->valFactTitle[$tip])) {
                    $this->valFactTitle[$tip] = $this->valFactTitle[$tip] ? trim($this->valFactTitle[$tip], ', ') : '';
                }
                if (isset($this->valFactTitle['filter'][$v['title']])) {
                    $this->valFactTitle['filter'][$v['title']] = $this->valFactTitle['filter'][$v['title']] ? trim($this->valFactTitle['filter'][$v['title']], ', ') : '';
                }
            }
            $v['get'] = $v['get'] ?? '';
            if ($this->formprocessor && $this->oneproduct && !$this->check && $v['count'] < 100) {
                if ($v['radio']) {
                    $this->inputs[$nameattr]['type'] = 'radio';
                } else {
                    $this->inputs[$nameattr]['type'] = 'checkbox';
                }
            }
        }
        if ($this->formprocessor && $this->oneproduct && !$this->check) {
            $this->inputs['tagend_filter'] = ['type' => 'tagend', 'default_v' => 'div'];
            $this->inputs['bitvaluedata'] = [
                'name' => 'bitvaluedata',
                'type' => 'hidden',
                'default_v' => '',
                'value' => h(serialize($this->bitvalue)),
            ];
        }

        $this->count = $count;
        $this->countGr = count($this->groups);
        $this->filter = $this->groups;

        return true;
    }

    protected function getFilterLink() {
        if (!$this->filter)
            return false;
        $uriStart = $this->uri;
        $uriStart = Link::getLink($uriStart);

        foreach ($this->filter as $v) {
            $getArr[$v['alias']] = $v['get'];
            if ($v['get']) {
                $uriStart = URL::deleteGET($uriStart, $v['alias']);
            }
        }

        foreach ($this->filter as $k => $v) {
            foreach ($v['attrs'] as $key => $val) {
                $uri = $uriStart;
                $get = $getArr;

                $valuek = $v['color'] || $v['sizes'] ? $val['alias'] : $key;

                if ($this->filter[$k]['attrs'][$key]['active']) {
                    $get[$v['alias']] = str_replace($valuek, '', $get[$v['alias']]);
                    $get[$v['alias']] = str_replace(',,', ',', $get[$v['alias']]);
                    $get[$v['alias']] = trim($get[$v['alias']], ',');
                } else {
                    $get[$v['alias']] = $valuek . ',' . $get[$v['alias']];
                }

                $get[$v['alias']] = trim($get[$v['alias']], ',');
                if (!$get[$v['alias']])
                    unset($get[$v['alias']]);

                foreach ($get as $k1 => $v1) {
                    if ($v1)
                        $uri = URL::addGET($uri, $k1, $v1);
                }
                $get = [];
                $this->filter[$k]['attrs'][$key]['link'] = urldecode($uri);
            }
        }

        return true;
    }

    protected function getPriceJs() {
        /*
          https://github.com/IonDen/ion.rangeSlider
         */

        $block = 'false';
        $pricefrLimit = $this->priceLimit['pricefr'];
        $pricetoLimit = $this->priceLimit['priceto'];

        $pricefr = $pricefrLimit;
        $priceto = $pricetoLimit;


        if ((!$pricefrLimit && !$pricetoLimit)) {
            $block = 'true';
        }

        if (isset($this->valGetTitle['price']['pricefr'])) {
            $pricefr = $this->valGetTitle['price']['pricefr'] ? $this->valGetTitle['price']['pricefr'] : $pricefrLimit;
        }
        if (isset($this->valGetTitle['price']['priceto'])) {
            $priceto = $this->valGetTitle['price']['priceto'] ? $this->valGetTitle['price']['priceto'] : $pricetoLimit;
        }


        if (!$pricefr || !$priceto)
            return false;

        $uriPrice = $this->uri;

        $uriPrice = URL::deleteGET($uriPrice, 'pricefr');
        $uriPrice = URL::deleteGET($uriPrice, 'priceto');
        $uriPrice = URL::addGET($uriPrice, 'pricefr', '@fr@');
        $uriPrice = URL::addGET($uriPrice, 'priceto', '@to@');
        /*
          //$result['uri'] = $uriPrice;
          $this->pricejs['type'] = 'double';
          $this->pricejs['grid'] = "true";
          $this->pricejs['min'] = $pricefr;
          $this->pricejs['max'] = $priceto;
          $this->pricejs['step'] = 100;
          $this->pricejs['onFinish'] = "updateInputsPriceFilter";
          //$result .= " input_values_separator: ','," . PHP_EOL;
          $this->pricejs['block'] = $block ? 'true' : 'false';
          $this->pricejs['prefix'] = $this->currency['symbol_left'];
          $this->pricejs['postfix'] = $this->currency['symbol_right'];


          if ($pricefrLimit < $pricefr)
          $pricefrLimit = $pricefr;
          if ($pricetoLimit < $priceto)
          $pricetoLimit = $priceto;
         */


        $result = "$('#pricefilterslider').ionRangeSlider({" . PHP_EOL;
        $result .= " type: 'double'," . PHP_EOL;
        $result .= " grid: true," . PHP_EOL;
        $result .= " min: $pricefrLimit," . PHP_EOL;
        $result .= " max: $pricetoLimit," . PHP_EOL;
        $result .= " from: $pricefr," . PHP_EOL;
        $result .= " to: $priceto," . PHP_EOL;
        $result .= " step: 100," . PHP_EOL;
        $result .= " onStart: onStartPriceFilter," . PHP_EOL;
        $result .= " onFinish: onFinishPriceFilter," . PHP_EOL;
        $result .= " input_values_separator: ','," . PHP_EOL;
        $result .= " block : $block," . PHP_EOL;
        $result .= " prefix: '{$this->currency['symbol_left']}'," . PHP_EOL;
        $result .= " postfix: '{$this->currency['symbol_right']}'" . PHP_EOL;
        $result .= " });" . PHP_EOL;
        $result .= "var sliderFilterUri = $('#pricefilterslider').data('uri');";

        $this->pricejs['pricefr'] = $pricefr;
        $this->pricejs['priceto'] = $priceto;
        $this->pricejs['block'] = $block;
        $this->pricejs['uri'] = $uriPrice;
        $this->pricejs['pricefrLimit'] = $pricefrLimit;
        $this->pricejs['pricetoLimit'] = $pricetoLimit;

        return $result;
    }

    public function getPatternArray() {
        $result = [];

        if ($this->filter) {
            $sost = false;
            $result['filter'] = '';
            foreach ($this->filter as $v) {
                $result[$v['alias']] = '';
                $filtervalue = '';

                $result['filter_prefix'] = '';
                if ($v['status'] && $v['filter']) {
                    if (empty($v['attrs'])) {
                        continue;
                    }
                    foreach ($v['attrs'] as $val) {
                        $result[$v['alias']] .= $val['title'] . ', ';
                        if ($v['filter'] == '1')
                            $filtervalue .= $val['title'] . ', ';
                    }
                    $result[$v['alias']] = rtrim($result[$v['alias']], ', ');
                    $filtervalue = rtrim($filtervalue, ', ');
                    if ($filtervalue)
                        $result['filter'] .= $v['title'] . ': ' . $filtervalue . '; ';
                }
                if ($result[$v['alias']]) {
                    $result[$v['alias']] = $v['title'] . ': ' . $result[$v['alias']];
                    $result[$v['alias']] = ltrim($result[$v['alias']], ': ');
                    $result[$v['alias'] . '_postfix'] = ', ';
                    $result[$v['alias'] . '_upper'] = mb_strtoupper($result[$v['alias']]);
                    $result[$v['alias'] . '_lower'] = mb_strtolower($result[$v['alias']]);
                    $sost = true;
                } else {
                    $result[$v['alias'] . '_postfix'] = '';
                    $result[$v['alias'] . '_upper'] = '';
                    $result[$v['alias'] . '_lower'] = '';
                }
            }
            if ($sost) {
                $result['filter_prefix'] = 'Характеристики продукта: ';
                $result['filter'] = rtrim($result['filter'], '; ');
                if ($result['filter'])
                    $result['filter'] = '<span class="title-parrams-product">' . $result['filter_prefix'] . '</span>' . mb_strtolower($result['filter']) . '.';
            }
        }

        return $result;
    }

    protected function getTable($tablegrupp_id = 0, $tablenobinary_id = 0, $key_gr = 0, $alias_gr = '') {

        if (!$tablegrupp_id) {
            return false;
        }
        $key_gr = (int) $key_gr;
        $cache = Cache::instance();
        $cachetime = \ishop\App::$app->getProperty('cachetime_filter');

        if (!$tablenobinary_id) {
            $tablenobinary_name = 'attribute_value';
        } else {
//            $tablenobinary = $cache->get("filter_tablenobinarytable", 'filter');
//            if (!$tablenobinary) {
//                $tablenobinary = \R::getAssoc("SELECT * FROM tablenobinary");
//                $cache->set("filter_tablenobinarytable", $tablenobinary, $cachetime, 'filter');
//            }
            $tablenobinary_name = $this->tablenobinary[$tablenobinary_id];
        }

        $tablegrupp = $cache->get("filter_tablegrupptable", 'filter');
        if (!$tablegrupp) {
            $tablegrupp = \R::getAssoc("SELECT * FROM tablegrupp");
            $cache->set("filter_tablegrupptable", $tablegrupp, $cachetime, 'filter');
        }
        $tablegrupp_name = $tablegrupp[$tablegrupp_id];

        $rezult_table = $tablegrupp_name . $tablenobinary_name . $key_gr . $alias_gr;
        $rezult = $cache->get("filter_{$rezult_table}table", 'filter');
        if (!$rezult) {
            //$title = !$tablenobinary_id ? 'value' : 'title';

            $sql_part = "SELECT {$tablenobinary_name}.*, {$tablegrupp_name}.title AS gr_title, {$tablegrupp_name}.alias AS gr_alias, {$tablegrupp_name}.tags AS gr_tags";
            $sql_part .= " FROM {$tablenobinary_name}";
            $sql_part .= " JOIN {$tablegrupp_name} ON {$tablenobinary_name}.tablegr_id = {$tablegrupp_name}.id";

            if ($key_gr) {
                $sql_part .= " WHERE {$tablenobinary_name}.attr_group_id = $key_gr";
            }
            $sql_part .= " ORDER BY {$tablegrupp_name}.sortvalue";
            $data = \R::getAssoc($sql_part);
            $rezult['table'] = $data;

            foreach ($data as $k => $v) {
                if (isset($v['value'])) {
                    $rezult['table'][$k]['title'] = $v['value'];
                }
                $rezult['gr_table_id'][$v['tablegr_id']]['title'] = $v['gr_title'];
                $rezult['gr_table_id'][$v['tablegr_id']]['alias'] = $v['gr_alias'];
                $rezult['gr_table_id'][$v['tablegr_id']]['tags'] = $v['gr_tags'];
                $rezult['gr_table_alias'][$v['gr_alias']]['title'] = $v['gr_title'];
                $rezult['gr_table_alias'][$v['gr_alias']]['alias'] = $v['tablegr_id'];
                $rezult['gr_table_alias'][$v['gr_alias']]['tags'] = $v['gr_tags'];

                if (isset($rezult['id_id'][$v['tablegr_id']])) {
                    $rezult['id_id'][$v['tablegr_id']] .= ',' . $k;
                } else {
                    $rezult['id_id'][$v['tablegr_id']] = $k;
                }
                $rezult['id_id_gr'][$k] = $v['tablegr_id'];
                if (isset($v['alias'])) {
                    if (isset($rezult['alias_alias'][$v['gr_alias']])) {
                        $rezult['alias_alias'][$v['gr_alias']] .= ',' . $v['alias'];
                    } else {
                        $rezult['alias_alias'][$v['gr_alias']] = $v['alias'];
                    }
                    $rezult['alias_alias_gr'][$v['alias']] = $v['gr_alias'];
                }
            }

            $cache->set("filter_{$rezult_table}table", $rezult, $cachetime, 'filter');
        }

        return $rezult;
    }

    public function getFilterGrEnd() {
        if (!App::$app->getProperty('filter_gr_on')) {
            return false;
        }
        $filter = $this->filter;

        foreach ($filter as $k_gr => &$item_gr) {
            if (!$item_gr['tablegrupp']) {
                continue;
            }
            $data = $this->getTable($item_gr['tablegrupp'], $item_gr['tablenpbinary'], $k_gr, $item_gr['alias']);

            $arr = [];

            foreach ($item_gr['attrs'] as $val) {

                if (isset($data['gr_table_id'][$val['tablegr_id']])) {
                    if ($val['on']) {
                        $on[$val['tablegr_id']] = true;
                    }
                    if ($val['active']) {
                        $active[$val['tablegr_id']] = true;
                    }
                    if (!$val['block']) {
                        $block[$val['tablegr_id']] = false;
                    }
                    $arr[$val['tablegr_id']] = $val;
                    $arr[$val['tablegr_id']]['on'] = $on[$val['tablegr_id']] ?? false;
                    $arr[$val['tablegr_id']]['active'] = $active[$val['tablegr_id']] ?? false;
                    $arr[$val['tablegr_id']]['block'] = $block[$val['tablegr_id']] ?? true;
                    $arr[$val['tablegr_id']]['title'] = $data['gr_table_id'][$val['tablegr_id']]['title'];
                    $arr[$val['tablegr_id']]['value'] = $data['gr_table_id'][$val['tablegr_id']]['title'];
                    $arr[$val['tablegr_id']]['alias'] = $data['gr_table_id'][$val['tablegr_id']]['alias'];
                }
            }
            if ($arr) {
                $item_gr['attrs'] = $arr;
            }
        }

        $this->filter = $filter;
    }

    protected function getGetNoGr($tablegrupp_id, $dataGet, $attrs, $key_gr, $alias_gr, $tablenpbinary_id, $alias = false) {
        if (!App::$app->getProperty('filter_gr_on')) {
            return $dataGet;
        }
        $data = $this->getTable($tablegrupp_id, $tablenpbinary_id, $key_gr, $alias_gr);
        $value = '';
        foreach ($dataGet as $v) {
            if ($alias) {
                if (isset($data['alias_alias'][$v])) {
                    $value .= $data['alias_alias'][$v] . ',';
                }
            } else {
                if (isset($data['id_id'][$v])) {
                    $value .= $data['id_id'][$v] . ',';
                }
            }
        }

        $value = rtrim($value, ',');
        $dataGet = explode(',', $value);
        return $dataGet;
    }

    protected function getNameAttrKod($tablegrupp_id, $key_gr, $alias_gr, $tablenpbinary_id, $tablegr_id = 0) {

        $data = $this->getTable($tablegrupp_id, $tablenpbinary_id, $key_gr, $alias_gr);
        $value = $data['gr_table_id'][$tablegr_id]['title'] ?? '';

        return $value;
    }

    protected function getGetAttrTag($tablegrupp_id = 0, $tablenobinary_id = 0, $key_gr = 0, $alias = '', $alias_search = '') {
        $data = $this->getTable($tablegrupp_id, $tablenobinary_id, $key_gr, $alias);

        if (isset($data['gr_table_alias'][$alias_search]['tags'])) {
            return $data['gr_table_alias'][$alias_search]['tags'];
        } else
            return false;
    }

    protected function getGetGrupp($filterGet, $key_gr, $alias = false) {
        if (!App::$app->getProperty('filter_gr_on')) {
            return $filterGet['get'];
        }
        $get = $filterGet['get'];
        $data = $this->getTable($filterGet['tablegrupp'], $filterGet['tablenpbinary'], $key_gr, $filterGet['alias']);
        $get_arr = explode(',', $get);
        $new_get = [];

        foreach ($get_arr as $val) {
            if ($alias) {
                if (isset($data['alias_alias_gr'][$val])) {
                    $new_get[$data['alias_alias_gr'][$val]] = $data['alias_alias_gr'][$val];
                }
            } else {
                if (isset($data['id_id_gr'][$val])) {
                    $new_get[$data['id_id_gr'][$val]] = $data['id_id_gr'][$val];
                }
            }
        }

        if (!$new_get) {
            return false;
        }

        $new_get = implode(',', $new_get);

        return $new_get;
    }

    protected function getFilterCols() {
        if ($this->count_col > 1) {
            if ($this->col_auto) {
                $count_col = (int) ($this->count / $this->count_col);
                $count = 0;
                $i = 1;
                $deval = $this->delen;
                $tempf = [];
            } else {
                foreach ($this->filter as $key => $item) {
                    $tempf[$item['col']][$key] = $item;
                }
            }

            return $tempf;
        }
    }

    public function getFilterYml() {
        $rezult_arr = [];
        $i = 0;
        foreach ($this->filter as $item) {
            if (!$item['yml']) {
                continue;
            }
            $name = $item['title'];
            if ($item['alias'] == 'floor' && $item['count'] > 1) {
                $rezult_arr[$i]['name'] = $name;
                $rezult_arr[$i]['value'] = 'Унисекс';
                $i++;
                continue;
            }
            if ($item['notitle']) {
                foreach ($item['attrs'] as $item_attrs) {
                    $rezult_arr[$i]['name'] = $item_attrs['title'];
                    $rezult_arr[$i]['value'] = 'Есть';
                    $i++;
                }
                continue;
            }
            if ($item['ropr']) {
                $opr = current($item['attrs']);
                $arr_opr = razmOpr($opr['title']);
                if ($arr_opr) {
                    foreach ($arr_opr as $item_opr) {
                        $rezult_arr[$i]['name'] = $item_opr['title'];
                        $rezult_arr[$i]['value'] = $item_opr['value'];
                        $i++;
                    }
                }
                continue;
            }
            foreach ($item['attrs'] as $item_attrs) {
                $rezult_arr[$i]['name'] = $name;
                $rezult_arr[$i]['value'] = $item_attrs['title'];
                $i++;
                if ($item['radio']) {
                    continue;
                }
            }
        }
        return $rezult_arr;
    }

}
