import tippy from "tippy.js";
import * as DH from "../../helpers/document-helpers";
import { route } from "ziggy-js";

const TYPE_FIELD = document.getElementById("type");
const TYPES = [
    "text",
    "number",
    "color",
    "range",
    "image",
    "images",
    "video",
    "videos",
    "file",
    "files",
    "multifield",
    "siblingfield",
    "texteditor",
    "select",
];
const SUB_FIELDS = [
    document.querySelector("#prefix"),
    document.querySelector("#suffix"),
    document.querySelector("#placeholder"),
    document.querySelector("#value"),
    document.querySelector("#max_value"),
    document.querySelector("#min_value"),
    document.querySelector("#step"),
    document.querySelector("#image"),
    document.querySelector("#images"),
    document.querySelector("#image_width"),
    document.querySelector("#image_height"),
    document.querySelector("#url"),
    document.querySelector("#sluggable"),
];

const SUB_FIELDS_OBJECT = {
    prefix: document.querySelector("#prefix"),
    suffix: document.querySelector("#suffix"),
    placeholder: document.querySelector("#placeholder"),
    value: document.querySelector("#value"),
    max_value: document.querySelector("#max_value"),
    min_value: document.querySelector("#min_value"),
    step: document.querySelector("#step"),

    image: document.querySelector("#image"),
    image_width: document.querySelector("#image_width"),
    image_height: document.querySelector("#image_height"),

    images: document.querySelector("#images"),

    url: document.querySelector("#url"),
    sluggable: document.querySelector("#sluggable"),
};

const SUB_FEILDS_TREE = {
    text: [
        SUB_FIELDS_OBJECT.prefix,
        SUB_FIELDS_OBJECT.suffix,
        SUB_FIELDS_OBJECT.placeholder,
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.max_value,
        SUB_FIELDS_OBJECT.url,
        SUB_FIELDS_OBJECT.sluggable,
    ],
    longtext: [
        SUB_FIELDS_OBJECT.prefix,
        SUB_FIELDS_OBJECT.suffix,
        SUB_FIELDS_OBJECT.placeholder,
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.max_value,
    ],
    number: [
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.max_value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.step,
    ],
    range: [
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.max_value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.step,
    ],
    image: [
        SUB_FIELDS_OBJECT.image,
        SUB_FIELDS_OBJECT.image_width,
        SUB_FIELDS_OBJECT.image_height,
    ],
    images: [
        SUB_FIELDS_OBJECT.images,
        SUB_FIELDS_OBJECT.image_width,
        SUB_FIELDS_OBJECT.image_height,
    ],
};

function getType() {
    return TYPE_FIELD.value;
}

function takeActionOnTypes() {
    const TYPE = getType();
    const UPCOMING_FIELDS = getSubFieldsForType(TYPE);
    resetFields(SUB_FIELDS);
    bootFields(UPCOMING_FIELDS);

    if (route().current("fields.edit")) {
        if (TYPE === "image") bootImageContainer();
        else resetImageContainer();

        if (TYPE === "images") bootImagesContainer();
        else resetImagesContainer();
    }
}

function resetFields(fields = []) {
    fields.forEach((field) => {
        let gridItem = field.parentElement;
        while (!gridItem.classList.contains("grid-item"))
            gridItem = gridItem.parentElement;
        gridItem.style.display = "none";
        field.value = null;
    });
}

function bootFields(fields = []) {
    fields.forEach((field) => {
        let gridItem = field.parentElement;
        while (!gridItem.classList.contains("grid-item"))
            gridItem = gridItem.parentElement;
        gridItem.style.display = "block";
        field.value = field.dataset?.value || null;
    });
}

function bootImageContainer() {
    document.querySelector("#image-container").style.display = "block";
}

function resetImageContainer() {
    document.querySelector("#image-container").style.display = "none";
}

function bootImagesContainer() {
    document.querySelector("#images-container").style.display = "block";
}

function resetImagesContainer() {
    document.querySelector("#images-container").style.display = "none";
}

function getSubFieldsForType(type) {
    switch (type.toLowerCase()) {
        case "text":
            return SUB_FEILDS_TREE.text;
            break;
        case "longtext":
            return SUB_FEILDS_TREE.longtext;
            break;
        case "number":
            return SUB_FEILDS_TREE.number;
            break;
        case "range":
            return SUB_FEILDS_TREE.range;
            break;
        case "image":
            return SUB_FEILDS_TREE.image;
            break;
        case "images":
            return SUB_FEILDS_TREE.images;
            break;
        default:
            break;
    }
}

TYPE_FIELD.addEventListener("change", takeActionOnTypes);

const writeToFieldDatasetValue = (field) =>
    (field.dataset.value = field.value.trim());

SUB_FIELDS.forEach((field) =>
    field.addEventListener("input", writeToFieldDatasetValue.bind(null, field))
);

takeActionOnTypes();

if (route().current("fields.edit")) {
    let preifxCannotBeChangedBecauseURLFeatureUsingIt =
        document.querySelector("input[name='prefix']").readOnly &&
        document.querySelector("input[type='checkbox'][name='url']").checked;
    if (preifxCannotBeChangedBecauseURLFeatureUsingIt) {
        tippy(document.querySelector("label[for='prefix']"), {
            content:
                "Alanın önek özelliği, alanın URL özelliği tarafından kullanılıyor. Önek değerini değiştirmek için alanın URL özelliğini devre dışı bırakın.",
        });
    }
}

// PASTE TITLE VALUE TO SLUG FIELD AS SLUG FORMAT
let labelField = document.getElementById("label");
let handlerField = document.getElementById("handler");
labelField.addEventListener("input", function () {
    handlerField.value = DH.transformToSlug(this);
});
labelField.addEventListener(
    "blur",
    () => (handlerField.value = DH.trimGiven(handlerField, "-"))
);
handlerField.addEventListener(
    "blur",
    () => (handlerField.value = DH.trimGiven(handlerField, "-"))
);
