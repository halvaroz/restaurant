'use strict';

var BasketSession = function(){
    this.items = null;

    this.load();
};

BasketSession.prototype.add = function(mealId, name, quantity, salePrice){
    var index;

    mealId    = parseInt(mealId);
    quantity  = parseInt(quantity);
    salePrice = parseFloat(salePrice);

    for(index = 0; index < this.items.length; index++){
        if(this.items[index].mealId == mealId){
            this.items[index].quantity += quantity;
            this.save();
            return;
        }
    }

    this.items.push({
        mealId    : mealId,
        name      : name,
        quantity  : quantity,
        salePrice : salePrice
    });

    this.save();
};

BasketSession.prototype.clear = function(){
    saveDataToDomStorage('panier', null);
};

BasketSession.prototype.isEmpty = function(){
    return this.items.length == 0;
};

BasketSession.prototype.load = function(){
    this.items = loadDataFromDomStorage('panier');

    if(this.items == null){
        this.items = new Array();
    }
};

BasketSession.prototype.remove = function(mealId){
    var index;

    for(index = 0; index < this.items.length; index++){
        if(this.items[index].mealId == mealId){

            this.items.splice(index, 1);

            this.save();

            return true;
        }
    }

    return false;
};

BasketSession.prototype.save = function(){
    saveDataToDomStorage('panier', this.items);
};