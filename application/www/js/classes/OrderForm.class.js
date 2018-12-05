'use strict';


var OrderForm = function(){
    this.$form          = $('#order-form');
    this.$meal          = $('#meal');
    this.$mealDetails   = $('#meal-details');
    this.$orderSummary  = $('#order-summary');
    this.$validateOrder = $('#validate-order');

    this.basketSession = new BasketSession();
};

OrderForm.prototype.onAjaxChangeMeal = function(meal){
    var imageUrl;

    imageUrl = getWwwUrl() + '/images/meals/' + meal.Photo;

    this.$mealDetails.children('p').text(meal.Description);
    this.$mealDetails.find('strong').text(formatMoneyAmount(meal.SalePrice));
    this.$mealDetails.children('img').attr('src', imageUrl);

    this.$form.find('input[name=salePrice]').val(meal.SalePrice);
};

OrderForm.prototype.onAjaxClickValidateOrder = function(result){   
    var orderId;

    orderId = JSON.parse(result);

    window.location.assign(
        getRequestUrl() + '/order/payment?id=' + orderId
    );
};

OrderForm.prototype.onAjaxRefreshOrderSummary = function(basketViewHtml){
    this.$orderSummary.html(basketViewHtml);
    if(this.basketSession.isEmpty() == true){
        this.$validateOrder.attr('disabled', true);
    }
    else{
        this.$validateOrder.attr('disabled', false);
    }
};

OrderForm.prototype.onChangeMeal = function(){
    var mealId;
    mealId = this.$meal.val();

    $.getJSON
    (
        getRequestUrl() + '/meal?id=' + mealId,
        this.onAjaxChangeMeal.bind(this)       
    );
};

OrderForm.prototype.onClickRemoveBasketItem = function(event){
    var $button;
    var mealId;

    $button = $(event.currentTarget);
    mealId = $button.data('meal-id');

    this.basketSession.remove(mealId);
    this.refreshOrderSummary();

    event.preventDefault();
};

OrderForm.prototype.onClickValidateOrder = function(){
    var formFields;

    formFields ={
        basketItems : this.basketSession.items
    };

    $.post(
        getRequestUrl() + '/order/validation',      
        formFields,                                
        this.onAjaxClickValidateOrder.bind(this)   
};

OrderForm.prototype.onSubmitForm = function(event){   

    if(this.$form.data('validation-error-count') > 0){
        return;
    }


    this.basketSession.add(
        this.$meal.val(),

        this.$meal.find('option:selected').text(),

        this.$form.find('input[name=quantity]').val(),

        this.$form.find('input[name=salePrice]').val()
    );


    // Mise à jour du récapitulatif de la commande.
    this.refreshOrderSummary();


    this.$form.trigger('reset');                   
    this.$meal.trigger('change');                   
    this.$form.children('.error-message').hide();   



    event.preventDefault();
};

OrderForm.prototype.refreshOrderSummary = function()
{
    var formFields;

    formFields ={
        basketItems : this.basketSession.items
    };


    $.post(
        getRequestUrl() + '/basket',               
        formFields,                                
        this.onAjaxRefreshOrderSummary.bind(this)   
    );
};

OrderForm.prototype.run = function(){

    this.$meal.on('change', this.onChangeMeal.bind(this));

    this.$meal.trigger('change');

    this.$orderSummary.on('click', 'button', this.onClickRemoveBasketItem.bind(this));

    this.$validateOrder.on('click', this.onClickValidateOrder.bind(this));

    this.$form.find('[type=submit]').on('click', this.onSubmitForm.bind(this));

    this.$form.fadeIn('fast');

    this.refreshOrderSummary();
};

OrderForm.prototype.success = function(){
    this.basketSession.clear();
};