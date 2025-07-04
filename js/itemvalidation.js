function validateItemForm() {
    const code = document.getElementById('item_code');
    const name = document.getElementById('item_name');
    const category = document.getElementById('item_category');
    const subcategory = document.getElementById('item_subcategory');
    const quantity = document.getElementById('quantity');
    const price = document.getElementById('unit_price');

    const alphaNumRegex = /^[A-Za-z0-9\s\-_.]{2,50}$/;

    if (!code.value.trim() || !alphaNumRegex.test(code.value)) {
        alert("Item code must be 2-50 characters and alphanumeric.");
        code.focus();
        return false;
    }

    if (!name.value.trim() || !alphaNumRegex.test(name.value)) {
        alert("Item name must be 2-50 characters and alphanumeric.");
        name.focus();
        return false;
    }

    if (!category.value) {
        alert("Select a category.");
        category.focus();
        return false;
    }

    if (!subcategory.value) {
        alert("Select a subcategory.");
        subcategory.focus();
        return false;
    }

    const qtyVal = Number(quantity.value);
    if (!Number.isInteger(qtyVal) || qtyVal < 0) {
        alert("Quantity must be a whole number and not negative.");
        quantity.focus();
        return false;
    }

    const priceVal = Number(price.value);
    if (isNaN(priceVal) || priceVal < 0) {
        alert("Unit price must be a valid number and not negative.");
        price.focus();
        return false;
    }

    return true;
}
