const formatMoney = (amount) => '৳' + Number(amount).toFixed(2);

document.querySelectorAll('.qty-input').forEach((input) => {
    input.addEventListener('input', () => {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach((field) => {
            const row = field.closest('.cart-row');
            const line = Number(field.value || 0) * Number(field.dataset.price || 0);
            total += line;
            const lineTotal = row.querySelector('.line-total');
            if (lineTotal) {
                lineTotal.textContent = formatMoney(line);
            }
        });

        const cartTotal = document.querySelector('#cart-total');
        if (cartTotal) {
            cartTotal.textContent = formatMoney(total);
        }
    });
});
