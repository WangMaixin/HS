class upload_index {

    static run() {
        $('#UserIC').text(this.getCookie());
        this.getSESSION();
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

    /**
     * get SESSION
     * @return SESSION
     */
    static getSESSION() {
        $.get('../../system/index.php?p=Identification/getSESSION',{'session_name' : 'UUID'},function(data) {
            // ;
            var jsonData = JSON.parse(data);
            $('#UserICSESSION').text(jsonData['UUID']);
        })
    }

    /**
     * get data base UUID
     * @return UUID
     */
    static getDBUUID() {
        // $.post('../../system/index.php?p=Identification/getDBUUID',{"UserIC"})
    }
}