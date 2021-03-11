import AxiosCalls from "./AxiosCalls";

function showDriver(id) {
    return AxiosCalls.getRequest('/api/driver/:id/show'.replace(':id',id), {})
        .then(({data}) => {
            return data.data;
        });
}
export {
    showDriver
}
