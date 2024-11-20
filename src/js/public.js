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

    static getCookie(name) {
        var cookieArr = document.cookie.split("; ");
        for (var i = 0; i < cookieArr.length; i++) {
            var cookiePair = cookieArr[i].split("=");
            if (cookiePair[0] === name) {
            return cookiePair[1];
            }
        }
        return null;
    }
}