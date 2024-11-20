class upload_index {

    static run() {
        $('#UserIC').text(this.getCookie());
    }

    /**
     * get Cookie
     * @return COOKIE
     */
    static getCookie() {
        // 访问Cookie
        var cookieArr = document.cookie.split("; ");
        for (var i = 0; i < cookieArr.length; i++) {
            var cookiePair = cookieArr[i].split("=");
            if (cookiePair[0] === 'IC') {
            var CookieData = cookiePair[1];
            }
        }

        return CookieData;
    }
}