<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 07.05.2016
 * Time: 10:35
 */

namespace app\components;
use yii\base\Widget;
use app\models\Category;
use Yii;

class MenuWidget extends Widget{

    public $tpl;
    public $model;
    public $data;//будут храниться все записи категорий из базы данных(массив данных)
    public $tree;//будет храниться результат работы функции, которая будет строить из обычного массива массив дерева, на котором визуально будет видно какая категория будет вложена в какую
    public $menuHtml;//будет храниться готовый html код в зависимости от того шаблона, который сохранится в свойстве tpl(список ul или список select)

    public function init(){//если пользователь параметром передаст $tpl, то оно будет равно тому, что передаст пользователь
        parent::init();
        if( $this->tpl === null ){//если данный параметр не передан
            $this->tpl = 'menu';//тогда по умолчанию присвоим 'menu'
        }
        $this->tpl .= '.php';//и к нему прикрепляем расширение
    }

    public function run(){
        // пытаемся получить нужные нам данные из кеша
        if($this->tpl == 'menu.php'){//кеширование только в пользовательской части
            $menu = Yii::$app->cache->get('menu');// пытаемся получить данные по ключу 'menu'
            if($menu) return $menu;// если что-то получено из кеша, просто возвращаем $menu
        }

        // если не получено, тогда формируем $menu
        $this->data = Category::find()->indexBy('id')->asArray()->all();//в свойство data вернём нужные нам данные из таблицы Category. indexBy - чтобы ключи массива совпадали с идентификатором(id). asArray - в виде массива массивов
        $this->tree = $this->getTree();//в свойстве tree будем хранить наше дерево, возвращаемое методом getTree
        $this->menuHtml = $this->getMenuHtml($this->tree);//в свойство menuHtml вернём результат работы метода getMenuHtml, которому передадим дерево
        //после чего запишем его в кеш
        if($this->tpl == 'menu.php'){//кеширование только в пользовательской части
            Yii::$app->cache->set('menu', $this->menuHtml, 60);//'menu' - ключ, под которым мы запишем, $this->menuHtml - данные, которые хотим туда записать, 60 - время, на которое будет создаваться файл кеша
        }

        return $this->menuHtml;//возвращает готовый html код
    }
//из этого массива получаем дерево. Проходится в цикле по необходимому нам массиву и из обычного одномерного массива строит дерево
    protected function getTree(){
        $tree = [];
        foreach ($this->data as $id=>&$node) {
            if (!$node['parent_id'])
                $tree[$id] = &$node;
            else
                $this->data[$node['parent_id']]['childs'][$node['id']] = &$node;
        }
        return $tree;
    }
//принимает в себя дерево, проходится в цикле по всему дереву и берёт каждый конкретный элемент данного дерева и будет затем передавать его параметром($category)
    protected function getMenuHtml($tree, $tab = ''){//$tab - для первой категории, у которой нет родителя, отступ будет пустой, а для вложенных будет добавляться тире
        $str = '';//создаём пустую переменную $str, в неё будем помещать готовый html код
        foreach ($tree as $category) {//проходимся в цикле по переданному дереву либо в дальнейшем по узлу дерева
            $str .= $this->catToTemplate($category, $tab);// и вызываем метод catToTemplate, передавая ему каждый конкретный элемент дерева
        }
        return $str;//возвращает готовый html код
    }

    protected function catToTemplate($category, $tab){//принимает параметром переданный элемент
        ob_start();//буферизирует вывод, чтоб он не выводился на экран
        include __DIR__ . '/menu_tpl/' . $this->tpl;//и помещает его в шаблон, который подключается здесь
        return ob_get_clean();//возвращает вывод в переменную $str, не выводя при этом его на экран
    }

} 