import AxiosCalls from "./AxiosCalls";

function listUpcomingEvents(firstDate, lastDate) {
    const params = new URLSearchParams();
    params.append('first', firstDate);
    params.append('last', lastDate);
    return AxiosCalls.getRequest('/api/event/upcoming/list', {
        params: params
    })
        .then(({data}) => {
            return data;
        })
        .catch((error) => {
            console.log(error)
        });
}

function showUpcomingEvent() {
    return AxiosCalls.getRequest('/api/event/upcoming/show', {})
        .then(({data}) => {
            return data;
        })
        .catch((error) => {
            console.log(error)
        });
}

function listTopEvents() {
    return AxiosCalls.getRequest('/api/event/topbanner/list', {})
        .then(({data}) => {
            return data;
        })
        .catch((error) => {
            console.log(error)
        });
}

function showRunningEvent() {
    return AxiosCalls.getRequest('/api/event/running/show', {})
        .then(({data}) => {
            return data;
        })
        .catch((error) => {
            console.log(error)
        });
}

function listSectionResults(sectionId) {
    return AxiosCalls.getRequest('/api/section/' + sectionId + '/results/list', {})
        .then(({data}) => {
            return data;
        })
        .catch((error) => {
            console.log(error)
        });
}

function listEventResults(eventId) {
    return AxiosCalls.getRequest('/api/event/'+eventId+'/results/list', {})
        .then(({data}) => {
            return data;
        })
        .catch((error) => {
            console.log(error)
        });
}

function listNewsFeeds() {
    return AxiosCalls.getRequest('/api/document/list', {})
        .then(({data}) => {
            return data;
        })
        .catch((error) => {
            console.log(error)
        });
}

export {
    listUpcomingEvents,
    showUpcomingEvent,
    listTopEvents,
    showRunningEvent,
    listSectionResults,
    listEventResults,
    listNewsFeeds
}
