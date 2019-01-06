<li>
    <a href="<?= \yii\helpers\Url::to(['category/view', 'id' => $category['id']]) ?>">
        <?= $category['name']?>
        <?php if( isset($category['childs']) ): ?><!--если существует в $category элемент 'childs'(род категор)-->
            <span class="badge pull-right"><i class="fa fa-plus"></i></span><!--тогда напротив ссылки ставим +-->
        <?php endif;?>
    </a>
    <?php if( isset($category['childs']) ): ?><!--если существует в $category элемент 'childs'(род категор)-->
        <ul><!--тогда делаем вложенный список-->
            <?= $this->getMenuHtml($category['childs'])?><!--и внутри рекурсивно вызываем метод getMenuHtml, передавая ему уже не всё дерево, а только узел данного дерева $category['childs'], то есть его потомка-->
        </ul>
    <?php endif;?>
</li>