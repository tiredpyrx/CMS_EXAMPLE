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
    document.querySelector("#image_width"),
    document.querySelector("#image_height"),
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
};

const SUB_FEILDS_TREE = {
    text: [
        SUB_FIELDS_OBJECT.prefix,
        SUB_FIELDS_OBJECT.suffix,
        SUB_FIELDS_OBJECT.placeholder,
        SUB_FIELDS_OBJECT.value,
        SUB_FIELDS_OBJECT.min_value,
        SUB_FIELDS_OBJECT.max_value,
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
        SUB_FIELDS_OBJECT.image_height
    ],
};

function getType() {
    return TYPE_FIELD.value;
}

function takeActionOnTypes() {
    const UPCOMING_FIELDS = getSubFieldsForType(getType());
    resetFields(SUB_FIELDS);
    bootFields(UPCOMING_FIELDS);
}

function resetFields(fields = []) {
    fields.forEach((field) => {
        let gridItem = field.parentElement;
        while (!gridItem.classList.contains("grid-item"))
            gridItem = gridItem.parentElement;
        gridItem.style.display = "none";
        field.value = field.dataset?.value || null;
    });
}

function bootFields(fields = []) {
    fields.forEach((field) => {
        let gridItem = field.parentElement;
        while (!gridItem.classList.contains("grid-item"))
            gridItem = gridItem.parentElement;
        gridItem.style.display = "block";
    });
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
