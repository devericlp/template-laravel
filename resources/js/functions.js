document.addEventListener('alpine:init', () => {
    // Numeric mask. use in the component with the attribute x-mask-numeric
    Alpine.directive('mask-numeric', el => {
        el.addEventListener('input', () => {
            el.value = only_digits(el.value);
        });
    })

    // Validation phone. use in the component with the attribute x-phone-validate
    Alpine.directive('phone-validate', el => {
        el.addEventListener('blur', () => {
            if (only_digits(el.value).length < 10) {
                el.value = '';
            }

        });
    })

    // lowercase filter. use in the component with the attribute x-lowercase
    Alpine.directive('lowercase', el => {
        el.addEventListener('input', () => {
            el.value = el.value.toLowerCase()
        });
    })

    // subdomain filter. use in the component with the attribute x-subdomain
    Alpine.directive('subdomain', el => {
        el.addEventListener('input', () => {
            el.value = el.value.replace(/[^a-zA-Z0-9-]/g, '').toLowerCase();
        });
    })

    // CNPJ filter. use in the component with the attribute x-cnpj
    Alpine.directive('cnpj', el => {
        el.addEventListener('input', () => {
            let numbers = only_digits(el.value).slice(0, 14);

            if (numbers.length === 14) {
                el.value = numbers.replace(
                    /^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/,
                    '$1.$2.$3/$4-$5'
                );
            } else {
                el.value = numbers;
            }
        });
        el.addEventListener('blur', () => {
            if (only_digits(el.value).length < 14) {
                el.value = '';
            }

        });
    })
})

export function only_digits(str) {
    return str.replace(/\D/g, '');
}
