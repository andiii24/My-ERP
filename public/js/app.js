const d = document;

function hideMainMenuScroller() {
    d.getElementById("menuLeft").style.overflow = "hidden";
}

function hideMainMenuScrollerOnMouseOut() {
    this.style.overflow = "hidden";
}

function showMainMenuScrollerOnMouseOver() {
    this.style.overflow = "auto";
}

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

        newForm.innerHTML += keyValueFieldPair;

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
