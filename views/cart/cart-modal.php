<?php if(!empty($session['cart'])): ?><!--если не пуста корзина, тогда будем выводить наши товары-->
    <div class="table-responsive"><!--адаптиваня таблица-->
        <table class="table table-hover table-striped"><!--table чтоб красиво была оформлена, table-hover разные строки по разному подсвечивались, table-striped чтоб строки чередовались-->
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>Наименование</th>
                    <th>Кол-во</th>
                    <th>Цена</th>
                    <th><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th><!--glyphicon glyphicon-remove крестик для удаления-->
                </tr>
            </thead>
            <tbody>
            <?php foreach($session['cart'] as $id => $item):?><!--проходимся по массиву cart, $id => $item id товара с информацией-->
                <tr>
                    <td><?= \yii\helpers\Html::img("@web/images/products/{$item['img']}", ['alt' => $item['name'], 'height' => 50]) ?></td>
                    <td><?= $item['name']?></td>
                    <td><?= $item['qty']?></td>
                    <td><?= $item['price']?></td>
                    <td><span data-id="<?= $id?>" class="glyphicon glyphicon-remove text-danger del-item" aria-hidden="true"></span></td><!--text-danger выделение красным, del-item без перезагрузки стр удалять данную позицию, data-id здесь будет храниться номер товара-->
                </tr>
            <?php endforeach?>
                <tr>
                    <td colspan="4">Итого: </td><!--colspan="4" занимает 4 ячейки-->
                    <td><?= $session['cart.qty']?></td>
                </tr>
                <tr>
                    <td colspan="4">На сумму: </td>
                    <td><?= $session['cart.sum']?></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php else: ?><!--в противном случае, выведем сообщение, что корзина пуста-->
    <h3>Корзина пуста</h3>
<?php endif;?>