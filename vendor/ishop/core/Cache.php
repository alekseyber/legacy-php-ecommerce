<?php

namespace ishop;

use \Memcached as Memcached;

class Cache
{

    private $memcache_obj;
    private $memcache_on = false;
    private $prefix_key = '';

    use TSingletone;

    protected function __construct()
    {

        if (MEMCACHED) {
            $this->prefix_key = $_SERVER['HTTP_HOST'];
            $sost = false;
            $class = false;
            if (class_exists('Memcached')) {
                @$memcache_obj = new Memcached;
                @$sost = $memcache_obj->addServer(MEMCACHED_SERVER, MEMCACHED_PORT);
                $class = true;
            } else {
                error_log("[" . date('Y-m-d H:i:s') . "] 'Класс Memcached не существует'\n=================\n", 3, ROOT . '/tmp/errors.log');
            }
            if ($sost === false) {
                if ($class) {
                    error_log("[" . date('Y-m-d H:i:s') . "] 'Не установлено соединение Memcached'\n=================\n", 3, ROOT . '/tmp/errors.log');
                }
            } else {
                $this->memcache_obj = $memcache_obj;
                $this->memcache_on = true;
            }
        }
    }

    public function set($key, $data, $seconds = 3600, $label = 'nolabel')
    {
        if ($seconds) {
            $content['data'] = $data;
            $content['end_time'] = time() + $seconds;
            $content['label'] = $label;
            if ($this->memcache_on) {
                $key = $this->greatKey($key);
                $this->memcache_obj->set($key, $content, $content['end_time']);
                return true;
            }
            if (file_put_contents(CACHE . '/' . $label . '_' . md5($key) . '.txt', serialize($content))) {
                return true;
            }
        }
        return false;
    }

    public function get($key, $label = 'nolabel')
    {
        if ($this->memcache_on) {
            $key = $this->greatKey($key);
            $content = $this->memcache_obj->get($key);
            if ($content) {
                if (time() <= $content['end_time']) {
                    return $content['data'];
                } else {
                    $this->memcache_obj->delete($key);
                }
            }
            return false;
        }
        $file = CACHE . '/' . $label . '_' . md5($key) . '.txt';

        if (file_exists($file)) {
            $content = unserialize(file_get_contents($file));
            if (time() <= $content['end_time']) {
                return $content['data'];
            }
            //unlink($file);
            $this->userUnlink($file);
        }
        return false;
    }

    public function delete($key, $label = 'nolabel')
    {
        $rez = true;
        if ($this->memcache_on) {
            $key = $this->greatKey($key);
            $rez = $this->memcache_obj->delete($key);
        }
        $file = CACHE . '/' . $label . '_' . md5($key) . '.txt';
        // if (file_exists($file)) {
        //     unlink($file);
        // }
        $this->userUnlink($file);
        return $rez;
    }

    public function deleteMask($label = 'nolabel')
    {
        $rez = true;
        if ($this->memcache_on) {
            $end_time = time() + 3600;
            $this->memcache_obj->set('test', 'test', $end_time);
            $keys = $this->memcache_obj->getAllKeys();
            if ($keys) {
                $this->memcache_obj->getDelayed($keys);
                $store = $this->memcache_obj->fetchAll();
                if (is_array($store) && $store) {
                    foreach ($store as $item) {
                        $item_label = $item['value']['label'] ?? '';
                        $item_key = $item['key'] ?? '';
                        if ($item_label == $label) {
                            $rez_item = $this->memcache_obj->delete($item_key);
                            if (!$rez_item) {
                                $rez = false;
                            }
                        }
                    }
                }
            }
        }
        $mask = CACHE . '/' . $label . '_*.txt';
        array_map("unlink", glob($mask));
        return $rez;
    }

    public function deleteAllMask()
    {
        $rez = true;
        if ($this->memcache_on) {
            $end_time = time() + 3600;
            $this->memcache_obj->set('test', 'test', $end_time);
            $keys = $this->memcache_obj->getAllKeys();
            $rez = $this->memcache_obj->deleteMulti($keys);
        }
        $mask = CACHE . '/*.txt';
        array_map("unlink", glob($mask));
        return $rez;
    }

    public function getAllKey()
    {
        if ($this->memcache_on) {
            $end_time = time() + 3600;
            $this->memcache_obj->set('test', 'test', $end_time);
            return $this->memcache_obj->getAllKeys();
        }
        return [];
    }

    private function greatKey($key = '')
    {
        // return md5($this->prefix_key . '_' . $key);
        return $this->prefix_key . '_' . $key;
    }
    public function userUnlink($file)
    {
        if (file_exists($file)) {
            $old_er = error_reporting();
            error_reporting($old_er & ~E_WARNING);
            unlink($file);
            error_reporting($old_er);
        }
    }
}
