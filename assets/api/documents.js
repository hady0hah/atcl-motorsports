import AxiosCalls from "./AxiosCalls";

function listDocumentCategories() {
    return AxiosCalls.getRequest('/api/document/category/list', {})
        .then(({data}) => {
            return data;
        })
        .catch((error) => {
            console.log(error)
        });
}


export {
    listDocumentCategories
}
