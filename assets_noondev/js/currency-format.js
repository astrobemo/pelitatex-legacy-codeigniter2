var currency = formatCurrency()

function formatCurrency() {
    return {
        rupiah: function (number) {
            return new Intl.NumberFormat(['ban', 'id'], {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            }).format(number);
        },
        removeRupiah: function (number) {
            let raw = number
            raw = raw.toString().replaceAll('.','');
            raw = raw.toString().replace(',','.');
            return raw;
        }
    };
}

var remove_currency = formatRemoveCurrency()

function formatRemoveCurrency() {
    return {
        rupiah: function (number) {
            
        }
    };
}

const currency_type_1 = ['rupiah'];
// var inputCurrency = formatInputCurrency();
function inputCurrency(els, currency){
    els.forEach(el => {
        el.addEventListener("focusin", function(){
            let raw = el.value
            raw = raw.toString().replaceAll('.','');
            raw = raw.toString().replace(',','.');
            el.value = raw;
        })

        el.addEventListener("focusout", function(){
            if (this.value.toString().length > 0) {
                this.value = window.currency.rupiah(this.value)
            }else{
                this.value = '';
            }
        })
    });
}
