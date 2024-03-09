export function toggleResourcesActive() {
    Array.from(document.querySelectorAll("input[type='checkbox']"))
        .filter((i) => i.id.includes("-actice-togglebox"))
        .forEach((i) => {
            i.addEventListener("input", async (e) => {
                let primaryKey = i.dataset.key;
                let primaryValue = i.dataset.value;
                let modelName = i.dataset.modelname;
                let checked = i.checked;
                console.log(primaryValue);
                let prefix = i.dataset.modelname_plural;
                const DATA = { primaryKey, primaryValue, checked };
                await axios
                    .patch(route(prefix + ".active", modelName), DATA).finally(() => window.location.reload())
            });
        });
}
