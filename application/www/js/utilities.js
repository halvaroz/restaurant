'use strict';

function formatMoneyAmount(amount)
{
    var formatter;

    formatter = new Intl.NumberFormat('fr',
    {
        currency              : 'eur',
        maximumFractionDigits : 2,
        minimumFractionDigits : 2,
        style                 : 'currency'
    });

    return formatter.format(amount);
}

function getRequestUrl()
{
	var requestUrl;

	requestUrl = window.location.href;
	requestUrl = requestUrl.substr(0, requestUrl.indexOf('/index.php') + 10);

	return requestUrl;
}

function getWwwUrl()
{
	var wwwUrl;
	wwwUrl = window.location.href;
	wwwUrl = wwwUrl.substr(0, wwwUrl.indexOf('/index.php')) + '/application/www';

	return wwwUrl;
}

function isInteger(value)
{
    if(isNumber(value) == true)
    {
        if(value % 1 == 0)
        {
            return true;
        }
    }

    return false;
}

function loadDataFromDomStorage(name){
    var jsonData;
    jsonData = window.localStorage.getItem(name);

    return JSON.parse(jsonData);
}

function saveDataToDomStorage(name, data){
    var jsonData;
    jsonData = JSON.stringify(data);

    window.localStorage.setItem(name, jsonData);
}


function isNumber(value){
    if(isNaN(value) == true){
        return false;
    }
    return true;
}