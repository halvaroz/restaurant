'use strict';

var FormValidator = function($form){
    this.$form            = $form;
    this.$errorMessage    = $form.find('.error-message');
    this.$totalErrorCount = $form.find('.total-error-count');

    this.totalErrors = null;
};

FormValidator.prototype.checkDataTypes = function(){   
    var errors;

    errors = new Array();

    this.$form.find('[data-type]').each(function(){
        var value;

        value = $(this).val().trim();


        switch($(this).data('type')){
            case 'number':
            if(isNumber(value) == false){
                errors.push({
                    fieldName : $(this).data('name'),
                    message   : 'doit être un nombre'
                });
            }
            break;

            case 'positive-integer':
            if(isInteger(value) == false || value <= 0){
                errors.push({
                    fieldName : $(this).data('name'),
                    message   : 'doit être un nombre entier positif'
                });
            }
            break;
        }
    });

    $.merge(this.totalErrors, errors);
};

FormValidator.prototype.checkMinimumLength = function(){
    var errors;
    errors = new Array();

    this.$form.find('[data-length]').each(function(){
        var minLength;
        var value;


        minLength = $(this).data('length');

        value = $(this).val().trim();

        if(value.length < minLength){
            errors.push({
                fieldName : $(this).data('name'),
                message   : 'doit avoir au moins ' + minLength + ' caractère(s)'
            });
        }
    });

    $.merge(this.totalErrors, errors);
};

FormValidator.prototype.checkRequiredFields = function(){
    var errors;
    errors = new Array();

    this.$form.find('[data-required]').each(function(){
        var value;
        value = $(this).val().trim();

        if(value.length == 0){
            errors.push({
                fieldName : $(this).data('name'),
                message   : 'est requis'
            });
        }
    });

    $.merge(this.totalErrors, errors);
};

FormValidator.prototype.onSubmitForm = function(event){   
    var $errorList;

    $errorList = this.$errorMessage.children('p');
    $errorList.empty();


    this.totalErrors = new Array();

    this.checkRequiredFields();
    this.checkDataTypes();
    this.checkMinimumLength();

    this.$form.data('validation-error-count', this.totalErrors.length);


    if(this.totalErrors.length > 0){
        this.totalErrors.forEach(function(error){
            var message;
            message =
                'Le champ <em><strong>' + error.fieldName +
                '</strong></em> ' + error.message + '.<br>';

            $errorList.append(message);
        });

        this.$totalErrorCount.text(this.totalErrors.length);

        this.$errorMessage.fadeIn('slow');

        event.preventDefault();
    }
};

FormValidator.prototype.run = function(){

    this.$form.find('[type=submit]').on('click', this.onSubmitForm.bind(this));

    if(this.$errorMessage.children('p').text().length > 0){
        this.$errorMessage.fadeIn('slow');
    }
};


