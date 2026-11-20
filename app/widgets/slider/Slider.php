<?php

namespace app\widgets\slider;

use ishop\App;
use ishop\Cache;

class Slider {

    public $js = '';
    public $jscomment = '';
    protected $id;
    protected $sliderHtml = '';
    protected $tpl;
    protected $cache = 3600;
    protected $cacheKey = 'top_slider';
    protected $data = [];

    public function __construct($options = []) {
        $this->tpl = __DIR__ . '/slider_tpl/topslider.php';
        $this->getOptions($options);
        $this->run();
    }

    protected function getOptions($options) {
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    protected function run() {
        if (!$this->id) {
            return false;
        }

        $cache = Cache::instance();
        $this->cache ?? App::$app->getProperty('cachetime_' . $this->cacheKey);
        $this->cache = $this->cache ? $this->cache : 3600;

        $tableslider = $cache->get('slidertable' . $this->id);		
        if (!$tableslider) {
            $tableslider = \R::getRow("SELECT name, js FROM slider WHERE id = ? LIMIT 1", [$this->id]);
            if (!$tableslider)
                return false;
            $cache->set('slidertable' . $this->id, $tableslider, $this->cache, 'slider');
        }
        $this->js = $tableslider['js'];
        $this->jscomment = $tableslider['name'];

		$this->sliderHtml = $cache->get($this->cacheKey);
		
        if (!$this->sliderHtml) {
            $data = \R::getAll("SELECT id, name, img, href, content FROM slidervalue WHERE status = 1 AND slider_id = ? ORDER BY sortvalue", [$this->id]);
           
			$this->sliderHtml = $this->getSliderHtml($data);			
            $cache->set($this->cacheKey, $this->sliderHtml, $this->cache, 'slider');
        }
    }

    public function __toString() {
        return $this->sliderHtml;
    }

    protected function getSliderHtml($slider) {
        $content = '';
        if ($slider) {
            ob_start();
            require $this->tpl;
            $content = ob_get_clean();
        }
        return $content;
    }

}
