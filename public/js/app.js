const d = document;

const addKeyValueInputFields = (function () {
    let index = 0;
    const newForm = d.getElementById("newForm");

    if (!newForm) {
        return false;
    }

    newForm.classList.remove("is-hidden");

    return function () {
        const keyValueFieldPair = `
            <div class="column is-6">
                <div class="field">
                    <label for="key${index}" class="label text-green has-text-weight-normal">Property</label>
                    <div class="control has-icons-left">
                        <input id="key${index}" name="properties[${index}][key]" type="text" class="input" placeholder="Color">
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <label for="value${index}" class="label text-green has-text-weight-normal">Data</label>
                    <div class="control has-icons-left">
                        <input id="value${index}" name="properties[${index}][value]" type="text" class="input" placeholder="Green">
                    </div>
                </div>
            </div>`;

        newForm.insertAdjacentHTML("beforeend", keyValueFieldPair);

        index++;
    };
})();

function toggleCreateMenu() {
    d.getElementById("createMenu").classList.toggle("is-hidden");
}

async function getProductSelected(elementId, productId) {
    const response = await axios.get("/product/uom/" + productId);
    const unitOfMeasurement = response.data;

    d.getElementById(elementId + "Quantity").innerText = unitOfMeasurement;
    d.getElementById(
        elementId + "Price"
    ).innerText = `Per ${unitOfMeasurement}`;
}

const addPurchaseForm = (function () {
    const purchaseFormGroup = d.getElementsByName("purchaseFormGroup");
    const purchaseFormWrapper = d.getElementById("purchaseFormWrapper");
    let productList = d.getElementById("purchase[0][product_id]");

    if (!purchaseFormWrapper) {
        return false;
    }

    const formLimit = productList.length - 1;

    if (formLimit == 1) {
        d.getElementById("addNewPurchaseForm").remove();
    }

    let index = purchaseFormGroup.length;

    return function () {
        const createPurchaseForm = `
            <div class="has-text-weight-medium has-text-left">
                <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                    Item ${index + 1}
                </span>
            </div>
            <div class="box has-background-white-bis radius-top-0 mb-5">
                <div name="purchaseFormGroup" class="columns is-marginless is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label for="purchase[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="purchase[${index}][product_id]" name="purchase[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                        ${productList.innerHTML}
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-th"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="purchase[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="purchase[${index}][quantity]" name="purchase[${index}][quantity]" type="number" class="input" placeholder="Purchase Quantity">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="purchase[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="purchase[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="purchase[${index}][unit_price]" name="purchase[${index}][unit_price]" type="number" class="input" placeholder="Purchase Price">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="purchase[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        purchaseFormWrapper.insertAdjacentHTML("beforeend", createPurchaseForm);

        let currentSelect = d.getElementById(`purchase[${index}][product_id]`);
        let previousSelect = d.getElementById(
            `purchase[${index - 1}][product_id]`
        );

        for (let j = 0; j < previousSelect.length; j++) {
            if (!previousSelect.options[j].selected)
                previousSelect.options[j].hidden = true;
        }

        for (let i = 0; i < currentSelect.length; i++) {
            if (currentSelect.options[i].value == previousSelect.value)
                currentSelect.remove(i);
        }

        productList = currentSelect;

        index++;

        if (index == formLimit) {
            d.getElementById("addNewPurchaseForm").remove();
            return false;
        }
    };
})();

const addSaleForm = (function () {
    const saleFormGroup = d.getElementsByName("saleFormGroup");
    const saleFormWrapper = d.getElementById("saleFormWrapper");
    const warehouseList = d.getElementById("sale[0][warehouse_id]");
    let productList = d.getElementById("sale[0][product_id]");

    if (!saleFormWrapper) {
        return false;
    }

    const formLimit = productList.length - 1;

    if (formLimit == 1) {
        d.getElementById("addNewSaleForm").remove();
    }

    let index = saleFormGroup.length;

    return function () {
        const createSaleForm = `
            <div class="has-text-weight-medium has-text-left">
                <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                    Item ${index + 1}
                </span>
            </div>
            <div class="box has-background-white-bis radius-top-0 mb-5">
                <div name="saleFormGroup" class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="sale[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="sale[${index}][product_id]" name="sale[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                        ${productList.innerHTML}
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-th"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="sale[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> Warehouse <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="sale[${index}][warehouse_id]" name="sale[${index}][warehouse_id]">
                                        ${warehouseList.innerHTML}
                                    </select>
                                </div>
                                <div class="icon is-small is-left">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="sale[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="sale[${index}][quantity]" name="sale[${index}][quantity]" type="number" class="input" placeholder="Sale Quantity">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="sale[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="sale[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="sale[${index}][unit_price]" name="sale[${index}][unit_price]" type="number" class="input" placeholder="Sale Price">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="sale[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        saleFormWrapper.insertAdjacentHTML("beforeend", createSaleForm);

        let currentSelect = d.getElementById(`sale[${index}][product_id]`);
        let previousSelect = d.getElementById(`sale[${index - 1}][product_id]`);

        for (let j = 0; j < previousSelect.length; j++) {
            if (!previousSelect.options[j].selected)
                previousSelect.options[j].hidden = true;
        }

        for (let i = 0; i < currentSelect.length; i++) {
            if (currentSelect.options[i].value == previousSelect.value)
                currentSelect.remove(i);
        }

        productList = currentSelect;

        index++;

        if (index == formLimit) {
            d.getElementById("addNewSaleForm").remove();
            return false;
        }
    };
})();

function jumpToCurrentPageMenuTitle() {
    let menuTitles = d.getElementsByName("menuTitles");

    let currentMenuTitle = Object.values(menuTitles).filter(
        (menuTitle) => menuTitle.href == location.href
    );

    if (location.pathname.includes("/home") || !currentMenuTitle.length) {
        return;
    }

    currentMenuTitle = currentMenuTitle.pop();
    currentMenuTitle = currentMenuTitle.parentElement.parentElement;

    if (currentMenuTitle.previousElementSibling) {
        currentMenuTitle.previousElementSibling.scrollIntoView();
    } else {
        currentMenuTitle.parentElement.parentElement.previousElementSibling.scrollIntoView();
    }
}

function goToPreviousPage() {
    return history.back();
}

function refreshPage() {
    return location.reload();
}

function openAddToInventoryModal() {
    d.getElementById("addToInventoryModal").classList.toggle("is-active");
}

function showOnHandMerchandise() {
    this.classList.add("is-active");
    d.getElementById("onHand").classList.remove("is-hidden");

    hideHistoryMerchandise();
    hideOutofMerchandise();
}

function showHistoryMerchandise() {
    this.classList.add("is-active");
    d.getElementById("historyMerchandise").classList.remove("is-hidden");

    hideOnHandMerchandise();
}

function showOutofMerchandise() {
    this.classList.add("is-active");
    d.getElementById("outOf").classList.remove("is-hidden");

    hideOnHandMerchandise();
}

function hideOnHandMerchandise() {
    let onHandTab = d.getElementById("onHandTab");
    if (onHandTab) {
        onHandTab.classList.remove("is-active");
        d.getElementById("onHand").classList.add("is-hidden");
    }
}

function hideHistoryMerchandise() {
    let historyTab = d.getElementById("historyTab");
    if (historyTab) {
        historyTab.classList.remove("is-active");
        d.getElementById("historyMerchandise").classList.add("is-hidden");
    }
}

function hideOutofMerchandise() {
    let outOfTab = d.getElementById("outOfTab");
    if (outOfTab) {
        outOfTab.classList.remove("is-active");
        d.getElementById("outOf").classList.add("is-hidden");
    }
}

function disableSaveButton() {
    let saveButton = d.getElementById("saveButton");
    saveButton.classList.add("is-loading");
    saveButton.disabled = true;
}

function openCloseSaleModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to close this sale?",
        text:
            "By clicking 'Yes, Close & Subtract', you are going to close this sale and subtract the products from inventory.",
        buttons: ["Not now", "Yes, Close & Subtract"],
    }).then((willCloseSale) => {
        if (willCloseSale) {
            d.getElementById("formOne").submit();
        }
    });
}

function changeWarehouse() {
    if (this.value == 0) {
        return (location.href = "/merchandises/level");
    }

    location.href = `/merchandises/level/warehouse/${this.value}`;
}

function toggleLeftMenuOnMobile() {
    let menuLeft = d.getElementById("menuLeft");

    menuLeft.classList.toggle("is-hidden-mobile");

    d.getElementById("contentRight").classList.toggle("is-hidden-mobile");

    d.getElementById("burgerMenuBars").classList.toggle("fa-times");

    if (!menuLeft.classList.contains("is-hidden-mobile")) {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
}

const addGdnForm = (function () {
    const gdnFormGroup = d.getElementsByName("gdnFormGroup");
    const gdnFormWrapper = d.getElementById("gdnFormWrapper");
    const productList = d.getElementById("gdn[0][product_id]");
    const warehouseList = d.getElementById("gdn[0][warehouse_id]");
    const formLimit = 10;
    let index = gdnFormGroup.length;

    if (!gdnFormWrapper) {
        return false;
    }

    return function () {
        const createGdnForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="gdnFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="gdn[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="gdn[${index}][product_id]" name="gdn[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
                                    ${productList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-th"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="gdn[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> Warehouse <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="gdn[${index}][warehouse_id]" name="gdn[${index}][warehouse_id]">
                                    ${warehouseList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-warehouse"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <label for="gdn[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="gdn[${index}][quantity]" name="gdn[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="gdn[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="gdn[${index}][description]" class="label text-green has-text-weight-normal">Description <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="gdn[${index}][description]" id="gdn[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        gdnFormWrapper.insertAdjacentHTML("beforeend", createGdnForm);

        index++;

        if (index == formLimit) {
            d.getElementById("addNewGdnForm").remove();
            return false;
        }
    };
})();