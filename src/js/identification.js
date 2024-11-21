class identification {
    static run() {
        this.verification();
    }

    /**
     * verification login
     * @method
     */
    static verification() {
        // 访问Cookie
        var cookieArr = document.cookie.split("; ");
        for (var i = 0; i < cookieArr.length; i++) {
            var cookiePair = cookieArr[i].split("=");
            if (cookiePair[0] === 'IC') {
                var CookieData = cookiePair[1];
            }
        }

        CookieData === undefined ? this.submit() : this.repeat();
    }

    /**
     * login true
     * @method
     */
    static repeat() {
        $('#submit').click(function() {
            alert('已保存您的凭证，您无需重复提交！');
        })
        window.location = "page/upload/index.html";
    }

    /**
     * submit data
     * @method
     */
    static submit() {
        $('#submit').click(function() {
            // error handling
            function filter() {
                alert('请检查输入的内容！');
                throw new Error('Please check the input!');
            }

            // get parameter
            var UserIC = $('#UserIC').val();
            var dormitoryNumber = $('#dormitoryNumber').val();
            var buildingNumber = $('#buildingNumber').val();
            var dormitoriesNumber = $('#dormitoriesNumber').val();
            var grade = $('#grade').val();
            var classNumber = $('#class').val();
            var AdminIC = $('#AdminIC').val();

            // parameter handling
            UserIC === ''  ? filter() : "true";
            dormitoryNumber === ''  ? filter() : "true";
            buildingNumber === ''  ? filter() : "true";
            classNumber === ''  ? filter() : "true";
            AdminIC === ''  ? filter() : "true";

            // parameter length handling
            UserIC.length >= 4 && UserIC.length <= 6 ? 'true' : filter();
            dormitoryNumber.length >= 4 || dormitoryNumber.length <= 2 ? filter() : 'true';
            buildingNumber.length > 1 ? filter() : 'true';

            // loading Text
            document.getElementById('from_container').style.display = 'none';
            document.getElementById('text').innerHTML  = '提交中，请稍后...';

            // Submit to data base
            $.ajax({
                type: "POST",
                url: "system/index.php?p=identification/login",
                data: {
                    "UserIC": UserIC,
                    "dormitoryNumber": dormitoryNumber,
                    "buildingNumber": buildingNumber,
                    "dormitoriesNumber": dormitoriesNumber,
                    "grade": grade,
                    "classNumber": classNumber,
                    "AdminIC": AdminIC
                },
                success: function(data) {

                    // 后台检测到错误数据
                    function loginError(data) {
                        alert('录入错误！错误原因：' + data);
                        throw new Error('false');
                    }

                    // True
                    var jsonData = JSON.parse(data);

                    alert( jsonData['code'] ? "录入成功！" : loginError(jsonData['msg']));
                    
                    // 设置Cookie
                    var expirationDate = new Date();
                    expirationDate.setDate(expirationDate.getDate() + 365); // 设置一年后过期
                    document.cookie = "IC=" + encodeURIComponent(jsonData['UUID']) + '; expires=' + expirationDate.toUTCString() + "; path=/";
                    
                    // 转跳页面
                    window.location = "page/upload/index.html";
                },
                error: function(jqXHR, textStatus, errorThrown) {   // 错误处理
                    alert('错误！错误代码：' + jqXHR.status + ',错误状态：' + textStatus + ',异常信息：' + errorThrown);
                    document.getElementById('from_container').style.display = 'flex';
                    document.getElementById('text').innerHTML  = '';
                }
            })
        });

        return 'identification->submit();';
    }
    /**
     * filter
     * @method
     */
    static filter() {
        alert('请检查输入的内容！');
        throw new Error('Please check the input!');

        return ;
    }

    /**
     * generate COOKIE
     * @method
     */
    static generateCookie() {
        $.get('system/index.php?p=identification/getUUID',function(data) {
            // 设置Cookie
            var expirationDate = new Date();
            expirationDate.setDate(expirationDate.getDate() + 365); // 设置一年后过期
            document.cookie = "IC=" + encodeURIComponent(data) + '; expires=' + expirationDate.toUTCString() + "; path=/";
        },'json');

        // 访问Cookie
        var cookieArr = document.cookie.split("; ");
        for (var i = 0; i < cookieArr.length; i++) {
            var cookiePair = cookieArr[i].split("=");
            if (cookiePair[0] === 'IC') {
            var CookieData = cookiePair[1];
            }
        }

        console.log(CookieData);
    }

}