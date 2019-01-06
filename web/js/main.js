/*price range*/

 $('#sl2').slider();

 $('.catalog').dcAccordion({//для списка с классом .catalog вызываем метод dcAccordion
    speed: 300//скорость разворачивания аккордеона
});

function showCart(cart){
    $('#cart .modal-body').html(cart);//обращаемся к нашему модальному окну(#cart), находим там элемент .modal-body и методом .html в него вставляем ответ(cart)
    $('#cart').modal();//показать модальное окно
}

function getCart(){
    $.ajax({
        url: '/cart/show',//прописать, куда будет вести url
        type: 'GET',//полетит на сервер методом GET
        success: function(res){//в success параметре будем принимать ответ(res)
            if(!res) alert('Ошибка!');/если переменная res это false(пустая строка), тогда выведем ошибку
            showCart(res);//вызовем функцию showCart, который покажет корзину
        },
        error: function(){//если что-то пошло не так, то в параметр error
            alert('Error!');//выведем ошибку
        }
    });
    return false;
}

$('#cart .modal-body').on('click', '.del-item', function(){//на кнопку удаления навешиваем событие клика и пытаемся вызвать функцию
    var id = $(this).data('id');//получаем id товара, который хотим удалить
    $.ajax({
        url: '/cart/del-item',//прописать, куда будет вести url
        data: {id: id},//данные, которые будем передавать(id)
        type: 'GET',//переменая id полетит на сервер методом GET
        success: function(res){//в success параметре будем принимать ответ(res)
            if(!res) alert('Ошибка!');//если переменная res это false(пустая строка), тогда выведем ошибку
            showCart(res);//вызовем функцию showCart, который покажет корзину
        },
        error: function(){//если что-то пошло не так, то в параметр error
            alert('Error!');//выведем ошибку
        }
    });
});

function clearCart(){
    $.ajax({
        url: '/cart/clear',//прописать, куда будет вести url
        type: 'GET',//полетит на сервер методом GET
        success: function(res){//в success параметре будем принимать ответ(res)
            if(!res) alert('Ошибка!');//если переменная res это false(пустая строка), тогда выведем ошибку
            showCart(res);//вызовем функцию showCart
        },
        error: function(){//если что-то пошло не так, то в параметр error
            alert('Error!');//выведем ошибку
        }
    });
}

$('.add-to-cart').on('click', function (e) {//на кнопку добавления в корзину навешиваем событие клика и по этому событию будем выполнять соответствующую функцию
    e.preventDefault();//отменить дефолтное поведение для данной кнопки(переход по ссылке)
    var id = $(this).data('id');//нужно получить переменную id данного товара
        qty = $('#qty').val();
    $.ajax({//формируем ajax запрос
        url: '/cart/add',//прописать, куда будет вести url
        data: {id: id, qty: qty},//данные, которые будем передавать(id)
        type: 'GET',//переменая id полетит на сервер методом GET
        success: function(res){//в success параметре будем принимать ответ(res)
            if(!res) alert('Ошибка!');//если переменная res это false(пустая строка), тогда выведем ошибку
            showCart(res);//вызовем функцию showCart
        },
        error: function(){//если что-то пошло не так, то в параметр error
            alert('Error!');//выведем ошибку
        }
    });
});

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};	
		
/*scroll to top*/

$(document).ready(function(){
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
					//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});
	});
});
