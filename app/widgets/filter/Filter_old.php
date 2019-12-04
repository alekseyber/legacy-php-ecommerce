<?php

namespace app\widgets\filter;

use ishop\Cache;
use ishop\App;
use ishop\libs\URL;
use ishop\Link;

class Filter {

    public $uri;
    public $ids = '';
    public $color = false;
    public $colorSql = '';
    public $valGetTitle = []; //Титлы принятых GET параметров
    public $valFactTitle = []; //Титлы найденых товаров
    public $getPrice = ['pricefr' => 0, 'priceto' => 0];
    public $js = '';
    protected $tpl;
    protected $check;
    protected $status;
    protected $oneproduct;
    protected $groups = [];
    protected $attrs = [];
    protected $colorAttrs = [];
    protected $sizesAttrs = [];
    protected $getAttrs = [];
    protected $clearLink;
    protected $filterGet = [];
    public $filterIds = [];
    protected $filter = '';
    protected $sizesSql = '';
    protected $idsSql = '';
    protected $idsSqlcountGr = 0;
    protected $currency = [];
    protected $pricejs = [];
    protected $priceLimit = [];

//public $valGetTitle = ['color' => '', 'filter' => [], 'price' => ['pricefr' => 0, 'priceto' => 0]]; //Титлы принятых GET параметров
//public $valFactTitle = ['color' => '', 'filter' => [], 'price' => ['pricefr' => 0, 'priceto' => 0], 'count' => ['product' => 0, 'modif' => 0]]; //Титлы найденых товаров


    public function __construct($ids = '', $uri = '', $tpl = '', $check = true, $oneproduct = false, $cartproduct = false, $status = true) {
        $this->tpl = $tpl ?: __DIR__ . '/filter_tpl.php';
        $this->uri = $uri;
        $this->ids = $ids;
        $this->check = $check;
        $this->status = $status;

        $this->oneproduct = $oneproduct;
        $this->cartproduct = $cartproduct;
        $this->currency = App::$app->getProperty('currency');
        $this->run();
    }

    protected function run() {
        $cache = Cache::instance();
        $cachetime = App::$app->getProperty('cachetime_filter');
        $oneproduct = $this->oneproduct ? 'oneproduct' : '';
        $cartproduct = $this->cartproduct ? 'cartproduct' : '';
        $status = $this->status ? 'statustrure' : '';
        $check = $this->check ? 'checktrure' : '';

        $this->groups = $cache->get('filter_group' . $check . $oneproduct . $cartproduct . $status, 'filter');
        if (!$this->groups) {
            $this->groups = $this->getGroups();
            $cache->set('filter_group' . $check . $oneproduct . $cartproduct . $status, $this->groups, $cachetime, 'filter');
        }
        $this->attrs = $cache->get('filter_attrs' . $status, 'filter');
        if (!$this->attrs) {
            $this->attrs = $this->getAttrs();
            $cache->set('filter_attrs' . $status, $this->attrs, $cachetime, 'filter');
        }

        if ($this->check && !$this->oneproduct) {
            $this->uri = !$this->uri ? $this->getParams() : $this->uri;
            $this->ids = $this->getIds();
        }

        $this->filterIds = $this->getFilterIds();

        $this->filter = $this->getFilterEnd();

        if ($this->uri)
            $this->getFilterLink();
        if (!$this->oneproduct) {
            $this->js = $this->getPriceJs();
        }
    }

    public function getHtml() {
        if (!$this->tpl)
            return false;

        ob_start();
        $filter = $this->filter;
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
            //$status = '';
            $cartproduct = $this->cartproduct ? ' AND cartproduct = 1' : '';
            $sql_part = 'WHERE color = 0 AND sizes = 0' . $status . $cartproduct;
        } else {
            $sql_part = $this->check ? 'WHERE filter = 1 AND status = 1' : '';
        }
        $data = \R::getAssoc("SELECT id, alias, title, color, sizes, filter, status, cartproduct FROM attribute_group $sql_part ORDER BY filter, sortvalue");

        return $data;
    }

    protected function getAttrs() {
        $status = $this->status ? 'WHERE status = true' : '';
        $data = \R::getAssoc('SELECT * FROM attribute_value ' . $status . ' ORDER BY sortvalue');

        $attrs = [];
        foreach ($data as $k => $v) {
            //$bitvalue = bindec(decbin(1 << $v['sdvig']));
            //'bitvalue' => $bitvalue,             
            $attrs[$v['attr_group_id']][$k] = ['value' => $v['value'], 'sdvig' => $v['sdvig'], 'status' => $v['status']];
        }

        return $attrs;
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

            $data = \R::getAssoc("SELECT id, $title, alias FROM $tip ORDER BY sortvalue");

            $attrs = [];
            foreach ($data as $k => $v) {
                $attrs['key'][$k] = ['title' => $v['title'], 'alias' => $v['alias']];
                $attrs['alias'][$v['alias']] = ['title' => $v['title'], 'key' => $k];
            }

            $cache->set("filter_{$tip}attrs", $attrs, $cachetime, 'filter');
        }

        $this->$tipparams = $attrs;

        return $attrs;
    }

    protected function getIds() {
        $ids = $this->ids;
        $idsNoPrice = $this->ids;
        $wherePrice = '';
        $this->priceLimit['pricefr'] = 0;
        $this->priceLimit['priceto'] = 0;

//  echo $ids . ' до<br>';

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

//  echo  $sql_partNoPr;
//die;
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
// echo $ids . ' после<br>';
        return $ids;
    }

    protected function getGroupsChek() {
        $status = $this->status ? 'AND attribute_group.status = 1' : '';
        $filter = !$this->oneproduct ? 'AND attribute_group.filter = 1' : '';

        $sql_part = 'SELECT attrgr_id, alias, BIT_OR(bitmask) AS bitmask';
        $sql_part .= ' FROM attribute_product JOIN attribute_group ON attribute_group.id = attribute_product.attrgr_id';
        $sql_part .= " WHERE product_id IN ($this->ids) $filter $status";
        $sql_part .= " GROUP BY attrgr_id";

        //echo $sql_part ;
        $data = \R::getAssoc($sql_part);

        // debug ($data);
        if ($this->oneproduct)
            return $data;

        $sql_part = 'SELECT id FROM attribute_group WHERE color = 1 AND filter = 1 AND status = 1 ORDER BY sortvalue LIMIT 1';
        $colorGroup = \R::getCell($sql_part);

        $colorSelect = '';
        $sizesSelect = '';
        if ($colorGroup) {
            $sql_part = 'SELECT DISTINCT color.id FROM product';
            $sql_part .= ' JOIN modification ON product.id = modification.product_id';
            $sql_part .= ' JOIN color ON modification.color_id = color.id';
            $sql_part .= " WHERE product.id IN ({$this->ids}) AND modification.status = 1";
            $sql_part .= " AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id IN ({$this->ids}) AND amount > 0)";
            $colorSelect = \R::getCol($sql_part);
            if ($colorSelect) {
                $data[$colorGroup]['bitmask'] = $colorSelect;
            }
        }
        $sql_part = 'SELECT id FROM attribute_group WHERE sizes = 1 AND filter = 1  AND status = 1 ORDER BY sortvalue LIMIT 1';
        $sizesGroup = \R::getCell($sql_part);
        $sizesSelect = '';

        if ($sizesGroup) {

            $sql_part = 'SELECT DISTINCT modificationregistr.sizes_id FROM product';
            $sql_part .= ' JOIN modification ON product.id = modification.product_id';
            $sql_part .= ' JOIN modificationregistr ON modification.id = modificationregistr.modification_id';
            $sql_part .= " WHERE product.id IN ({$this->ids})";
            $sql_part .= " AND modificationregistr.id IN (SELECT modificationregistr_id FROM `registr` WHERE product_id IN ({$this->ids}) AND amount > 0)";

            $sizesSelect = \R::getCol($sql_part);
            if ($sizesSelect) {
                $data[$sizesGroup]['bitmask'] = $sizesSelect;
            }
        }
//debug ($data);
        return $data;
    }

    protected function getFilterIds() {

        $filter = [];
        $data = $this->groups;
        //debug ($data);

        $groupscheck = [];
        if ($this->ids) {
            $groupscheck = $this->getGroupsChek();
        }
//debug ($groupscheck);
        foreach ($data as $k => $v) {
            $color = $v['color'];
            $sizes = $v['sizes'];
            $bitmask = '';

            if (isset($groupscheck[$k])) {
                $bitmask = $groupscheck[$k]['bitmask'];
            } else {
                //  if ($this->check)
                continue;
            }
            $filter[$k]['title'] = $v['title'];
            $filter[$k]['alias'] = $v['alias'];
            $filter[$k]['color'] = $color;
            $filter[$k]['sizes'] = $sizes;
            $filter[$k]['filter'] = $v['filter'];
            $filter[$k]['cartproduct'] = $v['cartproduct'];
            $filter[$k]['status'] = $v['status'];
            $filter[$k]['get'] = '';
            $filter[$k]['bitmask'] = $bitmask;

            if ($color || $sizes) {
                $attrs = [];
                $attrs = $this->getColorAttrs($color);

                if (!$this->check) {
                    $filter[$k]['attrs'] = $attrs;
                    continue;
                }
                if ($this->oneproduct) {
                    continue;
                }
                if (is_array($bitmask)) {
                    foreach ($bitmask as $val) {
                        $filter[$k]['attrs'][$val] = [
                            'title' => $attrs['key'][$val]['title'],
                            'alias' => $attrs['key'][$val]['alias'],
                            'active' => '',
                            'link' => '',
                            'block' => ''
                        ];
                    }
                }
            } else {
                //debug($this->attrs);

                if (isset($this->attrs[$k])) {
                    $attrs = $this->attrs[$k];
                } else {
                    continue;
                    //$attrs = [];
                }

                //if ($this->check) {
                //$filter[$k]['attrs'] = $attrs;
                //     continue;
                //  }



                $bitmask = decbin($bitmask);

                foreach ($attrs as $key => $val) {
                    $bitvalue = '';
                    $tempsdvig = '';
                    //  debug ($attrs);

                    $bitvalue = decbin(1 << $val['sdvig']);

//                    echo '==============================<br>';
//                    echo 'iconv_strlen($bitmask): ' . iconv_strlen($bitmask) . ' <br>';
//                    echo 'iconv_strlen($bitvalue): ' . iconv_strlen($bitvalue) . ' <br>';

                    $tempsdvig = iconv_strlen($bitmask) - iconv_strlen($bitvalue);






                    if ($tempsdvig >= 0) {
                        $temp = '';
                        for ($i = 0; $i < $tempsdvig; $i++)
                            $temp .= '0';
                        $bitvalue = $temp . $bitvalue;
                    } else {

                        continue;
                    }
//                    echo '++++++++++++++++++++++++<br>';
//                    echo 'iconv_strlen($bitmask): ' . iconv_strlen($bitmask) . ' <br>';
//                    echo 'iconv_strlen($bitvalue): ' . iconv_strlen($bitvalue) . ' <br>';
//
//                    echo 'bitmask=группы: ' . $bitmask . ' <br>';
//                    echo 'код группы атрибутов: ' . $k . ' <br>';
//                    echo 'код атрибута: ' . $key . ' <br>';
//                    echo 'сдвиг атрибута: : ' . $val['sdvig'] . ' <br>';
//                    echo 'bitvalue атрибута: ' . $bitvalue . ' <br>';
//                    echo 'tempsdvige===' . $tempsdvig . ' <br>';
//                    echo 'bindec($bitmask & $bitvalue)===' . bindec($bitmask & $bitvalue) . ' <br>';
//                    echo '------------------<br><br>';



                    if (bindec($bitmask & $bitvalue)) {
                        $filter[$k]['attrs'][$key] = [
                            'title' => $val['value'],
                            'active' => '',
                            'link' => '',
                            'block' => '',
                            'on' => true,
                        ];
                    } else {
                        continue;
                    }
                }
            }
        }

        return $filter;
    }

    protected function getParams() {
        $getArr = App::$app->getProperty('chek_get');
        $uri = App::$app->getProperty('chek_uri');

        $uri = URL::deletePage($uri);
        $clearLink = Link::getLink($uri);
        $filterGet = [];
        $grups = $this->groups;
//$colorSql = '';
        $idsSql = '';
        $valGetTitle = [];


        foreach ($grups as $k => $v) {
            $color = '';
            $sizes = '';
            $attrs = [];
            if (isset($getArr[$v['alias']])) {
                $val = $getArr[$v['alias']];
                $color = $v['color'];
                $sizes = $v['sizes'];
                $attrs = [];
                $attrs = ($color || $sizes) ? $this->getColorAttrs($color) : $this->attrs[$k];

                $uri = URL::deleteGET($uri, $v['alias']);
                $clearLink = URL::deleteGET($clearLink, $v['alias']);

                $dataGet = [];
                $dataGet = explode(',', $getArr[$v['alias']]);


                $get = '';
                $bitsumm = 0;
                $selectAttrs = [];
                $valGetTitle['filter'][$v['title']] = '';
                foreach ($dataGet as $val) {
                    $tipparams = '';
                    $tip = '';
                    if ($color || $sizes) {
                        $tip = $sizes ? 'sizes' : 'color';
                        $tipparams = $tip . 'Sql';
                        if (!isset($valGetTitle[$tip]))
                            $valGetTitle[$tip] = '';
                        if (isset($attrs['alias'][$val])) {

                            $selectAttrs[$attrs['alias'][$val]['key']] = [
                                'title' => $attrs['key'][$attrs['alias'][$val]['key']]['title'],
                                'alias' => $val,
                                'active' => true,
                                'link' => '',
                                'block' => ''
                            ];
                            $get .= $val . ',';

                            $this->$tipparams .= $attrs['alias'][$val]['key'] . ',';
                            $valGetTitle[$tip] .= $attrs['key'][$attrs['alias'][$val]['key']]['title'] . ', ';
                        }
                    } else {

                        if (isset($attrs[$val])) {
                            $selectAttrs[$val] = [
                                'title' => $attrs[$val]['value'],
                                'active' => true,
                                'link' => '',
                                'block' => ''
                            ];

                            $get .= $val . ',';
                            $bitsumm += (1 << $attrs[$val]['sdvig']);
                            $valGetTitle['filter'][$v['title']] .= $attrs[$val]['value'] . ', ';
                        }
                    }
                }
                if ($selectAttrs) {

                    $get = $get ? rtrim($get, ',') : '';
                    if ($tipparams)
                        $this->$tipparams = $this->$tipparams ? rtrim($this->$tipparams, ',') : '';
                    if ($tip)
                        $valGetTitle[$tip] = $valGetTitle[$tip] ? rtrim($valGetTitle[$tip], ', ') : '';

                    $valGetTitle['filter'][$v['title']] = $valGetTitle['filter'][$v['title']] ? rtrim($valGetTitle['filter'][$v['title']], ', ') : '';
                    if (!$valGetTitle['filter'][$v['title']])
                        unset($valGetTitle['filter'][$v['title']]);
                    $uri = URL::addGET($uri, $v['alias'], $get);
                    if ($bitsumm) {
                        $idsSql .= "(attrgr_id = $k AND bitmask & $bitsumm) OR ";
                        $this->idsSqlcountGr++;
                    }

                    $filterGet[$k]['title'] = $v['title'];
                    $filterGet[$k]['alias'] = $v['alias'];
                    $filterGet[$k]['color'] = $v['color'];
                    $filterGet[$k]['sizes'] = $v['sizes'];
                    $filterGet[$k]['filter'] = $v['filter'];
                    $filterGet[$k]['get'] = $get;
                    $filterGet[$k]['attrs'] = $selectAttrs;
                }
            }
        }
        $this->color = $this->colorSql ? true : false;
        $idsSql = $idsSql ? trim($idsSql, ' OR ') : '';

        foreach ($this->getPrice as $k => $v) {
            $get = 0;
            if (!empty($getArr[$k])) {
                $get = $getArr[$k];
                $uri = URL::deleteGET($uri, $k);
                $clearLink = URL::deleteGET($clearLink, $k);
                if ($get > 0) {
                    $this->getPrice[$k] = $get;
                    $uri = URL::addGET($uri, $k, $get);
                    $valGetTitle['price'][$k] = $get;
                }
            }
        }
        $this->idsSql = $idsSql;
//$this->colorSql = $colorSql;
        $this->valGetTitle = $valGetTitle;
        $this->filterGet = $filterGet;
        $this->clearLink = urldecode($clearLink);

        return urldecode($uri);
    }

    protected function getFilterEnd() {
        $getFilterEnd = [];
        $grups = $this->groups;
        $filterGet = $this->filterGet;
        $filterIds = $this->filterIds;

        if (!$this->check && $this->oneproduct) {

            foreach ($grups as $k => $v) {
                if ($v['color'] || $v['sizes'])
                    continue;
                $getFilterEnd[$k] = $v;
                $getFilterEnd[$k]['attrs'] = [];

                if (isset($this->attrs[$k]))
                    $getFilterEnd[$k]['attrs'] = $this->attrs[$k];
                foreach ($getFilterEnd[$k]['attrs'] as $key => &$val) {
                    $val['on'] = false;
                    if (isset($filterIds[$k]['attrs'][$key]))
                        $val['on'] = true;
                    //echo 'Сдвиг====='.$val['sdvig'].'=====decbin=='. decbin(1 << $val['sdvig']) .'=======iconv_strlen===='. iconv_strlen(decbin(1 << $val['sdvig'])) .'===<br>';

                    $val['bitvalue'] = bindec(decbin(1 << $val['sdvig']));
                }
            }

            return $getFilterEnd;
        }

        foreach ($grups as $k => $v) {
            $attrs = [];
            $attrsSelect = [];

            $color = $v['color'];
            $sizes = $v['sizes'];
            $tip = '';

            $attarsTemp = [];

            if ($color || $sizes) {
                if ($color && $this->colorAttrs)
                    $attrs = $this->colorAttrs['key'];

                if ($sizes && $this->sizesAttrs)
                    $attrs = $this->sizesAttrs['key'];

                $tip = $sizes ? 'sizes' : 'color';
            } else {
                $attrs = [];
                if (isset($this->attrs[$k]))
                    $attrs = $this->attrs[$k];
            }

            if (!$attrs)
                continue;

            $getgr = '';
            foreach ($attrs as $key => $val) {

                if (!isset($filterGet[$k]['attrs'][$key]) && !isset($filterIds[$k]['attrs'][$key]))
                    continue;

                if (isset($filterGet[$k]['attrs'][$key]) && !isset($filterIds[$k]['attrs'][$key])) {
                    $attrsSelect[$key] = $filterGet[$k]['attrs'][$key];
                    $attrsSelect[$key]['block'] = true;
                }
                if (!isset($filterGet[$k]['attrs'][$key]) && isset($filterIds[$k]['attrs'][$key])) {
                    $attrsSelect[$key] = $filterIds[$k]['attrs'][$key];
                }

                if (isset($filterGet[$k]['attrs'][$key]) && isset($filterIds[$k]['attrs'][$key])) {
                    $attrsSelect[$key] = $filterGet[$k]['attrs'][$key];
                }


                if (isset($filterGet[$k]['attrs'][$key]))
                    $getgr = true;

                if (isset($filterIds[$k]['attrs'][$key])) {
                    if ($v['color'] || $v['sizes']) {
                        $tip = $v['sizes'] ? 'sizes' : 'color';
                        if (!isset($this->valFactTitle[$tip]))
                            $this->valFactTitle[$tip] = '';
                        $this->valFactTitle[$tip] .= $attrsSelect[$key]['title'] . ', ';
                    } else {

                        if (isset($this->valFactTitle['filter'][$v['title']])) {
                            $this->valFactTitle['filter'][$v['title']] .= $attrsSelect[$key]['title'] . ', ';
                        } else {
                            $this->valFactTitle['filter'][$v['title']] = $attrsSelect[$key]['title'] . ', ';
                        }
                    }
                }
            }
            if ($attrsSelect) {
                if ($getgr) {
                    $getFilterEnd[$k] = $filterGet[$k];
                } else {
                    $getFilterEnd[$k] = $filterIds[$k];
                }
                $getFilterEnd[$k]['attrs'] = $attrsSelect;
                if (isset($this->valFactTitle[$tip]))
                    $this->valFactTitle[$tip] = $this->valFactTitle[$tip] ? trim($this->valFactTitle[$tip], ', ') : '';

                if (isset($this->valFactTitle['filter'][$v['title']]))
                    $this->valFactTitle['filter'][$v['title']] = $this->valFactTitle['filter'][$v['title']] ? trim($this->valFactTitle['filter'][$v['title']], ', ') : '';
            }
        }

        return $getFilterEnd;
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
//debug($val);
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

        if ($this->filterIds) {
            $sost = false;
            $result['filter'] = '';
            foreach ($this->filterIds as $v) {
                $result[$v['alias']] = '';
                $filtervalue = '';

                $result['filter_prefix'] = '';
                if ($v['status'] && $v['filter']) {
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

}
