class HS {
    static equipment() {
        document.body.style.display = 'none';
    }
    static getUniqueDeviceIdentifier() {
        var uid = localStorage.getItem('device_uid');
        if(!uid) {
            uid = (new Date()).getTime() + navigator.userAgent;
            localStorage.setItem('device_uid', uid);
        }

        return uid;
    }
}