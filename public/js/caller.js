if (d.getElementById("addNewForm")) {
    d.getElementById("addNewForm").addEventListener(
        "click",
        addKeyValueInputFields
    );
}

d.getElementById("createMenuButton").addEventListener(
    "click",
    toggleCreateMenu
);

d.getElementById("createMenu").addEventListener("mouseleave", toggleCreateMenu);

if (d.getElementById("addNewPurchaseForm")) {
    d.getElementById("addNewPurchaseForm").addEventListener(
        "click",
        addPurchaseForm
    );
}

if (d.getElementById("addNewSaleForm")) {
    d.getElementById("addNewSaleForm").addEventListener("click", addSaleForm);
}

d.addEventListener("readystatechange", jumpToCurrentPageMenuTitle);

d.getElementById("backButton").addEventListener("click", goToPreviousPage);

d.getElementById("refreshButton").addEventListener("click", refreshPage);

if (d.getElementById("addToInventoryModal")) {
    d.getElementById("openAddToInventoryModal").addEventListener(
        "click",
        openAddToInventoryModal
    );

    d.getElementById("closeModal").addEventListener(
        "click",
        openAddToInventoryModal
    );

    d.getElementById("addToInventoryNotNow").addEventListener(
        "click",
        openAddToInventoryModal
    );
}

if (d.getElementById("onHandTab")) {
    d.getElementById("onHandTab").addEventListener(
        "click",
        showOnHandMerchandise
    );
    d.getElementById("limitedTab").addEventListener(
        "click",
        showLimitedMerchandise
    );
    d.getElementById("outOfTab").addEventListener(
        "click",
        showOutofMerchandise
    );
}

if (d.getElementById("formOne")) {
    d.getElementById("formOne").addEventListener("submit", disableSaveButton);
}
