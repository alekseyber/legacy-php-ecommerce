<?php

namespace app\widgets\sitemap;

use ishop\App;

class Sitemap {

    protected $file = WWW . '/sitemap.xml';
    protected $data;

    public function __construct($file = false) {
        if ($file) {
            $this->file = $file;
        }
        $this->run();
    }

    protected function run() {
        $this->getData();
        $this->getSitemap();
        echo "OK";
    }

    protected function getData() {
        $controlers = [
            'main' => [
                'table' => 'lastmodified',
                'changefreq' => 'weekly',
                'priority' => '1.0'
            ],
            'page' => [
                'table' => 'page',
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            'citypage' => [
                'table' => 'citypage',
                'changefreq' => 'daily',
                'priority' => '0.8'
            ],
            'news' => [
                'table' => 'news',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            'category' => [
                'table' => 'category',
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            'product' => [
                'table' => 'product',
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            'comment' => [
                'table' => 'lastmodified',
                'changefreq' => 'daily',
                'priority' => '0.6'
            ],
            'delivery' => [
                'table' => 'lastmodified',
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
            'search' => [
                'table' => 'lastmodified',
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
            'vozvrat' => [
                'table' => 'lastmodified',
                'changefreq' => 'monthly',
                'priority' => '0.3'
            ],
        ];
        $data = [];
        $i = 0;
        foreach ($controlers as $k => $v) {
            $table = $v['table'];
            if ($k == 'main') {
                $data[$i]['loc'] = PATH;
                $lastmod = \R::getCell("SELECT `update_at` FROM $table WHERE `controler` = 'main' LIMIT 1");
                $lastmod = $this->getDat($lastmod);
                $data[$i]['lastmod'] = $lastmod;
                $data[$i]['changefreq'] = $v['changefreq'];
                $data[$i]['priority'] = $v['priority'];
                $i++;
                continue;
            } elseif ($k == 'delivery') {
                $data[$i]['loc'] = PATH . '/delivery';
                $lastmod = \R::getCell("SELECT `update_at` FROM $table WHERE `controler` = 'delivery' LIMIT 1");
                $lastmod = $this->getDat($lastmod);
                $data[$i]['lastmod'] = $lastmod;
                $data[$i]['changefreq'] = $v['changefreq'];
                $data[$i]['priority'] = $v['priority'];
                $i++;
                continue;
            } elseif ($k == 'search') {
                $data[$i]['loc'] = PATH . '/search';
                $lastmod = \R::getCell("SELECT `update_at` FROM $table WHERE `controler` = 'search' LIMIT 1");
                $lastmod = $this->getDat($lastmod);
                $data[$i]['lastmod'] = $lastmod;
                $data[$i]['changefreq'] = $v['changefreq'];
                $data[$i]['priority'] = $v['priority'];
                $i++;
                continue;
            } elseif ($k == 'vozvrat') {
                $data[$i]['loc'] = PATH . '/vozvrat';
                $lastmod = \R::getCell("SELECT `update_at` FROM $table WHERE `controler` = 'vozvrat' LIMIT 1");
                $lastmod = $this->getDat($lastmod);
                $data[$i]['lastmod'] = $lastmod;
                $data[$i]['changefreq'] = $v['changefreq'];
                $data[$i]['priority'] = $v['priority'];
                $i++;
                continue;
            } elseif ($k == 'comment') {
                $data[$i]['loc'] = PATH . '/comment';
                $lastmod = \R::getCell("SELECT `update_at` FROM $table WHERE `controler` = 'comment' LIMIT 1");
                $lastmod = $this->getDat($lastmod);
                $data[$i]['lastmod'] = $lastmod;
                $data[$i]['changefreq'] = $v['changefreq'];
                $data[$i]['priority'] = $v['priority'];
                $i++;
                continue;
            } elseif ($k == 'page') {
                $sql_part = "SELECT `alias`, `seolink`, `update_at` FROM $table WHERE `main` = 0 AND `status` = 1 ORDER BY `update_at` DESC";
            } elseif ($k == 'news') {
                $data[$i]['loc'] = PATH . '/news';
                //$lastmod = \R::getCell("SELECT `update_at` FROM $table WHERE `status` = 1 ORDER BY `update_at` DESC LIMIT 1");
                $lastmod = \R::getCell("SELECT `update_at` FROM lastmodified WHERE `controler` = 'news' LIMIT 1");
                $lastmod = $this->getDat($lastmod);
                $data[$i]['lastmod'] = $lastmod;
                $data[$i]['changefreq'] = 'weekly';
                $data[$i]['priority'] = '0.8';
                $i++;
                $sql_part = "SELECT `alias`, `seolink`, `update_at` FROM $table WHERE `status` = 1 ORDER BY `update_at` DESC";
            } else {
                $sql_part = "SELECT `alias`, `seolink`, `update_at` FROM $table WHERE `status` = 1 ORDER BY `update_at` DESC";
            }
            $list = \R::getAll($sql_part);

            if ($list) {
                foreach ($list as $item) {
                    $data[$i]['loc'] = $this->getLink($k, $item);
                    $lastmod = $this->getDat($item['update_at']);
                    $data[$i]['lastmod'] = $lastmod;
                    $data[$i]['changefreq'] = $v['changefreq'];
                    $data[$i]['priority'] = $v['priority'];
                    $i++;
                }
            }
        }

        $this->data = $data;
    }

    protected function getDat($dat = '') {
        $arr = explode(' ', $dat);
        return $arr[0];
    }

    protected function getLink($k = '', $item = []) {
        if (!$k && $item) {
            return false;
        }
        $seo_link_on = App::$app->getProperty('seo_link_on');
        if ($seo_link_on) {
            return PATH . '/' . $item['seolink'];
        } else {
            return PATH . '/' . $k . '/' . $item['alias'];
        }
    }

    protected function getSitemap() {
        $dom = new \DOMDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
        $dom->formatOutput = true;
        $root = $dom->createElement("urlset"); // Создаём корневой элемент
        $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($root);

        foreach ($this->data as $item) {
            $url = $root->appendChild($dom->createElement('url'));
            foreach ($item as $key => $val) {
                $url->appendChild($dom->createElement($key, $val));
            }
        }
        $dom->save($this->file); // Сохраняем полученный XML-документ в файл
    }

}
