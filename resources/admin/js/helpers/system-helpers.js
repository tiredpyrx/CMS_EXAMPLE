export function toggleResourcesActive() {
    Array.from(document.querySelectorAll("input[type='checkbox']"))
        .filter((i) => i.id.includes("-active-togglebox"))
        .forEach((i) => {
            i.addEventListener("input", async (e) => {
                let primaryKey = i.dataset.key;
                let primaryValue = i.dataset.value;
                let modelName = i.dataset.modelname;
                let checked = i.checked;
                console.log(primaryValue);
                let prefix = i.dataset.modelname_plural;
                const DATA = { primaryKey, primaryValue, checked };
                let parent = i;
                while (parent.nodeName != "TR") parent = parent.parentElement;
                await axios
                    .patch(route(prefix + ".active", modelName), DATA)
                    .then(_ => {
                        toastr.success(
                            `${ucfirst(modelName)} aktif özelliği düzenlendi`
                        );
                        if (!i.checked) parent.classList.add("disabled");
                        else parent.classList.remove("disabled");
                    })
                    .catch((e) => e && location.reload());
            });
        });
}

document.querySelectorAll("input[type='checkbox']").forEach((i) => {
    i.value = i.checked;
    i.addEventListener("click", () => (i.value = new Boolean(i.checked)));
});
