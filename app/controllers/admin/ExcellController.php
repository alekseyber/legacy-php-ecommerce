<?php

namespace app\controllers\admin;

use ishop\App;
use app\models\Product;
use ishop\libs\URL;
use ishop\Link;

class ExcellController extends AppController {

    public function uploadAction() {
        $onlyugg = $_POST['onlyugg'] ?? false;
        $price = $_POST['price'] ?? false;
        if ($onlyugg !== false) {
            $onlyugg = 1;
        }

        $products = $this->getProducts($onlyugg);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Менеджер')
                ->setLastModifiedBy('Менеджер')
                ->setTitle('Office 2007 XLSX Catalogs')
                ->setSubject('Office 2007 XLSX Catalogs')
                ->setDescription('Test document for Office 2007 XLSX, generated Catalogs.')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('Catalogs result file');


        $cols[0] = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M']; //, , 'N', 'O', 'P', 'Q'
        $cols[1] = ['A', 'B', 'C', 'D', 'E', 'F'];
        $rows[0] = 2;
        $rows[1] = 2;
        $datacols[0] = ['img_thumb', 'model', 'title', 'gender', 'vendor', 'vendorCode', 'product_id', 'id', 'categoryId', 'baselink'];
        $datacols[1] = ['model', 'title', 'product_id', 'id'];

        $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
        $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
        $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
        $conditional1->addCondition('0');
        $conditional1->getStyle()->getFont()->setBold(true);



        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Фото')
                ->setCellValue('B1', 'Наименование')
                ->setCellValue('C1', 'Цвет')
                ->setCellValue('D1', 'Гендер')
                ->setCellValue('E1', 'Вендор')
                ->setCellValue('F1', 'SKU')
                ->setCellValue('G1', 'Product Id')
                ->setCellValue('H1', 'Mod Id')
                ->setCellValue('I1', 'Category Id')
                ->setCellValue('J1', 'Ссылка');

        if ($price) {
            $datacols[0][10] = 'price';
            $datacols[0][11] = 'old_price';
            $spreadsheet->getActiveSheet()
                    ->setCellValue('K1', 'Цена')
                    ->setCellValue('L1', 'Цена без скидки');
        }
        $countdatacols = count($datacols[0]);
        $spreadsheet->getActiveSheet()->setCellValue($cols[0][$countdatacols] . '1', 'Галлерея');
        $spreadsheet->getActiveSheet()->setTitle("Catalog");


        foreach ($cols[0] as $v) {
            $cl = $v . '1';
            $spreadsheet->getActiveSheet()->getStyle($cl)->getFont()->setBold(true);
        }
        $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(20);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1)
                ->setCellValue('A1', 'Наименование')
                ->setCellValue('B1', 'Цвет')
                ->setCellValue('C1', 'Product Id')
                ->setCellValue('D1', 'Mod Id')
                ->setCellValue('E1', 'Номер')
                ->setCellValue('F1', 'Ссылка на фото');
        foreach ($cols[1] as $val) {
            $spreadsheet->getActiveSheet()->getColumnDimension($val)->setAutoSize(true);
        }
        $spreadsheet->getActiveSheet()->setTitle("Gallery_link");

        $countdatacols2 = count($datacols[1]);
        foreach ($cols[1] as $v) {
            $cl = $v . '1';
            $spreadsheet->getActiveSheet()->getStyle($cl)->getFont()->setBold(true);
        }
        $spreadsheet->getActiveSheet()->getRowDimension(1)->setRowHeight(20);

        $spreadsheet->setActiveSheetIndex(0);
        foreach ($products as $product) {

            $i = 0;
            $spreadsheet->getActiveSheet()->getRowDimension($rows[0])->setRowHeight(105);

            foreach ($datacols[0] as $item) {
                $cell = $cols[0][$i] . $rows[0];

                switch ($item) {
                    case 'img_thumb':
                        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $drawing->setName('Foto');
                        $drawing->setDescription('Foto');
                        $drawing->setPath($product[$item]);
                        $drawing->setCoordinates($cell);
                        $drawing->setHeight(100);
                        $drawing->setWorksheet($spreadsheet->getActiveSheet());
                        break;
                    case 'baselink':
                        $spreadsheet->getActiveSheet()->setCellValue($cell, 'Открыть на сайте');
                        $spreadsheet->getActiveSheet()->getCell($cell)->getHyperlink()->setUrl($product[$item]);
                        $spreadsheet->getActiveSheet()->getStyle($cell)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
                        break;
                    default:
                        $spreadsheet->getActiveSheet()->setCellValue($cell, $product[$item]);
                }
                $i++;
            }
            $spreadsheet->setActiveSheetIndex(1);
            $start = $rows[1];
            $q = 1;
            foreach ($product['img'] as $img) {
                $i2 = 0;
                foreach ($datacols[1] as $item1) {
                    $cell2 = $cols[1][$i2] . $rows[1];
                    $spreadsheet->getActiveSheet()->setCellValue($cell2, $product[$item1]);
                    $i2++;
                }

                $spreadsheet->getActiveSheet()->setCellValue($cols[1][$countdatacols2] . $rows[1], $q);
                $cell2 = $cols[1][$countdatacols2 + 1] . $rows[1];
                $spreadsheet->getActiveSheet()->setCellValue($cell2, $img);
                $spreadsheet->getActiveSheet()->getCell($cell2)->getHyperlink()->setUrl($img);
                $spreadsheet->getActiveSheet()->getStyle($cell2)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);
                $rows[1] ++;
                $q++;
            }
            $end = $rows[1] - 1;
            $spreadsheet->setActiveSheetIndex(0);
            $cell = $cols[0][$countdatacols] . $rows[0];
            $spreadsheet->getActiveSheet()->setCellValue($cell, 'Посмотреть');
            $url = "sheet://'Gallery_link'!A" . $start;
            //  "sheet://'Sheetname'!A1"
            $spreadsheet->getActiveSheet()->getCell($cell)->getHyperlink()->setUrl($url);
            $spreadsheet->getActiveSheet()->getStyle($cell)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);


            $rows[0] ++;
        }

        foreach ($cols[0] as $value) {
            if ($value === "A") {
                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            } else {
                $spreadsheet->getActiveSheet()->getColumnDimension($value)->setAutoSize(true);
            }
        }
        $spreadsheet->setActiveSheetIndex(0);
        $cell = $cols[0][$countdatacols] . $rows[0];
        $diapazon = 'A1:' . $cell;
        $spreadsheet->getActiveSheet()->getStyle($diapazon)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="catalog.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    protected function getProducts($brand = false) {
        $sql_part = "SELECT `code` FROM `currency` WHERE `base` = '1'";
        $currencyId = \R::getCell($sql_part);
        $product_ids = Product::getAllProductsIds(false, false, $brand); //, content
        $sql_part = 'SELECT id, title, alias, seolink, sku, price, old_price, color_default, product_model, gender, sizesgroup_id, brand_id, category_id';
        $sql_part .= ' FROM product';
        $sql_part .= " WHERE id IN (" . \R::genSlots($product_ids) . ")";
        $products = \R::getAssoc($sql_part, $product_ids);
        $vendors = \R::getAssoc('SELECT `id`, `title` FROM `brand`');
        foreach ($products as $key => &$product) {

            $product['id'] = $key;
            Product::getColorsProduct($product);

            $product['title'] = $product['title'] . ' ' . $product['gender'];
            if (($product['gender'] == 'Женские') || ($product['gender'] == 'Мужские')) {
                $product['vozrast'] = 'Взрослый';
                $product['gender'] = $product['gender'] == 'Женские' ? 'Женский' : 'Мужской';
            } else if ($product['gender'] == 'Детские') {
                $product['gender'] = 'Унисекс';
                if ($product['sizesgroup_id'] == 5) {
                    $product['vozrast'] = 'Для малышей';
                } else {
                    $product['vozrast'] = 'Детский';
                }
            }
            if (isset($vendors[$product['brand_id']])) {
                $product['brand_title'] = $vendors[$product['brand_id']];
            }
            $product_link = ltrim(Link::getLinkProduct($product), '/');
            $product['baselink'] = PATH . '/' . $product_link;
        }
        $sql_part = 'SELECT modification.id AS modification_id, color.alias, colorya.title AS ytitle, color.title, color.rustitle, price,';
        $sql_part .= ' old_price, color_id, product_id, img FROM modification';
        $sql_part .= ' JOIN color ON modification.color_id = color.id';
        $sql_part .= ' JOIN colorya ON color.tablegr_id = colorya.id';
        $sql_part .= " WHERE modification.status = '1'";
        $sql_part .= " AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id IN (" . \R::genSlots($product_ids) . ") AND amount > 0)";
        $sql_part .= ' ORDER BY modification.product_id, color.sortvalue';
        $modifications = \R::getAssoc($sql_part, $product_ids);
        $path = App::$app->getProperty('img_property');
        $loaddir = WWW . $path['160']['path'];
        //  $mod_ids = [];
        foreach ($modifications as $k => &$modification) {
            //    $mod_ids[] = $k;
            $modification['id'] = $k;
            $modification['baselink'] = URL::addGET($products[$modification['product_id']]['baselink'], 'color', $modification['alias']);
            $gallery = Product::getGallery($k, $modification['img']);
            $modification['price'] = $modification['price'] ? $modification['price'] : $products[$modification['product_id']]['price'];
            $modification['old_price'] = $modification['old_price'] ? $modification['old_price'] : $products[$modification['product_id']]['old_price'];
            $modification['img_thumb'] = $loaddir . $modification['img'];
            $img = [];
            foreach ($gallery as $item) {
                $img[] = PATH . $item['img_main'];
            }
            $modification['img'] = $img;
            $modification['categoryId'] = $products[$modification['product_id']]['category_id'];
            $modification['vendor'] = $products[$modification['product_id']]['brand_title'];
            $modification['vendorCode'] = $products[$modification['product_id']]['sku'];
            $modification['model'] = $products[$modification['product_id']]['title'];
            $modification['gender'] = $products[$modification['product_id']]['gender'];
        }

        return $modifications;
    }

}
